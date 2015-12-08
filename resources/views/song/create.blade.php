@extends('appzb')

@section('title', '添加歌曲 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">添加歌曲信息</h4>
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

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/song') }}" onsubmit="this.submit.disabled=true;this.submit.innerHTML='处理中';">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            @if($drama)<input type="hidden" name="drama_id" value="{{ $drama }}">@endif
                            @if($episode)<input type="hidden" name="episode_id" value="{{ $episode }}">@endif

                            <div class="form-group">
                                <label class="col-md-4 control-label">歌名</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="title" required="required" placeholder="必填" value="{{ old('title') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">副标题</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="alias" placeholder="如《XXX》第N期ED等" value="{{ old('alias') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">发布地址</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="url" value="{{ old('url') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">海报地址</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="poster_url" value="{{ old('poster_url') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">演唱者</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="artist" placeholder="必填" value="{{ old('artist') }}" required="required">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">制作名单</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="staff" rows="5" placeholder="必填" required="required">{{ old('staff') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">歌词</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="lyrics" rows="20">{{ old('lyrics') }}</textarea>
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
