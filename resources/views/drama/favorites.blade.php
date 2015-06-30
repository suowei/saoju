@extends('app')

@section('css')
    <link href="{{ asset('/css/star-rating.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>《<a href="{{ url('/drama/'.$drama->id) }}">{{ $drama->title }}</a>》的收藏<small>（{{ $favorites->total() }}）</small></h3>
                @foreach ($favorites as $favorite)
                    <p>
                        <a href="{{ url('/user/'.$favorite->user->id) }}" target="_blank">{{ $favorite->user->name }}</a>
                        {{ $favorite->created_at }}
                        @if($favorite->type == 0)想听@elseif($favorite->type == 1)在追@elseif($favorite->type == 2)听过@elseif($favorite->type == 3)搁置@else抛弃@endif
                    </p>
                @endforeach
                <?php echo $favorites->render(); ?>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/star-rating.min.js') }}"></script>
@endsection
