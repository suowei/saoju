<?php namespace App\Http\Controllers\Api;

use App\Dramalist;
use App\Ed;
use App\Epfav;
use App\Episode;
use App\Episodever;
use App\Item;
use App\Review;
use App\History;
use App\Drama;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Role;
use App\Song;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Input, Auth, DB;

class EpisodeController extends Controller {

    public function __construct()
    {
        $this->middleware('apiauth', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    public function show(Request $request, $id)
    {
        $episode = Episode::find($id, ['id', 'drama_id', 'title', 'alias', 'release_date', 'url', 'sc',
            'duration', 'poster_url', 'introduction', 'reviews']);
        $episode->load(['drama' => function($query)
        {
            $query->select('id', 'title', 'type', 'era', 'genre', 'original');
        }]);
        if(Auth::check())
        {
            $episode->userFavorite = Epfav::select('type', 'rating')
                ->where('user_id', $request->user()->id)->where('episode_id', $id)->first();
        }
        return $episode;
    }

    public function reviews($id)
    {
        $reviews = Review::with(['user' => function($query)
        {
            $query->select('id', 'name');
        }])
            ->select('id', 'user_id', 'title', 'content', 'visible', 'created_at')
            ->where('episode_id', $id)
            ->orderBy('id', 'desc')
            ->simplePaginate(10);
        return $reviews;
    }
}
