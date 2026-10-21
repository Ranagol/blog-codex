<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        $comment = $post->comments()->make($request->validated());
        $comment->author()->associate($request->user());
        $comment->save();

        return redirect()->route('posts.show', $post)->with('status', 'Comment added.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post, Comment $comment): View
    {
        Gate::authorize('update', $comment);

        return view('comments.edit', ['comment' => $comment, 'post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Post $post, Comment $comment): RedirectResponse
    {
        $comment->update($request->validated());

        return redirect()->route('posts.show', $post)->with('status', 'Comment updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Comment $comment): RedirectResponse
    {
        Gate::authorize('delete', $comment);
        $comment->delete();

        return redirect()->route('posts.show', $post)->with('status', 'Comment deleted.');
    }
}
