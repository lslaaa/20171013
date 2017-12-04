<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/money/bank_card|template/admin/header|template/admin/footer', '1511885931', 'template/shop/money/bank_card');?><!DOCTYPE HTML>
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
<p style="font-size:18px"><b>银行卡列表</b></p>
<br>
<div style="clear: both;"></div>
<p><a href="/shop.php?mod=money&extra=add_bank_card" class="blue_nav" style="padding: 10px 30px;border-radius: 3px;" data-id="<?=$v['id']?>">添加银行卡</a></p>
<div style="clear: both;"></div>
<br>
<!-- <form action="" method="post">
    <span>用户名：</span><input type="text" class="text_s" style="height:25px" name="username" value="">
    <span>手机号：</span><input type="text" class="text_s" style="height:25px" name="mobile" value="">
    <input type="submit" class="blue_nav btn_class"  name="" style="padding: 5px 20px;border-radius: 3px;border:none" value="搜索">
</form>
<br> -->
<div class="info_wrap main_table mt10">
    <table>
        <tr style="white-space:nowrap">
            <th class="table_w5">开户行</th>
            <th class="table_w5">银行账号</th>
            <th class="table_w5">银行卡姓名</th>
            <th class="table_w5">操作</th>
        </tr>
        <?php if($arr_list['total']>0) { ?>
        <?php if(is_array($arr_list['list'])) { foreach($arr_list['list'] as $v) { ?>
        <tr class="white">
            <td><?=$v['bank']?></td>
            <td><?=$v['bank_card']?></td>
            <td><?=$v['bank_card_name']?></td>
            <td class="table_nav">
                <a href="javascript:;" class="J_btn_cancel gray_nav admin_nav" data-id="<?=$v['id']?>">删除</a>
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
bank_cancel._init();
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