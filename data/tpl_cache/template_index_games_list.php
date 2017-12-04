<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/games_list|template/index/header|template/index/footer_menu|template/index/footer', '1511798557', 'template/index/games_list');?><!DOCTYPE html>
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

<!-- 头部 -->
<div class="header">
<div class="hed_lf"><a href="/"><i class="icon iconfont icon-sanjiao"></i></a></div>
<div class="hed_ct">任务列表</div>
</div>
<!-- 头部end -->
<!-- container -->
<div class="container">
<div class="games_list">
<p class="h3"><i class="icon iconfont icon-jinggao"></i>请确认手机淘宝APP登录的账号为：<span><?=$arr_taobao['taobao']?></span>，用错淘宝账号接单，任务奖励将不发并处罚扣除0.2金币，接单后2小时内必须完成，否则平台会扣除0.2金币作为处罚。</p>
<div class="rows">
<dl>
<dd>
<select name="" id="J_select_cid">
<option value="">任务类型</option>
<option value="1" <?php if($int_cid==1) { ?>selected<?php } ?>>付款任务</option>
<option value="2" <?php if($int_cid==2) { ?>selected<?php } ?>>流量任务</option>
</select>
</dd>
<dd><a href="/game/sort-<?php if(!$int_sort || $int_sort==2) { ?>1<?php } else { ?>2<?php } ?>">金币<i class="icon iconfont icon-paixu"></i></a></dd>
<div class="clearfix"></div>
</dl>
<div class="rows_list">
<?php if($arr_list['total']>0) { ?>
<ul>
<?php if(is_array($arr_list['list'])) { foreach($arr_list['list'] as $v) { ?>
<li>
<h3>【<?=$arr_type_name[$v['shop_type']]?><?php if($v['cid_1']==1) { ?>付款任务】<?php } else { ?>流量任务】<?php } ?><span>￥<?=$v['total_price_2']?></span></h3>
<h5>商品名：<?php echo mb_substr($v['item_title'],0,2).'****' ?></h5>
<h5>价格：<?=$v['price']?></h5>
<h5>店铺名：<?php echo mb_substr($v['shop']['shopname'],0,2).'****' ?></h5>
<p class="h4"><a href="/game/get_task/item_id-<?=$v['item_id']?>">立即领取</a></p>
</li>
<?php } } ?>
<!-- <li>
<h3>浏览[￥0.60]<span>【评价点赞】</span></h3>
<h5>商品名：加绒***</h5>
<h5>价格：69.00</h5>
<h5>店铺名：幻想***</h5>
<p class="h4"><a href="#">立即抢单</a></p>
</li>
<li>
<h3>浏览+收藏商品+加购[￥0.60]</h3>
<h5>商品名：加绒***</h5>
<h5>价格：69.00</h5>
<h5>店铺名：幻想***</h5>
<p class="h4"><a href="#">立即抢单</a></p>
</li> -->
</ul>
<?php } else { ?>
<p style="padding: 10px;text-align: center">本时段任务已抢完，请<?php echo date('H:00',time()+3600) ?>再来吧！</p>
<?php } ?>
</div>
</div>
</div>
<div class="prompting" <?php if($int_order_num<1) { ?>style="display:none"<?php } ?>>
<div class="ceng">
<p class="h3">提示</p>
<p class="h5">您有<?=$int_order_num?>个任务尚未完成</p>
<p class="h4"><a href="javascript:;" id="J_ceng">确定</a></p>
</div>
</div>
<div class="prompting" id="prompting2" <?php if($arr_list['total']>0) { ?>style="display:none"<?php } ?>>
<div class="ceng">
<p class="h3">提示</p>
<p class="h5">本时段任务已抢完，请<?php echo date('H:00',time()+3600) ?>再来吧！</p>
<p class="h4"><a href="javascript:;" id="J_ceng2">确定</a></p>
</div>
</div>
</div>
<!-- /container -->


