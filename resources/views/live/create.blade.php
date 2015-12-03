@extends('appzb')

@section('title', '添加活动 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">添加活动信息</h4>
                    </div>
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

                        <p>
                            说明：<br>
                            录音地址请优先填写官录发布地址，若为个人录音，请经过录制者的允许后再填写。禁录禁传的活动录音就不必填写了^ ^
                        </p><br>

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/live') }}" onsubmit="this.submit.disabled=true;this.submit.innerHTML='处理中';">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-4 control-label">活动主题</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="title" required="required" placeholder="必填" value="{{ old('title') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">开始时间</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="showtime" placeholder="形如2015-12-03 19:30" value="{{ old('showtime') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">活动信息</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="information" rows="10">{{ old('information') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">海报地址</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="poster_url" value="{{ old('poster_url') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">录音地址</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="record_url" value="{{ old('record_url') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" name="submit" class="btn btn-primary">
                                        提交
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
