var admin_member_shop={_this:"",str_uids:"",_init:function(){admin_member_shop._listen()},_listen:function(){$("#J_send_form").submit(function(){return admin_member_shop.bool_sending?!1:(admin_member_shop.bool_sending=!0,void _ajax_frame(get_obj("ajaxframeid"),admin_member_shop.send_success,"send_success"))})},_listen_del:function(){$(".J_btn_del").click(function(){var a=$(this);confirm_cancle(admin_member_shop._del,arr_language.warn,arr_language.del_3),admin_member_shop._this=a})},_del:function(){var a=admin_member_shop._this,b=a.attr("data-uid");_ajax_jsonp("/admin.php?mod=member_shop&mod_dir=shop&extra=del&uid="+b,admin_member_shop._del_success)},_del_success:function(a){make_alert_html(arr_language.notice,a.info,window.location.href)},send_success:function(a){return _remove_ajax_frame("send_success"),200!=a.status?(admin_member_shop.bool_sending=!1,make_alert_html(arr_language.warn,a.info),!1):void make_alert_html(arr_language.notice,a.info,window.location.href)},_listen_audited:function(){$(".J_audited").click(function(){var a=$(this);confirm_cancle(admin_member_shop._audited,arr_language.warn,arr_language.del_3),admin_member_shop._this=a})},_audited:function(){var a=admin_member_shop._this,b=a.attr("data-uid");_ajax_jsonp("/admin.php?mod=member_shop&mod_dir=shop&extra=audited&uid="+b,admin_member_shop._audited_success)},_audited_success:function(a){make_alert_html(arr_language.notice,a.info,window.location.href)},_listen_bat_audited:function(){$("#J_bat_audited").click(function(){for(var a=$(this),b=$(".J_checkall_sub"),c="",d=0;d<b.length;d++){var e=b.eq(d);"checked"==e.attr("checked")&&(c+=e.val()+",")}return c?(_str_uids=c,confirm_cancle(admin_member_shop._bat_audited,arr_language.warn,arr_language.del_3),void(admin_member_shop._this=a)):(make_alert_html(arr_language.warn,arr_language.no_select),!1)})},_bat_audited:function(){admin_member_shop._this;_ajax_jsonp("/admin.php?mod=member_shop&mod_dir=shop&extra=bat_audited&uids="+_str_uids,admin_member_shop._bat_audited_success)},_bat_audited_success:function(a){make_alert_html(arr_language.notice,a.info,window.location.href)},_listen_bat_phy_del:function(){$("#J_bat_phy_del").click(function(){for(var a=$(this),b=$(".J_checkall_sub"),c="",d=0;d<b.length;d++){var e=b.eq(d);"checked"==e.attr("checked")&&(c+=e.val()+",")}return c?(_str_uids=c,confirm_cancle(admin_member_shop._bat_phy_del,arr_language.warn,arr_language.del_3),void(admin_member_shop._this=a)):(make_alert_html(arr_language.warn,arr_language.no_select),!1)})},_bat_phy_del:function(){admin_member_shop._this;_ajax_jsonp("/admin.php?mod=member_shop&mod_dir=shop&extra=bat_phy_del&uids="+_str_uids,admin_member_shop._bat_phy_del_success)},_bat_phy_del_success:function(a){make_alert_html(arr_language.notice,a.info,window.location.href)},_listen_bat_phy_open:function(){$("#J_bat_phy_open").click(function(){for(var a=$(this),b=$(".J_checkall_sub"),c="",d=0;d<b.length;d++){var e=b.eq(d);"checked"==e.attr("checked")&&(c+=e.val()+",")}return c?(_str_uids=c,confirm_cancle(admin_member_shop._bat_phy_open,arr_language.warn,arr_language.del_3),void(admin_member_shop._this=a)):(make_alert_html(arr_language.warn,arr_language.no_select),!1)})},_bat_phy_open:function(){admin_member_shop._this;_ajax_jsonp("/admin.php?mod=member_shop&mod_dir=shop&extra=bat_phy_open&uids="+_str_uids,admin_member_shop._bat_phy_open_success)},_bat_phy_open_success:function(a){make_alert_html(arr_language.notice,a.info,window.location.href)}},member_recharge={_init:function(){member_recharge._listen()},_listen:function(){var a=member_recharge;$(".J_recharge").click(function(){var b=$(this).attr("data-uid"),c=a._make_html();make_common_open_html(c),$("#J_queren_cc").click(function(){var c=new Object;return c.uid=b,c.money=$("#J_money").val(),isPriceNumber(c.money)?void _ajax_jsonp("/admin.php?mod=member_shop&mod_dir=shop&extra=recharge",a._recharge_success,c):(make_alert_html(arr_language.warn,"请输入正确的金额"),!1)})})},_make_html:function(){var a='<div class="pop_wrap" id="J_open_div" style="width:400px;height:190px;z-index:99999"><div class="pop_title"><span class="f_l">充值</span><a href="javascript:;" class="close f_r" id="J_close_tan">¹Ø±Õ</a></div><div class="pop_main"><div class="pop_content main_c f16" id="J_content_cc">充值金额<input id="J_money" type="text"></div><div class="pop_nav align_c"><a href="javascript:void(0);" class="nav_sure" id="J_queren_cc">确认</a></div></div></div>';return a},_recharge_success:function(a){make_alert_html(arr_language.notice,a.info,window.location.href)}},admin_add_shop={_init:function(){var a=admin_add_shop;a._listen()},_listen:function(){$("#J_send_form").submit(function(){return admin_add_shop._verify()?admin_add_shop.bool_sending?!1:($("#J_form_do").val("add"),admin_add_shop.bool_sending=!0,void _ajax_frame(get_obj("ajaxframeid"),admin_add_shop.send_success,"send_success")):!1})},_verify:function(){return $("#J_type").val()?$("#J_shopname").val()?$("#J_shop_url").val()?!0:(make_alert_html("系统提示","请填写店铺地址"),!1):(make_alert_html("系统提示","请填写店铺名称"),!1):(make_alert_html("系统提示","请选择店铺类型"),!1)},send_success:function(a){return _remove_ajax_frame("send_success"),200!=a.status?(admin_add_shop.bool_sending=!1,make_alert_html(arr_language.warn,a.info),!1):void make_alert_html(arr_language.notice,a.info,"/admin.php?mod=member_shop&mod_dir=shop")}};