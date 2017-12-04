<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/item/index|template/shop/header|template/shop/footer', '1512315865', 'template/shop/item/index');?><!DOCTYPE HTML>
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
<div class="help_menu mt10">
    <div class="product_search">
        <form action="/shop.php?mod=item" method="get" id="search_form">
            <input type="hidden" value="item" name="mod" />
            <input type="hidden" value="<?=$_GET['shop_uid']?>" name="shop_uid" />
            <input type="hidden" value="<?=$_GET['type']?>" name="type" />
            <dl class="mt10">
                <dt class="ml15"><?=$arr_language['item']['title']?>:</dt>
                <dd><input class="text"  name="name" value="<?=$str_name?>"></dd>
                <dt class="ml15 add_navbox"><input type="submit" class="blue_nav" value="<?=$arr_language['search']?>" style="margin-top:0;"/></dt>
            </dl>
        </form>
    </div>
</div>
<div class="admin_menu mt12">
        <span class="J_show_hide_menu">
            <a id="J_add_level_one" href="/shop.php?mod=item&extra=<?php if($_GET['type']) { ?>add_buy<?php } else { ?>add<?php } ?>" class="blue_nav" style="display:inline-block;"><?=$arr_language['add']?></a>
            <a id="J_bat_inactive" href="javascript:;" class="red_nav" style="display:inline-block;"><?php if($_GET['status']==200) { ?>批量下架<?php } else { ?>批量上架<?php } ?></a>
        </span>
        <span class="J_submit_from"></span>
</div>
<div class="info_wrap main_table mt10">
    <table>
    	<tr>
            <th class="table_w1"><input id="J_checkall" type="checkbox" value=""></th>
            <th class="table_w3" style="text-align:left; padding-left:10px;">任务标题</th>
            <th class="table_w3" style="text-align:left; padding-left:10px;"><?=$arr_language['item']['title']?></th>
            <th class="table_w3">任务单价</th>
            <th class="table_w3">任务总价</th>
            <!-- <th class="table_w3"><?=$arr_language['des']?></th> -->
            <th class="table_w6">任务数量</th>
            <th class="table_w5"><?=$arr_language['admin_member']['do']?></th>
        </tr>
        <?php if($arr_list['total']>0) { ?>
        <?php if(is_array($arr_list['list'])) { foreach($arr_list['list'] as $v) { ?>
        <tr class="white">
            <td><input type="checkbox" class="J_checkall_sub" value="<?=$v['item_id']?>"></td>
            <td style="text-align:left; padding-left:10px;"><?=$arr_type_name[$v['shop_type']]?><?php if($v['cid_1']==1) { ?>商家付款任务<?php } else { ?>流量任务<?php } ?></td>
            <td style="text-align:left; padding-left:10px;"><?=$v['item_title']?><?php if($v['pic']) { ?><img src="<?=$v['pic']?>" style=" height:100px;" /><?php } ?></td>
            <td><?=$v['total_price']?>元</td>
            <td><?php echo $v['total_price']*$v['total_stock'] ?>元<br><?php if($v['is_pay']==1) { ?>已支付<?php } else { ?><em style="color: red">未支付</em><?php } ?></td>
           <!--  <td><textarea style="height: 80px"><?=$v['content']?></textarea></td> -->
            <td><?=$v['total_stock']?></td>
            <td class="table_nav">
            	<?php if($arr_languages) { ?>
            	<a style="width:50px;" href="/shop.php?mod=item&mod_dir=mall&extra=add&item_id=<?=$v['item_id']?>&copy=1" class="red_nav admin_nav"><?=$arr_language['copy']?></a>
                <?php } ?>
                <?php if($v['is_pay'] < 1) { ?>
                <a style="width:50px;" href="/shop.php?mod=item&extra=<?php if($_GET['type']) { ?>add_buy<?php } else { ?>add<?php } ?>&item_id=<?=$v['item_id']?>" class="blue_nav admin_nav">支付</a>
                <?php } ?>
            	<a style="width:50px;" href="/shop.php?mod=item&extra=<?php if($_GET['type']) { ?>add_buy<?php } else { ?>add<?php } ?>&item_id=<?=$v['item_id']?>" class="blue_nav admin_nav">详情</a>
                <a style="width:50px;" href="javascript:;" class="gray_nav admin_nav J_btn_del" onclick="return false;" data-item_id="<?=$v['item_id']?>"><?=$arr_language['del']?></a>
            </td>
        </tr>
        <?php } } ?>
        <?php } else { ?>
        <tr class="white">
            <td colspan="<?php if($arr_languages) { ?>7<?php } else { ?>7<?php } ?>"><div class="no-data"><?=$arr_language['nothing']?></div></td>
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
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/shop/shop_item.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
shop_item_del._init()
shop_item._listen_bat_inactive();
checkall._init();
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