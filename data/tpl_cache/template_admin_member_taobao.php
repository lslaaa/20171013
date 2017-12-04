<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/admin/member/taobao|template/admin/header|template/admin/footer', '1511886201', 'template/admin/member/taobao');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO管理系统</title>
<link rel="stylesheet" href="/mat/??dist/css/admin/global.css,dist/css/admin/mainstyle.css,dist/css/admin/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<!-- 列表 [[-->
<div class="product_search">
    <form id="search_form" method="get" action="/admin.php?mod=member&mod_dir=member">
        <input type="hidden" name="mod" value="member">
        <input type="hidden" name="mod_dir" value="member">
        <input type="hidden" name="extra" value="taobao">
        <dl class="mt10 f_l" style="width:auto;">
            <dt class="ml15">类型:</dt>
            <dd>
                <select style="height: 36px;width: 100px" name="type">
                    <option value="">全部</option>
                    <option value="1">淘宝</option>
                    <option value="2">京东</option>
                    <option value="3">蘑菇街</option>
                    <option value="4">拼多多</option>
                </select>
            </dd>
            <dt class="ml15">账号:</dt>
            <dd><input value="<?=$str_taobao?>" name="taobao" class="text"></dd>
            <dt class="ml15">手机号:</dt>
            <dd><input value="<?=$str_phone?>" name="phone" class="text"></dd>
            <dt class="ml15 add_navbox"><input type="submit" style="margin-top:0;" value="搜索" class="blue_nav"></dt>
        </dl>
        
    </form>
</div>
<div style="clear:both; height:0; overflow:hidden;"></div>
<div class="admin_menu mt12">
        <span class="J_show_hide_menu">
            <a id="J_bat_audited" href="javascript:;" class="blue_nav" style="display:inline-block;">批量审核</a>
            <a id="J_bat_phy_del" href="javascript:;" class="red_nav" style="display:inline-block;">批量不通过</a>
        </span>
</div>
<div style="clear:both; height:0; overflow:hidden;"></div>
<div class="vip_show_list mt10">
    <ul>
        <li><a href="/admin.php?mod=member&mod_dir=member&extra=taobao" <?php if(!$int_is_check && $int_is_check!==0) { ?>class="on"<?php } ?>>全部</a></li>
        <li><a href="/admin.php?mod=member&mod_dir=member&extra=taobao&is_check=0" <?php if($int_is_check===0) { ?>class="on"<?php } ?>>待审核</a></li>
        <li><a href="/admin.php?mod=member&mod_dir=member&extra=taobao&is_check=1" <?php if($int_is_check==1) { ?>class="on"<?php } ?>>审核通过</a></li>
        <li><a href="/admin.php?mod=member&mod_dir=member&extra=taobao&is_check=2" <?php if($int_is_check==2) { ?>class="on"<?php } ?>>审核未通过</a></li>
    </ul>
</div>
<div class="info_wrap main_table mt10">
    <table>
        <tr>
            <th class="table_w1"><input id="J_checkall" type="checkbox" value=""></th>
            <th class="table_w6">手机号</th>
            <th class="table_w6"><?=$arr_language['admin_member']['username']?></th>
            <th class="table_w6">账号类型</th>
            <th class="table_w6">任务账号</th>
            <th class="table_w6">性别</th>
            <th class="table_w6">账号截图</th>
            <th class="table_w6">个人截图</th>
            <th class="table_w6">状态</th>
            <th class="table_w5"><?=$arr_language['admin_member']['do']?></th>
        </tr>
        <?php if($arr_member_taobao['total']>0) { ?>
        <?php if(is_array($arr_member_taobao['list'])) { foreach($arr_member_taobao['list'] as $v) { ?>
        <tr class="white">
            <td><input type="checkbox" class="J_checkall_sub" value="<?=$v['id']?>"></td>
            <td><?=$v['member']['phone']?></td>
            <td><?php if($v['member']['realname']) { ?><?=$v['member']['realname']?><?php } else { ?><?=$v['member']['nickname']?><?php } ?></td>
            <td><?php if($v['type']==1) { ?>淘宝<?php } elseif($v['type']==2) { ?>京东<?php } elseif($v['type']==3) { ?>蘑菇街<?php } elseif($v['type']==4) { ?>拼多多<?php } ?></td>
            <td><?=$v['taobao']?></td>
            <td><?php if($v['sex']==1) { ?>男<?php } else { ?>女<?php } ?></td>
            <td><a id="example2-1" class="exp_box left" title="账号截图" href="<?=$v['pics']['0']?>"><img src="<?=$v['pics']['0']?>" width="100px" height="100px" class="exmpic"></a></td>
            <td><a id="example2-1" class="exp_box left" title="个人资料截图" href="<?=$v['pics']['1']?>"><img src="<?=$v['pics']['1']?>" width="100px" height="100px" class="exmpic"></a></td>
            <td><?php if($v['is_check']==0) { ?>未审核<?php } ?><?php if($v['is_check']==1) { ?>已通过审核<?php } ?><?php if($v['is_check']==2) { ?>未通过审核<?php } ?></td>
            <td class="table_nav">
                <?php if($v['is_check']==0 || $v['is_check']==2) { ?>
                <a href="javascript:;" class="blue_nav admin_nav J_audited" data-id="<?=$v['id']?>">通过</a>
                <?php } ?>
                <?php if($v['is_check']==0 || $v['is_check']==1) { ?>
                <a href="javascript:;" class="gray_nav admin_nav J_btn_del" data-id="<?=$v['id']?>">不通过</a>
                <?php } ?>
            </td>
        </tr>
        <?php } } ?>
        <?php } else { ?>
        <tr class="white">
            <td colspan="9"><div class="no-data"><?=$arr_language['nothing']?></div></td>
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
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/admin/admin_taobao.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script src="/mat/dist/js/lib/jquery.imgbox.js"></script>
<script>
$(document).ready(function() {
   showBig();
});
function showBig(){
   $("#example2-1,#example2-first,#example2-second,#example2-three").imgbox({
       'speedIn'        : 0,
       'speedOut'       : 0,
       'alignment'      : 'center',
       'overlayShow'    : true,
       'allowMultiple'  : false
   });
}
</script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
checkall._init();
admin_taobao._listen_audited();
admin_taobao._listen_del();
admin_taobao._listen_bat_audited();
admin_taobao._listen_bat_phy_del();
// $('img').click(function(){
//     var src = $(this).attr('src');
//     var html = '<img id="J_show_img" src="'+src+'" style="width: 450px;border:1px solid #ddd">';
//     make_alert_html('图片',html);
//     $('#J_pop_wrap').css('width','600px');
//     $('#J_show_img').click(function(){
//         $('#J_pop_wrap').remove();
//         $('#J_mask').remove();
//     });
// });
</script>
<div id="imgbox-loading" style="display: none;"><div style="opacity: 0.4;"></div></div>
<div id="imgbox-overlay" style="height: 3193px; opacity: 0.5; display: none;"></div>
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