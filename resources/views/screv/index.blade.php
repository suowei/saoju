@extends('app')

@section('title', 'SC、社团印象列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">SC、社团印象列表</h4>
                <div>
                    @foreach($reviews as $review)
                        <div class="review">
                            <div class="review-title">
                                <a href="{{ url('/user/'.$review->user_id) }}" target="_blank">{{ $review->user->name }}</a> 发表了对
                                @if($review->model)
                                    <a href="{{ url('/club/'.$review->model_id) }}" target="_blank">{{ $review->club_name }}</a>
                                @else
                                    <a href="{{ url('/sc/'.$review->model_id) }}" target="_blank">{{ $review->sc_name }}</a>
                                @endif的印象
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
                <?php echo $reviews->render(); ?>
            </div>
            <div class="col-md-3">
            </div>
        </div>
    </div>
@endsection