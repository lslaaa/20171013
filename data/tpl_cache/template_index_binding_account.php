<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/binding_account|template/index/header|template/index/footer_menu|template/index/footer', '1511601917', 'template/index/binding_account');?><!DOCTYPE html>
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
<div class="hed_lf"><a href="/"><i class="icon iconfont icon-sanjiao"></i></a></div>
<div class="hed_ct">绑定账号</div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="container">
<form id="J_send_form" action="/account/binding_account" method="post" target="ajaxframeid">
        <input class="J_callback" type="hidden" name="callback" value="parent.send_success">
        <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
<div class="binding_account">
<ul>
<li><label>账号类型：</label>
<select name="type" id="J_type" style="height: 30px;font-size: 16px;border:none;width: 60%">
<option value="1" <?php if($int_type==1) { ?>selected<?php } ?>>淘宝</option>
<option value="2" <?php if($int_type==2) { ?>selected<?php } ?>>京东</option>
<option value="3" <?php if($int_type==3) { ?>selected<?php } ?>>蘑菇街</option>
<option value="4" <?php if($int_type==4) { ?>selected<?php } ?>>拼多多</option>
</select>
</li>
<li><label>账号：</label><input name="taobao" id="J_taobao" placeholder="请输入账号" value="<?=$arr_member_taobao['taobao']?>" /></li>
<li><label>性 别</label><span><a id="sex_1" href="javascript:;" class="sex" data-sex="1"><i class="icon iconfont <?php if($arr_member_taobao['sex']==1 || !$arr_member_taobao['sex']) { ?>icon-dui active<?php } else { ?>icon-yuanquan<?php } ?>"></i>男</a><a id="sex_2" href="javascript:;" class="sex" data-sex="2"><i class="icon iconfont <?php if($arr_member_taobao['sex']==2) { ?>icon-dui active<?php } else { ?>icon-yuanquan<?php } ?>"></i>女</a></span></li>
<input type="hidden" name="sex" id="J_sex" value="1">
</ul>
<p class="beiz">绑定的任务帐号和性别，必须与提交的截图上现实的会员名、性别保持一致！（会员名不能是手机号或者邮箱）</p>
<div class="scre_enshot">
<p class="h3">上传手机账号截图：<a href="/page/p-6" id="J_jietu">截图示例</a></p>
<div class="up_img">
<dl>
<dd>
<a href="javascript:void(0);" class="input"><i class="icon iconfont icon-jia1" style="background-image: url(<?=$arr_member_taobao['pics']['0']?>);background-size: 120px;background-repeat: no-repeat;"></i>我的<font class="str_type">淘宝</font>截图
<input type="file" id="file" class="J_file">
<input type="hidden" name="pic_url[1]" value="<?=$arr_member_taobao['pics']['0']?>" class="J_file_url">
</a>
<div class="my-3" id="preview"></div>
</dd>
<dd>
<a href="javascript:void(0);" class="input"><i class="icon iconfont icon-jia1" style="background-image: url(<?=$arr_member_taobao['pics']['1']?>);background-size: 120px;background-repeat: no-repeat;"></i>个人资料截图
<input type="file" id="file" class="J_file">
<input type="hidden" name="pic_url[2]" value="<?=$arr_member_taobao['pics']['1']?>" class="J_file_url">
</a>
<div class="my-3" id="preview"></div>
</dd>
<div class="clearfix"></div>
</dl>
</div>
</div>
<p class="zhuy">注意：您绑定的淘宝帐号，需满足注册时间超一个月，完成支付宝实名认证，淘气值400以上等条件；提交后平台将在1个工作日内完成审核！</p>
<div class="tijiao" id="J_send"><a href="javascript:;">申请绑定</a></div>
</div>
</form>
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
<script type="text/javascript" src="/mat/src/js/index/account.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript" src="/mat/dist/js/upload/lrz.bundle.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>

<script type="text/javascript">
account._init();
$(function(){
var type = $('#J_type').val();
if (type!=1) {
$('.zhuy').hide()
if (type==2) {
$('.str_type').text('京东')
$('#J_jietu').attr('href','/page/p-7')
}else if(type==3){
$('.str_type').text('蘑菇街')
$('#J_jietu').attr('href','/page/p-8')
}else if(type==4){
$('.str_type').text('拼多多')
$('#J_jietu').attr('href','/page/p-9')
}
}else{
$('.str_type').text('淘宝')
$('#J_jietu').attr('href','/page/p-6')
$('.zhuy').show()
}
})

$('#J_type').change(function(){
var type = $(this).val();
window.location.href='/account/binding_account/type-'+type
})

$('#sex_1').click(function(){
var sex = $(this).attr('data-sex');
$('#J_sex').val(sex);
$('.icon-dui').addClass('icon-yuanquan')
$('.icon-dui').removeClass('active')
$('.icon-dui').removeClass('icon-dui')
$(this).find('i').addClass('icon-dui')
$(this).find('i').addClass('active')
$(this).find('i').removeClass('icon-yuanquan')
})
$('#sex_2').click(function(){
var sex = $(this).attr('data-sex');
$('#J_sex').val(sex);
$('.icon-dui').addClass('icon-yuanquan')
$('.icon-dui').removeClass('active')
$('.icon-dui').removeClass('icon-dui')
$(this).find('i').addClass('icon-dui')
$(this).find('i').addClass('active')
$(this).find('i').removeClass('icon-yuanquan')
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