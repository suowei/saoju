@extends('app')

@section('title', '已收藏剧单 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">已收藏剧单</h4>
                @foreach($listfavs as $listfav)
                    <div class="drama">
                        <a href="{{ url('/list/'.$listfav->list_id) }}" target="_blank">{{ $listfav->dramalist->title }}</a>
                        收藏时间：{{ $listfav->created_at }}&nbsp;
                        <a class="text-muted" href="{{ url('/listfav/delete?list='.$listfav->list_id) }}">取消收藏</a>
                    </div>
                @endforeach
                <?php echo $listfavs->render(); ?>
            </div>
        </div>
    </div>
@endsection
