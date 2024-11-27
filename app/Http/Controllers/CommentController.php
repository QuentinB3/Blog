<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Return entire list of comments
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'You are not logged in']);
        }

        $comments = Comment::get();
        return response()->json([
            'comments' => $comments
        ]);
    }

    /**
     * Save new comment
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $comment = Comment::create([
            'content' => $data['content'],
            'post_id' => $data['post_id'],
            'user_id' => $request->user()->id
        ]);
        return response()->json($comment);
    }
    /**
     * Return one comment
     *
     * @param string $id
     * @return void
     */
    public function show(string $id)
    {
        $comment = Comment::find($id);
        return response()->json($comment);
    }

    /**
     * Update one comment
     *
     * @param Request $request
     * @param string $id
     * @return void
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $comment = Comment::find($id);

        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->update([
            'content' => $data['content']
        ]);
        return response()->json($comment);
    }

    /**
     * Remove one comment
     *
     * @param Request $request
     * @param string $id
     * @return void
     */
    public function destroy(Request $request, string $id)
    {
        $comment = Post::find($id);

        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted']);
    }
}
