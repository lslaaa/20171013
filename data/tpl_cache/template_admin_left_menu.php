<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/left_menu', '1511887181', 'template/admin/left_menu');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>left_menu</title>
<link rel="stylesheet" href="/mat/dist/css/admin/global.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>

<body style="overflow-x: hidden; background:url('/mat/dist/images/admin/left_menu_back.jpg') repeat-y scroll right top #fff;">
<!-- left_menu [[-->
<div class="left_menu f_l">
    <?php if(is_array($arr_menu)) { foreach($arr_menu as $k => $v) { ?>
    <h1 class="h1_bn J_h1_bn">
        <a class="J_menu_one" href="<?php if($v['url']) { ?><?=$v['url']?><?php } else { ?>javascript:;<?php } ?>" class="<?php echo $k===0?'on':'' ?>"  target="main">
            <i class="ico"></i>
            <em><?=$v['name']?></em>
        </a>
    </h1>
    <?php $arr_menu_2 = $obj_setting_menu->get_list(array('pid'=>$v['mid'],'is_del'=>0)); ?>
    <?php if($arr_menu_2) { ?>
    <div class="two_menu J_menu_two" style="display:none;">
        <?php if(is_array($arr_menu_2)) { foreach($arr_menu_2 as $v2) { ?>
        <a href="<?=$v2['url']?>" class="<?php echo $j===0?'on':'' ?>"  target="main">
            <i class="ico"></i>
            <em><?=$v2['name']?></em>
        </a>
        <?php } } ?>
    </div>
    <?php } ?>
    <?php } } ?>
</div>
<!--]] left_menu -->
<script type="text/javascript">
if(typeof(parent._attachEvent)=='function'){
parent._attachEvent(document.documentElement, 'keydown', parent.resetEscAndF5);
}
</script>   
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/left_menu.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
left_menu._init();
</script>
</body>
</html><?php ob_out();?>