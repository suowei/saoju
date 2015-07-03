@extends('app')

@section('title', '我的待听列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    @if($type == 0)
                        <span class="label label-primary">待听列表</span>
                    @else
                        <a href="{{ url('/playlist?type=0') }}">待听列表</a>
                    @endif&nbsp;
                    @if($type == 1)
                            <span class="label label-primary">已听列表</span>
                    @else
                        <a href="{{ url('/playlist?type=1') }}">已听列表</a>
                    @endif
                </p>
                @foreach($playlists as $playlist)
                    <div class="drama">
                        <p>
                            《<a href="{{ url('/drama/'.$playlist->episode->drama_id) }}" target="_blank">{{ $playlist->episode->drama_title }}</a>》<a href="{{ url('/episode/'.$playlist->episode->id) }}" target="_blank">{{ $playlist->episode->episode_title }}</a>
                            @if($type == 0)
                                <a class="text-muted" href="{{ url('/playlist/'.$playlist->episode->id.'/edit') }}">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </a>
                                <a class="text-muted" href="{{ url('/playlist/'.$playlist->episode->id.'/edit') }}">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </a>
                            @endif
                        </p>
                        <a href="{{ $playlist->episode->url }}" target="_blank">{{ $playlist->episode->url }}</a>
                    </div>
                @endforeach
                <?php echo $playlists->appends(['type' => $type])->render(); ?>
            </div>
        </div>
    </div>
@endsection
