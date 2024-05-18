<?php
namespace App\Http\Controllers;
use App\Utils\Token;
use App\Utils\Password;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Laravel\Lumen\Routing\Controller;

class AuthController extends Controller
{
    public  function register(Request $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Password::hashPassword($request->password),
            ]);
            $role =  Role::where('role', 'user')->first();
            $user['role_id'] = $role->id;
            $tokens = Token::generateJwtTokens(['user_id' => $user->id, 'email' => $user->email, 'name' => $user->name , 'role_id' => $role->id]);
            $user->refresh_token = $tokens['refresh_token'];
            $user->save();
            return response()->json(['token' => $tokens['access_token'], 'refresh_token' => $tokens['refresh_token']], 201);
        } catch (\Exception $e) {
            Log::error('User registration failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Registration failed', 'message' => $e->getMessage()], 500);
        }
    }
    

    public function login(Request $request)
    {
        $user = User::where('email', $request['email'])->first();
        if ($user && Password::checkPassword($request['password'], $user->password)) {
            $tokens = Token::generateJwtTokens(['user_id' => $user->id, 'email' => $user->email, 'name' => $user->name, 'role_id' => $user->role_id]);
            $user['refresh_token'] = $tokens['refresh_token'];
            $user->save();
            return response()->json(['token' => $tokens['access_token'] , 'refresh_token' => $tokens['refresh_token']], 201);
        }

        return response('Credentials do not match', 401);
    }

    public function logout(Request $request)
    {   
        
        $request->user()->forceFill(['Authorization' => null])->save();
        return response('Logged out', 200);
    }
}
