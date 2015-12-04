@extends('appzb')

@section('title', '编辑'.$ft->title.'的信息 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">编辑节目信息</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/ft/'.$ft->id) }}">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-4 control-label">节目名</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="title" placeholder="必填" required="required" value="{{ $ft->title }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">主持人</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="host" value="{{ $ft->host }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">海报地址</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="poster_url" value="{{ $ft->poster_url }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">节目介绍</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="introduction" rows="5">{{ $ft->introduction }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        修改
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
