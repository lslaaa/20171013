<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/shop/item/add|template/shop/header|template/shop/footer', '1512315866', 'template/shop/item/add');?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>壁虎SEO</title>
<link rel="stylesheet" href="/mat/??dist/css/shop/global.css,dist/css/shop/mainstyle.css,dist/css/shop/pop_css.css?code_version=<?=$_SGLOBAL['code_version']?>" type="text/css" />
</head>
<body>
<div style="padding:10px 20px;">

    <!--]] site -->

<style type="text/css">
.pic_ico{background-image: url(/mat/dist/images/shop/nav_ico.jpg);background-repeat: no-repeat;width: 16px;height: 16px;display: inline-block;left: 100px;background-position: -74px -62px;position: absolute;left: 84px;cursor: pointer;}
.comment_dd{position: relative;width: 70%;border: 1px solid #ddd;padding: 3px;margin-left: 13%;margin-bottom: 5px}
.comment_file{filter:alpha(opacity=0);-moz-opacity:0;opacity:0;width:100px;height:36px;position:absolute;left:0;top:0;cursor: pointer;float:left;}
.J_comment_pic_show{position:relative;}
.J_comment{margin-bottom: 10px;}
</style>
<script type="text/javascript" charset="utf-8" src="/lib/editor_lemb/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/lib/editor_lemb/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/lib/editor_lemb/lang/zh-cn/zh-cn.js"></script>
<form id="J_send_form" action="/shop.php?mod=item&extra=add" method="post" target="ajaxframeid" enctype="Multipart/form-data">
    <input type="hidden" name="formhash" value="<?=$_SGLOBAL['formhash']?>">
    <input class="J_callback" type="hidden" name="callback" value="parent.send_success">
    <input id="J_item_id" type="hidden" name="item_id" value="<?=$int_item_id?>" />
    <input id="J_form_do" name="form_do" type="hidden" />
    <input id="J_upload_type" name="upload_type" type="hidden" />
    <input id="J_editor_pic_max_size" type="hidden" value="<?=$arr_config['item_editor_pic']['val']?>" />
<div class="form_list mt10 trade">
   <dl class="mt12">
        <dt>任务模板:</dt>
        <dd>
            <select id="J_temp_id" name="temp_id" style="height:36px; line-height:36px;">
                <option value="">请选择模板</option>
                <?php if(is_array($arr_temp_list['list'])) { foreach($arr_temp_list['list'] as $v) { ?>
                    <option value="<?=$v['item_id']?>" data-type="<?=$v['type']?>" <?php if($v['item_id']== $int_temp_id) { ?>selected<?php } ?>><?=$v['title']?></option>
                <?php } } ?>
            </select>
            <span class="tips red"></span>
        </dd>
    </dl>
    <dl class="mt12 J_no_att_type">
       <dt>任务类型：</dt>
       <dd>
            <input class="not_price J_cid" type="radio" name="cid_1" value="1" checked="checked">付款任务
            <input class="not_price J_cid" type="radio" name="cid_1" value="2">浏览任务
            <span class="tips red">*</span>
        </dd>
    </dl>
    <dl class="mt12">
        <dt>店铺:</dt>
        <dd>
            <select id="J_sid" name="sid" style="height:36px; line-height:36px;">
            	<option value="">请选择店铺</option>
                <?php if(is_array($arr_shop['list'])) { foreach($arr_shop['list'] as $v) { ?>
                    <option value="<?=$v['sid']?>" data-type="<?=$v['type']?>" <?php if($arr_item['main']['sid']== $v['sid']) { ?>selected<?php } ?>><?=$v['shopname']?></option>
                <?php } } ?>
            </select>
            <span class="tips red">*</span>
            <input type="hidden" name="shop_type" value="<?=$arr_item['main']['shop_type']?>">
            <span class="stint_tips" id="J_shop_type"><?=$arr_type_name[$arr_item['main']['shop_type']]?></span>
        </dd>
    </dl>
    <dl class="mt12">
        <dt>端口：</dt>
        <dd>
            <input type="radio" name="duankou" value="1" <?php if($arr_item['main']['duankou']==1 || !$arr_item['main']['duankou']) { ?>checked="checked"<?php } ?>>手机端
            <input type="radio" name="duankou" value="2" <?php if($arr_item['main']['duankou']==2) { ?>checked="checked"<?php } ?>>PC端
        </dd>
    </dl>
    <div id="key_box">
    <?php if($arr_keyword['total']) { ?>
    <?php if(is_array($arr_keyword['list'])) { foreach($arr_keyword['list'] as $k => $v) { ?>
    <dl class="mt12">
        <dt>关键词：</dt>
        <dd>
            <input id="J_key_word" type="text" name="keyword[]" class="text" value="<?=$v['keyword']?>" style="width: 150px">
            <input type="text" name="keyword_num[]" class="text k_num" style="width: 50px;margin-left: 10px" value="<?=$v['total_stock']?>" placeholder="数量">
            <?php if($k==0) { ?>
            <a id="J_add_key" style="padding: 5px 10px;margin-left: 10px;cursor: pointer;" class="blue_nav">增加</a>
            <?php } else { ?>
            <a id="J_del_key" style="padding: 5px 10px;margin-left: 10px;cursor: pointer;" class="red_nav">删除</a>
            <?php } ?>
            <span class="tips red">*</span>
        </dd>
    </dl>
    <?php } } ?>
    <?php } else { ?>
    <dl class="mt12">
        <dt>关键词：</dt>
        <dd>
            <input id="J_key_word" type="text" name="keyword[]" class="text" value="" style="width: 150px">
            <input type="text" name="keyword_num[]" class="text k_num" style="width: 50px;margin-left: 10px" placeholder="数量">
            <a id="J_add_key" style="padding: 5px 10px;margin-left: 10px;cursor: pointer;" class="blue_nav">增加</a>
        </dd>
    </dl>
    <?php } ?>
    </div>
   <dl class="mt12 J_no_att_type">
       <dt>是否指定评语：</dt>
       <dd>
            <input type="radio" name="require[comment]" value="comment_1" price="<?=$arr_sku_price['comment_1']?>" price2="<?=$arr_sku_price2['comment_1']?>" <?php if($arr_require['comment']['val']=='comment_1' || !$arr_require['comment']['val']) { ?>checked<?php } ?>>否
            <input type="radio" name="require[comment]" value="comment_2" price="<?=$arr_sku_price['comment_2']?>" price2="<?=$arr_sku_price2['comment_2']?>"  <?php if($arr_require['comment']['val']=='comment_2') { ?>checked<?php } ?>>是
            <span class="tips red total" price="<?=$arr_require['comment']['price']?>" price2="<?=$arr_require['comment']['price2']?>"></span>
       </dd>
   </dl>
   <dl class="mt12 J_no_att_type" id="J_comment_box" style="<?php if(!$arr_comment['total']) { ?>display: none<?php } ?>">
       <dt>指定评语：</dt>
       <div style="clear: both;"></div>
       <?php if($arr_comment['total']) { ?>
       <?php if(is_array($arr_comment['list'])) { foreach($arr_comment['list'] as $k => $v) { ?>
            <dd style="margin-left: 13%" class="J_comment"><?php echo $k+1 ?><input type="text" name="comment[<?=$k?>]" class="text_z comment" value="<?=$v['comment']?>" style="width: 600px"><span class="tips red">*</span></dd>
       <?php } } ?>
       <?php } ?>
   </dl>
   <dl class="mt12 J_no_att_type">
       <dt>是否指定买家秀：</dt>
       <dd>
            <input type="radio" name="require[pic]" value="pic_1" price="<?=$arr_sku_price['pic_1']?>" price2="<?=$arr_sku_price2['pic_1']?>" <?php if($arr_require['pic']['val']=='pic_1' || !$arr_require['pic']['val']) { ?>checked<?php } ?>>否
            <input type="radio" name="require[pic]" value="pic_2" price="<?=$arr_sku_price['pic_2']?>" price2="<?=$arr_sku_price2['pic_2']?>"  <?php if($arr_require['pic']['val']=='pic_2') { ?>checked<?php } ?>>是
            <span class="tips red total" price="<?=$arr_require['pic']['price']?>" price2="<?=$arr_require['pic']['price2']?>"></span>
       </dd>
   </dl>
   <dl class="mt12 J_no_att_type" id="J_comment_pic_box" style="<?php if(!$arr_comment_pic['total']) { ?>display: none<?php } ?>">
        <dt>指定买家秀：</dt>
        <div style="clear: both;"></div>
        <?php if($arr_comment_pic['total']) { ?>
        <?php if(is_array($arr_comment_pic['list'])) { foreach($arr_comment_pic['list'] as $k => $v) { ?>
        <?php $v['pics'] = explode(',',$v['pics']) ?>
        <dd class="comment_dd">
            <span style="float:left"><?php echo $k+1 ?></span>
            <input name="" class="J_comment_pic comment_file" type="file" data-id="<?=$k?>">
            <a class="upload_pic f_l" href="javascript:;">上传</a>
            <?php if(is_array($v['pics'])) { foreach($v['pics'] as $v2) { ?>
             <span class="J_comment_pic_show"><img src="<?=$v2?>" style="width: 100px"><i class="pic_ico"></i><input type="hidden" class="J_comment_url" name="comment_pic[<?=$k?>][]" value="<?=$v2?>"></span>
            <?php } } ?>
            <div class="comment_up_pic_box">
            </div>
        </dd>
        <?php } } ?>
        <?php } ?>
    </dl>
   <dl class="mt12 J_no_att_type">
       <dt>每小时发布个数：</dt>
       <dd><input id="J_stock" type="text" name="hour_send" class="text_z" value="<?=$arr_item['main']['hour_send']?>"><span class="tips gray">个</span><span class="tips red">默认发布全部</span></dd>
   </dl>
   <dl class="mt12 J_no_att_type">
       <dt>定时发布：</dt>
       <dd><input id="J_release_time" type="text" name="release_time" class="text_z some_class" value="<?php if($arr_item['main']['release_time']) { ?><?php echo date('Y-m-d H:00:00',$arr_item['main']['release_time']) ?><?php } ?>"></span><span class="tips red">默认就当前时间立即发布出去</span></dd>
   </dl>
   <dl class="mt12">
       <dt>淘口令：</dt>
       <dd><textarea name="kouling" style="width: 600px;height:100px"><?=$arr_item['main']['kouling']?></textarea></dd>
   </dl>
   <!-- <dl class="mt12 J_no_att_type">
       <dt>商品名：</dt>
       <dd><input id="J_item_title" type="text" name="item_title" class="text_z" value="<?=$arr_item['main']['item_title']?>" style="width: 600px"><span class="tips red">*</span></dd>
   </dl> -->
   <dl class="mt12">
        <dt>产品主图：</dt>
        <dd>
            <div class="goods_img">
                <div id="J_pic_upload" class="goods_upload">
                    <span class="f_l"><?=$arr_language['item']['change_pic']?>：</span>
                    <span class="f_r">
                        <div style="position:relative;">
                            <input id="J_pic_upload_status" type="button" value="<?=$arr_language['uploads']['upload']?>">
                            <input id="J_pic_upload_btn" type="file" style="position:absolute;left:0;top:0;width:80px;height:32px;filter:alpha(opacity=0);-moz-opacity:0;opacity:0;cursor:pointer;" id="J_upload_main_btn" name="" accept="image/*">
                        </div>
                    </span>
                </div>
                <div class="goods_data">
                    <h2><?=$arr_language['best']?>:<b><?=$str_main_pic_size?></b></h2>
                    <ul id="J_pic_list">
                        <?php $int_li_num = 5 - count($arr_pics); ?>
                        <?php if(is_array($arr_pics)) { foreach($arr_pics as $v) { ?>
                        <?php if($v) { ?>
                        <li>
                        <input type="hidden" name="item_pic" value="<?=$v?>"/>
                        </li>
                        <?php } ?>
                        <?php } } ?>
                        <?php for($i=0;$i<$int_li_num;$i++){ ?>
                        <li><?php if($int_li_num==5 && $i==0) { ?><font><em>*</em> <?=$arr_language['item']['main_pic']?></font><?php } ?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <span class="tips red">*</span>
        </dd>
    </dl>
   <!-- <dl class="mt12 J_no_att_type">
       <dt>产品链接：</dt>
       <dd><input id="J_item_link" type="text" name="item_link" class="text_z" value="<?=$arr_item['main']['item_link']?>" style="width: 600px"><span class="tips red">*</span></dd>
   </dl> -->
   <dl class="mt12">
        <dt>产品价格：</dt>
        <dd>
            <input id="J_price" type="text" name="price" class="text_z" value="<?=$arr_item['main']['price']?>" autocomplete="off" placeholder="按需要下单的实付金额填写" style="width: 450px"><span class="tips gray">元</span>
            <input type="hidden" id="J_sku_price" value="">
            <input type="hidden" id="J_sku_price2" value="">
            <input type="hidden" id="J_require_price" name="require[price]" value="">
            <input type="hidden" id="J_require_price2" name="require[price2]" value="">
            <span class="tips red total" price="<?=$arr_require['price']['price']?>" price2="<?=$arr_require['price']['price2']?>"></span>
        </dd>
    </dl>
   <!--  <dl class="mt12 J_no_att_type">
       <dt>发货地：</dt>
       <dd><input id="J_send_address" type="text" name="send_address" class="text_z" value="<?=$arr_item['main']['send_address']?>" style="width: 600px"></dd>
   </dl>
   <dl class="mt12 J_no_att_type">
       <dt>购买属性：</dt>
       <dd><input id="J_item_attr" type="text" name="item_attr" class="text_z" value="<?=$arr_item['main']['item_attr']?>" style="width: 600px"></dd>
   </dl> -->

   <dl class="mt12 J_no_att_type J_fukuan">
       <dt>购买件数：</dt>
       <dd><input id="J_buy_num" type="text" name="buy_num" class="text_z" value="<?=$arr_item['main']['buy_num']?>" style="width: 200px"><span class="tips red">是指一个买家同个链接一次需要购买几件</span></dd>
   </dl>
   <dl class="mt12 J_no_att_type J_fukuan">
       <dt>优惠券链接：</dt>
       <dd><input id="J_coupon_link" type="text" name="coupon_link" class="text_z" value="<?=$arr_item['main']['coupon_link']?>" style="width: 600px"></dd>
   </dl>
   <dl class="mt12 J_no_att_type J_fukuan">
       <dt>信用卡、花呗：</dt>
       <dd>
            <input type="radio" name="huabei" class="not_price" value="0"  <?php if($arr_item['main']['huabei']!=1) { ?>checked<?php } ?>>不支持
            <input type="radio" name="huabei" class="not_price" value="1" <?php if($arr_item['main']['huabei']==1) { ?>checked<?php } ?>>支持（手续费商家承担）
            <span class="tips red"></span>
       </dd>
   </dl>
   <dl class="mt12 J_no_att_type">
       <dt>与客服假聊：</dt>
       <dd>
            <input type="radio" name="wangwang" class="not_price" value="0"  <?php if($arr_item['main']['wangwang']!=1) { ?>checked<?php } ?>>不需要
            <input type="radio" name="wangwang" class="not_price" value="1" <?php if($arr_item['main']['wangwang']==1) { ?>checked<?php } ?>>需要
            <span class="tips red"></span>
       </dd>
   </dl>
   <dl class="mt12 J_no_att_type">
       <dt>是否收藏产品：</dt>
       <dd>
            <input type="radio" name="is_collect_item" class="not_price" value="0" <?php if($arr_item['main']['is_collect_item']!=1) { ?>checked<?php } ?>>否
            <input type="radio" name="is_collect_item" class="not_price" value="1" <?php if($arr_item['main']['is_collect_item']==1) { ?>checked<?php } ?>>是
            <span class="tips red"></span>
       </dd>
   </dl>
   <dl class="mt12 J_no_att_type">
       <dt>是否收藏店铺：</dt>
       <dd>
            <input type="radio" name="is_collect_shop" class="not_price" value="0"  <?php if($arr_item['main']['is_collect_shop']!=1) { ?>checked<?php } ?>>否
            <input type="radio" name="is_collect_shop" class="not_price" value="1" <?php if($arr_item['main']['is_collect_shop']==1) { ?>checked<?php } ?>>是
            <span class="tips red"></span>
       </dd>
   </dl>
   <dl class="mt12 J_no_att_type J_liulan">
       <dt>是否加入购物车：</dt>
       <dd>
            <input type="radio" name="is_add_cart" class="not_price" value="0"  <?php if($arr_item['main']['is_add_cart']!=1) { ?>checked<?php } ?>>否
            <input type="radio" name="is_add_cart" class="not_price" value="1" <?php if($arr_item['main']['is_add_cart']==1) { ?>checked<?php } ?>>是
            <span class="tips red"></span>
       </dd>
   </dl>
   <dl class="mt12 J_no_att_type J_fukuan">
        <dt>加购收藏后多久付款：</dt>
        <dd>
            <select  name="require[collect]" style="height:36px; line-height:36px;">
                <option value="">随机</option>
                <option value="collect_0" price="<?=$arr_sku_price['collect_0']?>" price2="<?=$arr_sku_price2['collect_0']?>" <?php if($arr_require['collect']['val']=='collect_0') { ?>selected<?php } ?>><?=$arr_price_name['collect_0']?></option>
                <option value="collect_1" price="<?=$arr_sku_price['collect_1']?>" price2="<?=$arr_sku_price2['collect_1']?>" <?php if($arr_require['collect']['val']=='collect_1') { ?>selected<?php } ?>><?=$arr_price_name['collect_1']?></option>
                <option value="collect_2" price="<?=$arr_sku_price['collect_2']?>" price2="<?=$arr_sku_price2['collect_2']?>" <?php if($arr_require['collect']['val']=='collect_2') { ?>selected<?php } ?>><?=$arr_price_name['collect_2']?></option>
                <option value="collect_3" price="<?=$arr_sku_price['collect_3']?>" price2="<?=$arr_sku_price2['collect_3']?>" <?php if($arr_require['collect']['val']=='collect_3') { ?>selected<?php } ?>><?=$arr_price_name['collect_3']?></option>
                <option value="collect_4" price="<?=$arr_sku_price['collect_4']?>" price2="<?=$arr_sku_price2['collect_4']?>" <?php if($arr_require['collect']['val']=='collect_4') { ?>selected<?php } ?>><?=$arr_price_name['collect_4']?></option>
            </select>
            <span class="tips red total" price="<?=$arr_sku_price[$arr_require['collect']['val']]?>" price2="<?=$arr_sku_price2[$arr_require['collect']['val']]?>"></span>
        </dd>
   </dl>
   <dl class="mt12 J_no_att_type">
       <dt>性别要求：</dt>
       <dd>
            <input type="radio" name="require[sex]" value="" price="" price2="" <?php if(!$arr_require['sex']) { ?>checked<?php } ?>>随机
            <input type="radio" name="require[sex]" value="sex_1" price="<?=$arr_sku_price['sex_1']?>" price2="<?=$arr_sku_price2['sex_1']?>" <?php if($arr_require['sex']['val']=='sex_1') { ?>checked<?php } ?>>男
            <input type="radio" name="require[sex]" value="sex_2" price="<?=$arr_sku_price['sex_2']?>" price2="<?=$arr_sku_price2['sex_2']?>" <?php if($arr_require['sex']['val']=='sex_2') { ?>checked<?php } ?>>女
            <span class="tips red total" price="<?=$arr_sku_price[$arr_require['sex']['val']]?>" price2="<?=$arr_sku_price2[$arr_require['sex']['val']]?>"></span>
       </dd>
   </dl>
   <dl class="mt12 J_no_att_type">
       <dt>旺旺等级要求：</dt>
       <dd>
            <select  name="require[level]" style="height:36px; line-height:36px;">
                <option value="">随机</option>
                <option value="level_1" price="<?=$arr_sku_price['level_1']?>" price2="<?=$arr_sku_price2['level_1']?>" <?php if($arr_require['level']['val']=='level_1') { ?>selected<?php } ?>><?=$arr_price_name['level_1']?></option>
                <option value="level_2" price="<?=$arr_sku_price['level_2']?>" price2="<?=$arr_sku_price2['level_2']?>" <?php if($arr_require['level']['val']=='level_2') { ?>selected<?php } ?>><?=$arr_price_name['level_2']?></option>
                <option value="level_3" price="<?=$arr_sku_price['level_3']?>" price2="<?=$arr_sku_price2['level_3']?>" <?php if($arr_require['level']['val']=='level_3') { ?>selected<?php } ?>><?=$arr_price_name['level_3']?></option>
                <option value="level_4" price="<?=$arr_sku_price['level_4']?>" price2="<?=$arr_sku_price2['level_4']?>" <?php if($arr_require['level']['val']=='level_4') { ?>selected<?php } ?>><?=$arr_price_name['level_4']?></option>
                <option value="level_5" price="<?=$arr_sku_price['level_5']?>" price2="<?=$arr_sku_price2['level_5']?>" <?php if($arr_require['level']['val']=='level_5') { ?>selected<?php } ?>><?=$arr_price_name['level_5']?></option>
                <option value="level_6" price="<?=$arr_sku_price['level_6']?>" price2="<?=$arr_sku_price2['level_6']?>" <?php if($arr_require['level']['val']=='level_6') { ?>selected<?php } ?>><?=$arr_price_name['level_6']?></option>
            </select>
            <span class="tips red total" price="<?=$arr_sku_price[$arr_require['level']['val']]?>" price2="<?=$arr_sku_price2[$arr_require['level']['val']]?>"></span>
       </dd>
   </dl>
   <dl class="mt12 J_no_att_type">
       <dt>年龄要求：</dt>
       <dd>
            <select  name="require[age]" style="height:36px; line-height:36px;">
                <option value="">随机</option>
                <option value="age_1" price="<?=$arr_sku_price['age_1']?>" price2="<?=$arr_sku_price2['age_1']?>" <?php if($arr_require['age']['val']=='age_1') { ?>selected<?php } ?>><?=$arr_price_name['age_1']?></option>
                <option value="age_2" price="<?=$arr_sku_price['age_2']?>" price2="<?=$arr_sku_price2['age_2']?>" <?php if($arr_require['age']['val']=='age_2') { ?>selected<?php } ?>><?=$arr_price_name['age_2']?></option>
                <option value="age_3" price="<?=$arr_sku_price['age_3']?>" price2="<?=$arr_sku_price2['age_3']?>" <?php if($arr_require['age']['val']=='age_3') { ?>selected<?php } ?>><?=$arr_price_name['age_3']?></option>
                <option value="age_4" price="<?=$arr_sku_price['age_4']?>" price2="<?=$arr_sku_price2['age_4']?>" <?php if($arr_require['age']['val']=='age_4') { ?>selected<?php } ?>><?=$arr_price_name['age_4']?></option>
                <option value="age_5" price="<?=$arr_sku_price['age_5']?>" price2="<?=$arr_sku_price2['age_5']?>" <?php if($arr_require['age']['val']=='age_5') { ?>selected<?php } ?>><?=$arr_price_name['age_5']?></option>
                <option value="age_6" price="<?=$arr_sku_price['age_6']?>" price2="<?=$arr_sku_price2['age_6']?>" <?php if($arr_require['age']['val']=='age_6') { ?>selected<?php } ?>><?=$arr_price_name['age_6']?></option>
                <option value="age_7" price="<?=$arr_sku_price['age_7']?>" price2="<?=$arr_sku_price2['age_7']?>" <?php if($arr_require['age']['val']=='age_7') { ?>selected<?php } ?>><?=$arr_price_name['age_7']?></option>
            </select>
            <span class="tips red total" price="<?=$arr_sku_price[$arr_require['age']['val']]?>" price2="<?=$arr_sku_price2[$arr_require['age']['val']]?>"></span>
       </dd>
   </dl>
   <dl class="mt12 J_no_att_type">
       <dt>身高要求：</dt>
       <dd>
            <select id="J_height"  name="require[height]" style="height:36px; line-height:36px;">
                <option value="">随机</option>
                <option value="0,150" price="<?=$arr_sku_price['height']?>" price2="<?=$arr_sku_price2['height']?>" <?php if($arr_require['height']['val']=='0,150') { ?>selected<?php } ?>>150以下</option>
                <option value="150,155" price="<?=$arr_sku_price['height']?>" price2="<?=$arr_sku_price2['height']?>" <?php if($arr_require['height']['val']=='150,155') { ?>selected<?php } ?>>150-155</option>
                <option value="155,160" price="<?=$arr_sku_price['height']?>" price2="<?=$arr_sku_price2['height']?>" <?php if($arr_require['height']['val']=='155,160') { ?>selected<?php } ?>>155-160</option>
                <option value="160,165" price="<?=$arr_sku_price['height']?>" price2="<?=$arr_sku_price2['height']?>" <?php if($arr_require['height']['val']=='160,165') { ?>selected<?php } ?>>160-165</option>
                <option value="165,170" price="<?=$arr_sku_price['height']?>" price2="<?=$arr_sku_price2['height']?>" <?php if($arr_require['height']['val']=='165,170') { ?>selected<?php } ?>>165-170</option>
                <option value="175,180" price="<?=$arr_sku_price['height']?>" price2="<?=$arr_sku_price2['height']?>" <?php if($arr_require['height']['val']=='175,180') { ?>selected<?php } ?>>175-180</option>
                <option value="180,300" price="<?=$arr_sku_price['height']?>" price2="<?=$arr_sku_price2['height']?>" <?php if($arr_require['height']['val']=='180,300') { ?>selected<?php } ?>>180以上</option>
            </select>CM
            <span class="tips red total" price="<?=$arr_require['height']['price']?>" price2="<?=$arr_require['height']['price2']?>"></span>
       </dd>
   </dl>
   <dl class="mt12 J_no_att_type">
       <dt>体重要求：</dt>
       <dd>
            <select id="J_weight"  name="require[weight]" style="height:36px; line-height:36px;">
                <option value="">随机</option>
                <option value="0,40" price="<?=$arr_sku_price['weight']?>" price2="<?=$arr_sku_price2['weight']?>" <?php if($arr_require['weight']['val']=='0,40') { ?>selected<?php } ?>>40以下</option>
                <option value="40,45" price="<?=$arr_sku_price['weight']?>" price2="<?=$arr_sku_price2['weight']?>" <?php if($arr_require['weight']['val']=='40,45') { ?>selected<?php } ?>>40-45</option>
                <option value="45,50" price="<?=$arr_sku_price['weight']?>" price2="<?=$arr_sku_price2['weight']?>" <?php if($arr_require['weight']['val']=='45,50') { ?>selected<?php } ?>>45-50</option>
                <option value="50,55" price="<?=$arr_sku_price['weight']?>" price2="<?=$arr_sku_price2['weight']?>" <?php if($arr_require['weight']['val']=='50,55') { ?>selected<?php } ?>>50-55</option>
                <option value="55,60" price="<?=$arr_sku_price['weight']?>" price2="<?=$arr_sku_price2['weight']?>" <?php if($arr_require['weight']['val']=='55,60') { ?>selected<?php } ?>>55-60</option>
                <option value="60,65" price="<?=$arr_sku_price['weight']?>" price2="<?=$arr_sku_price2['weight']?>" <?php if($arr_require['weight']['val']=='60,65') { ?>selected<?php } ?>>60-65</option>
                <option value="65,70" price="<?=$arr_sku_price['weight']?>" price2="<?=$arr_sku_price2['weight']?>" <?php if($arr_require['weight']['val']=='65,70') { ?>selected<?php } ?>>65-70</option>
                <option value="70,75" price="<?=$arr_sku_price['weight']?>" price2="<?=$arr_sku_price2['weight']?>" <?php if($arr_require['weight']['val']=='70,75') { ?>selected<?php } ?>>70-75</option>
                <option value="75,80" price="<?=$arr_sku_price['weight']?>" price2="<?=$arr_sku_price2['weight']?>" <?php if($arr_require['weight']['val']=='75,80') { ?>selected<?php } ?>>75-80</option>
                <option value="80,85" price="<?=$arr_sku_price['weight']?>" price2="<?=$arr_sku_price2['weight']?>" <?php if($arr_require['weight']['val']=='80,85') { ?>selected<?php } ?>>80-85</option>
                <option value="85,90" price="<?=$arr_sku_price['weight']?>" price2="<?=$arr_sku_price2['weight']?>" <?php if($arr_require['weight']['val']=='85,90') { ?>selected<?php } ?>>85-90</option>
                <option value="90,200" price="<?=$arr_sku_price['weight']?>" price2="<?=$arr_sku_price2['weight']?>" <?php if($arr_require['weight']['val']=='90,200') { ?>selected<?php } ?>>90以上</option>
            </select>
            KG<span class="tips red total" price="<?=$arr_require['weight']['price']?>" price2="<?=$arr_require['weight']['price2']?>"></span>
       </dd>
   </dl>
   <dl class="mt12 J_no_att_type">
       <dt>地址距离：</dt>
       <dd>
            <input id="J_local" type="text" name="require[local]" class="text_z" value="<?=$arr_require['local']['val']?>" price="<?=$arr_sku_price['local']?>" price2="<?=$arr_sku_price2['local']?>" style="width: 200px">公里内当天只能匹配1人<span class="tips red total" price="<?=$arr_require['local']['price']?>" price2="<?=$arr_require['local']['price2']?>"></span>
       </dd>
   </dl>
   <dl class="mt12 J_no_att_type">
       <dt>地区要求：</dt>
       <dd>
            <select class="w_3" name="province" id="J_province" data-province="<?=$arr_item['main']['province']?>" price="<?=$arr_sku_price['province']?>" price2="<?=$arr_sku_price2['province']?>" style="height: 36px">
                    <option value="1">省份</option>
                    <option value="2">广东省</option>
            </select>
            <select class="w_3" name="city" id="J_city" data-city="<?=$arr_item['main']['city']?>" style="height: 36px">
                    <option value="1">城市</option>
                    <option value="2">深圳市</option>
            </select>
            <select class="w_3" name="area" id="J_area" data-area="<?=$arr_item['main']['area']?>" style="height: 36px">
                    <option value="1">区/县</option>
                    <option value="2">龙华新区</option>
            </select>
            <span class="tips red total" price="<?php if($arr_item['main']['province']) { ?><?=$arr_sku_price['province']?><?php } ?>" price2="<?php if($arr_item['main']['province']) { ?><?=$arr_sku_price2['province']?><?php } ?>"></span>
       </dd>
   </dl>
   <dl class="mt12 J_no_att_type">
       <dt>货比三家：</dt>
       <dd>
            <input type="radio" name="require[huobi]" value="" price=""  price2="" checked="checked">随机
            <input type="radio" name="require[huobi]" value="huobi_1" price="<?=$arr_sku_price['huobi_1']?>" price2="<?=$arr_sku_price2['huobi_1']?>" <?php if($arr_require['huobi']['val']=='huobi_1') { ?>checked<?php } ?>><?=$arr_price_name['huobi_1']?>
            <input type="radio" name="require[huobi]" value="huobi_2" price="<?=$arr_sku_price['huobi_2']?>" price2="<?=$arr_sku_price2['huobi_2']?>" <?php if($arr_require['huobi']['val']=='huobi_2') { ?>checked<?php } ?>><?=$arr_price_name['huobi_2']?>
            <input type="radio" name="require[huobi]" value="huobi_3" price="<?=$arr_sku_price['huobi_3']?>" price2="<?=$arr_sku_price2['huobi_3']?>" <?php if($arr_require['huobi']['val']=='huobi_3') { ?>checked<?php } ?>><?=$arr_price_name['huobi_3']?>
            <span class="tips red total" price="<?=$arr_sku_price[$arr_require['huobi']['val']]?>" price2="<?=$arr_sku_price2[$arr_require['huobi']['val']]?>"></span>
       </dd>
   </dl>
   <dl class="mt12" id="J_pic_box" <?php if(!$arr_huobi_pic) { ?>style="display: none"<?php } ?>>
                <dt>货比指定商品截图:</dt>
                <dd style="position:relative;width: 10%;<?php if(!$arr_huobi_pic['0']['pic']) { ?>display: none<?php } ?>" id="J_img_add_dd">
                    <input type="hidden" name="data_pic[]" value="<?=$arr_huobi_pic['0']['pic']?>" class="J_pic_url" style="float:left;"/>
                    <input name="" class="J_pic" type="file" style="filter:alpha(opacity=0);-moz-opacity:0;opacity:0;width:100px;height:36px;position:absolute;left:0;top:0;cursor: pointer;float:left;">
                    <a class="upload_pic f_l" href="javascript:;">上传</a>
                    <div class="up_pic_box">
                        <span class="J_pic_show"><?php if($arr_huobi_pic['0']['pic']) { ?><img src="<?=$arr_huobi_pic['0']['pic']?>" style="width:100px;" /><?php } ?></span>
                        <span class="J_shop_name" style="<?php if(!$arr_huobi_pic['0']['pic']) { ?>display: none<?php } ?>"><input type="text" value="<?=$arr_huobi_pic['0']['shop_name']?>" name="shop_name[]" style="width: 88px;height: 20px" placeholder="店铺名称"></span>
                    </div>
      
                </dd>
                <dd style="position:relative;width: 10%;<?php if(!$arr_huobi_pic['1']['pic']) { ?>display: none<?php } ?>" id="J_img_add_dd2">
                    <input type="hidden" name="data_pic[]" value="<?=$arr_huobi_pic['1']['pic']?>" class="J_pic_url" style="float:left;"/>
                    <input name="" class="J_pic" type="file" style="filter:alpha(opacity=0);-moz-opacity:0;opacity:0;width:100px;height:36px;position:absolute;left:0;top:0;cursor: pointer;float:left;">
                    <a class="upload_pic f_l" href="javascript:;">上传</a>
                    <div class="up_pic_box">
                        <span class="J_pic_show"><?php if($arr_huobi_pic['1']['pic']) { ?><img src="<?=$arr_huobi_pic['1']['pic']?>" style="width:100px;" /><?php } ?></span>
                        <span class="J_shop_name" style="<?php if(!$arr_huobi_pic['1']['pic']) { ?>display: none<?php } ?>"><input type="text" value="<?=$arr_huobi_pic['1']['shop_name']?>" name="shop_name[]" style="width: 88px;height: 20px" placeholder="店铺名称"></span>
                    </div>
                </dd>
                <dd style="position:relative;width: 10%;<?php if(!$arr_huobi_pic['2']['pic']) { ?>display: none<?php } ?>" id="J_img_add_dd3">
                    <input type="hidden" name="data_pic[]" value="<?=$arr_huobi_pic['2']['pic']?>" class="J_pic_url" style="float:left;"/>
                    <input name="" class="J_pic" type="file" style="filter:alpha(opacity=0);-moz-opacity:0;opacity:0;width:100px;height:36px;position:absolute;left:0;top:0;cursor: pointer;float:left;">
                    <a class="upload_pic f_l" href="javascript:;">上传</a>
                    <div class="up_pic_box">
                        <span class="J_pic_show"><?php if($arr_huobi_pic['2']['pic']) { ?><img src="<?=$arr_huobi_pic['2']['pic']?>" style="width:100px;" /><?php } ?></span>
                        <span class="J_shop_name" style="<?php if(!$arr_huobi_pic['2']['pic']) { ?>display: none<?php } ?>"><input type="text" value="<?=$arr_huobi_pic['2']['shop_name']?>" name="shop_name[]" style="width: 88px;height: 20px" placeholder="店铺名称"></span>
                    </div>
                </dd>
                <dd style="width: 15px"><span class="tips red">*</span></dd>
            </dl>
   <dl class="mt12 J_no_att_type J_fukuan">
       <dt>下单备注：</dt>
       <dd>
            <input id="J_remark" type="text" name="remark" class="text_z" value="<?=$arr_item['main']['remark']?>" style="width: 200px">
       </dd>
   </dl>
   
<!--     <dl class="mt12 J_no_att_type">
       <dt>优先匹配：</dt>
       <dd>
            <input type="radio" name="require[priority]" value="priority" price="<?=$arr_sku_price['priority']?>" price2="<?=$arr_sku_price2['priority']?>"<?php if($arr_require['priority']) { ?>checked="checked"<?php } ?>>需要
            <input type="radio" name="require[priority]" value="" price="" price2="" <?php if(!$arr_require['priority']) { ?>checked="checked"<?php } ?>>不需要
            <span class="tips red total" price="<?=$arr_sku_price[$arr_require['priority']['val']]?>" price2="<?=$arr_sku_price[$arr_require['priority']['val']]?>"></span>
       </dd>
   </dl> -->
    <dl class="mt12 J_no_att_type">
       <dt>用户信息审核：</dt>
       <dd>
            <input type="radio" name="is_shenhe" class="not_price" value="1" <?php if($arr_item['main']['is_shenhe']==1) { ?>checked="checked"<?php } ?>>需要
            <input type="radio" name="is_shenhe" class="not_price" value="0" <?php if(!$arr_item['main']['is_shenhe']) { ?>checked="checked"<?php } ?>>不需要
            <span class="tips red"></span>
       </dd>
    </dl>
    <dl class="mt12 J_no_att_type" id="J_butie" style="display: none">
       <dt style="color: red">审核扣费规则：</dt>
       <dd>
            <span class="tips red">超过5分钟未审核系统每超过1分钟扣除<?=$_SGLOBAL['data_config']['data_butie']?>个金币，超过30分钟未审核任务自动取消，扣除金币直接打入接单者钱包</span>
       </dd>
    </dl>
    <dl class="mt12">
        <dt>其他要求：</dt>
        <dd>
            <div>
            <!-- <script id="J_content" type="text/plain" style="width:600px;height:400px;" name="content"><?=$arr_item['detail']['content']?></script> -->
            <textarea id="J_content" name="content" style="width:600px;height:100px;"><?=$arr_item['detail']['content']?></textarea>
            </div>
        </dd>
    </dl>
    <dl class="mt12 J_no_att_type J_fukuan">
        <dt>确认收货时间：</dt>
        <dd>
            <select  name="require[confirm]" style="height:36px; line-height:36px;">
                <option value="">随机</option>
                <option value="confirm_1" price="<?=$arr_sku_price['confirm_1']?>" price2="<?=$arr_sku_price2['confirm_1']?>" <?php if($arr_require['confirm']['val']=='confirm_1') { ?>selected<?php } ?>><?=$arr_price_name['confirm_1']?></option>
                <option value="confirm_2" price="<?=$arr_sku_price['confirm_2']?>" price2="<?=$arr_sku_price2['confirm_2']?>" <?php if($arr_require['confirm']['val']=='confirm_2') { ?>selected<?php } ?>><?=$arr_price_name['confirm_2']?></option>
                <option value="confirm_3" price="<?=$arr_sku_price['confirm_3']?>" price2="<?=$arr_sku_price2['confirm_3']?>" <?php if($arr_require['confirm']['val']=='confirm_3') { ?>selected<?php } ?>><?=$arr_price_name['confirm_3']?></option>
                <option value="confirm_4" price="<?=$arr_sku_price['confirm_4']?>" price2="<?=$arr_sku_price2['confirm_4']?>" <?php if($arr_require['confirm']['val']=='confirm_4') { ?>selected<?php } ?>><?=$arr_price_name['confirm_4']?></option>
            </select>
            <span class="tips red total" price="<?=$arr_sku_price[$arr_require['confirm']['val']]?>" price2="<?=$arr_sku_price2[$arr_require['confirm']['val']]?>"></span>
        </dd>
   </dl>
    

     <dl class="mt12">
        <dt>任务单价：</dt>
        <dd>
            <span class="tips red" style="font-size: 20px">￥</span><input type="text" id="J_total_price" name="total_price" placeholder="0" class="text_z" value="<?=$arr_item['main']['total_price']?>" readonly="readonly" style="color: red;font-size: 20px;border: none;">
            <input type="hidden" id="J_total_price2" name="total_price_2" class="text_z" value="<?=$arr_item['main']['total_price_2']?>">
        </dd>
    </dl>
    <dl class="mt12">
        <dt>任务总价：</dt>
        <dd>
            <span class="tips red" style="font-size: 20px">￥</span><input type="text" id="J_payment" name="payment" placeholder="0" class="text_z" value="<?php echo $arr_item['main']['total_price']*$arr_item['main']['total_stock'] ?>" readonly="readonly" style="color: red;font-size: 20px;border: none;">
        </dd>
    </dl>
    <dl class="mt12 J_no_att_type">
       <dt>是否设为任务模板：</dt>
       <dd>
            <input type="radio" name="is_temp" value="0" checked="checked"><span style="line-height: 36px">不需要</span>
            <input type="radio" name="is_temp" value="1">是
            <input type="text" class="text_z" name="title" value="" style="display: none" id="J_item_temp_name">
       </dd>
    </dl>
    <dl class="mt20">
        <dt>&nbsp;</dt>
        <dd class="sure_nav"><input type="submit" class="blue_nav" value="<?php if($arr_item['main']['item_id'] && !$arr_item['main']['is_pay']) { ?>支付<?php } else { ?><?=$arr_language['send']?><?php } ?>" style="border-radius:3px;height:52px; line-height:52px; width:158px; border:0; font-weight:700; font-size:16px;"></dd>
    </dl>
</div>
</form>
<script type="text/javascript" src="/mat/??dist/js/lib/jquery/jquery.min.js,dist/js/language/admin_zh_cn.js,dist/js/modules/common.js,dist/js/shop/shop_item.js?code_version=<?=$_SGLOBAL['code_version']?>"></script>
<link rel="stylesheet" type="text/css" href="/mat/dist/css/lib/jquery.datetimepicker.css">
<script type="text/javascript" src="/mat/dist/js/lib/jquery.datetimepicker.full.js"></script>
<script src="/mat/dist/js/index/district.js"></script> 
<script type="text/javascript">
district._init('J_province','J_city','J_area');
// var ue = UE.getEditor('J_content');
admin_item_pics._init()
shop_item._init()
shop_item.sku_price = '<?=$json_sku_price?>'
shop_item.sku_price2 = '<?=$json_sku_price2?>'

$('#J_temp_id').change(function(){
    var temp_id = $(this).val();
    window.location.href='/shop.php?mod=item&extra=add&temp_id='+temp_id
})
</script>
<script type="text/javascript">
$.datetimepicker.setLocale('ch')
$('.some_class').datetimepicker({

    format:'Y-m-d H:00:00',
    lang:"ch",
});
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