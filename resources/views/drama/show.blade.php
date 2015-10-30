@extends('app')

@section('title', $drama->title.' - ')

@section('meta')
    <meta name="description" content="广播剧《{{ $drama->title }}》">
    <meta name="keywords" content="广播剧《{{ $drama->title }}》">
@endsection

@section('css')
    <link href="{{ asset('/css/star-rating.min.css') }}" rel="stylesheet">
    <link href="http://cdn.bootcss.com/bootstrap-tagsinput/0.5.0/bootstrap-tagsinput.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-4">
                    <div id="carousel" class="carousel slide">
                        <ol class="carousel-indicators">
                            <?php $i = 0; ?>
                            @foreach($episodes as $episode)
                                <li data-target="#carousel" data-slide-to="{{ $i }}"@if($i==0)class="active"@endif></li>
                                    <?php $i++; ?>
                                @endforeach
                        </ol>
                        <div class="carousel-inner" role="listbox">
                            <?php $i = 0; ?>
                                @foreach($episodes as $episode)
                                <div @if($i==0)class="item active"@else class="item"@endif>
                                    <a href="{{ url('/episode/'.$episode->id) }}" target="_blank">
                                        <img src="{{ $episode->poster_url }}" alt="海报">
                                    </a>
                                </div>
                                    <?php $i++; ?>
                            @endforeach
                        </div>
                        <a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        </a>
                        <a class="right carousel-control" href="#carousel" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>
                <div class="col-md-8">
                    <h3>《{{ $drama->title }}》</h3>
                    <p><span class="text-muted">副标题及别名：</span>{{ $drama->alias ? $drama->alias : '无' }}</p>
                    <div class="row">
                        <div class="col-md-5">
                            <p>
                                <span class="text-muted">性向：</span>
                                @if($drama->type == 0)
                                    耽美
                                @elseif($drama->type == 1)
                                    全年龄
                                @elseif($drama->type == 2)
                                    言情
                                @else
                                    百合
                                @endif
                            </p>
                            <p>
                                <span class="text-muted">时代：</span>
                                @if($drama->era == 0)
                                    现代
                                @elseif($drama->era == 1)
                                    古风
                                @elseif($drama->era == 2)
                                    民国
                                @elseif($drama->era == 3)
                                    未来
                                @else
                                    其他
                                @endif
                            </p>
                            <p><span class="text-muted">其他：</span>{{ $drama->genre ? $drama->genre : '无' }}</p>
                            <p><span class="text-muted">原创性：</span>{{ $drama->original == 1 ? '原创' : '改编' }}</p>
                            <p><span class="text-muted">期数：</span>{{ $drama->count }}</p>
                            <p>
                                <span class="text-muted">进度：</span>
                                @if($drama->state == 0)
                                    连载
                                @elseif($drama->state == 1)
                                    完结
                                @else
                                    已坑
                                @endif
                            </p>
                        </div>
                        <div class="col-md-7 tagcloud">
                            <span class="glyphicon glyphicon-tags"></span> 热门标签：
                            @foreach($tagmaps as $tagmap)
                                <a href="{{ url('/drama/tag/'.$tagmap->tag->name) }}" target="_blank">{{ $tagmap->tag->name }}</a>({{ $tagmap->count }})&nbsp;
                            @endforeach
                        </div>
                    </div>
                    <p><span class="text-muted">主役CV：</span>{{ $drama->sc }}</p>
                    <p>
                        <span class="introduction content-pre-line">@if($drama->introduction){{ $drama->introduction }}@endif</span>
                    </p>
                </div>
            </div>

            <br>
            @if($favorite && $favorite->tags)
                <p>
                    <span class="glyphicon glyphicon-tags"></span>
                    我的标签：
                    @foreach(explode(',', $favorite->tags) as $tag)
                        <a class="btn btn-default btn-xs" href="{{ url('/user/'.$favorite->user_id.'/favorites?tag='.$tag) }}"
                           target="_blank">{{ $tag }}</a>
                    @endforeach
                </p>
            @endif
            <p>
                @if(Auth::check())
                    @if($favorite)
                        <span class="pull-left">
                            <span class="glyphicon glyphicon-headphones"></span>
                            我@if($favorite->type == 0)想听@elseif($favorite->type == 1)在追@elseif($favorite->type == 2)听过@elseif($favorite->type == 3)搁置@else抛弃@endif这部剧&nbsp;
                        </span>
                        <span class="pull-left">
                            @if($favorite->rating != 0)
                                <input type="number" class="rating form-control" value="{{ $favorite->rating }}" data-size="rating-user-favorite" data-show-clear="false" readonly>
                            @endif
                        </span>
                        <span>&nbsp;
                            <a class="btn btn-info btn-xs" href="{{ url('/favorite/'.$drama->id.'/edit') }}">
                                修改收藏与评论
                            </a>
                            <a class="btn btn-warning btn-xs" data-toggle="modal" href="#favModal" data-favorite="{{ $favorite }}"
                               data-action="{{ url('/favorite/'.$favorite->id) }}" data-method="PUT" data-idname="drama_id">修改收藏</a>
                            <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/favorite/'.$favorite->id) }}">删除收藏</a>
                        </span>
                    @else
                        <a class="btn btn-info btn-xs" href="{{ url('/favorite/create?drama='.$drama->id) }}">
                            收藏本剧并写评
                        </a>
                        <a class="btn btn-warning btn-xs" data-toggle="modal" data-target="#favModal"
                           data-action="{{ url('/favorite') }}" data-method="POST" data-idname="drama_id" data-idvalue="{{ $drama->id }}">
                            <span class="glyphicon glyphicon-gift"></span> 收藏本剧
                        </a>
                    @endif
                @else
                    <a class="btn btn-info btn-xs" href="{{ url('/favorite/create?drama='.$drama->id) }}">
                        收藏本剧并写评
                    </a>
                @endif
                    <a class="btn btn-success btn-xs" href="{{ url('/review/create?drama='.$drama->id) }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 写本剧评论
                    </a>
                    <a class="btn btn-primary btn-xs" href="{{ url('/item/create?drama='.$drama->id) }}">
                        <span class="glyphicon glyphicon-plus"></span> 添加到剧单
                    </a>
            </p>

            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist" id="episodeTab">
                    @foreach ($episodes as $episode)
                        <li role="presentation">
                            <a href="#{{ $episode->id }}" aria-controls="{{ $episode->title }}" role="tab" data-toggle="tab">{{ $episode->title }}</a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content" id="dramaTabContent">
                    @foreach ($episodes as $episode)
                        <div role="tabpanel" class="tab-pane fade" id="{{ $episode->id }}">
                            <p>
                                @if(Auth::check())
                                    @if(isset($epfavs[$episode->id]) && $favorite = $epfavs[$episode->id])
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
                                            <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/epfav/'.$episode->id) }}">删除收藏</a>
                                        </span>
                                    @else
                                        <a class="btn btn-info btn-xs" href="{{ url('/epfav/create?episode='.$episode->id) }}">
                                            收藏本期并写评
                                        </a>
                                        <a class="btn btn-warning btn-xs" data-toggle="modal" data-target="#favModal"
                                           data-action="{{ url('/epfav') }}" data-method="POST" data-idname="episode_id" data-idvalue="{{ $episode->id }}">
                                            <span class="glyphicon glyphicon-gift"></span> 收藏本期
                                        </a>
                                    @endif
                                @else
                                    <a class="btn btn-info btn-xs" href="{{ url('/epfav/create?episode='.$episode->id) }}">
                                        收藏本期并写评
                                    </a>
                                @endif
                                <a class="btn btn-success btn-xs" href="{{ url('/review/create?drama='.$drama->id.'&episode='.$episode->id) }}">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 写本期评论
                                </a>
                                <a class="btn btn-primary btn-xs" href="{{ url('/episode/'.$episode->id) }}" target="_blank">
                                    <span class="glyphicon glyphicon-share-alt"></span> 去分集页面
                                </a>
                                <a class="btn btn-danger btn-xs" role="button" data-toggle="collapse" href="#sc{{ $episode->id }}">
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                    制作组名单 <span class="caret"></span>
                                </a>
                            </p>
                            <p class="collapse content-pre-line" id="sc{{ $episode->id }}">{{ $episode->sc }}</p>
                            @if ($episode->url)
                                <p>
                                    <span class="text-muted">发布地址：</span>
                                    <a href="{{ $episode->url }}" target="_blank" title="{{ $episode->url }}">{{ $episode->url }}</a>
                                </p>
                            @endif
                            <p><span class="text-muted">副标题：</span>{{ $episode->alias ? $episode->alias : '无' }}</p>
                            <p><span class="text-muted">发布时间：</span>{{ $episode->release_date }}</p>
                            <p><span class="text-muted">时长：</span>{{ $episode->duration.'分钟' }}</p>
                            @if ($episode->introduction)<p class="content-pre-line">{{ $episode->introduction }}</p>@endif
                        </div>
                    @endforeach
                </div>
            </div>

            @if (Auth::check())
                <div class="reviews">
                    <h4 class="text-success">我的评论</h4>
                    @foreach ($userReviews as $review)
                        <div class="review">
                            <div class="review-title">
                                {{ $review->created_at }}
                                @if ($review->episode_id) [<a href="{{ url('/episode/'.$review->episode_id) }}" target="_blank">{{ $review->episode->title }}</a>]@endif
                                {{ $review->title }}
                                <span class="pull-right">
                                    <a href="{{ url('/review/'.$review->id) }}" target="_blank">查看</a>
                                    <a class="text-muted" href="{{ url('/review/'.$review->id.'/edit') }}">修改</a>
                                    <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/review/'.$review->id) }}">删除</a>
                                </span>
                            </div>
                            <div class="review-content">{{ $review->content }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="reviews">
                <h4 class="text-success">最新评论<small>（<a href="{{ url('/drama/'.$drama->id.'/reviews') }}" target="_blank">查看全部{{ $drama->reviews }}篇评论</a>）</small></h4>
                @foreach ($reviews as $review)
                    <div class="review">
                        <div class="review-title">
                            <a href="{{ url('/user/'.$review->user->id) }}" target="_blank">{{ $review->user->name }}</a>
                            {{ $review->created_at }}
                            @if ($review->episode_id) [<a href="{{ url('/episode/'.$review->episode_id) }}" target="_blank">{{ $review->episode->title }}</a>]@endif
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
            <wb:share-button appkey="125628789" addition="number" type="button"></wb:share-button>
            <p>
                <a class="btn btn-success btn-xs" href="{{ url('/episode/create?drama='.$drama->id) }}">
                    <span class="glyphicon glyphicon-plus"></span> 更新剧集（添加分集）
                </a>
                <a class="btn btn-warning btn-xs" href="{{ url('/drama/'.$drama->id.'/edit') }}">
                    <span class="glyphicon glyphicon-edit"></span> 编辑剧集信息
                </a>
            </p>
            <p class="text-danger">
                <span class="glyphicon glyphicon-info-sign"></span>
                分集信息维护请进入分集页面操作
            </p>
            <p>
                <a class="btn btn-primary btn-xs" role="button" data-toggle="collapse" href="#sc">
                    查看关联SC <span class="caret"></span>
                </a>
            </p>
            <div class="panel panel-primary collapse" id="sc">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a href="{{ url('/drama/'.$drama->id.'/sc') }}" target="_blank">
                            <span class="glyphicon glyphicon-list-alt"></span> 主要SC
                        </a>
                        <small class="pull-right">
                            <a href="{{ url('/drama/'.$drama->id.'/sc') }}" target="_blank">
                                查看全部<span class="glyphicon glyphicon-menu-right"></span>
                            </a>
                        </small>
                    </h4>
                </div>
                <?php $jobs = ['原著', '策划', '导演', '编剧', '后期', '美工', '宣传', '填词', '翻唱', '歌曲后期', '其他staff', '主役', '协役', '龙套']; ?>
                <ul class="list-group">
                    @foreach ($roles as $role)
                        <li class="list-group-item">
                            {{ $role->note ? $role->note : $jobs[$role->job] }}：<a href="{{ url('/sc/'.$role->sc_id) }}" target="_blank">{{ $role->sc->name }}</a><br>
                        </li>
                    @endforeach
                </ul>
            </div>
            <p>
                <a class="btn btn-info btn-xs" href="{{ url('/drama/'.$drama->id.'/versions') }}" target="_blank">
                    <span class="glyphicon glyphicon-book" aria-hidden="true"></span> 查看版本列表
                </a>
                <a class="btn btn-danger btn-xs" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/drama/'.$drama->id) }}">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 删除本剧
                </a>
            </p>
            <p class="text-info"><span class="glyphicon glyphicon-hand-down"></span> 剧集海报，用于剧集列表的展示</p>
            <p><img src="{{ $drama->poster_url }}" class="img-responsive"></p>
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <span class="glyphicon glyphicon-step-forward"></span>
                        最新收录剧单<small>（<a href="{{ url('/drama/'.$drama->id.'/lists') }}"
                                         target="_blank">查看全部</a>）</small></h4>
                </div>
                <ul class="list-group">
                    @foreach ($lists as $list)
                        <li class="list-group-item">
                            <a href="{{ url('/list/'.$list->id) }}" target="_blank">{{ $list->title }}</a>
                            （<a href="{{ url('/user/'.$list->user_id) }}" target="_blank">{{ $list->user->name }}</a>）
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <span class="glyphicon glyphicon-gift"></span> 最新收藏
                        <small>（<a href="{{ url('/drama/'.$drama->id.'/favorites') }}" target="_blank">查看全部{{ $drama->favorites }}条收藏</a>）</small>
                    </h4>
                </div>
                <ul class="list-group">
                    @foreach ($favorites as $favorite)
                        <li class="list-group-item">
                            <a href="{{ url('/user/'.$favorite->user->id) }}" target="_blank">{{ $favorite->user->name }}</a>
                            {{ $favorite->updated_at->format('m-d') }}
                            @if($favorite->type == 0)想听@elseif($favorite->type == 1)在追@elseif($favorite->type == 2)听过@elseif($favorite->type == 3)搁置@else抛弃@endif
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
                    <div class="form-group" id="tagsinput">
                        <label class="col-md-2 control-label">标签：</label>
                        <div class="col-md-8">
                            <input type="text" class="tagsinput form-control" data-role="tagsinput" name="tags">
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
    <script src="http://cdn.bootcss.com/Readmore.js/2.0.5/readmore.min.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap-tagsinput/0.5.0/bootstrap-tagsinput.min.js"></script>

@endsection
