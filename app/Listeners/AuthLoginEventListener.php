<?php

namespace App\Listeners;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;

class AuthLoginEventListener
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(User $user, $remember)
    {
        $user->last_login_at = Carbon::now();
        $user->last_ip = $this->request->ip();
        $user->save();
    }
}
