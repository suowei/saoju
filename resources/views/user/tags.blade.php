@extends('app')

@section('title', $user->name.'的标签 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">
                    <a href="{{ url('/user/'.$user->id) }}">{{ $user->name }}</a>的标签
                </h4>
                @foreach($tagmaps as $tagmap)
                    <div class="drama">
                        {{ $tagmap->count }}部
                        <a href="{{ url('/user/'.$user->id.'/favorites?tag='.$tagmap->tag->name) }}" target="_blank">
                            {{ $tagmap->tag->name }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
