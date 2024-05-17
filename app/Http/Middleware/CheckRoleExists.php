<?php

namespace App\Http\Middleware;


use Closure;
use App\Models\Role;
use Illuminate\Http\Request;

class CheckRoleExists
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
        $role = $request['role'];
        $roleExists = Role::where('role', $role)->exists();
        if ($roleExists) {
            return response()->json(['error' => 'This Role already exists'], 404);
        }
        return $next($request);
    }
}
