@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">发表评论</div>
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

                        <h4>评论{{ $drama->title }}@if ($episode){{ $episode->title }}@endif</h4>

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/review') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="drama_id" value="{{ $drama->id }}">
                            @if ($episode)<input type="hidden" name="episode_id" value="{{ $episode->id }}">@endif
                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">

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
