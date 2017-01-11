@extends('app')

@section('title', '管理页 - ')

@section('content')
    <div class="container">
        <h4 class="text-success">后台管理页</h4>
        <br/>
        <p><a href="{{ url('/admin/recommend') }}" target="_blank" class="btn btn-info">剧集分集推荐</a></p>
        <p><a href="{{ url('/admin/weixin') }}" target="_blank" class="btn btn-success">微信公众号每日出剧清单推送内容</a></p>
        <p><a href="{{ url('/admin/banreview') }}" target="_blank" class="btn btn-danger">屏蔽评论</a></p>
        <p>
        <form class="form-inline" role="form" method="GET" action="{{ url('/admin/dramarating') }}" target="_blank">
            <input type="text" name="id" class="form-control" placeholder="剧集ID" required="required">
            <button type="submit" class="btn btn-warning">查询剧集评分详情</button>
        </form>
        </p>
        <p>
        <form class="form-inline" role="form" method="GET" action="{{ url('/admin/episoderating') }}">
            <input type="text" name="id" class="form-control" placeholder="分集ID" required="required">
            <button type="submit" class="btn btn-primary">查询分集评分详情</button>
        </form>
        </p>
    </div>
@endsection
