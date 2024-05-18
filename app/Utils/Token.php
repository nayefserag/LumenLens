<?php
namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use Illuminate\Http\Request;

class Token
{
    private static $secretKey ;
    private static $algorithm ;

    private static $accessTokenExp ;

    private static $refreshTokenExp ;
    
    

    /**
     * Ensures static properties are initialized.
     */
    private static function ensureInitialized()
    {
            self::$secretKey = env('JWT_SECRET');
            self::$algorithm = env('JWT_ALGORITHM');
            self::$accessTokenExp = env('JWT_ACCESS_TOKEN_EXP');
            self::$refreshTokenExp = env('JWT_REFRESH_TOKEN_EXP');
    
    }

    /**
     * Generates a JWT.
     *
     * @param array $data
     * @return string JWT
     */
    public static function generateJwtTokens(array $data): array
    {
        self::ensureInitialized();
        $accessTokenPayload = [
            'data' => $data,
            'iat' => time(),
            'exp' => time() + self::$accessTokenExp,
        ];
        
        // Generate refresh token payload
        $refreshTokenPayload = [
            'data' => $data,
            'iat' => time(),
            'exp' => time() + self::$refreshTokenExp,
        ];
        
        $accessToken = JWT::encode($accessTokenPayload, self::$secretKey, self::$algorithm);
        $refreshToken = JWT::encode($refreshTokenPayload, self::$secretKey, self::$algorithm);
        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }
    

    /**
     * Decodes a JWT.
     *
     * @param string $jwt
     * @return object Decoded JWT payload
     * @throws Exception If the token is invalid
     */
    public static function decodeJwt(string $jwt): object
    {
        self::ensureInitialized();
        try {
            return JWT::decode($jwt, new Key(self::$secretKey, self::$algorithm));
        } catch (Exception $e) {
            throw new Exception('Invalid token: ' . $e->getMessage());
        }
    }

    /**
     * Verifies a JWT.
     *
     * @param string $jwt
     * @return bool Whether the JWT is valid
     */
    public static function verifyJwt(string $jwt): bool
    {
        self::ensureInitialized();
        try {
            self::decodeJwt($jwt);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Extracts the data from the JWT in the request.
     *
     * @param Request $request
     * @return array|null The data from the JWT or null if invalid
     */
    public static function extractData(Request $request): ?array
    {
        self::ensureInitialized();
        $token = $request->bearerToken();
        if ($token && self::verifyJwt($token)) {
            $payload = JWT::decode($token, new Key(self::$secretKey, self::$algorithm));
            return (array) $payload->data;
        }
        return null;
    }
    public static function getValueFromToken(Request $request, $key)
    {
        $token = $request->bearerToken();
        $payload = json_decode(base64_decode(explode('.', $token)[1]), true);
        return $payload['data'][$key];
    }
    
}
