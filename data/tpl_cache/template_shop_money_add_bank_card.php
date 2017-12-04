<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/money/add_bank_card|template/shop/header|template/shop/footer', '1511608866', 'template/shop/money/add_bank_card');?><!DOCTYPE HTML>
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
<style type="text/css">
.bank_box{position: absolute;min-height: 100px;border:1px solid #ddd;border-radius: 3px;padding: 5px;left: 150px;top:30px;background-color: white;width: 250px;max-height: 165px;overflow: hidden;overflow-y: auto}
.bank_box li{line-height: 25px;border-bottom: 1px solid #ddd;cursor: pointer;}
</style>
<div class="form_list mt10  trade">
    <form id="J_send_form" action="/shop.php?mod=money&extra=add_bank_card" method="post" target="ajaxframeid" enctype="Multipart/form-data">
    <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>"> 
    <input class="J_callback" type="hidden" name="callback" value="parent.send_success">   
    <dl class="mt12" style="position: relative;overflow: inherit">
        <dt>开户行：</dt>
        <dd style="height:36px; line-height:36px;">
            <input type="text" class="text" name="bank" id="J_bank" value="" style="width: 300px" onkeyup="bank_list('J_bank',this.value)" />
        </dd>
        <div class="bank_box" style="display: none">
            <ul>
                <li>中国工商银行</li>
            </ul>
        </div>
    </dl>
    <div style="clear: both;"></div>
    <dl class="mt12">
        <dt>银行卡账号：</dt>
        <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="bank_card" id="J_bank_card" value="" style="width: 300px" /></dd>
    </dl>  
    <dl class="mt12">
        <dt>银行卡姓名：</dt>
        <dd style="height:36px; line-height:36px;"><input type="text" class="text" name="bank_card_name" id="J_bank_card_name" value="" style="width: 300px" /></dd>
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
add_bank._init()
$(function(){
    $('#J_bank').focus(function(){
        bank_list('J_bank',$(this).val())
    })
})
function bank_list(obj,val){
    var reg = new RegExp("[\\u4E00-\\u9FFF]+","g");
　　  if(reg.test(val)){     
        $.post(
            '/shop.php?mod=money&extra=bank_list',
            {'name':val},
            function(data){
                console.log(data)
                var _data = eval("("+data+")")
                $('.bank_box li').remove()
                $('.bank_box ul').append('<li>'+val+'</li>')
                for (var i = 0; i < _data.length; i++) {
                    $('.bank_box ul').append('<li>'+_data[i].name+'</li>')
                }
                $('.bank_box').show() 

                $('.bank_box li').click(function(){
                    var name = $(this).text()
                    $('#J_bank').val(name)
                    $('.bank_box').hide() 
                })
            }

        )
　　  }
    if (!val) {
        $('.bank_box').hide() 
    }
}
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