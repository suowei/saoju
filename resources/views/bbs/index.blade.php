@extends('app')

@section('title', '留言板 - ')

@section('content')
<div class="container">
    <h4 class="text-success">留言板</h4>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th class="text-warning col-md-1"><span class="glyphicon glyphicon-comment"></span> 回复</th>
                <th class="text-danger col-md-7"><span class="glyphicon glyphicon-bullhorn"></span> 标题</th>
                <th class="text-info col-md-2"><span class="glyphicon glyphicon-sunglasses"></span> 作者</th>
                <th class="text-primary col-md-2"><span class="glyphicon glyphicon-time"></span> 最后回复</th>
            </tr>
            </thead>
            <tbody>
            @foreach($topics as $topic)
                <tr>
                    <td >{{ $topic->comments }}</td>
                    <td><a href="{{ url('/bbs/topic/'.$topic->id) }}" target="_blank">{{ $topic->title }}</a></td>
                    <td>
                        @if($topic->user_id)
                            <a href="{{ url('/user/'.$topic->user_id) }}" target="_blank">{{ $topic->user->name }}</a>
                        @else
                            {{ preg_replace('/((?:\d+\.){3})\d+/', "\\1*", $topic->ip) }}
                        @endif
                    </td>
                    <td>{{ $topic->updated_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <?php echo $topics->render(); ?>

    <h4 class="text-success">发表新帖</h4>
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

    @if(Auth::check())
    <form method="POST" action="{{ url('/bbs/topic') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
        <input type="hidden" name="ip" value="{{ Request::ip() }}">

        <div class="form-group">
            <input type="text" class="form-control" name="title" required="required" placeholder="请填写标题" value="{{ old('title') }}">
        </div>

        <div class="form-group">
            <textarea class="form-control" name="content" rows="5" required="required">{{ old('content') }}</textarea>
        </div>

        <button type="submit" class="btn btn-info">提交</button>
    </form>
    @endif
</div>
@endsection
