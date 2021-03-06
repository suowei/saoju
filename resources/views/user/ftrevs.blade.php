@extends('app')

@section('title', $user->name.'的节目评论 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">
                    <a href="{{ url('/user/'.$user->id) }}">{{ $user->name }}</a>发表的节目评论（{{ $reviews->total() }}篇）
                </h4>
                @foreach ($reviews as $review)
                    <div class="review">
                        <div class="review-title">
                            《<a href="{{ url('/ft/'.$review->ft_id) }}"
                                target="_blank">{{ $review->ft->title }}</a>》@if($review->ftep_id)<a
                                    href="{{ url('/ftep/'.$review->ftep_id) }}"
                                    target="_blank">{{ $review->ftep->title }}</a>@endif
                            {{ $review->created_at }}
                            {{ $review->title }}
                            <span class="pull-right">
                                <a href="{{ url('/ftrev/'.$review->id) }}" target="_blank">查看</a>
                                @if(Auth::id() == $user->id)
                                    <a class="text-muted" href="{{ url('/ftrev/'.$review->id.'/edit') }}">修改</a>
                                    <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/ftrev/'.$review->id) }}">删除</a>
                                @endif
                            </span>
                        </div>
                        <div class="review-content">{{ $review->content }}</div>
                    </div>
                @endforeach
                <?php echo $reviews->render(); ?>
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
    <script src="https://lib.baomitu.com/Readmore.js/2.0.5/readmore.min.js"></script>
@endsection
