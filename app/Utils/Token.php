<?php

namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token
{
    private static $secretKey = 'lumen lense';
    private static $algorithm = 'HS256';

    /**
     * Generates a JWT.
     *
     * @return string JWT
     */
    public static function generateJwt($data)
    {
        $payload = [
            'data' => $data,
            // 'iss' => '', 
            'iat' => time(),
            'exp' => time() + 3600, 
            // 'sub' => Str::random(), 
        ];

        return JWT::encode($payload, self::$secretKey, self::$algorithm);
    }

    /**
     * Decodes a JWT.
     *
     * @param string $jwt
     * @return object Decoded JWT payload
     * @throws Exception If the token is invalid
     */
    public static function decodeJwt($jwt)
    {
        return JWT::decode($jwt, new Key(self::$secretKey, self::$algorithm));
    }

    /**
     * Verifies a JWT.
     *
     * @param string $jwt
     * @return bool Whether the JWT is valid
     */
    public static function verifyJwt($jwt)
    {
        try {
            self::decodeJwt($jwt);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public static function extractData($request)
    {
        $token = $request->bearerToken();
        $payload = json_decode(base64_decode(explode('.', $token)[1]), true);
        return $payload['data'];
    }

}
