@foreach ($reviews as $review)
    <div class="review">
        <div class="review-title">
            <a href="{{ url('/user/'.$review->user_id) }}" target="_blank">{{ $review->user->name }}</a> 评论
            《<a href="{{ url('/drama/'.$review->drama_id) }}" target="_blank">{{ $review->drama_title }}</a>》
            @if ($review->episode_id)
                [<a href="{{ url('/episode/'.$review->episode_id) }}" target="_blank">{{ $review->episode->title }}</a>]
            @endif
            {{ $review->created_at }}
            {{ $review->title }}
            <span class="pull-right">
                                <a href="{{ url('/review/'.$review->id) }}" target="_blank">查看</a>
                            </span>
        </div>
        <div class="review-content">{{ $review->content }}</div>
    </div>
@endforeach
