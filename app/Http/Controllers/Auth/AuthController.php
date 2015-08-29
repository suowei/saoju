<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|email|max:255|unique:users',
            'name' => 'required|max:30|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function inviteRegister(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255|unique:users',
            'name' => 'required|max:30|unique:users',
            'password' => 'required|confirmed|min:6',
            'code' => 'required',
        ]);

        $invitation = DB::table('invitations')->select('id', 'new_user_id', 'code')
            ->where('id', $request->input('invitation'))->first();
        if($invitation->code != $request->input('code'))
            return redirect()->back()->withInput()->withErrors('暗号不对> <');
        if($invitation->new_user_id)
            return redirect()->back()->withInput()->withErrors('该邀请码已被使用');

        Auth::login($this->create($request->all()));

        DB::table('invitations')->where('id', $invitation->id)->update(['new_user_id' => Auth::user()->id]);

        return redirect('/');
    }
}
