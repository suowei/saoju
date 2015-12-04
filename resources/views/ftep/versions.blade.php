@extends('appzb')

@section('title', '《'.$ft->title.'》'.$ftep->title.'版本列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>《<a href="{{ url('/ft/'.$ft->id) }}">{{ $ft->title }}</a>》<a href="{{ url('/ftep/'.$ftep->id) }}">{{ $ftep->title }}</a>的版本列表</h3>
                @foreach ($versions as $version)
                    <div class="review">
                        <div class="review-title">
                            <a href="{{ url('/user/'.$version->user_id) }}" target="_blank">{{ $version->user->name }}</a>
                            @if($version->first)（创建者）@endif
                            此版本创建于{{ $version->created_at }}
                            更新于{{ $version->updated_at }}
                        </div>
                        <div class="version">
                            <span class="text-muted">分集标题：</span>{{ $version->title }}<br>
                            <span class="text-muted">发布日期：</span>{{ $version->release_date }}<br>
                            <span class="text-muted">发布地址：</span>{{ $version->url }}<br>
                            <span class="text-muted">海报地址：</span>{{ $version->poster_url }}<br>
                            <span class="text-muted">制作组名单：</span>{{ $version->staff }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
