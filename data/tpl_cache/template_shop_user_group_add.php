<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/user_group/add|template/admin/header|template/admin/footer', '1508496674', 'template/shop/user_group/add');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>巨檬管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<!-- 审核 [[-->
<div class="form_list mt10">
    <form id="J_send_form" action="/shop.php?mod=user_group&extra=add" method="post" target="ajaxframeid">
        <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">  
        <input class="J_callback" type="hidden" name="callback" value="parent.send_success">  
        <input id="J_uid" type="hidden" name="gid" value="<?=$int_gid?>">       
        <dl class="mt12">
            <dt><?=$arr_language['admin_member_group']['group_name']?>：</dt>
            <dd><input id="J_group_name" type="text" name="group_name" class="text_s" autocomplete="off" value="<?=$arr_group['group_name']?>"></dd>
        </dl>                
        <dl class="mt20">
            <dt>&nbsp;</dt>
            <dd class="sure_nav"><input type="submit" class="blue_nav" value="<?=$arr_language['send']?>" style="border-radius:3px;height:52px; line-height:52px; width:158px; border:0; font-weight:700; font-size:16px;"></dd>
        </dl>
    </form>
</div>
<!--]] 审核 -->
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/shop/admin_user_group.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
admin_user_group._init();
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