<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/shop/member_shop/user_list|template/admin/header|template/admin/footer', '1511489465', 'template/admin/shop/member_shop/user_list');?><!DOCTYPE HTML>
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
            <!-- <a id="J_bat_audited" href="javascript:;" class="blue_nav" style="display:inline-block;">批量审核</a> -->
            <a id="J_bat_phy_open" href="javascript:;" class="blue_nav" style="display:inline-block;">批量启用</a>
            <a id="J_bat_phy_del" href="javascript:;" class="red_nav" style="display:inline-block;">批量禁用</a>
        </span>
</div>
<div class="info_wrap main_table mt10">
    <table>
        <tr>
            <th class="table_w1"><input id="J_checkall" type="checkbox" value=""></th>
            <th class="table_w6"><?=$arr_language['admin_member']['group_name']?></th>
            <th class="table_w6"><?=$arr_language['admin_member']['username']?></th>
            <th class="table_w6"><?=$arr_language['admin_member']['last_date']?></th>
            <th class="table_w6"><?=$arr_language['admin_member']['last_ip']?></th>
            <th class="table_w6"><?=$arr_language['member']['reg_date']?></th>
            <th class="table_w5"><?=$arr_language['admin_member']['do']?></th>
        </tr>
        <?php if($arr_list['total']>0) { ?>
        <?php if(is_array($arr_list['list'])) { foreach($arr_list['list'] as $v) { ?>
        <tr class="white">
            <td><input type="checkbox" class="J_checkall_sub" value="<?=$v['uid']?>"></td>
            <td><?=$arr_groups[$v['gid']]['group_name']?></td>
            <td><?=$v['username']?></td>
            <td><?php if($v['last_date']) { ?><?php echo date("Y-m-d H:i:s",$v['last_date']); ?><?php } ?>&nbsp;&nbsp;<?=$member['last_ip']?></td>
            <td><?=$v['last_ip']?></td>
            <td><?php echo date("Y-m-d H:i:s", $v['in_date']) ?></td>
            <td class="table_nav">
                <a href="/admin.php?mod=member_shop&mod_dir=shop&extra=mod&uid=<?=$v['uid']?>" class="blue_nav admin_nav"><?=$arr_language['show']?></a>
                <a href="javascript:;" class="gray_nav admin_nav J_btn_del" data-uid="<?=$v['uid']?>"><?php if($v['is_del']==0) { ?><?=$arr_language['member']['disable']?><?php } else { ?><?=$arr_language['member']['enable']?><?php } ?></a>
                        </td>
        </tr>
        <?php } } ?>
        <?php } else { ?>
        <tr class="white">
            <td colspan="7"><div class="no-data"><?=$arr_language['nothing']?></div></td>
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
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/member_shop_admin.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
checkall._init();
admin_member_shop._listen_audited();
admin_member_shop._listen_del();
admin_member_shop._listen_bat_audited();
admin_member_shop._listen_bat_phy_del();
admin_member_shop._listen_bat_phy_open();
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