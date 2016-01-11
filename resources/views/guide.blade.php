@extends('app')

@section('title', '用户手册 - ')

@section('content')
    <div class="container">
        <h2>抓糖用户手册</h2>
        <p>欢迎大家加入抓糖！<br>希望这个手册能让大家了解抓糖的基本功能，祝大家在抓糖玩得愉快！</p>
        <h3>Get Start</h3>
        <p>
            这个用户手册由四个部分组成：对抓糖的介绍、抓糖入门引导、抓糖功能简要展示，和抓糖特色功能推介。具体可参见下面的目录<span class="text-info">ヾ(^▽^*)))</span>
        </p>
        <ul>
            <li><a href="#jianjie">抓糖简介</a></li>
            <li><a href="#renshi">认识抓糖</a></li>
            <li><a href="#zhuce">抓糖入门之注册登录那点事</a></li>
            <li><a href="#juji">抓糖入门之添加剧集那点事</a></li>
            <li><a href="#pinglun">抓糖入门之收藏评论那点事</a></li>
            <li><a href="#sc">抓糖入门之SC社团那点事</a></li>
            <li><a href="#judan">抓糖功能之剧单</a></li>
            <li><a href="#shaixuan">抓糖功能之筛选</a></li>
            <li><a href="#zhoubian">抓糖功能之周边板块</a></li>
            <li><a href="#xingxiang">抓糖特色之性向切换</a></li>
            <li><a href="#gongju">抓糖特色之小工具</a></li>
        </ul>
        <h3 id="jianjie">抓糖简介</h3>
        <p>
            抓糖成立于2015年6月，名称取自英文Drama Town的谐音，旨在为中抓听众提供剧集信息汇总和听剧分享平台。截至2015年12月31日，网站已有注册用户253位；共收录广播剧2123部，分集3575期；SC信息1673位，社团信息292个；SC剧集关联信息6249条。网站用户共撰写广播剧评论1867篇，剧集收藏记录6665条，分集收藏记录794条，标签124个。我们的官方微博为<ins>@抓糖DramaTown</ins> 。
        </p>
        <h3 id="renshi">认识抓糖</h3>
        <p>首页是一个网站的门面担当，我们先从抓糖的首页开始，一一介绍抓糖的主要功能吧！</p>
        <p>请键入<ins class="text-info">saoju.net</ins> ➷➷</p>
        <p><img src="http://ww4.sinaimg.cn/large/0069l0tRgw1ezvajopxqwj31bq0s7k6l.jpg" class="img-responsive"></p>
        <p>首页的版面分为六个区，详细如下：</p>
        <ul>
            <li><strong>导航栏</strong>
                <ul>
                    <li><span style="color:#573e7d">网站版头“抓糖”</span><br>任何时候点击该版头即可回到首页</li>
                    <li><span style="color:#573e7d">综合搜索</span><br>结果将展示包含关键词的剧集、 SC 和社团名称</li>
                    <li><span style="color:#573e7d">剧集相关列表入口</span><br>剧集列表以一整部剧为单位，分集列表以一部剧的每一集为单位对剧集进行陈列；评论列表以用户发表评论的时间先后倒序排列，方便用户通过评价选择剧集；SC列表和社团列表以制作组人员为中心进行展示，我们鼓励用户多多添加呀！</li>
                    <li><span style="color:#573e7d">用户相关</span><br>在登录之后，原有的“登录”、“注册”按钮会被替换成用户名</li>
                </ul>
            </li>
            <li><strong>新剧展示区</strong><br>一周的新剧以日期区分，分7个Tab展示在首页的中间部分，鼠标移动到Tab头区域即可查看对应日期的新剧；右方有海报相册，点击左侧或右侧可进行切换</li>
            <li><strong>附加功能区</strong>
                <ul>
                    <li><span style="color:#573e7d">添加剧集信息</span><br>这是抓糖的核心功能<span class="text-info">^。^</span>后面会有详细介绍</li>
                    <li><span style="color:#573e7d">SC社团印象</span><br>表白可以来这里呀<span class="text-info">\(^o^)/</span></li>
                    <li><span style="color:#573e7d">标签搜索</span><br>根据用户在剧集上打上的标签进行的搜索，比普通搜索更有趣<span class="text-info">☆⌒(*＾-゜)v</span></li>
                    <li><span style="color:#573e7d">周边板块</span><br>剧情歌、ED、FT、电台节目都到碗里来<span class="text-info">o(〃'▽'〃)o</span></li>
                </ul>
            </li>
            <li><strong>公告留言区</strong><br>网站抓虫可进，建议也可以到留言板发言</li>
            <li><strong>评论展示区</strong><br>展示用户最新发表的20条评论</li>
            <li><strong>剧单、剧集陈列区</strong><br>分为最新剧单、热门剧单、月热评剧集、月热门剧集和剧集添加感谢栏<span class="text-info"><(￣︶￣)↗</span></li>
        </ul>
        <h3 id="zhuce">抓糖入门之注册登录那点事</h3>
        <p>抓糖目前实施的邀请注册制，想加入的中抓er可以向注册用户索取，或者发微博并@抓糖DramaTown 等待别的用户分享。拿到邀请码之后就可以进入注册页面啦<span class="text-info">(＾－＾)V</span></p>
        <p><img src="http://ww3.sinaimg.cn/large/0069l0tRjw1ezvb5djmsxj30kv0bm3z1.jpg" class="img-responsive"></p>
        <p>请注意：邀请码和暗号是配对使用的，注册的时候要小心哦！</p>
        <p>注册完成后就可以立刻登录，导航栏上会多出“我的主页”按钮，点进去看一下！</p>
        <p><img src="http://ww2.sinaimg.cn/large/0069l0tRjw1ezvb7rqzi4j30xr0qawmu.jpg" class="img-responsive"></p>
        <p>基本上，所有跟用户相关的功能都会展示在个人主页里，比如收藏、剧单、标签、评论、印象……用户可以通过主页对自己的相关条目进行修改，还可以导出自己的评论（参见截图右下角按钮），很方便哒！</p>
        <p>另外，导航栏上的用户名也带有一个快捷菜单，用户可以在任意界面点击进入。</p>
        <p><img src="http://ww1.sinaimg.cn/large/0069l0tRjw1ezvbaje0tlj308y08o0tn.jpg" class="img-responsive"></p>
        <h3 id="juji">抓糖入门之添加剧集那点事</h3>
        <p>在抓糖添加剧集分两种情况：<strong>添加新剧</strong>和<strong>更新分集</strong>。</p>
        <p>添加新剧可在首页通过<img src="http://ww4.sinaimg.cn/large/0069l0tRjw1ezvbe6opfhj303500s0sk.jpg">按钮进入，页面如下：</p>
        <p><img src="http://ww2.sinaimg.cn/large/0069l0tRjw1ezvbfbtllqj30ku0qstci.jpg" class="img-responsive"></p>
        <p>一般来说，填写的时候对照着页面里的注意事项就没问题了。值得注意的是我们在剧名栏加入了一个预设的剧集同名搜索，方便用户在添加的时候确认信息。举个例子<img src="http://ww3.sinaimg.cn/large/0069l0tRjw1ezvbgw6cvij302w02y745.jpg" height="16px"></p>
        <p><img src="http://ww2.sinaimg.cn/large/0069l0tRjw1ezvbiy3yenj30dm06u758.jpg" class="img-responsive"></p>
        <p>是不是很方便呢？<span class="text-info"><(￣︶￣)></span></p>
        <p>如果想要更新分集，请首先进入剧集页面，点击右方的<img src="http://ww1.sinaimg.cn/large/0069l0tRjw1ezvboucjuuj304i00r745.jpg"></p>
        <p>添加分集的页面跟添加剧集是很相似的，不过需要填写的信息少了很多，只有：分集标题（必填）、副标题、发布地址、海报地址、SC表（必填）、发布日期（必填）、本集简介。详细的截图就不上啦XD</p>
        <h3 id="pinglun">抓糖入门之收藏评论那点事</h3>
        <p>剧集页面的结构是这样的：上方是整剧的信息，下方是分集的分页面板，右方是剧的相关信息，最底下是评论（用户自己的评论会放在第一条），中间就是收藏评论按钮啦。继续举个例子<img src="http://ww3.sinaimg.cn/large/0069l0tRjw1ezvbgw6cvij302w02y745.jpg" height="16px"></p>
        <p><img src="http://ww1.sinaimg.cn/large/0069l0tRjw1ezvbrpv2maj30xg0r0k2l.jpg" class="img-responsive"></p>
        <p>整部剧和它每个分集的收藏和评论是分开的，这样有利于用户对剧集进行针对性的评价。用户点击“收藏并写评”或者“写评论”的按钮的时候，会进入新的页面；而单纯的收藏只需要通过悬浮框操作。</p>
        <p><img src="http://ww1.sinaimg.cn/large/0069l0tRjw1ezvbtbde5kj30lf0aewg1.jpg" class="img-responsive"></p>
        <p>在输入标签的时候，键入空格即表示该标签完成，便可直接输入下一个；点击常用标签也是另外一种输入方式哦<span class="text-info">(๑•ᴗ•๑)</span></p>
        <h3 id="sc">抓糖入门之SC社团那点事</h3>
        <p>如果想添加SC或者社团，需要先通过导航栏点击进入“SC列表”或者“社团列表”，然后通过页面右方的<img src="http://ww4.sinaimg.cn/large/0069l0tRjw1ezvbznshn6j303100sglf.jpg">和<img src="http://ww2.sinaimg.cn/large/0069l0tRjw1ezvbznb740j304h00ndfp.jpg">按钮添加，具体的信息输入界面就不赘述啦！</p>
        <p>在列表页面里点击SC或者社团名，就可以进入对应的页面。页面中部有<img src="http://ww1.sinaimg.cn/large/0069l0tRjw1ezvbzmxgvxj302c00s0sj.jpg">按钮，可以继续点击发表自己感想哦！目前还比较荒凉，大家一起来嘛<span class="text-info">o(￣▽￣)d</span></p>
        <p>我们还可以通过印象列表进入页面，链接就是抓糖<strong>首页</strong>附加功能栏里的<img src="http://ww4.sinaimg.cn/large/0069l0tRjw1ezvbzmj09vj302q00ndfn.jpg"></p>
        <p><strong>社团</strong>的页面主要分为基本信息、成员和印象三部分，举个例子<img src="http://ww3.sinaimg.cn/large/0069l0tRjw1ezvbgw6cvij302w02y745.jpg" height="16px"></p>
        <p><img src="http://ww3.sinaimg.cn/large/0069l0tRjw1ezvbzm3ye8j30mt06wjs9.jpg" class="img-responsive"></p>
        <p><strong>SC</strong>的页面分为基本信息、作品和印象三部分，例子<img src="http://ww3.sinaimg.cn/large/0069l0tRjw1ezvbgw6cvij302w02y745.jpg" height="16px">又来啦</p>
        <p><img src="http://ww4.sinaimg.cn/large/0069l0tRjw1ezvc4ygks0j308y0e4gne.jpg" class="img-responsive"></p>
        <p>可以看到，作品是SC页面很重要的一个部分啊！那这些关联要在哪里添加呢？再次进入剧集页面吧XD 页面右方有“主要SC”栏，点击进入就可以看到添加面板啦！</p>
        <p><img src="http://ww1.sinaimg.cn/large/0069l0tRjw1ezvc8m8irhj30ly0hrwhg.jpg" class="img-responsive"></p>
        <p>点击添加关联，就是正式的添加页面了！具体请参照页面说明，提交便可完成。</p>
        <p><img src="http://ww3.sinaimg.cn/large/0069l0tRjw1ezvc4xayeqj30q408yjsx.jpg" class="img-responsive"></p>
        <h3 id="judan">抓糖功能之剧单</h3>
        <p>剧单在推剧的时候很好用哦！把剧按照自己的喜好再次归类，管理和查找的时候更加方便。</p>
        <p>剧单页面在首页的入口位于首页右方的剧单、剧集陈列区。</p>
        <p>点击 <strong>查看全部</strong> 即可进入➷➷</p>
        <p><img src="http://ww3.sinaimg.cn/large/0069l0tRjw1ezvhgfsd7nj30ga0cu75r.jpg" class="img-responsive"></p>
        <p>剧单页面内有简单的排序功能，点击<img src="http://ww1.sinaimg.cn/large/0069l0tRjw1ezvciadtogj303y00owec.jpg">和<img src="http://ww1.sinaimg.cn/large/0069l0tRjw1ezvcia110ij303s00nmx0.jpg">按钮即可切换。</p>
        <p>点击进入剧单页面内即可<img src="http://ww1.sinaimg.cn/mw690/0069l0tRjw1ezvci9taftj302500ojr6.jpg">，导航栏中用户名自带的快捷菜单可以查看自己的剧单收藏页面~</p>
        <p><img src="http://ww3.sinaimg.cn/large/0069l0tRjw1ezvci9homwj304z07fdg2.jpg"></p>
        <p>想要管理自己创建的剧单的话可以在自己的主页右方点击 <strong>查看全部</strong></p>
        <p><img src="http://ww1.sinaimg.cn/large/0069l0tRjw1ezvci90uowj307v02maa2.jpg" class="img-responsive"></p>
        <p>或者直接点击自己想编辑的剧单名，<img src="http://ww1.sinaimg.cn/large/0069l0tRjw1ezvci5nlgwj302x00qjr7.jpg">可以让用户修改剧单名和简介，还有顾名思义的<img src="http://ww1.sinaimg.cn/large/0069l0tRjw1ezvci5f249j302400o0sj.jpg">功能，要小心哦！</p>
        <p>单个剧集在剧单内的编辑可以在每个剧集的收录信息栏通过修改和删除按钮编辑，点击后会出现悬浮框，按照对应信息修改就可以啦</p>
        <p><img src="http://ww2.sinaimg.cn/large/0069l0tRjw1ezvci5jl1dj30os0980th.jpg" class="img-responsive"></p>
        <h3 id="shaixuan">抓糖功能之筛选</h3>
        <p>抓糖在剧集页面和分集页面都设置了筛选面板，两者会有点不同，这里就仅以分集筛选为例子解说。</p>
        <p>筛选的功能是随着用户的每一次操作而运行的，除了一个重设按钮之外，筛选面板主要分为四个部分。</p>
        <p><img src="http://ww3.sinaimg.cn/large/0069l0tRjw1ezvhmn3y0yj30og083dhl.jpg" class="img-responsive"></p>
        <ul>
            <li>条件筛选：以明确的类型条件收窄展示的剧的范围</li>
            <li>日期筛选：点击日历按钮即出现月历面板，点击具体日期即可</li>
            <li>成员关键词筛选：以制作组或者cv的名称搜索剧名</li>
            <li>进阶排序：在筛选完成后对结果进行排序</li>
        </ul>
        <h3 id="zhoubian">抓糖功能之周边板块</h3>
        <p>周边板块是最近新加入的板块，在首页右方进入<img src="http://ww4.sinaimg.cn/large/0069l0tRjw1ezvhsmgix3j307b00oq2w.jpg"></p>
        <p>周边板块的导航栏跟主站已经不一样了~相较于剧集，它是相对独立的</p>
        <p><img src="http://ww3.sinaimg.cn/large/0069l0tRjw1ezvhr553lhj30w401gweu.jpg"></p>
        <p>周边主要分为三个部分：</p>
        <ul>
            <li>歌曲及剧情歌</li>
            <li>FT或电台节目</li>
            <li>歌会、YY活动及其他</li>
        </ul>
        <p>在周边板块的首页里它们依次从上而下排列，左方是简单列表，右方是评论</p>
        <p><img src="http://ww1.sinaimg.cn/large/0069l0tRjw1ezvhr4ijcfj30wj0h6n4d.jpg" class="img-responsive"></p>
        <p>每个部分的列表都可以通过导航栏内的按钮进入，目前板块还比较空，大家一起来添加吧XD</p>
        <h3 id="xingxiang">抓糖特色之性向切换</h3>
        <p>抓糖在剧集和周边的收集上并没有性向区分，但是考虑到用户喜好，我们在首页展示中预设了只显示耽美剧集。但是，用户可以通过首页导航栏下方的切换超链<img src="http://ww4.sinaimg.cn/large/0069l0tRjw1ezvhw7cqzdj307p00oglj.jpg">调整首页剧集及评论的显示类型。这排超链也出现在评论列表里哒！</p>
        <p>在剧集分集页面内便没有这种区分了，一切的工作交给筛选面板啦！</p>
        <h3 id="gongju">抓糖特色之小工具</h3>
        <p>出于站长的个人喜好，抓糖还开发了一些有意思的小功能，在这里给大家介绍一下。</p>
        <ul>
            <li>
                <p><strong>页面分享工具（至微博）</strong></p>
                <p>在剧集页面中，导航栏右下方会有个微博分享按钮<img src="http://ww3.sinaimg.cn/large/0069l0tRjw1ezvi01rgi1j302q00o3yb.jpg">，点击后就可以自动发微博啦，当然，还是要授权一下的XD</p>
                <p><img src="http://ww3.sinaimg.cn/large/0069l0tRjw1ezvi01jca7j30i20fuace.jpg" class="img-responsive"></p>
            </li>
            <li>
                <p><strong>听剧年终总结工具</strong></p>
                <p>首页右上方有惊喜哦！想写听剧总结的时候参考一下是不是特别棒！</p>
                <p><img src="http://ww1.sinaimg.cn/large/0069l0tRjw1ezvi014tduj307302gq34.jpg"></p>
            </li>
            <li>
                <p><strong>评论印象导出工具</strong></p>
                <p>在用户的个人主页右下方有一列功能按钮，在红框框里的就是方便的导出工具啦！</p>
                <p><img src="http://ww4.sinaimg.cn/large/0069l0tRjw1ezvi00tlidj307r07q0tj.jpg"></p>
                <p>点击按钮便会自动下载文件，导出收藏文件“favorites.csv”，评论文件“reviews.csv”，印象文件”screvs.csv”</p>
            </li>
        </ul>
    </div>
@endsection
