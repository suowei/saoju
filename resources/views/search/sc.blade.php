@extends('app')

@section('title', '搜索SC - '.$keyword.' - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4>搜索SC：{{ $keyword }}</h4>
                <h4 class="text-success">搜索结果：{{ count($scs) }}位</h4>
                @foreach($scs as $sc)
                    <div class="drama">
                        <a href="{{ url('/sc/'.$sc->id) }}" target="_blank">{{ $sc->name }}</a>
                        <span class="text-muted">{{ $sc->alias }} {{ $sc->club->name or '' }} {{ $sc->jobs }}</span>
                    </div>
                @endforeach
                <?php echo $scs->appends(['type' => 1, 'keyword' => $keyword])->render(); ?>
            </div>
            <div class="col-md-3">
                <h4 class="text-warning">没找到？来这里添加<span class="glyphicon glyphicon-hand-down"></span></h4>
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/sc/create') }}">
                        <span class="glyphicon glyphicon-plus"></span> 添加SC信息
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
