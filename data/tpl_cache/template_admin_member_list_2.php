<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/member/list_2|template/admin/header|template/admin/footer', '1508409119', 'template/admin/member/list_2');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>巨檬管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<!-- 后台菜单设置 [[-->
<div class="admin_rights mt10">
    <!-- admin_menu [[-->
    <div class="help_menu mt10">
        <div class="f_l">
            <?=$arr_language['city']?>:
            <select id="J_province" name="province" data-province="<?=$int_province?>" style="height:36px; line-height:36px;">
                <option value="0"><?=$arr_language['select']?></option>
            <?php if(is_array($arr_cat)) { foreach($arr_cat as $v) { ?>
                <?php if($v['pid']==0) { ?>
                <option value="<?=$v['cid']?>"><?=$v['name']?></option>
                <?php } ?>
            <?php } } ?>
            </select>
            
            <select id="J_city" name="city" data-city="<?=$int_city?>" style="height:36px; line-height:36px; display:none;"></select>
            <select id="J_area" name="area" data-area="<?=$int_area?>" style="height:36px; line-height:36px; display:none;"></select>
            <a id="J_search" class="nav_run blue_nav" href="javascript:;"><?=$arr_language['search']?></a>
        </div>
    </div>
    
    <!--]] admin_menu -->
    <?php if($arr_list['total']>0) { ?>
    <dl class="mt12">
    <?php if(is_array($arr_list['list'])) { foreach($arr_list['list'] as $v) { ?>
        <dt id="J_member_1_<?=$v['uid']?>">
        	<span><a class="J_zhankai nav_run blue_nav" href="javascript:;" data-uid="<?=$v['uid']?>"><?=$arr_language['zhankai']?></a></span>
            <em><?=$v['username']?></em>
            <span></span>
        </dt>
    <?php } } ?>
        
        <!--二级菜单 start-->
        <!--
        <h1 class="J_show_hide_mouseover" data-cid="<?=$v2['cid']?>" data-level="<?=$v2['level']?>">
            <em><b><?=$arr_language['sort']?>:<?=$v2['sort']?></b><?=$v2['name']?></em>           
            <span class="J_submit_from"></span>
        </h1>
        -->
<!-- 3级菜单 start-->
<!--
        
        <h2 class="J_show_hide_mouseover" data-cid="<?=$v3['cid']?>" data-level="<?=$v3['level']?>">
            <em><b><?=$arr_language['sort']?>:<?=$v3['sort']?></b><?=$v3['name']?></em>
            <span class="J_show_hide_menu">
                <a href="javascript:;" class="nav_run blue_nav J_show_hide_menu_add" style="display:none;"><?=$arr_language['add']?></a>
                <a href="javascript:;" class="nav_run red_nav J_show_hide_menu_mod"><?=$arr_language['mod']?></a>
                <a href="javascript:;" class="nav_run gray_nav J_show_hide_menu_del"><?=$arr_language['del']?></a>
            </span>
            <span class="J_submit_from"></span>
        </h2>
        -->
        <!-- 3级菜单 end-->
        <!--二级菜单 end-->
    </dl>
    <?php } else { ?>
    <div class="info_wrap main_table mt10">
    	<div class="no-data" style="text-align:center;"><?=$arr_language['nothing']?></div>
    </div>
    <?php } ?>
</div>
<div class="info_wrap mt20">
<div class="page"><?=$str_num_of_page?></div>
</div>
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/admin_member_list_2.js,dist/js/index/district.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
admin_member_list_2._init();
district._init('J_province','J_city','J_area');
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