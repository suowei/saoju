@extends('app')

@section('title', $club->name.' - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>{{ $club->name }}</h3>
                <div>
                    {!! $club->information !!}
                </div>
            </div>
            <div class="col-md-3">
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/club/'.$club->id.'/edit') }}">
                        <span class="glyphicon glyphicon-edit"></span> 编辑社团信息
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
