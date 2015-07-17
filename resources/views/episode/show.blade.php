@extends('app')

@section('title', '《'.$drama->title.'》'.$episode->title.' - ')

@section('meta')
    <meta name="description" content="广播剧《{{ $drama->title }}》{{ $episode->title }}">
    <meta name="keywords" content="广播剧《{{ $drama->title }}》{{ $episode->title }}">
@endsection

@section('css')
    <link href="{{ asset('/css/star-rating.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>
                    《<a href="{{ url('/drama/'.$episode->drama_id) }}">{{ $drama->title }}</a>》{{ $episode->title }}
                    @if($episode->alias){{ $episode->alias }}@endif
                </h3>
                <p>
                    @if(Auth::check())
                        @if($favorite)
                            <span class="pull-left">
                                <span class="glyphicon glyphicon-headphones"></span>
                                我@if($favorite->type == 0)想听@elseif($favorite->type == 2)听过@else抛弃@endif这期&nbsp;
                            </span>
                            <span class="pull-left">
                                @if($favorite->rating != 0)
                                    <input type="number" class="rating form-control" value="{{ $favorite->rating }}" data-size="rating-user-favorite" data-show-clear="false" readonly>
                                @endif
                            </span>
                            <span>&nbsp;
                                <a class="btn btn-info btn-xs" href="{{ url('/epfav/'.$episode->id.'/edit') }}">
                                    修改收藏与评论
                                </a>
                                <a class="btn btn-warning btn-xs" data-toggle="modal" href="#favModal" data-favorite="{{ $favorite }}"
                                   data-action="{{ url('/epfav/'.$episode->id) }}" data-method="PUT" data-idname="episode_id">修改收藏</a>
                                <a class="btn btn-danger btn-xs" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/epfav/'.$episode->id) }}">删除收藏</a>
                            </span>
                        @else
                            <a class="btn btn-info btn-xs" href="{{ url('/epfav/create?episode='.$episode->id) }}">
                                <span class="glyphicon glyphicon-gift"></span> 收藏并
                                <span class="glyphicon glyphicon-pencil"></span> 写评
                            </a>
                            <a class="btn btn-warning btn-xs" data-toggle="modal" data-target="#favModal"
                               data-action="{{ url('/epfav') }}" data-method="POST" data-idname="episode_id" data-idvalue="{{ $episode->id }}">
                                <span class="glyphicon glyphicon-gift"></span> 收藏本期
                            </a>
                        @endif
                    @else
                        <a class="btn btn-info btn-xs" href="{{ url('/epfav/create?episode='.$episode->id) }}">
                            <span class="glyphicon glyphicon-gift"></span> 收藏并
                            <span class="glyphicon glyphicon-pencil"></span> 写评
                        </a>
                    @endif
                    <a class="btn btn-success btn-xs" href="{{ url('/review/create?drama='.$episode->drama_id.'&episode='.$episode->id) }}">
                        <span class="glyphicon glyphicon-pencil"></span> 写本期评论
                    </a>
                </p>

                <div class="row">
                    <div class="col-md-6">
                        <p><span class="text-muted">时长：</span>{{ $episode->duration.'分钟' }}</p>
                        <p><span class="text-muted">发布时间：</span>{{ $episode->release_date }}</p>
                        <p class="content-pre-line">{{ $episode->sc }}</p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <a href="{{ $episode->poster_url  }}" target="_blank">
                                <img src="{{ $episode->poster_url }}" class="img-responsive" alt="海报">
                            </a>
                        </p>
                    </div>
                </div>
                @if ($episode->introduction)<p class="content-pre-line">{{ $episode->introduction }}</p>@endif
                <p><span class="text-muted">发布地址：</span>@if ($episode->url)<a href="{{ $episode->url }}" target="_blank">{{ $episode->url }}</a>@else未知@endif</p>

                @if (Auth::check())
                    <div class="reviews">
                        <h4 class="text-success">我的评论</h4>
                        @foreach ($userReviews as $review)
                            <div class="review">
                                <div class="review-title">
                                    <div class="row">
                                        <div class="col-md-10">
                                            {{ $review->created_at }}
                                            {{ $review->title }}
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <a href="{{ url('/review/'.$review->id) }}" target="_blank">查看</a>
                                            <a class="text-muted" href="{{ url('/review/'.$review->id.'/edit') }}">修改</a>
                                            <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/review/'.$review->id) }}">删除</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="review-content">{{ $review->content }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="reviews">
                    <h4 class="text-success">最新评论<small>（<a href="{{ url('/episode/'.$episode->id.'/reviews') }}" target="_blank">查看全部{{ $episode->reviews }}篇评论</a>）</small></h4>
                    @foreach ($reviews as $review)
                        <div class="review">
                            <div class="review-title">
                                <a href="{{ url('/user/'.$review->user->id) }}" target="_blank">{{ $review->user->name }}</a>
                                {{ $review->created_at }}
                                {{ $review->title }}
                                <span class="pull-right">
                                    <a href="{{ url('/review/'.$review->id) }}" target="_blank">查看</a>
                                </span>
                            </div>
                            <div class="review-content">{{ $review->content }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-3">
                <br><wb:share-button appkey="125628789" addition="number" type="button"></wb:share-button>
                <h4 class="text-warning">信息维护看这里<span class="glyphicon glyphicon-hand-down" aria-hidden="true"></span></h4>
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/episode/'.$episode->id.'/edit') }}">
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> 编辑本集信息
                    </a>
                </p>
                <p>
                    <a class="btn btn-success btn-xs" href="{{ url('/episode/'.$episode->id.'/histories') }}" target="_blank">
                        <span class="glyphicon glyphicon-book" aria-hidden="true"></span> 查看编辑历史
                    </a>
                </p>
                <p>
                    <a class="btn btn-danger btn-xs" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/episode/'.$episode->id) }}">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 删除本期分集
                    </a>
                </p>
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 class="panel-title"><span class="glyphicon glyphicon-gift"></span> 最新收藏<small>（<a href="{{ url('/episode/'.$episode->id.'/favorites') }}" target="_blank">查看全部{{ $episode->favorites }}条收藏</a>）</small></h4>
                    </div>
                    <ul class="list-group">
                        @foreach ($favorites as $favorite)
                            <li class="list-group-item">
                                <a href="{{ url('/user/'.$favorite->user->id) }}" target="_blank">{{ $favorite->user->name }}</a>
                                {{ $favorite->updated_at->format('m-d') }}
                                @if($favorite->type == 0)想听@elseif($favorite->type == 2)听过@else抛弃@endif
                            </li>
                        @endforeach
                    </ul>
                </div>
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
