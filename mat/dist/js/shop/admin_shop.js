var admin_shop={_init:function(){var a=admin_shop;a._listen(),a._listen_edit_pwd()},_listen:function(){$("#J_send_form").submit(function(){return admin_shop._verify()?admin_shop.bool_sending?!1:($("#J_form_do").val("add"),admin_shop.bool_sending=!0,void _ajax_frame(get_obj("ajaxframeid"),admin_shop.send_success,"send_success")):!1})},_verify:function(){return $("#J_type").val()?$("#J_shopname").val()?$("#J_username").val()?$("#J_phone").val()?$("#J_contact").val()?$("#J_address").val()?$("#J_shop_url").val()?!0:(make_alert_html("系统提示","请填写店铺地址"),!1):(make_alert_html("系统提示","请填写联系地址"),!1):(make_alert_html("系统提示","请填写联系人"),!1):(make_alert_html("系统提示","请填写联系电话"),!1):(make_alert_html("系统提示","请填写用户昵称"),!1):(make_alert_html("系统提示","请填写店铺名称"),!1):(make_alert_html("系统提示","请选择店铺类型"),!1)},send_success:function(a){return _remove_ajax_frame("send_success"),200!=a.status?(admin_shop.bool_sending=!1,make_alert_html(arr_language.warn,a.info),!1):void make_alert_html(arr_language.notice,a.info,"/shop.php?mod=shop")},_listen_edit_pwd:function(){$("#J_edit_pwd").click(function(){var a=admin_shop,b=($("#J_uid").val(),'<div id="J_open_div" class="pop_wrap" style="width:400px;"><div class="pop_title"><span class="f_l">修改密码</span><a id="J_close_tan" class="close f_r" href="javascript:void(0);">关闭</a></div><div class="pop_main"><form id="J_edit_form" action="/shop.php?mod=shop&extra=mod_detail" method="post" target="ajaxframeid" ><input class="J_callback" type="hidden" value="parent.send_success" name="callback"><input type="password" name="password" id="J_old_pwd" style="height:32px;"><p id="J_error" class="red" style="height:15px; padding:5px 0;"></p><input type="submit" style="border-radius:3px;height:52px; line-height:52px; width:158px; border:0; font-weight:700; font-size:16px;" value="确认" class="blue_nav"></form></div></div>');make_common_open_html(b),a._listen_send_pwd()})},_listen_send_pwd:function(){var a=admin_shop;$("#J_edit_form").unbind("submit"),$("#J_edit_form").submit(function(){var b=$("#J_old_pwd");return b.val()?a.bool_sending?!1:(a.bool_sending=!0,void _ajax_frame(get_obj("ajaxframeid"),a.send_success,"send_success")):($("#J_error").css("display",""),$("#J_error").html("密码不能为空"),!1)})}},admin_shop_del={_init:function(){admin_shop_del._listen_del()},_listen_del:function(){var a=admin_shop_del;$(".J_btn_del").click(function(){var b=$(this);confirm_cancle(a._del,arr_language.warn,arr_language.del_2),a._this=b})},_del:function(){var a=admin_shop_del,b=a._this,c=b.attr("data-id");_ajax_jsonp("/shop.php?mod=shop&extra=del&sid="+c,a._del_success)},_del_success:function(a){make_alert_html(arr_language.notice,a.info,window.location.href)}};