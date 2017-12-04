var address = {
	_init:function(){
		var c = address;
		c._listen_add();
		c._listen_mod();
		c._listen_save();
		c._listen_choose();
	},
	_listen_choose:function(){
		var c = address;
		$('.J_address_list').unbind('click');
		$('.J_address_list').click(function(){
									var _this = $(this);			
									_this.find('span').addClass('on');
									_this.siblings().find('span').removeClass('on');
									$('#J_order_address_id').val(_this.attr('id').replace('J_address_',''));
								});	
		
	},
	_listen_add:function(){
		$('#J_btn_add_address').click(function(){
								$('#J_add_address').css('display','');
								$('#J_address_id').val(0);
							});
		$('#J_save_address').click(function(){
										   
							});
		$('#J_cancel_address').click(function(){
								$('#J_add_address').css('display','none');		   
							});
	},
	_listen_mod:function(){
		var c = address;
		$('.J_btn_edit_address').unbind('click');
		$('.J_btn_edit_address').click(function(){
									var _this = $(this);			
									var _data = new Object;
									_data.address_id = _this.attr('data-address_id');
									_ajax_jsonp('/mall/get_address',c._get_address_success,_data);	
								});	
		$('#J_btn_order_step_1_edit').click(function(){
									$('#J_order_step_1').css('display','');	
									$('#J_order_step_1_title').addClass('pc_title_on');
								});
	},
	_listen_save:function(){
		var c = address;
		$('#J_save_address').click(function(){
								
								if(!c._verify_address()){
									return false;	
								}
								if(c.bool_sending){
									return false;	
								}	
								var obj = $('#J_pc_login_info');
								if(obj.length<=0){//未登陆
									$('#J_order_step_1').css('display','none');
									$('#J_order_step_1_title').removeClass('pc_title_on');
									return false;	
								}
								c.bool_sending = true;
								_ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');		
								$('#J_address_form').submit();
							});
	},
	_verify_address:function(){		
		var obj = $('#J_contact');
		if(!obj.val()){
			make_alert_html(arr_language['warn'],arr_language['address']['error_1']);
			return false;
		}
		
		var obj = $('#J_phone');
		if(!obj.val()){
			make_alert_html(arr_language['warn'],arr_language['address']['error_2']);
			return false;
		}
		
		var obj = $('#J_area');
		if(obj.val()<=0){
			make_alert_html(arr_language['warn'],arr_language['address']['error_3']);
			return false;
		}
		
		var obj = $('#J_address');
		if(!obj.val()){
			make_alert_html(arr_language['warn'],arr_language['address']['error_4']);
			return false;
		}
		
		var obj = $('#J_zip_code');
		if(!obj.val()){
			make_alert_html(arr_language['warn'],arr_language['address']['error_5']);
			return false;
		}
		return true;
	},
	_get_address_success:function(_data){
		var arr_data = _data['data'];
		$('#J_add_address').css('display','');
		$('#J_contact').val(arr_data['contact']);
		$('#J_phone').val(arr_data['phone']);
		
		$('#J_address').val(arr_data['address']);
		$('#J_zip_code').val(arr_data['zip_code']);
		$('#J_address_id').val(arr_data['address_id']);	
		$('#J_province').attr('data-province',arr_data['province']);
		$('#J_city').attr('data-city',arr_data['city']);
		$('#J_area').attr('data-area',arr_data['area']);
		district._init('J_province','J_city','J_area');
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
		var arr_data = _data['data'];
		$('#J_add_address').css('display','none');
		$('#J_contact').val('');
		$('#J_phone').val('');
		$('#J_address').val('');
		$('#J_zip_code').val('');
		$('#J_address_id').val('');
		
		var str_address_id = 'J_address_'+arr_data['address_id'];
		if($('#'+str_address_id).length>0){
			var obj = $('#'+str_address_id);
			obj.find('.J_show_address_nickname').html(arr_data['address_nickname']);
			obj.find('.J_show_first_name').html(arr_data['first_name']);
			obj.find('.J_show_last_name').html(arr_data['last_name']);
			obj.find('.J_show_address').html(arr_data['address']);
			return false;
		}
					
		var _html = '<div  id="J_address_'+arr_data['address_id']+'" class="J_address_list sure_address">'+
						'<div class="edit_address">'+
							'<span><i>&nbsp;</i><em>'+arr_language['address']['des_1']+'</em></span>'+
							'<a class="J_btn_edit_address" data-address_id="'+arr_data['address_id']+'" href="#add_address">'+arr_language['edit']+'</a>'+
						'</div>'+
						'<p class="name">'+arr_language['address']['contact']+'：<i class="J_show_first_name">'+arr_data['contact']+'</i></p>'+
						'<p>'+arr_language['address']['phone']+'：<i class="J_show_phone">'+arr_data['phone']+'</i></p>'+
						'<p>'+arr_language['address']['address']+'：<i class="J_show_address">'+arr_data['address']+'</i>&nbsp;&nbsp;&nbsp;'+arr_language['address']['zip_code']+'<i class="J_show_address">'+arr_data['zip_code']+'</i></p>'+
					'</div>';
		if($('.J_address_list').length<=0){
			$('#J_address_list').append(_html);
		}
		else{
			$(_html).insertAfter($('.J_address_list').last());
		}
		c._listen_mod();
		c._listen_choose();
	//	make_alert_html(arr_language['notice'],_data['info'],'/admin.php?mod=ads&mod_dir=ads');
	}
	
}