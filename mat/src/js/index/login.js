var login = {
	_init:function(){
		var c = login;
		c._listen();
	},
	_listen:function(){
		var c = login;
		$('#J_send_login').click(function(){
				if(!c._verify()){
					return false;	
				}

				c.bool_sending = true;
				_ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');
				setTimeout(function(){
								$('#J_login_form').submit();
							},20);
				
			});		
	},
	_verify:function(){		
		var obj = $('#J_login_phone');
		if(!obj.val()){
			make_alert_html(arr_language['warn'],'请输入手机号');
			return false;
		}
		
		var obj = $('#J_login_password');
		if(!obj.val()){
			make_alert_html(arr_language['warn'],'密码不能为空');
			return false;
		}
		return true;
	},
	send_success:function(_data){
		var c = login;
		_remove_ajax_frame('send_success');
		c.bool_sending = false;
		//console.log(_data);
		if(_data['status']!=200){
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}
		window.location.href = '/';
	}
}