<?php namespace App\Http\Controllers;

use App\Epfav;
use App\Review;
use App\Screv;
use App\User;
use App\Favorite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Auth, DB;

class UserController extends Controller {

    public function show($id)
    {
        $user = User::find($id, ['id', 'name', 'introduction', 'reviews',
            'favorite0', 'favorite1', 'favorite2', 'favorite3', 'favorite4',
            'epfav0', 'epfav2', 'epfav4', 'screvs', 'created_at']);
        $epfavs = [];
        for($type = 0; $type <= 4; $type+=2)
        {
            $epfavs[$type] = Epfav::with(['episode' => function($query)
            {
                $query->join('dramas', 'dramas.id', '=', 'episodes.drama_id')
                    ->select('episodes.id as id', 'drama_id', 'dramas.title as drama_title', 'episodes.title as title');
            }])->select('episode_id')->where('user_id', $id)->where('type', $type)->orderBy('updated_at', 'desc')->take(8)->get();
        }
        $favorites = [];
        for($type = 0; $type <= 4; $type++)
        {
            $favorites[$type] = Favorite::with(['drama' => function($query)
            {
                $query->select('id', 'title', 'poster_url');
            }])
                ->where('user_id', $id)->where('type', $type)
                ->orderBy('updated_at', 'desc')->take(6)->get();
        }
        $reviews = Review::with(['drama' => function($query)
        {
            $query->select('id', 'title');
        },
            'episode' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->where('user_id', $id)->orderBy('id', 'desc')->take(6)->get();
        $screvs = Screv::leftJoin('scs', function($join)
        {
            $join->on('screvs.model_id', '=', 'scs.id')
                ->where('screvs.model', '=', 0);
        })
            ->leftJoin('clubs', function($join)
            {
                $join->on('screvs.model_id', '=', 'clubs.id')
                    ->where('screvs.model', '=', 1);
            })
            ->select('screvs.*', 'scs.name as sc_name', 'clubs.name as club_name')
            ->where('screvs.user_id', $id)
            ->orderBy('id', 'desc')->take(6)->get();
        return view('user.show', ['user' => $user, 'epfavs' => $epfavs,
            'favorites' => $favorites, 'reviews' => $reviews, 'screvs' => $screvs]);
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

    public function epfavs(Request $request, $id, $type)
    {
        $user = User::find($id, ['id', 'name']);
        if($request->has('sort'))
        {
            $sort = $request->input('sort');
        }
        else
        {
            $sort = 'updated_at';
        }
        $favorites = Epfav::with(['episode' => function($query)
        {
            $query->join('dramas', 'dramas.id', '=', 'episodes.drama_id')
                ->select('episodes.id as id', 'drama_id', 'dramas.title as drama_title', 'episodes.title as title',
                    'dramas.sc as cv', 'episodes.duration as duration');
        }])->select('episode_id', 'type', 'rating', 'updated_at')
            ->where('user_id', $id)->where('type', $type)->orderBy($sort, 'desc')->paginate(20);
        return view('user.epfavs', ['user' => $user, 'type' => $type, 'favorites' => $favorites, 'sort' => $sort]);
    }

    public function reviews($id)
    {
        $reviews = Review::where('user_id', $id)->paginate(20);
        return view('user.reviews')->withUser(User::find($id))->withReviews($reviews);
    }

    public function screvs($id)
    {
        $user = User::find($id, ['id', 'name']);
        $reviews = Screv::leftJoin('scs', function($join)
        {
            $join->on('screvs.model_id', '=', 'scs.id')
                ->where('screvs.model', '=', 0);
        })
            ->leftJoin('clubs', function($join)
            {
                $join->on('screvs.model_id', '=', 'clubs.id')
                    ->where('screvs.model', '=', 1);
            })
            ->select('screvs.*', 'scs.name as sc_name', 'clubs.name as club_name')
            ->where('screvs.user_id', $id)
            ->orderBy('id', 'desc')->paginate(20);
        return view('user.screvs', ['user' => $user, 'reviews' => $reviews]);
    }

    public function exportReviews()
    {
        $reviews = Review::with(['drama' => function($query)
        {
            $query->select('id', 'title', 'sc');
        }, 'episode' => function($query)
        {
            $query->select('id', 'title');
        }])->select('drama_id', 'episode_id', 'title','content','created_at','updated_at')
            ->where('user_id', Auth::id())->get();
        $str = "\xEF\xBB\xBF剧集,主役,分集,标题,内容,发表时间,更新时间\n";
        foreach($reviews as $review)
        {
            $str .= "\"".str_replace("\"", "\"\"", $review->drama->title)."\",\"".str_replace("\"", "\"\"", $review->drama->sc)
                ."\",\"".str_replace("\"", "\"\"", $review->episode ? $review->episode->title : '')
                ."\",\"".str_replace("\"", "\"\"", $review->title)."\",\"".str_replace("\"", "\"\"", $review->content)."\","
                .$review->created_at.",".$review->updated_at."\n";
        }
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="reviews.csv"',
        );
        return response($str, 200, $headers);
    }

    public function exportFavorites()
    {
        $favorites = Favorite::with(['drama' => function($query)
        {
            $query->select('id', 'title', 'sc');
        }])->select('drama_id', 'type', 'rating', 'updated_at')
            ->where('user_id', Auth::id())->get();
        $str = "\xEF\xBB\xBF剧集,主役,收藏类型,评分,更新时间\n";
        foreach($favorites as $favorite)
        {
            switch($favorite->type)
            {
                case 0:
                    $type = '想听';
                    break;
                case 1:
                    $type = '在追';
                    break;
                case 2:
                    $type = '听过';
                    break;
                case 3:
                    $type = '搁置';
                    break;
                default:
                    $type = '抛弃';
            }
            $str .= "\"".str_replace("\"", "\"\"", $favorite->drama->title)."\",\"".str_replace("\"", "\"\"", $favorite->drama->sc)
                ."\",".$type.",\"".($favorite->rating != 0 ? $favorite->rating : '')."\",".$favorite->updated_at."\n";
        }
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="favorites.csv"',
        );
        return response($str, 200, $headers);
    }

    public function exportScrevs()
    {
        $reviews = Screv::leftJoin('scs', function($join)
        {
            $join->on('screvs.model_id', '=', 'scs.id')
                ->where('screvs.model', '=', 0);
        })
            ->leftJoin('clubs', function($join)
            {
                $join->on('screvs.model_id', '=', 'clubs.id')
                    ->where('screvs.model', '=', 1);
            })
            ->select('screvs.*', 'scs.name as sc_name', 'clubs.name as club_name')
            ->where('screvs.user_id', Auth::id())->orderBy('screvs.id')->get();
        $str = "\xEF\xBB\xBF属性,名称,标题,内容,发表时间,更新时间\n";
        foreach($reviews as $review)
        {
            $str .= ($review->model ? '社团' : 'SC')
                .",".($review->model ? $review->club_name : $review->sc_name)
                .",\"".str_replace("\"", "\"\"", $review->title)
                ."\",\"".str_replace("\"", "\"\"", $review->content)."\","
                .$review->created_at.",".$review->updated_at."\n";
        }
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="screvs.csv"',
        );
        return response($str, 200, $headers);
    }

    public function invite(Request $request)
    {
        $invitation = DB::table('invitations')->select('id', 'code', 'new_user_id')
            ->where('old_user_id', $request->user()->id)->first();
        return view('user.invite', ['invitation' => $invitation]);
    }

    public function updateCode(Request $request)
    {
        $invitation = DB::table('invitations')->select('id', 'old_user_id')
            ->where('old_user_id', $request->user()->id)->first();
        DB::table('invitations')->where('id', $invitation->id)->update(['code' => $request->input('code')]);
        return redirect()->route('user.invite');
    }

}
