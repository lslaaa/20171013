<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/money/recharge_pay|template/admin/header|template/admin/footer', '1511608216', 'template/shop/money/recharge_pay');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<link href="/lib/spay/personalprod.transfer-1.6.css" rel="stylesheet" type="text/css"  />
<script src="/lib/spay/jquery-1.7.2.min.js"></script>
<script src="/lib/spay/layer/layer.js"></script>
<script type="text/javascript">
layer.config({extend:'skin/moon/style.css'});
//在引入css的基础上配置skin参数，如下所示
layer.config({
skin:'layer-ext-moon',
extend:'skin/moon/style.css'
});
</script>
<script src="/lib/spay/ZeroClipboard.min.js"></script>
<script type="text/javascript">
var queryStatusPayNotify = function(){
$.ajax({
  type: 'POST',
  url: "/index.php?mod=money&mod_dir=money&extra=recharge_chek",
  data: {'order_no': '<?=$order_no?>'},
  dataType: "json",
  success: function(obj){
if(obj.status==1){			
window.clearInterval(ctInterval);
window.clearInterval(qrInterval);
// alert('充值成功');
window.location='/index.php?mod=money&mod_dir=money&extra=recharge_list'; 
}
if(obj.code==99){			
window.clearInterval(ctInterval);
            window.clearInterval(qrInterval);
            $("#count").html('充值已经过期，请重新获取充值二维码');
layer.alert('充值已经过期，请重新获取充值二维码', {title:'充值提示', icon: 1, area: '580px'});

}
  }
});
}
var qrInterval;
window.clearInterval(qrInterval);
qrInterval = window.setInterval(function () {
queryStatusPayNotify();
}, 1000 * 5);
queryStatusPayNotify();
</script>
<script type="text/javascript">
var ctInterval;
function runCount(){
    $("#count").html($("#count").html() * 1 + 1);
if($("#count").html()>180)
{
window.clearInterval(ctInterval);
window.clearInterval(qrInterval);
 $("#count").html('充值已经过期，请重新获取充值二维码');
}
}
ctInterval = window.setInterval("runCount();", 1000);
</script>
<script>
$(document).ready(function(){
  $(".ui-tab4-trigger-item").click(function(){	
$(".ui-tab4-trigger-item").removeClass('ui-tab4-trigger-item-current');
$(this).addClass('ui-tab4-trigger-item-current');
$('.content-box').addClass('fn-hide');
$("#"+$(this).attr('data-box-target')).removeClass('fn-hide');
  });

  var clip = new ZeroClipboard($('.clip_button'),{ 
    moviePath: '/lib/spay/ZeroClipboard.swf' 
  });
  
  clip.on('ready', function(){
    this.on('aftercopy', function(event){
$(event.target).next(".clip_tips").html("复制成功【" + event.data['text/plain'] + "】");
    	//alert("复制成功【" + event.data['text/plain'] + "】");
    });
  });
  
  
});
</script>
</head>
<body>
<div class="ui-grid-22 ui-grid-center">
  <a href="/shop.php?mod=money&extra=add_recharge" style="padding:10px 20px;border-radius:2px;border:1px solid #ddd;" class="gray_nav">返回</a><br><br>
  <div class="content payment">
    <div class="page-head">
      <div class="ui-tab4">
        <ul class="ui-tab4-trigger fn-clear">
  <!--li class="ui-tab4-trigger-item  <?php if($type==1) { ?>  ui-tab4-trigger-item-current <?php } ?>" data-box-target="box-1"> <a class="ui-tab4-trigger-text needIframe" href="javascript:void(0);">微信转账付款</a> <div class="news"></div></li-->
  <li class="ui-tab4-trigger-item <?php if($type==2) { ?> ui-tab4-trigger-item-current <?php } ?>"" data-box-target="box-2"> <a class="ui-tab4-trigger-text needIframe" href="javascript:void(0);">支付宝手机扫码付款</a> </li>
  <li class="ui-tab4-trigger-item" data-box-target="box-3"> <a class="ui-tab4-trigger-text needIframe" href="javascript:void(0);">电脑支付宝转账付款</a> </li>
        </ul>
      </div>
    </div>
    <div class="out-border">


 <div id="box-1" class="content-box <?php if($type!=1) { ?> fn-hide <?php } ?>">
        <div class="ui-tiptext-container2">
    <p class="ui-tiptext-message2 ui-tiptext-red " style="font-size:12px;font-weight:normal;color:#ff0000;height:auto;"><b style="color:#FF0000;">请在三分钟之内完成充值，否则二维码过期,手机充值按住图片保存 然后打开微信扫描相册</b></p>
          <p class="ui-tiptext-message2 ui-tiptext-red " style="font-size:12px;font-weight:normal;color:#ff0000;height:auto;">然后请按右图所示内容填写付款金额，发起微信扫码，没有按图下的说明操作将不能自动到账！</p>
        </div>
