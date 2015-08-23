@extends('app')

@section('title', '《'.$drama->title.'》版本列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>《<a href="{{ url('/drama/'.$drama->id) }}">{{ $drama->title }}</a>》的版本列表</h3>
                @foreach ($versions as $version)
                    <div class="review">
                        <div class="review-title">
                            <a href="{{ url('/user/'.$version->user_id) }}" target="_blank">{{ $version->user->name }}</a>
                            @if($version->first)（创建者）@endif
                            此版本创建于{{ $version->created_at }}
                            更新于{{ $version->updated_at }}
                        </div>
                        <div class="version">
                            <span class="text-muted">标题：</span>{{ $version->title }}<br>
                            <span class="text-muted">副标题及别名：</span>{{ $version->alias }}<br>
                            <span class="text-muted">性向</span>：@if($drama->type == 0)耽美
                            @elseif($drama->type == 1)全年龄@elseif($drama->type == 2)言情@else百合@endif<br>
                            <span class="text-muted">时代：</span>@if($drama->era == 0)现代@elseif($drama->era == 1)古风
                            @elseif($drama->era == 2)民国@elseif($drama->era == 3)未来@else其他@endif<br>
                            <span class="text-muted">其他描述：</span>{{ $version->genre }}<br>
                            <span class="text-muted">原创性：</span>{{ $version->original == 1 ? '原创' : '改编' }}<br>
                            <span class="text-muted">期数：</span>{{ $version->count }}<br>
                            <span class="text-muted">进度：</span>@if($version->state == 0)连载
                            @elseif($version->state == 1)完结@else已坑@endif<br>
                            <span class="text-muted">主役CV：</span>{{ $version->sc }}<br>
                            <span class="text-muted">海报地址：</span>{{ $version->poster_url }}<br>
                            <span class="text-muted">剧情简介：</span>{{ $version->introduction }}
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-3">
                <p>自2015年8月23日起，编辑历史功能升级为版本列表，在此日期之前的剧集编辑历史均合并到剧集添加者的版本中。</p>
                <p>
                    <a class="btn btn-info btn-xs" href="{{ url('/drama/'.$drama->id.'/histories') }}" target="_blank">
                        <span class="glyphicon glyphicon-book" aria-hidden="true"></span> 查看旧版编辑历史
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
