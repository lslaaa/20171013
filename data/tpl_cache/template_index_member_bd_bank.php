<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/member/bd_bank|template/index/header|template/index/footer', '1511601866', 'template/index/member/bd_bank');?><!DOCTYPE html>
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
<div class="hed_lf"><a href="/i"><i class="icon iconfont icon-sanjiao"></i></a></div>
<div class="hed_ct">绑定银行卡</div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="container">
<form id="J_send_form" action="/i/bd_bank" method="post" target="ajaxframeid">
    	<input class="J_callback" type="hidden" name="callback" value="parent.send_success">
    	<input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
<div class="bd_alipay">
<p class="h5">请绑定您的银行卡信息，用于提现使用</p>
<ul>
<li><span>开户行：</span><input name="bank" id="J_bank" value="<?=$arr_member['bank']?>" placeholder="请输入姓名" onkeyup="bank_list('J_bank',this.value)" style="width: 70%;" />
<div class="bank_box" style="display: none">
<ul>
<li>中国工商银行</li>
</ul>
</div>
</li>
<li><span>银行卡账号：</span><input name="bank_card" id="J_bank_card" value="<?=$arr_member['bank_card']?>" placeholder="请输入姓名" style="width: 65%;" /></li>
<li><span>银行卡姓名：</span><input name="bank_card_name" id="J_bank_card_name" value="<?=$arr_member['bank_card_name']?>" placeholder="请输入账号" style="width: 65%;" /></li>
</ul>
<div class="and_login"><a href="javascript:;" id="J_send">绑定</a></div>	
</div>
</form>
</div>
<!-- /container -->


<!-- footer -->
<!-- footer end -->
<script src="/mat/dist/js/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="/mat/dist/js/modules/common.js" type="text/javascript"></script>
<script src="/mat/dist/js/language/zh_cn.js" type="text/javascript"></script>
<script src="/mat/dist/js/index/idangerous.swiper.min.js"></script> 
<script src="/mat/dist/js/index/member_account.js"></script> 
<script type="text/javascript">
bd_bank._init()
function bank_list(obj,val){
var reg = new RegExp("[\\u4E00-\\u9FFF]+","g");
　　	if(reg.test(val)){     
$.post(
'/i/bank_list',
{'name':val},
function(data){
console.log(data)
var _data = eval("("+data+")")
$('.bank_box li').remove()
$('.bank_box ul').append('<li>'+val+'</li>')
for (var i = 0; i < _data.length; i++) {
$('.bank_box ul').append('<li>'+_data[i].name+'</li>')
}
$('.bank_box').show() 

$('.bank_box li').click(function(){
var name = $(this).text()
$('#J_bank').val(name)
$('.bank_box').hide() 
})
}

)
　　	}
if (!val) {
$('.bank_box').hide() 
}
}
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