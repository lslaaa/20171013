var admin_seo = {
	_init:function(){
		$('#J_send_form').submit(function(){
							if(admin_seo.bool_sending){
								return false;	
							}
							
							$('#J_form_do').val('add');
							admin_seo.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),admin_seo.send_success,'send_success');			  
						});
	},
	send_success:function(_data){
		_remove_ajax_frame('send_success');
		//console.log(_data);
		if(_data['status']!=200){
			admin_seo.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);
	}
}