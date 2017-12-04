<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/login', '1511314894', 'template/shop/login');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>动态猫 - www.dongtaimao.com</title>
<link rel="stylesheet" href="/mat/dist/css/shop/global.css" type="text/css" />
<link rel="stylesheet" href="/mat/dist/css/shop/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>

<body class="login_back">
<!-- login [[-->
<form id="J_login_from" action="/shop.php?mod=login" method="post" target="ajaxframeid"><!--login/index -->
<input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
<input id="J_from" type="hidden" name="from" value="<?=$str_from?>">
<input class="J_callback" type="hidden" name="callback" value="parent.send_success">
<div class="login_wrap J_append_check_code">
        <ul>
        <li class="login_li_back"><i style="width:50px">用户名</i><input id="J_username" class="login_input" type="text" name="username"></li>
        <li class="login_li_back"><i style="width:50px">密&nbsp;&nbsp;&nbsp;码</i><input id="J_password" class="login_input" type="password" name="password"><p class="red J_tip"></p></li>
        <?php if($bool_check_code) { ?>
        <li><span><i>xx</i><input class="verify_input J_check_code" type="text" name="check_code"></span><span class="verify"><img src="/?mod=login&extra=check_code"></span></li>
        <?php } ?>
        <li class="learn_name" style="text-align: left;"><em style="cursor:pointer" onclick="forget()">忘记密码</em></li>
        <li class="login_nav">
        <input type="submit" id="J_login" value="登录" style="float:left;width:120px">
        <input type="botton" id="J_login" onclick="javascript:location.href='/shop.php?mod=login&extra=reg'" value="注册" style="float:right;width:120px;text-align:center;margin-left:10px">
        </li>
    </ul>
</div>
</form>
<!--]] login -->
</body>
<script type="text/javascript" src="/mat/dist/js/lib/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/mat/dist/js/language/admin_zh_cn.js"></script>
<script type="text/javascript" src="/mat/dist/js/modules/common.js"></script>
<script type="text/javascript" src="/mat/dist/js/shop/login.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
login._init();
function forget(){
    var html = '用户名：<input name="J_username"><br><br>'+
               '<div class="pop_nav"><a class="nav_sure" id="J_submit" style="cursor:pointer">确定</a></div>';
    make_alert_html('找回密码',html);
    $('.align_c').hide();
    $('#J_submit').click(function(){
        var f_username = $('input[name="J_username"]').val();
        if (f_username=='') {
            make_alert_html('提示','请填写用户名');
            return false;
        }

        $.post(
            '/index.php?mod=sms&extra=forget',
            {'username':f_username},
            function(data){
                make_alert_html('提示',data);
            }
            )

    })

}
</script>
</html><?php ob_out();?>