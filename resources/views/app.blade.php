<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
	<title>中抓扫剧</title>

	<link href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    @yield('css')
    <link href="{{ asset('/css/site.css') }}" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="http://apps.bdimg.com/libs/html5shiv/3.7/html5shiv.min.js"></script>
		<script src="http://apps.bdimg.com/libs/respond.js/1.4.2/respond.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{ url('/') }}"><strong>中抓扫剧</strong></a>
			</div>

			<div class="navbar-collapse collapse" id="navbar">
                @if (Auth::check())
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/user/'. Auth::id()) }}"><span class="glyphicon glyphicon-home"></span> 我的主页</a></li>
                </ul>
                @endif

                <form class="navbar-form navbar-left" role="search" method="GET" action="{{ url('/search') }}">
                    <div class="form-group">
                        <input type="text" class="form-control" id="navbarSearch" name="search" placeholder="剧名或别名">
                    </div>
                    <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-search"></span> 搜索</button>
                </form>

				<ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ url('/drama') }}"><span class="glyphicon glyphicon-film"></span> 剧集列表</a></li>
                    <li><a href="{{ url('/review') }}"><span class="glyphicon glyphicon-comment"></span> 评论列表</a></li>
                    <li><a href="{{ url('/drama/create') }}"><span class="glyphicon glyphicon-plus"></span> 添加剧集信息</a></li>

					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}"><span class="glyphicon glyphicon-log-in"></span> 登录</a></li>
						<li><a href="{{ url('/auth/register') }}"><span class="glyphicon glyphicon-list-alt"></span> 注册</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-user"></span>
                                {{ Auth::user()->name }}
                                <span class="caret"></span>
                            </a>
							<ul class="dropdown-menu" role="menu">
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
                <div class="col-md-6">
                    <p>
                        <span class="glyphicon glyphicon-heart-empty"></span>
                        创意来自<a href="http://www.douban.com" target="_blank">豆瓣</a>&<a href="http://saowen.net" target="_blank">扫文小院</a>
                        ©2015 saoju.net
                    </p>
                </div>
                <div class="col-md-6 text-right">
                    <img src="http://weibo.com/favicon.ico" height="14px">
                    <a href="http://weibo.com/u/5634790755" target="_blank">微博</a>&nbsp;
                    <img src="http://bbs.jjwxc.net/favicon.ico" height="14px">
                    <a href="http://bbs.jjwxc.net/board.php?board=52&page=1" target="_blank">优声由色</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
	<script src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="http://apps.bdimg.com/libs/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    @yield('script')
    <script src="{{ asset('/js/site.js') }}"></script>
</body>
</html>
