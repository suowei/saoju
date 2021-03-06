<?php

namespace App\Http\Controllers;

use App\Club;
use App\Role;
use App\Sc;
use App\Screv;
use App\Scver;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    public function index(Request $request)
    {
        //职位筛选
        if($request->has('job'))
        {
            $job = $request->input('job');
        }
        else
        {
            $job = -1;
        }
        //传递给视图的url参数
        $params = $request->except('page');
        //排序
        if($request->has('sort'))
        {
            $params['sort'] = $request->input('sort');
        }
        else
        {
            $params['sort'] = 'id';
        }
        if($request->has('order'))
        {
            $params['order'] = $request->input('order');
        }
        else
        {
            $params['order'] = 'desc';
        }
        if($job < 0)
        {
            $scs = Sc::select('id', 'name', 'alias', 'club_id', 'jobs')
                ->orderBy($params['sort'], $params['order'])->paginate(50);
        }
        else
        {
            $scs = Sc::join('sc_job', function($join) use($job)
            {
                $join->on('sc_job.sc_id', '=', 'scs.id')
                    ->where('sc_job.job_id', '=', $job);
            })->select('id', 'name', 'alias', 'club_id', 'jobs')
                ->orderBy($params['sort'], $params['order'])->paginate(50);
        }
        $scs->load(['club' => function($query)
        {
            $query->select('id', 'name');
        }]);
        return view('sc.index', ['params' => $params, 'scs' => $scs]);
    }

    public function create()
    {
        return view('sc.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255|unique:scs',
            'alias' => 'max:255',
            'weibo' => 'max:255',
        ]);

        $sc = new Sc;
        $sc->name = $request->input('name');
        $sc->alias = $request->input('alias');
        if($club = Club::select('id')->where('name', $request->input('club'))->first())
        {
            $sc->club_id = $club->id;
        }
        else
        {
            $sc->club_id = 0;
        }
        $sc->jobs = $request->input('jobs');
        $sc->weibo = $request->input('weibo');
        $sc->information = $request->input('information');
        if($sc->save())
        {
            Scver::create(['sc_id' => $sc->id, 'user_id' => $request->user()->id, 'first' => 1,
                'name' => $sc->name, 'alias' => $sc->alias, 'club_id' => $sc->club_id,
                'jobs' => $sc->jobs, 'weibo' => $sc->weibo, 'information' => $sc->information]);
            if($request->has('job'))
            {
                $jobs = [];
                foreach($request->input('job') as $job)
                {
                    $jobs[] = ['sc_id' => $sc->id, 'job_id' => $job];
                }
                DB::table('sc_job')->insert($jobs);
            }
            return redirect()->route('sc.show', [$sc]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('添加失败');
        }
    }

    public function show(Request $request, $id)
    {
        $sc = Sc::find($id, ['id', 'name', 'alias', 'club_id', 'jobs', 'weibo', 'information', 'reviews']);
        $sc->load(['club' => function($query)
        {
            $query->select('id', 'name');
        }]);
        $roles = Role::join('episodes', function($join) use($id)
        {
            $join->on('episodes.id', '=', 'roles.episode_id')
                ->where('roles.sc_id', '=', $id);
        })->join('dramas', function($join) use($id)
        {
            $join->on('dramas.id', '=', 'roles.drama_id')
                ->where('roles.sc_id', '=', $id);
        })->select('roles.drama_id as drama_id', 'dramas.title as drama_title', 'dramas.type as drama_type',
            'dramas.era as drama_era', 'dramas.state as drama_state',
            'episode_id', 'episodes.title as episode_title', 'job', 'note', 'release_date')
            ->orderBy('release_date', 'asc')->get();
        $roles = $roles->groupBy('job');
        $reviews = Screv::with(['user' => function($query) {
            $query->select('id', 'name');
        }])->select('id', 'user_id', 'title', 'content', 'created_at')
            ->where('model_id', $id)->where('model', 0)->orderBy('id', 'desc')->take(20)->get();
        if(Auth::check())
        {
            $userReviews = Screv::select('id', 'title', 'content', 'created_at')
                ->where('user_id', $request->user()->id)->where('model_id', $id)->where('model', 0)->get();
        }
        else
        {
            $userReviews = 0;
        }
        return view('sc.show', ['sc' => $sc, 'roles' => $roles, 'reviews' => $reviews, 'userReviews' => $userReviews]);
    }

    public function edit($id)
    {
        $sc = Sc::find($id, ['id', 'name', 'alias', 'club_id', 'jobs', 'weibo', 'information']);
        $sc->load(['club' => function($query)
        {
            $query->select('id', 'name');
        }]);
        $jobs = DB::table('sc_job')->select('job_id')->where('sc_id', $id)->get();
        $job = [];
        foreach($jobs as $row)
        {
            $job[$row->job_id] = 1;
        }
        return view('sc.edit', ['sc' => $sc, 'job' => $job]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'alias' => 'max:255',
            'weibo' => 'max:255',
        ]);

        $sc = Sc::find($id);
        $sc->name = $request->input('name');
        $sc->alias = $request->input('alias');
        if($club = Club::select('id')->where('name', $request->input('club'))->first())
        {
            $sc->club_id = $club->id;
        }
        else
        {
            $sc->club_id = 0;
        }
        $sc->jobs = $request->input('jobs');
        $sc->weibo = $request->input('weibo');
        $sc->information = $request->input('information');
        if($sc->save())
        {
            $user_id = $request->user()->id;
            $version = Scver::where('sc_id', $id)->where('user_id', $user_id)->first();
            if(!$version)
            {
                $version = new Scver;
                $version->sc_id = $id;
                $version->user_id = $user_id;
                $version->first = 0;
            }
            $version->name = $sc->name;
            $version->alias = $sc->alias;
            $version->club_id = $sc->club_id;
            $version->jobs = $sc->jobs;
            $version->weibo = $sc->weibo;
            $version->information = $sc->information;
            $version->save();

            //获取sc当前job数组
            $jobs = DB::table('sc_job')->select('job_id')->where('sc_id', $id)->get();
            $job_old = [];
            foreach($jobs as $row)
            {
                $job_old[$row->job_id] = 1;
            }
            //获取输入job数组
            $job_input = [];
            if($request->has('job'))
            {
                foreach($request->input('job') as $job)
                {
                    $job_input[$job] = 1;
                }
            }
            //对比两个数组判断需要添加和删除的职位
            $adds = [];
            $removes = [];
            for($i = 0; $i <= 11; $i++)
            {
                if(isset($job_input[$i]) && !isset($job_old[$i]))
                {
                    $adds[] = ['sc_id' => $id, 'job_id' => $i];//若输入有，原来没有，则添加职位
                }
                else if(isset($job_old[$i]) && !isset($job_input[$i]))
                {
                    $removes[] = $i;//若原来有，输入没有，则删除职位
                }
            }
            DB::table('sc_job')->insert($adds);
            DB::table('sc_job')->where('sc_id', $id)->whereIn('job_id', $removes)->delete();
            return redirect()->route('sc.show', [$sc]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors('修改失败');
        }
    }

    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q');
        $scs = Sc::select('name', 'alias')->where('name', 'LIKE', '%'.$keyword.'%')->orWhere('alias', 'LIKE', '%'.$keyword.'%')->get();
        return $scs;
    }

    public function episodes(Request $request, $id)
    {
        $sc = Sc::find($id, ['id', 'name']);
        //职位筛选
        if($request->has('job'))
        {
            $job = $request->input('job');
        }
        else
        {
            $job = -2;
        }
        //传递给视图的url参数
        $params = $request->all();
        //排序
        if($request->has('sort'))
        {
            $params['sort'] = $request->input('sort');
        }
        else
        {
            $params['sort'] = 'release_date';
        }
        if($request->has('order'))
        {
            $params['order'] = $request->input('order');
        }
        else
        {
            $params['order'] = 'asc';
        }
        if($job < 0)
        {
            $roles = Role::join('episodes', function($join) use($id)
            {
                $join->on('episodes.id', '=', 'roles.episode_id')
                    ->where('roles.sc_id', '=', $id);
            })->select('roles.drama_id as drama_id', 'episode_id',
                'episodes.title as episode_title', 'job', 'note', 'release_date')
                ->orderBy($params['sort'], $params['order'])->get();
        }
        else
        {
            $roles = Role::join('episodes', function($join) use($id, $job)
            {
                $join->on('episodes.id', '=', 'roles.episode_id')
                    ->where('roles.sc_id', '=', $id)->where('roles.job', '=', $job);
            })->select('roles.drama_id as drama_id', 'episode_id',
                'episodes.title as episode_title', 'job', 'note', 'release_date')
                ->orderBy($params['sort'], $params['order'])->get();
        }
        $roles->load(['drama' => function($query)
        {
            $query->select('id', 'title');
        }]);
        if($job == -2)
            $roles = $roles->groupBy('job');
        else if($job == -1)
            $roles = $roles->groupBy('episode_id');
        return view('sc.episodes', ['params' => $params, 'roles' => $roles, 'sc' => $sc, 'job' => $job]);
    }

    public function dramas(Request $request, $id)
    {
        $sc = Sc::find($id, ['id', 'name']);
        //职位筛选
        if($request->has('job'))
        {
            $job = $request->input('job');
        }
        else
        {
            $job = -2;
        }
        //传递给视图的url参数
        $params = $request->all();
        //排序
        if($request->has('sort'))
        {
            $params['sort'] = $request->input('sort');
        }
        else
        {
            $params['sort'] = 'release_date';
        }
        if($request->has('order'))
        {
            $params['order'] = $request->input('order');
        }
        else
        {
            $params['order'] = 'asc';
        }
        if($job < 0)
        {
            $roles = Role::join('episodes', function($join) use($id)
            {
                $join->on('episodes.id', '=', 'roles.episode_id')
                    ->where('roles.sc_id', '=', $id);
            })->join('dramas', function($join) use($id)
            {
                $join->on('dramas.id', '=', 'roles.drama_id')
                    ->where('roles.sc_id', '=', $id);
            })->select('roles.drama_id as drama_id', 'dramas.title as drama_title', 'episode_id',
                'episodes.title as episode_title', 'job', 'note', 'release_date')
                ->orderBy($params['sort'], $params['order'])->get();
        }
        else
        {
            $roles = Role::join('episodes', function($join) use($id, $job)
            {
                $join->on('episodes.id', '=', 'roles.episode_id')
                    ->where('roles.sc_id', '=', $id)->where('roles.job', '=', $job);
            })->join('dramas', function($join) use($id)
            {
                $join->on('dramas.id', '=', 'roles.drama_id')
                    ->where('roles.sc_id', '=', $id);
            })->select('roles.drama_id as drama_id', 'dramas.title as drama_title', 'episode_id',
                'episodes.title as episode_title', 'job', 'note', 'release_date')
                ->orderBy($params['sort'], $params['order'])->get();
        }
        if($job == -2)
        {
            $roles = $roles->groupBy('job');
        }
        else
            $roles = $roles->groupBy('drama_id');
        return view('sc.dramas', ['params' => $params, 'roles' => $roles, 'sc' => $sc, 'job' => $job]);
    }

    public function versions($id)
    {
        $sc = Sc::find($id, ['id', 'name']);
        $versions = Scver::with(['user' => function($query)
        {
            $query->select('id', 'name');
        }, 'club' => function($query)
        {
            $query->select('id', 'name');
        }])
            ->select('user_id', 'first', 'name', 'alias', 'club_id', 'jobs', 'weibo', 'information', 'created_at', 'updated_at')
            ->where('sc_id', $id)->orderBy('updated_at', 'desc')->get();
        return view('sc.versions', ['sc' => $sc, 'versions' => $versions]);
    }
}
