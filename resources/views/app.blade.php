<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
	<title>@yield('title')抓糖</title>
    @yield('meta')

	<link href="https://lib.baomitu.com/twitter-bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    @yield('css')
    <link href="{{ asset('/css/site.css') }}" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="//apps.bdimg.com/libs/html5shiv/3.7/html5shiv.min.js"></script>
		<script src="//apps.bdimg.com/libs/respond.js/1.4.2/respond.js"></script>
	<![endif]-->

    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "//hm.baidu.com/hm.js?23831bd1561bb8071317c8bf3e4985c2";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{ url('/') }}"><strong>抓糖</strong></a>
			</div>

			<div class="navbar-collapse collapse" id="navbar">
                @if (Auth::check())
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/user/'. Auth::id()) }}"><span class="glyphicon glyphicon-home"></span> 我的主页</a></li>
                </ul>
                @endif

                <form class="navbar-form navbar-left" role="search" method="GET" action="{{ url('/search') }}">
                    <div class="input-group">
                        <input id="navbarSearch" type="text" class="form-control" name="keyword" placeholder="剧名或别名、SC、社团">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-search"></span></button>
                        </span>
                    </div>
                </form>

				<ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ url('/drama?type=0') }}"><span class="glyphicon glyphicon-film"></span> 剧集列表</a></li>
                    <li><a href="{{ url('/episode?type=0') }}"><span class="glyphicon glyphicon-facetime-video"></span> 分集列表</a></li>
                    <li><a href="{{ url('/review') }}"><span class="glyphicon glyphicon-comment"></span> 评论列表</a></li>
                    <li><a href="{{ url('/sc') }}"><span class="glyphicon glyphicon-camera"></span> SC列表</a></li>
                    <li><a href="{{ url('/club') }}"><span class="glyphicon glyphicon-ice-lolly"></span> 社团列表</a></li>

					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}"><span class="glyphicon glyphicon-log-in"></span> 登录</a></li>
						<li><a href="{{ url('/auth/register') }}"><span class="glyphicon glyphicon-list-alt"></span> 注册</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" title="{{ $name = Auth::user()->name }}">
                                <span class="glyphicon glyphicon-user"></span>
                                {{ mb_strlen($name) > 4 ? mb_substr($name, 0, 4).'...' : $name}}
                                <span class="caret"></span>
                            </a>
							<ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/user/'.Auth::id().'/epfavs/0') }}"><span class="glyphicon glyphicon-step-forward"></span> 待听分集</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ url('/user/dramafeed') }}"><span class="glyphicon glyphicon-film"></span> 在追剧集更新</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ url('/user/listfavs') }}"><span class="glyphicon glyphicon-gift"></span> 收藏剧单</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ url('/user/edit') }}"><span class="glyphicon glyphicon-cog"></span> 修改信息</a></li>
                                <li class="divider"></li>
								<li><a href="{{ url('/auth/logout') }}"><span class="glyphicon glyphicon-log-out"></span> 退出</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')

    <div id="toTop">↑返回顶部</div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <p>
                        <span class="glyphicon glyphicon-heart-empty"></span>
                        创意来自<a href="http://www.douban.com" target="_blank">豆瓣</a>&<a href="http://saowen.net" target="_blank">扫文小院</a>
                        ©2015-2019 saoju.net&nbsp;&nbsp;&nbsp;&nbsp;邮箱：dramatown@saoju.net
                    </p>
                </div>
                <div class="col-md-3 text-right">
                    <img src="{{ asset('/img/jjwxc.ico') }}" height="14px">
                    <a href="http://bbs.jjwxc.net/board.php?board=52&page=1" target="_blank">优声由色</a>
                    <a href="http://bbs.jjwxc.net/board.php?board=44&page=1" target="_blank">留声花园</a>
                    @yield('link')
                </div>
            </div>
        </div>
    </footer>

	<script src="//apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="//apps.bdimg.com/libs/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    @yield('script')
    <script src="{{ asset('/js/site.js') }}"></script>
</body>
</html>
