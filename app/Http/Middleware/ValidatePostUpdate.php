<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidatePostUpdate
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
        $rules = [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string|max:65535',
            'author' => 'sometimes|string|max:255'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return $next($request);
    }
}
