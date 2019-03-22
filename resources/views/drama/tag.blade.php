@extends('app')

@section('title', $tag.' - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">{{ $tag }}</h4>
                <div class="drama">
                    排序：&nbsp;&nbsp;
                    <?php
                    $url = url('/drama/tag/'.$tag.'?');
                    ?>
                    @if($sort == 'tagcount')
                        <strong>
                            @if($order == 'asc')
                                <a href="{{ $url.'sort=tagcount&order=desc' }}">标记人数<span class="glyphicon glyphicon-arrow-up order-select"></span></a>
                            @else
                                <a href="{{ $url.'sort=tagcount&order=asc' }}">标记人数<span class="glyphicon glyphicon-arrow-down order-select"></span></a>
                            @endif
                        </strong>
                    @else
                        <a href="{{ $url.'sort=tagcount&order=desc' }}">标记人数<span class="glyphicon glyphicon-arrow-down order"></span></a>
                    @endif&nbsp;&nbsp;
                    @if($sort == 'id')
                        <strong>
                            @if($order == 'asc')
                                <a href="{{ $url.'sort=id&order=desc' }}">添加顺序<span class="glyphicon glyphicon-arrow-up order-select"></span></a>
                            @else
                                <a href="{{ $url.'sort=id&order=asc' }}">添加顺序<span class="glyphicon glyphicon-arrow-down order-select"></span></a>
                            @endif
                        </strong>
                    @else
                        <a href="{{ $url.'sort=id&order=desc' }}">添加顺序<span class="glyphicon glyphicon-arrow-down order"></span></a>
                    @endif&nbsp;&nbsp;
                    @if($sort == 'favorites')
                        <strong>
                            @if($order == 'asc')
                                <a href="{{ $url.'sort=favorites&order=desc' }}">收藏人数<span class="glyphicon glyphicon-arrow-up order-select"></span></a>
                            @else
                                <a href="{{ $url.'sort=favorites&order=asc' }}">收藏人数<span class="glyphicon glyphicon-arrow-down order-select"></span></a>
                            @endif
                        </strong>
                    @else
                        <a href="{{ $url.'sort=favorites&order=desc' }}">收藏人数<span class="glyphicon glyphicon-arrow-down order"></span></a>
                    @endif&nbsp;&nbsp;
                    @if($sort == 'reviews')
                        <strong>
                            @if($order == 'asc')
                                <a href="{{ $url.'sort=reviews&order=desc' }}">评论数量<span class="glyphicon glyphicon-arrow-up order-select"></span></a>
                            @else
                                <a href="{{ $url.'sort=reviews&order=asc' }}">评论数量<span class="glyphicon glyphicon-arrow-down order-select"></span></a>
                            @endif
                        </strong>
                    @else
                        <a href="{{ $url.'sort=reviews&order=desc' }}">评论数量<span class="glyphicon glyphicon-arrow-down order"></span></a>
                    @endif
                    <span class="pull-right">
                    共{{ $dramas->total() }}部剧&nbsp;&nbsp;
                    <a href="{{ url('/drama/tag/'.$tag.'?type=0') }}"><span class="glyphicon glyphicon-repeat"></span> 重新排序</a>
                </span>
                </div>
                <div>
                    @foreach($dramas as $drama)
                        <?php if($sort=='tagcount') $drama = $drama->drama; ?>
                        <div class="row drama">
                            <div class="col-md-2">
                                <a href="{{ url('/drama/'.$drama->id) }}" target="_blank">
                                    <img src="{{ $drama->poster_url }}" class="img-responsive" alt="海报">
                                </a>
                            </div>
                            <div class="col-md-10">
                                <h4>
                                    <a href="{{ url('/drama/'.$drama->id) }}" target="_blank">{{ $drama->title }}</a>
                                    <small class="text-muted">{{ $drama->alias }}</small>
                                </h4>
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
                                    @endif，
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
                                    @endif，
                                    @if($drama->genre)
                                        {{ $drama->genre }}，
                                    @endif
                                    {{ $drama->original == 1 ? '原创' : '改编' }}， {{ $drama->count }}期，
                                    @if($drama->state == 0)
                                        连载
                                    @elseif($drama->state == 1)
                                        完结
                                    @else
                                        已坑
                                    @endif
                                </p>
                                <p class="introduction text-muted content-pre-line">{{ $drama->introduction }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <?php echo $dramas->appends(['sort' => $sort, 'order' => $order])->render(); ?>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://lib.baomitu.com/Readmore.js/2.0.5/readmore.min.js"></script>
@endsection
