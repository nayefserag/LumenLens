<?php
namespace App\Http\Middleware;

use Closure;
use App\Utils\Token;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
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
        } catch (ExpiredException $e) {
            return response()->json(['message' => 'Token has expired'], 401);
        } catch (SignatureInvalidException $e) {
            return response()->json(['message' => 'Invalid token signature'], 401);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
