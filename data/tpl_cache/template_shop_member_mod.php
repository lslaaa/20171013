<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/member/mod|template/shop/header|template/shop/footer', '1511454356', 'template/shop/member/mod');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO</title>
<link rel="stylesheet" href="/mat/??dist/css/shop/global.css,dist/css/shop/mainstyle.css,dist/css/shop/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<!-- 审核 [[-->

<div class="form_list mt10  trade">
    <form id="J_send_form" action="/shop.php?mod=member&extra=mod" method="post" target="ajaxframeid" enctype="Multipart/form-data">
        <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">  
        <input id="J_form_do" name="form_do" type="hidden" />
        <input class="J_callback" type="hidden" name="callback" value="parent.send_success">  
        <input id="J_uid" type="hidden" name="uid" value="<?=$arr_member_one['uid']?>">   
    <dl class="mt12">
        <dt>用户组：</dt>
        <dd style="height:36px; line-height:36px;">
            <select name="gid" style="height: 30px" id="J_gid">
                <option value="">请选择</option>
                <?php if(is_array($arr_groups['list'])) { foreach($arr_groups['list'] as $v) { ?>
                    <option value="<?=$v['gid']?>" <?php if($v['gid']==$arr_member_one['gid']) { ?>selected<?php } ?>><?=$v['group_name']?></option>
                <?php } } ?>
            </select>
        </dd>
    </dl>
    <dl class="mt12">
        <dt>用户名：</dt>
        <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="username" id="J_username" value="<?=$arr_member_one['username']?>" style="width: 300px" /></dd>
    </dl>
    <dl class="mt12">
        <dt>联系电话：</dt>
        <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="mobile" id="J_mobile" value="<?=$arr_member_one['mobile']?>" style="width: 300px" /></dd>
    </dl>  
    <dl class="mt12">
        <dt>联系人：</dt>
        <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="contacts" id="J_contacts" value="<?=$arr_member_one['contacts']?>" style="width: 300px" /></dd>
    </dl> 
    <dl class="mt12">
        <dt>QQ：</dt>
        <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="qq" id="J_qq" value="<?=$arr_member_one['qq']?>" style="width: 300px" /></dd>
    </dl> 
    <dl class="mt12">
        <dt>密码：</dt>
        <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="password2" id="J_password2" value="<?=$arr_member_one['password2']?>" style="width: 300px" /></dd>
    </dl> 
    <dl class="mt20">
        <dt>&nbsp;</dt>
        <dd class="sure_nav">
            <input type="submit" id="J_send" class="blue_nav" value="提交" style="border-radius:3px;height:52px; line-height:52px; width:158px; border:0; font-weight:700; font-size:16px;">
        </dd>
    </dl>

    </form>
</div>
<!--]] 审核 -->
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/shop/admin_member.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
admin_member._init()
</script>
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
</html><?php ob_out();?>