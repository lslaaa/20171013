<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/shop/add|template/shop/header|template/shop/footer', '1511506405', 'template/shop/shop/add');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO</title>
<link rel="stylesheet" href="/mat/??dist/css/shop/global.css,dist/css/shop/mainstyle.css,dist/css/shop/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<!-- 审核 [[-->

<div class="form_list mt10  trade">
    <form id="J_send_form" action="/shop.php?mod=shop&extra=add" method="post" target="ajaxframeid" enctype="Multipart/form-data">
        <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">  
        <input id="J_form_do" name="form_do" type="hidden" />
        <input class="J_callback" type="hidden" name="callback" value="parent.send_success">  
        <input id="J_sid" type="hidden" name="sid" value="<?=$arr_shop['sid']?>">   
    <dl class="mt12">
        <dt>店铺类型：</dt>
        <dd style="height:36px; line-height:36px;">
            <select name="type" style="height: 30px" id="J_type">
                <option value="">请选择</option>
                <option value="10" <?php if($arr_shop['type']==10) { ?>selected<?php } ?>>天猫</option>
                <option value="20" <?php if($arr_shop['type']==20) { ?>selected<?php } ?>>淘宝</option>
                <option value="30" <?php if($arr_shop['type']==30) { ?>selected<?php } ?>>京东</option>
                <option value="40" <?php if($arr_shop['type']==40) { ?>selected<?php } ?>>蘑菇街</option>
                <option value="50" <?php if($arr_shop['type']==50) { ?>selected<?php } ?>>拼多多</option>
                <option value="60" <?php if($arr_shop['type']==60) { ?>selected<?php } ?>>非电商</option>
            </select>
        </dd>
    </dl>
    <dl class="mt12">
        <dt>店铺名：</dt>
        <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="shopname" id="J_shopname" value="<?=$arr_shop['shopname']?>" style="width: 300px" /></dd>
    </dl>
    <dl class="mt12">
        <dt>用户昵称：</dt>
        <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="username" id="J_username" value="<?=$arr_shop['username']?>"  style="width: 300px"/></dd>
    </dl>
<dl class="mt12">
        <dt>联系电话：</dt>
        <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="phone" id="J_phone" value="<?=$arr_shop['phone']?>" style="width: 300px" /></dd>
    </dl>  
    <dl class="mt12">
        <dt>联系人：</dt>
        <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="contact" id="J_contact" value="<?=$arr_shop['contact']?>" style="width: 300px" /></dd>
    </dl> 
    <dl class="mt12">
        <dt>联系地址：</dt>
        <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="address" id="J_address" value="<?=$arr_shop['address']?>" style="width: 300px" /></dd>
    </dl>
    <dl class="mt12">
        <dt>店铺地址：</dt>
        <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="shop_url" id="J_shop_url" value="<?=$arr_shop['shop_url']?>" style="width: 300px"/></dd>
    </dl>

    <!-- <dl class="mt12">
        <dt>店铺描述：</dt>
        <textarea style="width: 500px;height: 100px" name="content" id="J_content"><?=$arr_shop['content']?></textarea>
    </dl> -->
    <dl class="mt20">
        <dt>&nbsp;</dt>
        <dd class="sure_nav">
            <input type="submit" id="J_send" class="blue_nav" value="提交" style="border-radius:3px;height:52px; line-height:52px; width:158px; border:0; font-weight:700; font-size:16px;">
        </dd>
    </dl>

    </form>
</div>
<!--]] 审核 -->
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/shop/admin_shop.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
admin_shop._init()
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