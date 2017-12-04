var admin_member = {
	_this:'',
	_init:function(){
		admin_member._listen();	
	},
	_listen:function(){
		$('#J_send_form').submit(function(){
							var obj_name = $('#J_name');
							if(!obj_name.val()){
								make_alert_html(arr_language['warn'],arr_language['admin_member']['error_1']);
								return false;
							}
							
							var obj_uid = $('#J_uid');
							if(!obj_uid.val()){
								var obj_password = $('#J_password');
								if(!obj_password.val()){
									make_alert_html(arr_language['warn'],arr_language['admin_member']['error_2']);
									return false;
								}
							}
							
							var obj_realname = $('#J_realname');
							if(!obj_realname.val()){
								make_alert_html(arr_language['warn'],arr_language['admin_member']['error_3']);
								return false;
							}
							if(admin_member.bool_sending){
								return false;	
							}
							admin_member.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),admin_member.send_success,'send_success');			  
						});
	},
	_listen_del:function(){
		$('.J_btn_del').click(function(){
								var _this = $(this);
								confirm_cancle(admin_member._del,arr_language['warn'],arr_language['del_2']);
								admin_member._this = _this;
							});
	},
	_del:function(){
		var _this = admin_member._this;
		var int_uid = _this.attr('data-uid');
		_ajax_jsonp('/admin.php?mod=member&extra=del&mod_dir=setting&uid='+int_uid,admin_member._del_success);
	},
	_del_success:function(_data){
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);	
	},
	send_success:function(_data){
		_remove_ajax_frame('send_success');
		//console.log(_data);
		if(_data['status']!=200){
			admin_member.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);
	}
}