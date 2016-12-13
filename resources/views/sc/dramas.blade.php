@extends('app')

@section('title', $sc->name.'作品列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">
                    <a href="{{ url('/sc/'.$sc->id) }}" target="_blank">{{ $sc->name }}</a>的作品列表
                    <small class="pull-right">
                        <strong><a href="{{ url('/sc/'.$sc->id.'/dramas') }}">
                                <span class="glyphicon glyphicon-th"></span> 剧集</a></strong>
                        <a class="text-muted" href="{{ url('/sc/'.$sc->id.'/episodes') }}">
                            <span class="glyphicon glyphicon-th-list"></span> 分集</a>
                    </small>
                </h4>
                <p>
                    职位：&nbsp;&nbsp;
                    <?php
                    $url = url('/sc/'.$sc->id.'/dramas?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'job')
                            $url .= $key.'='.$value.'&';
                    }
                    ?>
                    @if(isset($params['job']) && $params['job'] == -1)
                        <span class="label label-primary">列表</span>
                    @else
                        <a href="{{ $url.'job=-1' }}">列表</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['job']))
                        <a href="{{ $url }}">分组</a>
                    @else
                        <span class="label label-primary">分组</span>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 0)
                        <span class="label label-primary">原著</span>
                    @else
                        <a href="{{ $url.'job=0' }}">原著</a>
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
                        <span class="label label-primary">翻唱</span>
                    @else
                        <a href="{{ $url.'job=8' }}">翻唱</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 9)
                        <span class="label label-primary">歌曲后期</span>
                    @else
                        <a href="{{ $url.'job=9' }}">歌曲后期</a>
                    @endif&nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 10)
                        <span class="label label-primary">其他staff</span>
                    @else
                        <a href="{{ $url.'job=10' }}">其他staff</a>
                    @endif
                    &nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 11)
                        <span class="label label-primary">主役</span>
                    @else
                        <a href="{{ $url.'job=11' }}">主役</a>
                    @endif
                    &nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 12)
                        <span class="label label-primary">协役</span>
                    @else
                        <a href="{{ $url.'job=12' }}">协役</a>
                    @endif
                    &nbsp;&nbsp;
                    @if(isset($params['job']) && $params['job'] == 13)
                        <span class="label label-primary">龙套</span>
                    @else
                        <a href="{{ $url.'job=13' }}">龙套</a>
                    @endif
                </p>
                <div class="drama">
                    排序：&nbsp;&nbsp;
                    <?php
                    $url = url('/sc/'.$sc->id.'/dramas?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'sort' && $key != 'order')
                            $url .= $key.'='.$value.'&';
                    }
                    ?>
                    @if($params['sort'] == 'roles.id')
                        <strong>
                            @if($params['order'] == 'asc')
                                <a href="{{ $url.'sort=roles.id&order=desc' }}">添加顺序<span class="glyphicon glyphicon-arrow-up order-select"></span></a>
                            @else
                                <a href="{{ $url.'sort=roles.id&order=asc' }}">添加顺序<span class="glyphicon glyphicon-arrow-down order-select"></span></a>
                            @endif
                        </strong>
                    @else
                        <a href="{{ $url.'sort=roles.id&order=desc' }}">添加顺序<span class="glyphicon glyphicon-arrow-down order"></span></a>
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
                    @if($params['sort'] == 'dramas.reviews')
                        <strong>
                            @if($params['order'] == 'asc')
                                <a href="{{ $url.'sort=dramas.reviews&order=desc' }}">评论数量<span class="glyphicon glyphicon-arrow-up order-select"></span></a>
                            @else
                                <a href="{{ $url.'sort=dramas.reviews&order=asc' }}">评论数量<span class="glyphicon glyphicon-arrow-down order-select"></span></a>
                            @endif
                        </strong>
                    @else
                        <a href="{{ $url.'sort=dramas.reviews&order=desc' }}">评论数量<span class="glyphicon glyphicon-arrow-down order"></span></a>
                    @endif&nbsp;&nbsp;
                    @if($params['sort'] == 'dramas.favorites')
                        <strong>
                            @if($params['order'] == 'asc')
                                <a href="{{ $url.'sort=dramas.favorites&order=desc' }}">收藏人数<span class="glyphicon glyphicon-arrow-up order-select"></span></a>
                            @else
                                <a href="{{ $url.'sort=dramas.favorites&order=asc' }}">收藏人数<span class="glyphicon glyphicon-arrow-down order-select"></span></a>
                            @endif
                        </strong>
                    @else
                        <a href="{{ $url.'sort=dramas.favorites&order=desc' }}">收藏人数<span class="glyphicon glyphicon-arrow-down order"></span></a>
                    @endif
                    <span class="pull-right">
                        @if($job != -2)共{{ $roles->count() }}部作品&nbsp;&nbsp;@endif
                        <a href="{{ url('/sc/'.$sc->id.'/dramas') }}"><span class="glyphicon glyphicon-repeat"></span> 重新筛选</a>
                    </span>
                </div>
                <?php $jobs = ['原著', '策划', '导演', '编剧', '后期', '美工', '宣传', '填词', '翻唱', '歌曲后期', '其他staff', '主役', '协役', '龙套']; ?>
                <div>
                    @if($job == -2)
                        @foreach($jobs as $key => $job)
                            @if($roles->has($key))
                                <?php $roles[$key] = $roles[$key]->groupBy('drama_id'); ?>
                                <h4 class="text-success">{{ $job }}<small>（共{{ $roles[$key]->count() }}部）</small></h4>
                                @foreach($roles[$key] as $drama)
                                        《<a href="{{ url('/drama/'.$drama[0]->drama_id) }}" target="_blank">{{ $drama[0]->drama_title }}</a>》
                                        @foreach($drama as $episode)<br>　　{{ $episode->release_date }}
                                        <a href="{{ url('/episode/'.$episode->episode_id) }}" target="_blank">{{ $episode->episode_title }}</a>
                                        {{ $episode->note }}
                                        @endforeach
                                        <br>
                                @endforeach
                            @endif
                        @endforeach
                    @elseif($job == -1)
                        @foreach($roles as $drama)<br>
                            《<a href="{{ url('/drama/'.$drama[0]->drama_id) }}" target="_blank">{{ $drama[0]->drama_title }}</a>》
                            @foreach($drama->groupBy('episode_id') as $episode)<br>　　{{ $episode[0]->release_date }}
                            <a href="{{ url('/episode/'.$episode[0]->episode_id) }}" target="_blank">{{ $episode[0]->episode_title }}</a>
                            @foreach($episode->sortBy('job') as $job)
                                {{ $jobs[$job->job] }}{{ $job->note ? '：'.$job->note : '' }}；
                            @endforeach
                            @endforeach
                        @endforeach
                    @else
                        @foreach($roles as $drama)<br>
                            《<a href="{{ url('/drama/'.$drama[0]->drama_id) }}" target="_blank">{{ $drama[0]->drama_title }}</a>》
                            @foreach($drama as $episode)<br>　　{{ $episode->release_date }}
                            <a href="{{ url('/episode/'.$episode->episode_id) }}" target="_blank">{{ $episode->episode_title }}</a>
                            {{ $episode->note }}
                            @endforeach
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
