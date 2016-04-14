@extends('app')

@section('meta')
    <meta name="description" content="抓糖是一个中文广播剧扫剧平台，在这里你可以查看最新的广播剧及评论，写评并分类记录广播剧。">
    <meta name="keywords" content="广播剧,中文广播剧,中抓,剧评,扫剧">
@endsection

@section('content')
    <div class="container">
        <a href="{{ url('/bbs/topic/92') }}" target="_blank">
             <div class="jumbotron" style="color: #ffffff;background-color: #ce4646;">
                 <h2>热烈庆祝本站创意提出一周年！</h2>
                 <p>2015.4.14 - 2016.4.14 感谢大家一直以来的支持，欢迎常来玩~</p>
                 <p><button class="btn btn-default btn-lg" style="color: #ffffff;background-color: transparent;">查看详情</button></p>
             </div>
         </a>
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <?php $days = [date("Y-m-d", strtotime("now")), date("Y-m-d", strtotime("-1 day")),
                            date("Y-m-d", strtotime("-2 day")), date("Y-m-d", strtotime("-3 day")),
                            date("Y-m-d", strtotime("-4 day")), date("Y-m-d", strtotime("-5 day")),
                            date("Y-m-d", strtotime("-6 day")), date("Y-m-d", strtotime("-7 day")),];
                        $types = ['耽美', '全龄', '言情', '百合'];
                        $eras = ['现代', '古风', '民国', '未来', '其他时代'];
                        $states = ['连载', '完结', '已坑'];
                    ?>

                        <div class="col-md-8">
                        <ul class="nav nav-tabs hoverTab">
                            <li role="presentation" class="active">
                                <a href="#{{ $days[0] }}" role="tab" data-toggle="tab">两日新剧</a>
                            </li>
                            @for($i = 2; $i <= 7; $i++)
                                <li role="presentation">
                                    <a href="#{{ $days[$i] }}" role="tab" data-toggle="tab">{{ date("m-d", strtotime("-".$i." day")) }}</a>
                                </li>
                            @endfor
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="{{ $days[0] }}">
                                <div class="list-group">
                                    @if(isset($episodes[$days[0]]))
                                        @foreach ($episodes[$days[0]] as $episode)
                                            <a href="{{ url('/episode/'.$episode->episode_id) }}" target="_blank"
                                               class="list-group-item @if($episode->state==1){{ 'list-group-item-info' }}@endif">
                                                <h4 class="list-group-item-heading">
                                                    @if($episode->state==1)<strong>@endif
                                                        {{ $eras[$episode->era] }}{{ $types[$episode->type] }}《{{ $episode->drama_title }}》{{ $episode->episode_title }}
                                                        @if($episode->alias){{ $episode->alias }}@endif
                                                        @if($episode->state==1)</strong>@endif
                                                    <span class="badge pull-right">{{ $episode->reviews }}评论</span>
                                                </h4>
                                                {{ $episode->sc }}
                                                <span class="pull-right">
                                                    @if($episode->original)原创，@endif{{ $states[$episode->state] }}@if($episode->genre)，{{ $episode->genre }}@endif，{{ $episode->duration }}分钟
                                                </span>
                                            </a>
                                        @endforeach
                                    @endif
                                    <div class="dateline">
                                        ↑{{ $days[0] }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↓{{ $days[1] }}
                                    </div>
                                        @if(isset($episodes[$days[1]]))
                                            @foreach ($episodes[$days[1]] as $episode)
                                                <a href="{{ url('/episode/'.$episode->episode_id) }}" target="_blank"
                                                   class="list-group-item @if($episode->state==1){{ 'list-group-item-info' }}@endif">
                                                    <h4 class="list-group-item-heading">
                                                        @if($episode->state==1)<strong>@endif
                                                        {{ $eras[$episode->era] }}{{ $types[$episode->type] }}《{{ $episode->drama_title }}》{{ $episode->episode_title }}
                                                        @if($episode->alias){{ $episode->alias }}@endif
                                                            @if($episode->state==1)</strong>@endif
                                                        <span class="badge pull-right">{{ $episode->reviews }}评论</span>
                                                    </h4>
                                                    {{ $episode->sc }}
                                                    <span class="pull-right">
                                                        @if($episode->original)原创，@endif{{ $states[$episode->state] }}@if($episode->genre)，{{ $episode->genre }}@endif，{{ $episode->duration }}分钟
                                                    </span>
                                                </a>
                                            @endforeach
                                        @endif
                                </div>
                            </div>
                            @for($i = 2; $i <= 7; $i++)
                                <div role="tabpanel" class="tab-pane" id="{{ $days[$i] }}">
                                    <ul class="list-group">
                                        @if(isset($episodes[$days[$i]]))
                                            @foreach ($episodes[$days[$i]] as $episode)
                                                <a href="{{ url('/episode/'.$episode->episode_id) }}" target="_blank"
                                                   class="list-group-item @if($episode->state==1){{ 'list-group-item-info' }}@endif">
                                                    <h4 class="list-group-item-heading">
                                                        @if($episode->state==1)<strong>@endif
                                                            {{ $eras[$episode->era] }}{{ $types[$episode->type] }}《{{ $episode->drama_title }}》{{ $episode->episode_title }}
                                                            @if($episode->alias){{ $episode->alias }}@endif
                                                            @if($episode->state==1)</strong>@endif
                                                        <span class="badge pull-right">{{ $episode->reviews }}评论</span>
                                                    </h4>
                                                    {{ $episode->sc }}
                                                    <span class="pull-right">
                                                        @if($episode->original)原创，@endif{{ $states[$episode->state] }}@if($episode->genre)，{{ $episode->genre }}@endif，{{ $episode->duration }}分钟
                                                    </span>
                                                </a>
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
                                                <img src="{{ $top10[0]->poster_url }}" class="center-block"
                                                     title="{{ $top10[0]->drama_title }}{{ $top10[0]->episode_title }}"
                                                     alt="{{ $top10[0]->drama_title }}{{ $top10[0]->episode_title }}">
                                            </a>
                                        </div>
                                    @endif
                                    @for($i = 1; $i < $count; $i++)
                                        <div class="item">
                                            <a href="{{ url('/episode/'.$top10[$i]->episode_id) }}" target="_blank">
                                                <img src="{{ $top10[$i]->poster_url }}" class="center-block"
                                                     title="{{ $top10[$i]->drama_title }}{{ $top10[$i]->episode_title }}"
                                                     alt="{{ $top10[0]->drama_title }}{{ $top10[0]->episode_title }}">
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
                    <span class="glyphicon glyphicon-comment"></span>
                    最新评论（<a href="{{ url('/review') }}"） target="_blank">查看全部</a>）
                    <small class="pull-right"><a class="text-muted" href="{{ url('/favorite') }}" target="_blank">看看大家在听什么</a></small>
                </h4>
                <div id="indexReviews"></div>
                <button id="loadmore" datatype="{{ $type }}" type="button" class="btn btn-default btn-block">加载更多</button>
            </div>
            <div class="col-md-3">
                @if(Auth::check() && $dramafeed = Auth::user()->dramafeed)
                    <div class="alert alert-success">
                        有{{ $dramafeed }}部在追剧集更新啦！<a href="{{ url('/user/dramafeed') }}">去看看</a>
                    </div>
                @endif
                <h4><strong><a href="{{ url('/report2015') }}" target="_blank">抓糖2015年终总结</a></strong></h4>
                <h4><strong><a href="{{ url('/guide') }}" target="_blank">抓糖用户手册</a></strong></h4>
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
                    <a class="btn btn-warning btn-xs" href="{{ url('/screv') }}" target="_blank">
                        <span class="glyphicon glyphicon-picture"></span> SC社团印象
                    </a>
                </p>
                <p>
                    <a class="btn btn-info btn-xs" href="{{ url('/search/tag') }}" target="_blank">
                        <span class="glyphicon glyphicon-tag"></span> 标签搜索
                    </a>
                    <span class="glyphicon glyphicon-hand-left"></span> 标签搜索及热门标签展示
                </p>
                <p>
                    <a class="btn btn-success btn-xs" href="{{ url('/zhoubian') }}" target="_blank">
                        <span class="glyphicon glyphicon-glass"></span> 周边板块
                    </a>
                    <span class="glyphicon glyphicon-hand-left"></span> 歌曲访谈闲聊歌会等相关
                </p>
                <p>
                    <a class="btn btn-danger btn-xs" href="{{ url('/bbs') }}" target="_blank">
                        <span class="glyphicon glyphicon-send"></span> 留言板
                    </a>
                    <span class="glyphicon glyphicon-hand-left"></span> 意见建议捉虫反馈看这里
                </p>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title"><span class="glyphicon glyphicon-bullhorn"></span> 公告栏</h4>
                    </div>
                    <div class="list-group">
                        <a style="color:red;" href="{{ url('/bbs/topic/86') }}" class="list-group-item" target="_blank">
                            Android版抓糖app上线
                        </a>
                        <a href="{{ url('/bbs/topic/53') }}" class="list-group-item" target="_blank">
                            剧单功能上线
                        </a>
                        <a href="{{ url('/bbs/topic/51') }}" class="list-group-item" target="_blank">
                            邀请注册功能开放
                        </a>
                    </div>
                </div>
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span class="glyphicon glyphicon-step-forward"></span> 最新剧单
                            <small class="pull-right"><a href="{{ url('/list') }}" target="_blank">查看全部</a></small>
                        </h4>
                    </div>
                    <div class="list-group">
                        @foreach($newlists as $list)
                            <a class="list-group-item" href="{{ url('/list/'.$list->id) }}" target="_blank">
                                {{ $list->title }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4 class="panel-title"><span class="glyphicon glyphicon-play"></span> 热门剧单</h4>
                    </div>
                    <div class="list-group">
                        @foreach($lists as $list)
                            <a class="list-group-item" href="{{ url('/list/'.$list->list_id) }}" target="_blank">
                                {{ $list->dramalist->title }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 class="panel-title"><span class="glyphicon glyphicon-equalizer"></span> 月热评剧集</h4>
                    </div>
                    <div class="list-group">
                        @foreach($hotDramas as $hot)
                            <a class="list-group-item" href="{{ url('/drama/'.$hot->drama_id) }}" target="_blank">
                                《{{ $hot->drama->title }}》
                                <span class="pull-right">{{ $hot->review_count }}评论</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h4 class="panel-title"><span class="glyphicon glyphicon-stats"></span> 月热门剧集</h4>
                    </div>
                    <div class="list-group">
                        @foreach($hotFavorites as $hot)
                            <a class="list-group-item" href="{{ url('/drama/'.$hot->drama_id) }}" target="_blank">
                                《{{ $hot->drama->title }}》
                                <span class="pull-right">{{ $hot->favorite_count }}收藏</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title"><span class="glyphicon glyphicon-heart"></span> 感谢各位的贡献！</h4>
                    </div>
                    <ul class="list-group">
                        @foreach($versions as $version)
                            <li class="list-group-item">
                                <a href="{{ url('/user/'.$version->user_id) }}" target="_blank">{{ $version->user->name }}</a>
                                于 {{ $version->created_at->format('m-d H:i') }} 添加了
                                《<a href="{{ url('/drama/'.$version->drama_id) }}" target="_blank">{{ $version->drama->title }}</a>》
                                <span class="text-muted">{{ $version->drama->sc }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="http://cdn.bootcss.com/Readmore.js/2.0.5/readmore.min.js"></script>
@endsection
