var menu={_this:"",_init:function(){$("#J_add_level_one").click(function(){var a=$(this),b=new Object;b.pid=0,b._do="add",b.mid=0,b.name="",b.cname="",b.url="",b.sort=200;var c=menu.make_html(b);a.parent().next(".J_submit_from").html(c),menu.listen_send()}),$(".J_show_hide_menu_add").click(function(){$(".J_submit_from").html("");var a=$(this),b=a.parent().parent(),c=new Object;c.pid=b.attr("data-mid"),c._do="add",c.mid=0,c.name="",c.cname="",c.url="",c.sort=200;var d=menu.make_html(c);a.parent().next(".J_submit_from").html(d),menu.listen_send()}),$(".J_show_hide_menu_mod").click(function(){$(".J_submit_from").html("");var a=$(this),b=a.parent().parent(),c=b.attr("data-mid");_ajax_jsonp("/admin.php?mod=menu&extra=get_one&mod_dir=setting&mid="+c,menu._mod),menu._this=a}),$(".J_show_hide_menu_del").click(function(){$(".J_submit_from").html("");var a=$(this);confirm_cancle(menu._del,arr_language.warn,arr_language.del_2),menu._this=a})},listen_send:function(){$("#J_btn_send").unbind("click"),$("#J_btn_cancel").unbind("click"),$("#J_btn_send").click(function(){menu.verify()}),$("#J_btn_cancel").click(function(){$(".J_submit_from").html("")})},_mod:function(a){var b=menu._this,a=a.data;a._do="mod";var c=menu.make_html(a);b.parent().next(".J_submit_from").html(c),menu.listen_send()},_del:function(){var a=menu._this,b=a.parent().parent(),c=b.attr("data-mid");_ajax_jsonp("/admin.php?mod=menu&extra=del&mod_dir=setting&mid="+c,menu._del_success)},_del_success:function(a){200==a.status&&(window.location.href=window.location.href)},verify:function(){var a=$("#J_name");if(!a.val())return make_alert_html(arr_language.warn,arr_language.setting.menu.error_1),!1;var b=$("#J_sort");return b.val()?menu.bool_sending?!1:(_ajax_frame(get_obj("ajaxframeid"),menu.send_success,"send_success"),$("#J_send_form").submit(),void(menu.bool_sending=!0)):(make_alert_html(arr_language.warn,arr_language.setting.menu.error_3),!1)},send_success:function(a){return _remove_ajax_frame("send_success"),200!=a.status?(menu.bool_sending=!1,make_alert_html(arr_language.warn,a.info),!1):void(window.location.href=window.location.href)},make_html:function(a){var b='<form action="" method="post" id="J_send_form" target="ajaxframeid"><input type="hidden" name="formhash" value="'+str_formhash+'"/><input type="hidden" id="J_pid" name="pid" value="'+a.pid+'"/><input type="hidden" id="J_do" name="_do" value="'+a._do+'"/><input type="hidden" id="J_id" name="mid" value="'+a.mid+'"/><input class="J_callback" type="hidden" name="callback" value="parent.send_success"><div class="add_box"><em>'+arr_language.setting.menu.name+':</em><input type="text" id="J_name" name="name" value="'+a.name+'">';return menu.show_cname&&(b+="<em>"+arr_language.setting.menu.cname+':</em><input type="text" id="J_name" name="cname" value="'+a.cname+'">'),b+="<em>"+arr_language.url+':</em><input type="text" id="J_url" name="url" value="'+a.url+'"><em>'+arr_language.sort+':</em><input type="text" id="J_sort" name="sort" value="'+a.sort+'"><a id="J_btn_send" class="blue_nav">'+arr_language.send+'</a><a id="J_btn_cancel" class="gray_nav">'+arr_language.cancel+"</a></div></form>"}};