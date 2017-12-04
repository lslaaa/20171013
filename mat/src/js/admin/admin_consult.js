var admin_consult = {
	_init:function(){
		var c = admin_consult;
		c._listen_reply();
		c._listen_del();
	},
	_listen_reply:function(){
		var c = admin_consult;
		$('.J_btn_reply').click(function(){
								var _this = $(this);
								var _index = $('.J_btn_reply').index(this);
								var str_reply = $('.J_reply').eq(_index).html();
								var _html = '<div id="J_open_div" class="pop_wrap" style="width:400px;">'+
												'<div class="pop_title">'+
													'<span class="f_l">'+arr_language['consult']['reply']+'</span>'+
													'<a id="J_close_tan" class="close f_r" href="javascript:void(0);">'+arr_language['close']+'</a>'+
												'</div>'+
												'<div class="pop_main">'+
													'<form id="J_send_form" action="/admin.php?mod=consult&mod_dir=consult&extra=reply" method="post" target="ajaxframeid" >'+
														'<input class="J_callback" type="hidden" value="parent.send_success" name="callback">'+
														'<input type="hidden" value="'+_this.attr('data-id')+'" name="id">'+
														'<textarea id="J_reply" name="reply" style="width:350px; height:100px;">'+str_reply+'</textarea>'+
														'<p id="J_error" class="red" style="height:15px; padding:5px 0;"></p>'+
														'<input type="submit" style="border-radius:3px;height:52px; line-height:52px; width:158px; border:0; font-weight:700; font-size:16px;" value="'+arr_language['consult']['reply']+'" class="blue_nav">'+
													'</form>'+
												'</div>'+
											'</div>';
								make_common_open_html(_html);
								c._listen_send();
							});
		
	},
	_listen_send:function(){
		var c = admin_consult;
		$('#J_send_form').unbind('submit');
		$('#J_send_form').submit(function(){							
							var obj = $('#J_reply');
							if(!obj.val()){
								$('#J_error').css('display','');
								$('#J_error').html(arr_language['consult']['error_1']);
								return false;
							}
							
							if(c.bool_sending){
								return false;	
							}
							c.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');			  
						});	
	},
	send_success:function(_data){
		var c = admin_consult;
		_remove_ajax_frame('send_success');
		//console.log(_data);
		if(_data['status']!=200){
			c.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);
	},
	_listen_del:function(){
		var c = admin_consult;
		$('.J_btn_del').click(function(){
								var _this = $(this);
								confirm_cancle(c._del,arr_language['warn'],arr_language['del_2']);
								c._this = _this;
							});
	},
	_del:function(){
		var c = admin_consult;
		var _this = c._this;
		var int_id = _this.attr('data-id');
		_ajax_jsonp('/admin.php?mod=consult&mod_dir=consult&extra=del&id='+int_id,c._del_success);
	},
	_del_success:function(_data){
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);	
	}
}