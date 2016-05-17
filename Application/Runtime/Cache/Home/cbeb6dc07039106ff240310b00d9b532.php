<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>登录</title>
	<meta name="viewport" content="width=320, initial-scale=1, maximum-scale=1, user-scalable=1"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="/TenderPlatform/Public/css/head-same.css"/>
	<link rel="stylesheet" type="text/css" href="/TenderPlatform/Public/css/login.css"/>

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

	<div class="login-details">
		<div class="current-pos">
			<p>用户登录</p>
		</div>

		<div class="login-box">
			<div class="login-account">
				<input class="account-text" id="username" type="text">
				<p>请输入手机号码/电子邮箱</p>
			</div>
			<div class="login-password">
				<input class="password-text" id="password" type="text">
				<p>请输入密码</p>
			</div>
			<p class="not-account"><a href="/TenderPlatform/index.php/Home/User/toRegister">还没有账号?立即注册</a></p>
			<div class="clear"></div>
			<a class="login-button" onclick="login()"><img src="/TenderPlatform/Public/img/login/login-button.png"></a>
		</div>
	</div>

	<div class="footer">
		<p>&copy;2015招投标&nbsp;服务平台&nbsp;版权所有&nbsp;复制必究&nbsp;粤ICP备 172625111号</p>
	</div>



<script type="text/javascript">
    function login() {
        var params = {};

        var username = $('#username').val();
        var password = $('#password').val();

        params.username = username;
        params.password = password;

        $.post("/TenderPlatform/index.php/Home/User/login", params, function (data, textStatus) {
            var json = {};
            if(typeof(data )=="object"){
                json = data;
            }else{
                json = eval("("+data+")");
            }
            if (json.code == '200' && json.isSuccess) {
                alert(json.msg);
                location.href = '/TenderPlatform/index.php/Home/Index/index';
            } else {
                alert(json.msg);
            }
        });
    }

$(function(){
	$(".account-text").focus(function(){
		$(".login-account p").html("");
	}).blur(function(){
		if($(this).val()==""){
			$(".login-account p").html("请输入手机号码/电子邮箱");
		}
		
	});

	$(".password-text").focus(function(){
		$(".login-password p").html("");
	}).blur(function(){
		if($(this).val()==""){
			$(".login-password p").html("请输入密码");
		}
		
	});
})
</script>
</body>
</html>