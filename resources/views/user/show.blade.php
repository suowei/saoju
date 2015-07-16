@extends('app')

@section('title', $user->name.'的主页 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3>{{ $user->name }}</h3>
                <p>{{ $user->created_at }}加入</p>
                <p>自我介绍：@if($user->introduction){{ $user->introduction }}@else无@endif</p>
                <h4 class="text-success"><span class="glyphicon glyphicon-facetime-video"></span> 分集收藏</h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href="{{ url('/user/'.$user->id.'/epfavs/0') }}" target="_blank">
                                        <span class="glyphicon glyphicon-headphones"></span> 想听：{{ $user->epfav0 }}期
                                    </a>
                                </h4>
                            </div>
                            <ul class="list-group">
                                @foreach($epfavs[0] as $favorite)
                                    <li class="list-group-item">
                                        《<a href="{{ url('/drama/'.$favorite->episode->drama_id) }}" target="_blank">{{ $favorite->episode->drama_title }}</a>》<a href="{{ url('/episode/'.$favorite->episode_id) }}" target="_blank">{{ $favorite->episode->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href="{{ url('/user/'.$user->id.'/epfavs/2') }}" target="_blank">
                                        <span class="glyphicon glyphicon-headphones"></span> 听过：{{ $user->epfav2 }}期
                                    </a>
                                </h4>
                            </div>
                            <ul class="list-group">
                                @foreach($epfavs[2] as $favorite)
                                    <li class="list-group-item">
                                        《<a href="{{ url('/drama/'.$favorite->episode->drama_id) }}" target="_blank">{{ $favorite->episode->drama_title }}</a>》<a href="{{ url('/episode/'.$favorite->episode_id) }}" target="_blank">{{ $favorite->episode->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href="{{ url('/user/'.$user->id.'/epfavs/4') }}" target="_blank">
                                        <span class="glyphicon glyphicon-headphones"></span> 抛弃：{{ $user->epfav4 }}期
                                    </a>
                                </h4>
                            </div>
                            <ul class="list-group">
                                @foreach($epfavs[4] as $favorite)
                                    <li class="list-group-item">
                                        《<a href="{{ url('/drama/'.$favorite->episode->drama_id) }}" target="_blank">{{ $favorite->episode->drama_title }}</a>》<a href="{{ url('/episode/'.$favorite->episode_id) }}" target="_blank">{{ $favorite->episode->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <h4 class="text-success"><span class="glyphicon glyphicon-film"></span> 剧集收藏</h4>
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/user/'.$user->id.'/favorites/0') }}" target="_blank">
                        <span class="glyphicon glyphicon-headphones"></span> 想听：{{ $user->favorite0 }}部
                    </a>
                </p>
                <div class="row">
                    @foreach($favorites[0] as $favorite)
                        <div class="col-md-2">
                            <div class="thumbnail">
                                <a href="{{ url('/drama/'.$favorite->drama->id) }}" target="_blank">
                                    <img src="{{ $favorite->drama->poster_url }}" alt="{{ $favorite->drama->title }}" title="{{ $favorite->drama->title }}">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p>
                    <a class="btn btn-info btn-xs" href="{{ url('/user/'.$user->id.'/favorites/1') }}" target="_blank">
                        <span class="glyphicon glyphicon-play"></span> 在追：{{ $user->favorite1 }}部
                    </a>
                </p>
                <div class="row">
                    @foreach($favorites[1] as $favorite)
                        <div class="col-md-2">
                            <div class="thumbnail">
                                <a href="{{ url('/drama/'.$favorite->drama->id) }}" target="_blank">
                                    <img src="{{ $favorite->drama->poster_url }}" alt="{{ $favorite->drama->title }}" title="{{ $favorite->drama->title }}">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p>
                    <a class="btn btn-success btn-xs" href="{{ url('/user/'.$user->id.'/favorites/2') }}" target="_blank">
                        <span class="glyphicon glyphicon-check"></span> 听过：{{ $user->favorite2 }}部
                    </a>
                </p>
                <div class="row">
                    @foreach($favorites[2] as $favorite)
                        <div class="col-md-2">
                            <div class="thumbnail">
                                <a href="{{ url('/drama/'.$favorite->drama->id) }}" target="_blank">
                                    <img src="{{ $favorite->drama->poster_url }}" alt="{{ $favorite->drama->title }}" title="{{ $favorite->drama->title }}">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p>
                    <a class="btn btn-warning btn-xs" href="{{ url('/user/'.$user->id.'/favorites/3') }}" target="_blank">
                        <span class="glyphicon glyphicon-inbox"></span> 搁置：{{ $user->favorite3 }}部
                    </a>
                </p>
                <div class="row">
                    @foreach($favorites[3] as $favorite)
                        <div class="col-md-2">
                            <div class="thumbnail">
                                <a href="{{ url('/drama/'.$favorite->drama->id) }}" target="_blank">
                                    <img src="{{ $favorite->drama->poster_url }}" alt="{{ $favorite->drama->title }}" title="{{ $favorite->drama->title }}">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p>
                    <a class="btn btn-danger btn-xs" href="{{ url('/user/'.$user->id.'/favorites/4') }}" target="_blank">
                        <span class="glyphicon glyphicon-folder-close"></span> 抛弃：{{ $user->favorite4 }}部
                    </a>
                </p>
                <div class="row">
                    @foreach($favorites[4] as $favorite)
                        <div class="col-md-2">
                            <div class="thumbnail">
                                <a href="{{ url('/drama/'.$favorite->drama->id) }}" target="_blank">
                                    <img src="{{ $favorite->drama->poster_url }}" alt="{{ $favorite->drama->title }}" title="{{ $favorite->drama->title }}">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <h4 class="text-success">
                    <span class="glyphicon glyphicon-comment"></span> 评论
                    <a href="{{ url('/user/'.$user->id.'/reviews') }}" target="_blank">{{ $user->reviews }}篇</a>
                </h4>
                @foreach ($reviews as $review)
                    <div class="review">
                        <div class="review-title">
                            <a href="{{ url('/drama/'.$review->drama_id) }}" target="_blank">{{ $review->drama->title }}</a>
                            @if ($review->episode_id) [<a href="{{ url('/episode/'.$review->episode_id) }}" target="_blank">{{ $review->episode->title }}</a>]@endif
                            {{ $review->created_at }}
                            {{ $review->title }}
                            <span class="pull-right">
                                <a href="{{ url('/review/'.$review->id) }}" target="_blank">查看</a>
                                @if(Auth::id() == $user->id)
                                    <a class="text-muted" href="{{ url('/review/'.$review->id.'/edit') }}">修改</a>
                                    <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/review/'.$review->id) }}">删除</a>
                                @endif
                            </span>
                        </div>
                        <div class="review-content">{{ $review->content }}</div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-3">
                <p class="text-info">以下勋章仅代表网站态度，<br>与谦逊的用户本人无关……</p>
                @if($user->reviews > 100)
                    <h3 style="color: #ce4646;text-shadow: 0 0 1px rgba(0, 0, 0, 0.2);"><span class="glyphicon glyphicon-bookmark"></span> 扫剧大师</h3>
                @elseif($user->reviews > 50)
                    <h3 style="color: #ce4646;text-shadow: 0 0 1px rgba(0, 0, 0, 0.2);"><span class="glyphicon glyphicon-bookmark"></span> 扫剧专家</h3>
                @elseif($user->reviews > 10)
                    <h3 style="color: #ce4646;text-shadow: 0 0 1px rgba(0, 0, 0, 0.2);"><span class="glyphicon glyphicon-bookmark"></span> 扫剧精英</h3>
                @elseif($user->reviews > 0)
                    <h3 style="color: #ce4646;text-shadow: 0 0 1px rgba(0, 0, 0, 0.2);"><span class="glyphicon glyphicon-bookmark"></span> 扫剧新星</h3>
                @endif
                    @if(($favcount = $user->favorite1 + $user->favorite2) > 200)
                        <h3 style="color: #ce4646;text-shadow: 0 0 1px rgba(0, 0, 0, 0.2);"><span class="glyphicon glyphicon-bookmark"></span> 听遍中抓无敌</h3>
                    @elseif($favcount > 100)
                        <h3 style="color: #ce4646;text-shadow: 0 0 1px rgba(0, 0, 0, 0.2);"><span class="glyphicon glyphicon-bookmark"></span> 中抓资深听众</h3>
                    @elseif($favcount > 20)
                        <h3 style="color: #ce4646;text-shadow: 0 0 1px rgba(0, 0, 0, 0.2);"><span class="glyphicon glyphicon-bookmark"></span> 听剧小能手</h3>
                    @elseif($favcount > 0)
                        <h3 style="color: #ce4646;text-shadow: 0 0 1px rgba(0, 0, 0, 0.2);"><span class="glyphicon glyphicon-bookmark"></span> 中抓听众</h3>
                    @endif
                    @if(($addcount = \App\History::where('user_id', $user->id)->where('type', 0)->count()) > 50)
                        <h3 style="color: #ce4646;text-shadow: 0 0 1px rgba(0, 0, 0, 0.2);"><span class="glyphicon glyphicon-bookmark"></span> 搬剧小飞机</h3>
                    @elseif($addcount > 25)
                        <h3 style="color: #ce4646;text-shadow: 0 0 1px rgba(0, 0, 0, 0.2);"><span class="glyphicon glyphicon-bookmark"></span> 搬剧小火车</h3>
                    @elseif($addcount > 5)
                        <h3 style="color: #ce4646;text-shadow: 0 0 1px rgba(0, 0, 0, 0.2);"><span class="glyphicon glyphicon-bookmark"></span> 搬剧小汽车</h3>
                    @elseif($addcount > 0)
                        <h3 style="color: #ce4646;text-shadow: 0 0 1px rgba(0, 0, 0, 0.2);"><span class="glyphicon glyphicon-bookmark"></span> 搬剧小马车</h3>
                    @endif
                @if($user->reviews == 0 && $favcount == 0 && $addcount == 0)
                    <h3 style="color: #ce4646;text-shadow: 0 0 1px rgba(0, 0, 0, 0.2);"><span class="glyphicon glyphicon-bookmark"></span> 中抓资深围观党</h3>
                @endif
                <br>
                @if(Auth::check() && Auth::id() == $user->id)
                    <h4 class="text-warning">修改信息请点击名字<span class="glyphicon glyphicon-hand-up" aria-hidden="true"></span></h4>
                    <h5 class="text-warning">下边<span class="glyphicon glyphicon-hand-down" aria-hidden="true"></span>也行</h5>
                    <p>
                        <a class="btn btn-success btn-xs" href="{{ url('/user/edit') }}">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> 修改信息
                        </a>
                    </p>
                    <p>
                        <a class="btn btn-warning btn-xs" href="{{ url('/user/export/favorites') }}">
                            <span class="glyphicon glyphicon-export" aria-hidden="true"></span> 导出所有收藏
                        </a>
                    </p>
                    <p>
                        <a class="btn btn-primary btn-xs" href="{{ url('/user/export/reviews') }}">
                            <span class="glyphicon glyphicon-export" aria-hidden="true"></span> 导出所有评论
                        </a>
                    </p>
                    <p class="text-danger">
                        <span class="glyphicon glyphicon-info-sign"></span>
                        导出文件格式为CSV，可直接用Excel或记事本等软件打开。
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div id="deleteConfirmModal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">删除确认</h4>
                </div>
                <div class="modal-body">
                    确定要删除吗？
                </div>
                <div class="modal-footer">
                    <form class="form-inline" method="POST" action="/unknown">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-danger">确定</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
