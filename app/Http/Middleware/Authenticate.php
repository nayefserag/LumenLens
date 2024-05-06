<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Log;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth1;
use Illuminate\Support\Facades\Auth;
class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth1 $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('Authorization')) {
            $key = trim($request->header('Authorization'));
            if (str_starts_with($key, 'Bearer ')) {
                $token = substr($key, 7);
                $apiToken = hash('sha256', $token);
                $user = \App\Models\User::where('api_token', $apiToken)->first();
                
                if (!$user) {
                    return response('Unauthorized', 401);
                }

                Auth::login($user);
            }
        }

        return $next($request);
    }
}
