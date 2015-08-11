@extends('app')

@section('title', '分集列表 - ')

@section('css')
    <link href="//cdn.bootcss.com/bootstrap-datepicker/1.4.0/css/bootstrap-datepicker3.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <p>
                    性向：&nbsp;&nbsp;
                    <?php
                    $url = url('/episode?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'type')
                            $url .= $key.'='.$value.'&';
                    }
                    ?>
                    @if(isset($params['type']))
                        <a href="{{ $url }}">全部</a>
                    @else
                        <span class="label label-primary">全部</span>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['type']) && $params['type'] == 0)
                        <span class="label label-primary">耽美</span>
                    @else
                        <a href="{{ $url.'type=0' }}">耽美</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['type']) && $params['type'] == 1)
                        <span class="label label-primary">全年龄</span>
                    @else
                        <a href="{{ $url.'type=1' }}">全年龄</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['type']) && $params['type'] == 2)
                        <span class="label label-primary">言情</span>
                    @else
                        <a href="{{ $url.'type=2' }}">言情</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['type']) && $params['type'] == 3)
                        <span class="label label-primary">百合</span>
                    @else
                        <a href="{{ $url.'type=3' }}">百合</a>
                    @endif
                </p>
                <p>
                    时代：&nbsp;&nbsp;
                    <?php
                    $url = url('/episode?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'era')
                            $url .= $key.'='.$value.'&';
                    }
                    ?>
                    @if(isset($params['era']))
                        <a href="{{ $url }}">全部</a>
                    @else
                        <span class="label label-primary">全部</span>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['era']) && $params['era'] == 0)
                        <span class="label label-primary">现代</span>
                    @else
                        <a href="{{ $url.'era=0' }}">现代</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['era']) && $params['era'] == 1)
                        <span class="label label-primary">古风</span>
                    @else
                        <a href="{{ $url.'era=1' }}">古风</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['era']) && $params['era'] == 2)
                        <span class="label label-primary">民国</span>
                    @else
                        <a href="{{ $url.'era=2' }}">民国</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['era']) && $params['era'] == 3)
                        <span class="label label-primary">未来</span>
                    @else
                        <a href="{{ $url.'era=3' }}">未来</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['era']) && $params['era'] == 4)
                        <span class="label label-primary">其他</span>
                    @else
                        <a href="{{ $url.'era=4' }}">其他</a>
                    @endif
                </p>
                <p>
                    原创：&nbsp;&nbsp;
                    <?php
                    $url = url('/episode?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'original')
                            $url .= $key.'='.$value.'&';
                    }
                    ?>
                    @if(isset($params['original']))
                        <a href="{{ $url }}">全部</a>
                    @else
                        <span class="label label-primary">全部</span>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['original']) && $params['original'] == 0)
                        <span class="label label-primary">改编</span>
                    @else
                        <a href="{{ $url.'original=0' }}">改编</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['original']) && $params['original'] == 1)
                        <span class="label label-primary">原创</span>
                    @else
                        <a href="{{ $url.'original=1' }}">原创</a>
                    @endif
                </p>
                <p>
                    进度：&nbsp;&nbsp;
                    <?php
                    $url = url('/episode?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'state')
                            $url .= $key.'='.$value.'&';
                    }
                    ?>
                    @if(isset($params['state']))
                        <a href="{{ $url }}">全部</a>
                    @else
                        <span class="label label-primary">全部</span>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['state']) && $params['state'] == 1)
                        <span class="label label-primary">已完结</span>
                    @else
                        <a href="{{ $url.'state=1' }}">已完结</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['state']) && $params['state'] == 2)
                        <span class="label label-primary">全一期</span>
                    @else
                        <a href="{{ $url.'state=2' }}">全一期</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['state']) && $params['state'] == 0)
                        <span class="label label-primary">连载中</span>
                    @else
                        <a href="{{ $url.'state=0' }}">连载中</a>
                    @endif
                    &nbsp;&nbsp;
                    @if(isset($params['state']) && $params['state'] == 3)
                        <span class="label label-primary">已坑</span>
                    @else
                        <a href="{{ $url.'state=3' }}">已坑</a>
                    @endif
                </p>
                <p>
                <form class="form-inline" method="GET" action="{{ url('/episode') }}">
                    日期：&nbsp;&nbsp;
                    <?php
                    $url = url('/episode?');
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
                <form class="form-inline" method="GET" action="{{ url('/episode') }}">
                    主役：&nbsp;&nbsp;
                    <?php
                    $url = url('/episode?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'cv')
                        {
                            $url .= $key.'='.$value.'&';
                            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
                        }
                    }
                    ?>
                    @if(isset($params['cv']))
                        <span class="label label-primary">
                        {{ $params['cv'] }}
                            <a class="white-link" href="{{ $url }}"><span class="glyphicon glyphicon-remove"></span></a></span>&nbsp;&nbsp;
                    @endif
                    <input type="text" class="form-control input-sm" name="cv">
                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                </form>
                </p>
                <p>
                <form class="form-inline" method="GET" action="{{ url('/episode') }}">
                    成员：&nbsp;&nbsp;
                    <?php
                    $url = url('/episode?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'sc')
                        {
                            $url .= $key.'='.$value.'&';
                            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
                        }
                    }
                    ?>
                    @if(isset($params['sc']))
                        <span class="label label-primary">
                        {{ $params['sc'] }}
                            <a class="white-link" href="{{ $url }}"><span class="glyphicon glyphicon-remove"></span></a></span>&nbsp;&nbsp;
                    @endif
                    <input type="text" class="form-control input-sm" name="sc">
                    <button type="submit" class="btn btn-default btn-sm">确定</button>
                </form>
                </p>
                <div class="drama">
                    排序：&nbsp;&nbsp;
                    <?php
                    $url = url('/episode?');
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
                        共{{ $episodes->total() }}部剧&nbsp;&nbsp;
                        <a href="{{ url('/episode?type=0') }}"><span class="glyphicon glyphicon-repeat"></span> 重新筛选</a>
                    </span>
                </div>
                <div>
                    @foreach($episodes as $episode)
                        <div class="row drama">
                            <div class="col-md-2">
                                <a href="{{ url('/episode/'.$episode->id) }}" target="_blank">
                                    <img src="{{ $episode->poster_url }}" class="img-responsive" alt="海报">
                                </a>
                            </div>
                            <div class="col-md-10">
                                <h4>
                                    《<a href="{{ url('/drama/'.$episode->drama_id) }}" target="_blank">{{ $episode->drama_title }}</a>》
                                    <a href="{{ url('/episode/'.$episode->id) }}" target="_blank">{{ $episode->title }}</a>
                                    <small class="text-muted">{{ $episode->alias }}</small>
                                </h4>
                                <p>
                                    {{ $episode->release_date }}，
                                    @if($episode->type == 0)
                                        耽美
                                    @elseif($episode->type == 1)
                                        全年龄
                                    @elseif($episode->type == 2)
                                        言情
                                    @else
                                        百合
                                    @endif，
                                    @if($episode->era == 0)
                                        现代
                                    @elseif($episode->era == 1)
                                        古风
                                    @elseif($episode->era == 2)
                                        民国
                                    @elseif($episode->era == 3)
                                        未来
                                    @else
                                        其他时代
                                    @endif，
                                    @if($episode->genre)
                                        {{ $episode->genre }}，
                                    @endif
                                    {{ $episode->original == 1 ? '原创' : '改编' }}，
                                    @if($episode->state == 0)
                                        连载
                                    @elseif($episode->state == 1)
                                        完结
                                    @else
                                        已坑
                                    @endif，
                                    {{ $episode->duration }}分钟
                                </p>
                                <p>
                                    {{ $episode->cv }}&nbsp;&nbsp;
                                    <a class="btn btn-default btn-xs" role="button" data-toggle="collapse" href="#sc{{ $episode->id }}">
                                        展开剧组名单 <span class="caret"></span>
                                    </a>
                                </p>
                                <p class="collapse content-pre-line" id="sc{{ $episode->id }}">{{ $episode->sc }}</p>
                                <p class="introduction text-muted content-pre-line">{{ $episode->introduction }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <?php echo $episodes->appends($params)->render(); ?>
            </div>
            <div class="col-md-3">
                <wb:share-button appkey="125628789" addition="number" type="button"></wb:share-button>
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/drama/create') }}" target="_blank">
                        <span class="glyphicon glyphicon-plus"></span> 添加剧集信息
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
