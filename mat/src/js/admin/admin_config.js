var admin_config = {
	_init:function(){
		var c = admin_config;
		c._listen();	
	},
	_listen:function(){
		var c = admin_config;
		$('#J_pic').change(function(){
							$('#J_form_do').val('upload');		
							if(c.bool_sending){
								return false;	
							}
							c.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),c.upload_success,'send_success');
							$('#J_send_form').submit();
						});
		$('#J_send_form').submit(function(){
							if($('#J_form_do').val()=='upload'){
								return true;	
							}
							
 							if(c.bool_sending){
								return false;	
							}
							$('#J_form_do').val('add');
							c.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');			  
						});
	},
	upload_success:function(_data){
		var c = admin_config;
		_remove_ajax_frame('upload_success');
		$('#J_form_do').val('');
		if(_data['status']!=200){
			c.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;
		}
		c.bool_sending = false;
		$('#J_pic_show').html('<img src="'+_data['data']+'" style="width:100px">');
		$('#J_pic_url').val(_data['data']);
	},
	send_success:function(_data){
		var c = admin_config;
		_remove_ajax_frame('send_success');
		//console.log(_data);
		c.bool_sending = false;
		if(_data['status']!=200){
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}
		
		make_alert_html(arr_language['notice'],_data['info']);
	}
}