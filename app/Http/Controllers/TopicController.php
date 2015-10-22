<?php namespace App\Http\Controllers;

use App\Topic;
use App\Comment;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Input;

class TopicController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $topics = Topic::select('id', 'title', 'user_id', 'ip', 'comments', 'updated_at')
            ->orderBy('updated_at', 'desc')->paginate(30);
        $topics->load(['user' => function($query)
        {
            $query->select('id','name');
        }]);
		return view('bbs.index', ['topics' => $topics]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $this->validate($request, [
            'title' => 'required|max:50',
            'ip' => 'required|ip',
            'content' => 'required',
            'user_id' => 'required',
        ]);
        if ($topic = Topic::create(Input::all())) {
            return redirect()->route('bbs.topic.show', [$topic]);
        } else {
            return redirect()->back()->withInput()->withErrors('发表新帖失败！');
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $topic = Topic::find($id, ['id', 'title', 'user_id', 'ip', 'content', 'created_at']);
        $topic->load(['user' => function($query)
        {
            $query->select('id','name');
        }]);
        $comments = Comment::withTrashed()->select('user_id', 'ip', 'content', 'deleted_at', 'created_at')
            ->where('topic_id', $id)->orderBy('id')->paginate(100);
        $comments->load(['user' => function($query)
        {
            $query->select('id','name');
        }]);
        return view('bbs.topic', ['topic' => $topic, 'comments' => $comments]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
