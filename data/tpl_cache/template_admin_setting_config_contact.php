<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/setting/config/contact|template/admin/header|template/admin/footer', '1511886190', 'template/admin/setting/config/contact');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<form id="J_send_form" action="/admin.php?mod=config&mod_dir=setting&extra=add" method="post" target="ajaxframeid" enctype="Multipart/form-data">
    <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
    <input class="J_callback" type="hidden" name="callback" value="parent.send_success">  
    <input type="hidden" name="config_name" value="<?=$str_config?>" />
    <input type="hidden" name="config_pic_name" value="<?=$str_config_pic?>" />
    <input id="J_form_do" name="form_do" type="hidden" />
<div class="form_list mt10 trade">
<dl class="mt12">
        <dt><?=$arr_language['config']['contact']['phone']?>：</dt>
        <dd><input type="text" name="data_phone" class="text_s" autocomplete="off" value="<?=$arr_data['data_phone']?>" style="width:400px;"></dd>
    </dl>
    <dl class="mt12">
        <dt>客服qq：</dt>
        <dd><input type="text" name="data_qq" class="text_s"  autocomplete="off" value="<?=$arr_data['data_qq']?>" style="width:400px;"></dd>
    </dl>
    <dl class="mt12">
        <dt>接单设置：</dt>
        <dd><input type="text" name="data_shop_day" class="text_s"  autocomplete="off" value="<?=$arr_data['data_shop_day']?>" style="width:50px;"><span class="size ml10 gray">天内不能重复做相同店铺任务</span></dd>
    </dl>
    <dl class="mt12">
        <dt>&nbsp;</dt>
        <dd>每个用户1天<input type="text" name="data_order_day" class="text_s"  autocomplete="off" value="<?=$arr_data['data_order_day']?>" style="width:50px;">单，7天<input type="text" name="data_order_week" class="text_s"  autocomplete="off" value="<?=$arr_data['data_order_week']?>" style="width:50px;">单，30天<input type="text" name="data_order_month" class="text_s"  autocomplete="off" value="<?=$arr_data['data_order_month']?>" style="width:50px;">单</dd>
    </dl>
    <dl class="mt12">
        <dt>&nbsp;</dt>
        <dd>审核每超一分钟补贴：<input type="text" name="data_butie" class="text_s"  autocomplete="off" value="<?=$arr_data['data_butie']?>" style="width:50px;">元</dd>
    </dl>
    <dl class="mt12">
        <dt>推荐设置：</dt>
        <dd>做完<input type="text" name="data_success_order" class="text_s"  autocomplete="off" value="<?=$arr_data['data_success_order']?>" style="width:50px;">任务可以推荐人</dd>
    </dl>
    <dl class="mt12">
        <dt>&nbsp;</dt>
        <dd>推荐用户获得<input type="text" name="data_tui_gold" class="text_s"  autocomplete="off" value="<?=$arr_data['data_tui_gold']?>" style="width:50px;">金币，被推荐人做完一个任务解冻<input type="text" name="data_jie_gold" class="text_s"  autocomplete="off" value="<?=$arr_data['data_jie_gold']?>" style="width:50px;">金币</dd>
    </dl>
    <dl class="mt12" id="J_pic_box">
        <dt><?=$arr_language['config']['contact']['qcode']?>:</dt>
        <dd style="position:relative;" id="J_img_add_dd">
            <input type="hidden" name="data_pic" value="<?=$arr_data['data_pic']?>" id="J_pic_url" style="float:left;"/>
            <input name="pic" id="J_pic" type="file" style="filter:alpha(opacity=0);-moz-opacity:0;opacity:0;width:100px;height:36px;position:absolute;left:0;top:0;cursor: pointer;float:left;">
            <a class="upload_pic f_l" href="javascript:;"><?=$arr_language['upload']?></a>
            <span class="size f_l ml10 gray"><?=$arr_language['best']?>:<em id="J_best_size"><?=$str_pic_size?></em></span>
            <div class="up_pic_box">
                <span id="J_pic_show"><?php if($arr_data['data_pic']) { ?><img src="<?=$arr_data['data_pic']?>" style="width:100px;" /><?php } ?></span>
            </div>
        </dd>
    </dl>
    <dl class="mt12">
        <dt>三要素淘口令1：</dt>
        <dd><textarea name="data_kouling_1" style="width: 700px;height: 50px"><?=$arr_data['data_kouling_1']?></textarea></dd>
        <dt>三要素淘口令2：</dt>
        <dd><textarea name="data_kouling_2" style="width: 700px;height: 50px"><?=$arr_data['data_kouling_2']?></textarea></dd>
        <dt>三要素淘口令3：</dt>
        <dd><textarea name="data_kouling_3" style="width: 700px;height: 50px"><?=$arr_data['data_kouling_3']?></textarea></dd>
    </dl>
    <dl class="mt12">
        <dt>推荐设置：</dt>
        <dd style="width: 40%">
            <span style="line-height: 34px;font-size: 18px"><b>收商家</b></span><br>
            <?php if(is_array($arr_sku_price)) { foreach($arr_sku_price as $k => $v) { ?>
                <?php if($k!='id' && $k!='uid') { ?>
                <span style="line-height: 40px;"><?=$arr_price_name[$k]?></span><input type="text" name="sku[<?=$k?>]" class="text_s"  autocomplete="off" value="<?=$v?>" style="width:50px;height: 30px"><br>
                <?php } ?>
            <?php } } ?>
        </dd>
        <dd style="width: 40%">
            <span style="line-height: 34px;font-size: 18px"><b>给用户</b></span><br>
            <?php if(is_array($arr_sku_price2)) { foreach($arr_sku_price2 as $k => $v) { ?>
                <?php if($k!='id' && $k!='uid') { ?>
                <span style="line-height: 40px;"><?=$arr_price_name[$k]?></span><input type="text" name="sku2[<?=$k?>]" class="text_s"  autocomplete="off" value="<?=$v?>" style="width:50px;height: 30px"><br>
                <?php } ?>
            <?php } } ?>
        </dd>

    </dl>

    <dl class="mt20">
        <dt>&nbsp;</dt>
        <dd class="sure_nav"><input type="submit" class="blue_nav" value="<?=$arr_language['send']?>" style="border-radius:3px;height:52px; line-height:52px; width:158px; border:0; font-weight:700; font-size:16px;"></dd>
    </dl>
</div>
</form>
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/admin_config.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
admin_config._init();
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