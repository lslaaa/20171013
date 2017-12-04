<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/login|template/index/header', '1512303272', 'template/index/login');?><!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>任务帮</title>

        <link rel="stylesheet" type="text/css" href="/mat/dist/css/index/iconfont/iconfont.css">
        <!-- Custom styles for this template -->
        <link href="/mat??/dist/css/index/style.css,dist/css/admin/pop_css.css" rel="stylesheet" type="text/css">
</head>


<body>
<!-- 头部 -->
<div class="header">
<div class="hed_lf"><a href="/"><i class="icon iconfont icon-sanjiao"></i></a></div>
<div class="hed_ct">登录</div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="login">
        <form id="J_login_form" action="/login" method="post" target="ajaxframeid">
        <input class="J_callback" type="hidden" name="callback" value="parent.send_success">
        <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
<ul>
<li><span>手机号码：</span><input id="J_login_phone" name="phone" placeholder="请输入手机号" /></li>
<li><span>密  码：</span><input id="J_login_password" name="password" type="password" placeholder="请输入密码" /></li>
</ul>
<div class="zhu_ce"><a href="/reg">还没有账号？3秒注册</a></div>
<div class="and_login" id="J_send_login"><a href="javascript:;">登录</a></div>
        </form>
</div>
<!-- /container -->


<!-- footer -->

<!-- footer end -->

<script src="/mat/dist/js/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="/mat/dist/js/modules/common.js" type="text/javascript"></script>
<script src="/mat/dist/js/language/zh_cn.js" type="text/javascript"></script>
<script src="/mat/dist/js/index/idangerous.swiper.min.js"></script> 
<script src="/mat/dist/js/index/login.js"></script> 
<script type="text/javascript">
login._init();
</script>
</body>

</html><?php ob_out();?>