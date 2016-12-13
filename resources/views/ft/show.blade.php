@extends('appzb')

@section('title', $ft->title.' - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-3">
                        <img src="{{ $ft->poster_url }}" class="img-responsive">
                    </div>
                    <div class="col-md-9">
                        <h3>《{{ $ft->title }}》</h3>
                        <p><span class="text-muted">主持人：</span>{{ $ft->host }}</p>
                        <p class="content-pre-line">{{ $ft->introduction }}</p>
                        <p>
                            @if(Auth::check())
                                @if($favorite)
                                    <span class="glyphicon glyphicon-headphones"></span> 已收藏
                                    <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/ftfav/'.$ft->id) }}">删除收藏</a>
                                @else
                                    <a class="btn btn-info btn-xs" href="{{ url('/ftfav/create?ft='.$ft->id) }}">收藏节目并写评</a>
                                    <a class="btn btn-warning btn-xs" href="{{ url('/ftfav/store?ft='.$ft->id) }}">
                                        <span class="glyphicon glyphicon-gift"></span> 收藏节目
                                    </a>
                                @endif
                            @else
                                <a class="btn btn-info btn-xs" href="{{ url('/ftfav/create?ft='.$ft->id) }}">
                                    收藏并写评
                                </a>
                            @endif
                            <a class="btn btn-success btn-xs" href="{{ url('/ftrev/create?ft='.$ft->id) }}">
                                <span class="glyphicon glyphicon-pencil"></span> 写节目评论
                            </a>
                        </p>
                    </div>
                </div>
                <br>
                <div class="panel-group" id="accordion" role="tablist">
                    @foreach($fteps as $ftep)
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#{{ $ftep->id }}">
                                        {{ $ftep->title }}
                                    </a>
                                </h4>
                            </div>
                            <div id="{{ $ftep->id }}" class="panel-collapse collapse" role="tabpanel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-4"><img src="{{ $ftep->poster_url }}" class="img-responsive"></div>
                                        <div class="col-md-8">
                                            <p><a class="btn btn-primary btn-xs" href="{{ url('/ftep/'.$ftep->id) }}" target="_blank">
                                                    <span class="glyphicon glyphicon-share-alt"></span> 去分集页面
                                                </a></p>
                                            <p><span class="text-muted">发布日期：</span>{{ $ftep->release_date }}</p>
                                            <p class="content-pre-line">{{ $ftep->staff }}</p>
                                            <p><a href="{{ $ftep->url }}" target="_blank">{{ $ftep->url }}</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (Auth::check())
                    <div class="reviews">
                        <h4 class="text-success">我的评论</h4>
                        @foreach ($userReviews as $review)
                            <div class="review">
                                <div class="review-title">
                                    {{ $review->created_at }}
                                    @if ($review->ftep_id)<a href="{{ url('/ftep/'.$review->ftep_id) }}" target="_blank">{{ $review->ftep->title }}</a>@endif
                                    {{ $review->title }}
                                    <span class="pull-right">
                                    <a href="{{ url('/ftrev/'.$review->id) }}" target="_blank">查看</a>
                                    <a class="text-muted" href="{{ url('/ftrev/'.$review->id.'/edit') }}">修改</a>
                                    <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/ftrev/'.$review->id) }}">删除</a>
                                </span>
                                </div>
                                <div class="review-content">{{ $review->content }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="reviews">
                    <h4 class="text-success">最新评论<small>（<a href="{{ url('/ft/'.$ft->id.'/reviews') }}" target="_blank">查看全部{{ $ft->reviews }}篇评论</a>）</small></h4>
                    @foreach ($reviews as $review)
                        <div class="review">
                            <div class="review-title">
                                <a href="{{ url('/user/'.$review->user->id) }}" target="_blank">{{ $review->user->name }}</a>
                                {{ $review->created_at }}
                                @if($review->ftep_id)<a href="{{ url('/ftep/'.$review->ftep_id) }}" target="_blank">{{ $review->ftep->title }}</a>@endif
                                {{ $review->title }}
                                <span class="pull-right">
                                <a href="{{ url('/ftrev/'.$review->id) }}" target="_blank">查看</a>
                            </span>
                            </div>
                            <div class="review-content">{{ $review->content }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-3">
                <p>
                    <a class="btn btn-success btn-xs" href="{{ url('/ftep/create?ft='.$ft->id) }}">
                        <span class="glyphicon glyphicon-plus"></span> 更新节目（添加分集）
                    </a>
                </p>
                <p class="text-danger">
                    <span class="glyphicon glyphicon-info-sign"></span>
                    分集信息维护请进入相应分集页面操作
                </p>
                <p>
                    <a class="btn btn-warning btn-xs" href="{{ url('/ft/'.$ft->id.'/edit') }}">
                        <span class="glyphicon glyphicon-edit"></span> 编辑节目信息
                    </a>
                </p>
                <p>
                    <a class="btn btn-info btn-xs" href="{{ url('/ft/'.$ft->id.'/versions') }}" target="_blank">
                        <span class="glyphicon glyphicon-book" aria-hidden="true"></span> 查看版本列表
                    </a>
                </p>
                <p>
                    <a class="btn btn-danger btn-xs" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/ft/'.$ft->id) }}">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 删除节目信息
                    </a>
                </p>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span class="glyphicon glyphicon-gift"></span> 最新收藏
                            <small>（<a href="{{ url('/ft/'.$ft->id.'/favorites') }}" target="_blank">查看全部{{ $ft->favorites }}条收藏</a>）</small>
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
