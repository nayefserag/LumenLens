<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Comment;
use Illuminate\Http\Request;

class CheckCommentExists
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
        $commentExists = Comment::where('id', $commentId)->exists();
        if (!$commentExists) {
            return response()->json(['error' => 'Comment not found'], 404);
        }
        return $next($request);
    }
}
