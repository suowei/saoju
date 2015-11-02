<?php namespace App\Http\Controllers;

use App\Drama;
use App\Epfav;
use App\Favorite;
use App\Http\Requests;

use App\Review;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $favorites = Favorite::with(['user' => function($query)
        {
            $query->select('id', 'name');
        },
            'drama' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->select('user_id', 'drama_id', 'type', 'updated_at')
            ->orderBy('updated_at', 'desc')->take(50)->get();
        $epfavs = Epfav::join('episodes', function($join)
        {
            $join->on('episodes.id', '=', 'epfavs.episode_id');
        })
            ->join('dramas', function($join) {
            $join->on('dramas.id', '=', 'episodes.drama_id');
        })
            ->select('user_id', 'drama_id', 'dramas.title as drama_title', 'episode_id',
            'episodes.title as episode_title', 'epfavs.type as type', 'epfavs.updated_at as updated_at')
            ->orderBy('epfavs.updated_at', 'desc')->take(50)->get();
        $epfavs->load(['user' => function($query)
        {
            $query->select('id', 'name');
        }]);
        return view('favorite.index', ['favorites' => $favorites, 'epfavs' => $epfavs]);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'drama' => 'required',
        ]);
        $drama = Drama::find($request->input('drama'), ['id', 'title']);
        return view('favorite.create', ['drama' => $drama]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|in:0,1,2,3,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
            'drama_id' => 'required',
        ]);

        $favorite = new Favorite;
        $favorite->user_id = $request->user()->id;
        $favorite->drama_id = $request->input('drama_id');
        $favorite->type = $request->input('type');
        if($favorite->type == 0)//想听状态下不能评分
        {
            $favorite->rating = 0;
        }
        else
        {
            $favorite->rating = $request->input('rating');
        }
        $favorite->tags = $request->input('tags');
        if($favorite->save())
        {
            DB::table('users')->where('id', $favorite->user_id)->increment('favorite'.$favorite->type);
            DB::table('dramas')->where('id', $favorite->drama_id)->increment('favorites');
            if($request->input('tags'))//防止出现空字符串标签
            {
                $tagmaps = [];
                $tags = explode(',', $request->input('tags'));
                foreach($tags as $tag)
                {
                    $tagmaps[] = ['drama_id' => $favorite->drama_id, 'user_id' => $favorite->user_id,
                        'tag_id' => Tag::firstOrCreate(['name' => $tag])->id];
                }
                DB::table('tagmaps')->insert($tagmaps);
            }
        }
        return redirect()->back();
    }

    //添加收藏及评论
    public function store2(Request $request)
    {
        $this->validate($request, [
            'drama_id' => 'required',
            'content' => 'required_with:title',
            'type' => 'required|in:0,1,2,3,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
            'title' => 'max:255',
        ]);

        $favorite = new Favorite;
        $favorite->user_id = $request->user()->id;
        $favorite->drama_id = $request->input('drama_id');
        $favorite->type = $request->input('type');
        if($favorite->type == 0)//想听状态下不能评分
        {
            $favorite->rating = 0;
        }
        else
        {
            $favorite->rating = $request->input('rating');
        }
        $favorite->tags = $request->input('tags');
        //评论内容不为空则新建评论
        if($request->has('content'))
        {
            $review = new Review;
            $review->user_id = $favorite->user_id;
            $review->drama_id = $favorite->drama_id;
            $review->title = $request->input('title');
            $review->content = $request->input('content');
            if($review->save())
            {
                DB::table('users')->where('id', $review->user_id)->increment('reviews');
                DB::table('dramas')->where('id', $review->drama_id)->increment('reviews');
            }
            else
            {
                return redirect()->back()->withInput()->withErrors('添加失败');
            }
        }
        if($favorite->save())
        {
            DB::table('users')->where('id', $favorite->user_id)->increment('favorite'.$favorite->type);
            DB::table('dramas')->where('id', $favorite->drama_id)->increment('favorites');
            if($request->input('tags'))//防止出现空字符串标签
            {
                $tagmaps = [];
                $tags = explode(',', $request->input('tags'));
                foreach($tags as $tag)
                {
                    $tagmaps[] = ['drama_id' => $favorite->drama_id, 'user_id' => $favorite->user_id,
                        'tag_id' => Tag::firstOrCreate(['name' => $tag])->id];
                }
                DB::table('tagmaps')->insert($tagmaps);
            }
            return redirect()->route('drama.show', [$favorite->drama_id]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('评论添加成功，收藏添加失败！');
        }
    }

    public function edit(Request $request, $drama_id)
    {
        $drama = Drama::find($drama_id, ['id', 'title']);
        $user_id = $request->user()->id;
        $favorite = Favorite::select('type', 'rating', 'tags')->where('user_id', $user_id)->where('drama_id', $drama_id)->first();
        $review = Review::select('title', 'content')->where('user_id', $user_id)->where('drama_id', $drama_id)->where('episode_id', 0)->first();
        return view('favorite.edit', ['drama' => $drama, 'favorite' => $favorite, 'review' => $review]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'type' => 'required|in:0,1,2,3,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
        ]);

        $favorite = Favorite::find($id);
        if($favorite->user_id == $request->user()->id)
        {
            $oldType = $favorite->type;
            $favorite->type = $request->input('type');
            if($favorite->type == 0)//想听状态下不能评分
            {
                $favorite->rating = 0;
            }
            else
            {
                $favorite->rating = $request->input('rating');
            }
            $favorite->tags = $request->input('tags');
            if($favorite->save())
            {
                DB::table('users')->where('id', $favorite->user_id)->decrement('favorite'.$oldType);
                DB::table('users')->where('id', $favorite->user_id)->increment('favorite'.$favorite->type);
                //获取用户当前标签
                $tags_old = DB::table('tagmaps')->where('drama_id', $favorite->drama_id)
                    ->where('user_id', $favorite->user_id)->lists('tag_id');
                //获取输入标签
                if($request->input('tags'))
                    $tagsinput = explode(',', $request->input('tags'));
                else
                    $tagsinput = [];//防止出现空字符串标签
                $tags_new = [];
                foreach($tagsinput as $tag)
                {
                    $tags_new[] = Tag::firstOrCreate(['name' => $tag])->id;
                }
                //得到新输入标签中有而过去无的新增标签映射
                $adds = array_diff($tags_new, $tags_old);
                $add_maps = [];
                foreach($adds as $tag)
                {
                    $add_maps[] = ['drama_id' => $favorite->drama_id, 'user_id' => $favorite->user_id,
                        'tag_id' => $tag];
                }
                //得到过去有而现在无的移除标签
                $removes = array_diff($tags_old, $tags_new);
                DB::table('tagmaps')->insert($add_maps);
                DB::table('tagmaps')->where('drama_id', $favorite->drama_id)->where('user_id', $favorite->user_id)
                    ->whereIn('tag_id', $removes)->delete();
            }
        }
        return redirect()->back();
    }

    public function update2(Request $request, $drama_id)
    {
        $this->validate($request, [
            'content' => 'required_with:title',
            'type' => 'required|in:0,1,2,3,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
            'title' => 'max:255',
        ]);

        $favorite = Favorite::where('user_id', $request->user()->id)->where('drama_id', $drama_id)->first();
        $oldType = $favorite->type;
        $favorite->type = $request->input('type');
        if($favorite->type == 0)//想听状态下不能评分
        {
            $favorite->rating = 0;
        }
        else
        {
            $favorite->rating = $request->input('rating');
        }
        $favorite->tags = $request->input('tags');
        $review = Review::where('user_id', $favorite->user_id)->where('drama_id', $drama_id)->where('episode_id', 0)->first();
        if($review)//若已有评论则修改
        {
            $review->title = $request->input('title');
            $review->content = $request->input('content');
            if(!$review->save())
            {
                return redirect()->back()->withInput()->withErrors('修改评论失败');
            }
        }
        else if($request->has('content'))//若原先无评论且此次评论内容不为空则新建评论
        {
            $review = new Review;
            $review->user_id = $favorite->user_id;
            $review->drama_id = $drama_id;
            $review->title = $request->input('title');
            $review->content = $request->input('content');
            if($review->save())
            {
                DB::table('users')->where('id', $review->user_id)->increment('reviews');
                DB::table('dramas')->where('id', $review->drama_id)->increment('reviews');
            }
            else
            {
                return redirect()->back()->withInput()->withErrors('添加评论失败');
            }
        }
        //修改收藏
        if($favorite->save())
        {
            DB::table('users')->where('id', $favorite->user_id)->decrement('favorite'.$oldType);
            DB::table('users')->where('id', $favorite->user_id)->increment('favorite'.$favorite->type);
            //获取用户当前标签
            $tags_old = DB::table('tagmaps')->where('drama_id', $favorite->drama_id)
                ->where('user_id', $favorite->user_id)->lists('tag_id');
            //获取输入标签
            if($request->input('tags'))
                $tagsinput = explode(',', $request->input('tags'));
            else
                $tagsinput = [];//防止出现空字符串标签
            $tags_new = [];
            foreach($tagsinput as $tag)
            {
                $tags_new[] = Tag::firstOrCreate(['name' => $tag])->id;
            }
            //得到新输入标签中有而过去无的新增标签映射
            $adds = array_diff($tags_new, $tags_old);
            $add_maps = [];
            foreach($adds as $tag)
            {
                $add_maps[] = ['drama_id' => $favorite->drama_id, 'user_id' => $favorite->user_id,
                    'tag_id' => $tag];
            }
            //得到过去有而现在无的移除标签
            $removes = array_diff($tags_old, $tags_new);
            DB::table('tagmaps')->insert($add_maps);
            DB::table('tagmaps')->where('drama_id', $favorite->drama_id)->where('user_id', $favorite->user_id)
                ->whereIn('tag_id', $removes)->delete();
            return redirect()->route('drama.show', [$drama_id]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('评论修改成功，收藏修改失败');
        }
    }

    public function destroy(Request $request, $id)
    {
        $favorite = Favorite::find($id);
        if($favorite->user_id == $request->user()->id)
        {
            if($favorite->delete())
            {
                DB::table('users')->where('id', $favorite->user_id)->decrement('favorite'.$favorite->type);
                DB::table('dramas')->where('id', $favorite->drama_id)->decrement('favorites');
                DB::table('tagmaps')->where('drama_id', $favorite->drama_id)->where('user_id', $favorite->user_id)->delete();
            }
        }
        return redirect()->back();
    }
}
