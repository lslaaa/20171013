<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/consult/index|template/admin/header|template/admin/footer', '1508293081', 'template/admin/consult/index');?><!DOCTYPE HTML>
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
<div class="vip_show_list mt10">
    <ul>
        <li><a href="/admin.php?mod=consult&mod_dir=consult" <?php if($int_status==0) { ?>class="on"<?php } ?>><?=$arr_language['all']?></a></li>
        <li><a href="/admin.php?mod=consult&mod_dir=consult&status=<?=LEM_consult::NO_DO?>" <?php if($int_status == LEM_consult::NO_DO) { ?>class="on"<?php } ?>><?=$arr_language['consult']['no_do']?></a></li>
        <li><a href="/admin.php?mod=consult&mod_dir=consult&status=<?=LEM_consult::SUCCESS?>" <?php if($int_status == LEM_consult::SUCCESS) { ?>class="on"<?php } ?>><?=$arr_language['consult']['success']?></a></li>

    </ul>
</div>
        
<div class="info_wrap main_table mt10">
    <table>
    	<tr>
            <th class="" style="text-align:left; padding-left:10px;"><?=$arr_language['info']?></th>
            <th class="table_w5"><?=$arr_language['status']?></th>
            <th class="table_w5"><?=$arr_language['date']?></th>
            <th class="table_w5"><?=$arr_language['admin_member']['do']?></th>
        </tr>
        <?php if($arr_list['total']>0) { ?>
        <?php if(is_array($arr_list['list'])) { foreach($arr_list['list'] as $v) { ?>
        <tr class="white">
            <td style="text-align:left; padding-left:10px;">
            	<p><?=$v['name']?></p>
                <p><?=$v['phone']?></p>
                <p><?=$v['des']?></p>
                <p<?php if(!$v['reply']) { ?> style="display:none;"<?php } ?>><?=$arr_language['consult']['reply']?>:<span class="J_reply"><?=$v['reply']?></span></p>
            </td>
            <td><?php echo LEM_consult::get_status($v['status']) ?></td>
            <td><?php echo date("Y-m-d H:i:s",$v['in_date']) ?></td>
            <td class="table_nav">
            <?php if($v['status'] == LEM_consult::SUCCESS) { ?>
            <a href="javascript:;" class="J_btn_reply blue_nav admin_nav" data-id="<?=$v['id']?>"><?=$arr_language['consult']['success']?></a>
            <?php } else { ?>
            	<a href="javascript:;" class="J_btn_reply blue_nav admin_nav" data-id="<?=$v['id']?>"><?=$arr_language['consult']['reply']?></a>
            <?php } ?>
            <a<?php if($arr_languages) { ?> style="width:50px;" <?php } ?> href="javascript:;" class="gray_nav admin_nav J_btn_del" onclick="return false;" data-id="<?=$v['id']?>"><?=$arr_language['del']?></a>
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
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/admin_consult.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
admin_consult._init();
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