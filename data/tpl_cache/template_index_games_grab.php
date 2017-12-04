<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/games_grab|template/index/header|template/index/footer_menu|template/index/footer', '1511454221', 'template/index/games_grab');?><!DOCTYPE html>
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
<div class="hed_lf"><a href="/game"><i class="icon iconfont icon-sanjiao"></i></a></div>
<div class="hed_ct">任务领取</div>
</div>
<!-- 头部end -->


<!-- container -->
<div class="container">
<div class="games_list">
<form id="J_send_form" action="/game/get_task" method="post" target="ajaxframeid">
        <input class="J_callback" type="hidden" name="callback" value="parent.send_success">
        <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
        <input type="hidden" name="item_id" value="<?=$_GET['item_id']?>">
<div class="grad">
<div class="rows_list">
<ul>
<li>
<h6><?=$arr_data['data_kouling_1']?></h6>
<textarea id="J_des_1" style="display:none;"><?=$arr_data['data_kouling_1']?></textarea>
<p class="h4">复制淘口令在手机淘宝打来<a href="javascript:;" id="J_des_1_btn" class="J_copy_btn">复制</a></p>
<div class="up_img">
<dl>
<dd>
<a href="javascript:void(0);" class="input"><i class="icon iconfont icon-jia1"></i>
<input type="file" id="file" class="J_file">
<input type="hidden" name="pic_url[1]" value="<?=$arr_member_taobao['pics']['0']?>" class="J_file_url">
</a>
<div class="my-3" id="preview"></div>
</dd>
<div class="clearfix"></div>
</dl>
</div>
</li>
<li>
<h6><?=$arr_data['data_kouling_2']?></h6>
<textarea id="J_des_2" style="display:none;"><?=$arr_data['data_kouling_2']?></textarea>
<p class="h4">复制淘口令在手机淘宝打来，上中下三张截图<a  href="javascript:;" id="J_des_2_btn" class="J_copy_btn">复制</a></p>
<div class="up_img">
<dl>
<dd>
<a href="javascript:void(0);" class="input"><i class="icon iconfont icon-jia1"></i>
<input type="file" id="file" class="J_file">
<input type="hidden" name="pic_url[2]" value="<?=$arr_member_taobao['pics']['0']?>" class="J_file_url">
</a>
<div class="my-3" id="preview"></div>
</dd>
<dd>
<a href="javascript:void(0);" class="input"><i class="icon iconfont icon-jia1"></i>
<input type="file" id="file" class="J_file">
<input type="hidden" name="pic_url[3]" value="<?=$arr_member_taobao['pics']['0']?>" class="J_file_url">
</a>
<div class="my-3" id="preview"></div>
</dd>
<dd>
<a href="javascript:void(0);" class="input"><i class="icon iconfont icon-jia1"></i>
<input type="file" id="file" class="J_file">
<input type="hidden" name="pic_url[4]" value="<?=$arr_member_taobao['pics']['0']?>" class="J_file_url">
</a>
<div class="my-3" id="preview"></div>
</dd>
<div class="clearfix"></div>
</dl>
</div>
</li>
<li>
<h6><?=$arr_data['data_kouling_3']?></h6>
<textarea id="J_des_3" style="display:none;"><?=$arr_data['data_kouling_3']?></textarea>
<p class="h4">复制淘口令在手机淘宝打来，上中下三张截图<a href="javascript:;" id="J_des_3_btn" class="J_copy_btn">复制</a></p>
<div class="up_img">
<dl>
<dd>
<a href="javascript:void(0);" class="input"><i class="icon iconfont icon-jia1"></i>
<input type="file" id="file" class="J_file">
<input type="hidden" name="pic_url[5]" value="<?=$arr_member_taobao['pics']['0']?>" class="J_file_url">
</a>
<div class="my-3" id="preview"></div>
</dd>
<dd>
<a href="javascript:void(0);" class="input"><i class="icon iconfont icon-jia1"></i>
<input type="file" id="file" class="J_file">
<input type="hidden" name="pic_url[6]" value="<?=$arr_member_taobao['pics']['0']?>" class="J_file_url">
</a>
<div class="my-3" id="preview"></div>
</dd>
<dd>
<a href="javascript:void(0);" class="input"><i class="icon iconfont icon-jia1"></i>
<input type="file" id="file" class="J_file">
<input type="hidden" name="pic_url[7]" value="<?=$arr_member_taobao['pics']['0']?>" class="J_file_url">
</a>
<div class="my-3" id="preview"></div>
</dd>
<div class="clearfix"></div>
</dl>
</div>
</li>
</ul>
</div>
<div class="tijiao" id="J_send"><a href="javascript:;">提交</a></div>
</div>
</form>
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
<script src="/mat/dist/js/lib/clipboard/clipboard.min.js"></script> 
<script src="/mat/dist/js/index/game.js"></script> 
<script type="text/javascript" src="/mat/dist/js/upload/lrz.bundle.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>

<script>
game._init();
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
<script>
var arr_init_copy = new Array();
$('.J_copy_btn').click(function(){
var _this = $(this);
var str_btn_id = _this.attr('id');
var str_des_id = str_btn_id.replace('_btn','');
if(typeof(arr_init_copy[str_btn_id])!='undefined'){
return false;	
}
arr_init_copy[str_btn_id] = 1;
var clipboard = new Clipboard('#'+str_btn_id, {
text: function() {
var str_des = $('#'+str_des_id).html();
return str_des;
}
});
clipboard.on('success', function(e) {
$('#'+str_btn_id).html('文字复制成功');
});

clipboard.on('error', function(e) {
alert('复制失败，请长按文字，选择复制');
});				
});

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