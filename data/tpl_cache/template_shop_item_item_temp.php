<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/item/item_temp|template/shop/header|template/shop/footer', '1511506356', 'template/shop/item/item_temp');?><!DOCTYPE HTML>
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
<div class="info_wrap main_table mt10">
    <table>
        <tr>
            <th class="table_w1"><input id="J_checkall" type="checkbox" value=""></th>
            <th class="table_w3" style="text-align:left; padding-left:10px;">模板标题</th>
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
            <td style="text-align:left; padding-left:10px;"><?=$v['title']?></td>
            <td style="text-align:left; padding-left:10px;"><?=$v['item_title']?><?php if($v['pic']) { ?><img src="<?=$v['pic']?>" style=" height:100px;" /><?php } ?></td>
            <td><?=$v['total_price']?>元</td>
            <td><?php echo $v['total_price']*$v['total_stock'] ?>元<br><?php if($v['is_pay']==1) { ?>已支付<?php } else { ?><em style="color: red">未支付</em><?php } ?></td>
           <!--  <td><textarea style="height: 80px"><?=$v['content']?></textarea></td> -->
            <td><?=$v['total_stock']?></td>
            <td class="table_nav">
                <?php if($arr_languages) { ?>
                <a style="width:50px;" href="/shop.php?mod=item&mod_dir=mall&extra=add_temp&item_id=<?=$v['item_id']?>&copy=1" class="red_nav admin_nav"><?=$arr_language['copy']?></a>
                <?php } ?>
                <a<?php if($arr_languages) { ?> style="width:50px;" <?php } ?> href="/shop.php?mod=item_temp&extra=add_temp&item_id=<?=$v['item_id']?>" class="blue_nav admin_nav">详情</a>
                <a<?php if($arr_languages) { ?> style="width:50px;" <?php } ?> href="javascript:;" class="gray_nav admin_nav J_btn_del" onclick="return false;" data-item_id="<?=$v['item_id']?>"><?=$arr_language['del']?></a>
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
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/shop/shop_item_temp.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
shop_item_temp_del._init()
shop_item_temp._listen_bat_inactive();
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