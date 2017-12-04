<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/shop/shop/index|template/shop/header|template/shop/footer', '1511886207', 'template/admin/shop/shop/index');?><!DOCTYPE HTML>
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
<div class="product_search">
    <form id="search_form" method="get" action="/admin.php?mod=shop&mod_dir=shop">
        <input type="hidden" name="mod" value="shop">
        <input type="hidden" name="mod_dir" value="shop">
        <dl class="mt10 f_l" style="width:auto;">
            <dt class="ml15">用户名:</dt>
            <dd><input value="<?=$str_username?>" name="username" class="text"></dd>
        </dl>
        
        <dl class="mt10 f_l" style="width:auto;">
            <dt class="ml15"><?=$arr_language['member']['phone']?>:</dt>
            <dd><input value="<?=$str_phone?>" name="phone" class="text"></dd>
        </dl>
        
        <dl class="mt10 f_l" style="width:auto;">
            <dt class="ml15">店铺名:</dt>
            <dd><input value="<?=$str_shopname?>" name="shopname" class="text"></dd>
            <dt class="ml15 add_navbox"><input type="submit" style="margin-top:0;" value="搜索" class="blue_nav"></dt>
        </dl>
        
    </form>
</div>
<div style="clear:both; height:0; overflow:hidden;"></div>
<div class="vip_show_list mt10">
    <ul>
        <li><a href="/admin.php?mod=shop&mod_dir=shop" <?php if(!$int_status) { ?>class="on"<?php } ?>>全部</a></li>
        <li><a href="/admin.php?mod=shop&mod_dir=shop&status=100" <?php if($int_status==100) { ?>class="on"<?php } ?>>待审核</a></li>
        <li><a href="/admin.php?mod=shop&mod_dir=shop&status=110" <?php if($int_status==110) { ?>class="on"<?php } ?>>审核通过</a></li>
        <li><a href="/admin.php?mod=shop&mod_dir=shop&status=304" <?php if($int_status==304) { ?>class="on"<?php } ?>>审核未通过</a></li>
    </ul>
</div>
        
<div class="info_wrap main_table mt10">
    <table>
    	<tr>
            <th class="table_w6" style="text-align:left; padding-left:10px;">店铺名</th>
            <th class="table_w6">用户名</th>
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
            <td><?=$arr_user[$v['uid']]['username']?></td>
            <td><?=$arr_type_name[$v['type']]?></td>
            <td><?=$v['username']?></td>
            <td><?=$v['shop_url']?></td>
            <td><?=$v['phone']?></td>
            <td><?=$v['contact']?></td>
            <td><?=$v['address']?></td>
            <td><?php if($v['status']==100) { ?>待审核<?php } ?><?php if($v['status']==110) { ?>已通过<?php } ?><?php if($v['status']==304) { ?>不通过<?php } ?></td>
            <td class="table_nav">
               <a href="/admin.php?mod=shop&mod_dir=shop&extra=add&sid=<?=$v['sid']?>" class="blue_nav admin_nav" style="width: 50px">详情</a>
        	   <!-- <a href="javascript:;" class="J_btn_del red_nav admin_nav" data-id="<?=$v['sid']?>">删除</a> -->
               <?php if($v['status'] == 100) { ?>
               <a href="javascript:;" class="blue_nav admin_nav J_btn_yes" style="width: 50px" data-id="<?=$v['sid']?>">通过</a>
               <a href="javascript:;" class="red_nav admin_nav J_btn_no" style="width: 60px" data-id="<?=$v['sid']?>">不通过</a>
               <?php } ?>
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
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/admin_shop.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
admin_shop_shehe._init();

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