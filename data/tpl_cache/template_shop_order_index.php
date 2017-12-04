<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/order/index|template/admin/header|template/admin/footer', '1512318358', 'template/shop/order/index');?><!DOCTYPE HTML>
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
        <li><a href="/shop.php?mod=order" <?php if($int_status==0) { ?>class="on"<?php } ?>><?=$arr_language['all']?></a></li>
        <li><a href="/shop.php?mod=order&status=100" <?php if($int_status==100) { ?>class="on"<?php } ?>>待审核</a></li>
        <!-- <li><a href="/shop.php?mod=order&status=105" <?php if($int_status==105) { ?>class="on"<?php } ?>>待完成</a></li> -->
        <li><a href="/shop.php?mod=order&status=304" <?php if($int_status==304) { ?>class="on"<?php } ?>>接单审核失败</a></li>
        <li><a href="/shop.php?mod=order&status=110" <?php if($int_status==110) { ?>class="on"<?php } ?>>待确认</a></li>
        <li><a href="/shop.php?mod=order&status=90" <?php if($int_status==90) { ?>class="on"<?php } ?>>已取消</a></li>
        <li><a href="/shop.php?mod=order&status=200" <?php if($int_status==200) { ?>class="on"<?php } ?>>已完成</a></li>
        <li><a href="/shop.php?mod=order&status=404" <?php if($int_status==404) { ?>class="on"<?php } ?>>审核失败</a></li>

    </ul>
