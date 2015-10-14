@extends('app')

@section('title', '编辑'.$sc->name.'的信息 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">编辑{{ $sc->name }}的信息</div>
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

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/sc/'.$sc->id) }}" onsubmit="this.submit.disabled=true;this.submit.innerHTML='处理中';">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-2 control-label">ID</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="name" value="{{ $sc->name }}" required="required">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">马甲或昵称</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="alias" value="{{ $sc->alias }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">社团</label>
                                <div class="col-md-9">
                                    <input type="text" class="typeahead form-control" name="club" placeholder="搜索社团、工作室" value="{{ $sc->club->name or '' }}">
                                    &nbsp;如未搜到，可先不填留待以后修改，或先<a href="{{ url('/club') }}" target="_blank">添加</a>再填写。
                                </div>
                            </div>

                            <div class="form-group" id="jobs">
                                <label class="col-md-2 control-label">职位</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="jobs" value="{{ $sc->jobs }}" placeholder="点击下面的复选框选择职位，点击顺序影响显示顺序，请先点击该SC主要担任职位" readonly>
                                </div>
                                <div class="col-md-10 col-md-offset-2">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="job[]" value="0" @if(isset($job[0])) checked @endif> CV
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="job[]" value="1" @if(isset($job[1])) checked @endif> 策划
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="job[]" value="2" @if(isset($job[2])) checked @endif> 导演
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="job[]" value="3" @if(isset($job[3])) checked @endif> 编剧
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="job[]" value="4" @if(isset($job[4])) checked @endif> 后期
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="job[]" value="5" @if(isset($job[5])) checked @endif> 美工
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="job[]" value="6" @if(isset($job[6])) checked @endif> 宣传
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="job[]" value="7" @if(isset($job[7])) checked @endif> 填词
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="job[]" value="8" @if(isset($job[8])) checked @endif> 歌后
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="job[]" value="9" @if(isset($job[9])) checked @endif> 作者
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="job[]" value="10" @if(isset($job[10])) checked @endif> 歌手
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="job[]" value="11" @if(isset($job[11])) checked @endif> 其他
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">SC信息</label>
                                <div class="col-md-9">
                                    <h5>直接回车将另起一段，新段落与上一段落有间距，如只想另起一行，即与上一行无间距，请输入Shift+回车。</h5>
                                    <textarea class="form-control" id="richtext" name="information" rows="15">{{ $sc->information }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-2">
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

@section('script')
    <script src="http://cdn.bootcss.com/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var clubs = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: '/club/search?q=%QUERY',
                    wildcard: '%QUERY'
                }
            });
            $('.typeahead').typeahead({
                hint: false
            }, {
                name: 'clubs',
                display: 'name',
                limit: 10,
                source: clubs
            });
        });
    </script>
    <script src="http://cdn.bootcss.com/tinymce/4.2.2/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: "#richtext",
            plugins: [
                "autosave link image lists hr code fullscreen table textcolor colorpicker preview"
            ],
            table_default_attributes: {
                border: 1
            },
            toolbar1: "bold italic underline removeformat | fontselect | fontsizeselect | forecolor backcolor | alignleft aligncenter alignright alignjustify",
            toolbar2: "formatselect | indent outdent blockquote | table | bullist numlist | link image hr | preview fullscreen code",
            menubar: false,
            font_formats: "宋体=宋体;微软雅黑=微软雅黑;楷体=楷体;黑体=黑体;隶书=隶书;"+
            "Arial=Arial;Arial Black=Arial Black;Comic Sans MS=Comic Sans MS;Courier New=Courier New;"+
            "Helvetica=Helvetica;Impact=Impact;Tahoma=Tahoma;Times New Roman=Times New Roman;Verdana=Verdana",
            language_url: 'http://cdn.bootcss.com/tinymce/4.2.0/langs/zh_CN.js',
            content_css: ['http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css', '/css/content.css']
        });
    </script>
@endsection
