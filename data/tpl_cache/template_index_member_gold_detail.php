<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/member/gold_detail|template/index/header', '1511455350', 'template/index/member/gold_detail');?><!DOCTYPE html>
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
<div class="hed_lf"><a href="/i"><i class="icon iconfont icon-sanjiao"></i></a></div>
<div class="hed_ct">金币明细</div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="center">
<div class="gold_detail">
<div class="mber_top">
<div class="mber_top_tux">
<p class="txt">累计金币</p>
<p class="money"><?php echo $arr_member['balance']+$arr_member['commission'] ?></p>
</div>
<p class="mingx">金币明细</p>
<ul>
<li class="h2">
<p>可用金币</p>
<p><?=$arr_member['balance']?></p>
</li>
<li class="h3">
<p>活动奖励</p>
<p>0.00</p>
</li>
<div class="clearfix"></div>
</ul>
</div>
<dl class="gold_list">
<dt>近5日金币明细</dt>
<?php if(is_array($arr_money_log['list'])) { foreach($arr_money_log['list'] as $v) { ?>
<dd><?php echo date('Y-m-d',$v['in_date']) ?>
<span><?php if($v['type']!=50) { ?>+<?php } else { ?>-<?php } ?><?=$v['money']?></span>
</dd>
<?php } } ?>
</dl>
</div>
</div>
<!-- /container -->


<!-- footer -->
<!-- footer end -->



</body>

</html><?php ob_out();?>