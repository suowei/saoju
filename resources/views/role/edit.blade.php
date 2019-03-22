@extends('app')

@section('title', '修改《'.$drama->title.'》'.$episode->title.'关联SC - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">修改《{{ $drama->title }}》{{ $episode->title }}关联SC</div>
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

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/role/'.$role->id) }}" onsubmit="this.submit.disabled=true;">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-md-2 control-label">SC</label>
                                <div class="col-md-9">
                                    <input type="text" class="typeahead form-control" name="sc" required="required" placeholder="搜索SC" value="{{ $sc->name }}">
                                    &nbsp;如未搜到，请先<a href="{{ url('/sc') }}" target="_blank">添加</a>再关联。
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">职位</label>
                                <div class="col-md-10">
                                    <h5>注：以下职位为单选，如该SC在本剧中担任多个职位，请添加多次关联。</h5>
                                    <label class="radio-inline">
                                        <input type="radio" name="job" value="0" checked> 原著
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="job" value="1" @if($role->job == 1) checked @endif> 策划
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="job" value="2" @if($role->job == 2) checked @endif> 导演
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="job" value="3" @if($role->job == 3) checked @endif> 编剧
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="job" value="4" @if($role->job == 4) checked @endif> 后期
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="job" value="5" @if($role->job == 5) checked @endif> 美工
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="job" value="6" @if($role->job == 6) checked @endif> 宣传
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="job" value="7" @if($role->job == 7) checked @endif> 填词
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="job" value="8" @if($role->job == 8) checked @endif> 翻唱
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="job" value="9" @if($role->job == 9) checked @endif> 歌曲后期
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="job" value="10" @if($role->job == 10) checked @endif> 其他staff
                                    </label>
                                    <br>
                                    <label class="radio-inline">
                                        <input type="radio" name="job" value="11" @if($role->job == 11) checked @endif> 主役
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="job" value="12" @if($role->job == 12) checked @endif> 协役
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="job" value="13" @if($role->job == 13) checked @endif> 龙套
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">备注</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="note" placeholder="角色名、其他staff名称等" value="{{ $role->note }}">
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
    <script src="https://lib.baomitu.com/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var scs = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: '/sc/search?q=%QUERY',
                    wildcard: '%QUERY'
                }
            });
            $('.typeahead').typeahead({
                hint: false
            }, {
                name: 'scs',
                display: 'name',
                limit: 10,
                source: scs
            });
        });
    </script>
@endsection
