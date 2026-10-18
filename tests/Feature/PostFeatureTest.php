<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_view_the_public_post_list_and_post_detail(): void
    {
        $author = User::factory()->create(['name' => 'Post Author']);
        $post = Post::factory()->for($author, 'author')->create([
            'title' => 'A public post',
            'content' => 'Readable post content.',
        ]);

        $this->get(route('posts.index'))
            ->assertOk()
            ->assertSee('A public post')
            ->assertSee('Post Author');

        $this->get(route('posts.show', $post))
            ->assertOk()
            ->assertSee('Readable post content.')
            ->assertSee('Post Author');
    }

    public function test_post_title_and_content_are_escaped_in_the_detail_page(): void
    {
        $post = Post::factory()->create([
            'title' => '<script>alert("title")</script>',
            'content' => '<script>alert("content")</script>',
        ]);

        $this->get(route('posts.show', $post))
            ->assertOk()
            ->assertSee('<script>alert("title")</script>')
            ->assertSee('<script>alert("content")</script>')
            ->assertDontSee('<script>alert("title")</script>', false)
            ->assertDontSee('<script>alert("content")</script>', false);
    }

    public function test_post_list_shows_ten_newest_posts_per_page(): void
    {
        $author = User::factory()->create();

        for ($index = 0; $index < 11; $index++) {
            $createdAt = now()->subMinutes($index);

            Post::factory()->for($author, 'author')->create([
                'title' => "Post {$index}",
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->get(route('posts.index'))
            ->assertOk()
            ->assertSeeInOrder([
                'Post 0',
                'Post 1',
                'Post 2',
                'Post 3',
                'Post 4',
                'Post 5',
                'Post 6',
                'Post 7',
                'Post 8',
                'Post 9',
            ])
            ->assertDontSee('Post 10');

        $this->get(route('posts.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Post 10')
            ->assertDontSee('Post 0');
    }

    public function test_authenticated_user_can_create_a_post_for_themselves(): void
    {
        $author = User::factory()->unverified()->create();
        $anotherUser = User::factory()->create();

        $response = $this->actingAs($author)->post(route('posts.store'), [
            'title' => 'My post',
            'content' => 'New post content.',
            'user_id' => $anotherUser->id,
        ]);

        $response->assertSessionHasNoErrors();

        $post = Post::query()->where('title', 'My post')->sole();

        $this->assertSame($author->id, $post->user_id);
        $this->assertSame($author->id, $post->author->id);
        $response->assertRedirect(route('posts.show', $post));
    }

    public function test_guest_cannot_open_the_post_form_or_create_a_post(): void
    {
        $this->get(route('posts.create'))
            ->assertRedirect(route('login'));

        $this->post(route('posts.store'), [
            'title' => 'Guest post',
            'content' => 'Guests cannot create posts.',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_post_title_and_content_are_required(): void
    {
        $author = User::factory()->create();

        $this->actingAs($author)
            ->post(route('posts.store'), ['title' => '', 'content' => ''])
            ->assertSessionHasErrors(['title', 'content']);

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_post_title_cannot_exceed_255_characters(): void
    {
        $author = User::factory()->create();

        $this->actingAs($author)
            ->post(route('posts.store'), [
                'title' => str_repeat('a', 256),
                'content' => 'Post content.',
            ])
            ->assertSessionHasErrors(['title']);

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_post_title_with_255_characters_is_accepted(): void
    {
        $author = User::factory()->create();

        $this->actingAs($author)
            ->post(route('posts.store'), [
                'title' => str_repeat('a', 255),
                'content' => 'Post content.',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('posts', [
            'user_id' => $author->id,
            'title' => str_repeat('a', 255),
            'content' => 'Post content.',
        ]);
    }

    public function test_authenticated_user_can_update_their_own_post(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->for($author, 'author')->create([
            'title' => 'Original title',
            'content' => 'Original content.',
        ]);

        $this->actingAs($author)
            ->get(route('posts.edit', $post))
            ->assertOk();

        $response = $this->actingAs($author)->put(route('posts.update', $post), [
            'title' => 'Updated title',
            'content' => 'Updated content.',
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('posts.show', $post));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'user_id' => $author->id,
            'title' => 'Updated title',
            'content' => 'Updated content.',
        ]);
    }

    public function test_post_title_and_content_are_required_when_updating(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->for($author, 'author')->create([
            'title' => 'Original title',
            'content' => 'Original content.',
        ]);

        $this->actingAs($author)
            ->put(route('posts.update', $post), ['title' => '', 'content' => ''])
            ->assertSessionHasErrors(['title', 'content']);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Original title',
            'content' => 'Original content.',
        ]);
    }

    public function test_authenticated_user_cannot_update_another_users_post(): void
    {
        $post = Post::factory()->create([
            'title' => 'Original title',
            'content' => 'Original content.',
        ]);

        $anotherUser = User::factory()->create();

        $this->actingAs($anotherUser)
            ->get(route('posts.edit', $post))
            ->assertClientError();

        $this->actingAs($anotherUser)
            ->put(route('posts.update', $post), [
                'title' => 'Changed title',
                'content' => 'Changed content.',
            ])
            ->assertClientError();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Original title',
            'content' => 'Original content.',
        ]);
    }

    public function test_guest_cannot_edit_or_delete_a_post(): void
    {
        $post = Post::factory()->create([
            'title' => 'Protected post',
            'content' => 'Original content.',
        ]);

        $this->get(route('posts.edit', $post))
            ->assertRedirect(route('login'));

        $this->put(route('posts.update', $post), [
            'title' => 'Changed title',
            'content' => 'Changed content.',
        ])->assertRedirect(route('login'));

        $this->delete(route('posts.destroy', $post))
            ->assertRedirect(route('login'));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Protected post',
            'content' => 'Original content.',
        ]);
    }

    public function test_authenticated_user_cannot_delete_another_users_post(): void
    {
        $post = Post::factory()->create([
            'title' => 'Protected post',
            'content' => 'Original content.',
        ]);

        $this->actingAs(User::factory()->create())
            ->delete(route('posts.destroy', $post))
            ->assertClientError();

        $this->assertModelExists($post);
    }

    public function test_authenticated_user_can_delete_their_own_post(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->for($author, 'author')->create();

        $this->actingAs($author)
            ->delete(route('posts.destroy', $post))
            ->assertRedirect(route('posts.index'));

        $this->assertModelMissing($post);
    }

    public function test_my_posts_only_shows_the_authenticated_users_posts(): void
    {
        $author = User::factory()->create();
        $anotherPost = Post::factory()->create(['title' => 'Another users post']);
        Post::factory()->for($author, 'author')->create(['title' => 'My own post']);

        $this->actingAs($author)
            ->get(route('posts.mine'))
            ->assertOk()
            ->assertSee('My own post')
            ->assertDontSee('Another users post');
    }

    public function test_guest_cannot_view_my_posts(): void
    {
        $this->get(route('posts.mine'))
            ->assertRedirect(route('login'));
    }

    public function test_deleting_a_user_account_also_deletes_their_posts(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->for($author, 'author')->create();

        $this->actingAs($author)
            ->delete(route('profile.destroy'), ['password' => 'password'])
            ->assertRedirect('/');

        $this->assertModelMissing($post);
    }
}
