@extends('app')

@section('title', '管理页 - ')

@section('content')
    <div class="container">
        <h4 class="text-success">后台管理页</h4><br/>
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
                                    主役CV：{{ $episode->cv }}<br/>
                                    站内链接：https://saoju.net/episode/{{ $episode->id }}<br/>
                                    发布地址：{{ $episode->url }}<br/>
                                    SC表：{{ mb_substr($episode->sc, 0, 100) }}...<br/>
                                </p><br/>
                            @endforeach
                            <br/>
                        @endforeach
                        <h4>感谢搬运者！</h4>
                    </div>
                </div>
                <p><strong>封面：</strong>随便选一张图就行。</p>
                <p><strong>摘要：</strong>共{{ $newEpisodesCount }}部剧。</p>
            </div>
        </div>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="panel panel-warning">
            <div class="panel-heading">屏蔽评论</div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ url('/admin/deletereview') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <p>打开评论详情页，查看地址，例如https://saoju.net/review/3189，则评论ID为3189。</p>
                    <p>同时需输入评论时间进行校验，例如2016-08-28 20:18:44，以防输错评论ID。</p>
                    <p>屏蔽成功后会跳转到首页，否则会提示屏蔽失败。</p><br/>
                    <div class="form-group">
                        <label class="col-md-2 control-label">评论ID</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="review_id" required="required" value="{{ old('review_id') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">评论时间</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="created_at" required="required" value="{{ old('created_at') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">屏蔽理由</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="reason" required="required" value="{{ old('reason') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-2">
                            <button type="submit" name="submit" class="btn btn-warning">屏蔽评论</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
