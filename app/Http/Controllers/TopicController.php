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
        $topics = Topic::orderBy('updated_at', 'desc')->paginate(30);
		return view('bbs.index')->withTopics($topics);
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
            return redirect('success?url='.url('/bbs/topic/'.$topic->id));
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
        $topic = Topic::find($id);
        $comments = Comment::withTrashed()->where('topic_id', $id)->orderBy('created_at')->paginate(100);
        return view('bbs.topic')->withTopic($topic)->withComments($comments);
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
