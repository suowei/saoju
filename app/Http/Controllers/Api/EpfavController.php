<?php

namespace App\Http\Controllers\Api;

use App\Epfav;
use App\Review;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;

class EpfavController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|in:0,2,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
            'episode_id' => 'required',
        ]);

        $epfav = new Epfav;
        $epfav->user_id = $request->user()->id;
        $epfav->episode_id = $request->input('episode_id');
        $epfav->type = $request->input('type');
        if($epfav->type == 0)
        {
            $epfav->rating = 0;
        }
        else
        {
            $epfav->rating = $request->input('rating');
        }
        if($epfav->save())
        {
            DB::table('users')->where('id', $epfav->user_id)->increment('epfav'.$epfav->type);
            DB::table('episodes')->where('id', $epfav->episode_id)->increment('favorites');
            return "success";
        }
        return response('收藏失败> <', 422);
    }

    public function store2(Request $request)
    {
        $this->validate($request, [
            'drama_id' => 'required',
            'episode_id' => 'required',
            'content' => 'required_with:title',
            'type' => 'required|in:0,2,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
            'title' => 'max:255',
        ]);

        $epfav = new Epfav;
        $epfav->user_id = $request->user()->id;
        $epfav->episode_id = $request->input('episode_id');
        $epfav->type = $request->input('type');
        if($epfav->type == 0)
        {
            $epfav->rating = 0;
        }
        else
        {
            $epfav->rating = $request->input('rating');
        }
        if($request->has('content'))
        {
            $review = new Review;
            $review->user_id = $epfav->user_id;
            $review->drama_id = $request->input('drama_id');
            $review->episode_id = $epfav->episode_id;
            $review->title = $request->input('title');
            $review->content = $request->input('content');
            if($review->save())
            {
                DB::table('users')->where('id', $review->user_id)->increment('reviews');
                DB::table('dramas')->where('id', $review->drama_id)->increment('reviews');
                DB::table('episodes')->where('id', $review->episode_id)->increment('reviews');
            }
            else
            {
                return response('添加失败> <', 422);
            }
        }
        if($epfav->save())
        {
            DB::table('users')->where('id', $epfav->user_id)->increment('epfav'.$epfav->type);
            DB::table('episodes')->where('id', $epfav->episode_id)->increment('favorites');
            return "success";
        }
        else
        {
            return response('评论添加成功，收藏添加失败> <', 422);
        }
    }

    public function update(Request $request, $episode_id)
    {
        $this->validate($request, [
            'type' => 'required|in:0,2,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
        ]);

        $favorite = Epfav::where('user_id', $request->user()->id)->where('episode_id', $episode_id)->first();
        $oldType = $favorite->type;
        $favorite->type = $request->input('type');
        if($favorite->type == 0)
        {
            $favorite->rating = 0;
        }
        else
        {
            $favorite->rating = $request->input('rating');
        }
        $result = DB::table('epfavs')->where('user_id', $favorite->user_id)->where('episode_id', $episode_id)
            ->update(['type' => $favorite->type, 'rating' => $favorite->rating, 'updated_at' => date("Y-m-d H:i:s")]);
        if($result)
        {
            DB::table('users')->where('id', $favorite->user_id)->decrement('epfav'.$oldType);
            DB::table('users')->where('id', $favorite->user_id)->increment('epfav'.$favorite->type);
            return "success";
        }
        return response('修改失败> <', 422);
    }

    public function update2(Request $request, $episode_id)
    {
        $this->validate($request, [
            'content' => 'required_with:title',
            'type' => 'required|in:0,2,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
            'title' => 'max:255',
        ]);

        $favorite = Epfav::where('user_id', $request->user()->id)->where('episode_id', $episode_id)->first();
        $oldType = $favorite->type;
        $favorite->type = $request->input('type');
        if($favorite->type == 0)
        {
            $favorite->rating = 0;
        }
        else
        {
            $favorite->rating = $request->input('rating');
        }
        $review = Review::where('user_id', $favorite->user_id)->where('episode_id', $episode_id)->first();
        if($review)
        {
            $review->title = $request->input('title');
            $review->content = $request->input('content');
            if(!$review->save())
            {
                return response('修改评论失败> <', 422);
            }
        }
        else if($request->has('content'))
        {
            $review = new Review;
            $review->user_id = $favorite->user_id;
            $review->drama_id = $request->input('drama_id');
            $review->episode_id = $favorite->episode_id;
            $review->title = $request->input('title');
            $review->content = $request->input('content');
            if($review->save())
            {
                DB::table('users')->where('id', $review->user_id)->increment('reviews');
                DB::table('dramas')->where('id', $review->drama_id)->increment('reviews');
                DB::table('episodes')->where('id', $review->episode_id)->increment('reviews');
            }
            else
            {
                return response('评论评论失败> <', 422);
            }
        }
        $result = DB::table('epfavs')->where('user_id', $favorite->user_id)->where('episode_id', $episode_id)
            ->update(['type' => $favorite->type, 'rating' => $favorite->rating, 'updated_at' => date("Y-m-d H:i:s")]);
        if($result)
        {
            DB::table('users')->where('id', $favorite->user_id)->decrement('epfav'.$oldType);
            DB::table('users')->where('id', $favorite->user_id)->increment('epfav'.$favorite->type);
            return "success";
        }
        else
        {
            return response('评论修改成功，收藏修改失败> <', 422);
        }
    }

    public function destroy(Request $request, $episode_id)
    {
        $user_id = $request->user()->id;
        $favorite = Epfav::select('type')->where('user_id', $user_id)->where('episode_id', $episode_id)->first();
        $result = DB::table('epfavs')->where('user_id', $user_id)->where('episode_id', $episode_id)->delete();
        if($result)
        {
            DB::table('users')->where('id', $user_id)->decrement('epfav'.$favorite->type);
            DB::table('episodes')->where('id', $episode_id)->decrement('favorites');
            return "success";
        }
        return response('删除失败> <', 422);
    }
}
