@extends('appzb')

@section('title', '活动列表 - ')

@section('css')
    <link href="//cdn.bootcss.com/bootstrap-datepicker/1.4.0/css/bootstrap-datepicker3.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <p>
                <form class="form-inline" method="GET" action="{{ url('/live') }}">
                    活动主题：&nbsp;&nbsp;
                    <?php
                    $url = url('/live?');
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
                <form class="form-inline" method="GET" action="{{ url('/live') }}">
                    活动日期：&nbsp;&nbsp;
                    <?php
                    $url = url('/live?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'startdate' && $key != 'enddate')
                        {
                            $url .= $key.'='.$value.'&';
                            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
                        }
                    }
                    ?>
                    @if(isset($params['startdate']) || isset($params['enddate']))
                        <span class="label label-primary">
                            @if(isset($params['startdate'])){{ $params['startdate'] }}@endif
                            -
                            @if(isset($params['enddate'])){{ $params['enddate'] }}@endif
                            <a class="white-link" href="{{ $url }}"><span class="glyphicon glyphicon-remove"></span></a></span>&nbsp;&nbsp;
                    @endif
                    <div class="input-group date">
                        <input type="text" class="form-control input-sm" name="startdate">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                    -
                    <div class="input-group date">
                        <input type="text" class="form-control input-sm" name="enddate">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                </form>
                </p>
                <p>
                <form class="form-inline" method="GET" action="{{ url('/live') }}">
                    活动信息：&nbsp;&nbsp;
                    <?php
                    $url = url('/live?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'information')
                        {
                            $url .= $key.'='.$value.'&';
                            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
                        }
                    }
                    ?>
                    @if(isset($params['information']))
                        <span class="label label-primary">
                        {{ $params['information'] }}
                            <a class="white-link" href="{{ $url }}"><span class="glyphicon glyphicon-remove"></span></a></span>&nbsp;&nbsp;
                    @endif
                    <input type="text" class="form-control input-sm" name="information">
                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                </form>
                </p>
                <div class="drama">
                    排序：&nbsp;&nbsp;
                    <?php
                    $url = url('/live?');
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
                    @if($params['sort'] == 'showtime')
                        <strong>
                            @if($params['order'] == 'asc')
                                <a href="{{ $url.'sort=showtime&order=desc' }}">开始时间<span class="glyphicon glyphicon-arrow-up order-select"></span></a>
                            @else
                                <a href="{{ $url.'sort=showtime&order=asc' }}">开始时间<span class="glyphicon glyphicon-arrow-down order-select"></span></a>
                            @endif
                        </strong>
                    @else
                        <a href="{{ $url.'sort=showtime&order=desc' }}">开始时间<span class="glyphicon glyphicon-arrow-down order"></span></a>
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
                        共{{ $lives->total() }}场活动&nbsp;&nbsp;
                        <a href="{{ url('/live') }}"><span class="glyphicon glyphicon-repeat"></span> 重新筛选</a>
                    </span>
                </div>
                <div>
                    @foreach($lives as $live)
                        <div class="drama">
                            <h4>
                                <a href="{{ url('/live/'.$live->id) }}" target="_blank">{{ $live->title }}</a>
                            </h4>
                            <p>{{ date('Y-m-d H:i', strtotime($live->showtime)) }}</p>
                            <p class="introduction text-muted content-pre-line">{{ $live->information }}</p>
                        </div>
                    @endforeach
                </div>
                <?php echo $lives->appends($params)->render(); ?>
            </div>
            <div class="col-md-3">
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/live/create') }}" target="_blank">
                        <span class="glyphicon glyphicon-plus"></span> 添加活动信息
                    </a>
                </p>
                <p class="text-danger">
                    <span class="glyphicon glyphicon-info-sign"></span>
                    添加活动请先进行筛选，以免重复添加^ ^
                </p>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="//cdn.bootcss.com/Readmore.js/2.0.5/readmore.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap-datepicker/1.4.0/js/bootstrap-datepicker.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap-datepicker/1.4.0/locales/bootstrap-datepicker.zh-CN.min.js"></script>
    <script type="text/javascript">
        $('.date').datepicker({
            format: "yyyy-mm-dd",
            language: "zh-CN",
            autoclose: true,
            todayHighlight: true
        });
    </script>
@endsection
