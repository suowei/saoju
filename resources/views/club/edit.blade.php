@extends('app')

@section('title', '编辑'.$club->name.'信息 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">编辑{{ $club->name }}信息</div>
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

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/club/'.$club->id) }}" onsubmit="this.submit.disabled=true;this.submit.innerHTML='处理中';">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-2 control-label">社团名称</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="name" value="{{ $club->name }}" required="required">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">社团信息</label>
                                <div class="col-md-9">
                                    <h5>直接回车将另起一段，新段落与上一段落有间距，如只想另起一行，即与上一行无间距，请输入Shift+回车。</h5>
                                    <textarea class="form-control" id="richtext" name="information" rows="15">{{ $club->information }}</textarea>
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
