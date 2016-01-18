<?php namespace App\Http\Controllers\Api;

use App\Drama;
use App\Dramalist;
use App\Dramaver;
use App\Ed;
use App\Epfav;
use App\History;
use App\Favorite;
use App\Item;
use App\Review;
use App\Episode;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Role;
use App\Song;
use App\Tag;
use App\Tagmap;
use Illuminate\Http\Request;

use Input, Auth, DB;

class DramaController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    public function show($id)
    {
        $drama = Drama::find($id, ['id', 'title', 'alias', 'type', 'era', 'genre',
            'original', 'count', 'state', 'sc', 'poster_url', 'introduction', 'reviews', 'favorites']);
        $drama->load(['episodes' => function($query)
        {
            $query->select('id', 'drama_id', 'title', 'alias', 'release_date', 'poster_url')
                ->orderByRaw('release_date, id')
                ->get();
        }]);
        $drama->commtags = Tagmap::with('tag')
            ->select(DB::raw('count(*) as count, tag_id'))
            ->where('drama_id', $id)
            ->groupBy('tag_id')
            ->orderBy('count', 'desc')
            ->take(20)
            ->get();
        return $drama;
    }

    public function reviews($id)
    {
        $reviews = Review::with(['user' => function($query)
        {
            $query->select('id', 'name');
        },
            'episode' => function($query)
        {
            $query->select('id', 'title');
        }])
            ->select('id', 'episode_id', 'user_id', 'title', 'content', 'created_at')
            ->where('drama_id', $id)
            ->simplePaginate(20);
        return $reviews;
    }
}
