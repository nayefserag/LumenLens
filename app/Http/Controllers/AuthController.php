<?php
namespace App\Http\Controllers;
use App\Utils\TokenGenerator;
use App\Utils\Password;
use App\Models\User;
use Illuminate\Http\Request;

use Laravel\Lumen\Routing\Controller;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request['password'] = Password::hashPassword($request['password']);
        $user = User::create($request->all());

        $remember_token = TokenGenerator::generateApiToken();
        $token = TokenGenerator::generateApiToken();
        
        $user->forceFill(['remember_token' => $remember_token])->save();

        return response()->json(['token' => $token , 'refresh_token' => $remember_token], 201);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request['email'])->first();

        if ($user && Password::checkPassword($request['password'], $user->password)) {
            $token = TokenGenerator::generateApiToken();
            $remember_token = TokenGenerator::generateApiToken();
            $user->forceFill(['remember_token' => $remember_token])->save();
            return response()->json(['token' => $token , 'refresh_token' => $remember_token], 201);
        }

        return response('Credentials do not match', 401);
    }

    public function logout(Request $request)
    {
        $request->user()->forceFill(['remember_token' => null])->save();
        return response('Logged out', 200);
    }
}
