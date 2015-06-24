@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4>搜索结果：{{ $dramas->total() }}部</h4>
                @foreach($dramas as $drama)
                    <div class="row drama">
                        <div class="col-md-2">
                            <a href="{{ url('/drama/'.$drama->id) }}" target="_blank"><img src="{{ $drama->poster_url }}" class="img-responsive" alt="海报"></a>
                        </div>
                        <div class="col-md-10">
                            <p>
                                <a href="{{ url('/drama/'.$drama->id) }}" target="_blank">{{ $drama->title }}</a>
                                <span class="text-muted">{{ $drama->alias }}</span>
                            </p>
                            <p>
                                {{ $drama->sc }}
                            </p>
                            <p>
                                @if($drama->type == 0)
                                    耽美
                                @elseif($drama->type == 1)
                                    全年龄
                                @elseif($drama->type == 2)
                                    言情
                                @else
                                    百合
                                @endif /
                                    @if($drama->era == 0)
                                        现代
                                    @elseif($drama->era == 1)
                                        古风
                                    @elseif($drama->era == 2)
                                        民国
                                    @elseif($drama->era == 3)
                                        未来
                                    @else
                                        其他时代
                                    @endif /
                                @if($drama->genre)
                                    {{ $drama->genre }} /
                                @endif
                                {{ $drama->original == 1 ? '原创' : '改编' }} / {{ $drama->count }}期 /
                                @if($drama->state == 0)
                                    连载
                                @elseif($drama->state == 1)
                                    完结
                                @else
                                    已坑
                                @endif
                            </p>
                            <p class="text-muted content-pre-line">{{ $drama->introduction }}</p>
                        </div>
                    </div>
                @endforeach
                <?php echo $dramas->appends(['search' => $search])->render(); ?>
            </div>
            <div class="col-md-3">
                <h4 class="text-warning">没找到？来这里添加<span class="glyphicon glyphicon-hand-up" aria-hidden="true"></span></h4>
                <h5 class="text-warning">下边<span class="glyphicon glyphicon-hand-down" aria-hidden="true"></span>也行</h5>
                <p>
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    <a href="{{ url('/drama/create') }}">添加剧集信息</a>
                </p>
            </div>
        </div>
    </div>
@endsection
