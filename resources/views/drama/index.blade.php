@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <h4 class="text-success">剧集列表</h4>
            <p class="text-muted">
                <span class="glyphicon glyphicon-map-marker"></span>
                <a href="{{ url('/drama?type=-1') }}">全部</a>
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
                    @if($type != 0)<a href="{{ url('/drama?type=0') }}">耽美</a>@endif
                    @if($type != 1)<a href="{{ url('/drama?type=1') }}">全年龄</a>@endif
                    @if($type != 2)<a href="{{ url('/drama?type=2') }}">言情</a>@endif
                    @if($type != 3)<a href="{{ url('/drama?type=3') }}">百合</a>@endif
                    </span>
            </p>
            <div>
                @foreach($dramas as $drama)
                    <div>
                        <h4>《<a href="{{ url('/drama/'.$drama->id) }}" target="_blank">{{ $drama->title }}</a>》</h4>
                        <p>
                            @if($drama->alias)
                                {{ $drama->alias }}，
                            @endif
                            @if($type < 0)
                                @if($drama->type == 0)
                                    耽美，
                                @elseif($drama->type == 1)
                                    全年龄，
                                @elseif($drama->type == 2)
                                    言情，
                                @else
                                    百合，
                                @endif
                                @endif
                                @if($drama->era == 0)
                                    现代，
                                @elseif($drama->era == 1)
                                    古风，
                                @elseif($drama->era == 2)
                                    民国，
                                @elseif($drama->era == 3)
                                    未来，
                                @else
                                    其他时代，
                                @endif
                            @if($drama->genre)
                                {{ $drama->genre }}，
                                @endif
                            {{ $drama->original==1 ? '原创' : '改编' }}，
                            {{ $drama->count }}期，
                            @if($drama->state == 0)
                                连载
                            @elseif($drama->state == 1)
                                完结
                            @else
                                已坑
                            @endif
                        </p>
                        <p>{{ $drama->sc }}</p><br>
                    </div>
                @endforeach
            </div>
            <?php echo $dramas->appends(['type' => $type])->render(); ?>
        </div>
        <div class="col-md-3">
        </div>
    </div>
</div>
@endsection
