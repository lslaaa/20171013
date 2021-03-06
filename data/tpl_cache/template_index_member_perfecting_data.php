<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/member/perfecting_data|template/index/header', '1511601863', 'template/index/member/perfecting_data');?><!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>任务帮</title>

        <link rel="stylesheet" type="text/css" href="/mat/dist/css/index/iconfont/iconfont.css">
        <!-- Custom styles for this template -->
        <link href="/mat??/dist/css/index/style.css,dist/css/admin/pop_css.css" rel="stylesheet" type="text/css">
</head>


<body>
<!-- 头部 -->
<div class="header">
<div class="hed_lf"><a href="/i"><i class="icon iconfont icon-sanjiao"></i></a></div>
<div class="hed_ct">个人信息</div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="container">
<form id="J_send_form" action="/account/perfecting_data" method="post" target="ajaxframeid">
    	<input class="J_callback" type="hidden" name="callback" value="parent.send_success">
    	<input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
<div class="perfecting_data">
<ul>
<li>
<label><b>*</b>证件类型</label>
<select class="w_7" name="card_type">
<option value="1">身份证</option>
<option value="2">驾驶证</option>
</select>
</li>
<li>
<label><b>*</b>证件号码</label>
<input class="w_7" name="card_num" type="text" value="<?=$arr_member['detail']['card_num']?>" />
</li>
<li>
<label><b>*</b>证件姓名</label>
<input class="w_7" name="card_name" type="text" value="<?=$arr_member['detail']['card_name']?>" />
</li>
<li>
<label><b>*</b>区域地址</label>
<select class="w_3" name="province" id="J_province" data-province="<?=$arr_member['detail']['province']?>">
<option value="1">省份</option>
<option value="2">广东省</option>
</select>
<select class="w_3" name="city" id="J_city" data-city="<?=$arr_member['detail']['city']?>">
<option value="1">城市</option>
<option value="2">深圳市</option>
</select>
<select class="w_3" name="area" id="J_area" data-area="<?=$arr_member['detail']['area']?>">
<option value="1">区/县</option>
<option value="2">龙华新区</option>
</select>
</li>
<li style="display: none">
<label><b>*</b>人脸识别</label>
<a class="w_7" href="#"><i class="icon iconfont icon-yousanjiao-copy-copy"></i></a>
</li>
<li>
<label><b>*</b>性别</label>
<select class="w_7" name="sex">
<option value="1" <?php if($arr_member['sex']==1) { ?>selected<?php } ?>>男</option>
<option value="2" <?php if($arr_member['sex']==2) { ?>selected<?php } ?>>女</option>
</select>
</li>
<li>
<label><b>*</b>年龄</label>
<input class="w_7" name="age" type="text" value="<?=$arr_member['detail']['age']?>" />
</li>
<li>
<label class="w_4">身材信息(选填)</label>
<a class="w_6 more" href="javascript:;"><i class="icon iconfont icon-sanjiao-copy-copy"></i></a>
<div class="figure">
<ul>
<li>
<label>身高</label>
<input class="w_7" name="shengao" type="text" value="<?=$arr_member['detail']['shengao']?>" />
</li>
<li>
<label>三围</label>
<input class="w_3" name="sanwei[]" type="text" value="<?=$arr_member['detail']['sanwei']['0']?>" />
<input class="w_3" name="sanwei[]" type="text" value="<?=$arr_member['detail']['sanwei']['1']?>" />
<input class="w_3" name="sanwei[]" type="text" value="<?=$arr_member['detail']['sanwei']['2']?>" />
</li>
<li>
<label>体重</label>
<input class="w_7" name="tizhong" type="text" value="<?=$arr_member['detail']['tizhong']?>" />
</li>
<li>
<input type="hidden" name="clothes" value="<?=$arr_member['detail']['clothes']?>">
<label>衣服尺码</label>
<span class="active" data="S"><i class="icon iconfont icon-Selected"></i>S</span>
<span data="M"><i class="icon iconfont icon-Selected"></i>M</span>
<span data="L"><i class="icon iconfont icon-Selected"></i>L</span>
<span data="XL"><i class="icon iconfont icon-Selected"></i>XL</span>
<span data="2XL"><i class="icon iconfont icon-Selected"></i>2XL</span>
<span data="3XL"><i class="icon iconfont icon-Selected"></i>3XL</span>
</li>
<li>
<input type="hidden" name="shoes" value="<?=$arr_member['detail']['shoes']?>">
<label>鞋子尺码</label>
<span data="26"><i class="icon iconfont icon-Selected"></i>26</span>
<span data="27" class="active"><i class="icon iconfont icon-Selected"></i>27</span>
<span data="28"><i class="icon iconfont icon-Selected"></i>28</span>
<span data="29"><i class="icon iconfont icon-Selected"></i>29</span>
<span data="30"><i class="icon iconfont icon-Selected"></i>30</span>
<span data="31"><i class="icon iconfont icon-Selected"></i>31</span>
</li>
<li>	
<input type="hidden" name="pants" value="<?=$arr_member['detail']['pants']?>">
<label>裤子尺码</label>
<span data="S"><i class="icon iconfont icon-Selected"></i>S</span>
<span data="M"><i class="icon iconfont icon-Selected"></i>M</span>
<span data="L" class="active"><i class="icon iconfont icon-Selected"></i>L</span>
<span data="XL"><i class="icon iconfont icon-Selected"></i>XL</span>
<span data="2XL"><i class="icon iconfont icon-Selected"></i>2XL</span>
</li>
<li>
<input type="hidden" name="is_jinshi" value="<?=$arr_member['detail']['is_jinshi']?>">
<label>是否近视</label>
<span data="1"><i class="icon iconfont icon-Selected"></i>是</span>
<span data="0"><i class="icon iconfont icon-Selected"></i>否</span>
</li>
</ul>
</div>
</li>
<li>
<label>学历</label>
<select class="w_7" name="xueli">
<option <?php if($arr_member['detail']['xueli']=='本科') { ?>selected<?php } ?> value="本科">本科</option>
<option <?php if($arr_member['detail']['xueli']=='大专') { ?>selected<?php } ?> value="大专">大专</option>
</select>
</li>
<li>
<input type="hidden" name="is_shengyu" value="<?=$arr_member['detail']['is_shengyu']?>">
<label>生育</label>
<span data="1" class="active"><i class="icon iconfont icon-Selected"></i>是</span>
<span data="0"><i class="icon iconfont icon-Selected"></i>否</span>
</li>
<li>
<input type="hidden" name="is_hunyin" value="<?=$arr_member['detail']['is_hunyin']?>">
<label>婚姻</label>
<span data="1"><i class="icon iconfont icon-Selected"></i>是</span>
<span data="0"><i class="icon iconfont icon-Selected"></i>否</span>
</li>
<li>
<label>职业</label>
<input class="w_7" name="job" type="text" value="<?=$arr_member['detail']['job']?>" />
</li>
<li>
<label>月收入</label>
<select class="w_7" name="income">
<option <?php if($arr_member['detail']['income']==5000) { ?>selected<?php } ?> value="5000">5000</option>
<option <?php if($arr_member['detail']['income']==10000) { ?>selected<?php } ?> value="10000">10000</option>
</select>
</li>
<li style="display: none">
<label>头像</label>
<a class="w_7" href="#"><i class="icon iconfont icon-yousanjiao-copy-copy"></i></a>
</li>
<li>
<label>昵称</label>
<input class="w_7" name="nickname" type="text" value="<?=$arr_member['realname']?>" />
</li>
<li>
<label>个人介绍</label>
<textarea class="w_10" placeholder="不可以超过200字" name="content" cols="" rows=""><?=$arr_member['detail']['content']?></textarea>
</li>
</ul>
<div class="qued" id="J_send"><a href="javascript:;">确 定</a></div>
</div>
</form>
</div>
<!-- /container -->


