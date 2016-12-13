@extends('appzb')

@section('title', '节目列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <p>
                <form class="form-inline" method="GET" action="{{ url('/ft') }}">
                    节目名称：&nbsp;&nbsp;
                    <?php
                    $url = url('/ft?');
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
                <form class="form-inline" method="GET" action="{{ url('/ft') }}">
                    节目主持：&nbsp;&nbsp;
                    <?php
                    $url = url('/ft?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'host')
                        {
                            $url .= $key.'='.$value.'&';
                            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
                        }
                    }
                    ?>
                    @if(isset($params['host']))
                        <span class="label label-primary">
                        {{ $params['host'] }}
                            <a class="white-link" href="{{ $url }}"><span class="glyphicon glyphicon-remove"></span></a></span>&nbsp;&nbsp;
                    @endif
                    <input type="text" class="form-control input-sm" name="host">
                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                </form>
                </p>
                <p>
                <form class="form-inline" method="GET" action="{{ url('/ft') }}">
                    节目介绍：&nbsp;&nbsp;
                    <?php
                    $url = url('/ft?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'introduction')
                        {
                            $url .= $key.'='.$value.'&';
                            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
                        }
                    }
                    ?>
                    @if(isset($params['introduction']))
                        <span class="label label-primary">
                        {{ $params['introduction'] }}
                            <a class="white-link" href="{{ $url }}"><span class="glyphicon glyphicon-remove"></span></a></span>&nbsp;&nbsp;
                    @endif
                    <input type="text" class="form-control input-sm" name="introduction">
                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                </form>
                </p>
                <div class="drama">
                    排序：&nbsp;&nbsp;
                    <?php
                    $url = url('/ft?');
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
                        共{{ $fts->total() }}个节目&nbsp;&nbsp;
                        <a href="{{ url('/ft') }}"><span class="glyphicon glyphicon-repeat"></span> 重新筛选</a>
                    </span>
                </div>
                <div>
                    @foreach($fts as $ft)
                        <div class="row drama">
                            <div class="col-md-2">
                                <a href="{{ url('/ft/'.$ft->id) }}" target="_blank">
                                    <img src="{{ $ft->poster_url }}" class="img-responsive" alt="海报">
                                </a>
                            </div>
                            <div class="col-md-10">
                                <h4>
                                    <a href="{{ url('/ft/'.$ft->id) }}" target="_blank">{{ $ft->title }}</a>
                                </h4>
                                <p>主持人：{{ $ft->host }}</p>
                                <p>{{ $ft->introduction }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <?php echo $fts->appends($params)->render(); ?>
            </div>
            <div class="col-md-3">
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/ft/create') }}" target="_blank">
                        <span class="glyphicon glyphicon-plus"></span> 添加节目信息
                    </a>
                </p>
                <p class="text-danger">
                    <span class="glyphicon glyphicon-info-sign"></span>
                    添加节目请先进行筛选，避免重复添加^ ^添加节目单集请前往相应节目页添加。
                </p>
            </div>
        </div>
    </div>
@endsection
