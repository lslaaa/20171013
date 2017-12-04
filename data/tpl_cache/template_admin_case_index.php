<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/case/index|template/admin/header|template/admin/footer', '1508293081', 'template/admin/case/index');?><!DOCTYPE HTML>
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
<div class="help_menu mt10">
<div class="f_l">
    <?php if($arr_languages) { ?>
    	<?=$arr_language['language']?>:
        <select id="J_language" name="language" style="height:36px; line-height:36px;">
            <option value="0"><?=$arr_language['select']?></option>
        <?php if(is_array($arr_languages)) { foreach($arr_languages as $v) { ?>
            <option value="<?=$v['cname']?>"<?php if($str_language==$v['cname']) { ?> selected="selected"<?php } ?>><?=$v['name']?></option>
        <?php } } ?>
        </select>
    <?php } ?>
    	<?=$arr_language['cat']?>:
        <select id="J_province" name="province" data-province="<?=$int_province?>" style="height:36px; line-height:36px;"></select>
        <select id="J_cid" name="cid_1" data-cid="<?=$int_cid_1?>" style="height:36px; line-height:36px;">
            <option value="0"><?=$arr_language['select']?></option>
        <?php if(is_array($arr_cat)) { foreach($arr_cat as $v) { ?>
            <?php if($v['pid']==0) { ?>
            <option value="<?=$v['cid']?>"><?=$v['name']?></option>
            <?php } ?>
        <?php } } ?>
        </select>
        <select id="J_cid_2" name="cid_2" data-cid="<?=$int_cid_2?>" style="height:36px; line-height:36px; display:none;"></select>
        <select id="J_cid_3" name="cid_3" data-cid="<?=$int_cid_3?>" style="height:36px; line-height:36px; display:none;"></select>
    </div>
</div>
<div class="admin_menu mt12">
        <span class="J_show_hide_menu">
            <a id="J_add_level_one" href="/admin.php?mod=case&mod_dir=case&extra=add" class="blue_nav"><?=$arr_language['add']?></a>
        </span>
        <span class="J_submit_from"></span>
</div>
<div class="info_wrap main_table mt10">
    <table>
    	<tr>
            <th class="" style="text-align:left; padding-left:10px;"><?=$arr_language['case']['title']?></th>
            <?php if($arr_list['list']['0']['cid_1']>0) { ?>
            <th class="table_w5"><?=$arr_language['cat']?></th>
            <?php } ?>
            <?php if($arr_languages) { ?>
            <th class="table_w6"><?=$arr_language['language']?></th>
            <?php } ?>
            <th class="table_w5"><?=$arr_language['date']?></th>
            <th class="table_w5"><?=$arr_language['admin_member']['do']?></th>
        </tr>
        <?php if($arr_list['total']>0) { ?>
        <?php if(is_array($arr_list['list'])) { foreach($arr_list['list'] as $v) { ?>
        <tr class="white">
            <td style="text-align:left; padding-left:10px;"><?=$v['title']?></td>
            <?php if($v['cid_1']>0) { ?>
            <td>
            	<?php if($v['province']>0) { ?><?=$arr_province[$v['province']]['name']?>&nbsp;&nbsp;<?php } ?>
            	<?=$arr_cat_b[$v['cid_1']]['name']?>
                <?php if($v['cid_2']>0) { ?>&nbsp;&nbsp;<?=$arr_cat_b[$v['cid_2']]['name']?><?php } ?>
                <?php if($v['cid_3']>0) { ?>&nbsp;&nbsp;<?=$arr_cat_b[$v['cid_3']]['name']?><?php } ?></td>
            <?php } ?>
            <?php if($arr_languages) { ?>
            <td><?=$arr_languages_b[$v['language']]['name']?></td>
            <?php } ?>
            <td><?php echo date("Y-m-d H:i:s",$v['in_date']) ?></td>
            <td class="table_nav">
            	<?php if($arr_languages) { ?>
            	<a style="width:50px;" href="/admin.php?mod=case&mod_dir=case&extra=add&id=<?=$v['id']?>&copy=1" class="red_nav admin_nav"><?=$arr_language['copy']?></a>
                <?php } ?>
            	<a<?php if($arr_languages) { ?> style="width:50px;" <?php } ?> href="/admin.php?mod=case&mod_dir=case&extra=add&id=<?=$v['id']?>" class="blue_nav admin_nav"><?=$arr_language['mod']?></a>
                <a<?php if($arr_languages) { ?> style="width:50px;" <?php } ?> href="javascript:;" class="gray_nav admin_nav J_btn_del" onclick="return false;" data-id="<?=$v['id']?>"><?=$arr_language['del']?></a>
</td>
        </tr>
        <?php } } ?>
        <?php } else { ?>
        <tr class="white">
            <td colspan="<?php if($arr_languages) { ?>4<?php } else { ?>3<?php } ?>"><div class="no-data"><?=$arr_language['nothing']?></div></td>
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
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/index/district.js,dist/js/admin/admin_case.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
admin_case.json_cat = <?=$json_cat?>;
admin_case._listen_del();
admin_case._listen_cat();
admin_case_search._init();
district._init('J_province');
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