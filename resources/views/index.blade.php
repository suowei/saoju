@extends('app')

@section('meta')
    <meta name="description" content="中抓扫剧是一个中文广播剧扫剧平台，在这里你可以查看最新的广播剧信息及评论，分类记录广播剧，打分，写评。">
    <meta name="keywords" content="广播剧,中文广播剧,中抓,剧评,扫剧">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <p class="text-muted">
                    <span class="glyphicon glyphicon-map-marker"></span>
                    <a href="{{ url('/?type=-1') }}">全部</a>
                    @if($type == 0)
                        / 耽美
                    @elseif($type == 1)
                        / 全年龄
                    @elseif($type == 2)
                        / 言情
                    @elseif($type == 3)
                        / 百合
                    @endif
                    <span class="pull-right">
                        <span class="glyphicon glyphicon-repeat"></span>
                        切换至
                        @if($type != 0)<a href="{{ url('/?type=0') }}">耽美</a>@endif
                        @if($type != 1)<a href="{{ url('/?type=1') }}">全年龄</a>@endif
                        @if($type != 2)<a href="{{ url('/?type=2') }}">言情</a>@endif
                        @if($type != 3)<a href="{{ url('/?type=3') }}">百合</a>@endif
                    </span>
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title"><span class="glyphicon glyphicon-music"></span> 两日新剧</h4>
                            </div>
                            <ul class="list-group">
                                @foreach($todays as $episode)
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
                                        {{ $episode->sc }}
                                        <div class="text-muted">
                                            @if($type < 0)
                                                @if($episode->type == 0)
                                                    耽美，
                                                @elseif($episode->type == 1)
                                                    全年龄，
                                                @elseif($episode->type == 2)
                                                    言情，
                                                @else
                                                    百合，
                                                @endif
                                            @endif
                                            @if($episode->era == 0)
                                                现代，
                                            @elseif($episode->era == 1)
                                                古风，
                                            @elseif($episode->era == 2)
                                                民国，
                                            @elseif($episode->era == 3)
                                                未来，
                                            @else
                                                其他时代，
                                            @endif
                                            @if($episode->genre)
                                                {{ $episode->genre }}，
                                            @endif
                                            @if($episode->state == 0)
                                                连载，
                                            @elseif($episode->state == 1)
                                                完结，
                                            @else
                                                已坑，
                                            @endif
                                            {{ $episode->duration }}分钟
                                        </div>
                                    </li>
                                @endforeach
                                <div class="text-center">
                                    ↑{{ date('Y-m-d') }}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    ↓{{ date('Y-m-d', strtotime('-1 day')) }}
                                </div>
                                @foreach($yesterdays as $episode)
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
                                        {{ $episode->sc }}
                                        <div class="text-muted">
                                            @if($type < 0)
                                                @if($episode->type == 0)
                                                    耽美，
                                                @elseif($episode->type == 1)
                                                    全年龄，
                                                @elseif($episode->type == 2)
                                                    言情，
                                                @else
                                                    百合，
                                                @endif
                                            @endif
                                            @if($episode->era == 0)
                                                现代，
                                            @elseif($episode->era == 1)
                                                古风，
                                            @elseif($episode->era == 2)
                                                民国，
                                            @elseif($episode->era == 3)
                                                未来，
                                            @else
                                                其他时代，
                                            @endif
                                            @if($episode->genre)
                                                {{ $episode->genre }}，
                                            @endif
                                            @if($episode->state == 0)
                                                连载，
                                            @elseif($episode->state == 1)
                                                完结，
                                            @else
                                                已坑，
                                            @endif
                                            {{ $episode->duration }}分钟
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-success">
                            <div class="panel-heading"><h4 class="panel-title"><span class="glyphicon glyphicon-headphones"></span> 一周新剧</h4></div>
                            <ul class="list-group">
                                @for($i = 0, $count = count($todays) + count($yesterdays), $length = count($thisweeks); $i < $count && $i < $length; $i++)
                                    <?php $episode = $thisweeks[$i]; ?>
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
                                        {{ $episode->sc }}
                                        <div class="text-muted">
                                            {{ $episode->release_date }}
                                            @if($type < 0)
                                                @if($episode->type == 0)
                                                    耽美，
                                                @elseif($episode->type == 1)
                                                    全年龄，
                                                @elseif($episode->type == 2)
                                                    言情，
                                                @else
                                                    百合，
                                                @endif
                                            @endif
                                            @if($episode->era == 0)
                                                现代，
                                            @elseif($episode->era == 1)
                                                古风，
                                            @elseif($episode->era == 2)
                                                民国，
                                            @elseif($episode->era == 3)
                                                未来，
                                            @else
                                                其他时代，
                                            @endif
                                            @if($episode->genre)
                                                {{ $episode->genre }}，
                                            @endif
                                            @if($episode->state == 0)
                                                连载，
                                            @elseif($episode->state == 1)
                                                完结，
                                            @else
                                                已坑，
                                            @endif
                                            {{ $episode->duration }}分钟
                                        </div>
                                    </li>
                                @endfor
                                    @if($i < $length)
                                        <div class="text-center" data-toggle="collapse" data-target="#week">
                                            <span class="caret"></span>
                                        </div>
                                        <div class="collapse" id="week">
                                            @while($i < $length)
                                                <?php $episode = $thisweeks[$i++]; ?>
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
                                                    {{ $episode->sc }}
                                                    <div class="text-muted">
                                                        {{ $episode->release_date }}
                                                        @if($type < 0)
                                                            @if($episode->type == 0)
                                                                耽美，
                                                            @elseif($episode->type == 1)
                                                                全年龄，
                                                            @elseif($episode->type == 2)
                                                                言情，
                                                            @else
                                                                百合，
                                                            @endif
                                                        @endif
                                                        @if($episode->era == 0)
                                                            现代，
                                                        @elseif($episode->era == 1)
                                                            古风，
                                                        @elseif($episode->era == 2)
                                                            民国，
                                                        @elseif($episode->era == 3)
                                                            未来，
                                                        @else
                                                            其他时代，
                                                        @endif
                                                        @if($episode->genre)
                                                            {{ $episode->genre }}，
                                                        @endif
                                                        @if($episode->state == 0)
                                                            连载，
                                                        @elseif($episode->state == 1)
                                                            完结，
                                                        @else
                                                            已坑，
                                                        @endif
                                                        {{ $episode->duration }}分钟
                                                    </div>
                                                </li>
                                            @endwhile
                                        </div>
                                    @endif
                            </ul>
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
                    <a style="color:red;" href="http://weibo.com/5243765174/CpK8UermN" target="_blank">
                        <span class="glyphicon glyphicon-link"></span><strong>2015年YS发剧统计半年版</strong>
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
                </p>
                <p>
                    <a class="btn btn-success btn-xs" href="{{ url('/episode?type=0') }}" target="_blank">
                        <span class="glyphicon glyphicon-facetime-video"></span> 查看全部分集
                    </a>
                </p>
                <p>
                    <a class="btn btn-info btn-xs" href="{{ url('/review') }}" target="_blank">
                        <span class="glyphicon glyphicon-comment"></span> 查看全部评论
                    </a>
                </p>
                <p class="text-info">
                    <span class="glyphicon glyphicon-hand-down"></span> 意见建议捉虫反馈看这里
                </p>
                <p>
                    <a class="btn btn-danger btn-xs" href="{{ url('/bbs') }}" target="_blank">留言板</a>
                </p>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title"><span class="glyphicon glyphicon-bullhorn"></span> 公告栏</h4>
                    </div>
                    <div class="list-group">
                        <a href="{{ url('/bbs/topic/19') }}" class="list-group-item" target="_blank">
                            性向分区功能上线
                        </a>
                        <a href="{{ url('/bbs/topic/18') }}" class="list-group-item" target="_blank">
                            服务已恢复，给大家造成的不便我们深表抱歉
                        </a>
                        <a href="{{ url('/bbs/topic/13') }}" class="list-group-item" target="_blank">
                            关于写评和收藏系统是否需要结合起来的意见征询
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
