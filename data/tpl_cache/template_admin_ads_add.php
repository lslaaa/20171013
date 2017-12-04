<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/ads/add|template/admin/header|template/admin/footer', '1511801148', 'template/admin/ads/add');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<form id="J_send_form" action="/admin.php?mod=ads&mod_dir=ads&extra=add" method="post" target="ajaxframeid" enctype="Multipart/form-data">
    <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
    <input class="J_callback" type="hidden" name="callback" value="parent.send_success">
    <input type="hidden" name="aid" value="<?php if($int_copy==0) { ?><?=$arr_ads['aid']?><?php } ?>" />
    <input id="J_form_do" name="form_do" type="hidden" />
    <!--
<input id="J_pic_max_width" type="hidden" value="760" />
<input type="hidden" id="J_editor_pic_size_tip" value="图片最佳为宽度760px,最大高度不要超过1000px" />
    -->
<div class="form_list mt10 trade">
<?php if($arr_languages) { ?>
        <dl class="mt12">
            <dt><?=$arr_language['language']?>:</dt>
            <dd>
                <select id="J_language" name="language" style="height:36px; line-height:36px;">
                    <option value="0"><?=$arr_language['select']?></option>
                <?php if(is_array($arr_languages)) { foreach($arr_languages as $v) { ?>
                    <option value="<?=$v['cname']?>"<?php if($arr_ads['language']==$v['cname']) { ?> selected="selected"<?php } ?>><?=$v['name']?></option>
                <?php } } ?>
                </select>
                <span class="tips red">*</span>
            </dd>
        </dl>
    <?php } ?>
<dl class="mt12">
        <dt><?=$arr_language['cat']?>:</dt>
        <dd>
        	<select id="J_ads_cid" name="ads_cid" style="height:36px; line-height:36px;">
            	<option value="0"><?=$arr_language['select']?></option>
            <?php if(is_array($arr_cat)) { foreach($arr_cat as $v) { ?>
            	<?php if($v['pid']==0) { ?>
            	<option value="<?=$v['ads_cid']?>"<?php if($arr_ads['ads_cid']==$v['ads_cid']) { ?> selected="selected"<?php } ?> data-url="<?=$v['has_url']?>" data-des="<?=$v['has_des']?>" data-des_2="<?=$v['has_des_2']?>" data-des_3="<?=$v['has_des_3']?>" data-pic="<?=$v['has_pic']?>" data-max_width="<?=$v['max_width']?>" data-max_height="<?=$v['max_height']?>"><?=$v['name']?></option>
                <?php } ?>
            <?php } } ?>
            </select>
            <span class="tips red">*</span>
        </dd>
    </dl>
    <dl class="mt12">
        <dt><?=$arr_language['ads']['title']?>:</dt>
        <dd>
        	<input id="J_title" type="text" name="title" class="text_s" autocomplete="off" value="<?=$arr_ads['title']?>" style="width:400px;">
            <span class="tips red">*</span>
        </dd>
    </dl>
    <dl class="mt12" id="J_url_box" style="display:none;">
        <dt><?=$arr_language['url']?>:</dt>
        <dd>
        	<input id="J_url" type="text" name="url" class="text_s" autocomplete="off" value="<?=$arr_ads['url']?>" style="width:400px;">
            <span class="tips red">*</span>
        </dd>
    </dl>
    <dl class="mt12" id="J_des_box" style="display:none;">
        <dt><?=$arr_language['des']?>:</dt>
        <dd>
        	<textarea id="J_des" name="des" style="width:400px; height:200px; padding:0 10px; line-height:34px;"><?=$arr_ads['des']?></textarea>
            <span class="tips red">*</span>
        </dd>
    </dl>
    <dl class="mt12" id="J_des_2_box" style="display:none;">
        <dt><?=$arr_language['ads']['des_2']?>:</dt>
        <dd>
            <input id="J_des_2" type="text" name="des_2" class="text_s" autocomplete="off" value="<?=$arr_ads['des_2']?>" style="width:400px;">
            <span class="tips red">*</span>
        </dd>
    </dl>
    <dl class="mt12" id="J_des_3_box" style="display:none;">
        <dt><?=$arr_language['ads']['des_3']?>:</dt>
        <dd>
            <input id="J_des_3" type="text" name="des_3" class="text_s" autocomplete="off" value="<?=$arr_ads['des_3']?>" style="width:400px;">
            <span class="tips red">*</span>
        </dd>
    </dl>
    <dl class="mt12" id="J_pic_box" style="display:none;">
            <dt><?=$arr_language['pic']?>:</dt>
            <dd style="position:relative;" id="J_img_add_dd">
                <input type="hidden" name="pic_url" value="<?=$arr_ads['pic']?>" id="J_pic_url" style="float:left;"/>
                <input name="pic" id="J_pic" type="file" style="filter:alpha(opacity=0);-moz-opacity:0;opacity:0;width:100px;height:36px;position:absolute;left:0;top:0;cursor: pointer;float:left;">
                <a class="upload_pic f_l" href="javascript:;"><?=$arr_language['upload']?></a>
                <span class="size f_l ml10 gray"><?=$arr_language['best']?>:<em id="J_best_size">100px*100px</em></span>
                <span class="tips red">*</span>
                <div class="up_pic_box">
                    <span id="J_pic_show"><?php if($arr_ads['pic']) { ?><img src="<?=$arr_ads['pic']?>" style="width:100px;" /><?php } ?></span>
                </div>
                
            </dd>
        </dl>
    <dl class="mt12">
        <dt><?=$arr_language['sort']?>:</dt>
        <dd>
        	<input id="J_sort" type="text" name="sort" class="text_s" autocomplete="off" value="<?php if(isset($arr_ads['sort'])) { ?><?=$arr_ads['sort']?><?php } else { ?>200<?php } ?>">
            <span class="tips red"><?=$arr_language['sort_des']?></span>
        </dd>
    </dl>
    <dl class="mt20">
        <dt>&nbsp;</dt>
        <dd class="sure_nav"><input type="submit" class="blue_nav" value="<?=$arr_language['send']?>" style="border-radius:3px;height:52px; line-height:52px; width:158px; border:0; font-weight:700; font-size:16px;"></dd>
    </dl>
</div>
</form>
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/admin_ads.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
admin_ads._init();
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