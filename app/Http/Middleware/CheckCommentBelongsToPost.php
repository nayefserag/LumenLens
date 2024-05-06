<?php

namespace App\Http\Middleware;
use Closure;
use App\Models\Post;
use Illuminate\Http\Request;

class CheckCommentBelongsToPost
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
        $commentId = $request->route('comment_id');
        $postId = $request->route('id');

        $post = Post::findOrFail($postId);
        if (in_array($commentId, $post->comment_ids ?? [])) {
            return $next($request);
        } else {
            return response()->json(['error' => 'Comment does not belong to the post'], 404);
        }
    }
}
