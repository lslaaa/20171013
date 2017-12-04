<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/index|template/index/header|template/index/footer_menu|template/index/footer', '1512312927', 'template/index/index');?><!DOCTYPE html>
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


<body>
<!-- 头部 -->
<div class="header">
<div class="hed_lf">账户余额：<?php if($_SGLOBAL['member']) { ?><?php echo $_SGLOBAL['member']['balance']+$_SGLOBAL['member']['commission'] ?><?php } else { ?>0.00<?php } ?></div>
<div class="hed_rg">今日收入：<?php if($float_money) { ?><?=$float_money?><?php } else { ?>0.00<?php } ?></div>
</div>
<!-- 头部end -->
<!-- banner -->
<div class="main_visual" id="J_hot_pic">
<div class="flicking_con">
<div class="flicking_inner" id="J_banner_dot">
<?php if(is_array($arr_banner['list'])) { foreach($arr_banner['list'] as $v) { ?>
<a href="javascript:;">1</a>
<?php } } ?>
</div>
</div>
<div class="main_image">
<ul>
<?php if(is_array($arr_banner['list'])) { foreach($arr_banner['list'] as $v) { ?>
<li class="J_banner">
<a href="<?=$v['url']?>" style="background:url(<?=$v['pic']?>) no-repeat center; display:block;background-size:100% 100%">&nbsp;</a>
</li>
<?php } } ?>
</ul>
<a href="#" id="btn_prev"></a>
<a href="#" id="btn_next"></a>
</div>
</div>
<!-- banner end -->
<!-- container -->
<div class="container">
<div class="sy_top">
<ul>
<li><a href="/find/news"><i class="icon icon_a iconfont icon-xinwenzixun"></i>新闻公告</a></li>
<li><a href="/page/p-3"><i class="icon icon_b iconfont icon-faya"></i>新手教程</a></li>
<li><a href="javascript:;"  class="J_qrcode"><i class="icon icon_c iconfont icon-yaoqinghaoyou"></i>邀请好友</a></li>
<li><a href="http://wpa.qq.com/msgrd?v=3&uin=<?=$_SGLOBAL['data_config']['data_qq']?>&Menu=yes"><i class="icon icon_d iconfont icon-kefu"></i>在线客服</a></li>
<div class="clearfix"></div>
</ul>
<div class="bangd"><a href="/account/binding_account">绑定账号</a></div>
<?php if($_SGLOBAL['taobao']['id']) { ?>
<div class="yi_bangd">
<dl>
<dt>已绑定帐号</dt>
<dd><a href="/game"><?=$_SGLOBAL['taobao']['taobao']?><span><label>开始接单(<?=$_SGLOBAL['today_order']?>/<?=$_SGLOBAL['data_config']['data_order_day']?>）</label><i class="icon iconfont icon-yousanjiao-copy-copy"></i></span></a></dd>
</dl>
</div>
<?php } ?>
</div>
<div class="rankings">
<div class="rankings_h3">邀请好友赚奖励 <a href="javascript:;" class="J_qrcode">前去邀请></a></div>
<div class="wrap">
<div class="tabs">
<a href="#" hidefocus="true" class="active">日排行</a>
<a href="#" hidefocus="true">月排行</a>
<a href="#" hidefocus="true">总排行</a>
</div>    
<div class="swiper-container">
<div class="swiper-wrapper">
<div class="swiper-slide">
   	<div class="content-slide">
  	<ul>
  		<?php if(is_array($arr_yaoqing1)) { foreach($arr_yaoqing1 as $v) { ?>
  	<li><?php echo mb_substr($v['member']['phone'],0,4).'****'.mb_substr($v['member']['phone'],8,4) ?><span><?=$v['total']?>人</span></li>
  	<?php } } ?>
  	</ul>
  	</div>
  	</div>
<div class="swiper-slide">
<div class="content-slide">
<ul>
<?php if(is_array($arr_yaoqing2)) { foreach($arr_yaoqing2 as $v) { ?>
  	<li><?php echo mb_substr($v['member']['phone'],0,4).'****'.mb_substr($v['member']['phone'],8,4) ?><span><?=$v['total']?>人</span></li>
  	<?php } } ?>
</ul>
</div>
  </div>
<div class="swiper-slide">
<div class="content-slide">
<ul>
<?php if(is_array($arr_yaoqing)) { foreach($arr_yaoqing as $v) { ?>
  	<li><?php echo mb_substr($v['member']['phone'],0,4).'****'.mb_substr($v['member']['phone'],8,4) ?><span><?=$v['total']?>人</span></li>
  	<?php } } ?>
</ul>
</div>
  </div>
  </div>
   </div>
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
<script src="/mat/dist/js/modules/common.js" type="text/javascript"></script>
<script src="/mat/dist/js/language/zh_cn.js" type="text/javascript"></script>
<script src="/mat/dist/js/index/idangerous.swiper.min.js"></script> 
<script src="/mat/dist/js/index/index.js"></script> 
<script>
index_banner._init()
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
</script>
<script type="text/javascript">
var int_success = <?php if($_SGLOBAL['success_order']) { ?><?=$_SGLOBAL['success_order']?><?php } else { ?>0<?php } ?>;
var int_login = <?php if($_SGLOBAL['member']) { ?><?=$_SGLOBAL['member']['uid']?><?php } else { ?>0<?php } ?>;
$('.J_qrcode').click(function(){
if (int_login==0) {
window.location.href='/login'
return false
}
if (int_success>=<?=$_SGLOBAL['data_config']['data_success_order']?>) {
location.href='/i/qrcode';
}else{
make_alert_html('提示','请先完成<?=$_SGLOBAL['data_config']['data_success_order']?>个任务');
}
})
</script>
</body>
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