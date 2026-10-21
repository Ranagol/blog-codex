<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_view_comments_in_oldest_first_order_with_authors(): void
    {
        $post = Post::factory()->create();
        $firstComment = Comment::factory()->for($post)->create([
            'content' => 'First comment.',
            'created_at' => now()->subMinutes(2),
        ]);
        $secondComment = Comment::factory()->for($post)->create([
            'content' => 'Second comment.',
            'created_at' => now()->subMinute(),
        ]);

        $this->get(route('posts.show', $post))
            ->assertOk()
            ->assertSeeInOrder(['First comment.', 'Second comment.'])
            ->assertSee($firstComment->author->name)
            ->assertSee($secondComment->author->name)
            ->assertDontSee('Edit comment')
            ->assertDontSee('Delete comment')
            ->assertSee('Log in');
    }

    public function test_comment_content_is_escaped_and_all_comments_are_displayed_without_pagination(): void
    {
        $post = Post::factory()->create();

        for ($index = 0; $index < 12; $index++) {
            Comment::factory()->for($post)->create([
                'content' => $index === 0 ? '<script>alert("comment")</script>' : "Comment {$index}",
                'created_at' => now()->addSeconds($index),
            ]);
        }

        $this->get(route('posts.show', $post))
            ->assertOk()
            ->assertSee('Comment 11')
            ->assertSee('&lt;script&gt;alert(&quot;comment&quot;)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert("comment")</script>', false);
    }

    public function test_authenticated_user_can_create_a_comment_for_the_post_as_themselves(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create();
        $anotherUser = User::factory()->create();
        $anotherPost = Post::factory()->create();
        $existingCommentsCount = Comment::query()->count();

        $response = $this->actingAs($author)->post(route('posts.comments.store', $post), [
            'content' => 'A new comment.',
            'user_id' => $anotherUser->id,
            'post_id' => $anotherPost->id,
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('posts.show', $post));

        $comment = Comment::query()
            ->where('post_id', $post->id)
            ->where('user_id', $author->id)
            ->where('content', 'A new comment.')
            ->latest('id')
            ->firstOrFail();

        $this->assertSame($post->id, $comment->post_id);
        $this->assertSame($author->id, $comment->user_id);
        $this->assertSame($post->id, $comment->post->id);
        $this->assertSame($author->id, $comment->author->id);
        $this->assertSame($comment->id, $post->comments()->sole()->id);
        $this->assertSame($comment->id, $author->comments()->sole()->id);
        $this->assertDatabaseCount('comments', $existingCommentsCount + 1);
    }

    public function test_guests_cannot_create_edit_or_delete_comments(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create(['content' => 'Keep this comment.']);
        $existingCommentsCount = Comment::query()->count();

        $this->post(route('posts.comments.store', $post), ['content' => 'Guest comment.'])
            ->assertRedirect(route('login'));
        $this->get(route('posts.comments.edit', [$post, $comment]))
            ->assertRedirect(route('login'));
        $this->put(route('posts.comments.update', [$post, $comment]), ['content' => 'Changed.'])
            ->assertRedirect(route('login'));
        $this->delete(route('posts.comments.destroy', [$post, $comment]))
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('comments', $existingCommentsCount);
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Keep this comment.',
        ]);
    }

    public function test_authenticated_user_can_edit_and_delete_their_own_comment(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->for($author, 'author')->create([
            'content' => 'Original comment.',
        ]);

        $this->actingAs($author)
            ->get(route('posts.show', $post))
            ->assertOk()
            ->assertSee('Edit comment')
            ->assertSee('Delete comment');

        $this->actingAs($author)
            ->get(route('posts.comments.edit', [$post, $comment]))
            ->assertOk()
            ->assertSee('Original comment.');

        $this->actingAs($author)
            ->put(route('posts.comments.update', [$post, $comment]), ['content' => 'Updated comment.'])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('posts.show', $post));

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Updated comment.',
            'user_id' => $author->id,
        ]);

        $this->actingAs($author)
            ->delete(route('posts.comments.destroy', [$post, $comment]))
            ->assertRedirect(route('posts.show', $post));

        $this->assertModelMissing($comment);
    }

    public function test_authenticated_user_cannot_edit_or_delete_another_users_comment(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->create(['content' => 'Protected comment.']);
        $existingCommentsCount = Comment::query()->count();

        $this->actingAs(User::factory()->create())
            ->get(route('posts.comments.edit', [$post, $comment]))
            ->assertClientError();

        $this->actingAs(User::factory()->create())
            ->put(route('posts.comments.update', [$post, $comment]), ['content' => 'Changed comment.'])
            ->assertClientError();

        $this->actingAs(User::factory()->create())
            ->delete(route('posts.comments.destroy', [$post, $comment]))
            ->assertClientError();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Protected comment.',
        ]);
        $this->assertDatabaseCount('comments', $existingCommentsCount);
    }

    public function test_comment_cannot_be_accessed_through_a_different_posts_nested_routes(): void
    {
        $comment = Comment::factory()->create(['content' => 'Original comment.']);
        $differentPost = Post::factory()->create();
        $author = $comment->author;

        $this->actingAs($author)
            ->get(route('posts.comments.edit', [$differentPost, $comment]))
            ->assertNotFound();

        $this->actingAs($author)
            ->put(route('posts.comments.update', [$differentPost, $comment]), ['content' => 'Changed comment.'])
            ->assertNotFound();

        $this->actingAs($author)
            ->delete(route('posts.comments.destroy', [$differentPost, $comment]))
            ->assertNotFound();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'post_id' => $comment->post_id,
            'content' => 'Original comment.',
        ]);
    }

    public function test_comment_content_must_be_non_empty_when_creating_or_updating(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->for($author, 'author')->create([
            'content' => 'Original comment.',
        ]);
        $existingCommentsCount = Comment::query()->count();

        $this->actingAs($author)
            ->post(route('posts.comments.store', $post), ['content' => " \t\n"])
            ->assertSessionHasErrors(['content']);

        $this->actingAs($author)
            ->put(route('posts.comments.update', [$post, $comment]), ['content' => ''])
            ->assertSessionHasErrors(['content']);

        $this->assertDatabaseCount('comments', $existingCommentsCount);
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Original comment.',
        ]);
    }

    public function test_comment_content_is_limited_to_500_characters_on_create_and_update(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->for($author, 'author')->create([
            'content' => 'Original comment.',
        ]);
        $content = str_repeat('a', 501);

        $this->actingAs($author)
            ->post(route('posts.comments.store', $post), ['content' => $content])
            ->assertSessionHasErrors([
                'content' => 'The content field must not be greater than 500 characters.',
            ]);

        $this->actingAs($author)
            ->put(route('posts.comments.update', [$post, $comment]), ['content' => $content])
            ->assertSessionHasErrors([
                'content' => 'The content field must not be greater than 500 characters.',
            ]);

        $this->assertDatabaseCount('comments', 1);
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Original comment.',
        ]);
    }

    public function test_comment_content_with_500_characters_is_accepted_on_create_and_update(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->for($post)->for($author, 'author')->create([
            'content' => 'Original comment.',
        ]);
        $createContent = str_repeat('a', 500);
        $updateContent = str_repeat('b', 500);

        $this->actingAs($author)
            ->post(route('posts.comments.store', $post), ['content' => $createContent])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $author->id,
            'content' => $createContent,
        ]);

        $this->actingAs($author)
            ->put(route('posts.comments.update', [$post, $comment]), ['content' => $updateContent])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => $updateContent,
        ]);
    }

    public function test_deleting_a_post_deletes_its_comments(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->for($author, 'author')->create();
        $comment = Comment::factory()->for($post)->create();

        $this->actingAs($author)
            ->delete(route('posts.destroy', $post))
            ->assertRedirect(route('posts.index'));

        $this->assertModelMissing($post);
        $this->assertModelMissing($comment);
    }

    public function test_deleting_a_user_account_deletes_their_comments(): void
    {
        $author = User::factory()->create();
        $comment = Comment::factory()->for($author, 'author')->create();

        $this->actingAs($author)
            ->delete(route('profile.destroy'), ['password' => 'password'])
            ->assertRedirect('/');

        $this->assertModelMissing($comment);
    }
}
