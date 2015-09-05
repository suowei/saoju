@extends('app')

@section('title', $drama->title.'的收录剧单列表 - ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h4 class="text-success">
                    <a href="{{ url('/drama/'.$drama->id) }}" target="_blank">{{ $drama->title }}</a>的收录剧单列表
                </h4>
                @foreach($lists as $key => $list)
                    <div class="drama">
                        <a href="{{ url('/list/'.$list->id) }}" target="_blank">{{ $list->title }}</a>
                        创建者：<a href="{{ url('/user/'.$list->user_id) }}" target="_blank">{{ $list->user->name }}</a>
                        收录于：{{ $items[$key]->created_at }}
                    </div>
                @endforeach
                <?php echo $items->render(); ?>
            </div>
        </div>
    </div>
@endsection
