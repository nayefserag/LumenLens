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
            // Verify if 'role_id' exists in the payload
            print_r($payload);
            if (!isset($payload['data']['role_id'])) {
                throw new \Exception('Role ID not found in token payload');
            }
            // Check if the role is admin
            $role = Role::find($payload['data']['role_id']);
            if (!$role || $role->role !== 'admin') {
                throw new \Exception('Unauthorized');
            }
            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
}
