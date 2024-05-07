<?php
namespace App\Http\Middleware;

use Closure;
use App\Utils\Token;
use Exception;

class JWTMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        try {
            $decoded = Token::decodeJwt($token);
            $request->auth = $decoded;
        } catch (Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
