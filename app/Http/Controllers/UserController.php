<?php namespace App\Http\Controllers;

use App\Dramalist;
use App\Epfav;
use App\Episode;
use App\Ftepfav;
use App\Ftfav;
use App\Ftrev;
use App\Listfav;
use App\Livefav;
use App\Liverev;
use App\Review;
use App\Screv;
use App\Songfav;
use App\Songrev;
use App\Tag;
use App\Tagmap;
use App\User;
use App\Favorite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Auth, DB;

class UserController extends Controller {

    public function show($id)
    {
        $user = User::find($id, ['id', 'name', 'introduction', 'episodevers', 'reviews',
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
        $songrevs = Songrev::with(['song' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->select('id', 'song_id', 'title', 'content', 'created_at')
            ->where('user_id', $id)->orderBy('id', 'desc')->take(6)->get();
        $ftrevs = Ftrev::with(['ft' => function($query)
        {
            $query->select('id', 'title');
        },
            'ftep' => function($query)
            {
                $query->select('id', 'title');
            }])
            ->where('user_id', $id)->orderBy('id', 'desc')->take(6)->get();
        $liverevs = Liverev::with(['live' => function($query)
        {
            $query->select('id', 'title', 'showtime');
        }])
            ->select('id', 'live_id', 'title', 'content', 'created_at')
            ->where('user_id', $id)->orderBy('id', 'desc')->take(6)->get();
        $lists = Dramalist::select('id', 'title')->where('user_id', $id)->take(10)->get();
        $tagmaps = Tagmap::with('tag')
            ->select(DB::raw('count(*) as count, tag_id'))
            ->where('user_id', $id)
            ->groupBy('tag_id')
            ->orderBy('count', 'desc')
            ->take(20)->get();
        $songfavs = Songfav::with(['song' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->select('song_id')
            ->where('user_id', $id)->orderBy('created_at', 'desc')->take(6)->get();
        $ftfavs = Ftfav::with(['ft' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->select('ft_id')
            ->where('user_id', $id)->orderBy('created_at', 'desc')->take(6)->get();
        $ftepfavs = Ftepfav::with(['ftep' => function($query)
        {
            $query->join('fts', 'fts.id', '=', 'fteps.ft_id')
                ->select('fteps.id as id', 'ft_id', 'fts.title as ft_title', 'fteps.title as title');
        }])->select('ftep_id')->where('user_id', $id)->orderBy('created_at', 'desc')->take(6)->get();
        $livefavs = Livefav::with(['live' => function($query)
        {
            $query->select('id', 'title', 'showtime');
        }])
            ->select('live_id')
            ->where('user_id', $id)->orderBy('created_at', 'desc')->take(6)->get();
        return view('user.show', ['user' => $user, 'epfavs' => $epfavs, 'favorites' => $favorites, 'songfavs' => $songfavs,
            'reviews' => $reviews, 'screvs' => $screvs, 'songrevs' => $songrevs, 'ftrevs' => $ftrevs, 'liverevs' => $liverevs,
            'lists' => $lists, 'tagmaps' => $tagmaps, 'ftfavs' => $ftfavs, 'ftepfavs' => $ftepfavs, 'livefavs' => $livefavs]);
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
        $user = User::find($id, ['id', 'name']);
        if($request->input('sort') == 'rating')
        {
            $sort = 'rating';
        }
        else
        {

            $sort = 'updated_at';
        }
        $favorites = Favorite::with('drama')->where('user_id', $id)->where('type', $type)->orderBy($sort, 'desc')->paginate(20);
        $favorites->load(['reviews' => function ($query) use($id) {
            $query->where('user_id', $id);
        }]);
        return view('user.favorites', ['user' => $user, 'favorites' => $favorites, 'sort' => $sort, 'type' => $type]);
    }

    public function favall(Request $request, $id)
    {
        $user = User::find($id, ['id', 'name']);
        if($request->input('sort') == 'rating')
        {
            $sort = 'rating';
        }
        else
        {
            $sort = 'updated_at';
        }
        if($request->input('tag'))
        {
            $tag = Tag::where('name', $request->input('tag'))->first();
            $favorites = Favorite::join('tagmaps', function($join) use($tag, $user)
            {
                $join->on('favorites.user_id', '=', 'tagmaps.user_id')
                    ->on('favorites.drama_id', '=', 'tagmaps.drama_id')
                    ->where('favorites.user_id', '=', $user->id)
                    ->where('tagmaps.tag_id', '=', $tag->id);
            })
                ->select('favorites.*')
                ->orderBy($sort, 'desc')->paginate(20);
            $favorites->load('drama');
            $tag = $tag->name;
        }
        else
        {
            $tag = null;
            $favorites = Favorite::with('drama')->where('user_id', $id)->orderBy($sort, 'desc')->paginate(20);
        }
        $favorites->load(['reviews' => function ($query) use($id) {
            $query->where('user_id', $id);
        }]);
        return view('user.favall', ['user' => $user, 'favorites' => $favorites, 'sort' => $sort, 'tag' => $tag]);
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
        $invitations = DB::table('invitations')->select('id', 'code', 'new_user_id')
            ->where('old_user_id', $request->user()->id)->get();
        return view('user.invite', ['invitations' => $invitations]);
    }

    public function updateCode(Request $request)
    {
        $invitation = DB::table('invitations')->select('id', 'old_user_id')->where('id', $request->input('id'))
            ->where('old_user_id', $request->user()->id)->first();
        DB::table('invitations')->where('id', $invitation->id)->update(['code' => $request->input('code')]);
        return redirect()->route('user.invite');
    }

    public function lists($id)
    {
        $lists = Dramalist::select('id', 'title', 'created_at', 'updated_at')->where('user_id', $id)->paginate(50);
        $user = User::find($id, ['id', 'name']);
        return view('user.lists', ['user' => $user, 'lists' => $lists]);
    }

    public function tags($id)
    {
        $tagmaps = Tagmap::with('tag')
            ->select(DB::raw('count(*) as count, tag_id'))
            ->where('user_id', $id)
            ->groupBy('tag_id')
            ->orderBy('count', 'desc')
            ->get();
        $user = User::find($id, ['id', 'name']);
        return view('user.tags', ['user' => $user, 'tagmaps' => $tagmaps]);
    }

    public function listfavs(Request $request)
    {
        $listfavs = Listfav::with(['dramalist' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->select('list_id', 'created_at')->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')->paginate(50);
        return view('user.listfavs', ['listfavs' => $listfavs]);
    }

    public function dramafeed(Request $request)
    {
        $user_id = $request->user()->id;
        DB::table('users')->where('id', $user_id)->update(['dramafeed' => 0]);
        $episodes = Episode::with(['drama' => function($query)
        {
            $query->select('id', 'title', 'type', 'era', 'genre', 'original', 'state', 'sc');
        }])
            ->select('id', 'drama_id', 'title', 'alias', 'release_date', 'duration', 'poster_url', 'introduction')
            ->whereIn('drama_id', function($query) use($user_id)
            {
                $query->select('drama_id')
                    ->from('favorites')
                    ->where('user_id', $user_id)
                    ->whereIn('type', [0, 1])
                    ->whereRaw('episodes.created_at > favorites.created_at');
            })
            ->orderBy('created_at', 'desc')->paginate(20);
        return view('user.dramafeed', ['episodes' => $episodes]);
    }

    public function songrevs($id)
    {
        $reviews = Songrev::with(['song' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->select('id', 'song_id', 'title', 'content', 'created_at')
            ->where('user_id', $id)->paginate(20);
        return view('user.songrevs', ['user' => User::find($id, ['id', 'name']), 'reviews' => $reviews]);
    }

    public function songfavs(Request $request, $id)
    {
        if($request->has('order'))
        {
            $order = $request->input('order');
        }
        else
        {
            $order = 'desc';
        }
        $songfavs = Songfav::with(['song' => function($query)
        {
            $query->select('id', 'title', 'alias', 'artist', 'staff');
        }])
            ->select('song_id', 'created_at')->where('user_id', $request->user()->id)
            ->orderBy('created_at', $order)->paginate(50);
        return view('user.songfavs', ['user' => User::find($id, ['id', 'name']), 'songfavs' => $songfavs, 'order' => $order]);
    }

    public function ftrevs($id)
    {
        $reviews = Ftrev::with(['ft' => function($query)
        {
            $query->select('id', 'title');
        }, 'ftep' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->select('id', 'ft_id', 'ftep_id', 'title', 'content', 'created_at')
            ->where('user_id', $id)->paginate(20);
        return view('user.ftrevs', ['user' => User::find($id, ['id', 'name']), 'reviews' => $reviews]);
    }

    public function ftfavs(Request $request, $id)
    {
        if($request->has('order'))
        {
            $order = $request->input('order');
        }
        else
        {
            $order = 'desc';
        }
        $ftfavs = Ftfav::with(['ft' => function($query)
        {
            $query->select('id', 'title', 'host', 'poster_url', 'introduction');
        }])
            ->select('ft_id', 'created_at')->where('user_id', $request->user()->id)
            ->orderBy('created_at', $order)->paginate(50);
        return view('user.ftfavs', ['user' => User::find($id, ['id', 'name']), 'ftfavs' => $ftfavs, 'order' => $order]);
    }

    public function ftepfavs(Request $request, $id)
    {
        if($request->has('order'))
        {
            $order = $request->input('order');
        }
        else
        {
            $order = 'desc';
        }
        $ftepfavs = Ftepfav::with(['ftep' => function($query)
        {
            $query->join('fts', 'fts.id', '=', 'fteps.ft_id')
                ->select('fteps.id as id', 'ft_id', 'fts.title as ft_title', 'fteps.title as title',
                    'release_date', 'staff', 'fteps.poster_url as poster_url');
        }])
            ->select('ftep_id', 'created_at')->where('user_id', $request->user()->id)
            ->orderBy('created_at', $order)->paginate(50);
        return view('user.ftepfavs', ['user' => User::find($id, ['id', 'name']), 'ftepfavs' => $ftepfavs, 'order' => $order]);
    }

    public function liverevs($id)
    {
        $reviews = Liverev::with(['live' => function($query)
        {
            $query->select('id', 'title', 'showtime');
        }])
            ->select('id', 'live_id', 'title', 'content', 'created_at')
            ->where('user_id', $id)->paginate(20);
        return view('user.liverevs', ['user' => User::find($id, ['id', 'name']), 'reviews' => $reviews]);
    }

    public function livefavs(Request $request, $id)
    {
        if($request->has('order'))
        {
            $order = $request->input('order');
        }
        else
        {
            $order = 'desc';
        }
        $livefavs = Livefav::with(['live' => function($query)
        {
            $query->select('id', 'title', 'showtime', 'information');
        }])
            ->select('live_id', 'created_at')->where('user_id', $request->user()->id)
            ->orderBy('created_at', $order)->paginate(50);
        return view('user.livefavs', ['user' => User::find($id, ['id', 'name']), 'livefavs' => $livefavs, 'order' => $order]);
    }

    public function drama2015(Request $request)
    {
        $user_id = $request->user()->id;
        $episodes = Episode::with(['drama' => function($query)
        {
            $query->select('id', 'title', 'type', 'era', 'original', 'state', 'sc');
        }])
            ->join('favorites', function($join) use($user_id)
            {
                $join->on('episodes.drama_id', '=', 'favorites.drama_id')
                    ->where('favorites.user_id', '=', $user_id)
                    ->where('release_date', '>=', '2015-01-01')
                    ->where('release_date', '<=', '2015-12-31');
            })
            ->select('episodes.id as id', 'episodes.drama_id as drama_id', 'title', 'release_date',
                'favorites.rating as rating')
            ->orderByRaw('rating desc, release_date')
            ->paginate(50);
        return view('user.drama2015', ['episodes' => $episodes]);
    }

    public function episode2015(Request $request)
    {
        $user_id = $request->user()->id;
        $episodes = Episode::with(['drama' => function($query)
        {
            $query->select('id', 'title', 'type', 'era', 'original', 'state', 'sc');
        }])
            ->join('epfavs', function($join) use($user_id)
            {
                $join->on('episodes.id', '=', 'epfavs.episode_id')
                    ->where('epfavs.user_id', '=', $user_id)
                    ->where('release_date', '>=', '2015-01-01')
                    ->where('release_date', '<=', '2015-12-31');
            })
            ->select('id', 'drama_id', 'title', 'release_date', 'rating')
            ->orderByRaw('rating desc, release_date')
            ->paginate(50);
        return view('user.episode2015', ['episodes' => $episodes]);
    }
}
