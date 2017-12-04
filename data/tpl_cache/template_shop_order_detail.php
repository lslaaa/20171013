<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/order/detail|template/admin/header|template/admin/footer', '1512146720', 'template/shop/order/detail');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<input type='hidden' id="J_hid_order_id" value="<?=$arr_data['main']['order_id']?>">
<input type='hidden' id="J_hid_express" value="<?=$arr_data['detail']['express']?>">
<input type='hidden' id="J_hid_express_id" value="<?=$arr_data['detail']['express_id']?>">
<input type='hidden' id="J_hid_quyang_code" value="<?=$arr_data['detail']['quyang_code']?>">

<div class="admin_menu_s mt10"><input type="submit" class="blue_nav" value="<?=$arr_language['back']?>" onclick="javascript:window.history.back(-1);"></div>
<div class="orders_status">
    <?=$arr_language['status']?>：<?php echo LEM_order::get_status($arr_data['main']['status']); ?>
    <div style="clear: both;height: 10px"></div>
    <?php if($arr_data['main']['fail_reason']) { ?>
    拒绝原因：<?php echo $arr_data['main']['fail_reason']; ?>
    <div style="clear: both;height: 10px"></div>
    <?php } ?>
    任务编号：<?=$arr_data['main']['order_id']?>
    <div style="clear: both;height: 10px"></div>
    领取时间：<?php echo date('Y-m-d H:i:s',$arr_data['main']['in_date']) ?>
    <?php if($arr_data['main']['status']==100) { ?>
    <div class="admin_menu_s mt10"><input type="button" class="blue_nav J_btn_shenhe"  data-order_id="<?=$arr_data['main']['order_id']?>" value="审核"></div>
    <?php } ?>
    <?php if($arr_data['main']['status']==110) { ?>
    <div class="admin_menu_s mt10"><input type="button" class="blue_nav J_btn_confirm"  data-order_id="<?=$arr_data['main']['order_id']?>" value="确认完成"></div>
    <?php } ?>
</div>
<?php if($arr_data['main']['pics']) { ?>
<div class="money_list">
    <div class="order_buyerss">
        <h1>任务截图</h1>
        <?php $arr_data['main']['pics'] = explode(',',$arr_data['main']['pics']); ?>
            <?php if(is_array($arr_data['main']['pics'])) { foreach($arr_data['main']['pics'] as $k2 => $v2) { ?>
                    <?php if($v2) { ?>
                    <a id="example2-1" title="任务截图<?php echo $k2+1 ?>" href="<?=$v2?>" style="width: 100px;height: 100px;float: left;padding: 0px"><img src="<?=$v2?>" style="width: 100px;height: 100px" class="exmpic"></a>
                    <?php } ?>
            <?php } } ?>
    </div>
</div>
<?php } ?>

