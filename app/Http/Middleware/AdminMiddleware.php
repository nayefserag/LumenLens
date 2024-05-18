<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $token = str_replace('Bearer ', '', $token);
        try {
            $payload = json_decode(base64_decode(explode('.', $token)[1]), true);
            if (!isset($payload['data']['role_id'])) {
                throw new \Exception('Role ID not found in token payload');
            }
            $role = Role::find($payload['data']['role_id']);
            if (!$role) {
                throw new \Exception('Role not found');
            }
            if (!in_array($role->role, ['admin', 'super_admin'])) {
                throw new \Exception("you are `{$role->role}` only admin can access this route");
            }
            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
}
