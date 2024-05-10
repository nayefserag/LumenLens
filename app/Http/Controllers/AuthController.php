<?php
namespace App\Http\Controllers;
use App\Utils\Token;
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
        $token = Token::generateJwt(['user_id' => $user->id, 'email' => $user->email , 'name' => $user->name]);
        $refresh_token = Token::generateJwt(['user_id' => $user->id, 'email' => $user->email , 'name' => $user->name]);
        $user['refresh_token'] = $refresh_token;
        $user->save();
        return response()->json(['token' => $token , 'refresh_token' => $refresh_token], 201);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request['email'])->first();
        if ($user && Password::checkPassword($request['password'], $user->password)) {
            $token = Token::generateJwt(['user_id' => $user->id, 'email' => $user->email , 'name' => $user->name]);
            $refresh_token = Token::generateJwt(['user_id' => $user->id, 'email' => $user->email , 'name' => $user->name]);
            $user['refresh_token'] = $refresh_token;
            $user->save();
            return response()->json(['token' => $token , 'refresh_token' => $refresh_token], 201);
        }

        return response('Credentials do not match', 401);
    }

    public function logout(Request $request)
    {   
        
        $request->user()->forceFill(['Authorization' => null])->save();
        return response('Logged out', 200);
    }
}
