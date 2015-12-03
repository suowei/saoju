@extends('appzb')

@section('title', $live->title.'的版本列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3><a href="{{ url('/live/'.$live->id) }}">{{ $live->title }}</a>的版本列表</h3>
                @foreach ($versions as $version)
                    <div class="review">
                        <div class="review-title">
                            <a href="{{ url('/user/'.$version->user_id) }}" target="_blank">{{ $version->user->name }}</a>
                            @if($version->first)（创建者）@endif
                            此版本创建于{{ $version->created_at }}
                            更新于{{ $version->updated_at }}
                        </div>
                        <div class="version">
                            <span class="text-muted">活动主题：</span>{{ $version->title }}<br>
                            <span class="text-muted">开始时间：</span>{{ date('Y-m-d H:i', strtotime($version->showtime)) }}<br>
                            <span class="text-muted">活动信息：</span>{{ $version->information }}<br>
                            <span class="text-muted">海报地址：</span>{{ $version->poster_url }}<br>
                            <span class="text-muted">录音地址：</span>{{ $version->record_url }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
