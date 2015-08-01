<?php

namespace App\Http\Controllers;

use App\Club;
use App\Sc;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
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
            'name' => 'required|max:255',
            'alias' => 'max:255',
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
        $sc->information = $request->input('information');
        $sc->user_id = $request->user()->id;
        if($sc->save())
        {
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

    public function show($id)
    {
        $sc = Sc::find($id, ['id', 'name', 'alias', 'club_id', 'jobs', 'information']);
        $sc->load(['club' => function($query)
        {
            $query->select('id', 'name');
        }]);
        return view('sc.show', ['sc' => $sc]);
    }

    public function edit($id)
    {
        $sc = Sc::find($id, ['id', 'name', 'alias', 'club_id', 'jobs', 'information']);
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
        $sc->information = $request->input('information');
        $sc->user_id = $request->user()->id;
        if($sc->save())
        {
            //获取sc当前job数组
            $jobs = DB::table('sc_job')->select('job_id')->where('sc_id', $id)->get();
            $job = [];
            foreach($jobs as $row)
            {
                $job[$row->job_id] = 1;
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
            for($i = 0; $i <= 10; $i++)
            {
                if(isset($job_input[$i]) && !isset($job[$i]))
                {
                    $adds[] = ['sc_id' => $id, 'job_id' => $i];//若输入有，原来没有，则添加职位
                }
                else if(isset($job[$i]) && !isset($job_input[$i]))
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
}
