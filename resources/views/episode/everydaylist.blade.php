@extends('app')

@section('title', $date.'出剧清单 - ')

@section('content')
    <div class="container">
        <h4>{{ $date }}出剧清单<small>（共{{ $newEpisodesCount }}部剧。）</small></h4><br/>
        <?php $types = ['耽美', '全年龄', '言情', 'GL']; ?>
        <?php $eras = ['现代', '古风', '民国', '未来', '其他时代']; ?>
        @foreach($newEpisodes as $type => $episodes)
            <h4>{{ $types[$type] }}：</h4>
            @foreach($episodes as $episode)
                <a href="https://saoju.net/episode/{{ $episode->id }}">
                    <h4>@if($episode->origianl)原创@else{{ $episode->author }}原著@endif{{ $eras[$episode->era] }}{{ $types[$type] }}广播剧《{{ $episode->drama_title }}》{{ $episode->episode_title }} {{ $episode->alias }}</h4>
                </a>
                <p>
                    @if($episode->genre)其他描述：{{ $episode->genre }}<br/>@endif
                    主役CV：{{ $episode->cv }}<br/>
                    发布地址：<a href="{{ $episode->url }}">{{ $episode->url }}</a><br/>
                    SC表：{{ mb_substr($episode->sc, 0, 100) }}...<br/>
                </p><br/>
            @endforeach
            <br/>
        @endforeach
        <h4>感谢搬运者！</h4>
    </div>
@endsection
