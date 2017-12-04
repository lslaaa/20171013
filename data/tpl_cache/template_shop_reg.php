<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/reg', '1511261231', 'template/shop/reg');?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>动态猫 - www.dongtaimao.com</title>
<link rel="stylesheet" href="/mat/dist/css/shop/mainstyle.css" type="text/css" />
<link rel="stylesheet" href="/mat/dist/css/shop/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />

<link rel="stylesheet" href="/mat/dist/css/shop/css.css" type="text/css" />
<style type="text/css">
    body{background-color: #76bded;}
    .head {
    width: 100%;
    overflow: hidden;
    padding: 20px 0 30px;
    background-color: #3ead01;
}
.reg_box dl dd span.inp input {
    background-color: #fff;
    color:black;
}
</style>
</head>

<body style="background-color:#76bded;">
<!-- head [[-->
<div class="head" style="background-color: #50a1d7!important;">
<div class="wp" >
    	<div class="logo">
        	<a href="/shop.php"><img src="/mat/dist/images/shop/logo.png"></a>
        </div>
        <div class="menu">
        	<a href="/shop.php?mod=main">
            	<i class="ico ico_a">&nbsp;</i>
                <em>商家中心</em>
            </a>
        </div>
    </div>
</div>
<!--]] head -->
<!-- reg [[-->
<div class="head" style="background-color:#76bded;">
<div class="wp">
    	<div class="reg_box">
            <form id="J_reg_form" action="/shop.php?mod=login&extra=reg" method="post" target="ajaxframeid">
            <input class="J_callback" type="hidden" name="callback" value="parent.send_success">
            <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
        	<h1>注册成为《动态猫》会员</h1>
        	<dl>
            	<dt>* 用户名：</dt>
                <dd>
                	<span class="inp"><input name="username"></span>
                </dd>
            </dl>
            <dl>
            	<dt>* 联系人：</dt>
                <dd>
                	<span class="inp"><input name="contacts"></span>
                </dd>
            </dl>
            <dl>
            	<dt>* 手机号码：</dt>
                <dd>
                	<span class="inp" style="background-color: none!important"><input name="mobile" onkeyup="value=value.replace(/[^\d{1,}]/g,'')" maxlength="11" minlength="11" style="width:80%;float: left"><input type="button" style="width:20%;border:2px solid #ddd;background-color: #efefef" id="J_get_checkcode" value="获取验证码" /> </span>

                </dd>
            </dl>
            <dl>
                <dt>* 验证码：</dt>
                <dd>
                    <span class="inp"><input name="code"  maxlength="11" minlength="11" placeholder="请填写收到的短信验证码"></span>
                </dd>
            </dl>
            <dl>
                <dt>* 联系QQ：</dt>
                <dd>
                    <span class="inp"><input name="qq" onkeyup="value=value.replace(/[^\d{1,}]/g,'')" maxlength="11" minlength="11"></span>
                </dd>
            </dl>
            <dl>
            	<dt>* 密码：</dt>
                <dd>
                	<span class="inp"><input name="password" type="password"></span>
                </dd>
            </dl>
            <dl>
            	<dt>* 重复密码：</dt>
                <dd>
                	<span class="inp"><input name="password2" type="password"></span>
                </dd>
            </dl>
            <dl>
            	<dt>&nbsp;</dt>
                <dd>
                	<input value="确定注册" class="nav" id="J_send" type="button" style="border-radius:3px">
                </dd>
            </dl>
        </form>
        </div>
    </div>
</div>
<!--]] reg -->
<!-- copyright [[-->
<div class="wp">
    <div class="copyright">
        Copyright © 2016 - 2026 Cld , All Rights Reserved 杭州云网电子商务有限公司 版权所有&nbsp;&nbsp;&nbsp;
        <a href="http://www.miitbeian.gov.cn/" target="_blank">浙ICP备16046856号-2</a>&nbsp;&nbsp;&nbsp;
    </div>
</div>
<!--]] copyright -->
</body>


<script type="text/javascript" src="/mat/dist/js/lib/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/mat/dist/js/language/admin_zh_cn.js"></script>
<script type="text/javascript" src="/mat/dist/js/modules/common.js"></script>
<script type="text/javascript" src="/mat/dist/js/shop/reg.js"></script>
<script type="text/javascript">
reg._init()
</script>
</html>
<?php ob_out();?>