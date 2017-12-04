<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/reg|template/index/header', '1511617452', 'template/index/reg');?><!DOCTYPE html>
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
<div class="hed_lf"><a href="/login"><i class="icon iconfont icon-sanjiao"></i></a></div>
<div class="hed_ct">注册</div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="login">
<form id="J_reg_form" action="/reg" method="post" target="ajaxframeid">
            <input class="J_callback" type="hidden" name="callback" value="parent.send_success">
            <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
<ul>
<li><span>手机号码：</span><input id="J_phone" name="phone" placeholder="请输入手机号" /><a href="javascript:;" id="J_get_checkcode" style="font-size: 0.2rem">获取验证码</a></li>
<li><span>验证码：</span><input id="J_checkcode" name="checkcode" placeholder="请输入验证码" /></li>
<li><span>密  码：</span><input id="J_password" name="password" type="password" placeholder="请输入密码" /></li>
<li><span>确认密码：</span><input id="J_password_2" name="password_2" type="password" placeholder="请输入密码" /></li>
</ul>
<div class="and_login" id="regsubmit"><a href="javascript:;">注册</a></div>
</form>
</div>
<!-- /container -->


<!-- footer -->

<!-- footer end -->




<script src="/mat/dist/js/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="/mat/dist/js/modules/common.js" type="text/javascript"></script>
<script src="/mat/dist/js/language/zh_cn.js" type="text/javascript"></script>
<script src="/mat/dist/js/index/idangerous.swiper.min.js"></script> 
<script src="/mat/dist/js/index/reg.js"></script> 
<script>
reg._init();
var tabsSwiper = new Swiper('.swiper-container',{
speed:500,
onSlideChangeStart: function(){
$(".tabs .active").removeClass('active');
$(".tabs a").eq(tabsSwiper.activeIndex).addClass('active');
}
});

$(".tabs a").on('touchstart mousedown',function(e){
e.preventDefault()
$(".tabs .active").removeClass('active');
$(this).addClass('active');
tabsSwiper.swipeTo($(this).index());
});

$(".tabs a").click(function(e){
e.preventDefault();
});
</script>
</body>

</html><?php ob_out();?>