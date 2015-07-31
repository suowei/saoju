@extends('app')

@section('title', '社团列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">社团及工作室列表</h4>
                <p>
                    @foreach($clubs as $club)
                        <a href="{{ url('/club/'.$club->id) }}" target="_blank">{{ $club->name }}</a>&nbsp;
                    @endforeach
                </p>
            </div>
            <div class="col-md-3">
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/club/create') }}" target="_blank">
                        <span class="glyphicon glyphicon-plus"></span> 添加社团或工作室信息
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
