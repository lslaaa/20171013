var admin_video_cat={_this:"",bool_sending:!1,_init:function(){var a=admin_video_cat;$("#J_add_level_one").click(function(){var b=$(this),c=new Object;if(c.pid=0,c._do="add",c.cid=0,c.name="","object"==typeof json_languages)for(var d,e=0;d=json_languages[e++];)c["name_"+d.cname]="";c.url="",c.pic="",c.sort=200;var f=admin_video_cat.make_html(c);b.parent().next(".J_submit_from").html(f),a._listen_upload(),a.listen_send()}),$(".J_show_hide_menu_add").click(function(){$(".J_submit_from").html("");var b=$(this),c=b.parent().parent(),d=new Object;if(d.pid=c.attr("data-cid"),d._do="add",d.cid=0,d.name="","object"==typeof json_languages)for(var e,f=0;e=json_languages[f++];)d["name_"+e.cname]="";d.url="",d.pic="",d.sort=200;var g=admin_video_cat.make_html(d);b.parent().next(".J_submit_from").html(g),a._listen_upload(),a.listen_send()}),$(".J_show_hide_menu_mod").click(function(){$(".J_submit_from").html("");var b=$(this),c=b.parent().parent(),d=c.attr("data-cid");_ajax_jsonp("/admin.php?mod=video&mod_dir=video&extra=get_one_cat&cid="+d,admin_video_cat._mod),a._this=b}),$(".J_show_hide_menu_del").click(function(){$(".J_submit_from").html("");var b=$(this);confirm_cancle(admin_video_cat._del,arr_language.warn,arr_language.del_2),a._this=b})},listen_send:function(){$("#J_btn_send").unbind("click"),$("#J_btn_cancel").unbind("click"),$("#J_btn_send").click(function(){admin_video_cat.verify()}),$("#J_btn_cancel").click(function(){$(".J_submit_from").html("")})},_mod:function(a){var b=admin_video_cat,c=b._this,a=a.data;a._do="mod";var d=b.make_html(a);c.parent().next(".J_submit_from").html(d),b._listen_upload(),b.listen_send()},_del:function(){var a=admin_video_cat._this,b=a.parent().parent(),c=b.attr("data-cid");_ajax_jsonp("/admin.php?mod=video&extra=del_cat&mod_dir=video&cid="+c,admin_video_cat._del_success)},_del_success:function(a){200==a.status&&(window.location.href=window.location.href)},verify:function(){var a=$("#J_name");if(!a.val())return make_alert_html(arr_language.warn,arr_language.page.error_3),!1;if("object"==typeof json_languages)for(var b,c=0;b=json_languages[c++];){var d=$("#J_name_"+b.cname);if(!d.val())return make_alert_html(arr_language.warn,b.name+arr_language.mall.item_cat.error_1),!1}var e=$("#J_sort");return e.val()?admin_video_cat.bool_sending?!1:(_ajax_frame(get_obj("ajaxframeid"),admin_video_cat.send_success,"send_success"),$("#J_send_form").submit(),void(admin_video_cat.bool_sending=!0)):(make_alert_html(arr_language.warn,arr_language.setting.menu.error_3),!1)},send_success:function(a){return _remove_ajax_frame("send_success"),200!=a.status?(admin_video_cat.bool_sending=!1,make_alert_html(arr_language.warn,a.info),!1):void(window.location.href=window.location.href)},make_html:function(a){console.log(a);var b="";b="video"==a.type?'<select name="p_type"><option value="image">图片</option><option value="video" selected="selected">视频</option></select>':'<select name="p_type"><option value="image" selected="selected">图片</option><option value="video">视频</option></select>';var c='<form action="" method="post" id="J_send_form" target="ajaxframeid" enctype="Multipart/form-data"><input type="hidden" name="formhash" value="'+str_formhash+'"/><input type="hidden" id="J_pid" name="pid" value="'+a.pid+'"/><input type="hidden" id="J_do" name="_do" value="'+a._do+'"/><input type="hidden" id="J_id" name="cid" value="'+a.cid+'"/><input class="J_callback" type="hidden" name="callback" value="parent.send_success"><div class="add_box"><p><em>'+arr_language.type+":</em>"+b+"</p><p><em>"+arr_language.cat+':</em><input type="text" id="J_name" name="name" value="'+a.name+'"></p>';if("object"==typeof json_languages)for(var d,e=0;d=json_languages[e++];)c+="<em>"+d.name+':</em><input type="text" id="J_name_'+d.cname+'" name="name_'+d.cname+'" value="'+a["name_"+d.cname]+'">';return c+="<p><em>"+arr_language.pic+':</em><a class="blue_nav updown"><input id="J_pic_url" name="pic" type="hidden" value="'+a.pic+'"><input id="J_pic" type="file" name="pic">'+arr_language.upload.upload_2+'</a><p id="J_pic_show" class="updown_img">'+(a.pic?'<img  style="width:100px" src="'+a.pic+'">':"")+"</p></p><p><em>"+arr_language.sort+':</em><input type="text" id="J_sort" name="sort" value="'+a.sort+'"></p><p><a id="J_btn_send" class="blue_nav">'+arr_language.send+'</a><a id="J_btn_cancel" class="gray_nav">'+arr_language.cancel+"</a></p></div></form>"},_listen_upload:function(){var a=admin_video_cat;$("#J_pic").unbind("change"),$("#J_pic").change(function(){return a.last_do=$("#J_do").val(),$("#J_do").val("upload"),a.bool_sending?!1:(a.bool_sending=!0,_ajax_frame(get_obj("ajaxframeid"),a.upload_success,"send_success"),void $("#J_send_form").submit())})},upload_success:function(a){var b=admin_video_cat;return _remove_ajax_frame("upload_success"),$("#J_form_do").val(""),200!=a.status?(b.bool_sending=!1,make_alert_html(arr_language.warn,a.info),!1):(b.bool_sending=!1,$("#J_do").val(b.last_do),$("#J_pic_show").html('<img src="'+a.data+'" style="width:100px">'),void $("#J_pic_url").val(a.data))}};