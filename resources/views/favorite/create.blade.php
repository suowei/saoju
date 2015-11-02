@extends('app')

@section('title', '收藏《'.$drama->title.'》并评论 - ')

@section('css')
    <link href="{{ asset('/css/star-rating.min.css') }}" rel="stylesheet">
    <link href="http://cdn.bootcss.com/bootstrap-tagsinput/0.5.0/bootstrap-tagsinput.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">收藏《{{ $drama->title }}》并评论</div>
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

                        <form class="form-horizontal" id="favoriteEdit" role="form" method="POST" action="{{ url('/favorite2') }}">
                            {!! csrf_field() !!}
                            <input type="hidden" name="drama_id" value="{{ $drama->id }}">

                            <div class="form-group">
                                <label class="col-md-2 control-label">状态：</label>
                                <div class="col-md-10">
                                    <label class="radio-inline">
                                        <input type="radio" name="type" value="0"><span class="btn btn-primary btn-xs">想听</span>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="type" value="1" @if(old('type') == 1) checked @endif><span class="btn btn-info btn-xs">在追</span>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="type" value="2" @if(old('type') == 2) checked @endif><span class="btn btn-success btn-xs">听过</span>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="type" value="3" @if(old('type') == 3) checked @endif><span class="btn btn-warning btn-xs">搁置</span>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="type" value="4" @if(old('type') == 4) checked @endif><span class="btn btn-danger btn-xs">抛弃</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" id="ratingEdit">
                                <label class="col-md-2 control-label">评分：</label>
                                <div class="col-md-10">
                                    <input type="number" class="rating form-control" name="rating" min=0 max=5 step=0.5 data-size="xxs" value="{{ old('rating') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">标签：</label>
                                <div class="col-md-8">
                                    <input type="text" class="tagsinput form-control" data-role="tagsinput" name="tags" value="{{ old('tags') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">标题：</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="title" placeholder="可不填" value="{{ old('title') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">内容：</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="content" rows="15">{{ old('content') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-2">
                                    <button type="submit" class="btn btn-primary btn-sm">保存</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/star-rating.min.js') }}"></script>
    <script src="http://cdn.bootcss.com/bootstrap-tagsinput/0.5.0/bootstrap-tagsinput.min.js"></script>
@endsection
