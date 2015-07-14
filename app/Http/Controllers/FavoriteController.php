<?php namespace App\Http\Controllers;

use App\Drama;
use App\Favorite;
use App\Http\Requests;

use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
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

        if($favorite = Favorite::onlyTrashed()
            ->where('user_id', Auth::id())
            ->where('drama_id', $request->input('drama_id'))->first())
        {
            $favorite->restore();
        }
        else
        {
            $favorite = new Favorite;
            $favorite->user_id = Auth::id();
            $favorite->drama_id = $request->input('drama_id');
        }
        $favorite->type = $request->input('type');
        if($favorite->type == 0)//想听状态下不能评分
        {
            $favorite->rating = 0;
        }
        else
        {
            $favorite->rating = $request->input('rating');
        }
        if($favorite->save())
        {
            DB::table('users')->where('id', $favorite->user_id)->increment('favorite'.$favorite->type);
            DB::table('dramas')->where('id', $favorite->drama_id)->increment('favorites');
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

        if($favorite = Favorite::onlyTrashed()
            ->where('user_id', $request->user()->id)
            ->where('drama_id', $request->input('drama_id'))->first())
        {
            $favorite->restore();
        }
        else
        {
            $favorite = new Favorite;
            $favorite->user_id = $request->user()->id;
            $favorite->drama_id = $request->input('drama_id');
        }
        $favorite->type = $request->input('type');
        if($favorite->type == 0)//想听状态下不能评分
        {
            $favorite->rating = 0;
        }
        else
        {
            $favorite->rating = $request->input('rating');
        }
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
            return redirect('success?url='.url('/drama/'.$favorite->drama_id));
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('评论添加成功，收藏添加失败！');
        }
    }

    public function edit(Request $request, $drama_id)
    {
        $user_id = $request->user()->id;
        $favorite = Favorite::where('user_id', $user_id)->where('drama_id', $drama_id)->first();
        $review = Review::where('user_id', $user_id)->where('drama_id', $drama_id)->where('episode_id', 0)->first();
        $drama = Drama::find($drama_id, ['id', 'title']);
        return view('favorite.edit', ['drama' => $drama, 'favorite' => $favorite, 'review' => $review]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'type' => 'required|in:0,1,2,3,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
        ]);

        $favorite = Favorite::find($id);
        if($favorite->user_id == Auth::id())
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
            if($favorite->save())
            {
                DB::table('users')->where('id', $favorite->user_id)->decrement('favorite'.$oldType);
                DB::table('users')->where('id', $favorite->user_id)->increment('favorite'.$favorite->type);
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
            return redirect('success?url='.url('/drama/'.$drama_id));
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('评论修改成功，收藏修改失败');
        }
    }

    public function destroy($id)
    {
        $favorite = Favorite::find($id);
        if($favorite->user_id == Auth::id())
        {
            if($favorite->delete())
            {
                DB::table('users')->where('id', $favorite->user_id)->decrement('favorite'.$favorite->type);
                DB::table('dramas')->where('id', $favorite->drama_id)->decrement('favorites');
            }
        }
        return redirect()->back();
    }
}
