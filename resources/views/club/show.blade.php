@extends('app')

@section('title', $club->name.' - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>
                    {{ $club->name }}
                    <a class="btn btn-success btn-xs" href="{{ url('/screv/create?club='.$club->id) }}">
                        <span class="glyphicon glyphicon-pencil"></span> 评论此社团
                    </a>
                </h3>
                <div>
                    {!! $club->information !!}
                </div>
                <h3 class="text-success">成员</h3>
                <p>
                    @foreach($scs as $sc)
                        <a href="{{ url('/sc/'.$sc->id) }}" target="_blank">{{ $sc->name }}</a>&nbsp;
                    @endforeach
                </p>
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
