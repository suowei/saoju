<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class RedirectIfAuthenticated
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if ($this->auth->check()) {
            $user = $this->auth->user();
            return response(['id' => $user->id, 'name' => $user->name], 400);
        }

        return $next($request);
    }
}
