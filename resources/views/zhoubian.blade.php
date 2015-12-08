@extends('appzb')

@section('content')
    <div class="container">
        <div class="row" id="song">
            <div class="col-md-4">
                <h4 class="text-success"><span class="glyphicon glyphicon-play"></span> 歌曲、翻唱、剧情歌等
                    <span class="pull-right">
                        <a class="btn btn-info btn-xs" href="{{ url('/song') }}" target="_blank">
                            <span class="glyphicon glyphicon-music"></span> 查看全部歌曲</a>
                    </span>
                </h4>
                <ul class="nav nav-tabs hoverTab">
                    <li role="presentation" class="active"><a href="#newsongs" role="tab" data-toggle="tab">新加入歌曲</a></li>
                    <li role="presentation"><a href="#hotrevsongs" role="tab" data-toggle="tab">月热评歌曲</a></li>
                    <li role="presentation"><a href="#hotfavsongs" role="tab" data-toggle="tab">月热门歌曲</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="newsongs">
                        <div class="list-group">
                            @foreach($newsongs as $song)
                                <a href="{{ url('/song/'.$song->id) }}" target="_blank" class="list-group-item">
                                    <h4 class="list-group-item-heading">《{{ $song->title }}》{{ $song->artist }}</h4>
                                    {{ $song->alias }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="hotrevsongs">
                        <div class="list-group">
                            @foreach($hotrevsongs as $hot)
                                <a class="list-group-item" href="{{ url('/song/'.$hot->song_id) }}" target="_blank">
                                    《{{ $hot->song->title }}》{{ $hot->song->artist }}
                                    <span class="pull-right">{{ $hot->review_count }}评论</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="hotfavsongs">
                        <div class="list-group">
                            @foreach($hotfavsongs as $hot)
                                <a class="list-group-item" href="{{ url('/song/'.$hot->song_id) }}" target="_blank">
                                    《{{ $hot->song->title }}》{{ $hot->song->artist }}
                                    <span class="pull-right">{{ $hot->favorite_count }}收藏</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <h4 class="text-success">
                    <span class="glyphicon glyphicon-comment"></span>
                    最新歌曲评论（<a href="{{ url('/songrev') }}"） target="_blank">查看全部</a>）
                    <small class="pull-right"><a class="text-muted" href="{{ url('/songfav') }}" target="_blank">看看大家在听什么歌</a></small>
                </h4>
                @foreach ($songrevs as $review)
                    <div class="review">
                        <div class="review-title">
                            <a href="{{ url('/user/'.$review->user_id) }}" target="_blank">{{ $review->user->name }}</a> 评论
                            《<a href="{{ url('/song/'.$review->song_id) }}" target="_blank">{{ $review->song->title }}</a>》
                            {{ $review->created_at }}
                            {{ $review->title }}
                            <span class="pull-right">
                                <a href="{{ url('/songrev/'.$review->id) }}" target="_blank">查看</a>
                            </span>
                        </div>
                        <div class="review-content">{{ $review->content }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="row" id="ft">
            <div class="col-md-4">
                <h4 class="text-success"><span class="glyphicon glyphicon-play"></span> FT、电台节目等
                    <span class="pull-right">
                        <a class="btn btn-info btn-xs" href="{{ url('/ft') }}" target="_blank">
                            <span class="glyphicon glyphicon-cutlery"></span> 全部节目</a>
                        <a class="btn btn-warning btn-xs" href="{{ url('/ftep') }}" target="_blank">
                            <span class="glyphicon glyphicon-glass"></span> 节目分集</a>
                    </span>
                </h4>
                <ul class="nav nav-tabs hoverTab">
                    <li role="presentation" class="active"><a href="#newcreatedfteps" role="tab" data-toggle="tab">新加入节目</a></li>
                    <li role="presentation"><a href="#hotrevfts" role="tab" data-toggle="tab">月热评节目</a></li>
                    <li role="presentation"><a href="#hotfavfts" role="tab" data-toggle="tab">月热门节目</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="newcreatedfteps">
                        <div class="list-group">
                            @foreach($newcreatedfteps as $ftep)
                                <a href="{{ url('/ftep/'.$ftep->id) }}" target="_blank" class="list-group-item">
                                    <h4 class="list-group-item-heading">《{{ $ftep->ft->title }}》</h4>
                                    {{ $ftep->title }}<br>
                                    {{ $ftep->ft->host }} {{ $ftep->release_date }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="hotrevfts">
                        <div class="list-group">
                            @foreach($hotrevfts as $hot)
                                <a class="list-group-item" href="{{ url('/ft/'.$hot->ft_id) }}" target="_blank">
                                    《{{ $hot->ft->title }}》
                                    <span class="pull-right">{{ $hot->review_count }}评论</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="hotfavfts">
                        <div class="list-group">
                            @foreach($hotfavfts as $hot)
                                <a class="list-group-item" href="{{ url('/ft/'.$hot->ft_id) }}" target="_blank">
                                    《{{ $hot->ft->title }}》
                                    <span class="pull-right">{{ $hot->favorite_count }}收藏</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <h4 class="text-success">
                    <span class="glyphicon glyphicon-comment"></span>
                    最新节目评论（<a href="{{ url('/ftrev') }}"） target="_blank">查看全部</a>）
                    <small class="pull-right"><a class="text-muted" href="{{ url('/ftfav') }}" target="_blank">看看大家在听什么节目</a></small>
                </h4>
                @foreach ($ftrevs as $review)
                    <div class="review">
                        <div class="review-title">
                            <a href="{{ url('/user/'.$review->user_id) }}" target="_blank">{{ $review->user->name }}</a> 评论
                            《<a href="{{ url('/ft/'.$review->ft_id) }}"
                                target="_blank">{{ $review->ft->title }}</a>》@if($review->ftep_id)<a
                                    href="{{ url('/ftep/'.$review->ftep_id) }}"
                                    target="_blank">{{ $review->ftep->title }}</a>@endif
                            {{ $review->created_at }}
                            {{ $review->title }}
                            <span class="pull-right">
                                <a href="{{ url('/ftrev/'.$review->id) }}" target="_blank">查看</a>
                            </span>
                        </div>
                        <div class="review-content">{{ $review->content }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="row" id="live">
            <div class="col-md-4">
                <h4 class="text-success"><span class="glyphicon glyphicon-play"></span> YY歌会、闲聊等活动
                    <span class="pull-right">
                        <a class="btn btn-info btn-xs" href="{{ url('/live') }}" target="_blank">
                            <span class="glyphicon glyphicon-time"></span> 查看全部活动</a>
                    </span>
                </h4>
                <ul class="nav nav-tabs hoverTab">
                    <li role="presentation" class="active"><a href="#todaylives" role="tab" data-toggle="tab">今日活动</a></li>
                    <li role="presentation"><a href="#newlives" role="tab" data-toggle="tab">新加入</a></li>
                    <li role="presentation"><a href="#hotrevlives" role="tab" data-toggle="tab">月热评</a></li>
                    <li role="presentation"><a href="#hotfavlives" role="tab" data-toggle="tab">月热门</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="todaylives">
                        <div class="list-group">
                            @foreach($todaylives as $live)
                                <a href="{{ url('/live/'.$live->id) }}" target="_blank" class="list-group-item">
                                    <h4 class="list-group-item-heading">
                                        {{ date('H:i', strtotime($live->showtime)) }} {{ $live->title }}
                                    </h4>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="newlives">
                        <div class="list-group">
                            @foreach($newlives as $live)
                                <a href="{{ url('/live/'.$live->id) }}" target="_blank" class="list-group-item">
                                    <h4 class="list-group-item-heading">{{ $live->title }}</h4>
                                    {{ date('Y-m-d H:i', strtotime($live->showtime)) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="hotrevlives">
                        <div class="list-group">
                            @foreach($hotrevlives as $hot)
                                <a class="list-group-item" href="{{ url('/live/'.$hot->live_id) }}" target="_blank">
                                    [{{ date('Ymd', strtotime($live->showtime)) }}]{{ $hot->live->title }}
                                    <span class="pull-right">{{ $hot->review_count }}评论</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="hotfavlives">
                        <div class="list-group">
                            @foreach($hotfavlives as $hot)
                                <a class="list-group-item" href="{{ url('/live/'.$hot->live_id) }}" target="_blank">
                                    [{{ date('Ymd', strtotime($live->showtime)) }}]{{ $hot->live->title }}
                                    <span class="pull-right">{{ $hot->favorite_count }}收藏</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <h4 class="text-success">
                    <span class="glyphicon glyphicon-comment"></span>
                    最新活动评论（<a href="{{ url('/liverev') }}"） target="_blank">查看全部</a>）
                    <small class="pull-right"><a class="text-muted" href="{{ url('/livefav') }}" target="_blank">看看大家在听什么活动</a></small>
                </h4>
                @foreach ($liverevs as $review)
                    <div class="review">
                        <div class="review-title">
                            <a href="{{ url('/user/'.$review->user_id) }}" target="_blank">{{ $review->user->name }}</a> 评论
                            <a href="{{ url('/live/'.$review->live_id) }}" target="_blank">
                                [{{ date('Ymd', strtotime($live->showtime)) }}]{{ $review->live->title }}
                            </a>
                            {{ $review->created_at }}
                            {{ $review->title }}
                            <span class="pull-right">
                                <a href="{{ url('/liverev/'.$review->id) }}" target="_blank">查看</a>
                            </span>
                        </div>
                        <div class="review-content">{{ $review->content }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div id="zhoubiannav">
        <p><a href="#song"><span class="glyphicon glyphicon-music"></span> 歌曲</a></p>
        <p><a href="#ft"><span class="glyphicon glyphicon-glass"></span> 节目</a></p>
        <p><a href="#live"><span class="glyphicon glyphicon-time"></span> 活动</a></p>
    </div>
@endsection
