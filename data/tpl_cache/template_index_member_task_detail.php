<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/member/task_detail|template/index/header|template/index/footer', '1512315262', 'template/index/member/task_detail');?><!DOCTYPE html>
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
<div class="hed_lf"><a href="javascript:;" onclick="javascript:window.history.back(-1);"><i class="icon iconfont icon-sanjiao"></i></a></div>
<div class="hed_ct">任务帮</div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="container">
<form id="J_send_form" action="/i/send_task" method="post" target="ajaxframeid">
        <input class="J_callback" type="hidden" name="callback" value="parent.send_success">
        <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
        <input type="hidden" name="order_id" id="J_order_id" value="<?=$arr_order_one['main']['order_id']?>">
<div class="my_show_xq">
<?php if($arr_order_one['main']['status']==200) { ?>
<dl class="h3" style="height: auto">任务已完成</dl>
<?php } elseif($arr_order_one['main']['status']==404) { ?>
<dl class="h3" style="height: auto;border-bottom:0px;padding-bottom: 0px">审核失败</dl>
<dl class="h3" style="height: auto;padding-top: 0px;color: red">失败原因：<?=$arr_order_one['main']['fail_reason']?></dl>
<?php } else { ?>
<dl class="h3" style="height: auto">接单后<?php echo date('Y-m-d H:i:s',$arr_order_one['main']['end_date']) ?>必须完成，否则平台会作处罚！任务接手60分钟内可以取消，不扣K币<?php if($arr_order_one['main']['status']==100) { ?><a href="javascript:;" id="J_btn_cancel" style="display: block;width: 100%;text-align: right">取消任务</a><?php } ?></dl>
<?php } ?>
<dl>
<dt><?=$arr_type_name[$arr_shop['type']]?>商品<span>任务编号：<?=$arr_order_one['main']['order_id']?></span></dt>
<dd>
<img class="img" src="<?=$arr_order_one['item']['pic']?>" alt="商品图片">
<div class="txt">
<!-- <p>商品名：<?php if($arr_order_one['main']['status']==100) { ?>{eval echo mb_substr(<?=$arr_order_one['item']['item_title']?>,0,2).'****'}<?php } else { ?><?=$arr_order_one['item']['item_title']?><?php } ?></p> -->
<p>产品价格：<?=$arr_order_one['item']['price']?>元</p>
<p>店铺名：<?php if($arr_order_one['main']['status']==100) { ?><?php echo mb_substr($arr_order_one['shop']['shopname'],0,2).'****' ?><?php } else { ?><?=$arr_order_one['shop']['shopname']?><?php } ?></p>
</div>
<div class="clearfix"></div>
</dd>
</dl>
<p class="h4">如何找到商品?<span><a href="/page/p-4">图文教程</a></span></p>
<p class="h4">端口：<span><?php if($arr_order_one['item']['duankou']==1) { ?>手机<?php } else { ?>PC端<?php } ?></span></p>
<p class="h4">关键词：<span><?=$arr_order_one['main']['keyword']['keyword']?>（请购买<?=$arr_order_one['item']['buy_num']?>件）</span></p>
<p class="h4">淘口令：<?=$arr_order_one['item']['kouling']?></p>
<p class="h4">优惠券链接：<span><?=$arr_order_one['item']['coupon_link']?></span></p>
<p class="h6">请注意：
<?php if($arr_order_one['item']['huabei']) { ?>支持信用卡花呗，<?php } else { ?>不支持信用卡花呗，<?php } ?>
<?php if($arr_order_one['item']['wangwang']) { ?>需要与客服假聊<?php } else { ?>不用客服假聊<?php } ?>
<?php if($arr_order_one['item']['is_collect_item']) { ?>,需要收藏商品<?php } ?>
<?php if($arr_order_one['item']['is_collect_shop']) { ?>,需要收藏店铺<?php } ?>
<?php if($arr_order_one['item']['is_add_cart']) { ?>,需要加入购物车<?php } ?>
<?php if($arr_order_one['item']['remark']) { ?>,下单备注(<?=$arr_order_one['item']['remark']?>)<?php } ?>
</p>
<p class="h5">任务要求：
<?php if(is_array($arr_order_one['item']['require'])) { foreach($arr_order_one['item']['require'] as $k => $v) { ?>
                <?php if($k=='collect') { ?>
                <?=$arr_price_name[$v['val']]?>,
                <?php } ?>
                <?php if($k=='huobi') { ?>
                <?=$arr_price_name[$v['val']]?>
                <?php } ?>
            <?php } } ?>
        </p>
        <?php if($arr_order_one['item']['huobi_pic']) { ?>
        <p class="h5">指定货比：
<?php if(is_array($arr_order_one['item']['huobi_pic'])) { foreach($arr_order_one['item']['huobi_pic'] as $v) { ?>
<?php if($v['pic']) { ?>
<br><span style="width: 70%"><?=$v['shop_name']?></span><img src="<?=$v['pic']?>" style="width: 70px" alt="<?=$v['shop_name']?>">
<?php } ?>
<?php } } ?>
        </p>
        <?php } ?>
