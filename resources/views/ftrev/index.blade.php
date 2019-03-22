@extends('appzb')

@section('title', '节目评论列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">节目评论列表</h4>
                <div>
                    @foreach($reviews as $review)
                        <div class="review">
                            <div class="review-title">
                                <a href="{{ url('/user/'.$review->user_id) }}" target="_blank">{{ $review->user->name }}</a> 评论
                                《<a href="{{ url('/ft/'.$review->ft_id) }}"
                                    target="_blank">{{ $review->ft->title }}</a>》@if($review->ftep_id)<a
                                        href="{{ url('/ftep/'.$review->ftep_id) }}"
                                        target="_blank">{{ $review->ftep->title }}</a>@endif
                                {{ $review->created_at }}
                                {{ $review->title }}
                                <span class="pull-right">
                                    <a href="{{ url('/ftrev/'.$review->id) }}" target="_blank">查看</a>
                                </span>
                            </div>
                            <div class="review-content">{{ $review->content }}</div>
                        </div>
                    @endforeach
                </div>
                <?php echo $reviews->render(); ?>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://lib.baomitu.com/Readmore.js/2.0.5/readmore.min.js"></script>
@endsection
