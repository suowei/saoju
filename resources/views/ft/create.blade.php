@extends('appzb')

@section('title', '添加节目 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">添加节目信息</div>
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

                        <p>
                            说明：<br>
                            1. 节目更新请前往相应节目页面“添加节目分集”。<br>
                            2. 此条目为节目基本信息，不包含分集内容，别忘记在节目添加完成后再通过“添加分集”加入各期节目分集哦~<br>
                            3. 请注意剧组版权声明中关于转载部分的内容。<br>
                            <span class="text-danger">4. 添加前请先搜索，以免重复添加^ ^</span>
                        </p><br>

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/ft') }}" onsubmit="this.submit.disabled=true;this.submit.innerHTML='处理中';">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-4 control-label">节目名</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="title" required="required" placeholder="必填" value="{{ old('title') }}">
                                    <div id="dramas"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">主持人</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="host" value="{{ old('host') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">海报地址</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="poster_url" value="{{ old('poster_url') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">节目介绍</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="introduction" rows="10">{{ old('introduction') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" name="submit" class="btn btn-primary">
                                        提交
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
