var shop_item_temp={_init:function(){var a=shop_item_temp;a._listen()},_listen:function(){var c=shop_item_temp;$("#J_send_form").submit(function(){return"upload"==$("#J_form_do").val()?!0:($("#J_form_do").val("add_temp"),shop_item_temp._verify()?shop_item_temp.bool_sending?!1:(shop_item_temp.bool_sending=!0,void _ajax_frame(get_obj("ajaxframeid"),shop_item_temp.send_success,"send_success")):!1)}),$(".J_pic").change(function(){return $("#J_form_do").val("upload"),c._index=$(".J_pic").index(this),$(this).attr("name","pic"),c.bool_sending?!1:(c.bool_sending=!0,_ajax_frame(get_obj("ajaxframeid"),c.upload_success,"send_success"),void $("#J_send_form").submit())}),$("#J_comment_pic_box").on("change",".J_comment_pic",function(){return $("#J_form_do").val("upload"),c._index=$(".J_comment_pic").index(this),$(this).attr("name","pic"),c.bool_sending?!1:(c.bool_sending=!0,_ajax_frame(get_obj("ajaxframeid"),c.upload_pic_success,"send_success"),void $("#J_send_form").submit())}),$("#J_comment_pic_box").on("click",".pic_ico",function(){$(this).closest(".J_comment_pic_show").remove()}),$("#J_sid").change(function(){var _sku_price=eval("("+c.sku_price+")"),_sku_price2=eval("("+c.sku_price2+")"),shop_type=$(this).find("option:selected").attr("data-type");if(10==shop_type){var str_type="天猫";c.jichu_price||(c.jichu_price=_sku_price.type_10),c.jichu_price2||(c.jichu_price2=_sku_price2.type_10)}else if(20==shop_type){var str_type="淘宝";c.jichu_price||(c.jichu_price=_sku_price.type_10),c.jichu_price2||(c.jichu_price2=_sku_price2.type_10)}else if(30==shop_type){var str_type="京东";c.jichu_price||(c.jichu_price=_sku_price.type_40),c.jichu_price2||(c.jichu_price2=_sku_price2.type_40)}else if(40==shop_type){var str_type="蘑菇街";c.jichu_price||(c.jichu_price=_sku_price.type_30),c.jichu_price2||(c.jichu_price2=_sku_price2.type_30)}else if(50==shop_type){var str_type="拼多多";c.jichu_price||(c.jichu_price=_sku_price.type_20),c.jichu_price2||(c.jichu_price2=_sku_price2.type_20)}else if(60==shop_type){var str_type="非电商";c.jichu_price||(c.jichu_price=_sku_price.type_50),c.jichu_price2||(c.jichu_price2=_sku_price2.type_50)}c._sum_total(),$("#J_shop_type").text(str_type),$("#J_shop_type").prev().val(shop_type)}),$("#J_add_key").click(function(){var a='<dl class="mt12"><dt>&nbsp;</dt><dd><input id="J_key_word" type="text" name="keyword[]" class="text" value="" style="width: 150px"><input type="text" name="keyword_num[]" class="text k_num" style="width: 50px;margin-left: 10px" placeholder="数量"><a id="J_del_key" style="padding: 5px 10px;margin-left: 10px;cursor: pointer;" class="red_nav">删除</a>';$("#key_box").append(a)}),$("#key_box").on("click","#J_del_key",function(){$(this).closest("dl").remove()}),$("#J_price").blur(function(){var _sku_price=eval("("+c.sku_price+")"),_sku_price2=eval("("+c.sku_price2+")"),price=Number($(this).val());if(price>0&&100>=price)var show_price=_sku_price.price_1,show_price2=_sku_price2.price_1;else if(price>100&&200>=price)var show_price=_sku_price.price_2,show_price2=_sku_price2.price_2;else if(price>200&&300>=price)var show_price=_sku_price.price_3,show_price2=_sku_price2.price_3;else if(price>300)var price_num=Math.floor(price/100),add_price=_sku_price.add_price,add_price2=_sku_price2.add_price,show_price=add_price*price_num,show_price2=add_price2*price_num;$(this).parent().find("#J_sku_price").val(show_price),$(this).parent().find("#J_sku_price2").val(show_price2),$(this).parent().find("#J_require_price").val(show_price*buy_num),$(this).parent().find("#J_require_price2").val(show_price2*buy_num),$(this).parent().find(".total").text("+"+show_price*buy_num),$(this).parent().find(".total").attr("price",show_price*buy_num),$(this).parent().find(".total").attr("price2",show_price2*buy_num),c._sum_total()}),$(".trade").on("blur","#J_buy_num,.k_num",function(){if("J_buy_num"==$(this).attr("id")){var a=Number($("#J_sku_price").val()),b=Number($("#J_sku_price2").val()),d=Number($("#J_buy_num").val());d||(d=1),$("#J_price").parent().find("#J_require_price").val(a*d),$("#J_price").parent().find("#J_require_price2").val(b*d),$("#J_price").parent().find(".total").text("+"+a*d),$("#J_price").parent().find(".total").attr("price",a*d),$("#J_price").parent().find(".total").attr("price2",b*d)}c._sum_total()}),$(".J_cid").click(function(){var a=$(this).val();1==a?($(".J_fukuan").show(),$(".J_liulan").hide()):($(".J_fukuan").hide(),$(".J_liulan").show())}),$('input[type="radio"]').not(".not_price").click(function(){if("is_shenhe"!=$(this).attr("name")&&"is_temp"!=$(this).attr("name")){var a=$(this).attr("price"),b=$(this).attr("price2");if("require[huobi]"==$(this).attr("name")&&("huobi_1"==$(this).val()&&($("#J_img_add_dd").show(),$("#J_img_add_dd2").hide(),$("#J_img_add_dd3").hide()),"huobi_2"==$(this).val()&&($("#J_img_add_dd").show(),$("#J_img_add_dd2").show(),$("#J_img_add_dd3").hide()),"huobi_3"==$(this).val()&&($("#J_img_add_dd").show(),$("#J_img_add_dd2").show(),$("#J_img_add_dd3").show()),""==$(this).val()&&($("#J_img_add_dd").hide(),$("#J_img_add_dd2").hide(),$("#J_img_add_dd3").hide())),a?($(this).parent().find(".tips").text("+"+a),$(this).parent().find(".tips").attr("price",a),$(this).parent().find(".tips").attr("price2",b)):($(this).parent().find(".tips").text(""),$(this).parent().find(".tips").attr("price",""),$(this).parent().find(".tips").attr("price2","")),c._sum_total(),"require[pic]"==$(this).attr("name")){var d=0;if($(".k_num").each(function(){$(this).val()&&isPriceNumber($(this).val())&&(d+=Number($(this).val()))}),"pic_2"==$(this).val()&&d>0){$("#J_comment_pic_box").show(),$(".comment_dd").remove();for(var e=0;d>e;e++)$("#J_comment_pic_box").append('<dd class="comment_dd"><span style="float:left">'+(e+1)+'</span><input name="" class="J_comment_pic comment_file" type="file" data-id="'+e+'"><a class="upload_pic f_l" href="javascript:;">上传</a><div class="comment_up_pic_box"></div></dd>')}else $("#J_comment_pic_box").hide(),$(".comment_dd").remove()}if("require[comment]"==$(this).attr("name")){var d=0;if($(".k_num").each(function(){$(this).val()&&isPriceNumber($(this).val())&&(d+=Number($(this).val()))}),"comment_2"==$(this).val()&&d>0){$("#J_comment_box").show(),$(".J_comment").remove();for(var e=0;d>e;e++)$("#J_comment_box").append('<dd style="margin-left: 13%" class="J_comment">'+(e+1)+'<input type="text" name="comment['+e+']" class="text_z comment" value="" style="width: 600px"><span class="tips red">*</span></dd>')}else $("#J_comment_box").hide(),$(".J_comment").remove()}}else"is_temp"==$(this).attr("name")&&1==$(this).val()&&$("#J_item_temp_name").show(),"is_temp"==$(this).attr("name")&&1!=$(this).val()&&$("#J_item_temp_name").hide()}),$('input[name="is_shenhe"]').click(function(){var a=$(this).val();1==a?$("#J_butie").show():$("#J_butie").hide()}),$("select").not("#J_sid").change(function(){if("J_province"!=$(this).attr("id")&&"J_city"!=$(this).attr("id")&&"J_area"!=$(this).attr("id"))var a=$(this).find("option:selected").attr("price"),b=$(this).find("option:selected").attr("price2");else var a=$("#J_province").attr("price"),b=$("#J_province").attr("price2");a?($(this).parent().parent().find(".tips").text("+"+a),$(this).parent().parent().find(".tips").attr("price",a),$(this).parent().parent().find(".tips").attr("price2",b)):($(this).parent().parent().find(".tips").text(""),$(this).parent().parent().find(".tips").attr("price",""),$(this).parent().parent().find(".tips").attr("price2","")),c._sum_total()}),$("#J_local,#J_province").blur(function(){var a=$(this).attr("price"),b=$(this).attr("price2"),d=$(this).val();a&&d?($(this).parent().find(".tips").text("+"+a),$(this).parent().find(".tips").attr("price",a),$(this).parent().find(".tips").attr("price2",b)):($(this).parent().find(".tips").text(""),$(this).parent().find(".tips").attr("price",""),$(this).parent().find(".tips").attr("price2","")),c._sum_total()}),$("#J_stock").blur(function(){c._sum_total()})},_verify:function(){if(!$("#J_sid").val())return make_alert_html("系统提示","请选择店铺"),!1;if(!$('input[name="keyword[]"]').eq(0).val())return make_alert_html("系统提示","请填写关键词"),!1;var a=!0;if($('input[name="keyword[]"]').each(function(){return $(this).val()?void 0:(a=!1,!1)}),!a)return make_alert_html("系统提示","请填写关键字"),!1;var b=!0;if($('input[name="keyword_num[]"]').each(function(){return $(this).val()?void 0:(b=!1,!1)}),!b)return make_alert_html("系统提示","请填写关键字数量"),!1;if("comment_2"==$('input[name="require[comment]"]:checked').val()){var c=!0;if($(".comment").each(function(){return $(this).val()?void 0:(c=!1,!1)}),!c)return make_alert_html("系统提示","请填写指定评语"),!1}if("pic_2"==$('input[name="require[pic]"]:checked').val()){var d=!0,e=0;if($(".J_comment_url").each(function(){return $(this).val()?void e++:(d=!1,!1)}),!d||0==e)return make_alert_html("系统提示","请填写指定买家秀"),!1}if(!$("#J_price").val())return make_alert_html("系统提示","请填写价格"),!1;if(!$("#J_buy_num").val())return make_alert_html("系统提示","请填写购买件数"),!1;var f=$(".J_has_pic");if(0==f.length)return make_alert_html("系统提示","请至少上传一张商品图片"),!1;if(!$("#J_pic_box").is(":hidden")){if(!("huobi_1"!=$('input[name="require[huobi]"]:checked').val()||$(".J_pic_url").eq(0).val()&&$(".J_shop_name").eq(0).find("input").val()))return make_alert_html("系统提示","请上传货比商品图片和店铺名称"),!1;if("huobi_2"==$('input[name="require[huobi]"]:checked').val()&&!($(".J_pic_url").eq(0).val()&&$(".J_shop_name").eq(0).find("input").val()&&$(".J_pic_url").eq(1).val()&&$(".J_shop_name").eq(1).find("input").val()))return make_alert_html("系统提示","请上传货比商品图片和店铺名称"),!1;if("huobi_3"==$('input[name="require[huobi]"]:checked').val()&&!($(".J_pic_url").eq(0).val()&&$(".J_shop_name").eq(0).find("input").val()&&$(".J_pic_url").eq(1).val()&&$(".J_shop_name").eq(1).find("input").val()&&$(".J_pic_url").eq(2).val()&&$(".J_shop_name").eq(2).find("input").val()))return make_alert_html("系统提示","请上传货比商品图片和店铺名称"),!1}return!0},_sum_total:function(){var c=shop_item_temp,_sku_price=eval("("+c.sku_price+")"),_sku_price2=eval("("+c.sku_price2+")"),shop_type=$('input[name="shop_type"]').val(),item_price=Number($("#J_price").val()),buy_num=Number($("#J_buy_num").val());c.jichu_price||(10==shop_type&&(c.jichu_price=_sku_price.type_10),20==shop_type&&(c.jichu_price=_sku_price.type_20),30==shop_type&&(c.jichu_price=_sku_price.type_30),40==shop_type&&(c.jichu_price=_sku_price.type_40),50==shop_type&&(c.jichu_price=_sku_price.type_50)),c.jichu_price2||(10==shop_type&&(c.jichu_price2=_sku_price2.type_10),20==shop_type&&(c.jichu_price2=_sku_price2.type_20),30==shop_type&&(c.jichu_price2=_sku_price2.type_30),40==shop_type&&(c.jichu_price2=_sku_price2.type_40),50==shop_type&&(c.jichu_price2=_sku_price2.type_50));var k_num=0;$(".k_num").each(function(){$(this).val()&&isPriceNumber($(this).val())&&(k_num+=Number($(this).val()))}),console.log(shop_type),console.log(c.jichu_price),console.log(c.jichu_price2);var total=parseFloat(c.jichu_price),total2=parseFloat(c.jichu_price2);$(".total").each(function(){var a=$(this).attr("price"),b=$(this).attr("price2");total+=Number(a),total2+=Number(b)}),total+=item_price*buy_num,total2+=item_price*buy_num,console.log(total),console.log(total2);var stock=Number($("#J_stock").val());$("#J_total_price").val(total),$("#J_total_price2").val(total2),$("#J_payment").val(total*k_num)},upload_success:function(a){var b=shop_item_temp;return _remove_ajax_frame("upload_success"),$("#J_form_do").val(""),$(".J_pic").eq(b._index).attr("name",""),200!=a.status?(b.bool_sending=!1,make_alert_html(arr_language.warn,a.info),!1):(b.bool_sending=!1,$(".J_pic_show").eq(b._index).html('<img src="'+a.data+'" style="width:100px">'),$(".J_pic_url").eq(b._index).val(a.data),void $(".J_shop_name").eq(b._index).show())},upload_pic_success:function(a){var b=shop_item_temp;_remove_ajax_frame("upload_success"),$("#J_form_do").val(""),$(".J_comment_pic").eq(b._index).attr("name","");var c=$(".J_comment_pic").eq(b._index).attr("data-id");return 200!=a.status?(b.bool_sending=!1,make_alert_html(arr_language.warn,a.info),!1):(b.bool_sending=!1,void $(".comment_up_pic_box").eq(b._index).append(' <span class="J_comment_pic_show"><img src="'+a.data+'" style="width: 100px"><i class="pic_ico"></i><input type="hidden" class="J_comment_url" name="comment_pic['+c+'][]" value="'+a.data+'"></span>'))},send_success:function(a){return _remove_ajax_frame("send_success"),200!=a.status?(shop_item_temp.bool_sending=!1,make_alert_html(arr_language.warn,a.info),!1):void make_alert_html(arr_language.notice,a.info,window.location.href)},_listen_bat_inactive:function(){$("#J_bat_inactive").click(function(){for(var a=$(this),b=$(".J_checkall_sub"),c="",d=0;d<b.length;d++){var e=b.eq(d);"checked"==e.attr("checked")&&(c+=e.val()+",")}return c?(_str_ids=c,confirm_cancle(shop_item_temp.bat_inactive,arr_language.warn,arr_language.del_3),void(shop_item_temp._this=a)):(make_alert_html(arr_language.warn,arr_language.no_select),!1)})},bat_inactive:function(){_ajax_jsonp("/shop.php?mod=item&extra=bat_inactive&ids="+_str_ids,shop_item_temp.bat_inactive_success)},bat_inactive_success:function(a){make_alert_html(arr_language.notice,a.info,window.location.href)}},shop_item_temp_del={_init:function(){shop_item_temp_del._listen_del()},_listen_del:function(){var a=shop_item_temp_del;$(".J_btn_del").click(function(){var b=$(this);confirm_cancle(a._del,arr_language.warn,arr_language.del_2),a._this=b})},_del:function(){var a=shop_item_temp_del,b=a._this,c=b.attr("data-item_id");_ajax_jsonp("/shop.php?mod=item_temp&extra=del&item_id="+c,a._del_success)},_del_success:function(a){make_alert_html(arr_language.notice,a.info,window.location.href)}},admin_item_pics={uploading:!1,pic_max_num:5,_init:function(){var a=admin_item_pics;a._listen_pic_upload_btn();for(var b=$("#J_pic_list input"),c="",d=0;d<b.length;d++){var e=b.eq(d).val(),f=b.eq(d).attr("data-pid");c=a.make_pic_html(d,e,f),$("#J_pic_list").children("li").eq(d).html(c),d>=a.pic_max_num-1&&$("#J_pic_upload").css("display","none")}a._listen_move_del_pic()},_listen_pic_upload_btn:function(){var a=admin_item_pics;$("#J_pic_upload_btn").change(function(){return $(this).attr("name","pic"),a.uploading?(make_alert_html(arr_language.warn,"有一张图片正在上传中，请稍后..."),!1):($("#J_do").val("upload_image"),setTimeout(function(){_ajax_frame(get_obj("ajaxframeid"),a._pic_upload_success,"send_success"),get_obj("J_send_form").submit()},10),$(".J_sku_pic_upload_btn").attr("disabled",!0),a.uploading=!0,$("#J_form_do").val("upload"),void $("#J_pic_upload_status").val(arr_language.upload.uploading))})},_pic_upload_success:function(a){var b=admin_item_pics;if($("#J_form_do").val(""),$("#J_pic_upload_btn").attr("name",""),$(".J_sku_pic_upload_btn").attr("disabled",!1),b.uploading=!1,$("#J_pic_upload_status").val(arr_language.upload.upload),_remove_ajax_frame("send_success"),200==a.status){var c=$("#J_pic_list").children("li"),d=$(".J_has_pic").length;c.eq(d).html(b.make_pic_html(d,a.data)),d>=b.pic_max_num-1&&$("#J_pic_upload").css("display","none"),b._reset_listen_move_del_pic()}else make_alert_html(arr_language.warn,arr_language.error_1)},make_pic_html:function(a,b){var c=_html_move_del_btn=left_btn_style=right_btn_style="";0==a&&(left_btn_style=' style="display:none;"'),4==a&&(right_btn_style=' style="display:none;"'),_html_move_del_btn="<i"+left_btn_style+' class="J_move_pic_left nico g_l" title="向左移"><!--左--></i>',_html_move_del_btn+="<i"+right_btn_style+' class="J_move_pic_right nico g_r" title="向右移"><!--右--></i>',_html_move_del_btn+='<i class="J_del_pic nico g_c" title="删除"><!--删除--></i>';var c='<div class="goods_nav J_has_pic">';return c+=_html_move_del_btn+"</div>",c+='<span><img src="'+b+'"></span>',c+='<input type="hidden" name="pic[]" value="'+b+'">'},_listen_move_del_pic:function(){var a=admin_item_pics;$(".J_move_pic_left").click(function(){var b=$(".J_move_pic_left").index(this);a._move_pic(b,"left")}),$(".J_move_pic_right").click(function(){var b=$(".J_move_pic_right").index(this);a._move_pic(b,"right")}),$(".J_del_pic").click(function(){var b=$(".J_del_pic").index(this);a._del_pic(b)})},_remove_listen_move_del_pic:function(){$(".J_move_pic_left").unbind("click"),$(".J_move_pic_right").unbind("click"),$(".J_del_pic").unbind("click")},_reset_listen_move_del_pic:function(){var a=admin_item_pics;a._remove_listen_move_del_pic(),a._listen_move_del_pic();var b=$(".J_has_pic");b.children(".J_move_pic_left").css("display",""),b.children(".J_move_pic_right").css("display",""),b.eq(0).children(".J_move_pic_left").css("display","none"),b.eq(b.length-1).children(".J_move_pic_right").css("display","none")},_move_pic:function(a,b){var c=admin_item_pics,d=$("#J_pic_list").children("li").eq(a);if("left"==b){var e=$("#J_pic_list").children("li").eq(a-1);e.before(d)}else if("right"==b){var e=$("#J_pic_list").children("li").eq(a+1);d.before(e)}c._reset_listen_move_del_pic()},_del_pic:function(a){var b=admin_item_pics;if(!confirm(arr_language.del_2))return!1;b._remove_listen_move_del_pic();var c=$("#J_pic_list").children("li").eq(a),d=new Object;d.filename=c.children("input").val(),d.pid=c.children("input").attr("data-pid"),d.item_id=$("#J_item_id").val(),c.remove(),0==$(".J_has_pic").length?$("#J_pic_list").children("li").eq(0).before("<li><font><em>*</em> "+arr_language.mall.main_pic+"</font></li>"):$("#J_pic_list").append("<li></li>"),$("#J_pic_upload").css("display",""),b._reset_listen_move_del_pic()}};