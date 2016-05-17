<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>内容详情</title>
	<meta name="viewport" content="width=320, initial-scale=1, maximum-scale=1, user-scalable=1"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="/TenderPlatform/Public/css/head-same.css"/>
	<link rel="stylesheet" type="text/css" href="/TenderPlatform/Public/css/content-detail.css"/>

    <!--<link href="/TenderPlatform/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
    <!--<link href="/TenderPlatform/Public/css/AdminLTE.css" rel="stylesheet" type="text/css"/>-->
    <link rel="stylesheet" href="/TenderPlatform/Public/plugins/uploadify/uploadify.css" />
    <script type="text/javascript" src="/TenderPlatform/Public/js/jquery-1.9.1.min.js"></script>
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

    function search() {
        var q = [];
        q.push('keyword='+$('#keyword').val());
        location.href = '/TenderPlatform/index.php/Home/Index/index?'+q.join('&');
    }

    function submit() {
        var params = {};
        var tenderId = $('#tenderId').val();
        params.tenderId = tenderId;

        $.post("/TenderPlatform/index.php/Home/Index/submit", params, function (data, textStatus) {
            var json = {};
            if(typeof(data )=="object"){
                json = data;
            }else{
                json = eval("("+data+")");
            }
            if (json.code == '200' && json.isSuccess) {
                alert(json.msg);
            } else {
                alert(json.msg);
            }
        });
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

	<div class="current-pos">
		<img src="/TenderPlatform/Public/img/index/pos.png" alt="定位logo"/>
		<p><span class="pos-tips">当前位置:</span>首页&gt;设计</p>
	</div>

	<div class="content-report">
        <input hidden="true" id="tenderId" type="text" value="<?php echo ($detail['id']); ?>"/>
		<div class="article-title">
			<h4><?php echo ($detail["projectname"]); ?></h4>
		</div>
		<p>招标区域：<?php echo ($detail["province"]); ?> <?php echo ($detail["city"]); ?></p>
		<p>报名人数：<?php echo ($detail["numbers"]); ?> 人</p>
		<p>截止时间：<?php echo ($detail["enddate"]); ?></p>

		<p class="bid-company"><?php echo ($userDetail["company"]); ?></p>
		<div class="clear"></div>
		<p class="bid-time"><?php echo ($detail["createtime"]); ?></p>
		<div class="clear"></div>
		
		 <!--动态添加的文件在后面每一条加在p便签里面-->
		<div class="annex">
			<p>附件:</p>
            <?php if(is_array($files)): $i = 0; $__LIST__ = $files;if( count($__LIST__)==0 ) : echo "没有数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p><a href="http://localhost/TenderPlatform/<?php echo ($vo['savepath']); echo ($vo['savename']); ?>"><?php echo ($vo['name']); ?></a></p><?php endforeach; endif; else: echo "没有数据" ;endif; ?>
		</div>
		

		<div class="register-button">
			<a onclick="submit()"><img src="/TenderPlatform/Public/img/content/register-button.png"></a>
		</div>
	</div>

	<div class="footer">
		<p>&copy;2015招投标&nbsp;服务平台&nbsp;版权所有&nbsp;复制必究&nbsp;粤ICP备 172625111号</p>
	</div>
</body>
</html>