<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            return response('未登录', 401);
        }

        return $next($request);
    }
}
