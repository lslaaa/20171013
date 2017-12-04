<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/main', '1512318353', 'template/shop/main');?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>壁虎SEO</title>
<link rel="stylesheet" href="/mat/dist/css/shop/global.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body scroll="no">
<table id="frametable" cellpadding="0" cellspacing="0" width="100%" height="100%" style="border-collapse:collapse;">
    <tr>
        <td colspan="2" height="63">
            <iframe width="100%" height="63" src="/shop.php?mod=main&extra=top" frameborder="0" name="main_top" id="ifr_main_top" marginwidth="0" marginheight="0"></iframe>
        </td>
    </tr>
    <tr>
        <td valign="top" width="160" class="menutd">
            <iframe width="216" height="100%" src="/shop.php?mod=main&extra=left_menu&top_menu_id=19" frameborder="0" name="main_left_menu" id="ifr_main_left_menu" marginwidth="0" marginheight="0"></iframe>
        </td>
        <td valign="top" width="100%">
            <iframe src="<?=$str_from?>" id="ifr_main" name="main" width="100%" height="100%" frameborder="0" scrolling="yes" style="overflow: visible;"></iframe>
        </td>
    </tr>
</table>
<script type="text/javascript">
/*只刷新指定框架，无需调整*/
function _attachEvent(obj, evt, func, eventobj) {
eventobj = !eventobj ? obj : eventobj;
if(obj.addEventListener) {
obj.addEventListener(evt, func, false);
} else if(eventobj.attachEvent) {
obj.attachEvent('on' + evt, func);
}
}
function resetEscAndF5(e) {
e = e ? e : window.event;
actualCode = e.keyCode ? e.keyCode : e.charCode;
if(actualCode == 27) {
if($('cpmap_menu').style.display == 'none') {
showMap();
} else {
hideMenu();
}
}
if(actualCode == 116 && parent.main) {
parent.main.location.reload();
if(document.all) {
e.keyCode = 0;
e.returnValue = false;
} else {
e.cancelBubble = true;
e.preventDefault();
}
}
}
_attachEvent(document.documentElement, 'keydown', resetEscAndF5);
/*刷新框架结束*/
</script>
</body>
</html><?php ob_out();?>