@extends('appzb')

@section('title', '节目分集列表 - ')

@section('css')
    <link href="//cdn.bootcss.com/bootstrap-datepicker/1.4.0/css/bootstrap-datepicker3.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <p>
                <form class="form-inline" method="GET" action="{{ url('/ftep') }}">
                    节目名称：&nbsp;&nbsp;
                    <?php
                    $url = url('/ftep?');
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
                <form class="form-inline" method="GET" action="{{ url('/ftep') }}">
                    节目主持：&nbsp;&nbsp;
                    <?php
                    $url = url('/ftep?');
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
                <form class="form-inline" method="GET" action="{{ url('/ftep') }}">
                    制作名单：&nbsp;&nbsp;
                    <?php
                    $url = url('/ftep?');
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
                <form class="form-inline" method="GET" action="{{ url('/ftep') }}">
                    发布日期：&nbsp;&nbsp;
                    <?php
                    $url = url('/ftep?');
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
                <div class="drama">
                    排序：&nbsp;&nbsp;
                    <?php
                    $url = url('/ftep?');
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
                    @if($params['sort'] == 'release_date')
                        <strong>
                            @if($params['order'] == 'asc')
                                <a href="{{ $url.'sort=release_date&order=desc' }}">发布时间<span class="glyphicon glyphicon-arrow-up order-select"></span></a>
                            @else
                                <a href="{{ $url.'sort=release_date&order=asc' }}">发布时间<span class="glyphicon glyphicon-arrow-down order-select"></span></a>
                            @endif
                        </strong>
                    @else
                        <a href="{{ $url.'sort=release_date&order=asc' }}">发布时间<span class="glyphicon glyphicon-arrow-up order"></span></a>
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
                        共{{ $fteps->total() }}期节目分集&nbsp;&nbsp;
                        <a href="{{ url('/ftep') }}"><span class="glyphicon glyphicon-repeat"></span> 重新筛选</a>
                    </span>
                </div>
                <div>
                    @foreach($fteps as $ftep)
                        <div class="row drama">
                            <div class="col-md-2">
                                <a href="{{ url('/ftep/'.$ftep->id) }}" target="_blank">
                                    <img src="{{ $ftep->poster_url }}" class="img-responsive" alt="海报">
                                </a>
                            </div>
                            <div class="col-md-10">
                                <h4>
                                    《<a href="{{ url('/ft/'.$ftep->ft_id) }}"
                                        target="_blank">{{ $ftep->ft_title }}</a>》<a
                                            href="{{ url('/ftep/'.$ftep->id) }}" target="_blank">{{ $ftep->title }}</a>
                                </h4>
                                <p>主持人：{{ $ftep->host }}</p>
                                <p>发布日期：{{ $ftep->release_date }}</p>
                                <p>{{ $ftep->staff }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <?php echo $fteps->appends($params)->render(); ?>
            </div>
            <div class="col-md-3">
                <wb:share-button appkey="125628789" addition="number" type="button"></wb:share-button>
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/ft/create') }}" target="_blank">
                        <span class="glyphicon glyphicon-plus"></span> 添加节目信息
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="//cdn.bootcss.com/bootstrap-datepicker/1.4.0/js/bootstrap-datepicker.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap-datepicker/1.4.0/locales/bootstrap-datepicker.zh-CN.min.js"></script>
    <script type="text/javascript">
        $('.date').datepicker({
            format: "yyyy-mm-dd",
            endDate: "0d",
            language: "zh-CN",
            autoclose: true,
            todayHighlight: true
        });
    </script>
@endsection
