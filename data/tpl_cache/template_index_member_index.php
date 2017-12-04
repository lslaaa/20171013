<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/member/index|template/index/header|template/index/footer_menu|template/index/footer', '1512312940', 'template/index/member/index');?><!DOCTYPE html>
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
<div class="hed_lf"><i class="icon iconfont icon-xiaoxi" onclick="javascript:location.href='/i/alerts'"></i></div>
<div class="hed_rg"><i class="icon iconfont icon-shezhi" onclick="javascript:location.href='/account/perfecting_data'"></i></div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="center">
<div class="member">
<div class="mber_top">
<div class="mber_top_tux">
<p class="img"><img src="<?=$arr_member['face']?>" alt=""></p>
<p class="name"><i class="icon iconfont icon-gerenxinxi"></i><?php if($arr_member['realname']) { ?><?=$arr_member['realname']?><?php } elseif($arr_member['nickname']) { ?><?=$arr_member['nickname']?><?php } else { ?><?=$arr_member['phone']?><?php } ?></p>
<p class="jianj"><?php if($arr_member_detail['content']) { ?><?=$arr_member_detail['content']?><?php } else { ?>这个人很懒，什么都没有？<?php } ?></p>
</div>
<div class="member_bg"></div>
<ul>
<li>
<a href="/i/gold_detail">
<p class="h2"><?php if($float_money) { ?><?=$float_money?><?php } else { ?>0.00<?php } ?></p>
<p class="h3">今日收入</p>
</a>
</li>
<li>
<a href="/i/gold_detail">
<p class="h2"><?php echo $arr_member['balance']+$arr_member['commission'] ?></p>
<p class="h3">账户总额</p>
</a>
</li>
<div class="clearfix"></div>
</ul>
</div>
<div class="mber_list">
<ul>
<li><a href="/i/my_task"><i class="icon iconfont icon-tupian"></i>我的任务<span><i class="icon iconfont icon-yousanjiao-copy-copy"></i></span></a></li>
<li><a href="/account/perfecting_data"><i class="icon iconfont icon-gerenxinxi1"></i>个人信息<span><i class="icon iconfont icon-yousanjiao-copy-copy"></i></span></a></li>
<li><a href="/i/my_purse"><i class="icon iconfont icon-qianbao"></i>我的钱包<span><i class="icon iconfont icon-yousanjiao-copy-copy"></i></span></a></li>
<li><a href="/withdrawal"><i class="icon iconfont icon-qiandai"></i>金币提现<span><i class="icon iconfont icon-yousanjiao-copy-copy"></i></span></a></li>
<li><a href="/i/bd_bank"><i class="icon iconfont icon-zhifubaofukuan"></i>绑定银行卡<span><i class="icon iconfont icon-yousanjiao-copy-copy"></i></span></a></li>
<li><a href="/i/my_client"><i class="icon iconfont icon-kehu"></i>我的客户<span><i class="icon iconfont icon-yousanjiao-copy-copy"></i></span></a></li>
<li><a href="javascript:;" id="J_qrcode"><i class="icon iconfont icon-erweima"></i>个人二维码<span><i class="icon iconfont icon-yousanjiao-copy-copy"></i></span></a></li>
<li><a href="/i/security"><i class="icon iconfont icon-zhanghuanquan"></i>账户安全<span><i class="icon iconfont icon-yousanjiao-copy-copy"></i></span></a></li>
</ul>
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
<script type="text/javascript">
var int_success = <?=$_SGLOBAL['success_order']?>;
$('#J_qrcode').click(function(){
if (int_success>=<?=$_SGLOBAL['data_config']['data_success_order']?>) {
location.href='/i/qrcode';
}else{
make_alert_html('提示','请先完成<?=$_SGLOBAL['data_config']['data_success_order']?>个任务');
}
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