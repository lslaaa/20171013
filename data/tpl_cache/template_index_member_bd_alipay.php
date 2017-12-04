<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/member/bd_alipay|template/index/header|template/index/footer', '1511196319', 'template/index/member/bd_alipay');?><!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>买家秀</title>

        <link rel="stylesheet" type="text/css" href="/mat/dist/css/index/iconfont/iconfont.css">
        <!-- Custom styles for this template -->
        <link href="/mat??/dist/css/index/style.css,dist/css/admin/pop_css.css" rel="stylesheet" type="text/css">
</head>


<body>
<!-- 头部 -->
<div class="header">
<div class="hed_lf"><a href="/i"><i class="icon iconfont icon-sanjiao"></i></a></div>
<div class="hed_ct">绑定支付宝</div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="container">
<form id="J_send_form" action="/i/bd_alipay" method="post" target="ajaxframeid">
    	<input class="J_callback" type="hidden" name="callback" value="parent.send_success">
    	<input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
<div class="bd_alipay">
<p class="h5">请绑定您的支付宝信息，用于提现使用</p>
<ul>
<li><span>支付宝姓名：</span><input name="alipay_name" id="J_alipay_name" value="<?=$arr_member['alipay_name']?>" placeholder="请输入姓名" /></li>
<li><span>支付宝账号：</span><input name="alipay" id="J_alipay" value="<?=$arr_member['alipay']?>" placeholder="请输入账号" /></li>
</ul>
<div class="and_login"><a href="javascript:;" id="J_send">绑定</a></div>	
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
bd_alipay._init()

</script>

</body>

</html><?php ob_out();?>