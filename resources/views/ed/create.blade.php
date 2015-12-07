@extends('app')

@section('title', '《'.$drama->title.'》'.($episode?$episode->title:'').'关联歌曲 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">《{{ $drama->title }}》{{ $episode?$episode->title:'' }}关联歌曲</div>
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

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/ed') }}" onsubmit="this.submit.disabled=true;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="drama_id" value="{{ $drama->id }}">
                            <input type="hidden" name="episode_id" value="{{ $episode?$episode->id:0 }}">

                            <div class="form-group">
                                <label class="col-md-2 control-label">歌曲id</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="song" required="required" value="{{ old('song') }}">
                                    <p>打开歌曲页面，浏览器地址栏最后的数字即为歌曲id</p>
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
