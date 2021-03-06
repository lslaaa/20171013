<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/mall/order/index|template/admin/header|template/admin/footer', '1508580491', 'template/admin/mall/order/index');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>巨檬管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<!-- 列表 [[-->

<div class="product_search">
    <form action="/admin.php?mod=order&mod_dir=mall" method="get" id="search_form">
    	<input type="hidden" value="order" name="mod" />
        <input type="hidden" value="mall" name="mod_dir" />
        <input type="hidden" value="<?=$int_status?>" name="status" />
        <dl class="mt10">
            <dt class="ml15"><?=$arr_language['order']['order_id']?>:</dt>
            <dd><input class="text"  name="order_id" value="<?php if($int_order_id) { ?><?=$int_order_id?><?php } ?>"></dd>
            <dt class="ml15"><?=$arr_language['order']['buyer_name']?>:</dt>
            <dd><input class="text"  name="buyer_name" value="<?php if($str_buyer_name) { ?><?=$str_buyer_name?><?php } ?>"></dd>
            <dt class="ml15"><?=$arr_language['order']['phone']?>:</dt>
            <dd><input class="text"  name="phone" value="<?php if($str_phone) { ?><?=$str_phone?><?php } ?>"></dd>
            <dt class="ml15 add_navbox"><input type="submit" class="blue_nav" value="<?=$arr_language['search']?>" style="margin-top:0;"/></dt>
        </dl>        
    </form>
</div>
<div class="vip_show_list mt10">
    <ul>
        <li><a href="/admin.php?mod=order&mod_dir=mall" <?php if($int_status==0) { ?>class="on"<?php } ?>><?=$arr_language['all']?></a></li>
        <li><a href="/admin.php?mod=order&mod_dir=mall&status=<?=$int_order_no_pay?>" <?php if($int_status==$int_order_no_pay) { ?>class="on"<?php } ?>><?=$arr_language['order']['order_no_pay_2']?></a></li>
        <li><a href="/admin.php?mod=order&mod_dir=mall&status=<?=$int_order_pay?>" <?php if($int_status==$int_order_pay) { ?>class="on"<?php } ?>><?=$arr_language['order']['no_send']?></a></li>
        <li><a href="/admin.php?mod=order&mod_dir=mall&status=<?=$int_order_send?>" <?php if($int_status==$int_order_send) { ?>class="on"<?php } ?>><?=$arr_language['order']['order_send']?></a></li>
        <!--<li><a href="/admin.php?mod=order&mod_dir=mall&status=<?=$int_order_success?>" <?php if($int_status==$int_order_success) { ?>class="on"<?php } ?>><?=$arr_language['order']['order_success']?></a></li>-->
        <li><a href="/admin.php?mod=order&mod_dir=mall&status=<?=$int_order_cancel?>" <?php if($int_status==$int_order_cancel) { ?>class="on"<?php } ?>><?=$arr_language['order']['order_cancel']?></a></li>

    </ul>
