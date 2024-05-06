<?php
namespace App\Utils;

use Illuminate\Support\Str;

class TokenGenerator
{
    /**
     * Generates a random API token.
     *
     * @return string Hashed token
     */
    public static function generateApiToken()
    {
        $token = Str::random(60);
        return hash('sha256', $token);
    }
}
