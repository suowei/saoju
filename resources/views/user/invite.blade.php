@extends('app')

@section('title', '邀请朋友 - ')

@section('content')
    <div class="container">
        <p>
            请注意：邀请码仅限一人使用，注册成功后即作废。请将编号和暗号都发送给朋友~
        </p><br>
        @forelse($invitations as $invitation)
            <p>
                您已获得邀请码一枚，编号为<strong>{{ $invitation->id }}</strong>
            </p>
            @if($invitation->code)
                暗号为<strong>{{ $invitation->code }}</strong>
            @endif
            @if($invitation->new_user_id)
                此邀请码已被使用<br>
            @else
                <form class="form-inline" method="POST" action="{{ url('/user/updateCode') }}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <input type="text" class="form-control" name="code" placeholder="请设置或重设暗号">
                    </div>
                    <button type="submit" class="btn btn-default">设置暗号</button>
                </form>
            @endif<br><br>
        @empty
            抱歉，您当前没有邀请码，请关注下次邀请码发放时间^ ^
        @endforelse
    </div>
@endsection