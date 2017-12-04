<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/reg_new', '1511606760', 'template/shop/reg_new');?><!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>商家入驻</title>

        <!-- Bootstrap core CSS -->
        <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">

        <link rel="stylesheet" type="text/css" href="/mat/dist/css/shop/iconfont/iconfont.css">
        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="/mat/dist/css/shop/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
        <link href="/mat/dist/css/shop/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<!-- 头部 -->
<div class="header">
        <div class="container">
                <div class="logo f_l"><img src="/mat/dist/images/shop/logo_new.png" alt=""></div>
                <div class="nav f_l">
                        <ul>
                                <li><a href="/shop.php?mod=login">首页</a></li>
                                <li class="no"><a href="/shop.php?mod=login&extra=reg">商家入驻</a></li>
                                <div class="clearfix"></div>
                        </ul>
                </div>
                <div class="clearfix"></div>
        </div>
</div>
<!-- 头部end -->
<!-- container -->
<div class="container tenants">
        <p class="h3">注册成为“壁虎SEO”会员</p>
        <form id="J_reg_form" action="/shop.php?mod=login&extra=reg" method="post" target="ajaxframeid">
        <input class="J_callback" type="hidden" name="callback" value="parent.send_success">
        <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
        <ul>
                <li><label>* 用户名：</label><input class="tenants_a" name="username" type="text"></li>
                <li><label>* 联系人：</label><input class="tenants_a" name="contacts" type="text"></li>
                <li><label>* 手机号码：</label><input class="tenants_b" name="mobile" type="text" onkeyup="value=value.replace(/[^\d{1,}]/g,'')" maxlength="11" minlength="11"><a class="hq" href="javascript:;" id="J_get_checkcode">获取验证码</a></li>
                <li><label>* 验证码：</label><input class="tenants_a" name="code" type="text"></li>
                <li><label>* 联系QQ：</label><input class="tenants_a" name="qq" type="text"></li>
                <li><label>* 密码：</label><input class="tenants_a" name="password" type="password"></li>
                <li><label>* 重复密码：</label><input class="tenants_a" name="password2" type="password"></li>
        </ul>
        <p class="and"><a href="javascript:;" id="J_send">确定注册</a></p>
        </form>
</div>
<!-- /container -->

<!-- footer -->
<div class="footer container">Copyright 2016 - 2026 Cld , All Rights Reserved 杭州云网电子商务有限公司 版权</div>
<!-- footer end -->
<script type="text/javascript" src="/mat/dist/js/lib/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/mat/dist/js/language/admin_zh_cn.js"></script>
<script type="text/javascript" src="/mat/dist/js/modules/common.js"></script>
<script type="text/javascript" src="/mat/dist/js/shop/reg.js"></script>
<script src="/mat/dist/js/shop/fordboy.js"></script>
<script type="text/javascript">
reg._init()
</script>
</body>

</html><?php ob_out();?>