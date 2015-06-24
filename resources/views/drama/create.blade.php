@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">添加剧集信息</div>
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
                        1. 连载剧更新请前往相应剧集页面“更新剧集”。<br>
                        2. 此条目为广播剧基本信息，不包含分集内容，别忘记在剧集添加完成后再通过“添加分集”加入全一期、第一期、第二期等分集哦~<br>
                        3. 请注意剧组版权声明中关于转载部分的内容。<br>
                        <span class="text-danger">4. 添加前请先搜索，以免重复添加^ ^</span>
                    </p><br>

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/drama') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">剧名</label>
                            <div class="col-md-6">
                                <input type="text" id="title" class="form-control" name="title" required="required" placeholder="必填" value="{{ old('title') }}">
                                <div id="dramas"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">副标题或别名</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="alias" value="{{ old('alias') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">性向</label>
                            <div class="col-md-6">
                                <label class="radio-inline">
                                    <input type="radio" name="type" value="0" checked> 耽美
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="type" value="1" @if(old('type') == 1) checked @endif> 全年龄
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="type" value="2" @if(old('type') == 2) checked @endif> 言情
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="type" value="3" @if(old('type') == 3) checked @endif> 百合
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">时代</label>
                            <div class="col-md-6">
                                <label class="radio-inline">
                                    <input type="radio" name="era" value="0" checked> 现代
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="era" value="1" @if(old('era') == 1) checked @endif> 古风
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="era" value="2" @if(old('era') == 2) checked @endif> 民国
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="era" value="3" @if(old('era') == 3) checked @endif> 未来
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="era" value="4" @if(old('era') == 4) checked @endif> 其他
                                </label>
                                <h5>说明：如果是日风、欧风剧等不清楚时代的，可以通通放到其他里^ ^</h5>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">其他描述</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="genre" value="{{ old('genre') }}" placeholder="可不填">
                                <h5>说明：标签系统上线前的暂时替代，如科幻、悬疑、都市、刑侦、乡村、强强、谍战等</h5>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">原创性</label>
                            <div class="col-md-6">
                                <label class="radio-inline">
                                    <input type="radio" name="original" id="adapted" value="0" checked> 改编
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="original" id="original" value="1" @if(old('original') == 1) checked @endif> 原创
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">期数</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="count" placeholder="必填，只允许填入数字" value="{{ old('count') }}" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">进度</label>
                            <div class="col-md-6">
                                <label class="radio-inline">
                                    <input type="radio" name="state" id="tbc" value="0" checked> 连载
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="state" id="done" value="1" @if(old('state') == 1) checked @endif> 完结
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="state" id="keng" value="2" @if(old('state') == 2) checked @endif> 已坑
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">主役CV</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="必填" name="sc" value="{{ old('sc') }}" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">海报地址</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="poster_url" value="{{ old('poster_url') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">剧情简介</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="introduction" rows="5">{{ old('introduction') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
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
