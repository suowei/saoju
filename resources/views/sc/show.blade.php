@extends('app')

@section('title', $sc->name.' - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>
                    {{ $sc->name }}
                    <a class="btn btn-success btn-xs" href="{{ url('/screv/create?sc='.$sc->id) }}">
                        <span class="glyphicon glyphicon-pencil"></span> 发表印象
                    </a>
                </h3>
                <p>马甲及昵称：{{ $sc->alias ? $sc->alias : '无' }}</p>
                <p>社团或工作室：@if($sc->club_id)<a href="{{ url('/club/'.$sc->club_id) }}" target="_blank">{{ $sc->club->name }}</a>@else无@endif</p>
                <p>职位：{{ $sc->jobs ? $sc->jobs : '无' }}</p>
                <div>
                    {!! $sc->information !!}
                </div>
                <h4 class="text-success">
                    新关联作品
                    <small>
                        （查看全部：<span class="glyphicon glyphicon-th"></span>
                        <a href="{{ url('/sc/'.$sc->id.'/dramas') }}" target="_blank">剧集</a>
                        <span class="glyphicon glyphicon-th-list"></span>
                        <a href="{{ url('/sc/'.$sc->id.'/episodes') }}" target="_blank">分集</a>）
                    </small>
                </h4>
                <?php $jobs = ['原著', '策划', '导演', '编剧', '后期', '美工', '宣传', '填词', '翻唱', '歌曲后期', '其他staff', '主役', '协役', '龙套']; ?>
                @foreach($roles as $role)
                    《<a href="{{ url('/drama/'.$role->drama_id) }}" target="_blank">{{ $role->drama->title }}</a>》<a href="{{ url('/episode/'.$role->episode_id) }}" target="_blank">{{ $role->episode->title }}</a>
                    {{ $jobs[$role->job] }} {{ $role->note }}<br>
                @endforeach
                @if (Auth::check())
                    <div class="reviews">
                        <h4 class="text-success">我的印象</h4>
                        @foreach ($userReviews as $review)
                            <div class="review">
                                <div class="review-title">
                                    <div class="row">
                                        <div class="col-md-10">
                                            {{ $review->created_at }}
                                            {{ $review->title }}
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <a href="{{ url('/screv/'.$review->id) }}" target="_blank">查看</a>
                                            <a class="text-muted" href="{{ url('/screv/'.$review->id.'/edit') }}">修改</a>
                                            <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/screv/'.$review->id) }}">删除</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="review-content">{{ $review->content }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="reviews">
                    <h4 class="text-success">最新印象<small>（<a href="{{ url('/sc/'.$sc->id.'/reviews') }}" target="_blank">查看全部{{ $sc->reviews }}篇印象</a>）</small></h4>
                    @foreach ($reviews as $review)
                        <div class="review">
                            <div class="review-title">
                                <a href="{{ url('/user/'.$review->user->id) }}" target="_blank">{{ $review->user->name }}</a>
                                {{ $review->created_at }}
                                {{ $review->title }}
                                <span class="pull-right">
                                <a href="{{ url('/screv/'.$review->id) }}" target="_blank">查看</a>
                            </span>
                            </div>
                            <div class="review-content">{{ $review->content }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-3">
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/sc/'.$sc->id.'/edit') }}">
                        <span class="glyphicon glyphicon-edit"></span> 编辑SC信息
                    </a>
                </p>
                <p>
                    <a class="btn btn-info btn-xs" href="{{ url('/sc/'.$sc->id.'/versions') }}" target="_blank">
                        <span class="glyphicon glyphicon-book" aria-hidden="true"></span> 查看版本列表
                    </a>
                </p>
                <p class="text-danger">
                    <span class="glyphicon glyphicon-info-sign"></span> 添加关联作品请前往相应剧集或分集页面，剧集页关联链接在右边栏，分集页在SC信息上方。
                </p>
            </div>
        </div>
    </div>
    <div id="deleteConfirmModal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">删除确认</h4>
                </div>
                <div class="modal-body">
                    确定要删除吗？
                </div>
                <div class="modal-footer">
                    <form class="form-inline" method="POST" action="/unknown">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-danger">确定</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
