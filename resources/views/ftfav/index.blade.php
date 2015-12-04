@extends('app')

@section('title', '最新节目收藏 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h4 class="text-success">最新节目收藏</h4>
                @foreach ($favorites as $favorite)
                    <p>
                        <a href="{{ url('/user/'.$favorite->user->id) }}" target="_blank">{{ $favorite->user->name }}</a>
                        @if($favorite->type == 0)想听@elseif($favorite->type == 1)在追@elseif($favorite->type == 2)听过
                        @elseif($favorite->type == 3)搁置@else抛弃@endif
                        《<a href="{{ url('/ft/'.$favorite->ft_id) }}" target="_blank">{{ $favorite->ft->title }}</a>》
                        {{ $favorite->created_at }}
                    </p>
                @endforeach
            </div>
            <div class="col-md-6">
                <h4 class="text-success">最新节目分集收藏</h4>
                @foreach ($epfavs as $favorite)
                    <p>
                        <a href="{{ url('/user/'.$favorite->user->id) }}" target="_blank">{{ $favorite->user->name }}</a>
                        《<a href="{{ url('/ft/'.$favorite->ft_id) }}" target="_blank">{{ $favorite->ft_title }}</a>》
                        <a href="{{ url('/ftep/'.$favorite->ftep_id) }}">{{ $favorite->ftep_title }}</a>
                        {{ $favorite->created_at }}
                    </p>
                @endforeach
            </div>
        </div>
    </div>
@endsection
