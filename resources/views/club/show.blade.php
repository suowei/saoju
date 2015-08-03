@extends('app')

@section('title', $club->name.' - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>
                    {{ $club->name }}
                    <a class="btn btn-success btn-xs" href="{{ url('/screv/create?club='.$club->id) }}">
                        <span class="glyphicon glyphicon-pencil"></span> 发表印象
                    </a>
                </h3>
                <div>
                    {!! $club->information !!}
                </div>
                <h3 class="text-success">成员</h3>
                <p>
                    @foreach($scs as $sc)
                        <a href="{{ url('/sc/'.$sc->id) }}" target="_blank">{{ $sc->name }}</a>&nbsp;
                    @endforeach
                </p>
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
                    <h4 class="text-success">最新印象<small>（<a href="{{ url('/club/'.$club->id.'/reviews') }}" target="_blank">查看全部{{ $club->reviews }}篇印象</a>）</small></h4>
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
                    <a class="btn btn-primary btn-xs" href="{{ url('/club/'.$club->id.'/edit') }}">
                        <span class="glyphicon glyphicon-edit"></span> 编辑社团信息
                    </a>
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
