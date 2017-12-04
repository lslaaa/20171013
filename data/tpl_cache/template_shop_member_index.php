<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/member/index|template/admin/header|template/admin/footer', '1511454359', 'template/shop/member/index');?><!DOCTYPE HTML>
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
<?php if($arr_member['gid']==1) { ?>
<div class="admin_menu mt12" style="background-color: white;border:1px solid #e4e4e4;padding: 5px">
    <p style="font-size: 18px"><b style="width: 80px;display: inline-block">用户名：</b><?=$arr_member['username']?></p>
    <p style="font-size: 18px"><b style="width: 80px;display: inline-block">余额：</b><em style="color: red"><?=$arr_member['balance']?></em>元</p>
</div>
<div class="product_search">
    <form id="search_form" method="get" action="/admin.php?mod=member&mod_dir=member">
    	<input type="hidden" name="mod" value="member">
        <input type="hidden" name="mod_dir" value="member">
        <dl class="mt10 f_l" style="width:auto;">
            <dt class="ml15"><?=$arr_language['member']['email']?>:</dt>
            <dd><input value="<?=$str_email?>" name="email" class="text"></dd>
        </dl>
        
        <dl class="mt10 f_l" style="width:auto;">
            <dt class="ml15"><?=$arr_language['member']['phone']?>:</dt>
            <dd><input value="<?=$str_phone?>" name="phone" class="text"></dd>
        </dl>
        
        <dl class="mt10 f_l" style="width:auto;">
            <dt class="ml15"><?=$arr_language['member']['realname']?>:</dt>
            <dd><input value="<?=$str_realname?>" name="realname" class="text"></dd>
            <dt class="ml15 add_navbox"><input type="submit" style="margin-top:0;" value="搜索" class="blue_nav"></dt>
        </dl>
        
    </form>
</div>
<div style="clear:both; height:0; overflow:hidden;"></div>
<div class="admin_menu mt12">
        <span class="J_show_hide_menu">
            <a href="/shop.php?mod=member&extra=mod" class="blue_nav" style="display:inline-block;">新增</a>
            <!-- <a id="J_bat_phy_del" href="javascript:;" class="red_nav" style="display:inline-block;">批量删除</a> -->
        </span>
</div>
<?php } ?>

<div class="info_wrap main_table mt10">
    <table>
    	<tr>
            <!-- <th class="table_w1"><input id="J_checkall" type="checkbox" value=""></th> -->
            <?php if($int_show_member_group) { ?>
            	<th class="table_w6"><?=$arr_language['admin_member']['group_name']?></th>
            <?php } ?>
            <th class="table_w6"><?=$arr_language['admin_member']['username']?></th>
            <th class="table_w6"><?=$arr_language['admin_member']['last_date']?></th>
            <th class="table_w6"><?=$arr_language['admin_member']['last_ip']?></th>
            <th class="table_w6"><?=$arr_language['member']['reg_date']?></th>
            <th class="table_w5"><?=$arr_language['admin_member']['do']?></th>
        </tr>
        <?php if($arr_list['total']>0) { ?>
        <?php if(is_array($arr_list['list'])) { foreach($arr_list['list'] as $v) { ?>
        <tr class="white">
            <!-- <td><input type="checkbox" class="J_checkall_sub" value="<?=$v['uid']?>"></td> -->
            <?php if($int_show_member_group) { ?>
            	<td><?=$arr_groups[$v['gid']]['group_name']?></td>
            <?php } ?>
            <td><?=$v['username']?></td>
            <td><?php if($v['last_date']) { ?><?php echo date("Y-m-d H:i:s",$v['last_date']); ?><?php } ?>&nbsp;&nbsp;<?=$member['last_ip']?></td>
            <td><?=$v['last_ip']?></td>
            <td><?php echo date("Y-m-d H:i:s", $v['in_date']) ?></td>
            <td class="table_nav">
            	<a href="/shop.php?mod=member&extra=mod&uid=<?=$v['uid']?>" class="blue_nav admin_nav"><?=$arr_language['show']?></a>
                <?php if($arr_member['gid']==1) { ?>
                <a href="javascript:;" class="gray_nav admin_nav J_btn_del" data-uid="<?=$v['uid']?>"><?php if($v['is_del']==0) { ?><?=$arr_language['member']['disable']?><?php } else { ?><?=$arr_language['member']['enable']?><?php } ?></a>
                <?php } ?>
</td>
        </tr>
        <?php } } ?>
        <?php } else { ?>
        <tr class="white">
            <td colspan="6"><div class="no-data"><?=$arr_language['nothing']?></div></td>
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
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/shop/member_admin.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
checkall._init();
admin_member._listen_audited();
admin_member._listen_del();
admin_member._listen_bat_audited();
admin_member._listen_bat_phy_del();
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