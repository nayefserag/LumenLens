<?php
namespace App\Utils;
use Illuminate\Support\Facades\Hash;
class Password
{
    /**
     * Hash Password
     *
     * @return string Hashed Password
     */
    public static function hashPassword($password)
    {
        $hasedPassword = Hash::make($password);
        return $hasedPassword ;    
    }

    /**
     * Verify Password
     *
     * @return bool
     */
    public static function checkPassword($password, $hashedPassword)
    {
        return Hash::check($password, $hashedPassword);
    }
}
