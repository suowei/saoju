@extends('app')

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
                            <div class="panel-heading"><span class="glyphicon glyphicon-music"></span> 今日新剧</div>
                            <ul class="list-group">
                                @foreach($todays as $episode)
                                    <li class="list-group-item">
                                        <h4>
                                            《<a href="{{ url('/drama/'.$episode->drama_id) }}" target="_blank">{{ $episode->drama_title }}</a>》<a href="{{ url('/episode/'.$episode->episode_id) }}" target="_blank">{{ $episode->episode_title }}</a>
                                            <small>
                                                @if($episode->alias)
                                                    {{ $episode->alias }}
                                                @endif
                                            </small>
                                            <span class="pull-right"><a href="{{ url('/episode/'.$episode->episode_id.'/reviews') }}" target="_blank">{{ $episode->reviews }}评论</a></span>
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

                        <div class="panel panel-warning">
                            <div class="panel-heading"><span class="glyphicon glyphicon-headphones"></span> 昨日新剧</div>
                            <ul class="list-group">
                                @foreach($yesterdays as $episode)
                                    <li class="list-group-item">
                                        <h4>
                                            《<a href="{{ url('/drama/'.$episode->drama_id) }}" target="_blank">{{ $episode->drama_title }}</a>》<a href="{{ url('/episode/'.$episode->episode_id) }}" target="_blank">{{ $episode->episode_title }}</a>
                                            <small>
                                                @if($episode->alias)
                                                    {{ $episode->alias }}
                                                @endif
                                            </small>
                                            <span class="pull-right"><a href="{{ url('/episode/'.$episode->episode_id.'/reviews') }}" target="_blank">{{ $episode->reviews }}评论</a></span>
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

                        <div class="panel panel-danger">
                            <div class="panel-heading"><span class="glyphicon glyphicon-step-forward"></span> 前日新剧</div>
                            <ul class="list-group">
                                @foreach($thedaybefores as $episode)
                                    <li class="list-group-item">
                                        <h4>
                                            《<a href="{{ url('/drama/'.$episode->drama_id) }}" target="_blank">{{ $episode->drama_title }}</a>》<a href="{{ url('/episode/'.$episode->episode_id) }}" target="_blank">{{ $episode->episode_title }}</a>
                                            <small>
                                                @if($episode->alias)
                                                    {{ $episode->alias }}
                                                @endif
                                            </small>
                                            <span class="pull-right"><a href="{{ url('/episode/'.$episode->episode_id.'/reviews') }}" target="_blank">{{ $episode->reviews }}评论</a></span>
                                        </h4>
                                        {{ $episode->sc }}
                                        <div class="text-muted">
                                            @if($type <0)
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
                            <div class="panel-heading"><span class="glyphicon glyphicon-volume-down"></span>一周新剧</div>
                            <ul class="list-group">
                                @foreach($thisweeks as $episode)
                                    <li class="list-group-item">
                                        <h4>
                                            《<a href="{{ url('/drama/'.$episode->drama_id) }}" target="_blank">{{ $episode->drama_title }}</a>》<a href="{{ url('/episode/'.$episode->episode_id) }}" target="_blank">{{ $episode->episode_title }}</a>
                                            <span class="pull-right"><a href="{{ url('/episode/'.$episode->episode_id.'/reviews') }}" target="_blank">{{ $episode->reviews }}评论</a></span>
                                        </h4>
                                        @if($type < 0)
                                            @if($episode->type == 0)
                                                耽美
                                            @elseif($episode->type == 1)
                                                全年龄
                                            @elseif($episode->type == 2)
                                                言情
                                            @else
                                                百合
                                            @endif
                                        @endif
                                        {{ $episode->release_date }}
                                        {{ $episode->sc }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <h4 class="text-success"><span class="glyphicon glyphicon-comment"></span> 最新评论（<a href="{{ url('/review') }}"） target="_blank">查看全部</a>）</h4>
                @foreach ($reviews as $review)
                    <div class="review">
                        <div class="review-title">
                            <a href="{{ url('/user/'.$review->user_id) }}" target="_blank">{{ $review->user->name }}</a> 评论
                            《<a href="{{ url('/drama/'.$review->drama_id) }}" target="_blank">{{ $review->drama_title }}</a>》
                            @if ($review->episode_id) [<a href="{{ url('/episode/'.$review->episode_id) }}" target="_blank">{{ $review->episode->title }}</a>]@endif
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
                <h4 class="text-warning">添加新剧请看上边<span class="glyphicon glyphicon-hand-up"></span></h4>
                <h5 class="text-warning">下边<span class="glyphicon glyphicon-hand-down"></span>也行</h5>
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/drama/create') }}" target="_blank"><span class="glyphicon glyphicon-plus"></span> 添加剧集信息</a>
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
                    <a class="btn btn-warning btn-xs" href="{{ url('/drama') }}" target="_blank"><span class="glyphicon glyphicon-film"></span> 查看全部剧集</a>
                </p>
                <p>
                    <a class="btn btn-success btn-xs" href="{{ url('/episode') }}" target="_blank"><span class="glyphicon glyphicon-facetime-video"></span> 查看全部分集</a>
                </p>
                <p>
                    <a class="btn btn-info btn-xs" href="{{ url('/review') }}" target="_blank"><span class="glyphicon glyphicon-comment"></span> 查看全部评论</a>
                </p>
                <p class="text-info">
                    <span class="glyphicon glyphicon-hand-down"></span> 意见建议捉虫反馈看这里
                </p>
                <p>
                    <a class="btn btn-danger btn-xs" href="{{ url('/bbs') }}" target="_blank">留言板</a>
                </p>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-bullhorn"></span> 公告栏</h3>
                    </div>
                    <div class="list-group">
                        <a href="{{ url('/bbs/topic/19') }}" class="list-group-item" target="_blank">性向分区功能上线，以及时代字段加入</a>
                        <a href="{{ url('/bbs/topic/18') }}" class="list-group-item" target="_blank">服务已恢复，给大家造成的不便我们深表抱歉</a>
                        <a href="{{ url('/bbs/topic/13') }}" class="list-group-item" target="_blank">关于写评和收藏系统是否需要结合起来的意见征询</a>
                        <a href="{{ url('/bbs/topic/4') }}" class="list-group-item" target="_blank">手机端导航条展开bug已修复，点击右侧按钮即可展开</a>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-heart"></span> 感谢各位的贡献！</h3>
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
