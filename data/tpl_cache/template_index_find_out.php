<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/find_out|template/index/header|template/index/footer_menu', '1511601978', 'template/index/find_out');?><!DOCTYPE html>
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
<div class="hed_ct">发现</div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="container">
<div class="find_out">
<ul>
<li><a href="/find/news"><i class="icon icon_a iconfont icon-xinwenzixun"></i>新闻公告</a></li>
<li><a href="/page/p-3"><i class="icon icon_b iconfont icon-faya"></i>新手教程</a></li>
<li><a href="javascript:;" id="J_qrcode"><i class="icon icon_c iconfont icon-yaoqinghaoyou"></i>邀请好友</a></li>
<li><a href="http://wpa.qq.com/msgrd?v=3&uin=<?=$_SGLOBAL['data_config']['data_qq']?>&Menu=yes"><i class="icon icon_d iconfont icon-kefu"></i>在线客服</a></li>
<div class="clearfix"></div>
</ul>
</div>
</div>
<!-- /container -->


<!-- footer -->
<div id="footer"></div>
<div class="footer">
        <ul>
                <li <?php if($_GET['mod']=='index') { ?>class="active"<?php } ?>><a href="/"><i class="icon iconfont icon-shouye-copy-copy-copy"></i>首页</a></li>
                <li <?php if($_GET['mod']=='game') { ?>class="active"<?php } ?>><a href="/game"><i class="icon iconfont icon-youxituijian"></i>任务</a></li>
                <li <?php if($_GET['mod']=='find') { ?>class="active"<?php } ?>><a href="/find"><i class="icon iconfont icon-weibiaoti-"></i>发现</a></li>
                <li <?php if($_GET['mod']=='i') { ?>class="active"<?php } ?>><a href="/i"><i class="icon iconfont icon-piaoliusanicon-geren-"></i>我的</a></li>
                <div class="clearfix"></div>
        </ul>
</div>
<!-- footer end -->

<script src="/mat/dist/js/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="/mat/dist/js/modules/common.js" type="text/javascript"></script>
<script src="/mat/dist/js/language/zh_cn.js" type="text/javascript"></script>
<script src="/mat/dist/js/index/idangerous.swiper.min.js"></script> 
<script type="text/javascript">
var int_success = <?php if($_SGLOBAL['success_order']) { ?><?=$_SGLOBAL['success_order']?><?php } else { ?>0<?php } ?>;
var int_login = <?php if($_SGLOBAL['member']) { ?><?=$_SGLOBAL['member']['uid']?><?php } else { ?>0<?php } ?>;
$('#J_qrcode').click(function(){
if (int_login==0) {
window.location.href='/login'
return false
}
if (int_success>=5) {
location.href='/find/invite_friends';
}else{
make_alert_html('提示','请先完成5个任务');
}
})
</script>

</body>

</html><?php ob_out();?>