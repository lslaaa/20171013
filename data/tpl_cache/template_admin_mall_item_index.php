<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/mall/item/index|template/admin/header|template/admin/footer', '1508293082', 'template/admin/mall/item/index');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>巨檬管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<!-- 列表 [[-->
<div class="help_menu mt10">
<div class="f_l">
    <?php if($arr_languages) { ?>
    	<?=$arr_language['language']?>:
        <select id="J_language" name="language" style="height:36px; line-height:36px;">
            <option value="0"><?=$arr_language['select']?></option>
        <?php if(is_array($arr_languages)) { foreach($arr_languages as $v) { ?>
            <option value="<?=$v['cname']?>"<?php if($str_language==$v['cname']) { ?> selected="selected"<?php } ?>><?=$v['name']?></option>
        <?php } } ?>
        </select>
    <?php } ?>
    	<?=$arr_language['cat']?>:
        <select id="J_cid_1" name="cid_1" data-cid="<?=$int_cid_1?>" style="height:36px; line-height:36px;">
            <option value="0"><?=$arr_language['select']?></option>
        <?php if(is_array($arr_cat)) { foreach($arr_cat as $v) { ?>
            <?php if($v['pid']==0) { ?>
            <option value="<?=$v['cid']?>"><?=$v['name']?></option>
            <?php } ?>
        <?php } } ?>
        </select>
        
        <select id="J_cid_2" name="cid_2" data-cid="<?=$int_cid_2?>" style="height:36px; line-height:36px; display:none;"></select>
    </div>
    <div class="product_search">
        <form action="/admin.php?mod=item&mod_dir=mall" method="get" id="search_form">
            <input type="hidden" value="item" name="mod" />
            <input type="hidden" value="mall" name="mod_dir" />
            <dl class="mt10">
                <dt class="ml15"><?=$arr_language['item']['title']?>:</dt>
                <dd><input class="text"  name="kwd" value="<?php if($str_kwd) { ?><?=$str_kwd?><?php } ?>"></dd>
                <dt class="ml15 add_navbox"><input type="submit" class="blue_nav" value="<?=$arr_language['search']?>" style="margin-top:0;"/></dt>
                
            </dl>
        </form>
    </div>
</div>
<div class="admin_menu mt12">
        <span class="J_show_hide_menu">
            <a id="J_add_level_one" href="/admin.php?mod=item&mod_dir=mall&extra=add" class="blue_nav" style="display:inline-block;"><?=$arr_language['add']?></a>
            <a id="J_bat_inactive" href="javascript:;" class="red_nav" style="display:inline-block;"><?=$arr_language['item']['bat_inactive']?></a>
        </span>
        <span class="J_submit_from"></span>
</div>
<div class="info_wrap main_table mt10">
    <table>
    	<tr>
            <th class="table_w1"><input id="J_checkall" type="checkbox" value=""></th>
            <th class="" style="text-align:left; padding-left:10px;"><?=$arr_language['item']['title']?></th>
            <?php if($arr_languages) { ?>
            <th class="table_w6"><?=$arr_language['language']?></th>
            <?php } ?>
            <th class="table_w5"><?=$arr_language['cat']?></th>
            <th class="table_w6"><?=$arr_language['price']?></th>
            <th class="table_w6"><?=$arr_language['sort']?></th>
           <!-- <th class="table_w5"><?=$arr_language['item']['stock']?></th>-->
            <th class="table_w5"><?=$arr_language['admin_member']['do']?></th>
        </tr>
        <?php if($arr_list['total']>0) { ?>
        <?php if(is_array($arr_list['list'])) { foreach($arr_list['list'] as $v) { ?>
        <tr class="white">
            <td><input type="checkbox" class="J_checkall_sub" value="<?=$v['item_id']?>"></td>
            <td style="text-align:left; padding-left:10px;"><?=$v['title']?><?php if($v['pic']) { ?><img src="<?php echo resize_image($v['pic'],400) ?>" style=" height:100px;" /><?php } ?></td>
            <?php if($arr_languages) { ?>
            <td><?=$arr_languages_b[$v['language']]['name']?></td>
            <?php } ?>
            <td><?=$arr_cat[$v['cid_1']]['name']?> <?=$arr_cat[$v['cid_2']]['name']?></td>
            <td><?=$v['price']?></td>
            <td><?=$v['sort']?></td>
           <!-- <td><?=$v['stock']?></td>-->
            <td class="table_nav">
            	<?php if($arr_languages) { ?>
            	<a style="width:50px;" href="/admin.php?mod=item&mod_dir=mall&extra=add&item_id=<?=$v['item_id']?>&copy=1" class="red_nav admin_nav"><?=$arr_language['copy']?></a>
                <?php } ?>
            	<a<?php if($arr_languages) { ?> style="width:50px;" <?php } ?> href="/admin.php?mod=item&mod_dir=mall&extra=add&item_id=<?=$v['item_id']?>" class="blue_nav admin_nav"><?=$arr_language['mod']?></a>
                <a<?php if($arr_languages) { ?> style="width:50px;" <?php } ?> href="javascript:;" class="gray_nav admin_nav J_btn_del" onclick="return false;" data-item_id="<?=$v['item_id']?>"><?=$arr_language['del']?></a>
</td>
        </tr>
        <?php } } ?>
        <?php } else { ?>
        <tr class="white">
            <td colspan="<?php if($arr_languages) { ?>7<?php } else { ?>6<?php } ?>"><div class="no-data"><?=$arr_language['nothing']?></div></td>
        </tr>
        <?php } ?>
    </table>
</div>
<!--]] 列表 -->
<!-- page [[-->

<div class="info_wrap mt20">
<div class="page"><?=$str_num_of_page?></div>
</div>
<!--]] page -->
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/admin_mall_item.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
admin_item_edit.json_cat = <?=$json_cat?>;
admin_item_edit._listen_cat();	
admin_item_edit._listen_bat_inactive();	
admin_item_search._init();
admin_item_del._init();
checkall._init();
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
</html>
<?php ob_out();?>