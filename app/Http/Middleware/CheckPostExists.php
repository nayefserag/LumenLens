<?php

namespace App\Http\Middleware;


use Closure;
use App\Models\Post;
use Illuminate\Http\Request;

class CheckPostExists
{
    /**
     * Handle an incoming request.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next)
    {
        $postId = $request->route('post_id');
        $postExists = Post::where('id', $postId)->exists();
        if (!$postExists) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        return $next($request);
    }
}
