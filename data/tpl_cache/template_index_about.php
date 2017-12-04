<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/about|template/index/header|template/index/footer', '1508296988', 'template/index/about');?><!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0;" name="viewport" />
<title><?php if(SCR=='index') { ?><?=$_SGLOBAL['seo']['title']?><?php } else { ?><?=$str_page_title?>-<?=$_SGLOBAL['seo']['short_title']?><?php } ?></title>
<?php if(SCR=='index') { ?>
<meta name="keywords" content="<?=$_SGLOBAL['seo']['kwd']?>">
<meta name="description" content="<?=$_SGLOBAL['seo']['des']?>">
<?php } ?>
<link rel="stylesheet" type="text/css" href="/mat/??dist/css/index/global.css,dist/css/index/global_rwd.css,dist/css/index/pop_css.css,dist/css/lib/weui.css?code_version=<?=$_SGLOBAL['code_version']?>">
<style type="text/css">
@-webkit-keyframes go_left_ico{
0% {-webkit-transform:matrix(1,0,0,1,0,0);opacity:1;}
100% {-webkit-transform:matrix(1,0,0,1,-20,0);opacity:0}
}
.go_left_ico{-webkit-animation:go_left_ico 1.5s 99999999}

@-webkit-keyframes go_right_ico{
0% {-webkit-transform:matrix(1,0,0,1,0,0);opacity:1;}
100% {-webkit-transform:matrix(1,0,0,1,20,0);opacity:0}
}
.go_right_ico{-webkit-animation:go_right_ico 1.5s 99999999}
</style>
</head>

<body>
<?php $obj_item_cat = L::loadClass('admin_mall_item_cat','admin');
$arr_item_cat = $obj_item_cat->get_list(array('is_del'=>0));
$json_item_cat = json_encode($arr_item_cat);
if(empty($arr_page_cat)){
    $obj_page = L::loadClass('page','index');
    $arr_page_cat = $obj_page->get_cat_list(array('is_del'=>0),1,100);
    $arr_page_cat_b = format_array_val_to_key($arr_page_cat,'page_id');
}
$json_page_cat = json_encode($arr_page_cat);

$obj_news = L::loadClass('news','index');
$arr_news_cat = $obj_news->get_cat_list(array('is_del'=>0),1,10);
$json_news_cat = json_encode($arr_news_cat);
include S_ROOT.'ssi/config/login/weixin.conf.php';
$str_sid = rand();
$str_weixin_url = "https://open.weixin.qq.com/connect/qrconnect?appid=" . weixin_login::APPID . "&redirect_uri=" . urlencode(weixin_login::CALLBACK_URL) . "&response_type=code&scope=snsapi_login&state=" . $str_sid . "#wechat_redirect";

