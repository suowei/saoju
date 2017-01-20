@extends('app')

@section('title', '微信 - 管理页 - ')

@section('content')
    <div class="container">
        <h4 class="text-success">后台管理页</h4>
        <br/>
        <div class="panel panel-success">
            <div class="panel-heading">微信公众号每日出剧清单推送内容</div>
            <div class="panel-body">
                <p><strong>标题：</strong>{{ date("Y年n月j日", strtotime("-1 day")) }}出剧清单</p>
                <p><strong>内容：</strong></p>
                <?php $types = ['耽美', '全年龄', '言情', 'GL']; ?>
                <?php $eras = ['现代', '古风', '民国', '未来', '其他时代']; ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        @foreach($newEpisodes as $type => $episodes)
                            <h4 class="text-success">{{ $types[$type] }}：</h4>
                            @foreach($episodes as $episode)
                                <h4>@if($episode->origianl)原创@else{{ $episode->author }}原著@endif{{ $eras[$episode->era] }}{{ $types[$type] }}广播剧《{{ $episode->drama_title }}》{{ $episode->episode_title }} {{ $episode->alias }}</h4>
                                <p class="text-muted">
                                    @if($episode->genre)其他描述：{{ $episode->genre }}<br/>@endif
                                    主役CV：{{ $episode->cv }}<br/>
                                    站内链接：https://saoju.net/episode/{{ $episode->id }}<br/>
                                    发布地址：{{ $episode->url }}<br/>
                                    SC表：{{ mb_substr($episode->sc, 0, 100) }}...<br/>
                                </p><br/>
                            @endforeach
                        @endforeach
                        <h4>感谢搬运者！</h4>
                    </div>
                </div>
                <p><strong>原文链接：</strong>https://saoju.net/episode/everydaylist/{{ date("Y-m-d", strtotime("-1 day")) }}</p>
                <p><strong>摘要：</strong>共{{ $newEpisodesCount }}部剧。</p>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">今日活动提醒</div>
            <div class="panel-body">
                <p><strong>摘要：</strong>共{{ $todayLivesCount }}场活动。</p>
            </div>
        </div>
    </div>
@endsection
