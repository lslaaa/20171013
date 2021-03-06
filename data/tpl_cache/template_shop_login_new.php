<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/login_new', '1512318349', 'template/shop/login_new');?><!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>首页</title>

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
                                <li class="no"><a href="/shop.php?mod=login">首页</a></li>
                                <li><a href="/shop.php?mod=login&extra=reg">商家入驻</a></li>
                                <div class="clearfix"></div>
                        </ul>
                </div>
                <div class="clearfix"></div>
        </div>
</div>
<!-- 头部end -->
<!-- banner -->
<div class="banner_diqu">
        <div id="solid">
                <ul>
                        <li><img src="/mat/dist/images/shop/banner_01.jpg" /></li>
                        <li><img src="/mat/dist/images/shop/banner_02.jpg" /></li>
                </ul>
                <div id="btt">
                        <span></span>
                        <span></span>
                </div>
        </div>
        <div class="login_sy">
                <div class="container">
                        <form id="J_login_from" action="/shop.php?mod=login" method="post" target="ajaxframeid"><!--login/index -->
                        <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
                        <input id="J_from" type="hidden" name="from" value="<?=$str_from?>">
                        <input class="J_callback" type="hidden" name="callback" value="parent.send_success">
                        <div class="f_r login_hig">
                                <p class="h3">登录壁虎SEO</p>
                                <ul>
                                        <li><span><i class="icon iconfont icon-gerenyonghutouxiang2"></i></span><input class="order_a" id="J_username" name="username" type="text" value="邮箱/手机/会员名" onfocus="if(this.value=='邮箱/手机/会员名'){this.value='';$(this).css('color','#000');}" onblur="if(this.value==''){this.value='邮箱/手机/会员名';$(this).css('color','#787676');}" maxlength="100" autocomplete="off" style="color: rgb(120, 118, 118);"></li>
                                        <li><span><i class="icon iconfont icon-mima-copy-copy"></i></span><input class="order_a" name="password" id="J_password" type="password"></li>
                                </ul>
                                <p class="h5" style="cursor:pointer" onclick="forget()">忘记登录密码？</p>
                                <p class="loing_and"><a href="javascript:;" id="J_login">登  录</a></p>
                                <p class="h5" style="cursor:pointer" onclick="javascript:location.href='/shop.php?mod=login&extra=reg'">免费注册</p>
                        </div>
                        </form>
                        <div class="clearfix"></div>
                </div>
        </div>
</div>
<!-- container -->
<div class="container">
        <div class="bh_seo">
                <ul>
                        <li>
                                <a href="#">
                                        <p class="ico"><i class="icon iconfont icon-jingdong"></i></p>
                                        <p class="h5">京东</p>
                                </a>
                        </li>
                        <li>
                                <a href="#">
                                        <p class="ico"><i class="icon iconfont icon-tianmao"></i></p>
                                        <p class="h5">天猫</p>
                                </a>
                        </li>
                        <li>
                                <a href="#">
                                        <p class="ico"><i class="icon iconfont icon-tao"></i></p>
                                        <p class="h5">淘宝</p>
                                </a>
                        </li>
                        <li>
                                <a href="#">
                                        <p class="ico"><i class="icon iconfont icon-mogujie"></i></p>
                                        <p class="h5">蘑菇街</p>
                                </a>
                        </li>
                        <li class="no">
                                <a href="#">
                                        <p class="ico"><i class="icon iconfont icon-pin1"></i></p>
                                        <p class="h5">拼多多</p>
                                </a>
                        </li>
                        <div class="clearfix"></div>
                </ul>
        </div>
</div>
<!-- /container -->

<!-- footer -->
<div class="footer container">Copyright 2016 - 2026 Cld , All Rights Reserved 杭州云网电子商务有限公司 版权</div>
<!-- footer end -->
<script type="text/javascript" src="/mat/dist/js/lib/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/mat/dist/js/language/admin_zh_cn.js"></script>
<script type="text/javascript" src="/mat/dist/js/modules/common.js"></script>
<script type="text/javascript" src="/mat/dist/js/shop/login.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script src="/mat/dist/js/shop/fordboy.js"></script>
<script type="text/javascript">
login._init();
function forget(){
    var html = '用户名：<input name="J_username" style="border: 1px solid #ddd;height: 30px;"><br><br>'+
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
            '/shop.php?mod=sms&extra=forget',
            {'username':f_username},
            function(data){
                make_alert_html('提示',data);
            }
            )

    })

}
</script>
</body>

</html><?php ob_out();?>