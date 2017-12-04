<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/shop/user_group/permission|template/admin/header|template/admin/footer', '1511506348', 'template/admin/shop/user_group/permission');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<div class="admin_rights mt10">
    <form id="J_send_form" action="/admin.php?mod=user_group&mod_dir=shop&extra=set_permission" method="post" target="ajaxframeid">
        <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
        <input class="J_callback" type="hidden" name="callback" value="parent.send_success"> 
        <input type="hidden" name="gid" value="<?=$int_gid?>">
    <?php if(is_array($arr_menu)) { foreach($arr_menu as $k => $v) { ?> 
    
    <dl class="mt12" id="J_menu_<?=$v['mid']?>">
        <dt><input class="J_menu_checkbox" id="J_menu_<?=$v['mid']?>_1" name="mid[]" type="checkbox" <?php if(($mix_permission==='all' || in_array($v['mid'],explode(',',$mix_permission))) ) { ?>checked="checked"<?php } ?> value="<?=$v['mid']?>"><em><?=$v['name']?></em></dt>
        <?php if(is_array($v['subs'])) { foreach($v['subs'] as $k2 => $v2) { ?>
        <h1><input class="J_menu_checkbox J_menu_checkbox_2" id="J_menu_<?=$v2['mid']?>_2" name="mid[]" type="checkbox" value="<?=$v2['mid']?>" <?php if(($mix_permission==='all' || in_array($v2['mid'],explode(',',$mix_permission))) ) { ?>checked="checked"<?php } ?>><em><?=$v2['name']?></em></h1>
        <h2 id="J_menu_checkbox_sub_<?=$v2['mid']?>">
        <?php if(is_array($v2['subs'])) { foreach($v2['subs'] as $k3 => $v3) { ?>
            <span><input class="J_menu_checkbox" id="J_menu_<?=$v3['mid']?>_3" name="mid[]" type="checkbox" value="<?=$v3['mid']?>" <?php if(($mix_permission==='all' || in_array($v3['mid'],explode(',',$mix_permission))) ) { ?>checked="checked"<?php } ?>><em><?=$v3['name']?></em></span>
        <?php } } ?>
        </h2>
        <?php } } ?>
    </dl>
    <?php } } ?>
    <dl class="sure_nav"><input id="J_send_form" class="blue_nav" type="submit" class="blue_nav" value="<?=$arr_language['send']?>" style="border-radius:3px;height:52px; line-height:52px; width:158px; border:0; font-weight:700; font-size:16px;"></dl>
    </form>
    
</div>
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/admin_user_group.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
admin_user_group_permission._init();
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
</html>
<?php ob_out();?>