var login = {
	bool_sending:false,
	_init:function(){
		$('#J_login_from').submit(function(){
			if(!login.check()){
				return false;
			}
			if(login.bool_sending){
				return false;	
			}
			_ajax_frame(get_obj('ajaxframeid'),login.send_success,'send_success');
			login.bool_sending = true;
		});
	},
	check:function(){
		var obj_username = $('#J_username');
		if(!obj_username.val()){
			make_alert_html(arr_language['warn'],arr_language['login']['error_1']);
			return false;
		}
		
		var obj_password = $('#J_password');
		if(!obj_password.val()){
			make_alert_html(arr_language['warn'],arr_language['login']['error_2']);
			return false;
		}
		return true;
	},
	send_success:function(_data){
		_remove_ajax_frame('send_success');
		//console.log(_data);
		if(_data['status']!=200){
			login.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}	
		window.location.href = '/admin.php?mod=main';
	}
}