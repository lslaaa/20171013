<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/money/add_withdrawal|template/shop/header|template/shop/footer', '1511885933', 'template/shop/money/add_withdrawal');?><!DOCTYPE HTML>
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
    <dl class="mt12">
        <dt><b style="width: 80px;display: inline-block">用户名：</b></dt><dd style="height:36px; line-height:36px;"><b><?=$arr_member['username']?></b></dd>
        <dt><b style="width: 80px;display: inline-block">余额：</b></dt><dd style="height:36px; line-height:36px;"><b><em style="color: red"><?=$arr_member['balance']?></em>元</b></dd>
    </dl>
    <form id="J_send_form" action="/shop.php?mod=money&extra=add_withdrawal" method="post" target="ajaxframeid" enctype="Multipart/form-data">
    <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>"> 
    <input class="J_callback" type="hidden" name="callback" value="parent.send_success">   
    <dl class="mt12">
        <dt>提现金额：</dt>
        <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="money" id="J_money" value="" style="width: 300px" /></dd>
    </dl>
    <dl class="mt12">
        <dt>开户行：</dt>
        <dd style="height:36px; line-height:36px;">
            <select  name="bank" id="J_bank" style="height: 30px">
                <option value="">请选择账号</option>
                <?php if(is_array($arr_bank_list['list'])) { foreach($arr_bank_list['list'] as $v) { ?>
                    <option value="<?=$v['bank']?>" data-card="<?=$v['bank_card']?>" data-name="<?=$v['bank_card_name']?>"><?=$v['bank']?></option>
                <?php } } ?>
            </select>
            <span class="tips red">如没有账号请先添加</span>
        </dd>
    </dl>
    <dl class="mt12">
        <dt>银行卡账号：</dt>
        <dd style="height:36px; line-height:36px;">
            <input type="text" class="text" name="bank_card" id="J_bank_card" value="" style="width: 300px" readonly="readonly" />
            
        </dd>
    </dl>  
    <dl class="mt12">
        <dt>银行卡姓名：</dt>
        <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="bank_card_name" id="J_bank_card_name" value="" style="width: 300px" readonly="readonly" /></dd>
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
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/shop/shop_money.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<script type="text/javascript">
shop_money._init()
$('#J_bank').change(function(){
        var card = $('#J_bank option:selected').attr('data-card');
        var name = $('#J_bank option:selected').attr('data-name');
        $('#J_bank_card').val(card)
        $('#J_bank_card_name').val(name)
})
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