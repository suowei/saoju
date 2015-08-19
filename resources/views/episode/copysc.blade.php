@extends('app')

@section('title', '复制关联SC - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">将《{{ $drama->title }}》{{ $src->title }}关联SC复制到{{ $episode->title }}</div>
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

                            <button id="selectAll" type="button" class="btn btn-default btn-xs">全选</button>
                            <button id="unSelect" type="button" class="btn btn-default btn-xs">全不选</button>

                        <form method="POST" action="{{ url('/episode/'.$episode->id.'/copysc') }}" onsubmit="this.submit.disabled=true;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="drama_id" value="{{ $episode->drama_id }}">

                            <?php $jobs = ['原著', '策划', '导演', '编剧', '后期', '美工', '宣传', '填词', '翻唱', '歌曲后期', '其他staff', '主役', '协役', '龙套']; ?>
                            @foreach ($roles as $key => $role)
                                <input type="hidden" name="sc[{{ $key }}]" value="{{ $role->sc_id }}">
                                <input type="hidden" name="job[{{ $key }}]" value="{{ $role->job }}">
                                <input type="hidden" name="note[{{ $key }}]" value="{{ $role->note }}">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="role[]" value="{{ $key }}" checked>
                                        {{ $jobs[$role->job] }}
                                        {{ $role->note ? '（'.$role->note.'）' : '' }}：
                                        {{ $role->sc->name }}
                                    </label>
                                </div>
                            @endforeach

                            <button type="submit" class="btn btn-primary">
                                提交
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