<!-- 列表 [[-->
<div class="money_list">
<div class="order_buyerss">
    	<h1>任务信息</h1>
        <ul>
            <li><em>任务标题：</em><a><?=$arr_data['item']['title']?></a></li>
        </ul>
    </div>
    <!-- 商品信息 [[-->
    <div class="orders_other mt15 J_info" style="width: 50%;float: left;"> 
        <p><b>端口：</b><?php if($arr_data['item']['duankou']==1) { ?>手机端<?php } else { ?>PC端<?php } ?></p>
        <p><b>商品名：</b><?=$arr_data['item']['item_title']?></p>
        <p><b>关键字：</b><?=$arr_data['item']['key_word']?></p>
        <p><b>产品链接：</b><?=$arr_data['item']['item_link']?></p>
        <p><b>产品价格：</b><?=$arr_data['item']['price']?></p>
        <p><b>购买属性：</b><?=$arr_data['item']['item_attr']?></p>
        <p><b>发货地：</b><?=$arr_data['item']['send_address']?></p>
        <p><b>购买件数：</b><?=$arr_data['item']['buy_num']?></p>
        <p><b>信用卡、花呗：</b><?php if($arr_data['item']['huabei']==1) { ?>支持<?php } else { ?>不支持<?php } ?></p>
        <p><b>旺旺聊天：</b><?php if($arr_data['item']['wangwang']==1) { ?>需要<?php } else { ?>不需要<?php } ?></p>
        <?php $arr_data['item_detail']['pics'] = explode(',',$arr_data['item_detail']['pics']) ?>
        <p>
            <span><b>商品图：</b></span>
            <div style="clear: both;"></div>
            <?php if(is_array($arr_data['item_detail']['pics'])) { foreach($arr_data['item_detail']['pics'] as $v) { ?>
            <img src="<?=$v?>" style="width: 100px;height: 100px">
            <?php } } ?>
        </p>
    </div>
    <div class="orders_other mt15 J_info" style="width: 50%;float: left;"> 
        <p><b>增值要求：</b>
            <br>
            <?php if(is_array($arr_data['item']['require'])) { foreach($arr_data['item']['require'] as $k => $v) { ?>
                <?php if($k=='height') { ?>
                身高：<?=$v['val']?>CM<br>
                <?php } elseif($k=='weight') { ?>
                体重：<?=$v['val']?>KG<br>
                <?php } elseif($k=='local') { ?>
                <?=$v['val']?>米内当天只能匹配1人<br>
                <?php } elseif($k=='sex') { ?>
                性别：<?=$arr_price_name[$v['val']]?><br>
                <?php } elseif($k=='province') { ?>
                地区要求：<?=$v['val']?><br>
                <?php } elseif($k!='priority') { ?>
                <?=$arr_price_name[$v['val']]?><br>
                <?php } ?>
            <?php } } ?>
        </p>
        <?php if($arr_data['item']['remark']) { ?>
        <p><b>下单备注：</b><?=$arr_data['item']['remark']?></p>
        <?php } ?>
        <p><b>其他描述：</b><?=$arr_data['item_detail']['content']?></p>
    </div>
</div>

<!-- 列表 [[-->
<div class="money_list">
    <div class="order_buyerss">
        <h1>接单者信息</h1>
        <ul>
            <li><em><?=$arr_language['order']['buyer_name']?>：</em><a><?=$arr_data['buyer']['realname']?></a></li>
        </ul>
    </div>
    <!-- 商品信息 [[-->
    <!--]] 商品信息 -->
    <!-- <div class="orders_other mt15 J_info" style="width: 50%;float: left;">        
        <p><b>手机号码：</b><?=$arr_data['buyer']['phone']?></p>
        <p><b>证件号（<?php if($arr_data['buyer_detail']['card_type']==1) { ?>身份证<?php } else { ?>驾驶证<?php } ?>）：</b><?=$arr_data['buyer_detail']['card_num']?></p>
        <p><b>证件姓名：</b><?=$arr_data['buyer_detail']['card_name']?></p>
        <p><b>省市区：</b><?=$arr_district[$arr_data['buyer_detail']['province']]['name']?> <?=$arr_district[$arr_data['buyer_detail']['city']]['name']?> <?=$arr_district[$arr_data['buyer_detail']['area']]['name']?></p>
        <p><b>性别：</b><?php if($arr_data['buyer']['sex']==1) { ?>男<?php } else { ?>女<?php } ?></p>
        <p><b>年龄：</b><?=$arr_data['buyer_detail']['age']?></p>
        <p><b>身高：</b><?=$arr_data['buyer_detail']['shengao']?><b>CM</b></p>
        <p><b>体重：</b><?=$arr_data['buyer_detail']['tizhong']?><b>KG</b></p>
        <p><b>三围：</b><?=$arr_data['buyer_detail']['sanwei']?></p>
    
    </div> -->
    <div class="orders_other mt15 J_info" style="width: 50%;float: left;">        
        <p><b>淘宝账号：</b><?=$arr_data['taobao']['taobao']?></p>
        <?php $arr_data['taobao']['pics'] = explode(',',$arr_data['taobao']['pics']) ?>
        <p>
            <span><b>淘宝账号截图：</b></span>
            <div style="clear: both;"></div>
            <a id="example2-1" title="淘宝账号截图" href="<?=$arr_data['taobao']['pics']['0']?>" style="width: 50px;height: 50px;float: left;padding: 0px;margin-right: 10px"><img src="<?=$arr_data['taobao']['pics']['0']?>" style="width: 50px;height: 50px" class="exmpic"></a>
            <a id="example2-1" title="个人资料截图" href="<?=$arr_data['taobao']['pics']['1']?>" style="width: 50px;height: 50px;float: left;padding: 0px;margin-right: 10px"><img src="<?=$arr_data['taobao']['pics']['1']?>" style="width: 50px;height: 50px" class="exmpic"></a>
            <div style="clear: both;"></div>
        </p>
        <p>
            <span><b>淘宝三要素截图：</b></span>
            <div style="clear: both;"></div>
            <?php $arr_data['main']['check_pics'] = explode(',',$arr_data['main']['check_pics']); ?>
            <?php if(is_array($arr_data['main']['check_pics'])) { foreach($arr_data['main']['check_pics'] as $k2 => $v2) { ?>
                <?php if($k2==0) { ?>
                    <a id="example2-1" title="" href="<?=$v2?>" style="width: 50px;height: 50px;float: left;padding: 0px;margin-right: 10px"><img src="<?=$v2?>" style="width: 50px;height: 50px" class="exmpic"></a>
                <?php } elseif($k2>0) { ?>
                    <?php if($k2==3) { ?>
                    <a id="example2-1" title="" href="<?=$v2?>" style="width: 50px;height: 50px;float: left;padding: 0px"><img src="<?=$v2?>" style="width: 50px;height: 50px" class="exmpic"></a>
                    <div style="clear: both;height:10px"></div>
                    <?php } else { ?>
                    <a id="example2-1" title="" href="<?=$v2?>" style="width: 50px;height: 50px;float: left;padding: 0px"><img src="<?=$v2?>" style="width: 50px;height: 50px" class="exmpic"></a>
                    <?php } ?>
                <?php } ?>
            <?php } } ?>
        </p>
    
    </div>
</div>
<!--]] 列表 -->
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/shop/shop_order.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script src="/mat/dist/js/lib/jquery.imgbox.js"></script>
<script type="text/javascript">
shop_order_detail._init();
shop_order_shenhe._init();
shop_order_confirm._init();
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
</html><?php ob_out();?>