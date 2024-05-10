<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        if ($posts->count() < 1) {
            return response()->json('No posts found!');
        }
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $post = Post::create($request->all());
        return response()->json(['message' => 'Post created successfully!', 'data' => $post], 201);
    }

    public function show($post_id)
    {
        $post = Post::find($post_id);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        return response()->json($post);
    }
    

    public function update(Request $request, $post_id)
    {
        $post = Post::find($post_id);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        $post->update($request->all());
        return response()->json(['message' => 'Post updated successfully!', 'data' => $post], 201);
    }

    public function destroy($post_id)
    {
        $post = Post::findOrFail($post_id);
        $post->delete();
    
        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function restore($post_id)
{
    $post = Post::withTrashed()->findOrFail($post_id);
    if (!$post->trashed()) {
        return response()->json(['message' => 'Post is not in trash']);
    }
    $post->restore();

    return response()->json(['message' => 'Post restored successfully']);
}

}
