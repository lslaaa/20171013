<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/shop/index|template/shop/header|template/shop/footer', '1512214519', 'template/shop/shop/index');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO</title>
<link rel="stylesheet" href="/mat/??dist/css/shop/global.css,dist/css/shop/mainstyle.css,dist/css/shop/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<!-- 列表 [[-->
<div class="admin_menu mt12" style="background-color: white;border:1px solid #e4e4e4;padding: 5px">
    <p style="font-size: 18px"><b style="width: 80px;display: inline-block">用户名：</b><?=$arr_member['username']?></p>
    <p style="font-size: 18px"><b style="width: 80px;display: inline-block">余额：</b><em style="color: red"><?=$arr_member['balance']?></em>元</p>
</div>
<div class="admin_menu mt12">
        <span class="J_show_hide_menu">
            <a id="J_add_level_one" href="/shop.php?mod=shop&extra=add" class="blue_nav" style="display:inline-block;"><?=$arr_language['add']?></a>
        </span>
        <span class="J_submit_from"></span>
</div>
<div style="clear:both; height:0; overflow:hidden;"></div>
<div class="vip_show_list mt10">
    <ul>
        <li><a href="/shop.php?mod=shop" <?php if(!$int_status) { ?>class="on"<?php } ?>>全部</a></li>
        <li><a href="/shop.php?mod=shop&status=100" <?php if($int_status==100) { ?>class="on"<?php } ?>>待审核</a></li>
        <li><a href="/shop.php?mod=shop&status=110" <?php if($int_status==110) { ?>class="on"<?php } ?>>审核通过</a></li>
        <li><a href="/shop.php?mod=shop&status=304" <?php if($int_status==304) { ?>class="on"<?php } ?>>审核未通过</a></li>
    </ul>
</div>
        
<div class="info_wrap main_table mt10">
    <table>
    	<tr>
            <th class="table_w6" style="text-align:left; padding-left:10px;">店铺名</th>
            <th class="table_w6">店铺类型</th>
            <th class="table_w6">用户昵称</th>
            <th class="table_w6">店铺地址</th>
            <th class="table_w6">联系电话</th>
            <th class="table_w6">联系人</th>
            <th class="table_w6">联系地址</th>
            <th class="table_w6">状态</th>
            <th class="table_w5">操作</th>
        </tr>
        <?php if($arr_shop['total']>0) { ?>
        <?php if(is_array($arr_shop['list'])) { foreach($arr_shop['list'] as $v) { ?>
        <tr class="white">
            <td style="text-align:left; padding-left:10px;"><?=$v['shopname']?></td>
            <td><?=$arr_type_name[$v['type']]?></td>
            <td><?=$v['username']?></td>
            <td><?=$v['shop_url']?></td>
            <td><?=$v['phone']?></td>
            <td><?=$v['contact']?></td>
            <td><?=$v['address']?></td>
            <td><?php if($v['status']==100) { ?>待审核<?php } ?><?php if($v['status']==110) { ?>已通过<?php } ?></td>
            <td class="table_nav">
               <a href="/shop.php?mod=shop&extra=add&sid=<?=$v['sid']?>" class="blue_nav admin_nav">修改</a>
        	   <a href="javascript:;" class="J_btn_del red_nav admin_nav" data-id="<?=$v['sid']?>">删除</a>
</td>
        </tr>
        <?php } } ?>
        <?php } else { ?>
        <tr class="white">
            <td colspan="10"><div class="no-data"><?=$arr_language['nothing']?></div></td>
        </tr>
        <?php } ?>
    </table>
</div>
<!--]] 列表 -->
<!-- page [[-->

<div class="info_wrap mt20">
<div class="page"><?=$str_num_of_page?></div>
</div>

<!--]] page -->
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/shop/admin_shop.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
admin_shop_del._init();

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