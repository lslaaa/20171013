<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/main_content|template/admin/header|template/admin/footer', '1511886171', 'template/admin/main_content');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<!-- 登录信息 [[-->
<div class="info_wrap admin_user">
    <b><?=$_SGLOBAL['member']['username']?></b>，<?=$arr_language['main']['welcome']?>! 
    <p><?=$arr_language['main']['last_login_date']?>:<?php echo date('Y/m/d H:i',$_SGLOBAL['member']['last_date']); ?>&nbsp;&nbsp;&nbsp;&nbsp;IP:<?=$_SGLOBAL['member']['last_ip']?></p>
</div>

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