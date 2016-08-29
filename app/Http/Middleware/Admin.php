<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Admin
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if ($this->auth->check()) {
            $user_id = $this->auth->user()->id;
            if($user_id == 1 || $user_id == 71 || $user_id == 31 || $user_id == 20 || $user_id == 78)
                return $next($request);
        }

        return redirect('/');
    }
}
