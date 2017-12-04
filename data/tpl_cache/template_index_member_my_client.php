<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/member/my_client|template/index/header', '1511188681', 'template/index/member/my_client');?><!DOCTYPE html>
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
<div class="hed_ct">我的客户</div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="center">
<div class="my_client">
<p class="h3">我的客户（<?=$arr_list['total']?>）</p>
<ul>
<?php if(is_array($arr_list['list'])) { foreach($arr_list['list'] as $v) { ?>
<li>
<img class="img" src="<?=$v['face']?>" alt="头像">
<dl>
<dt><?php if($v['nickname']) { ?><?=$v['nickname']?><?php } else { ?><?=$v['username']?><?php } ?><span><?php echo date('Y-m-d',$v['in_date']) ?></span></dt>
<dd><?=$v['phone']?><span>已做任务<label>0</label></span></dd>
</dl>
<div class="clearfix"></div>
</li>
<?php } } ?>
<!-- <li>
<img class="img" src="../images/banner_03.jpg" alt="">
<dl>
<dt>我爱洗澡<span>2017-01-01</span></dt>
<dd>13800138000<span>已做任务<label>0</label></span></dd>
</dl>
<div class="clearfix"></div>
</li>
<li>
<img class="img" src="../images/banner_03.jpg" alt="">
<dl>
<dt>我爱洗澡<span>2017-01-01</span></dt>
<dd>13800138000<span>已做任务<label>0</label></span></dd>
</dl>
<div class="clearfix"></div>
</li> -->
</ul>
</div>
</div>
<!-- /container -->


<!-- footer -->
<!-- footer end -->



</body>

</html><?php ob_out();?>