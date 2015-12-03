@extends('app')

@section('title', '最新活动收藏 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">最新活动收藏</h4>
                @foreach ($favorites as $favorite)
                    <p>
                        <a href="{{ url('/user/'.$favorite->user->id) }}" target="_blank">{{ $favorite->user->name }}</a>
                        收藏了
                        <a href="{{ url('/live/'.$favorite->live_id) }}" target="_blank">
                            [{{ date('Ymd', strtotime($favorite->live->showtime)) }}]{{ $favorite->live->title }}
                        </a>
                        {{ $favorite->created_at }}
                    </p>
                @endforeach
            </div>
        </div>
    </div>
@endsection
