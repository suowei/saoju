@extends('app')

@section('title', '《'.$drama->title.'》'.$episode->title.'版本列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>《<a href="{{ url('/drama/'.$drama->id) }}">{{ $drama->title }}</a>》<a href="{{ url('/episode/'.$episode->id) }}">{{ $episode->title }}</a>的版本列表</h3>
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
                            <span class="text-muted">副标题：</span>{{ $version->alias }}<br>
                            <span class="text-muted">时长：</span>{{ $version->duration }}<br>
                            <span class="text-muted">发布日期：</span>{{ $version->release_date }}<br>
                            <span class="text-muted">发布地址：</span>{{ $version->url }}<br>
                            <span class="text-muted">海报地址：</span>{{ $version->poster_url }}<br>
                            <span class="text-muted">SC表：</span>{{ $version->sc }}<br>
                            <span class="text-muted">本集简介：</span>{{ $version->introduction }}<br>
                            <span class="text-muted">版权声明：</span>{{ $version->authorization }}
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-3">
                <p>自2015年8月23日起，编辑历史功能升级为版本列表，在此日期之前的分集编辑历史均合并到分集添加者的版本中。</p>
                <p>
                    <a class="btn btn-info btn-xs" href="{{ url('/episode/'.$episode->id.'/histories') }}" target="_blank">
                        <span class="glyphicon glyphicon-book" aria-hidden="true"></span> 查看旧版编辑历史
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
