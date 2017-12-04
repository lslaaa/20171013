<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/page/add|template/admin/header|template/admin/footer', '1511189073', 'template/admin/page/add');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>巨檬管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<script type="text/javascript" charset="utf-8" src="/lib/editor_lemb/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/lib/editor_lemb/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/lib/editor_lemb/lang/zh-cn/zh-cn.js"></script>
<form id="J_send_form" action="/admin.php?mod=page&extra=add" method="post" target="ajaxframeid">
    <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
    <input class="J_callback" type="hidden" name="callback" value="parent.send_success">  
    <input id="J_editor_pic_max_size" type="hidden" value="<?=$arr_page_editor_pic['val']?>" />
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
            	<option value="<?=$v['cname']?>"<?php if($arr_content['language']==$v['cname']) { ?> selected="selected"<?php } ?>><?=$v['name']?></option>
            <?php } } ?>
            </select>
            <span class="tips red">*</span>
        </dd>
    </dl>
<?php } ?>
    <dl class="mt12">
        <dt><?=$arr_language['page']['page_name']?>:</dt>
        <dd>
        	<select id="J_page_id" name="page_id" data-page_id="<?php if($int_first_id) { ?><?=$int_first_id?><?php } ?>" style="height:36px; line-height:36px;">
            	<option value="0"><?=$arr_language['select']?></option>
            <?php if(is_array($arr_cat)) { foreach($arr_cat as $v) { ?>
            	<?php if($v['pid']==0) { ?>
            	<option value="<?=$v['page_id']?>"><?=$v['name']?></option>
                <?php } ?>
            <?php } } ?>
            </select>
            <select id="J_page_id_2" name="page_id_2" data-page_id="<?php if($int_two_id>0) { ?><?=$int_two_id?><?php } ?>" style="height:36px; line-height:36px; display:none;"></select>
            <select id="J_page_id_3" name="page_id_3" data-page_id="<?php if($int_three_id) { ?><?=$int_three_id?><?php } ?>" style="height:36px; line-height:36px; display:none;"></select>
            <span class="tips red">*</span>
        </dd>
    </dl>
    <dl class="mt12">
        <dt><?=$arr_language['page']['content']?>:</dt>
        <dd>
            <div>
            <script id="editor" type="text/plain" style="width:600px;height:500px;" name="content"><?=$arr_content['content']?></script>
            </div>
        </dd>
    </dl>

    <dl class="mt12">
        <dt><?=$arr_language['seo_title']?>：</dt>
        <dd><input type="text" name="page_title" class="text_s" autocomplete="off" value="<?=$arr_content['page_title']?>" style="width:400px;"></dd>
    </dl>
    <dl class="mt12">
        <dt><?=$arr_language['seo_kwd']?>：</dt>
        <dd><input type="text" name="page_kwd" class="text_s"  autocomplete="off" value="<?=$arr_content['page_kwd']?>" style="width:400px;"></dd>
    </dl>
    <dl class="mt12">
        <dt><?=$arr_language['seo_des']?>：</dt>
        <dd>
            <textarea name="page_des" style="width:400px; line-height:34px; padding:0 10px;"><?=$arr_content['page_des']?></textarea>
        </dd>
    </dl>

    <dl class="mt20">
        <dt>&nbsp;</dt>
        <dd class="sure_nav"><input type="submit" class="blue_nav" value="<?=$arr_language['send']?>" style="border-radius:3px;height:52px; line-height:52px; width:158px; border:0; font-weight:700; font-size:16px;"></dd>
    </dl>
</div>
</form>
<script type="text/javascript">
var ue = UE.getEditor('editor');
</script>
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/admin_page.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
admin_page.json_cat = <?=$json_cat?>;
admin_page._init();
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