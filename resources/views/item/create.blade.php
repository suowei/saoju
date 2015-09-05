@extends('app')

@section('title', '将《'.$drama->title.'》'.(isset($episode) ? $episode->title : '').'添加至剧单 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            将《{{ $drama->title }}》{{ isset($episode) ? $episode->title : '' }}添加至剧单
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

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/item') }}" onsubmit="this.submit.disabled=true;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="drama_id" value="{{ $drama->id }}">
                            <input type="hidden" name="episode_id" value="{{ isset($episode) ? $episode->id : 0 }}">

                            <div class="form-group">
                                <label class="col-md-2 control-label">剧单</label>
                                <div class="col-md-8">
                                    @forelse($lists as $list)
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="list_id" value="{{ $list->id }}"
                                                @if(old('list_id') == $list->id) checked @endif> {{ $list->title }}
                                            </label>
                                        </div>
                                    @empty
                                        <p>
                                            如还未创建剧单，请先
                                            <a href="{{ url('/list/create') }}" target="_blank">创建剧单</a>
                                            ，创建完毕后刷新本页即可。
                                        </p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">评论</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="review" rows="10">{{ old('review') }}</textarea>
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
