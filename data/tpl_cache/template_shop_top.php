<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/top', '1512318353', 'template/shop/top');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>head</title>
<link rel="stylesheet" href="/mat/dist/css/shop/global.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>

<body>
<!-- head_wrap [[-->
<div class="head ico">
<div class="head_logo f_l"><a href="/shop.php?mod=main&extra=main_content"  target="main"></a></div>
    <div class="head_menu f_r">
<?php $str_style = count($arr_menu)>8 ? ' style="width:auto;padding:0 5px;"' : ''; ?>
    	<?php if(is_array($arr_menu)) { foreach($arr_menu as $k => $v) { ?>
    	<a<?=$str_style?> href="/shop.php?mod=main&extra=left_menu&top_menu_id=<?=$v['mid']?>" class="J_top_menu <?php echo $k===0?'on':'' ?>" target="main_left_menu" data-url="<?=$v['url']?>"><?=$v['name']?></a>
    	<?php } } ?>
        <a href="/shop.php?mod=login&extra=logout" class="" target="_parent"><?=$arr_language['login']['logout']?></a>
    </div>
</div>
<!--]] head_wrap -->
<script type="text/javascript">
parent._attachEvent(document.documentElement, 'keydown', parent.resetEscAndF5);
</script>
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/shop/top_menu.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
top_menu._init();
</script>
</body>
</html><?php ob_out();?>