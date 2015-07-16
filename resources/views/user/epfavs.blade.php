@extends('app')

@section('title', $user->name.'的收藏 - ')

@section('css')
    <link href="{{ asset('/css/star-rating.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">
                    <a href="{{ url('/user/'.$user->id) }}">{{ $user->name }}</a>@if($type == 0)想听@elseif($type == 2)听过@else抛弃@endif的剧（{{ $favorites->total() }}期）
                </h4>
                @foreach($favorites as $favorite)
                    <div class="drama">
                        <span class="pull-left">
                            《<a href="{{ url('/drama/'.$favorite->episode->drama_id) }}" target="_blank">{{ $favorite->episode->drama_title }}</a>》<a href="{{ url('/episode/'.$favorite->episode_id) }}" target="_blank">{{ $favorite->episode->title }}</a>
                        &nbsp;</span>
                        <span class="pull-left">
                            @if($favorite->rating != 0)
                                <input type="number" class="rating" value="{{ $favorite->rating }}" data-size="rating-user-favorite" data-show-clear="false" readonly>
                            @endif
                        </span>&nbsp;
                        @if(Auth::check() && Auth::id() == $user->id)
                            <a class="text-muted" data-toggle="modal" href="#favModal" data-favorite="{{ $favorite }}"
                               data-action="{{ url('/epfav/'.$favorite->episode_id) }}" data-method="PUT" data-idname="episode_id">修改</a>
                            <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/epfav/'.$favorite->episode_id) }}">删除</a>
                        @endif
                    </div>
                @endforeach
                <?php echo $favorites->appends(['sort' => $sort])->render(); ?>
            </div>
            <div class="col-md-3">
                <h4 class="text-warning">排序看这里<span class="glyphicon glyphicon-hand-down" aria-hidden="true"></span></h4>
                <p>
                    <a class="btn btn-success btn-xs" href="{{ url('/user/'.$user->id.'/epfavs/'.$type.'?sort=rating') }}"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> 按评分排序</a>
                </p>
                <p>
                    <a class="btn btn-warning btn-xs" href="{{ url('/user/'.$user->id.'/epfavs/'.$type.'?sort=updated_at') }}"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> 按时间排序</a>
                </p>
            </div>
        </div>
    </div>
    <div class="modal fade" id="favModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">收藏及评分</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="favoriteEdit" role="form" method="POST" action="action">
                        <input type="hidden" name="_method" value="PUT">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" value="id">
                        <div class="form-group">
                            <label class="col-md-2 control-label">状态：</label>
                            <div class="col-md-10">
                                <label class="radio-inline">
                                    <input type="radio" name="type" value="0"><span class="btn btn-primary btn-xs">想听</span>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="type" value="1"><span class="btn btn-info btn-xs">在追</span>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="type" value="2"><span class="btn btn-success btn-xs">听过</span>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="type" value="3"><span class="btn btn-warning btn-xs">搁置</span>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="type" value="4"><span class="btn btn-danger btn-xs">抛弃</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="ratingEdit">
                            <label class="col-md-2 control-label">评分：</label>
                            <div class="col-md-10">
                                <input type="number" class="rating form-control" name="rating" min=0 max=5 step=0.5 data-size="xxs">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-2">
                                <button type="submit" class="btn btn-info btn-sm">保存</button>
                            </div>
                        </div>
                    </form>
                </div>
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

@section('script')
    <script src="{{ asset('/js/star-rating.min.js') }}"></script>
@endsection
