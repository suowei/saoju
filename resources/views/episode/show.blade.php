@extends('app')

@section('title', $episode->drama->title.$episode->title.' - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>
                    《<a href="{{ url('/drama/'.$drama->id) }}">{{ $drama->title }}</a>》{{ $episode->title }}
                    @if($episode->alias){{ $episode->alias }}@endif
                    <a class="btn btn-warning btn-xs" href="{{ url('/review/create?drama='.$drama->id.'&episode='.$episode->id) }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 写本期评论
                    </a>
                </h3>
                <div class="row">
                    <div class="col-md-6">
                        <p><span class="text-muted">时长：</span>{{ $episode->duration.'分钟' }}</p>
                        <p><span class="text-muted">发布时间：</span>{{ $episode->release_date }}</p>
                        <p class="content-pre-line">{{ $episode->sc }}</p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <a href="{{ $episode->poster_url  }}" target="_blank">
                                <img src="{{ $episode->poster_url }}" class="img-responsive" alt="海报">
                            </a>
                        </p>
                    </div>
                </div>
                @if ($episode->introduction)<p class="content-pre-line">{{ $episode->introduction }}</p>@endif
                <p><span class="text-muted">发布地址：</span>@if ($episode->url)<a href="{{ $episode->url }}" target="_blank">{{ $episode->url }}</a>@else未知@endif</p>

                @if (Auth::check())
                    <div class="reviews">
                        <h4 class="text-success">我的评论</h4>
                        @foreach (\App\Review::where('user_id', Auth::id())->where('episode_id', $episode->id)->get() as $review)
                            <div class="review">
                                <div class="review-title">
                                    <div class="row">
                                        <div class="col-md-10">
                                            {{ $review->created_at }}
                                            @if ($review->episode_id) [{{ $review->episode->title }}]@endif
                                            {{ $review->title }}
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <a href="{{ url('/review/'.$review->id) }}" target="_blank">查看</a>
                                            <a class="text-muted" href="{{ url('/review/'.$review->id.'/edit') }}">修改</a>
                                            <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/review/'.$review->id) }}">删除</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="review-content">{{ $review->content }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="reviews">
                    <h4 class="text-success">最新评论<small>（<a href="{{ url('/episode/'.$episode->id.'/reviews') }}" target="_blank">查看全部{{ $episode->reviews }}篇评论</a>）</small></h4>
                    @foreach ($reviews as $review)
                        <div class="review">
                            <div class="review-title">
                                <a href="{{ url('/user/'.$review->user->id) }}" target="_blank">{{ $review->user->name }}</a>
                                {{ $review->created_at }}
                                {{ $review->title }}
                                <span class="pull-right">
                                    <a href="{{ url('/review/'.$review->id) }}" target="_blank">查看</a>
                                </span>
                            </div>
                            <div class="review-content">{{ $review->content }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-3">
                <h4 class="text-warning">信息维护看这里<span class="glyphicon glyphicon-hand-down" aria-hidden="true"></span></h4>
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/episode/'.$episode->id.'/edit') }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> 编辑本集信息</a>
                </p>
                <p>
                    <a class="btn btn-success btn-xs" href="{{ url('/episode/'.$episode->id.'/histories') }}" target="_blank"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> 查看编辑历史</a>
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
