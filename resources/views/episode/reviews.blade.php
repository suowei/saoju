@extends('app')

@section('title', $drama->title.$episode->title.'的评论 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>《<a href="{{ url('/drama/'.$episode->drama_id) }}">{{ $drama->title }}</a>》<a href="{{ url('/episode/'.$episode->id) }}">{{ $episode->title }}</a>的评论<small>（{{ $reviews->total() }}篇）</small></h3>
                @foreach ($reviews as $review)
                    <div class="review">
                        <div class="review-title">
                            <a href="{{ url('/user/'.$review->user_id) }}" target="_blank">{{ $review->user->name }}</a>
                            {{ $review->created_at }}
                            {{ $review->title }}
                            <span class="pull-right">
                                <a href="{{ url('/review/'.$review->id) }}" target="_blank">查看</a>
                            </span>
                        </div>
                        @if($review->banned)
                            <p class="text-muted">{{ $review->banned }}</p>
                        @else
                            <div class="review-content">{{ $review->content }}</div>
                        @endif
                    </div>
                @endforeach
                <?php echo $reviews->render(); ?>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.bootcss.com/Readmore.js/2.0.5/readmore.min.js"></script>
@endsection
