@extends('appzb')

@section('title', $live->title.' - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-8">
                        <h3>{{ $live->title }}</h3>
                        <p>
                            @if(Auth::check())
                                @if($favorite)
                                    <span class="glyphicon glyphicon-headphones"></span> 已收藏
                                    <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/livefav/'.$live->id) }}">删除收藏</a>
                                @else
                                    <a class="btn btn-info btn-xs" href="{{ url('/livefav/create?live='.$live->id) }}">收藏活动并写评</a>
                                    <a class="btn btn-warning btn-xs" href="{{ url('/livefav/store?live='.$live->id) }}">
                                        <span class="glyphicon glyphicon-gift"></span> 收藏活动
                                    </a>
                                @endif
                            @else
                                <a class="btn btn-info btn-xs" href="{{ url('/livefav/create?live='.$live->id) }}">
                                    收藏并写评
                                </a>
                            @endif
                            <a class="btn btn-success btn-xs" href="{{ url('/liverev/create?live='.$live->id) }}">
                                <span class="glyphicon glyphicon-pencil"></span> 写活动评论
                            </a>
                        </p>
                        <p><span class="text-muted">开始时间：</span>{{ date('Y-m-d H:i', strtotime($live->showtime)) }}</p>
                        <p class="content-pre-line">{{ $live->information }}</p>
                        <p><span class="text-muted">录音地址：</span>@if($live->record_url)<a href="{{ $live->record_url }}" target="_blank">{{ $live->record_url }}</a>@else未知@endif</p>
                    </div>
                    <div class="col-md-4">
                        <p>
                            <a href="{{ $live->poster_url  }}" target="_blank">
                                <img src="{{ $live->poster_url }}" class="img-responsive" alt="海报">
                            </a>
                        </p>
                    </div>
                </div>
                @if (Auth::check())
                    <div class="reviews">
                        <h4 class="text-success">我的评论</h4>
                        @foreach ($userReviews as $review)
                            <div class="review">
                                <div class="review-title">
                                    <div class="row">
                                        <div class="col-md-10">
                                            {{ $review->created_at }}
                                            {{ $review->title }}
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <a href="{{ url('/liverev/'.$review->id) }}" target="_blank">查看</a>
                                            <a class="text-muted" href="{{ url('/liverev/'.$review->id.'/edit') }}">修改</a>
                                            <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/liverev/'.$review->id) }}">删除</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="review-content">{{ $review->content }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="reviews">
                    <h4 class="text-success">最新评论<small>（<a href="{{ url('/live/'.$live->id.'/reviews') }}" target="_blank">查看全部{{ $live->reviews }}篇评论</a>）</small></h4>
                    @foreach ($reviews as $review)
                        <div class="review">
                            <div class="review-title">
                                <a href="{{ url('/user/'.$review->user->id) }}" target="_blank">{{ $review->user->name }}</a>
                                {{ $review->created_at }}
                                {{ $review->title }}
                                <span class="pull-right">
                                    <a href="{{ url('/liverev/'.$review->id) }}" target="_blank">查看</a>
                                </span>
                            </div>
                            <div class="review-content">{{ $review->content }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-3">
                <p>
                    <a class="btn btn-warning btn-xs" href="{{ url('/live/'.$live->id.'/edit') }}">
                        <span class="glyphicon glyphicon-edit"></span> 编辑活动信息
                    </a>
                </p>
                <p>
                    <a class="btn btn-info btn-xs" href="{{ url('/live/'.$live->id.'/versions') }}" target="_blank">
                        <span class="glyphicon glyphicon-book"></span> 查看版本列表
                    </a>
                </p>
                <p>
                    <a class="btn btn-danger btn-xs" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/live/'.$live->id) }}">
                        <span class="glyphicon glyphicon-trash"></span> 删除活动
                    </a>
                </p>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span class="glyphicon glyphicon-gift"></span> 最新收藏
                            <small>（<a href="{{ url('/live/'.$live->id.'/favorites') }}" target="_blank">查看全部{{ $live->favorites }}条收藏</a>）</small>
                        </h4>
                    </div>
                    <ul class="list-group">
                        @foreach ($favorites as $favorite)
                            <li class="list-group-item">
                                <a href="{{ url('/user/'.$favorite->user->id) }}" target="_blank">{{ $favorite->user->name }}</a>
                                {{ $favorite->created_at->format('m-d') }}
                            </li>
                        @endforeach
                    </ul>
                </div>
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

@section('script')
    <script src="https://cdn.bootcss.com/Readmore.js/2.0.5/readmore.min.js"></script>
@endsection
