@extends('appzb')

@section('title', '编辑《'.$live->title.'》的活动信息 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">编辑《{{ $live->title }}》的活动信息</div>
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

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/live/'.$live->id) }}">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-4 control-label">活动主题</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="title" required="required" placeholder="必填" value="{{ $live->title }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">开始时间</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="showtime" placeholder="形如2015-12-03 19:30" value="{{ date('Y-m-d H:i', strtotime($live->showtime)) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">活动信息</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="information" rows="10">{{ $live->information }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">海报地址</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="poster_url" value="{{ $live->poster_url }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">录音地址</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="record_url" value="{{ $live->record_url }}">
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
