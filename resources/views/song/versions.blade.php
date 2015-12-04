@extends('appzb')

@section('title', '《'.$song->title.'》的版本列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>《<a href="{{ url('/song/'.$song->id) }}">{{ $song->title }}</a>》的版本列表</h3>
                @foreach ($versions as $version)
                    <div class="review">
                        <div class="review-title">
                            <a href="{{ url('/user/'.$version->user_id) }}" target="_blank">{{ $version->user->name }}</a>
                            @if($version->first)（创建者）@endif
                            此版本创建于{{ $version->created_at }}
                            更新于{{ $version->updated_at }}
                        </div>
                        <div class="version">
                            <span class="text-muted">歌名：</span>{{ $version->title }}<br>
                            <span class="text-muted">副标题：</span>{{ $version->alias }}<br>
                            <span class="text-muted">演唱者：</span>{{ $version->artist }}<br>
                            <span class="text-muted">发布地址：</span>{{ $version->url }}<br>
                            <span class="text-muted">海报地址：</span>{{ $version->poster_url }}<br>
                            <span class="text-muted">制作名单：</span>{{ $version->staff }}<br>
                            <span class="text-muted">歌词：</span>{{ $version->lyrics }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
