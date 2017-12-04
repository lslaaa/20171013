<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/member/mod|template/admin/header|template/admin/footer', '1511450120', 'template/admin/member/mod');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>巨檬管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<!-- 审核 [[-->
<div class="form_list mt10">
    <form id="J_send_form" action="/admin.php?mod=member&mod_dir=member&extra=mod" method="post" target="ajaxframeid">
        <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">  
        <input class="J_callback" type="hidden" name="callback" value="parent.send_success">  
        <input id="J_uid" type="hidden" name="uid" value="<?=$int_uid?>">   
        <dl class="mt12"<?php if(!$int_show_member_group) { ?> style="display:none;"<?php } ?>>
            <dt><?=$arr_language['admin_member']['group_name']?>：</dt>
            <dd>
            	<select name="gid" style="height:36px; line-height:36px;">
                <?php if(is_array($arr_groups['list'])) { foreach($arr_groups['list'] as $v) { ?>
                    <option value="<?=$v['gid']?>"<?php if($arr_member['gid']==$v['gid']) { ?> selected="selected"<?php } ?>><?=$v['group_name']?></option>
                <?php } } ?>
                </select>
            </dd>
        </dl>  
        <dl class="mt12">
            <dt><?=$arr_language['member']['realname']?>：</dt>
            <dd style="height:36px; line-height:36px;"><?=$arr_member['realname']?></dd>
        </dl>
        <dl class="mt12">
            <dt><?=$arr_language['member']['sex']?>：</dt>
            <dd style="height:36px; line-height:36px;"><?php if($arr_member['sex']==1) { ?><?=$arr_language['member']['female']?><?php } elseif($arr_member['sex']==2) { ?><?=$arr_language['member']['male']?><?php } ?></dd>
        </dl>
        <dl class="mt12">
            <dt>年龄：</dt>
            <dd style="height:36px; line-height:36px;"><?=$arr_member['detail']['age']?></dd>
        </dl>
        <dl class="mt12">
            <dt><?=$arr_language['member']['shengao']?>：</dt>
            <dd style="height:36px; line-height:36px;"><?=$arr_member['detail']['shengao']?><?=$arr_language['cm']?></dd>
        </dl>
        <dl class="mt12">
            <dt><?=$arr_language['member']['tizhong']?>：</dt>
            <dd style="height:36px; line-height:36px;"><?=$arr_member['detail']['tizhong']?><?=$arr_language['kg']?></dd>
        </dl>   
        <dl class="mt12">
            <dt>三围：</dt>
            <dd style="height:36px; line-height:36px;"><?=$arr_member['detail']['sanwei']['0']?>,<?=$arr_member['detail']['sanwei']['1']?>,<?=$arr_member['detail']['sanwei']['2']?></dd>
        </dl>
        <dl class="mt12">
            <dt>尺码：</dt>
            <dd style="height:36px; line-height:36px;">衣服：<?=$arr_member['detail']['clothes']?>&nbsp;&nbsp;&nbsp;裤子：<?=$arr_member['detail']['pants']?>&nbsp;&nbsp;&nbsp;鞋子：<?=$arr_member['detail']['shoes']?></dd>
        </dl>
        <dl class="mt12">
            <dt>是否近视：</dt>
            <dd style="height:36px; line-height:36px;"><?php if($arr_member['detail']['is_jinshi']) { ?>是<?php } else { ?>否<?php } ?></dd>
        </dl>
        <dl class="mt12">
            <dt>是否婚姻：</dt>
            <dd style="height:36px; line-height:36px;"><?php if($arr_member['detail']['is_hunyin']) { ?>是<?php } else { ?>否<?php } ?></dd>
        </dl>
        <dl class="mt12">
            <dt>是否生育：</dt>
            <dd style="height:36px; line-height:36px;"><?php if($arr_member['detail']['is_shengyu']) { ?>是<?php } else { ?>否<?php } ?></dd>
        </dl>
        <dl class="mt12">
            <dt>证件类型：</dt>
            <dd style="height:36px; line-height:36px;"><?php if($arr_member['detail']['card_type']==1) { ?>身份证<?php } else { ?>驾驶证<?php } ?></dd>
        </dl>
        <dl class="mt12">
            <dt><?=$arr_language['member']['card_num']?>：</dt>
            <dd style="height:36px; line-height:36px;"><?=$arr_member['detail']['card_num']?></dd>
        </dl>
        <dl class="mt12">
            <dt>证件姓名：</dt>
            <dd style="height:36px; line-height:36px;"><?=$arr_member['detail']['card_name']?></dd>
        </dl>
        <dl class="mt12">
            <dt>职业：</dt>
            <dd style="height:36px; line-height:36px;"><?=$arr_member['detail']['job']?></dd>
        </dl>
        <dl class="mt12">
            <dt>收入：</dt>
            <dd style="height:36px; line-height:36px;"><?=$arr_member['detail']['income']?>元</dd>
        </dl>
        <dl class="mt12">
            <dt><?=$arr_language['member']['address']?>：</dt>
            <dd style="height:36px; line-height:36px;"><?=$arr_district[$arr_member['detail']['province']]['name']?> <?=$arr_district[$arr_member['detail']['city']]['name']?> <?=$arr_district[$arr_member['detail']['area']]['name']?> <?=$arr_member['detail']['address']?></dd>
        </dl>

        <dl class="mt12">
            <dt><?=$arr_language['admin_member']['password']?>：</dt>
            <dd><input id="J_password" type="password" name="password" class="text_s"  autocomplete="off"></dd>
        </dl>
                
        <dl class="mt20">
            <dt>&nbsp;</dt>
            <dd class="sure_nav"><input type="submit" class="blue_nav" value="<?=$arr_language['send']?>" style="border-radius:3px;height:52px; line-height:52px; width:158px; border:0; font-weight:700; font-size:16px;"></dd>
        </dl>
    </form>
</div>
<!--]] 审核 -->
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/member_admin.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
admin_member._init();
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