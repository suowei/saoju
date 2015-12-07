@extends('app')

@section('title', '《'.$drama->title.'》'.$episode->title.'的关联歌曲 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h3 class="text-success">
                    《{{ $drama->title }}》{{ $episode->title }}的关联歌曲
                    <a class="btn btn-primary btn-xs" href="{{ url('/ed/create?drama='.$episode->drama_id.'&episode='.$episode->id) }}" target="_blank">
                        <span class="glyphicon glyphicon-plus"></span> 添加关联歌曲
                    </a>
                </h3>
                @foreach($eds as $ed)
                    <div class="row">
                        《<a href="{{ url('/song/'.$ed->song_id) }}" target="_blank">{{ $ed->song->title }}</a>》
                        <a href="{{ url('/ed/'.$ed->id.'/edit') }}">修改</a>
                        <a data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/ed/'.$ed->id) }}">删除</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div id="deleteConfirmModal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">删除确认</h4>
                </div>
                <div class="modal-body">
                    确定要删除吗？
                </div>
                <div class="modal-footer">
                    <form class="form-inline" method="POST" action="/unknown">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-danger">确定</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
