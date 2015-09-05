@extends('app')

@section('title', $list->title.' - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4>{{ $list->title }}</h4>
                <p>
                    <a href="{{ url('/user/'.$list->user_id) }}" target="_blank">{{ $list->user->name }}</a>
                    创建于{{ $list->created_at }} 更新于{{ $list->updated_at }}
                </p>
                <p class="content-pre-line">{{ $list->introduction }}</p>
                <p>
                    @if(Auth::check() && App\Listfav::select('user_id')->where('list_id', $list->id)
                                        ->where('user_id', Auth::user()->id)->first())
                        已收藏&nbsp;
                        <a class="text-muted" href="{{ url('/listfav/delete?list='.$list->id) }}">取消收藏</a>
                    @else
                        <a class="btn btn-success btn-xs" href="{{ url('/listfav/create?list='.$list->id) }}">
                            <span class="glyphicon glyphicon-gift"></span> 收藏剧单
                        </a>
                    @endif
                        （{{ $list->favorites }}人收藏）
                    <span class="pull-right">共包含{{ $items->total() }}部作品</span>
                </p>
                <?php
                $types = ['耽美', '全年龄', '言情', '百合'];
                $eras = ['现代', '古风', '民国', '未来', '其他时代'];
                $states = ['连载', '完结', '已坑'];
                ?>
                @foreach($items as $item)
                    <div class="review-title">
                        <span class="badge">{{ $item->no }}</span>
                        收录时间：{{ $item->created_at }} 更新时间：{{ $item->updated_at }}
                        @if(Auth::check() && Auth::user()->id == $list->user_id)
                            <span class="pull-right">
                                    <a class="text-muted" data-toggle="modal" href="#itemModal" data-item="{{ $item }}"
                                       data-action="{{ url('/item/'.$item->id) }}">修改</a>
                                    <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal"
                                       data-action="{{ url('/item/'.$item->id) }}">删除</a>
                                </span>
                        @endif
                    </div>
                    <div class="row version">
                        <div class="col-md-2">
                            <a href="{{ url('/drama/'.$item->drama_id) }}" target="_blank">
                                <img class="img-responsive" alt="海报"
                                     src="{{ $item->episode_id ? $item->episode->poster_url : $item->drama->poster_url }}">
                            </a>
                        </div>
                        <div class="col-md-10">
                            <h4>
                                <a href="{{ url('/drama/'.$item->drama_id) }}" target="_blank">{{ $item->drama->title }}</a>
                                @if($item->episode_id)
                                    <a href="{{ url('/episode/'.$item->episode_id) }}" target="_blank">{{ $item->episode->title }}</a>
                                    <small class="text-muted">{{ $item->episode->alias }}</small>
                                @endif
                            </h4>
                            <p>
                                主役：{{ $item->drama->sc }}<br>
                                类型：{{ $eras[$item->drama->era] }}{{ $types[$item->drama->type] }}<br>
                                @if($item->episode_id)
                                    日期：{{ $item->episode->release_date }}<br>
                                    时长：{{ $item->episode->duration }}分钟
                                @else
                                    描述：{{ $item->drama->genre }}<br>
                                    状态：{{ $item->drama->count }}期{{ $states[$item->drama->state] }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($item->review)
                        <blockquote class="content-pre-line">{{ $item->review }}</blockquote>
                    @endif
                @endforeach
                <?php echo $items->render(); ?>
            </div>
            <div class="col-md-3">
                <p class="text-success">
                    <span class="glyphicon glyphicon-info-sign"></span>
                    剧单既可以包含剧集也可以包含分集，请前往相应剧集或分集页面找到“添加至剧单”进行操作
                </p>
                @if(Auth::check() && Auth::user()->id == $list->user_id)
                    <p>
                        <a class="btn btn-warning btn-xs" href="{{ url('/list/'.$list->id.'/edit') }}">
                            <span class="glyphicon glyphicon-edit"></span> 编辑剧单信息
                        </a>
                    </p>
                    <p>
                        <a class="btn btn-danger btn-xs" data-toggle="modal" href="#deleteConfirmModal"
                           data-action="{{ url('/list/'.$list->id) }}">
                            <span class="glyphicon glyphicon-trash"></span> 删除剧单
                        </a>
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="itemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">编辑项目</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" method="POST" action="action">
                        <input type="hidden" name="_method" value="PUT">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="col-md-2 control-label">序号：</label>
                            <div class="col-md-9">
                                <input class="form-control" type="number" min="1" name="no" value="0" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">评论：</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="review" rows="10">none</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-9 col-md-offset-2">
                                <button type="submit" class="btn btn-info btn-sm">保存</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteConfirmModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
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
