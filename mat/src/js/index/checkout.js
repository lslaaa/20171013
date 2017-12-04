var checkout = {
	loading:false,
	_init:function(){
		var c = checkout;
		c._listen_send();
	},
	_listen_send:function(){
		var c = checkout;
		$('#J_send_order').click(function(){
								var obj = $('#J_order_address_id');
								if(obj.val()<=0){
									console.log(arr_language['address']);
									make_alert_html(arr_language['warn'],arr_language['address']['error_6']);
									return false;	
								}
								if(c.bool_sending){
									return false;	
								}
								//c._make_data();
								c.bool_sending = true;
								_ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');		
								$('#J_order_form').submit();
							});	
	},
	_make_data:function(){
		var c = checkout;
		$('.J_order_data').remove();
		var obj = $('#J_first_name');
		c.add_data('first_name',obj.val());
		
		var obj = $('#J_last_name');
		c.add_data('last_name',obj.val());
		
		var obj = $('#J_address');
		c.add_data('address',obj.val());
		
		var obj = $('#J_city');
		c.add_data('city_2',obj.val());
		
		var obj = $('#J_state');
		c.add_data('province',obj.val());
		
		var obj = $('#J_zip_code');
		c.add_data('zip_code',obj.val());
		
		var obj = $('#J_order_address_id');
		c.add_data('address_id',obj.val());
		
	},
	add_data:function(str_name,str_val){
		$('#J_order_form').append('<input class="J_order_data" type="hidden" name="'+str_name+'" value="'+str_val+'">');
	},
	send_success:function(_data){
		var c = address;
		_remove_ajax_frame('send_success');
		c.bool_sending = false;
		//console.log(_data);
		if(_data['status']!=200){
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}
		window.location.href = '/mall/pay/id-'+_data['data'];
	}
}