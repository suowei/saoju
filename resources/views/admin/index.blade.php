@extends('app')

@section('title', '管理页 - ')

@section('content')
    <div class="container">
        <h4 class="text-success">后台管理页</h4>
        <br/>
        <p><a href="{{ url('/admin/recommend') }}" target="_blank" class="btn btn-info">剧集分集推荐</a></p>
        <p><a href="{{ url('/admin/weixin') }}" target="_blank" class="btn btn-success">微信公众号每日出剧清单推送内容</a></p>
        <p><a href="{{ url('/admin/banreview') }}" target="_blank" class="btn btn-warning">屏蔽评论</a></p>
    </div>
@endsection
