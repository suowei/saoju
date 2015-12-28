@extends('app')

@section('title', '2015听剧总结（按分集评分排序） - ')

@section('css')
    <link href="{{ asset('/css/star-rating.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">2015听剧总结（按我的分集评分排序）</h4>
                @foreach($episodes as $episode)
                    <div class="row drama">
                        <h4>
                            《<a href="{{ url('/drama/'.$episode->drama_id) }}" target="_blank">{{ $episode->drama->title }}</a>》
                            <a href="{{ url('/episode/'.$episode->id) }}" target="_blank">{{ $episode->title }}</a>
                            <span class="pull-left"><input type="number" class="rating" value="{{ $episode->rating }}" data-size="rating-user-favorite" data-show-clear="false" readonly></span>
                        </h4>
                        <p>
                            {{ $episode->release_date }}，
                            @if($episode->drama->type == 0)
                                耽美
                            @elseif($episode->drama->type == 1)
                                全年龄
                            @elseif($episode->drama->type == 2)
                                言情
                            @else
                                百合
                            @endif，
                            @if($episode->drama->era == 0)
                                现代
                            @elseif($episode->drama->era == 1)
                                古风
                            @elseif($episode->drama->era == 2)
                                民国
                            @elseif($episode->drama->era == 3)
                                未来
                            @else
                                其他时代
                            @endif，
                            {{ $episode->drama->original == 1 ? '原创' : '改编' }}，
                            @if($episode->drama->state == 0)
                                连载
                            @elseif($episode->drama->state == 1)
                                完结
                            @else
                                已坑
                            @endif；
                            {{ $episode->drama->sc }}
                        </p>
                    </div>
                @endforeach
                <?php echo $episodes->render(); ?>
            </div>
            <div class="col-md-3">
                <p class="text-danger">本页展示了2015年发布的分集中，您标记过收藏的分集，如需按所属剧集收藏查看，请点击下面的链接↓</p>
                <p><a href="{{ url('/user/drama2015') }}" class="btn btn-info btn-xs">按我的剧集评分排序</a></p>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/star-rating.min.js') }}"></script>
@endsection
