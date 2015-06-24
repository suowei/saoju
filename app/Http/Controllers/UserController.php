<?php namespace App\Http\Controllers;

use App\Review;
use App\User;
use App\Favorite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Auth, DB;

class UserController extends Controller {

    public function show($id)
    {
        $user = User::find($id);
        $favorites = [];
        for($type = 0; $type <= 4; $type++)
        {
            $favorites[$type] = Favorite::with('drama')
                ->where('user_id', $id)->where('type', $type)
                ->orderBy('updated_at', 'desc')->take(6)->get();
        }
        $reviews = Review::with('drama')->where('user_id', $id)->orderBy('created_at', 'desc')->take(6)->get();
        return view('user.show')->withUser($user)->with('favorites', $favorites)->withReviews($reviews);
    }

    public function edit()
    {
        return view('user.edit')->withUser(Auth::user());
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'introduction' => 'max:255',
            'email' => 'required|email|max:255|unique:users,email,'.Auth::id(),
            'name' => 'required|max:30|unique:users,name,'.Auth::id(),
        ]);

        $user = User::find(Auth::id());
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->introduction = $request->input('introduction');
        if ($user->save())
        {
            return redirect()->route('user.edit')->withStatus('修改成功');
        }
        else
        {
            return redirect()->back()->withErrors('修改失败');
        }
    }

    public function editPassword()
    {
        return view('user.editpassword');
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::find(Auth::id());
        if (Hash::check($request->input('old_password'), $user->password))
        {
            $user->password = bcrypt($request->input('password'));
        }
        else
        {
            return redirect()->route('user.editPassword')->withErrors('当前密码错误');
        }
        if ($user->save())
        {
            return redirect()->route('user.editPassword')->withStatus('修改成功');
        }
        else
        {
            return redirect()->route('user.editPassword')->withErrors('修改失败');
        }
    }

    public function favorites(Request $request, $id, $type)
    {
        if($request->input('sort') == 'rating')
        {
            $favorites = Favorite::with('drama')->where('user_id', $id)->where('type', $type)->orderBy('rating', 'desc')->paginate(20);
            return view('user.favorites')->withUser(User::find($id))->with('type', $type)->withFavorites($favorites)->with('sort', 'rating');
        }
        else
        {
            $favorites = Favorite::with('drama')->where('user_id', $id)->where('type', $type)->orderBy('updated_at', 'desc')->paginate(20);
            return view('user.favorites')->withUser(User::find($id))->with('type', $type)->withFavorites($favorites)->with('sort', 'time');
        }
    }

    public function reviews($id)
    {
        $reviews = Review::where('user_id', $id)->paginate(20);
        return view('user.reviews')->withUser(User::find($id))->withReviews($reviews);
    }

}
