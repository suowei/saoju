<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">收藏《{{ $drama->title }}》{{ $episode->title }}</h4>
</div>
<div class="modal-body">
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

    <form class="form-horizontal favorite-form" role="form" method="POST" action="{{ url('/epfav') }}">
        {!! csrf_field() !!}
        <input type="hidden" name="episode_id" value="{{ $episode->id }}">

        <div class="form-group">
            <label class="col-md-2 control-label">状态：</label>
            <label class="radio-inline">
                <input type="radio" name="type" value="0"><span class="btn btn-primary btn-xs">想听</span>
            </label>
            <label class="radio-inline">
                <input type="radio" name="type" value="1" checked><span class="btn btn-success btn-xs">听过</span>
            </label>
            <label class="radio-inline">
                <input type="radio" name="type" value="2"><span class="btn btn-info btn-xs">抛弃</span>
            </label>
        </div>
        <div class="form-group" id="ratingSelect">
            <label class="col-md-2 control-label">评分：</label>
            <div class="col-md-10">
                <input type="number" class="rating form-control" name="rating" min=0 max=5 step=0.5 data-size="xxs">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                <button type="submit" class="btn btn-info btn-sm">保存</button>
            </div>
        </div>
    </form>
</div>

