@extends('app')

@section('title', '我的待听列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    @if($type == 0)
                        <span class="label label-primary">待听列表</span>
                    @else
                        <a href="{{ url('/playlist?type=0') }}">待听列表</a>
                    @endif&nbsp;
                    @if($type == 1)
                            <span class="label label-primary">已听</span>
                    @else
                        <a href="{{ url('/playlist?type=1') }}">已听</a>
                    @endif
                        &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-ok text-success"></span> 标记已听
                        &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-remove text-danger"></span> 移除
                </p>
                @foreach($playlists as $playlist)
                    <div class="drama">
                            《<a href="{{ url('/drama/'.$playlist->episode->drama_id) }}" target="_blank">{{ $playlist->episode->drama_title }}</a>》<a href="{{ url('/episode/'.$playlist->episode_id) }}" target="_blank">{{ $playlist->episode->episode_title }}</a>
                            @if($type == 0)&nbsp;
                                <a href="{{ url('/playlist/'.$playlist->episode_id.'/edit') }}">
                                    <span class="glyphicon glyphicon-ok text-success"></span>
                                </a>
                            @endif&nbsp;
                        <a data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/playlist/'.$playlist->episode_id) }}">
                            <span class="glyphicon glyphicon-remove text-danger"></span>
                        </a><br>
                        <a href="{{ $playlist->episode->url }}" target="_blank">{{ $playlist->episode->url }}</a>
                    </div>
                @endforeach
                <?php echo $playlists->appends(['type' => $type])->render(); ?>
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
