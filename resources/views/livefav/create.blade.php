@extends('appzb')

@section('title', '收藏《'.$live->title.'》并评论 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">收藏《{{ $live->title }}》并评论</div>
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

                        <form class="form-horizontal" id="favoriteEdit" role="form" method="POST" action="{{ url('/livefav2') }}">
                            {!! csrf_field() !!}
                            <input type="hidden" name="live_id" value="{{ $live->id }}">

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
