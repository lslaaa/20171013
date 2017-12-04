<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/invite_friends|template/index/header', '1511487114', 'template/index/invite_friends');?><!DOCTYPE html>
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
<div class="hed_lf"><a href="javascript:;" onclick="javascript:window.history.back(-1);"><i class="icon iconfont icon-sanjiao"></i></a></div>
<div class="hed_ct">邀请好友</div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="container" style="padding: 10px;min-height:0rem;border-bottom: 1px solid #ddd">
<p style="color: red">邀请介绍：所邀请的好友每完成一单任务，降赚取任务10%佣金，邀请越多，奖励越多</p>
</div>
<div class="container" style="padding: 10px;min-height:0rem;border-bottom: 1px solid #ddd">
        <p>1.扫码收徒：将二维码分享给好友进行邀请收徒</p>
        <p><img src="/mat/dist/images/index/qrcode.jpg" style="width: 50%"></p>
</div>
<div class="container" style="padding: 10px;min-height:5rem;border-bottom: 1px solid #ddd">
        <p>2.链接收徒：将邀请链接手动复制后分享给好友进行邀请收徒</p>
        <p>http://<?=M_URL?>/index/r_uid-<?=$_SGLOBAL['member']['uid']?></p>
</div>
<!-- /container -->


<!-- footer -->
<!-- footer end -->



</body>

</html><?php ob_out();?>