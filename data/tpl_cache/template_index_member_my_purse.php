<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/member/my_purse|template/index/header', '1511601130', 'template/index/member/my_purse');?><!DOCTYPE html>
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
<div class="hed_ct">我的钱包</div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="center">
<div class="gold_detail">
<div class="mber_top">
<div class="mber_top_tux">
<p class="txt">账户总额</p>
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
<li class="h4">
<p>冻结金币</p>
<p><?=$arr_member['commission']?></p>
</li>
<li class="h5">
<p>触发金币</p>
<p>0.00</p>
</li>
<div class="clearfix"></div>
</ul>
</div>
<div class="my_purse">
<p class="my_h3">金币记录</p>
<?php if(is_array($arr_money_date)) { foreach($arr_money_date as $v) { ?>
<dl>
<dt><?=$v['date']?></dt>
<?php if(is_array($v['money_log'])) { foreach($v['money_log'] as $v2) { ?>
<dd>
<div class="my_lf">
<p class="lu"><?php if($v2['type']==20) { ?>完成任务<?php } elseif($v2['type']==50) { ?>提现<?php } elseif($v2['type']==55) { ?>提现撤销<?php } elseif($v2['type']==21) { ?>自动取消补贴<?php } elseif($v2['type']==22) { ?>超时审核补贴<?php } ?></p>
<p class="sj"><?php echo date('m-d H:i:s',$v2['in_date']) ?></p>
</div>
<span><?php if($v2['type']!=50) { ?>+<?php } else { ?>-<?php } ?><?=$v2['money']?></span>
<div class="clearfix"></div>
</dd>
<?php } } ?>
</dl>
<?php } } ?>
<div class="and_login"><a href="javascript:;">查看更多</a></div>
</div>
</div>
</div>
<!-- /container -->


<!-- footer -->
<!-- footer end -->

<script src="/mat/dist/js/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script>
$(function () {
            	var flag = true; 
            	$(".and_login").click(function () {
                   	 var _this = $(this);
                    	if(flag) {
                            	$(".my_purse dl").addClass("item_da");
                            	flag = false;
                    	}else {
$(".my_purse dl").removeClass("item_da");
                            flag = true;
                    	}
            	});
});
</script>

</body>

</html><?php ob_out();?>