@extends('app')

@section('title', '评论列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">评论列表</h4>
                <p class="text-muted">
                    <span class="glyphicon glyphicon-map-marker"></span>
                    <a href="{{ url('/review?type=-1') }}">全部</a>
                    @if($type == 0)
                        / 耽美
                    @elseif($type == 1)
                        / 全年龄
                    @elseif($type == 2)
                        / 言情
                    @elseif($type == 3)
                        / 百合
                    @endif
                    <span class="pull-right">
                        <span class="glyphicon glyphicon-repeat"></span>
                        切换至
                        @if($type != 0)<a href="{{ url('/review?type=0') }}">耽美</a>@endif
                        @if($type != 1)<a href="{{ url('/review?type=1') }}">全年龄</a>@endif
                        @if($type != 2)<a href="{{ url('/review?type=2') }}">言情</a>@endif
                        @if($type != 3)<a href="{{ url('/review?type=3') }}">百合</a>@endif
                    </span>
                </p>
                <div>
                    @foreach($reviews as $review)
                        <div class="review">
                            <div class="review-title">
                                <a href="{{ url('/user/'.$review->user_id) }}" target="_blank">{{ $review->user->name }}</a> 评论
                                《<a href="{{ url('/drama/'.$review->drama_id) }}" target="_blank">{{ $review->drama_title }}</a>》
                                @if ($review->episode_id) [<a href="{{ url('/episode/'.$review->episode_id) }}" target="_blank">{{ $review->episode->title }}</a>]@endif
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
                </div>
                <?php echo $reviews->appends(['type' => $type])->render(); ?>
            </div>
            <div class="col-md-3">
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.bootcss.com/Readmore.js/2.0.5/readmore.min.js"></script>
@endsection
