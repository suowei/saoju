@extends('app')

@section('title', '修改关联歌曲 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">修改关联歌曲</div>
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

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/ed/'.$ed->id) }}" onsubmit="this.submit.disabled=true;">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-2 control-label">剧集id</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="drama" required="required" value="{{ $ed->drama_id }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">分集id</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="episode" placeholder="若不属于任何分集，请填写0" required="required" value="{{ $ed->episode_id }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">歌曲id</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="song" required="required" value="{{ $ed->song_id }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-2">
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
