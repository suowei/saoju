@extends('app')

@section('title', '在追剧集更新 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                @foreach($episodes as $episode)
                    <div class="row drama">
                        <div class="col-md-2">
                            <a href="{{ url('/episode/'.$episode->id) }}" target="_blank">
                                <img src="{{ $episode->poster_url }}" class="img-responsive" alt="海报">
                            </a>
                        </div>
                        <div class="col-md-10">
                            <h4>
                                《<a href="{{ url('/drama/'.$episode->drama_id) }}" target="_blank">{{ $episode->drama->title }}</a>》
                                <a href="{{ url('/episode/'.$episode->id) }}" target="_blank">{{ $episode->title }}</a>
                                <small class="text-muted">{{ $episode->alias }}</small>
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
                                @if($episode->drama->genre)
                                    {{ $episode->drama->genre }}，
                                @endif
                                {{ $episode->drama->original == 1 ? '原创' : '改编' }}，
                                @if($episode->drama->state == 0)
                                    连载
                                @elseif($episode->drama->state == 1)
                                    完结
                                @else
                                    已坑
                                @endif，
                                {{ $episode->duration }}分钟
                            </p>
                            <p>
                                {{ $episode->drama->sc }}
                            </p>
                            <p class="introduction text-muted content-pre-line">{{ $episode->introduction }}</p>
                        </div>
                    </div>
                @endforeach
                <?php echo $episodes->render(); ?>
            </div>
            <div class="col-md-3">
                <p class="text-danger">
                    <span class="glyphicon glyphicon-info-sign"></span>
                    本页展示收藏类型为“想听”及“已听”的剧集中，在收藏时间之后添加的分集。
                </p>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://lib.baomitu.com/Readmore.js/2.0.5/readmore.min.js"></script>
@endsection
