@extends('appzb')

@section('title', '《'.$ft->title.'》的收藏 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>《<a href="{{ url('/ft/'.$ft->id) }}">{{ $ft->title }}</a>》的收藏<small>（{{ $favorites->total() }}）</small></h3>
                @foreach ($favorites as $favorite)
                    <p>
                        <a href="{{ url('/user/'.$favorite->user_id) }}" target="_blank">{{ $favorite->user->name }}</a>
                        {{ $favorite->created_at }}
                    </p>
                @endforeach
                <?php echo $favorites->render(); ?>
            </div>
        </div>
    </div>
@endsection
