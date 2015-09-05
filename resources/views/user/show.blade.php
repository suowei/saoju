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
                                        <span class="glyphicon glyphicon-check"></span> 听过：{{ $user->epfav2 }}期
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
                                        <span class="glyphicon glyphicon-folder-close"></span> 抛弃：{{ $user->epfav4 }}期
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
                                @if(Auth::check() && Auth::id() == $user->id)
                                    <a class="text-muted" href="{{ url('/review/'.$review->id.'/edit') }}">修改</a>
                                    <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/review/'.$review->id) }}">删除</a>
                                @endif
                            </span>
                        </div>
                        <div class="review-content">{{ $review->content }}</div>
                    </div>
                @endforeach
                <h4 class="text-success">
                    <span class="glyphicon glyphicon-comment"></span> SC、社团印象
                    <a href="{{ url('/user/'.$user->id.'/screvs') }}" target="_blank">{{ $user->screvs }}篇</a>
                </h4>
                @foreach ($screvs as $review)
                    <div class="review">
                        <div class="review-title">
                            @if($review->model)
                                <a href="{{ url('/club/'.$review->model_id) }}" target="_blank">{{ $review->club_name }}</a>
                            @else
                                <a href="{{ url('/sc/'.$review->model_id) }}" target="_blank">{{ $review->sc_name }}</a>
                            @endif
                            {{ $review->created_at }}
                            {{ $review->title }}
                            <span class="pull-right">
                                <a href="{{ url('/screv/'.$review->id) }}" target="_blank">查看</a>
                                @if(Auth::check() && Auth::id() == $user->id)
                                    <a class="text-muted" href="{{ url('/screv/'.$review->id.'/edit') }}">修改</a>
                                    <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/screv/'.$review->id) }}">删除</a>
                                @endif
                            </span>
                        </div>
                        <div class="review-content">{{ $review->content }}</div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-3">
                <wb:share-button appkey="125628789" addition="number" type="button"></wb:share-button><br>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span class="glyphicon glyphicon-step-forward"></span> 创建的剧单
                            <small class="pull-right">
                                <a href="{{ url('/user/'.$user->id.'/lists') }}" target="_blank">查看全部</a>
                            </small>
                        </h4>
                    </div>
                    <div class="list-group">
                        @foreach($lists as $list)
                            <a href="{{ url('/list/'.$list->id) }}" class="list-group-item" target="_blank">
                                {{ $list->title }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @if(Auth::check() && Auth::id() == $user->id)
                    <p>
                        <a class="btn btn-danger btn-xs" href="{{ url('/list/create') }}" target="_blank">
                            <span class="glyphicon glyphicon-plus"></span> 创建剧单
                        </a>
                    </p>
                    <p>
                        <a class="btn btn-success btn-xs" href="{{ url('/user/edit') }}">
                            <span class="glyphicon glyphicon-cog"></span> 修改信息
                        </a>
                    </p>
                    <p>
                        <a class="btn btn-warning btn-xs" href="{{ url('/user/export/favorites') }}">
                            <span class="glyphicon glyphicon-export"></span> 导出所有收藏
                        </a>
                    </p>
                    <p>
                        <a class="btn btn-primary btn-xs" href="{{ url('/user/export/reviews') }}">
                            <span class="glyphicon glyphicon-export"></span> 导出所有评论
                        </a>
                    </p>
                    <p>
                        <a class="btn btn-info btn-xs" href="{{ url('/user/export/screvs') }}">
                            <span class="glyphicon glyphicon-export"></span> 导出所有SC、社团印象
                        </a>
                    </p>
                    <p class="text-danger">
                        <span class="glyphicon glyphicon-info-sign"></span>
                        导出文件格式为CSV，可直接用Excel或记事本等软件打开。
                    </p>
                    <p>
                        <a class="btn btn-danger btn-xs" href="{{ url('/user/invite') }}" target="_blank">
                            <span class="glyphicon glyphicon-phone"></span> 邀请朋友
                        </a>
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