</div>
<div class="money_list">
    <div class="admin_menu_s" style="overflow:hidden;">
        <table class="my_orders_list default_border mt15">
            <tr class="title">
                <th class="w50"><input name="" type="checkbox" value="" id="J_selece_all"></th>
                <th>
            <div class="my_orders_product">
                <table>
                    <tr>
                        <td class="img">&nbsp;</td>
                        <td class="name"><?=$arr_language['order']['item']?></td>
                        <td class="price"><?=$arr_language['price']?></td>
                        <td class="data"><?=$arr_language['num']?></td>
                    </tr>
                </table>
            </div>
            </th>
            <th class="w140"><?=$arr_language['order']['remark_2']?></th>
            <th class="w140"><?=$arr_language['order']['buyer']?></th>
            <th class="w140"><?=$arr_language['price']?></th>
             <th class="w140"><?=$arr_language['status']?></th>
            </tr>
        </table>
        <!-- 待付款 [[-->
        <?php if($arr_order['total']>0){ ?>
        <?php if(is_array($arr_order['list'])) { foreach($arr_order['list'] as $v) { ?>
        <table class="my_orders_list orders_border mt12">
            <tr class="title J_title" >
                <td colspan="6" class="number">
                    <?=$arr_language['order']['order_id']?>:
                    <em><?=$v['order_id']?></em>
                    <span class="ml30"><?=$arr_language['order']['buy_date']?>:<?php echo date('Y-m-d H:i:s',$v['in_date']); ?></span>
                    <span class="ml30"><?=$arr_language['order']['address']?>:<?=$v['detail']['contact']?>&nbsp;&nbsp;<?=$v['detail']['phone']?>&nbsp;&nbsp;<?=$arr_district[$v['detail']['province']]['name']?>&nbsp;&nbsp;<?=$arr_district[$v['detail']['city']]['name']?>&nbsp;&nbsp;<?=$arr_district[$v['detail']['area']]['name']?>&nbsp;&nbsp;<?=$v['detail']['address']?></span>
                    <?php if($v['detail']['remark']) { ?><span class="ml30"><?=$arr_language['order']['remark']?>:<?=$v['detail']['remark']?></span><?php } ?>
                </td>
            </tr>
            <tr>
                <td class="w50"><input name="ids[]" type="checkbox" value="" class="J_idea_code"></td>
                <td>
                    <div class="my_orders_product">
                        <table>
                            <?php $floot_return_price = 0; ?>
                            <?php if(is_array($v['item'])) { foreach($v['item'] as $v2) { ?>
                            <tr>
                                <td class="img"><img src="<?=$v2['item_main']['pic']?>"></td>
                                <td class="name">
                                    <p><?=$v2['item_main']['title']?></p>
                                    <p class="size"><?=$v2['item_main']['sku_1_name']?></p>
                                    <p class="size"><?php if($v2['special_sku_2']) { ?><?=$v2['special_sku_2']?><?php } else { ?><?=$v2['item_main']['sku_2_name']?><?php } ?></p>
                                </td>
                                <td class="price">
                                    <?=$v2['price']?>
                                </td>
                                <td class="data"><?=$v2['num']?></td>

                            </tr>
                            <?php } } ?>                        
                        </table>
                    </div>
                </td>
                <td class="w140"><?=$v['detail']['remark_2']?></td>
                <td class="w140 buyers"><a><?php if($v['uid']>0) { ?><?=$v['buyer']['username']?><?php } else { ?><?=$arr_language['guests']?><?php } ?></a></td>
                <td class="w140">
                    <p class="price"><?=$v['total_price']?></p>
                    
                </td>
                <td class="w140">
                <?php if($v['status']==$int_order_cancel) { ?>
                        <p class="gray"><?=$arr_language['order']['order_cancel']?></p>
                <?php } elseif($v['status']==$int_order_no_pay) { ?>
                        <p class="gray"><?=$arr_language['order']['order_no_pay']?></p>
                        <p class="gray"><a class="J_btn_cancel" data-order_id="<?=$v['order_id']?>" href="javascript:;"><?=$arr_language['order']['order_do_cancel']?></a></p>
                <?php } elseif($v['status']==$int_order_pay) { ?>
                		<p class="gray"><?=$arr_language['order']['order_pay']?></p>
                <?php } elseif($v['status']==$int_order_send) { ?>
                		<p class="gray"><?=$arr_language['order']['order_send']?></p>
                <?php } elseif($v['status']==$int_order_success) { ?>
                		<p class="gray"><?=$arr_language['order']['order_success']?></p>
                <?php } ?>
                <p class="gray"><a href="/admin.php?mod=order&mod_dir=mall&extra=detail&order_id=<?=$v['order_id']?>"><?=$arr_language['detail']?></a></p>
                </td>
            </tr>        
        </table>        
        <?php } } ?>
        <?php }else{ ?>
        <table class="my_orders_list orders_border mt12">
            <tr class="title">
                <td colspan="6" class="number"><?=$arr_language['nothing']?></td>
            </tr>
        </table>
        <?php } ?>
     </div>
</div>
<!--]] 列表 -->
<!-- page [[-->

<div class="info_wrap mt20">
<div class="page"><?=$str_num_of_page?></div>
</div>
<!--]] page -->
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/admin_order.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
admin_order_cancel._init();
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