<div class="fn-clear" style="margin:10px 0;">
  <div style="float:left;">
<img width="292" height="292" src="/mat/dist/images/shop/paycode.jpg">
  </div>
  <div class="arrow">			
  </div>
  <div class="weixinphone">
<div class="payAmount"><?=$money?></div>
<div class="title2">一定要付以上显示金额，不然不会自动到账</div>
  </div>
        </div>
      </div>	



  <div id="box-2" class="content-box <?php if($type!=2) { ?> fn-hide <?php } ?>">
        <div class="ui-tiptext-container2">
    <p class="ui-tiptext-message2 ui-tiptext-red " style="font-size:12px;font-weight:normal;color:#ff0000;height:auto;"><b style="color:#FF0000;">请在三分钟之内完成充值，否则二维码过期,手机充值按住图片保存 然后打开支付宝扫描相册</b></p>
          <p class="ui-tiptext-message2 ui-tiptext-red " style="font-size:12px;font-weight:normal;color:#333;height:auto;"> 使用手机支付宝扫码后请按右图所示内容填写付款金额和备注，否则将不能自动到账，谢谢！</p>
        </div>
<div class="fn-clear" style="margin:10px 0;">
  <div style="float:left;">
<img width="292" height="292" src="/mat/dist/images/shop/paycode.jpg">
  </div>
  <div class="arrow">			
  </div>
  <div class="phone">
<div class="payAmount"><?=$money?></div>
<div class="title2">一定要付以上显示金额，不然不会自动到账</div>
  </div>
        </div>
      </div>

  
      <div id="box-3" class="content-box fn-hide">
        <div class="ui-tiptext-container2">
  		    <p class="ui-tiptext-message2 ui-tiptext-red " style="font-size:12px;font-weight:normal;color:#ff0000;height:auto;"><b style="color:#FF0000;">请在三分钟之内完成充值，否则二维码过期</b></p>
          <p class="ui-tiptext-message2 ui-tiptext-red " style="font-size:12px;font-weight:normal;color:#333;height:auto;"> 请务必按照下面的内容填写到支付宝转账页面进行转账付款，否则将不能自动到账，谢谢！</p>
        </div>
        <div class="ui-form-input2">
          <fieldset>
          <div class="ui-form-item2 yui-ac">
            <label for="" class="ui-form-label2">收款人：谢俊晓</label>
            <input placeholder="邮箱地址/手机号码" readonly="true" value="13738023946" name="optEmail" maxlength="80" class=" fn-left i-text " seed="fz_input_fdemail" id="optEmail" isdisplay="true" ui-form-explain2="	" autocomplete="off"> <button class="clip_button button green" title="单击复制到剪贴板" data-clipboard-target="optEmail" data-clipboard-text="13738023946">复制</button> <span class="clip_tips"></span>
          </div>
          <div class="ui-form-item2" id="forAmount">
            <label class="ui-form-label2" for="amount">付款金额：</label>
            <input type="text" data-explain="" readonly="true" value="<?=$money?>" id="payAmount" class="i-text i-text-amount" maxlength="13" name="payAmount" seed="fk_input_money" ui-form-explain2="">元 <button class="clip_button button green" title="单击复制到剪贴板" data-clipboard-target="payAmount" data-clipboard-text="<?=$money?>">复制</button> <span class="clip_tips"></span>
  </div>
     
          </fieldset>
        </div>
        <div class="ui-form-item2 button-item fn-clear">
          <div class="ui-button ui-button-mblue"> <a hidefocus="hidefocus" href="https://auth.alipay.com/login/index.htm?goto=https://shenghuo.alipay.com/send/payment/fill.htm" target="_blank" class="ui-button-text">登录您的支付宝并开始转账...</a> </div>
        </div>
      </div>
    </div>
  </div>
  <div id="loading">亲，系统正在等待处理您的付款哦。请不要刷新页面，处理完成会自动为您显示的哦～<span id="count"></span>秒</div>
</div>
<script type="text/javascript" src="/mat/dist/js/lib/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/mat/dist/js/language/admin_zh_cn.js"></script>
<script type="text/javascript" src="/mat/dist/js/modules/common.js"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
// $('#J_add_level_one').click(function(){
//     make_alert_html_l('','<?=$arr_page_one['content']?>');
// });
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
</html><?php ob_out();?>