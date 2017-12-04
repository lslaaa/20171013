<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/setting/seo/index|template/admin/header|template/admin/footer', '1511607024', 'template/admin/setting/seo/index');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<!-- 审核 [[-->
<div class="form_list mt10">
    <form id="J_send_form" action="/admin.php?mod=seo&mod_dir=setting" method="post" target="ajaxframeid">
        <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">  
        <input class="J_callback" type="hidden" name="callback" value="parent.send_success">  
        <dl class="mt12">
            <dt><?=$arr_language['title']?>：</dt>
            <dd><input type="text" name="title" class="text_s" autocomplete="off" value="<?=$arr_seo['title']?>" style="width:400px;"></dd>
        </dl>
        <dl class="mt12">
            <dt><?=$arr_language['kwd']?>：</dt>
            <dd><input type="text" name="kwd" class="text_s"  autocomplete="off" value="<?=$arr_seo['kwd']?>" style="width:400px;"></dd>
        </dl>
        <dl class="mt12">
            <dt><?=$arr_language['des']?>：</dt>
            <dd>
            	<textarea name="des" style="width:400px; line-height:34px; padding:0 10px;"><?=$arr_seo['des']?></textarea>
            </dd>
        </dl>
        
        <dl class="mt12">
            <dt><?=$arr_language['short_title']?>：</dt>
            <dd><input type="text" name="short_title" class="text_s"  autocomplete="off" value="<?=$arr_seo['short_title']?>" style="width:400px;"></dd>
        </dl>
        <?php if(is_array($arr_languages)) { foreach($arr_languages as $v) { ?> 
            <dl class="mt12">
                <dt><?=$v['name']?><?=$arr_language['title']?>：</dt>
                <dd><input type="text" name="title_<?=$v['cname']?>" class="text_s" autocomplete="off" value="<?=$arr_seo['title_'.$v['cname']]?>" style="width:400px;"></dd>
            </dl>
            <dl class="mt12">
                <dt><?=$v['name']?><?=$arr_language['kwd']?>：</dt>
                <dd><input type="text" name="kwd_<?=$v['cname']?>" class="text_s"  autocomplete="off" value="<?=$arr_seo['kwd_'.$v['cname']]?>" style="width:400px;"></dd>
            </dl>
            <dl class="mt12">
                <dt><?=$v['name']?><?=$arr_language['des']?>：</dt>
                <dd>
                    <textarea name="des_<?=$v['cname']?>" style="width:400px; line-height:34px; padding:0 10px;"><?=$arr_seo['des_'.$v['cname']]?></textarea>
                </dd>
            </dl>
            
            <dl class="mt12">
                <dt><?=$v['name']?><?=$arr_language['short_title']?>：</dt>
                <dd><input type="text" name="short_title_<?=$v['cname']?>" class="text_s"  autocomplete="off" value="<?=$arr_seo['short_title_'.$v['cname']]?>" style="width:400px;"></dd>
            </dl>   
        <?php } } ?>
        <dl class="mt20">
            <dt>&nbsp;</dt>
            <dd class="sure_nav"><input type="submit" class="blue_nav" value="<?=$arr_language['send']?>" style="border-radius:3px;height:52px; line-height:52px; width:158px; border:0; font-weight:700; font-size:16px;"></dd>
        </dl>
    </form>
</div>
<!--]] 审核 -->
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/admin_seo.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
admin_seo._init();
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