<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidateRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $type
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $type)
    {
        $rules = [];

        switch ($type) {
            case 'comment':
                $rules = [
                    'content' => 'required|string|max:255',
                    'author' => 'required|string|max:255',
                ];
                break;
            case 'post':
                $rules = [
                    'title' => 'required|string|max:255',
                    'content' => 'required|string|max:65535',
                    'author' => 'required|string|max:255',
                ];
                break;
            case 'user':
                $rules = [
                    'name' => 'required|max:255',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:6',
                ];
                break;
                case 'role':
                    $rules = [
                        'role' => 'required|max:255',
                    ];
                break;
            default:
                abort(400, 'Unknown request type');
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        return $next($request);
    }
}