</div>
<div class="money_list">
    <div class="admin_menu mt12">
        <span class="J_show_hide_menu">
            <?php if($int_status==100 || !$int_status) { ?>
            <a id="J_bat_shenhe" href="javascript:;" class="blue_nav" style="display:inline-block;">批量审核</a>
            <!-- <a id="J_bat_phy_del" href="javascript:;" class="red_nav" style="display:inline-block;">批量取消</a> -->
            <?php } ?>
        </span>
    </div>
    <div class="admin_menu_s" style="overflow:hidden;">
        <table class="my_orders_list default_border mt15">
            <tr class="title">
                <th class="w50"><input name="" type="checkbox" value="" id="J_checkall"></th>
                <th class="w140">任务名称</th>
                <th>商品信息</th>
                <th>三要素</th>
                <th class="w140">接单人(淘宝号)</th>
                <th class="w140">任务价格</th>
                <?php if($int_status==404) { ?>
                <th class="w140">拒绝理由</th>
                <?php } ?>
                <th class="w140"><?=$arr_language['status']?></th>
            </tr>
        </table>
        <!-- 待付款 [[-->
        <?php if($arr_list['total']>0){ ?>
        <?php if(is_array($arr_list['list'])) { foreach($arr_list['list'] as $v) { ?>
        <table class="my_orders_list orders_border mt12">
            <tr class="title J_title" >
                <td colspan="7" class="number">
                    <?=$arr_language['order']['order_id']?>:
                    <em><?=$v['order_id']?></em>
                    <span class="ml30">提交时间:<?php echo date('Y-m-d H:i:s',$v['in_date']); ?></span>
                    <?php if($v['detail']['remark']) { ?><span class="ml30"><?=$arr_language['order']['remark']?>:<?=$v['detail']['remark']?></span><?php } ?>
                    <span class="ml30">店铺：<?=$v['shop']['shopname']?></span>
                    <span class="ml30">价格：<?=$v['item']['price']?></span>
                </td>
            </tr>
            <tr>
                <td class="w50"><?php if($v['status']==100) { ?><input name="ids[]" type="checkbox" value="<?=$v['order_id']?>" class="J_checkall_sub"><?php } ?></td>
                <td class="w140"><?=$arr_type_name[$v['shop']['type']]?><?php if($v['item']['cid_1']==1) { ?>商家付款任务<?php } else { ?>流量任务<?php } ?>
                    <span style="display: block;float: left;">
                        <?php if($v['item']['pic']) { ?><img src="<?=$v['item']['pic']?>" style=" height:100px;" /><?php } ?>
                        </span>
                </td>
                <td style="text-align:left;border-left: 1px solid #efefef;    vertical-align: top;">
                    <div style="padding:10px">
                        <span style="display: block;    line-height: 24px;">
                        <p><b>商品名：</b><?=$v['item']['item_title']?></p>
                        </span>
                        <div class="clear"></div>
                    </div>
                    <?php $v['check_pics'] = explode(',',$v['check_pics']); ?>
                    <?php if(is_array($v['check_pics'])) { foreach($v['check_pics'] as $k2 => $v2) { ?>
                        <?php if($k2==0) { ?>
                            <a id="example2-1" title="" href="<?=$v2?>" style="width: 50px;height: 50px;float: left;padding: 0px;margin: 0 0 10px 10px"><img src="<?=$v2?>" style="width: 50px;height: 50px" class="exmpic"></a>
                        <?php } elseif($k2>0) { ?>
                            <?php if($k2==3) { ?>
                            <a id="example2-1" title="" href="<?=$v2?>" style="width: 50px;height: 50px;float: left;padding: 0px;margin:  0 0 10px 10px"><img src="<?=$v2?>" style="width: 50px;height: 50px" class="exmpic"></a>
                            <?php } else { ?>
                            <a id="example2-1" title="" href="<?=$v2?>" style="width: 50px;height: 50px;float: left;padding: 0px;margin:  0 0 10px 10px"><img src="<?=$v2?>" style="width: 50px;height: 50px" class="exmpic"></a>
                            <?php } ?>
                        <?php } ?>
                    <?php } } ?>
                </td>
                <td class="w140">
                    <p>&nbsp;</p>
                    <?php if($v['uid']>0) { ?><?=$v['buyer']['realname']?><?php } else { ?><?=$arr_language['guests']?><?php } ?>（<?=$v['taobao']['taobao']?>）</td>
                <td class="w140">
                    <p>&nbsp;</p>
                    <p style="color: red"><?=$v['total_price']?></p>
                </td>
                <?php if($int_status == 404) { ?>
                <td><?=$v['fail_reason']?></td>
                <?php } ?>
                <td class="w140">
                <?php if($v['status']==304) { ?>
                        <p class="gray">审核失败</p>
                <?php } elseif($v['status']==100) { ?>
                        <p class="gray">待审核</p>
                        <p class="gray"><a class="J_btn_shenhe" data-order_id="<?=$v['order_id']?>" href="javascript:;">审核</a></p>
                        <p class="gray"><a class="J_btn_cancel" data-order_id="<?=$v['order_id']?>" href="javascript:;"><?=$arr_language['order']['order_do_cancel']?></a></p>
                <?php } elseif($v['status']==304) { ?>
                        <p class="gray">接单审核失败</p>
                <?php } elseif($v['status']==105) { ?>
                		<p class="gray">待完成</p>
                <?php } elseif($v['status']==110) { ?>
                        <p class="gray">待确认</p>
                        <p class="gray"><a class="J_btn_confirm" data-order_id="<?=$v['order_id']?>" href="javascript:;">确认/拒绝</a></p>
                <?php } elseif($v['status']==200) { ?>
                		<p class="gray">已完成</p>
                        <?php if($v['is_pay']!=1) { ?>
                        <p class="gray"><a class="J_btn_confirm_money" data-order_id="<?=$v['order_id']?>" href="javascript:;">支付佣金</a></p>
                        <?php } else { ?>
                        <p class="gray">已支付</p>
                        <?php } ?>
                <?php } elseif($v['status']==404) { ?>
                        <p class="gray">审核失败</p>
                <?php } ?>
                <p class="gray"><a href="/shop.php?mod=order&extra=detail&order_id=<?=$v['order_id']?>"><?=$arr_language['detail']?></a></p>
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
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/shop/shop_order.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script src="/mat/dist/js/lib/jquery.imgbox.js"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
checkall._init();
shop_order_cancel._init();
shop_order_shenhe._init();
shop_order_confirm._init();
shop_order_confirm_money._init();
shop_order._init();
</script>
<script>
$(document).ready(function() {
   showBig();
});
function showBig(){
   $("#example2-1,#example2-first,#example2-second,#example2-three").imgbox({
       'speedIn'        : 0,
       'speedOut'       : 0,
       'alignment'      : 'center',
       'overlayShow'    : true,
       'allowMultiple'  : false
   });
}
</script>
<div id="imgbox-loading" style="display: none;"><div style="opacity: 0.4;"></div></div>
<div id="imgbox-overlay" style="height: 3193px; opacity: 0.5; display: none;"></div>
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