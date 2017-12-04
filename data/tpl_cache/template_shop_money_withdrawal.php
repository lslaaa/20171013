<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/money/withdrawal|template/admin/header|template/admin/footer', '1511885938', 'template/shop/money/withdrawal');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<!-- 列表 [[-->
<div class="admin_rights mt10">
<div>
<p style="font-size: 18px"><b style="width: 80px;display: inline-block">用户名：</b><?=$arr_member['username']?></p>
<p style="font-size: 18px"><b style="width: 80px;display: inline-block">余额：</b><em style="color: red"><?=$arr_member['balance']?></em>元
    <a href="/shop.php?mod=money&extra=add_withdrawal" class="blue_nav" style="padding: 3px 10px;border-radius: 3px;margin-left: 10px" data-id="<?=$v['id']?>">提现</a>
</p>
<br>
<p style="font-size:18px"><b>提现记录</b></p>
</div>
<br>
<!-- <form action="" method="post">
    <span>用户名：</span><input type="text" class="text_s" style="height:25px" name="username" value="">
    <span>手机号：</span><input type="text" class="text_s" style="height:25px" name="mobile" value="">
    <input type="submit" class="blue_nav btn_class"  name="" style="padding: 5px 20px;border-radius: 3px;border:none" value="搜索">
</form>
<br> -->
<div class="vip_show_list mt10" style="background-color:#fff">
    <ul>
        <li><a href="/shop.php?mod=money&extra=withdrawal&type=2" <?php if($int_status==0) { ?>class="on"<?php } ?>><?=$arr_language['all']?></a></li>
        <li><a href="/shop.php?mod=money&extra=withdrawal&type=2&status=100" <?php if($int_status == 100) { ?>class="on"<?php } ?>>未处理</a></li>
        <li><a href="/shop.php?mod=money&extra=withdrawal&type=2&status=200" <?php if($int_status == 200) { ?>class="on"<?php } ?>>已处理</a></li>

    </ul>
</div>

<div class="info_wrap main_table mt10">
    <table>
        <tr style="white-space:nowrap">
            <th class="table_w5">提现金额</th>
            <!-- <th class="table_w5">联系人(手机)</th> -->
            <th class="table_w5">开户行</th>
            <th class="table_w5">银行卡号</th>
            <th class="table_w5">银行卡姓名</th>
            <th class="table_w5">时间</th>
            <th class="table_w5">状态</th>
            <th class="table_w5">留言信息</th>
            <th class="table_w5">操作</th>
        </tr>
        <?php if($arr_list['total']>0) { ?>
        <?php if(is_array($arr_list['list'])) { foreach($arr_list['list'] as $v) { ?>
        <tr class="white">
            <td><?=$v['amount']?></td>
            <!-- <td><p><?=$v['contacts']?>(<?=$v['phone']?>)</p></td> -->
            <td><?=$v['bank_name']?></td>
            <td><?=$v['bank_card']?></td>
            <td><?=$v['card_name']?></td>
            <td><?php echo date("Y-m-d H:i:s",$v['in_date']) ?></td>
            <td>
                <?php if($v['status'] == 200) { ?>已处理<?php } ?>
                <?php if($v['status'] == 100) { ?>未处理<?php } ?>
                <?php if($v['status'] == 304) { ?>已撤销<?php } ?>
            </td>
            <td><p<?php if(!$v['reply']) { ?> style="display:none;"<?php } ?>>说明:<span class="J_reply"><?=$v['reply']?></span></p></td>
            <td class="table_nav">
            <?php if($v['status'] == 200) { ?>
                <a href="javascript:;"  data-id="<?=$v['id']?>">提现完成</a>
            <?php } elseif($v['status'] == 100) { ?>
                <!-- <a href="javascript:;" class="J_btn_reply blue_nav admin_nav" data-id="<?=$v['id']?>">确认</a> -->
                <a href="javascript:;" class="J_btn_cancel gray_nav admin_nav" data-id="<?=$v['id']?>">撤销</a>
            <?php } elseif($v['status'] == 304) { ?>
                <a href="javascript:;"  data-id="<?=$v['id']?>">已撤销</a>
            <?php } ?>
            </td>
        </tr>
        <?php } } ?>
        <?php } else { ?>
        <tr class="white">
            <td colspan="9"><div class="no-data"><?=$arr_language['nothing']?></div></td>
        </tr>
        <?php } ?>
    </table>
</div>
        
<!--]] 列表 -->
<!-- page [[-->

<div class="info_wrap mt20">
<div class="page">
    <?=$str_num_of_page?>
    <a>本页<?php echo count($arr_list['list']) ?>条</a>
    <a>共<?php echo $arr_list['total'] ?>条</a>
    </div>
</div>
</div>

<!--]] page -->
<script type="text/javascript" src="/mat/dist/js/lib/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/mat/dist/js/language/admin_zh_cn.js"></script>
<script type="text/javascript" src="/mat/dist/js/modules/common.js"></script>
<script type="text/javascript" src="/mat/dist/js/shop/shop_money.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
shop_money._init();
money_cancel._init();
</script>
</div>
<?php $arr_languages = get_languages();
$arr_languages = json_encode($arr_languages); ?>
<script type="text/javascript">
var json_languages = <?=$arr_languages?>;
if(typeof(parent._attachEvent)=='function'){
parent._attachEvent(document.documentElement, 'keydown', parent.resetEscAndF5);
}
</script>
</body>
</html>
<?php ob_out();?>