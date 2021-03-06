@extends('app')

@section('title', '《'.$drama->title.'》关联SC - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h3 class="text-success">
                    《<a href="{{ url('/drama/'.$drama->id) }}">{{ $drama->title }}</a>》关联SC
                </h3>
                <?php $jobs = ['原著', '策划', '导演', '编剧', '后期', '美工', '宣传', '填词', '翻唱', '歌曲后期', '其他staff', '主役', '协役', '龙套']; ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="info">
                            <th class="col-md-3">职位</th>
                            <th class="col-md-3">备注</th>
                            <th class="col-md-3">SC</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($episodes as $episode)
                            <tr class="active">
                                <td colspan="4">
                                    <a href="{{ url('episode/'.$episode->id.'/sc') }}" target="_blank">
                                        <span class="glyphicon glyphicon-list-alt"></span> {{ $episode->title }}SC
                                    </a>
                                    <span class="pull-right">
                                        <a class="btn btn-warning btn-xs" href="{{ url('/role/create?episode='.$episode->id) }}">
                                            <span class="glyphicon glyphicon-plus"></span> 添加关联
                                        </a>
                                    </span>
                                </td>
                            </tr>
                            @if(isset($roles[$episode->id]))
                                @foreach($roles[$episode->id] as $role)
                                    <tr>
                                        <td>
                                            {{ $jobs[$role->job] }}
                                        </td>
                                        <td>
                                            {{ $role->note }}
                                        </td>
                                        <td>
                                            <a href="{{ url('/sc/'.$role->sc_id) }}" target="_blank">{{ $role->sc->name }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ url('/role/'.$role->id.'/edit') }}">修改</a>
                                            <a data-toggle="modal" href="#deleteConfirmModal" data-action="{{ url('/role/'.$role->id) }}">删除</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
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