<?php if($arr_order_one['main']['comment']['comment']) { ?>
<p class="h5">指定评语：<?=$arr_order_one['main']['comment']['comment']?></p>
<?php } ?>
<?php if($arr_order_one['main']['comment_pic']['pics']) { ?>
<p class="h5">指定买家秀：
<?php if(is_array($arr_order_one['main']['comment_pic']['pics'])) { foreach($arr_order_one['main']['comment_pic']['pics'] as $v) { ?>
<img src="<?=$v?>" style="width: 80px">
<?php } } ?>
</p>
<?php } ?>
            	<span><a href="/page/p-5">查看示例截图</a></span></p>
<p class="h6">操作要求：<?=$arr_order_one['item_detail']['content']?></p>
<p class="h5">付款过程截图</p>
<div class="up_img">
<ul>
<?php if($arr_order_one['main']['pics']) { ?>
<?php if(is_array($arr_order_one['main']['pics'])) { foreach($arr_order_one['main']['pics'] as $v) { ?>
<li>
<a href="javascript:void(0);" class="input"><i class="icon iconfont icon-jia1" style="background-image: url(<?=$v?>);background-size: 120px;background-repeat: no-repeat;"></i><input type="file" id="file"  class="J_file"><input type="hidden" name="pic_url[]" value="<?=$v?>" class="J_file_url"></a>
<div class="my-3" id="preview"></div>
</li>
<?php } } ?>
<?php } else { ?>
<li>
<a href="javascript:void(0);" class="input"><i class="icon iconfont icon-jia1" style="background-image: url();background-size: 120px;background-repeat: no-repeat;"></i><input type="file" id="file"  class="J_file"><input type="hidden" name="pic_url[]" value="" class="J_file_url"></a>
<div class="my-3" id="preview"></div>
</li>
<li>
<a href="javascript:void(0);" class="input"><i class="icon iconfont icon-jia1" style="background-image: url();background-size: 120px;background-repeat: no-repeat;"></i><input type="file" id="file"  class="J_file"><input type="hidden" name="pic_url[]" value="" class="J_file_url"></a>
<div class="my-3" id="preview"></div>
</li>
<?php } ?>
<div class="clearfix" id="J_img_box"></div>
</ul>
</div>
<?php if($arr_order_one['main']['status']==105 || $arr_order_one['main']['status']==404) { ?>
<div class="and_login" id="J_send"><a href="javascript:;">提交任务</a></div>
<?php } ?>
</div>
</form>
</div>
<!-- /container -->


<!-- footer -->
<!-- footer end -->

<script src="/mat/dist/js/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="/mat/dist/js/modules/common.js" type="text/javascript"></script>
<script src="/mat/dist/js/language/zh_cn.js" type="text/javascript"></script>
<script type="text/javascript" src="/mat/src/js/index/i_task.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript" src="/mat/dist/js/upload/lrz.bundle.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
send_task._init();
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