@extends('app')

@section('title', 'SC列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">SC列表</h4>
                @foreach($scs as $sc)
                    <p>
                        <a href="{{ url('/sc/'.$sc->id) }}" target="_blank">{{ $sc->name }}</a>
                        <span class="text-muted">{{ $sc->alias }} {{ $sc->club->name }} {{ $sc->jobs }}</span>
                    </p>
                @endforeach
            </div>
            <div class="col-md-3">
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/sc/create') }}" target="_blank">
                        <span class="glyphicon glyphicon-plus"></span> 添加SC信息
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
