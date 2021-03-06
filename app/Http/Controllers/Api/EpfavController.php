<?php

namespace App\Http\Controllers\Api;

use App\Epfav;
use App\Review;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB, Validator;

class EpfavController extends Controller
{
    public function __construct()
    {
        $this->middleware('apiauth');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'episode_id' => 'required',
            'type' => 'required|in:0,2,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
        ]);
        if ($validator->fails())
            return response($validator->messages(), 422);

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
            return ['result' => 'success'];
        }
        return response('收藏失败> <', 422);
    }

    public function store2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'drama_id' => 'required',
            'episode_id' => 'required',
            'content' => 'required_with:title',
            'visible' => 'required_with:content',
            'type' => 'required|in:0,2,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
            'title' => 'max:255',
        ]);
        if ($validator->fails())
            return response($validator->messages(), 422);

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
            if($request->has('content'))
            {
                $review = new Review;
                $review->user_id = $epfav->user_id;
                $review->drama_id = $request->input('drama_id');
                $review->episode_id = $epfav->episode_id;
                $review->title = $request->input('title');
                $review->content = $request->input('content');
                $review->visible = $request->input('visible');
                $review->visible = $review->visible == 1 ? 0 : 1;
                if($review->save())
                {
                    DB::table('users')->where('id', $review->user_id)->increment('reviews');
                    DB::table('dramas')->where('id', $review->drama_id)->increment('reviews');
                    DB::table('episodes')->where('id', $review->episode_id)->increment('reviews');
                }
                else
                {
                    return response('收藏添加成功，评论添加失败> <', 422);
                }
            }
            return ['result' => 'success'];
        }
        else
        {
            return response('添加失败> <', 422);
        }
    }

    public function update(Request $request, $episode_id)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:0,2,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
        ]);
        if ($validator->fails())
            return response($validator->messages(), 422);

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
            return ['result' => 'success'];
        }
        return response('修改失败> <', 422);
    }

    public function edit(Request $request, $episode_id)
    {
        $review = Review::select('title', 'content', 'visible')
            ->where('user_id', $request->user()->id)
            ->where('episode_id', $episode_id)
            ->first();
        return $review;
    }

    public function update2(Request $request, $episode_id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required_with:title',
            'visible' => 'required_with:content',
            'type' => 'required|in:0,2,4',
            'rating' => 'in:0,0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
            'title' => 'max:255',
        ]);
        if ($validator->fails())
            return response($validator->messages(), 422);

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
            $review = Review::where('user_id', $favorite->user_id)->where('episode_id', $episode_id)->first();
            if($review)
            {
                $review->title = $request->input('title');
                $review->content = $request->input('content');
                $review->visible = $request->input('visible');
                $review->visible = $review->visible == 1 ? 0 : 1;
                if(!$review->save())
                {
                    return response('收藏修改成功，评论修改失败> <', 422);
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
                $review->visible = $request->input('visible');
                $review->visible = $review->visible == 1 ? 0 : 1;
                if($review->save())
                {
                    DB::table('users')->where('id', $review->user_id)->increment('reviews');
                    DB::table('dramas')->where('id', $review->drama_id)->increment('reviews');
                    DB::table('episodes')->where('id', $review->episode_id)->increment('reviews');
                }
                else
                {
                    return response('收藏修改成功，评论添加失败> <', 422);
                }
            }
            return ['result' => 'success'];
        }
        else
        {
            return response('修改失败> <', 422);
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
            return ['result' => 'success'];
        }
        return response('删除失败> <', 422);
    }
}
