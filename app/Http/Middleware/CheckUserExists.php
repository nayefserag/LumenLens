<?php

namespace App\Http\Middleware;


use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class CheckUserExists
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
        $email = $request['email'];
        $userExists = User::where('email', $email)->exists();
        if (!$userExists) {
            return response()->json(['error' => 'This Email not found'], 404);
        }
        return $next($request);
    }
}
