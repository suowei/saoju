@extends('app')

@section('title', '剧单列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">
                    剧单列表
                </h4>
                @foreach($lists as $list)
                    <div class="drama">
                        <a href="{{ url('/list/'.$list->id) }}" target="_blank">{{ $list->title }}</a>
                        创建者：<a href="{{ url('/user/'.$list->user_id) }}" target="_blank">{{ $list->user->name }}</a>
                    </div>
                @endforeach
                <?php echo $lists->appends(['sort' => $sort])->render(); ?>
            </div>
            <div class="col-md-3">
                <h4 class="text-warning">
                    排序看这里<span class="glyphicon glyphicon-hand-down" aria-hidden="true"></span>
                </h4>
                <p>
                    <a class="btn btn-success btn-xs" href="{{ url('/list?sort=favorites') }}">
                        <span class="glyphicon glyphicon-heart"></span> 按收藏人数倒序排列
                    </a>
                </p>
                <p>
                    <a class="btn btn-warning btn-xs" href="{{ url('/list?sort=id') }}">
                        <span class="glyphicon glyphicon-calendar"></span> 按添加顺序倒序排列
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
