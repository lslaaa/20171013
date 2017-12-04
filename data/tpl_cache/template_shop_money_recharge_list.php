<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/money/recharge_list|template/admin/header|template/admin/footer', '1511885905', 'template/shop/money/recharge_list');?><!DOCTYPE HTML>
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
<div class="admin_rights mt10">
<!-- <a href="javascript:history.back(-1)" style="padding:10px 20px;border-radius:2px;border:1px solid #ddd;" class="gray_nav">返回</a><br><br> -->
<div>
<p style="font-size: 18px"><b style="width: 80px;display: inline-block">用户名：</b><?=$arr_member['username']?></p>
<p style="font-size: 18px"><b style="width: 80px;display: inline-block">余额：</b><em style="color: red"><?=$arr_member['balance']?></em>元<a href="/shop.php?mod=money&extra=add_recharge" class="blue_nav" style="padding: 3px 10px;border-radius: 3px;margin-left: 10px" data-id="<?=$v['id']?>">充值</a></p>
<br>
<p style="font-size:18px"><b>充值记录</b></p>
</div>
<!-- <form action="/admin.php?mod=money&mod_dir=money" method="get">
    <input type="hidden" name="mod" value="money"/>
    <input type="hidden" name="mod_dir" value="money"/>
    <span>用户名：</span><input type="text" class="text_s" style="height:25px" name="username" value="<?=$str_username?>">
    <span>手机号：</span><input type="text" class="text_s" style="height:25px" name="mobile" value="<?=$str_mobile?>">
    <input type="submit" class="blue_nav btn_class" style="padding: 5px 20px;border-radius: 3px;border:none"  name="" value="搜索">
</form> -->
<br>
<div class="vip_show_list mt10" style="overflow:hidden;">
    <ul>
        <li><a href="/shop.php?mod=money" <?php if($int_status==0) { ?>class="on"<?php } ?>>全部</a></li>
        <li><a href="/shop.php?mod=money&status=100" <?php if($int_status==100) { ?>class="on"<?php } ?>>未处理</a></li>
        <li><a href="/shop.php?mod=money&status=200" <?php if($int_status==200) { ?>class="on"<?php } ?>>已处理</a></li>
        <!-- <li><a href="/admin.php?mod=money&mod_dir=money&status=3" <?php if($int_status==2) { ?>class="on"<?php } ?>>已撤销</a></li> -->

    </ul>
</div>
<input type="hidden" name="uid" value="<?=$_GET['uid']?>" id="uid"/>
<div class="info_wrap main_table mt10">
    <table>
        <tr style="white-space:nowrap">
            <th class="table_w5" style="text-align:left; padding-left:10px;">用户名</th>
            <th class="table_w5">手机号</th>
            <th class="table_w5">历史余额</th>
            <th class="table_w5">充值金额</th>
            <th class="table_w5">充值类型</th>
            <th class="table_w5">线下账号</th>
            <th class="table_w5">转账截图</th>
            <th class="table_w5">时间</th>
            <th class="table_w5">联系人</th>
            <!-- <th class="table_w5">联系QQ</th> -->
            <th class="table_w5">处理状态</th>
            <th class="table_w5">留言信息</th>
            <th class="table_w5">操作</th>
        </tr>
        <?php if(count($arr_list['list'])>0) { ?>
        <?php if(is_array($arr_list['list'])) { foreach($arr_list['list'] as $v) { ?>
        <tr class="white">
            <td style="text-align:left; padding-left:10px;white-space:nowrap"><?=$v['user']['username']?></td>
            <td><?=$v['mobile']?></td>
            <td><?=$v['old_balance']?></td>
            <td><?=$v['money']?></td>
            <td><?php if($v['type']==1) { ?>支付宝<?php } elseif($v['type']==2) { ?>线下转账<?php } elseif($v['type']==3) { ?>微信<?php } ?></td>
            <td><?=$v['bank_name']?><br><?=$v['bank_card']?><br><?=$v['card_name']?></td>
            <td><?php if($v['pic']) { ?><a id="example2-1" title="" href="<?=$v['pic']?>" style="width: 50px;height: 50px;float: left;padding: 0px;margin-right: 10px"><img src="<?=$v['pic']?>" style="width: 50px;height: 50px" class="exmpic"></a><?php } ?></td>
            <td><p><?php echo date('Y-m-d H:i:s',$v['in_date']); ?></p></td>
            <td><?=$v['user']['contacts']?></td>
            <!-- <td><a style="float:left"><?=$v['qq']?></a><?php if($v['qq']) { ?><a  style="float:left" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?=$v['qq']?>&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:<?=$v['qq']?>:52" alt="点击这里给我发消息" title="点击这里给我发消息"/></a><?php } ?></td> -->
            <td><?php if($v['status']==200) { ?>已处理<?php } ?><?php if($v['status']==100) { ?><font style="color:red">未处理</font><?php } ?><?php if($v['status']==304) { ?>已撤销<?php } ?></td>
            <td><textarea class="J_reply"><?=$v['reply']?></textarea></td>
            <td class="table_nav">
            <?php if($v['status']==100) { ?>待处理<?php } ?>
            <?php if($v['status']==200) { ?>已处理<?php } ?>
            <?php if($v['status']==304) { ?>已撤销<?php } ?>
               <?php if($v['status']==100) { ?>
               <!-- <a href="javascript:;" class="J_btn_reply blue_nav admin_nav" data-id="<?=$v['id']?>">确认</a> -->
               <!-- <a href="javascript:;" class="J_btn_reply_2 red_nav admin_nav" data-id="<?=$v['id']?>">撤销</a> -->
               <?php } ?>
            </td>
        </tr>
        <?php } } ?>
        <?php } else { ?>
        <tr class="white">
            <td colspan="9"><div class="no-data">没有数据</div></td>
        </tr>
        <?php } ?>
    </table>
</div>
        
<!--]] 列表 -->
<!-- page [[-->

<div class="info_wrap mt20">
<div class="page">
    <?=$str_num_of_page?>
    <a>本页<?php echo count($arr_list['list']) ?>条</a>
    <a>共<?php echo $arr_list['total'] ?>条</a>
    </div>
</div>
</div>

<!--]] page -->
<script type="text/javascript" src="/mat/dist/js/lib/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/mat/dist/js/language/admin_zh_cn.js"></script>
<script type="text/javascript" src="/mat/dist/js/modules/common.js"></script>
<script src="/mat/dist/js/lib/jquery.imgbox.js"></script>
<script type="text/javascript">
var str_formhash = '<?=$_SGLOBAL['formhash']?>';
</script>
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