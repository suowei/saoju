@extends('app')

@section('title', $sc->name.' - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>
                    {{ $sc->name }}
                    <a class="btn btn-success btn-xs" href="{{ url('/screv/create?sc='.$sc->id) }}">
                        <span class="glyphicon glyphicon-pencil"></span> 评论此SC
                    </a>
                </h3>
                <p>马甲及昵称：{{ $sc->alias ? $sc->alias : '无' }}</p>
                <p>社团或工作室：@if($sc->club_id)<a href="{{ url('/club/'.$sc->club_id) }}" target="_blank">{{ $sc->club->name }}</a>@else无@endif</p>
                <p>职位：{{ $sc->jobs ? $sc->jobs : '无' }}</p>
                <div>
                    {!! $sc->information !!}
                </div>
            </div>
            <div class="col-md-3">
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/sc/'.$sc->id.'/edit') }}">
                        <span class="glyphicon glyphicon-edit"></span> 编辑SC信息
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
