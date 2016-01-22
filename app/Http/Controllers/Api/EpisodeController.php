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

    public function show($id)
    {
        $episode = Episode::find($id, ['id', 'drama_id', 'title', 'alias', 'release_date', 'url', 'sc',
            'duration', 'poster_url', 'introduction', 'reviews', 'favorites']);
        $episode->load(['drama' => function($query)
        {
            $query->select('id', 'title', 'type', 'era', 'genre', 'original');
        }]);
        return $episode;
    }

    public function reviews($id)
    {
        $reviews = Review::with(['user' => function($query)
        {
            $query->select('id', 'name');
        }])
            ->select('id', 'user_id', 'title', 'content', 'created_at')
            ->where('episode_id', $id)
            ->orderBy('id', 'desc')
            ->simplePaginate(20);
        return $reviews;
    }
}
