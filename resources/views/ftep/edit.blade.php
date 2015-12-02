@extends('appzb')

@section('title', '编辑'.$ft->title.$ftep->title.'的信息 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">编辑《{{ $ft->title }}》{{ $ftep->title }}的信息</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/ftep/'.$ftep->id) }}">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-4 control-label">分集标题</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="title" required="required" placeholder="必填，可标明嘉宾和主题" value="{{ $ftep->title }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">发布日期</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="release_date" placeholder="必填，形如2015-05-28" value="{{ $ftep->release_date }}" required="required">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">发布地址</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="url" value="{{ $ftep->url }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">制作组名单</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="staff" rows="20" required="required" placeholder="必填">{{ $ftep->staff }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">海报地址</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="poster_url" value="{{ $ftep->poster_url }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        修改
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
