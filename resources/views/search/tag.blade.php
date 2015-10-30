@extends('app')

@section('title', '标签搜索 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">标签搜索</h4>
                @if(isset($message))
                    <h4>{{ $message }}</h4>
                @endif
                <form class="form-inline" role="search" method="GET" action="{{ url('/search/tag') }}">
                    <div class="input-group">
                        <input type="text" class="form-control" name="tag">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-search"></span></button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
