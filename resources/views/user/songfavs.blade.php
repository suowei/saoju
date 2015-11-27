@extends('app')

@section('title', $user->name.'的歌曲收藏 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div>
                    @foreach($songfavs as $songfav)
                        <div class="drama">
                            <h4>
                                <a href="{{ url('/song/'.$songfav->song_id) }}" target="_blank">{{ $songfav->song->title }}</a>
                                <small>
                                    {{ $songfav->song->alias }}
                                    <span class="pull-right">
                                        {{ $songfav->created_at }}
                                        @if(Auth::id() == $user->id)
                                            <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/songfav/'.$songfav->song_id) }}">删除</a>
                                        @endif
                                    </span>
                                </small>
                            </h4>
                            <p>演唱：{{ $songfav->song->artist }}</p>
                            <p>{{ $songfav->song->staff }}</p>
                        </div>
                    @endforeach
                </div>
                <?php echo $songfavs->appends(['order' => $order])->render(); ?>
            </div>
            <div class="col-md-3">
                <h4 class="text-warning">排序看这里<span class="glyphicon glyphicon-hand-down"></span></h4>
                <p>
                    <a class="btn btn-success btn-xs" href="{{ url('/user/'.$user->id.'/songfavs?order=asc') }}"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span> 按时间正序</a>
                </p>
                <p>
                    <a class="btn btn-warning btn-xs" href="{{ url('/user/'.$user->id.'/songfavs?order=desc') }}"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span> 按时间倒序</a>
                </p>
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
