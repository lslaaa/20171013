var admin_firend_link={_init:function(){var a=admin_firend_link;a._listen()},_listen:function(){var a=admin_firend_link;$("#J_send_form").submit(function(){if("upload"==$("#J_form_do").val())return!0;var b=$("#J_name");if(!b.val())return make_alert_html(arr_language.warn,arr_language.firend_link.error_1),!1;var b=$("#J_url");return b.val()?a.bool_sending?!1:($("#J_form_do").val("add"),a.bool_sending=!0,void _ajax_frame(get_obj("ajaxframeid"),a.send_success,"send_success")):(make_alert_html(arr_language.warn,arr_language.firend_link.error_2),!1)}),$("#J_pic").change(function(){return $("#J_form_do").val("upload"),a.bool_sending?!1:(a.bool_sending=!0,_ajax_frame(get_obj("ajaxframeid"),a.upload_success,"send_success"),void $("#J_send_form").submit())})},_listen_del:function(){var a=admin_firend_link;$(".J_btn_del").click(function(){var b=$(this);confirm_cancle(a._del,arr_language.warn,arr_language.del_2),a._this=b})},_del:function(){var a=admin_firend_link,b=a._this,c=b.attr("data-fid");_ajax_jsonp("/admin.php?mod=firend_link&mod_dir=firend_link&extra=del&fid="+c,a._del_success)},_del_success:function(a){make_alert_html(arr_language.notice,a.info,window.location.href)},upload_success:function(a){var b=admin_firend_link;return _remove_ajax_frame("upload_success"),$("#J_form_do").val(""),200!=a.status?(b.bool_sending=!1,make_alert_html(arr_language.warn,a.info),!1):(b.bool_sending=!1,$("#J_pic_show").html('<img src="'+a.data+'" style="width:100px">'),void $("#J_pic_url").val(a.data))},send_success:function(a){return _remove_ajax_frame("send_success"),200!=a.status?(c.bool_sending=!1,make_alert_html(arr_language.warn,a.info),!1):void make_alert_html(arr_language.notice,a.info,"/admin.php?mod=firend_link&mod_dir=firend_link")}};