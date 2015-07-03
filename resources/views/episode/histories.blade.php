@extends('app')

@section('title', $episode->drama->title.$episode->title.'的编辑历史 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>《<a href="{{ url('/drama/'.$episode->drama_id) }}">{{ $episode->drama->title }}</a>》<a href="{{ url('/episode/'.$episode->id) }}">{{ $episode->title }}</a>的编辑历史</h3>
                @foreach ($histories as $history)
                    <div class="review">
                        <div class="review-title">
                            {{ $history->created_at }}
                            <a href="{{ url('/user/'.$history->user->id) }}" target="_blank">{{ $history->user->name }}</a>
                            @if($history->type == 1)修改@else添加@endif本集
                        </div>
                        <p>{{ $history->content }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection