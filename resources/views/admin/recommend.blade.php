@extends('app')

@section('title', '推荐页 - 管理页 - ')

@section('content')
    <div class="container">
        <h4 class="text-success">后台管理页</h4>
        <br/>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <caption>剧集推荐列表</caption>
                    <thead>
                    <tr>
                        <th>剧名</th>
                        <th>人数</th>
                        <th>平均分</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($favorites as $favorite)
                        <tr>
                            <td>《<a href="{{ url('/drama/'.$favorite->drama_id) }}" target="_blank">{{ $favorite->drama->title }}</a>》（{{ $favorite->drama->sc }}）</td>
                            <td>{{ $favorite->count }}</td>
                            <td>{{ $favorite->average }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <caption>分集推荐列表</caption>
                    <thead>
                    <tr>
                        <th>剧名</th>
                        <th>人数</th>
                        <th>平均分</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($epfavs as $favorite)
                        <tr>
                            <td>《<a href="{{ url('/drama/'.$favorite->episode->drama_id) }}" target="_blank">{{ $favorite->episode->drama->title }}</a>》<a href="{{ url('/episode/'.$favorite->episode_id) }}" target="_blank">{{ $favorite->episode->title }}</a>（{{ $favorite->episode->drama->sc }}）</td>
                            <td>{{ $favorite->count }}</td>
                            <td>{{ $favorite->average }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