$str_mobile_weixin_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . weixin_login::APPID . '&redirect_uri=' . weixin_login::CALLBACK_URL . '&response_type=code&scope=snsapi_userinfo&state='.$str_sid.'#wechat_redirect'; ?>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
var global_item_cat = <?=$json_item_cat?>;
var global_page_cat = <?=$json_page_cat?>;
var global_news_cat = <?=$json_news_cat?>;
</script>
<!-- head [[-->
<div class="head_main">
<div class="head_bar">
        <div class="head_top">
        <?php if(empty($_SGLOBAL['member'])) { ?>
            <a id="J_top_login" data-url="<?=$str_mobile_weixin_url?>" href="javascript:;" onClick="window.open('<?=$str_weixin_url?>','login','height=500,width=400');"><i class="pc_ico ico_a">&nbsp;</i><em><?=$arr_language['login']?></em></a>
            <a href="/reg" class="on"><i class="pc_ico ico_b">&nbsp;</i><em><?=$arr_language['join']?></em></a>
        <?php } else { ?>
        	<a href="/i"><i class="pc_ico ico_a">&nbsp;</i><em id="J_pc_login_info"><?=$_SGLOBAL['member']['username']?></em></a>
            <a href="/login/logout" class="on"><em><?=$arr_language['logout']?></em></a>
        <?php } ?>
            <a href="/mall/cart"><i class="pc_ico ico_c">&nbsp;</i><em><?=$arr_language['item']['mall_cart']?><b id="J_pic_header_cart_num"<?php if($_SGLOBAL['cart_num']<=0) { ?> style="display:none;"<?php } ?>><?=$_SGLOBAL['cart_num']?></b></em></a>
            <a href="/help"><em><?=$arr_language['help']?></em></a>
        </div>
    </div>
    <div class="menu_bar">
        <div class="head_menu">
            <div class="logo f_l"><a href="/" class="pc_ico">&nbsp;</a></div>
            <div class="menu f_r">
            	<ul>
                    <li class="tel">
                    	<i class="pc_ico">&nbsp;</i><em><?=$arr_language['phone_num']?></em>
                    </li>
                    <li>
                    	<a href="/contact" class="last<?php if(SCR == 'contact') { ?> on<?php } ?>"><em><?=$arr_language['contact']?></em></a>
                    </li>
                    <li>
                    	<a href="/mall" class="J_btn_pc_menu last<?php if(SCR == 'mall') { ?> on<?php } ?>"><em><?=$arr_language['mall']?></em></a>
                        <div class="J_pc_menu down_list" style="display:none;">
                        	<?php if(is_array($arr_item_cat)) { foreach($arr_item_cat as $v) { ?>
                                <a href="/mall/cid-<?=$v['cid']?>"><?=$v['name']?></a>
                            <?php } } ?>
                        </div>
                    </li>
                    <li>
                    	<a href="/news" class="J_btn_pc_menu last<?php if(SCR == 'news') { ?> on<?php } ?>"><em><?=$arr_language['news']?></em></a>
                        <div class="J_pc_menu down_list" style="display:none;">
                        	<?php if(is_array($arr_news_cat)) { foreach($arr_news_cat as $v) { ?>
                                <a href="/news/cid-<?=$v['cid']?>"><?=$v['name']?></a>
                            <?php } } ?>
                        </div>
                    </li>
                    <li>
                    	<a href="/about" class="J_btn_pc_menu last<?php if(SCR == 'about') { ?> on<?php } ?>"><em><?=$arr_language['about']?></em></a>
                        <div class="J_pc_menu down_list" style="display:none;">
                        	<?php if(is_array($arr_page_cat)) { foreach($arr_page_cat as $v) { ?>
                                <?php if($v['pid']==1) { ?>
                                <a href="/page/p-<?=$v['page_id']?>"><?=$v['name']?></a>
                                <?php } ?>
                            <?php } } ?>
                        </div>
                    </li>
                	<li>
                    	<a href="/" class="last<?php if(SCR == 'index') { ?> on<?php } ?>"><em><?=$arr_language['home']?></em></a>
                    </li>
                </ul>
            </div>
            <div class="phone_menu f_r">
                <a href="/mall/cart"><i class="pc_ico cart">&nbsp;</i></a>
                <a id="J_btn_mobile_menu" href="javascript:;"><i class="pc_ico menu">&nbsp;</i></a>
            </div>
        </div>
    </div>
</div>

<!-- phone_menu_bar [[-->
<!--]] phone_menu_bar -->
<!--]] head -->
<!-- banner [[-->

<!-- about_main [[-->
<!-- banner [[-->
<?php if($arr_banner) { ?>
<div class="banner_shop_show" style="height:240px;">
<div class="img_main">
    	<img src="<?=$arr_banner['pic']?>">
    </div>
</div>
<?php } ?>
<!--]] banner -->

<!-- content [[-->
<div id="content">
<!-- title [[-->
    <div class="title_page">
    	<span class="">
        	<i class="left">&nbsp;</i>
            <i class="right">&nbsp;</i>
        	<a href="/"><?=$arr_language['home']?></a> > <?=$arr_language['about']?> > <?=$arr_page_cat_b[$int_page_id]['name']?>
        </span>
        <em>&nbsp;</em>
    </div>
    <!--]] title -->
    <div class="about_show">
    	<div class="about_sort f_l">
        	<ul>
            <?php if(is_array($arr_page_cat)) { foreach($arr_page_cat as $v) { ?>
            	<li><a href="/about/p-<?=$v['page_id']?>"<?php if($v['page_id']==$int_page_id) { ?> class="on"<?php } ?>><?=$v['name']?></a></li>
            <?php } } ?>
            </ul>
        </div>
        <div class="about_main f_r">
        	<div class="title"><?=$arr_page_cat_b[$int_page_id]['name']?></div>
            <div class="about_content">
            	<?=$arr_content['content']?>
            </div>
        </div>
    </div>
</div>
<!--]] content -->
<!--]] about_main -->
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/zh_cn.js,dist/js/modules/common.js,dist/js/index/header.js,dist/js/index/footer.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
header._init();
</script>
<!-- copyright [[-->
<div id="content" class="foot_bj">
    <div class="copyright">
    	<div class="foot_menu">
        	<ul>
            	<h1><a href="/">首页</a></h1>
            	<li><a href="/about">关于我们</a></li>
            	<li><a href="/mall">基因商城</a></li>
            	<li><a href="/news">资讯中心</a></li>
                <li><a href="/contact">联系我们</a></li>
            </ul>
        </div>
        <div class="copy">
        	<i class="pc_ico">&nbsp;</i>
            <p class="mt15">深圳市逸基因工作室 | 粤ICP备1234561号</p>
            <p>Copyright &copy; 2016-2026 版权所有</p>
        </div>
        <div class="address">
        	<p class="tel"><i class="pc_ico">&nbsp;</i><em>服务热线：<?=$arr_language['phone_num']?></em></p>
            <p class="mt15">地址：深圳市盐田区壹海城壹海中心303</p>
            <p>邮箱：yee@yeegene.com</p>
            <p>邮箱：网址：www.yeegene.com</p>
        </div>
        <div class="code">
            <img src="/mat/dist/images/index/code.jpg">
            <p>微信扫一扫<br>关注逸基因官方服务号</p>
        </div>
    </div>
</div>
<!--]] copyright -->

</body>
</html><?php ob_out();?>