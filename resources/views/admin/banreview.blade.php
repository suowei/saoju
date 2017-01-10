@extends('app')

@section('title', '屏蔽评论 - 管理页 - ')

@section('content')
    <div class="container">
        <h4 class="text-success">后台管理页</h4>
        <br/>
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
        <div class="panel panel-warning">
            <div class="panel-heading">屏蔽评论</div>
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ url('/admin/deletereview') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <p>打开评论详情页，查看地址，例如https://saoju.net/review/3189，则评论ID为3189。</p>
                    <p>同时需输入评论时间进行校验，例如2016-08-28 20:18:44，以防输错评论ID。</p>
                    <p>屏蔽成功后会跳转到首页，否则会提示屏蔽失败。</p><br/>
                    <div class="form-group">
                        <label class="col-md-2 control-label">评论ID</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="review_id" required="required" value="{{ old('review_id') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">评论时间</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="created_at" required="required" value="{{ old('created_at') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">屏蔽理由</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="reason" required="required" value="{{ old('reason') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-2">
                            <button type="submit" name="submit" class="btn btn-warning">屏蔽评论</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
