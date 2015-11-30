@extends('appzb')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <ul class="nav nav-tabs" id="dateTab">
                    <li role="presentation"><a href="#newsongs" role="tab" data-toggle="tab">新加入歌曲</a></li>
                    <li role="presentation"><a href="#hotrevsongs" role="tab" data-toggle="tab">月热评歌曲</a></li>
                    <li role="presentation"><a href="#hotfavsongs" role="tab" data-toggle="tab">月热门歌曲</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane" id="newsongs">
                        <div class="list-group">
                            @foreach($newsongs as $song)
                                <a href="{{ url('/song/'.$song->id) }}" target="_blank" class="list-group-item">
                                    <h4 class="list-group-item-heading">《{{ $song->title }}》{{ $song->artist }}
                                        <span class="badge pull-right">{{ $song->reviews }}评论</span></h4>
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
                    <small class="pull-right"><a class="text-muted" href="{{ url('/songfav') }}" target="_blank">看看大家在听什么</a></small>
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
    </div>
@endsection
