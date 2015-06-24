@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">单集列表</h4>
                <p class="text-muted">
                    <span class="glyphicon glyphicon-map-marker"></span>
                    <a href="{{ url('/episode?type=-1') }}">全部</a>
                    @if($type == 0)
                        / 耽美
                    @elseif($type == 1)
                        / 全年龄
                    @elseif($type == 2)
                        / 言情
                    @elseif($type == 3)
                        / 百合
                    @endif
                    <span class="pull-right">
                        <span class="glyphicon glyphicon-repeat"></span>
                        切换至
                        @if($type != 0)<a href="{{ url('/episode?type=0') }}">耽美</a>@endif
                        @if($type != 1)<a href="{{ url('/episode?type=1') }}">全年龄</a>@endif
                        @if($type != 2)<a href="{{ url('/episode?type=2') }}">言情</a>@endif
                        @if($type != 3)<a href="{{ url('/episode?type=3') }}">百合</a>@endif
                    </span>
                </p>
                <div>
                    @foreach($episodes as $episode)
                        <div>
                            <h4>
                                《<a href="{{ url('/drama/'.$episode->drama_id) }}" target="_blank">{{ $episode->drama_title }}</a>》
                                <a href="{{ url('/episode/'.$episode->episode_id) }}" target="_blank">{{ $episode->episode_title }}</a>
                            </h4>
                            <p>
                                {{ $episode->release_date }}，
                                @if($episode->alias)
                                    {{ $episode->alias }}，
                                @endif
                                @if($type < 0)
                                    @if($episode->type == 0)
                                        耽美，
                                    @elseif($episode->type == 1)
                                        全年龄，
                                    @elseif($episode->type == 2)
                                        言情，
                                    @else
                                        百合，
                                    @endif
                                @endif
                                @if($episode->era == 0)
                                    现代，
                                @elseif($episode->era == 1)
                                    古风，
                                @elseif($episode->era == 2)
                                    民国，
                                @elseif($episode->era == 3)
                                    未来，
                                @else
                                    其他时代，
                                @endif
                                @if($episode->genre)
                                    {{ $episode->genre }}，
                                @endif
                                {{ $episode->original==1 ? '原创' : '改编' }}，
                                @if($episode->state == 0)
                                    连载，
                                @elseif($episode->state == 1)
                                    完结，
                                @else
                                    已坑，
                                @endif
                                {{ $episode->duration }}分钟
                            </p>
                            <p>{{ $episode->drama_sc }}</p><br>
                        </div>
                    @endforeach
                </div>
                <?php echo $episodes->appends(['type' => $type])->render(); ?>
            </div>
            <div class="col-md-3">
            </div>
        </div>
    </div>
@endsection
