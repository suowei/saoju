@extends('appzb')

@section('title', '歌曲列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <p>
                <form class="form-inline" method="GET" action="{{ url('/song') }}">
                    歌名：&nbsp;&nbsp;
                    <?php
                    $url = url('/song?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'title')
                        {
                            $url .= $key.'='.$value.'&';
                            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
                        }
                    }
                    ?>
                    @if(isset($params['title']))
                        <span class="label label-primary">
                        {{ $params['title'] }}
                            <a class="white-link" href="{{ $url }}"><span class="glyphicon glyphicon-remove"></span></a></span>&nbsp;&nbsp;
                    @endif
                    <input type="text" class="form-control input-sm" name="title">
                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                </form>
                </p>
                <p>
                <form class="form-inline" method="GET" action="{{ url('/song') }}">
                    演唱：&nbsp;&nbsp;
                    <?php
                    $url = url('/song?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'artist')
                        {
                            $url .= $key.'='.$value.'&';
                            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
                        }
                    }
                    ?>
                    @if(isset($params['artist']))
                        <span class="label label-primary">
                        {{ $params['artist'] }}
                            <a class="white-link" href="{{ $url }}"><span class="glyphicon glyphicon-remove"></span></a></span>&nbsp;&nbsp;
                    @endif
                    <input type="text" class="form-control input-sm" name="artist">
                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                </form>
                </p>
                <p>
                <form class="form-inline" method="GET" action="{{ url('/song') }}">
                    制作：&nbsp;&nbsp;
                    <?php
                    $url = url('/song?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'staff')
                        {
                            $url .= $key.'='.$value.'&';
                            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
                        }
                    }
                    ?>
                    @if(isset($params['staff']))
                        <span class="label label-primary">
                        {{ $params['staff'] }}
                            <a class="white-link" href="{{ $url }}"><span class="glyphicon glyphicon-remove"></span></a></span>&nbsp;&nbsp;
                    @endif
                    <input type="text" class="form-control input-sm" name="staff">
                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                </form>
                </p>
                <p>
                <form class="form-inline" method="GET" action="{{ url('/song') }}">
                    歌词：&nbsp;&nbsp;
                    <?php
                    $url = url('/song?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'lyrics')
                        {
                            $url .= $key.'='.$value.'&';
                            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
                        }
                    }
                    ?>
                    @if(isset($params['lyrics']))
                        <span class="label label-primary">
                        {{ $params['lyrics'] }}
                            <a class="white-link" href="{{ $url }}"><span class="glyphicon glyphicon-remove"></span></a></span>&nbsp;&nbsp;
                    @endif
                    <input type="text" class="form-control input-sm" name="lyrics">
                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                </form>
                </p>
                <div class="drama">
                    排序：&nbsp;&nbsp;
                    <?php
                    $url = url('/song?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'sort' && $key != 'order')
                            $url .= $key.'='.$value.'&';
                    }
                    ?>
                    @if($params['sort'] == 'id')
                        <strong>
                            @if($params['order'] == 'asc')
                                <a href="{{ $url.'sort=id&order=desc' }}">添加顺序<span class="glyphicon glyphicon-arrow-up order-select"></span></a>
                            @else
                                <a href="{{ $url.'sort=id&order=asc' }}">添加顺序<span class="glyphicon glyphicon-arrow-down order-select"></span></a>
                            @endif
                        </strong>
                    @else
                        <a href="{{ $url.'sort=id&order=desc' }}">添加顺序<span class="glyphicon glyphicon-arrow-down order"></span></a>
                    @endif&nbsp;&nbsp;
                    @if($params['sort'] == 'reviews')
                        <strong>
                            @if($params['order'] == 'asc')
                                <a href="{{ $url.'sort=reviews&order=desc' }}">评论数量<span class="glyphicon glyphicon-arrow-up order-select"></span></a>
                            @else
                                <a href="{{ $url.'sort=reviews&order=asc' }}">评论数量<span class="glyphicon glyphicon-arrow-down order-select"></span></a>
                            @endif
                        </strong>
                    @else
                        <a href="{{ $url.'sort=reviews&order=desc' }}">评论数量<span class="glyphicon glyphicon-arrow-down order"></span></a>
                    @endif&nbsp;&nbsp;
                    @if($params['sort'] == 'favorites')
                        <strong>
                            @if($params['order'] == 'asc')
                                <a href="{{ $url.'sort=favorites&order=desc' }}">收藏人数<span class="glyphicon glyphicon-arrow-up order-select"></span></a>
                            @else
                                <a href="{{ $url.'sort=favorites&order=asc' }}">收藏人数<span class="glyphicon glyphicon-arrow-down order-select"></span></a>
                            @endif
                        </strong>
                    @else
                        <a href="{{ $url.'sort=favorites&order=desc' }}">收藏人数<span class="glyphicon glyphicon-arrow-down order"></span></a>
                    @endif
                    <span class="pull-right">
                        共{{ $songs->total() }}首歌曲&nbsp;&nbsp;
                        <a href="{{ url('/song') }}"><span class="glyphicon glyphicon-repeat"></span> 重新筛选</a>
                    </span>
                </div>
                <div>
                    @foreach($songs as $song)
                        <div class="drama">
                            <h4>
                                <a href="{{ url('/song/'.$song->id) }}" target="_blank">{{ $song->title }}</a>
                                <small>{{ $song->alias }}</small>
                            </h4>
                            <p>演唱：{{ $song->artist }}</p>
                            <p>{{ $song->staff }}</p>
                        </div>
                    @endforeach
                </div>
                <?php echo $songs->appends($params)->render(); ?>
            </div>
            <div class="col-md-3">
                <wb:share-button appkey="125628789" addition="number" type="button"></wb:share-button>
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/song/create') }}" target="_blank">
                        <span class="glyphicon glyphicon-plus"></span> 添加歌曲信息
                    </a>
                </p>
                <p class="text-danger">
                    <span class="glyphicon glyphicon-info-sign"></span>
                    添加歌曲请先进行筛选，避免重复添加。添加剧集或分集所属歌曲请前往相应剧集、分集页面操作。
                </p>
            </div>
        </div>
    </div>
@endsection
