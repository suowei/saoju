<?php namespace App\Http\Controllers;

use App\Reply;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Input, Auth;

class ReplyController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		return redirect()->route('review.show', $request->input('review'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $this->validate($request, [
            'content' => 'required',
            'review_id' => 'required|exists:reviews,id',
            'user_id' => 'required|exists:users,id',
        ]);
        if($reply = Reply::create(Input::all()))
        {
            DB::table('reviews')->where('id', $reply->review_id)->increment('replies');
            return redirect()->back();
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
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
		//
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
        $reply = Reply::find($id);
        if ($reply->user_id == Auth::id())
        {
            if($reply->delete())
            {
                DB::table('reviews')->where('id', $reply->review_id)->decrement('replies');
            }
        }
        return redirect()->back();
	}

}
