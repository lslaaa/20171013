<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/mall/item_cat|template/admin/header|template/admin/footer', '1511798576', 'template/admin/mall/item_cat');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<!-- 后台菜单设置 [[-->
<div class="admin_rights mt10">
    <!-- admin_menu [[-->
    <div class="admin_menu mt12">
        	<span class="J_show_hide_menu">
            	<a id="J_add_level_one" href="javascript:;" class="blue_nav"><?=$arr_language['add']?></a>
            </span>
            <span class="J_submit_from"></span>
    </div>
    
    <!--]] admin_menu -->
    <?php if(count($arr_cat)>0) { ?>
    <?php if(is_array($arr_cat)) { foreach($arr_cat as $v) { ?>
    <dl class="mt12">
        <dt class="J_show_hide_mouseover" data-cid="<?=$v['cid']?>" data-level="<?=$v['level']?>">
            <em><b><?=$arr_language['sort']?>:<?=$v['sort']?></b><?=$v['name']?></em>
        	<span class="J_show_hide_menu">
            	<a href="javascript:;" class="nav_run blue_nav J_show_hide_menu_add"><?=$arr_language['add']?></a>
                <a href="javascript:;" class="nav_run red_nav J_show_hide_menu_mod"><?=$arr_language['mod']?></a>
                <a href="javascript:;" class="nav_run gray_nav J_show_hide_menu_del"><?=$arr_language['del']?></a>
            </span>
            <span class="J_submit_from"></span>
        </dt>
        
        <!--二级菜单 start-->
        <?php if(count($v['subs'])>0) { ?>
        <?php if(is_array($v['subs'])) { foreach($v['subs'] as $v2) { ?>
        <h1 class="J_show_hide_mouseover" data-cid="<?=$v2['cid']?>" data-level="<?=$v2['level']?>">
            <em><b><?=$arr_language['sort']?>:<?=$v2['sort']?></b><?=$v2['name']?></em>
            
            <span class="J_show_hide_menu">
            	<!--<a href="javascript:;" class="nav_run blue_nav J_show_hide_menu_add"><?=$arr_language['add']?></a> -->
                <a href="javascript:;" class="nav_run red_nav J_show_hide_menu_mod"><?=$arr_language['mod']?></a>
                <a href="javascript:;" class="nav_run gray_nav J_show_hide_menu_del"><?=$arr_language['del']?></a>
            </span>
           
            <span class="J_submit_from"></span>
        </h1>

        <!-- 3级菜单 start-->
        <?php if(count($v2['subs'])>0) { ?>
        <?php if(is_array($v2['subs'])) { foreach($v2['subs'] as $v3) { ?>
        <h2 class="J_show_hide_mouseover" data-cid="<?=$v3['cid']?>" data-level="<?=$v3['level']?>">
            <em><b><?=$arr_language['sort']?>:<?=$v3['sort']?></b><?=$v3['name']?></em>
            <span class="J_show_hide_menu">
                <a href="javascript:;" class="nav_run blue_nav J_show_hide_menu_add" style="display:none;"><?=$arr_language['add']?></a>
                <a href="javascript:;" class="nav_run red_nav J_show_hide_menu_mod"><?=$arr_language['mod']?></a>
                <a href="javascript:;" class="nav_run gray_nav J_show_hide_menu_del"><?=$arr_language['del']?></a>
            </span>
            <span class="J_submit_from"></span>
        </h2>
        
        <?php } } ?>
        <?php } ?>
        <!-- 3级菜单 end-->

        <?php } } ?>
        <?php } ?>
        <!--二级菜单 end-->
    </dl>
    <?php } } ?>
    <?php } ?>    
</div>
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/admin_mall_item_cat.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
admin_mall_item_cat._init();
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