<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/login', '1512144520', 'template/admin/login');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>

<body class="login_back">
<!-- login [[-->
<form id="J_login_from" action="/admin.php?mod=login" method="post" target="ajaxframeid"><!--login/index -->
<input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
<input id="J_from" type="hidden" name="from" value="<?=$str_from?>">
<input class="J_callback" type="hidden" name="callback" value="parent.send_success">
<div class="login_wrap J_append_check_code">
<ul>
    	<li class="login_li_back"><i><?=$arr_language['login']['username']?></i><input id="J_username" class="login_input" type="text" name="username"></li>
        <li class="login_li_back"><i><?=$arr_language['login']['password']?></i><input id="J_password" class="login_input" type="password" name="password"><p class="red J_tip"></p></li>
        <?php if($bool_check_code) { ?>
        <li><span><i>xx</i><input class="verify_input J_check_code" type="text" name="check_code"></span><span class="verify"><img src="/?mod=login&extra=check_code"></span></li>
        <?php } ?>
        <!--<li class="learn_name"><input name="" type="checkbox" value=""><em>记住用户名</em></li>-->
        <li class="login_nav"><input type="submit" id="J_login" value="<?=$arr_language['login']['login']?>"></li>
    </ul>
</div>
</form>
<!--]] login -->
</body>
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/login.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
login._init();
</script>
</html><?php ob_out();?>