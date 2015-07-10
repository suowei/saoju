@extends('app')

@section('title', '编辑我对《'.$drama->title.'》'.$episode->title.'的收藏与评论 - ')

@section('css')
    <link href="{{ asset('/css/star-rating.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">编辑我对《{{ $drama->title }}》{{ $episode->title }}的收藏与评论</div>
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

                        <form class="form-horizontal" id="favoriteEdit" role="form" method="POST" action="{{ url('/epfav2/'.$episode->id) }}">
                            <input type="hidden" name="_method" value="PUT">
                            {!! csrf_field() !!}
                            <input type="hidden" name="drama_id" value="{{ $episode->drama_id }}">

                            <div class="form-group">
                                <label class="col-md-2 control-label">状态：</label>
                                <div class="col-md-10">
                                    <label class="radio-inline">
                                        <input type="radio" name="type" value="0" checked><span class="btn btn-primary btn-xs">想听</span>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="type" value="2" @if($favorite->type == 2) checked @endif><span class="btn btn-success btn-xs">听过</span>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="type" value="4" @if($favorite->type == 4) checked @endif><span class="btn btn-danger btn-xs">抛弃</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" id="ratingEdit">
                                <label class="col-md-2 control-label">评分：</label>
                                <div class="col-md-10">
                                    <input type="number" class="rating form-control" name="rating" min=0 max=5 step=0.5 data-size="xxs" value="{{ $favorite->rating }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">标题：</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="title" placeholder="可不填" value="{{ $review ? $review->title : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">内容：</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="content" rows="15">{{ $review ? $review->content : '' }}</textarea>
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
@endsection
