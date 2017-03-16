@extends('app')

@section('title', '评论'.$drama->title.(isset($episode)?$episode->title:'').' - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">评论《{{ $drama->title }}》@if(isset($episode)){{ $episode->title }}@endif</h4>
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

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/review') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="drama_id" value="{{ $drama->id }}">
                            @if(isset($episode))<input type="hidden" name="episode_id" value="{{ $episode->id }}">@endif

                            <div class="form-group">
                                <label class="col-md-2 control-label">标题</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="title" placeholder="可不填" value="{{ old('title') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">内容</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="content" required="required" rows="15">{{ old('content') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">可见性</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="visible">
                                        <option value="0">首页可见</option>
                                        <option value="1">首页不可见，剧集页可见</option>
                                        <option value="2">首页、剧集页不可见，用户主页可见</option>
                                        <option value="3">仅自己可见</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-2">
                                    <button type="submit" class="btn btn-primary">
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