<!-- footer -->

<!-- footer end -->
<script src="/mat/dist/js/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="/mat/dist/js/modules/common.js" type="text/javascript"></script>
<script src="/mat/dist/js/language/zh_cn.js" type="text/javascript"></script>
<script src="/mat/dist/js/index/idangerous.swiper.min.js"></script> 
<script src="/mat/dist/js/index/district.js"></script> 
<script src="/mat/dist/js/index/account.js"></script> 
<script>
district._init('J_province','J_city','J_area');
account_data._init();
$(function () {
            	var flag = true; 
            	var clothes = '<?=$arr_member['detail']['clothes']?>';
            	var pants = '<?=$arr_member['detail']['pants']?>';
            	var shoes = '<?=$arr_member['detail']['shoes']?>';
            	var is_jinshi = '<?=$arr_member['detail']['is_jinshi']?>';
            	var is_shengyu = '<?=$arr_member['detail']['is_shengyu']?>';
            	var is_hunyin = '<?=$arr_member['detail']['is_hunyin']?>';
            	$(".more").click(function () {
                    var _this = $(this);
                    if(flag) {
                            $(".figure").addClass("item_da");
                            flag = false;
                    }
                    else {
$(".figure").removeClass("item_da");
                            flag = true;
                    }
            	});
            	$('.active').each(function(){
            	var val = $(this).attr('data')
$(this).closest('li').find('input').val(val)
            	})

            	$('input[name="clothes"]').closest('li').find('span').removeClass('active');
            	$('input[name="clothes"]').closest('li').find('span[data="'+clothes+'"]').addClass('active');
            	$('input[name="pants"]').closest('li').find('span').removeClass('active');
            	$('input[name="pants"]').closest('li').find('span[data="'+pants+'"]').addClass('active');
            	$('input[name="shoes"]').closest('li').find('span').removeClass('active');
            	$('input[name="shoes"]').closest('li').find('span[data="'+shoes+'"]').addClass('active');
            	$('input[name="is_jinshi"]').closest('li').find('span').removeClass('active');
            	$('input[name="is_jinshi"]').closest('li').find('span[data="'+is_jinshi+'"]').addClass('active');
            	$('input[name="is_shengyu"]').closest('li').find('span').removeClass('active');
            	$('input[name="is_shengyu"]').closest('li').find('span[data="'+is_shengyu+'"]').addClass('active');
            	$('input[name="is_hunyin"]').closest('li').find('span').removeClass('active');
            	$('input[name="is_hunyin"]').closest('li').find('span[data="'+is_hunyin+'"]').addClass('active');
});
$('li span').click(function(){
$(this).closest('li').find('span').removeClass('active');
$(this).addClass('active');
var val = $(this).attr('data')
$(this).closest('li').find('input').val(val)
})
</script>

</body>

</html><?php ob_out();?>