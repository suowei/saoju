@extends('app')

@section('title', 'SC列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <p>
                    职位：&nbsp;&nbsp;
                    <?php
                    $url = url('/sc?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'job')
                            $url .= $key.'='.$value.'&';
                    }
                    ?>
                    @if(isset($params['job']))
                        <a href="{{ $url }}">全部</a>
                    @else
                        <span class="label label-primary">全部</span>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 0)
                        <span class="label label-primary">CV</span>
                    @else
                        <a href="{{ $url.'job=0' }}">CV</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 1)
                        <span class="label label-primary">策划</span>
                    @else
                        <a href="{{ $url.'job=1' }}">策划</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 2)
                        <span class="label label-primary">导演</span>
                    @else
                        <a href="{{ $url.'job=2' }}">导演</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 3)
                        <span class="label label-primary">编剧</span>
                    @else
                        <a href="{{ $url.'job=3' }}">编剧</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 4)
                        <span class="label label-primary">后期</span>
                    @else
                        <a href="{{ $url.'job=4' }}">后期</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 5)
                        <span class="label label-primary">美工</span>
                    @else
                        <a href="{{ $url.'job=5' }}">美工</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 6)
                        <span class="label label-primary">宣传</span>
                    @else
                        <a href="{{ $url.'job=6' }}">宣传</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 7)
                        <span class="label label-primary">填词</span>
                    @else
                        <a href="{{ $url.'job=7' }}">填词</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 8)
                        <span class="label label-primary">歌后</span>
                    @else
                        <a href="{{ $url.'job=8' }}">歌后</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 9)
                        <span class="label label-primary">作者</span>
                    @else
                        <a href="{{ $url.'job=9' }}">作者</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 10)
                        <span class="label label-primary">歌手</span>
                    @else
                        <a href="{{ $url.'job=10' }}">歌手</a>
                    @endif
                    &nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 11)
                        <span class="label label-primary">其他</span>
                    @else
                        <a href="{{ $url.'job=11' }}">其他</a>
                    @endif
                </p>
                <div class="drama">
                    排序：&nbsp;&nbsp;
                    <?php
                    $url = url('/sc?');
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
                                <a href="{{ $url.'sort=reviews&order=desc' }}">印象数量<span class="glyphicon glyphicon-arrow-up order-select"></span></a>
                            @else
                                <a href="{{ $url.'sort=reviews&order=asc' }}">印象数量<span class="glyphicon glyphicon-arrow-down order-select"></span></a>
                            @endif
                        </strong>
                    @else
                        <a href="{{ $url.'sort=reviews&order=desc' }}">印象数量<span class="glyphicon glyphicon-arrow-down order"></span></a>
                    @endif
                    <span class="pull-right">
                        共{{ $scs->total() }}位SC&nbsp;&nbsp;
                        <a href="{{ url('/sc') }}"><span class="glyphicon glyphicon-repeat"></span> 重新筛选</a>
                    </span>
                </div>
                <div>
                    @foreach($scs as $sc)
                        <div class="drama">
                            <a href="{{ url('/sc/'.$sc->id) }}" target="_blank">{{ $sc->name }}</a>
                            <span class="text-muted">{{ $sc->alias }}；{{ $sc->club->name or '' }}；{{ $sc->jobs }}</span>
                        </div>
                    @endforeach
                </div>
                <?php echo $scs->appends($params)->render(); ?>
            </div>
            <div class="col-md-3">
                <wb:share-button appkey="125628789" addition="number" type="button"></wb:share-button>
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/sc/create') }}" target="_blank">
                        <span class="glyphicon glyphicon-plus"></span> 添加SC信息
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
