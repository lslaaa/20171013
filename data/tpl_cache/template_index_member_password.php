<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/member/password|template/index/header|template/index/footer', '1511316869', 'template/index/member/password');?><!DOCTYPE html>
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
<div class="hed_lf"><a href="/i/security"><i class="icon iconfont icon-sanjiao"></i></a></div>
<div class="hed_ct">密码修改</div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="container">
<form id="J_send_form" action="/i/password" method="post" target="ajaxframeid">
    	<input class="J_callback" type="hidden" name="callback" value="parent.send_success">
    	<input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
<div class="password">
<ul>
<li><label>旧密码：</label><input name="password_old" id="J_password_old" type="password" placeholder="请输入您的旧密码" /></li>
<li><label>新密码：</label><input name="password_new" id="J_password_new" type="password" placeholder="请输入您的新密码" /></li>
</ul>
<div class="and_login"><a href="javascript:;" id="J_send">确认</a></div>
</div>
</form>
</div>
<!-- /container -->


<!-- footer -->
<!-- footer end -->
<script src="/mat/dist/js/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="/mat/dist/js/modules/common.js" type="text/javascript"></script>
<script src="/mat/dist/js/language/zh_cn.js" type="text/javascript"></script>
<script src="/mat/dist/js/index/idangerous.swiper.min.js"></script> 
<script src="/mat/dist/js/index/member_account.js"></script> 
<script type="text/javascript">
member_password._init()

</script>

</body>

</html><?php ob_out();?>