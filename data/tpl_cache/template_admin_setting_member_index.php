<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/setting/member/index|template/admin/header|template/admin/footer', '1511607023', 'template/admin/setting/member/index');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<!-- 列表 [[-->
<div class="admin_menu mt12">
        <span class="J_show_hide_menu">
            <a id="J_add_level_one" href="/admin.php?mod=member&extra=add&mod_dir=setting" class="blue_nav"><?=$arr_language['add']?></a>
        </span>
        <span class="J_submit_from"></span>
</div>
<div class="info_wrap main_table mt10">
    <table>
    	<tr>
            <th><?=$arr_language['admin_member']['username']?></th>
            <?php if($int_show_member_group) { ?>
            	<th class="table_w3"><?=$arr_language['admin_member']['group_name']?></th>
            <?php } ?>
            <th class="table_w6"><?=$arr_language['admin_member']['realname']?></th>
            <th class="table_w3"><?=$arr_language['admin_member']['last_date']?></th>
            <th class="table_w3"><?=$arr_language['admin_member']['last_ip']?></th>
            <th class="table_w5"><?=$arr_language['admin_member']['do']?></th>
        </tr>
        <?php if($arr_list['total']>0) { ?>
        <?php if(is_array($arr_list['list'])) { foreach($arr_list['list'] as $v) { ?>
        <tr class="white">
            <td><?=$v['username']?></td>
            <?php if($int_show_member_group) { ?>
            	<td><?=$arr_groups[$v['gid']]['group_name']?></td>
            <?php } ?>
            <td><?=$v['realname']?></td>
            <td><?php if($v['last_date']) { ?><?php echo date("Y-m-d H:i:s",$v['last_date']); ?><?php } ?>&nbsp;&nbsp;<?=$member['last_ip']?></td>
            <td><?=$v['last_ip']?></td>
            <td class="table_nav">
            	<a href="/admin.php?mod=member&extra=add&mod_dir=setting&uid=<?=$v['uid']?>" class="blue_nav admin_nav"><?=$arr_language['mod']?></a>
                <a href="javascript:;" class="gray_nav admin_nav J_btn_del" data-uid="<?=$v['uid']?>"><?=$arr_language['del']?></a>
</td>
        </tr>
        <?php } } ?>
        <?php } else { ?>
        <tr class="white">
            <td colspan="<?php if($int_show_cname) { ?>6<?php } else { ?>5<?php } ?>"><div class="no-data"><?=$arr_language['nothing']?></div></td>
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
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/admin_member.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
admin_member._listen_del();
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