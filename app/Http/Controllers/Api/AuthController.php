<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('apiguest', ['except' => 'logout']);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email', 'password' => 'required',
        ]);
        if ($validator->fails())
            return response($validator->messages(), 422);

        $credentials = $this->getCredentials($request);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $user = $request->user();
            return ['id' => $user->id, 'name' => $user->name];
        }

        return response('登录失败> <请检查输入', 422);
    }

    public function logout()
    {
        Auth::logout();

        return "success";
    }

    public function inviteRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:users',
            'name' => 'required|max:30|unique:users',
            'password' => 'required|confirmed|min:6',
            'code' => 'required',
        ]);
        if ($validator->fails())
            return response($validator->messages(), 422);

        $invitation = DB::table('invitations')->select('id', 'new_user_id', 'code')
            ->where('id', $request->input('invitation'))->first();
        if($invitation->code != $request->input('code'))
            return reponse('暗号不对> <', 422);
        if($invitation->new_user_id)
            return response('该邀请码已被使用', 422);

        Auth::login($this->create($request->all()));

        DB::table('invitations')->where('id', $invitation->id)->update(['new_user_id' => Auth::user()->id]);

        $user = $request->user();
        return ['id' => $user->id, 'name' => $user->name];
    }
}
