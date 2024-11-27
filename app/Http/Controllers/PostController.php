<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchPostRequest;
use App\Http\Requests\FilterPostRequest;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{

    /**
     * Get all Posts with search and filter
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'You are not logged in']);
        }

        $posts = Post::query();

        $query = $request->get('q');
        if ($request->get("q")) {
            $posts = Post::where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('content', 'LIKE', "%{$query}%")
                    ->orWhereHas('comments', function ($q) use ($query) {
                        $q->where('content', 'LIKE', "%{$query}%");
                    });
            });
        }
        
        $userId = $request->get('user_id');
        if ($request->get('user_id')) {
            $posts->where('user_id', $userId);
        }

        $posts = $posts->with(['user', 'comments'])
            ->get();
        return response()->json([
            'status' => 'success',
            'posts' => $posts
        ]);
    }

    /**
     * Create a new Post
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $postData = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
        $post = Post::create([
            'title' => $postData['title'],
            'content' => $postData['content'],
            'user_id' => $request->user()->id
        ]);
        return response()->json($post);
    }

    /**
     * Get One Post
     *
     * @param string $id
     * @return void
     */
    public function show(string $id)
    {
        $post = Post::find($id);
        return response()->json($post);
    }
    /**
     * Update one post, only if its the same user
     *
     * @param Request $request
     * @param string $id
     * @return void
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $post = Post::find($id);

        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->update([
            'title' => $data['title'],
            'content' => $data['content']
        ]);
        return response()->json($post);
    }

    /**
     * Remove one Post
     *
     * @param Request $request
     * @param string $id
     * @return void
     */
    public function destroy(Request $request, string $id)
    {
        $post = Post::find($id);

        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();
        return response()->json(['message' => 'Post deleted']);
    }
}
