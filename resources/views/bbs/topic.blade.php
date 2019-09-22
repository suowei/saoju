@extends('app')

@section('title', $topic->title.' - ')

@section('content')
<div class="container">
    <p><a href="{{ url('/bbs') }}"><span class="glyphicon glyphicon-send"></span> 返回留言板</a></p>
    <h4>{{ $topic->title }}</h4>
    <div class="review-title">
        @if($topic->user_id)
            <a href="{{ url('/user/'.$topic->user_id) }}" target="_blank">{{ $topic->user->name }}</a>
        @else
            {{ preg_replace('/((?:\d+\.){3})\d+/', "\\1*", $topic->ip) }}
        @endif
        <span class="pull-right">{{ $topic->created_at }}</span>
    </div>
    <div class="review-show-content">{{ $topic->content }}</div>
    <h4 class="text-success">回帖<small>（{{ $comments->total() }}）</small></h4>
    @foreach($comments as $key => $comment)
        <div class="review">
            <div class="review-title">
                {{ $key+1+($comments->currentPage()-1)*$comments->perPage() }}楼
                @if($comment->user_id)
                    <a href="{{ url('/user/'.$comment->user_id) }}" target="_blank">{{ $comment->user->name }}</a>
                @else
                    {{ preg_replace('/((?:\d+\.){3})\d+/', "\\1*", $comment->ip) }}
                @endif
                <span class="pull-right">{{ $comment->created_at }}</span>
            </div>
            @if($comment->deleted_at)
                <p class="text-muted">广告等已删除</p>
            @else
                <div class="review-content">{{ $comment->content }}</div>
            @endif
        </div>
    @endforeach
    <?php echo $comments->render(); ?>

    <h4 class="text-success">发表回帖</h4>
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
    <form method="POST" action="{{ url('/bbs/comment') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="topic_id" value="{{ $topic->id }}">
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
        <input type="hidden" name="ip" value="{{ Request::ip() }}">

        <div class="form-group">
            <textarea class="form-control" name="content" rows="5" required="required">{{ old('content') }}</textarea>
        </div>

        <button type="submit" class="btn btn-info">提交</button>
    </form>
    @endif
</div>
@endsection
