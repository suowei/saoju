@extends('app')

@section('title', '搜索 - '.$keyword.' - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <h4>搜索：{{ $keyword }}</h4>
                <h4 class="text-success">剧集：{{ count($dramas) }}部</h4>
                @foreach($dramas as $drama)
                    <div class="row drama">
                        <div class="col-md-3">
                            <a href="{{ url('/drama/'.$drama->id) }}" target="_blank"><img src="{{ $drama->poster_url }}" class="img-responsive" alt="海报"></a>
                        </div>
                        <div class="col-md-9">
                            <p>
                                <a href="{{ url('/drama/'.$drama->id) }}" target="_blank">{{ $drama->title }}</a>
                                <span class="text-muted">{{ $drama->alias }}</span>
                            </p>
                            <p>
                                {{ $drama->sc }}
                            </p>
                            <p>
                                @if($drama->type == 0)
                                    耽美
                                @elseif($drama->type == 1)
                                    全年龄
                                @elseif($drama->type == 2)
                                    言情
                                @else
                                    百合
                                @endif，
                                @if($drama->era == 0)
                                    现代
                                @elseif($drama->era == 1)
                                    古风
                                @elseif($drama->era == 2)
                                    民国
                                @elseif($drama->era == 3)
                                    未来
                                @else
                                    其他时代
                                @endif，
                                @if($drama->genre)
                                    {{ $drama->genre }}，
                                @endif
                                {{ $drama->original == 1 ? '原创' : '改编' }}， {{ $drama->count }}期，
                                @if($drama->state == 0)
                                    连载
                                @elseif($drama->state == 1)
                                    完结
                                @else
                                    已坑
                                @endif
                            </p>
                            <p class="text-muted content-pre-line">{{ $drama->introduction }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-4">
                <h4 class="text-success">SC：{{ count($scs) }}位</h4>
                @foreach($scs as $sc)
                    <div class="drama">
                        <a href="{{ url('/sc/'.$sc->id) }}" target="_blank">{{ $sc->name }}</a>
                        <span class="text-muted">{{ $sc->alias }} {{ $sc->club->name or '' }} {{ $sc->jobs }}</span>
                    </div>
                @endforeach
                <h4 class="text-success">社团、工作室：{{ count($clubs) }}个</h4>
                @foreach($clubs as $club)
                    <div class="drama">
                        <a href="{{ url('/club/'.$club->id) }}" target="_blank">{{ $club->name }}</a>
                    </div>
                @endforeach
            </div>
            <div class="col-md-3">
                <h4 class="text-warning">没找到？来这里添加<span class="glyphicon glyphicon-hand-down"></span></h4>
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/drama/create') }}">
                        <span class="glyphicon glyphicon-plus"></span> 添加剧集信息
                    </a>
                </p>
                <p>
                    <a class="btn btn-warning btn-xs" href="{{ url('/sc/create') }}">
                        <span class="glyphicon glyphicon-plus"></span> 添加SC信息
                    </a>
                </p>
                <p>
                    <a class="btn btn-success btn-xs" href="{{ url('/club/create') }}">
                        <span class="glyphicon glyphicon-plus"></span> 添加社团信息
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
