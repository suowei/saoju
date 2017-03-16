<?php  namespace App\Http\Controllers\Api;

use App\Epfav;
use App\Favorite;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Review;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function show($id)
    {
        $user = User::find($id, ['id', 'name', 'introduction', 'episodevers', 'reviews',
            'favorite0', 'favorite1', 'favorite2', 'favorite3', 'favorite4',
            'epfav0', 'epfav2', 'epfav4', 'screvs', 'created_at']);
        return $user;
    }

    public function favorites(Request $request, $id, $type)
    {
        if($request->input('sort') == 'rating')
        {
            $sort = 'rating';
        }
        else
        {

            $sort = 'updated_at';
        }
        $favorites = Favorite::with(['drama' => function($query)
        {
            $query->select('id', 'title', 'sc');
        }])
            ->select('drama_id', 'type', 'rating', 'tags', 'updated_at')
            ->where('user_id', $id)
            ->where('type', $type)
            ->orderBy($sort, 'desc')
            ->simplePaginate(20);
        return $favorites;
    }

    public function epfavs(Request $request, $id, $type)
    {
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
                ->select('episodes.id as id', 'dramas.title as dramaTitle', 'episodes.title as title',
                    'dramas.sc as cv', 'episodes.duration as duration');
        }])
            ->select('episode_id', 'type', 'rating', 'updated_at')
            ->where('user_id', $id)
            ->where('type', $type)
            ->orderBy($sort, 'desc')
            ->simplePaginate(20);
        return $favorites;
    }

    public function reviews(Request $request, $id)
    {
        if($request->user() && $request->user()->id == $id) {
            $visible = 3;
        } else {
            $visible = 2;
        }
        $reviews = Review::with(['drama' => function($query)
        {
            $query->select('id', 'title');
        },
            'episode' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->select('id', 'drama_id', 'episode_id', 'title', 'content', 'visible', 'created_at')
            ->where('user_id', $id)
            ->where('visible', '<=', $visible)
            ->orderBy('id', 'desc')
            ->simplePaginate(20);
        return $reviews;
    }
}
