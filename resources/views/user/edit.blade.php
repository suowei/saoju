@extends('app')

@section('title', '修改我的信息 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">修改我的信息</div>
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

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

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/user/update') }}">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-4 control-label">邮箱</label>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" required="required" value="{{ $user->email }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">昵称</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" required="required" value="{{ $user->name }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">自我介绍</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="introduction" rows="5" placeholder="最多255个字">{{ $user->introduction }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">密码</label>
                                <div class="col-md-6">
                                    <a href="{{ url('/user/editpassword') }}">修改密码</a>
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