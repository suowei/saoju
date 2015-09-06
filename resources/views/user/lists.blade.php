@extends('app')

@section('title', $user->name.'的剧单 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">
                    <a href="{{ url('/user/'.$user->id) }}">{{ $user->name }}</a>的剧单
                </h4>
                @foreach($lists as $list)
                    <div class="drama">
                        <a href="{{ url('/list/'.$list->id) }}" target="_blank">{{ $list->title }}</a>
                        创建于{{ $list->created_at }} 更新于{{ $list->updated_at }}
                        @if(Auth::check() && Auth::id() == $user->id)
                            <span class="pull-right">
                                <a class="text-muted" href="{{ url('/list/'.$list->id.'/edit') }}" target="_blank">修改</a>
                                <a class="text-muted" data-toggle="modal" href="#deleteConfirmModal"
                                   data-action="{{ url('/list/'.$list->id) }}">删除</a>
                            </span>
                        @endif
                    </div>
                @endforeach
                <?php echo $lists->render(); ?>
            </div>
        </div>
    </div>

    <div id="deleteConfirmModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
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
