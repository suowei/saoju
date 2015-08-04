@extends('app')

@section('title', '搜索社团 - '.$keyword.' - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4>搜索社团、工作室：{{ $keyword }}</h4>
                <h4 class="text-success">搜索结果：{{ count($clubs) }}个</h4>
                @foreach($clubs as $club)
                    <div class="drama">
                        <a href="{{ url('/club/'.$club->id) }}" target="_blank">{{ $club->name }}</a>
                    </div>
                @endforeach
                <?php echo $clubs->appends(['type' => 2, 'keyword' => $keyword])->render(); ?>
            </div>
            <div class="col-md-3">
                <h4 class="text-warning">没找到？来这里添加<span class="glyphicon glyphicon-hand-down"></span></h4>
                <p>
                    <a class="btn btn-info btn-xs" href="{{ url('/club/create') }}">
                        <span class="glyphicon glyphicon-plus"></span> 添加社团信息
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