<!-- footer -->
<div id="footer"></div>
<div class="footer">
        <ul>
                <li <?php if($_GET['mod']=='index') { ?>class="active"<?php } ?>><a href="/"><i class="icon iconfont icon-shouye-copy-copy-copy"></i>首页</a></li>
                <li <?php if($_GET['mod']=='game') { ?>class="active"<?php } ?>><a href="/game"><i class="icon iconfont icon-youxituijian"></i>任务</a></li>
                <li <?php if($_GET['mod']=='find') { ?>class="active"<?php } ?>><a href="/find"><i class="icon iconfont icon-weibiaoti-"></i>发现</a></li>
                <li <?php if($_GET['mod']=='i') { ?>class="active"<?php } ?>><a href="/i"><i class="icon iconfont icon-piaoliusanicon-geren-"></i>我的</a></li>
                <div class="clearfix"></div>
        </ul>
</div>
<!-- footer end -->




<script src="/mat/dist/js/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="/mat/dist/js/index/idangerous.swiper.min.js"></script> 
<script>
var tabsSwiper = new Swiper('.swiper-container',{
speed:500,
onSlideChangeStart: function(){
$(".tabs .active").removeClass('active');
$(".tabs a").eq(tabsSwiper.activeIndex).addClass('active');
}
});

$(".tabs a").on('touchstart mousedown',function(e){
e.preventDefault()
$(".tabs .active").removeClass('active');
$(this).addClass('active');
tabsSwiper.swipeTo($(this).index());
});

$(".tabs a").click(function(e){
e.preventDefault();
});
$('#J_ceng').click(function(){
$('.prompting').hide()
})
$('#J_ceng2').click(function(){
$('#prompting2').hide()
})
$('#J_select_cid').change(function(){
var cid = $(this).val();
window.location.href='/game/cid-'+cid
})
</script>
<?php $arr_dingwei = unserialize(get_cooKie('district')); ?>
<?php if(!$arr_dingwei['lat'] && verify_browser()=='weixin') { ?>
<?php $obj_weixinapi = L::loadClass('weixinapi');
$str_noncestr = rand(1000, 9999);
$str_weixin_jsapi_signature = $obj_weixinapi->get_jsapi_signature('http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], $str_noncestr); ?>
<script src="/mat/dist/js/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="/mat/dist/js/modules/common.js" type="text/javascript"></script>
<script src="/mat/dist/js/language/zh_cn.js" type="text/javascript"></script>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
wx.config({
    debug:false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '<?=weixin_login::APPID?>', // 必填，公众号的唯一标识
    timestamp: <?=$_SGLOBAL['timestamp']?>, // 必填，生成签名的时间戳
    nonceStr: '<?=$str_noncestr?>', // 必填，生成签名的随机串
    signature: '<?=$str_weixin_jsapi_signature?>',// 必填，签名，见附录1
    jsApiList: ['openLocation','getLocation'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});
wx.ready(function(){
        wx.getLocation({
                type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
                success: function (res) {
                        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                        var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                        var speed = res.speed; // 速度，以米/每秒计
                        var accuracy = res.accuracy; // 位置精度
                        var latLng = new qq.maps.LatLng(latitude, longitude);
                        geocoder.getAddress(latLng);
                        // set_dingwei(res.latitude,res.longitude)
                }
        });
        
        geocoder = new qq.maps.Geocoder({
        complete : function(result){
                var str_city = result.detail.addressComponents.city;
                var str_district = result.detail.addressComponents.district;
                var str_lat = result.detail.location.lat;
                var str_lng = result.detail.location.lng;
                console.log(result);
                var _data = new Object();
                _data.city = str_city;
                _data.district = str_district;
                _data.lat = str_lat;
                _data.lng = str_lng;
                console.log(_data);
                _ajax_jsonp('/index/set_dingwei',set_dingwei,_data);
        }
    });
         function set_dingwei(_data){
                        console.log(_data)
                        if (_data['status']==200) {
                                $('.dingwei_city').text(_data['data']);
                        }

         }
        
});


</script>
<?php } ?>
</body>

</html><?php ob_out();?>