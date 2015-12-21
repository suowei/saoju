@extends('appzb')

@section('title', '编辑《'.$song->title.'》的歌曲信息 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">编辑《{{ $song->title }}》的歌曲信息</div>
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

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/song/'.$song->id) }}">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-4 control-label">歌名</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="title" required="required" placeholder="必填" value="{{ $song->title }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">副标题</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="alias" placeholder="如《XXX》第N期ED等" value="{{ $song->alias }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">歌曲地址</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="url" value="{{ $song->url }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">海报地址</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="poster_url" value="{{ $song->poster_url }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">演唱者</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="artist" placeholder="必填" value="{{ $song->artist }}" required="required">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">制作名单</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="staff" rows="5" required="required">{{ $song->staff }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">歌词</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="lyrics" rows="20">{{ $song->lyrics }}</textarea>
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
