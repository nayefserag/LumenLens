<?php

namespace App\Http\Controllers;
use App\Utils\Token;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
class UserController extends Controller
{
    
    public function me(Request $request)
    {   
        $token = $request->bearerToken();
        $payload = json_decode(base64_decode(explode('.', $token)[1]), true);
        $email = $payload['data']['email'];
        $user = User::where('email', $email)->first();
        return $user;
    }
    
}