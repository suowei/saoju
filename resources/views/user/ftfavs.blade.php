@extends('app')

@section('title', $user->name.'的节目收藏 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">
                    <a href="{{ url('/user/'.$user->id) }}">{{ $user->name }}</a>收藏的节目（{{ $ftfavs->total() }}个）
                </h4>
                @foreach($ftfavs as $ftfav)
                    <div class="row drama">
                        <div class="col-md-2">
                            <a href="{{ url('/ft/'.$ftfav->ft_id) }}" target="_blank">
                                <img src="{{ $ftfav->ft->poster_url }}" class="img-responsive" alt="海报">
                            </a>
                        </div>
                        <div class="col-md-10">
                            <h4>
                                <a href="{{ url('/ft/'.$ftfav->ft_id) }}" target="_blank">{{ $ftfav->ft->title }}</a>
                                <small>
                                        <span class="pull-right">
                                        {{ $ftfav->created_at }}
                                            @if(Auth::id() == $user->id)
                                                <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/ftfav/'.$ftfav->ft_id) }}">删除</a>
                                            @endif
                                    </span>
                                </small>
                            </h4>
                            <p>主持人：{{ $ftfav->ft->host }}</p>
                            <p>{{ $ftfav->ft->introduction }}</p>
                        </div>
                    </div>
                @endforeach
                <?php echo $ftfavs->appends(['order' => $order])->render(); ?>
            </div>
            <div class="col-md-3">
                <h4 class="text-warning">排序看这里<span class="glyphicon glyphicon-hand-down"></span></h4>
                <p>
                    <a class="btn btn-success btn-xs" href="{{ url('/user/'.$user->id.'/ftfavs?order=asc') }}"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span> 按时间正序</a>
                </p>
                <p>
                    <a class="btn btn-warning btn-xs" href="{{ url('/user/'.$user->id.'/ftfavs?order=desc') }}"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span> 按时间倒序</a>
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
