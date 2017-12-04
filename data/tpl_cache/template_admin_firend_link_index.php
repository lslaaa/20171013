<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/firend_link/index|template/admin/header|template/admin/footer', '1508292920', 'template/admin/firend_link/index');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>巨檬管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<!-- 列表 [[-->
<div class="admin_menu mt12">
        <span class="J_show_hide_menu">
            <a id="J_add_level_one" href="/admin.php?mod=firend_link&mod_dir=firend_link&extra=add" class="blue_nav"><?=$arr_language['add']?></a>
        </span>
        <span class="J_submit_from"></span>
</div>
<div class="info_wrap main_table mt10">
    <table>
    	<tr>
            <th class="" style="text-align:left; padding-left:10px;"><?=$arr_language['firend_link']['name']?></th>
            <th class="table_w5"><?=$arr_language['firend_link']['url']?></th>
            <th class="table_w5"><?=$arr_language['sort']?></th>
            <th class="table_w5"><?=$arr_language['admin_member']['do']?></th>
        </tr>
        <?php if($arr_list['total']>0) { ?>
        <?php if(is_array($arr_list['list'])) { foreach($arr_list['list'] as $v) { ?>
        <tr class="white">
            <td style="text-align:left; padding-left:10px;"><?=$v['name']?></td>
            <td><?=$v['url']?></td>
            <td><?=$v['sort']?></td>
            <td class="table_nav">
            	<a href="/admin.php?mod=firend_link&mod_dir=firend_link&extra=add&fid=<?=$v['fid']?>" class="blue_nav admin_nav"><?=$arr_language['mod']?></a>
                <a href="javascript:;" class="gray_nav admin_nav J_btn_del" onclick="return false;" data-fid="<?=$v['fid']?>"><?=$arr_language['del']?></a>
</td>
        </tr>
        <?php } } ?>
        <?php } else { ?>
        <tr class="white">
            <td colspan="4"><div class="no-data"><?=$arr_language['nothing']?></div></td>
        </tr>
        <?php } ?>
    </table>
</div>
<!--]] 列表 -->
<!-- page [[-->

<div class="info_wrap mt20">
<div class="page"><?=$str_num_of_page?></div>
</div>
<!--]] page -->
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/admin_firend_link.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
admin_firend_link._listen_del();
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