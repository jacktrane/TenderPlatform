<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>招投标平台</title>
	<meta name="viewport" content="width=320, initial-scale=1, maximum-scale=1, user-scalable=1"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="/TenderPlatform/Public/css/head-same.css"/>
	<link rel="stylesheet" type="text/css" href="/TenderPlatform/Public/css/index.css"/>

    <!--<link href="/TenderPlatform/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
    <!--<link href="/TenderPlatform/Public/css/AdminLTE.css" rel="stylesheet" type="text/css"/>-->
    <link rel="stylesheet" href="/TenderPlatform/Public/plugins/uploadify/uploadify.css" />

    <script src="/TenderPlatform/Public/js/html5shiv.min.js"></script>
    <script src="/TenderPlatform/Public/js/respond.min.js"></script>
    <![endif]-->
    <script src="/TenderPlatform/Public/js/jquery.min.js"></script>
    <script src="/TenderPlatform/Public/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/TenderPlatform/Public/js/common.js"></script>
    <script src="/TenderPlatform/Public/plugins/plugins/plugins.js"></script>
    <script src="/TenderPlatform/Public/plugins/formValidator/formValidator-4.1.3.js"></script>
    <script src="/TenderPlatform/Public/plugins/uploadify/jquery.uploadify.min.js"></script>
</head>

<script>
    function toDetail(id) {
        location.href = '/TenderPlatform/index.php/Home/Index/toBidDetail?id=' + id;
    }

    function search() {
        var q = [];
        q.push('keyword='+$('#keyword').val());
        location.href = '/TenderPlatform/index.php/Home/Index/index?'+q.join('&');
    }

    function category(id) {
        var q = [];
        q.push('class='+id);
        location.href = '/TenderPlatform/index.php/Home/Index/index?'+q.join('&');
    }
</script>
<body>
	<div class="head">
		<div class="nav-head">
			<div class="logo-img">
				<a href="/TenderPlatform/index.php/Home/Index/index"><img src="/TenderPlatform/Public/img/index/logo.png" alt="logo"/></a>
			</div>
			<div class="search-area">
				<input type="text" id="keyword"/>
				<a><img onclick="search()" src="/TenderPlatform/Public/img/index/search-button.png" alt="search-logo"></a>
			</div>
			<div class="login-area">

                <!--登录之后显示用户名-->
                <?php if($_SESSION['username']) { ?>
                <a class="admin-name" href="/TenderPlatform/index.php/Home/User/toMyProfile">欢迎你,<?php echo (session('username')); ?></a>
                <?php } else { ?>
                <a href="/TenderPlatform/index.php/Home/User/toLogin"><img src="/TenderPlatform/Public/img/index/login-button.png"></a>
                <a href="/TenderPlatform/index.php/Home/User/toRegister"><img src="/TenderPlatform/Public/img/index/sign-button.png"></a>
                <?php } ?>
			</div>
			
		</div>
	</div>

	<div class="bid-content">
		<div class="current-pos">
			<img src="/TenderPlatform/Public/img/index/pos.png" alt="定位logo"/>
			<p><span class="pos-tips">当前位置:</span>首页&gt;设计</p>
		</div>

		<div class="bid-category">
			<div class="bid-category-bg">
				<p>
					<img src="/TenderPlatform/Public/img/index/category.png" alt="category-logo"/>
					<span>招标类别</span>
				</p>
				
			</div>

			<div class="big-categoty-list">
				<ul>
					<li onclick="category(1)">设备设施租赁<span class="space-interval">&gt;</span></li>
					<li onclick="category(2)">设备设施销售<span class="space-interval">&gt;</span></li>
					<li onclick="category(3)">可行性研究<span class="space-interval">&gt;</span></li>
					<li onclick="category(4)">设计<span class="space-interval">&gt;</span></li>
					<li onclick="category(5)">勘察<span class="space-interval">&gt;</span></li>
					<li onclick="category(6)">测绘<span class="space-interval">&gt;</span></li>
					<li onclick="category(7)">检测<span class="space-interval">&gt;</span></li>
					<li onclick="category(8)">监测<span class="space-interval">&gt;</span></li>
					<li onclick="category(9)">专业工程承包<span class="space-interval">&gt;</span></li>
					<li onclick="category(10)">劳务承包<span class="space-interval">&gt;</span></li>
					<li onclick="category(11)">结构加固<span class="space-interval">&gt;</span></li>
					<li onclick="category(12)">BIM<span class="space-interval">&gt;</span></li>
					<li onclick="category(13)" style="border:none;">建材<span class="space-interval">&gt;</span></li>
				</ul>
			</div>
		</div>

		<div class="bid-category-right">
			<ul class="bid-category-title">
				<li class="cat1">序号</li>
				<li><img src="/TenderPlatform/Public/img/index/line.png"></li>
				<li class="cat2">项目名称</li>
				<li><img src="/TenderPlatform/Public/img/index/line.png"></li>
				<li class="cat3">开始时间</li>
				<li><img src="/TenderPlatform/Public/img/index/line.png"></li>
				<li class="cat3">结束时间</li>
				<li><img src="/TenderPlatform/Public/img/index/line.png"></li>
				<li class="cat4">报名人数</li>
				<div class="clear"></div>
			</ul>

            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><ul class="bid-category-content" onclick="javascript:toDetail(<?php echo ($vo['id']); ?>)">
				<li class="cat1"><?php echo ($i); ?></li>
				<li class="cat2"><?php echo ($vo['projectname']); ?></li>
				<li class="cat3"><?php echo ($vo['createtime']); ?></li>
				<li class="cat3"><?php echo ($vo['enddate']); ?></li>
				<li class="cat4"><?php echo ($vo['numbers']); ?></li>
				<div class="clear"></div>
			</ul><?php endforeach; endif; else: echo "没有数据" ;endif; ?>
			
			<div class="bid-content-page">
				<ul>
                    <td colspan='11' align='center'><?php echo ($page); ?></td>
					<div class="clear"></div>
				</ul>
				
			</div>
		</div>
	</div>
	<div class="footer">
		<p>&copy;2015招投标&nbsp;服务平台&nbsp;版权所有&nbsp;复制必究&nbsp;粤ICP备 172625111号</p>
	</div>
</body>
</html>