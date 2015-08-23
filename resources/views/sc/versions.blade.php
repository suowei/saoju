@extends('app')

@section('title', $sc->name.'版本列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3><a href="{{ url('/sc/'.$sc->id) }}">{{ $sc->name }}</a>的版本列表</h3>
                @foreach ($versions as $version)
                    <div class="review">
                        <div class="review-title">
                            <a href="{{ url('/user/'.$version->user_id) }}" target="_blank">{{ $version->user->name }}</a>
                            @if($version->first)（创建者或本功能上线前的最后编辑者）@endif
                            此版本创建于{{ $version->created_at }}
                            更新于{{ $version->updated_at }}
                        </div>
                        <div class="version">
                            <span class="text-muted">ID：</span>{{ $version->name }}<br>
                            <span class="text-muted">马甲及昵称：</span>{{ $version->alias }}<br>
                            <span class="text-muted">社团或工作室：</span>@if($version->club_id)<a
                                    href="{{ url('/club/'.$version->club_id) }}"
                                    target="_blank">{{ $version->club->name }}</a>@endif<br>
                            <span class="text-muted">职位：</span>{{ $version->jobs }}<br>
                            <span class="text-muted">信息：</span>{{ $version->information }}
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-3">
                <p>2015年8月23日，版本列表功能上线，但由于之前的数据只记录了最后一次编辑者而未记录创建者，所以将之前的版本都合并到此日期之前的最后编辑者版本中。</p>
            </div>
        </div>
    </div>
@endsection
