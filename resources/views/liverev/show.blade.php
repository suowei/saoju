@extends('appzb')

@section('title', $review->user->name.'评论'.$review->live->title.' - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4>
                    <a href="{{ url('/user/'.$review->user_id) }}" target="_blank">{{ $review->user->name }}</a> 评论
                    <a href="{{ url('/live/'.$review->live_id) }}">
                        [{{ date('Ymd', strtotime($review->live->showtime)) }}]{{ $review->live->title }}
                    </a>
                </h4>
                <h3>{{ $review->title }}</h3>
                <div class="review-title">
                    {{ $review->created_at }}
                    <span class="pull-right">
                        @if(Auth::id() == $review->user_id)
                            <a class="text-muted" href="{{ url('/liverev/'.$review->id.'/edit') }}">修改</a>
                            <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/liverev/'.$review->id) }}">删除</a>
                        @endif
                    </span>
                </div>
                <div class="review-show-content">{{ $review->content }}</div>
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
