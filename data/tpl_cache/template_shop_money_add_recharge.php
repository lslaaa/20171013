<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/money/add_recharge|template/shop/header|template/shop/footer', '1511885908', 'template/shop/money/add_recharge');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO</title>
<link rel="stylesheet" href="/mat/??dist/css/shop/global.css,dist/css/shop/mainstyle.css,dist/css/shop/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<style type="text/css">
    .shop-span{padding:8px 15px;border-radius: 2px;border:1px solid #ddd;background-color: #0580bb;color:white;}
    a{cursor:pointer;}
    .trade dl dd {
        float: left;
        width: 55%;
    }
    img{width: 100%}
</style>
<div style="width: 100%">
   
        <form id="J_send_form" action="/shop.php?mod=money&extra=add_recharge" method="post" target="ajaxframeid" enctype="Multipart/form-data">
        <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>"> 
        <input class="J_callback" type="hidden" name="callback" value="parent.send_success">   
        <input type="hidden" name="uid" value="<?=$arr_member['uid']?>">
        <input id="J_form_do" name="form_do" type="hidden" />
        <div class="form_list mt10 trade" style="width: 100%">
        <a href="javascript:history.back(-1)" style="padding:10px 20px;border-radius:2px;border:1px solid #ddd;" class="gray_nav">返回</a><br><br>

        <dl class="mt12">
        <dt><font style="font-size:18px">充值信息</font></dt>
        </dl>
        <div style="width: 40%;float: left">
            <dl class="mt12">
                <dt>充值方式:</dt>
                <dd style="text-align:left;width:55%">
                   <label> <input name="type" class="J_type"  value="1" type="radio" checked="checked"><a class="type">支付宝充值</a> </label>
                   <label> <input name="type" class="J_type"  value="3" type="radio"><a class="type">微信充值</a> </label>
                   <label> <input name="type" class="J_type"  value="2" type="radio"><a class="type">线下转账</a> </label>
                </dd>
            </dl>
            <dl class="mt12" style="display: none">
                <dt>用户名:</dt>
             <dd>
                 <input id="J_title" name="username" class="text_s" autocomplete="off" value="<?=$_SGLOBAL['member']['username']?>" style="width:200px;" type="text"><span class="tips red">*</span>
             </dd>
                 </dl>
            <dl class="mt12" style="display: none">
                <dt>联系人:</dt>
                <dd>
                    <input name="contacts" class="text_s" autocomplete="off" value="<?=$_SGLOBAL['member']['contacts']?>" style="width:200px;" type="text"><span class="tips red">*</span>
                </dd>
            </dl>
            <dl class="mt12" style="display: none">
                <dt>联系电话:</dt>
                <dd>
                    <input name="mobile" class="text_s" autocomplete="off" value="<?=$_SGLOBAL['member']['mobile']?>" style="width:200px;" type="text"><span class="tips red">*</span>
                </dd>
            </dl>

            <dl class="mt12">
                <dt>充值金额:</dt>
                <dd>
                    <input name="money" class="text_s" autocomplete="off" onkeyup="value=value.replace(/[^\d{1,}\.\d{1,}|\d{1,}]/g,'')" value="" style="width:200px;" type="text">
                </dd>
            </dl>
            <div style="display: none" id="J_bank_box">
            <dl class="mt12">
                <dt>开户行：</dt>
                <dd style="height:36px; line-height:36px;">
                    <select  name="bank" id="J_bank" style="height: 30px">
                        <option value="">请选择账号</option>
                        <?php if(is_array($arr_bank_list['list'])) { foreach($arr_bank_list['list'] as $v) { ?>
                            <option value="<?=$v['bank']?>" data-card="<?=$v['bank_card']?>" data-name="<?=$v['bank_card_name']?>"><?=$v['bank']?></option>
                        <?php } } ?>
                    </select>
                    <span class="tips red"><a href="/shop.php?mod=money&extra=add_bank_card">如没有账号请先添加</a></span>
                </dd>
            </dl>
            <dl class="mt12">
                <dt>银行卡账号：</dt>
                <dd style="height:36px; line-height:36px;">
                    <input type="text" class="text" name="bank_card" id="J_bank_card" value="" style="width: 300px" readonly="readonly" />
                    
                </dd>
            </dl>  
            <dl class="mt12">
                <dt>银行卡姓名：</dt>
                <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="bank_card_name" id="J_bank_card_name" value="" style="width: 300px" readonly="readonly" /></dd>
            </dl> 
            </div>
            <dl class="mt12" id="J_pic_box">
                <dt>转账截图:</dt>
                <dd style="position:relative;" id="J_img_add_dd">
                    <input type="hidden" name="data_pic" value="<?=$arr_data['data_pic']?>" id="J_pic_url" style="float:left;"/>
                    <input name="pic" id="J_pic" type="file" style="filter:alpha(opacity=0);-moz-opacity:0;opacity:0;width:100px;height:36px;position:absolute;left:0;top:0;cursor: pointer;float:left;">
                    <a class="upload_pic f_l" href="javascript:;">上传</a>
                    <!-- <span class="size f_l ml10 gray"><?=$arr_language['best']?>:<em id="J_best_size"><?=$str_pic_size?></em></span> -->
                    <div class="up_pic_box">
                        <span id="J_pic_show"><?php if($arr_data['data_pic']) { ?><img src="<?=$arr_data['data_pic']?>" style="width:100px;" /><?php } ?></span>
                    </div>
                </dd>
            </dl>
        </div>
        <div style="width: 50%;float: left;height: 500px">
            <img src="/upload_pic/171127/15117527839098_500.jpg" style="width: 50%;float: left">
            <img src="/upload_pic/171127/15117527839098_500.jpg" style="width: 50%;float: left">
            <dl class="mt12" style="margin-top: 0px">
                <dt>转账银行</dt>
            </dl> 
            <dl class="mt12" style="margin-top: 0px">
                <dt>卡号：</dt>
                <dd style="height:36px; line-height:36px;">jzfchgbcfjkh</dd>
            </dl> 
            <dl class="mt12" style="margin-top: 0px">
                <dt>户名：</dt>
                <dd style="height:36px; line-height:36px;">jzfchgbcfjkh</dd>
            </dl> 
            <dl class="mt12" style="margin-top: 0px">
                <dt>开户行：</dt>
                <dd style="height:36px; line-height:36px;">jzfchgbcfjkh</dd>
            </dl> 
        </div>
            <dl class="mt20">
                <dt>&nbsp;</dt>
                <dd class="sure_nav"><input class="blue_nav" value="充  值" style="border-radius:3px;height:52px; line-height:52px; width:158px; border:0; font-weight:700; font-size:16px;" id="abc" type="submit"></dd>
            </dl>
            </div>
           <input name="order_no" value="<?php echo date('ymdHis',time()); ?>" type="hidden">
        </form>
    
</div>

<script type="text/javascript" src="/mat/dist/js/lib/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/mat/dist/js/language/admin_zh_cn.js"></script>
<script type="text/javascript" src="/mat/dist/js/modules/common.js"></script>
<script type="text/javascript" src="/mat/dist/js/shop/shop_money.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
add_recharge._init();
$('#J_bank').change(function(){
        var card = $('#J_bank option:selected').attr('data-card');
        var name = $('#J_bank option:selected').attr('data-name');
        $('#J_bank_card').val(card)
        $('#J_bank_card_name').val(name)
})
$('.J_type').click(function(){
    type = $(this).val()
    if (type==2) {
        $('#J_bank_box').show()
    }else{
        $('#J_bank_box').hide()
    }
})
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