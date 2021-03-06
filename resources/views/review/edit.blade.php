@extends('app')

@section('title', '编辑我对'.$review->drama->title.($review->episode_id?$review->episode->title:'').'的评论 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            编辑我对《{{ $review->drama->title }}》{{ $review->episode_id?$review->episode->title:'' }}的评论
                        </h4>
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

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/review/'.$review->id) }}">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-2 control-label">标题</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="title" placeholder="可不填" value="{{ $review->title }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">内容</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="content" required="required" rows="15">{{ $review->content }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">可见性</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="visible">
                                        <option value="0">首页可见</option>
                                        <option value="1" @if($review && $review->visible == 1) selected @endif>首页不可见，剧集页可见</option>
                                        <option value="2" @if($review && $review->visible == 2) selected @endif>首页、剧集页不可见，用户主页可见</option>
                                        <option value="3" @if($review && $review->visible == 3) selected @endif>仅自己可见</option>
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
