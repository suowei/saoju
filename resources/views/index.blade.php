@extends('app')

@section('meta')
    <meta name="description" content="中抓扫剧是一个中文广播剧扫剧平台，在这里你可以查看最新的广播剧及评论，写评并分类记录广播剧。">
    <meta name="keywords" content="广播剧,中文广播剧,中抓,剧评,扫剧">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <?php $days = [date("Y-m-d", strtotime("now")), date("Y-m-d", strtotime("-1 day")),
                            date("Y-m-d", strtotime("-2 day")), date("Y-m-d", strtotime("-3 day")),
                            date("Y-m-d", strtotime("-4 day")), date("Y-m-d", strtotime("-5 day")),
                            date("Y-m-d", strtotime("-6 day")), date("Y-m-d", strtotime("-7 day")),]; ?>
                    <div class="col-md-8">
                        <ul class="nav nav-tabs" id="dateTab">
                            <li role="presentation">
                                <a href="#{{ $days[0] }}" role="tab" data-toggle="tab">两日新剧</a>
                            </li>
                            @for($i = 2; $i <= 7; $i++)
                                <li role="presentation">
                                    <a href="#{{ $days[$i] }}" role="tab" data-toggle="tab">{{ date("m-d", strtotime("-".$i." day")) }}</a>
                                </li>
                            @endfor
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane" id="{{ $days[0] }}">
                                <ul class="list-group">
                                    @if(isset($episodes[$days[0]]))
                                        @foreach ($episodes[$days[0]] as $episode)
                                            <li class="list-group-item">
                                                <h4>
                                                    <a href="{{ url('/drama/'.$episode->drama_id) }}" target="_blank">{{ $episode->drama_title }}</a>
                                                    [<a href="{{ url('/episode/'.$episode->episode_id) }}" target="_blank">{{ $episode->episode_title }}</a>]
                                                    <small>@if($episode->alias){{ $episode->alias }}@endif</small>
                                                    <span class="pull-right">
                                                        <a href="{{ url('/episode/'.$episode->episode_id.'/reviews') }}" target="_blank">
                                                            {{ $episode->reviews }}评论
                                                        </a>
                                                    </span>
                                                </h4>
                                                {{ $episode->sc }}；
                                                <span class="text-muted">
                                                    @if($episode->type == 0)耽美@elseif($episode->type == 1)全年龄@elseif($episode->type == 2)言情@else百合@endif，
                                                    @if($episode->era == 0)现代@elseif($episode->era == 1)古风@elseif($episode->era == 2)民国
                                                    @elseif($episode->era == 3)未来@else其他时代@endif，
                                                    @if($episode->genre){{ $episode->genre }}，@endif
                                                    @if($episode->state == 0)连载@elseif($episode->state == 1)完结@else已坑@endif，
                                                    {{ $episode->duration }}分钟
                                                </span>
                                            </li>
                                        @endforeach
                                    @endif
                                    <div class="dateline">
                                        ↑{{ $days[0] }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↓{{ $days[1] }}
                                    </div>
                                        @if(isset($episodes[$days[1]]))
                                            @foreach ($episodes[$days[1]] as $episode)
                                                <li class="list-group-item">
                                                    <h4>
                                                        <a href="{{ url('/drama/'.$episode->drama_id) }}" target="_blank">{{ $episode->drama_title }}</a>
                                                        [<a href="{{ url('/episode/'.$episode->episode_id) }}" target="_blank">{{ $episode->episode_title }}</a>]
                                                        <small>@if($episode->alias){{ $episode->alias }}@endif</small>
                                            <span class="pull-right">
                                                <a href="{{ url('/episode/'.$episode->episode_id.'/reviews') }}" target="_blank">
                                                    {{ $episode->reviews }}评论
                                                </a>
                                            </span>
                                                    </h4>
                                                    {{ $episode->sc }}；
                                                <span class="text-muted">
                                                    @if($episode->type == 0)耽美@elseif($episode->type == 1)全年龄@elseif($episode->type == 2)言情@else百合@endif，
                                                    @if($episode->era == 0)现代@elseif($episode->era == 1)古风@elseif($episode->era == 2)民国
                                                    @elseif($episode->era == 3)未来@else其他时代@endif，
                                                    @if($episode->genre){{ $episode->genre }}，@endif
                                                    @if($episode->state == 0)连载@elseif($episode->state == 1)完结@else已坑@endif，
                                                    {{ $episode->duration }}分钟
                                                </span>
                                                </li>
                                            @endforeach
                                        @endif
                                </ul>
                            </div>
                            @for($i = 2; $i <= 7; $i++)
                                <div role="tabpanel" class="tab-pane" id="{{ $days[$i] }}">
                                    <ul class="list-group">
                                        @if(isset($episodes[$days[$i]]))
                                            @foreach ($episodes[$days[$i]] as $episode)
                                                <li class="list-group-item">
                                                    <h4>
                                                        <a href="{{ url('/drama/'.$episode->drama_id) }}" target="_blank">{{ $episode->drama_title }}</a>
                                                        [<a href="{{ url('/episode/'.$episode->episode_id) }}" target="_blank">{{ $episode->episode_title }}</a>]
                                                        <small>@if($episode->alias){{ $episode->alias }}@endif</small>
                                            <span class="pull-right">
                                                <a href="{{ url('/episode/'.$episode->episode_id.'/reviews') }}" target="_blank">
                                                    {{ $episode->reviews }}评论
                                                </a>
                                            </span>
                                                    </h4>
                                                    {{ $episode->sc }}；
                                                <span class="text-muted">
                                                    @if($episode->type == 0)耽美@elseif($episode->type == 1)全年龄@elseif($episode->type == 2)言情@else百合@endif，
                                                    @if($episode->era == 0)现代@elseif($episode->era == 1)古风@elseif($episode->era == 2)民国
                                                    @elseif($episode->era == 3)未来@else其他时代@endif，
                                                    @if($episode->genre){{ $episode->genre }}，@endif
                                                    @if($episode->state == 0)连载@elseif($episode->state == 1)完结@else已坑@endif，
                                                    {{ $episode->duration }}分钟
                                                </span>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            @endfor
                        </div>
                    </div>
                        <div class="col-md-4">
                            <p class="text-muted">
                                <span class="glyphicon glyphicon-map-marker"></span>
                                <a href="{{ url('/?type=-1') }}">全部</a>
                                @if($type == 0)/ 耽美@elseif($type == 1)/ 全年龄@elseif($type == 2)/ 言情@elseif($type == 3)/ 百合@endif
                                <span class="pull-right">
                                    切换至
                                    @if($type != 0)<a href="{{ url('/?type=0') }}">耽美</a>@endif
                                    @if($type != 1)<a href="{{ url('/?type=1') }}">全年龄</a>@endif
                                    @if($type != 2)<a href="{{ url('/?type=2') }}">言情</a>@endif
                                    @if($type != 3)<a href="{{ url('/?type=3') }}">百合</a>@endif
                                </span>
                            </p>
                            <div id="carousel" class="carousel slide">
                                <ol class="carousel-indicators">
                                    @if($count = count($top10))
                                        <li data-target="#carousel" data-slide-to="0" class="active"></li>
                                    @endif
                                    @for($i = 1; $i < $count; $i++)
                                        <li data-target="#carousel" data-slide-to="{{ $i }}"></li>
                                    @endfor
                                </ol>
                                <div class="carousel-inner" role="listbox">
                                    @if($count)
                                        <div class="item active">
                                            <a href="{{ url('/episode/'.$top10[0]->episode_id) }}" target="_blank">
                                                <img src="{{ $top10[0]->poster_url }}" class="center-block" style="max-height: 300px;" alt="{{ $top10[0]->drama_title }}{{ $top10[0]->episode_title }}">
                                            </a>
                                        </div>
                                    @endif
                                    @for($i = 1; $i < $count; $i++)
                                        <div class="item">
                                            <a href="{{ url('/episode/'.$top10[$i]->episode_id) }}" target="_blank">
                                                <img src="{{ $top10[$i]->poster_url }}" class="center-block" style="max-height: 300px;" alt="{{ $top10[$i]->drama_title }}{{ $top10[$i]->episode_title }}">
                                            </a>
                                        </div>
                                    @endfor
                                </div>
                                <a class="carousel-control" href="#carousel" role="button" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                </a>
                                <a class="myright carousel-control" href="#carousel" role="button" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                </a>
                            </div>
                        </div>
                </div>

                <h4 class="text-success">
                    <span class="glyphicon glyphicon-comment"></span> 最新评论（<a href="{{ url('/review') }}"） target="_blank">查看全部</a>）
                </h4>
                @foreach ($reviews as $review)
                    <div class="review">
                        <div class="review-title">
                            <a href="{{ url('/user/'.$review->user_id) }}" target="_blank">{{ $review->user->name }}</a> 评论
                            《<a href="{{ url('/drama/'.$review->drama_id) }}" target="_blank">{{ $review->drama_title }}</a>》
                            @if ($review->episode_id)
                                [<a href="{{ url('/episode/'.$review->episode_id) }}" target="_blank">{{ $review->episode->title }}</a>]
                            @endif
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
            <div class="col-md-3">
                <h4>
                    <a href="http://weibo.com/5243765174/CpK8UermN" target="_blank">
                        <span class="glyphicon glyphicon-link"></span><strong>2015年YS发剧统计半年版</strong>
                    </a>
                </h4>
                <h4>
                    <a style="color:red;" href="http://weibo.com/2304976825/Ctei24nej" target="_blank">
                        <span class="glyphicon glyphicon-link"></span><strong>2014年YS出剧清单及盘点</strong>
                    </a>
                </h4>
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/drama/create') }}" target="_blank">
                        <span class="glyphicon glyphicon-plus"></span> 添加剧集信息
                    </a>
                </p>
                <p class="text-danger">
                    <span class="glyphicon glyphicon-info-sign"></span>
                    剧集更新（添加分集）请先搜索出该剧，然后前往相应剧集页面操作。
                </p>
                <p class="text-success">
                    <span class="glyphicon glyphicon-tree-deciduous"></span>
                    所有注册用户都可以添加和修改信息，希望大家一起来丰富小站内容啊^ ^
                </p>
                <p>
                    <a class="btn btn-warning btn-xs" href="{{ url('/drama?type=0') }}" target="_blank">
                        <span class="glyphicon glyphicon-film"></span> 查看全部剧集
                    </a>
                    <a class="btn btn-warning btn-xs" href="{{ url('/sc') }}" target="_blank">
                        <span class="glyphicon glyphicon-camera"></span> 查看全部SC
                    </a>
                </p>
                <p>
                    <a class="btn btn-success btn-xs" href="{{ url('/episode?type=0') }}" target="_blank">
                        <span class="glyphicon glyphicon-facetime-video"></span> 查看全部分集
                    </a>
                    <a class="btn btn-success btn-xs" href="{{ url('/club') }}" target="_blank">
                        <span class="glyphicon glyphicon-ice-lolly"></span> 查看全部社团
                    </a>
                </p>
                <p>
                    <a class="btn btn-info btn-xs" href="{{ url('/review') }}" target="_blank">
                        <span class="glyphicon glyphicon-comment"></span> 查看全部评论
                    </a>
                    <a class="btn btn-info btn-xs" href="{{ url('/screv') }}" target="_blank">
                        <span class="glyphicon glyphicon-picture"></span> 查看SC社团印象
                    </a>
                </p>
                <p class="text-info">
                    <span class="glyphicon glyphicon-hand-down"></span> 意见建议捉虫反馈看这里
                </p>
                <p>
                    <a class="btn btn-danger btn-xs" href="{{ url('/bbs') }}" target="_blank">
                        <span class="glyphicon glyphicon-send"></span> 留言板
                    </a>
                </p>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title"><span class="glyphicon glyphicon-bullhorn"></span> 公告栏</h4>
                    </div>
                    <div class="list-group">
                        <a style="color:red;" href="{{ url('/bbs/topic/38') }}" class="list-group-item" target="_blank">
                            SC作品关联功能上线及首页排版调整
                        </a>
                        <a href="{{ url('/bbs/topic/37') }}" class="list-group-item" target="_blank">
                            SC、社团功能上线
                        </a>
                        <a href="{{ url('/bbs/topic/30') }}" class="list-group-item" target="_blank">
                            部分页面添加微博分享按钮
                        </a>
                        <a href="{{ url('/bbs/topic/27') }}" class="list-group-item" target="_blank">
                            简单删除功能上线，可删除自己添加的剧集和分集
                        </a>
                    </div>
                </div>
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h4 class="panel-title"><span class="glyphicon glyphicon-equalizer"></span> 月热评剧集</h4>
                    </div>
                    <ul class="list-group">
                        @foreach($hotDramas as $hot)
                            <li class="list-group-item">
                                    《<a href="{{ url('/drama/'.$hot->drama_id) }}" target="_blank">{{ $hot->title }}</a>》
                                    <span class="pull-right">
                                        <a href="{{ url('/drama/'.$hot->drama_id.'/reviews') }}" target="_blank">{{ $hot->review_count }}评论</a>
                                    </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h4 class="panel-title"><span class="glyphicon glyphicon-stats"></span> 月热门剧集</h4>
                    </div>
                    <ul class="list-group">
                        @foreach($hotFavorites as $hot)
                            <li class="list-group-item">
                                《<a href="{{ url('/drama/'.$hot->drama_id) }}" target="_blank">{{ $hot->title }}</a>》
                                <span class="pull-right">
                                    <a href="{{ url('/drama/'.$hot->drama_id.'/favorites') }}" target="_blank">{{ $hot->favorite_count }}收藏</a>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title"><span class="glyphicon glyphicon-heart"></span> 感谢各位的贡献！</h4>
                    </div>
                    <ul class="list-group">
                        @foreach($histories as $key => $history)
                            <li class="list-group-item">
                                <a href="{{ url('/user/'.$history->user_id) }}" target="_blank">{{ $history->user->name }}</a>
                                于 {{ $history->created_at->format('m-d H:i') }} 添加了
                                《<a href="{{ url('/drama/'.$history->model_id) }}" target="_blank">{{ $dramas[$key]->title }}</a>》
                                <span class="text-muted">{{ $dramas[$key]->sc }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
