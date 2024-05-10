<?php

namespace App\Http\Controllers;
use App\Utils\Token;
use App\Models\Post;
use App\Utils\Password;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
class UserController extends Controller
{
    
    public function me(Request $request)
    {   
        $token = $request->bearerToken();
        $payload = json_decode(base64_decode(explode('.', $token)[1]), true);// will refactor
        $email = $payload['data']['email']; // will refactor
        $user = User::where('email', $email)->first();
        return $user;
    }

    public function update(Request $request)
    {
        $token = $request->bearerToken();
        echo $request->user();
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $payload = json_decode(base64_decode(explode('.', $token)[1]), true);
        $id = $payload['data']['user_id'];

        $user = User::find($id);
        if (!$user) {
            return response()->s(['error' => 'User not found'], 404);
        }
    
        if ($request->has('password')) {
            $user->password = Password::hashPassword($request->password);
        }
    
        $user->update($request->all());
    
        if ($request->has('email')) {

            $request->headers->remove('Authorization');
            return response('Email changed successfully. Please login again.', 200);
        }
    
        return response('User updated successfully', 200);
    }
    

    public function deleteuser(Request $request)
    {
        $token = $request->bearerToken();
        $payload = json_decode(base64_decode(explode('.', $token)[1]), true);// will refactor
        $id = $payload['data']['user_id']; // will refactor
        $user = User::where('id', $id)->first();
        $user->delete();
        $request->headers->remove('Authorization');
        return response()->json('your account deleted successfully',200);
    }
    

    
}