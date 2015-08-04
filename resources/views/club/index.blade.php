@extends('app')

@section('title', '社团列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="drama">
                    排序：&nbsp;&nbsp;
                    <?php
                    $url = url('/club?');
                    foreach($params as $key => $value)
                    {
                        if($key != 'sort' && $key != 'order')
                            $url .= $key.'='.$value.'&';
                    }
                    ?>
                    @if($params['sort'] == 'id')
                        <strong>
                            @if($params['order'] == 'asc')
                                <a href="{{ $url.'sort=id&order=desc' }}">添加顺序<span class="glyphicon glyphicon-arrow-up order-select"></span></a>
                            @else
                                <a href="{{ $url.'sort=id&order=asc' }}">添加顺序<span class="glyphicon glyphicon-arrow-down order-select"></span></a>
                            @endif
                        </strong>
                    @else
                        <a href="{{ $url.'sort=id&order=desc' }}">添加顺序<span class="glyphicon glyphicon-arrow-down order"></span></a>
                    @endif&nbsp;&nbsp;
                    @if($params['sort'] == 'reviews')
                        <strong>
                            @if($params['order'] == 'asc')
                                <a href="{{ $url.'sort=reviews&order=desc' }}">印象数量<span class="glyphicon glyphicon-arrow-up order-select"></span></a>
                            @else
                                <a href="{{ $url.'sort=reviews&order=asc' }}">印象数量<span class="glyphicon glyphicon-arrow-down order-select"></span></a>
                            @endif
                        </strong>
                    @else
                        <a href="{{ $url.'sort=reviews&order=desc' }}">印象数量<span class="glyphicon glyphicon-arrow-down order"></span></a>
                    @endif
                    <span class="pull-right">
                        共{{ $clubs->total() }}个社团和工作室
                    </span>
                </div>
                <p><br>
                    @foreach($clubs as $club)
                        <a href="{{ url('/club/'.$club->id) }}" target="_blank">{{ $club->name }}</a>&nbsp;
                    @endforeach
                </p>
                <?php echo $clubs->appends($params)->render(); ?>
            </div>
            <div class="col-md-3">
                <wb:share-button appkey="125628789" addition="number" type="button"></wb:share-button>
                <p>
                    <a class="btn btn-primary btn-xs" href="{{ url('/club/create') }}" target="_blank">
                        <span class="glyphicon glyphicon-plus"></span> 添加社团或工作室信息
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
