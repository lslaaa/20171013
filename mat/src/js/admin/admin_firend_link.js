var admin_firend_link = {
	_init:function(){
		var c = admin_firend_link;
		c._listen();	
	},
	_listen:function(){
		var c = admin_firend_link;
		$('#J_send_form').submit(function(){
							if($('#J_form_do').val()=='upload'){
								return true;	
							}
							var obj = $('#J_name');
							if(!obj.val()){
								make_alert_html(arr_language['warn'],arr_language['firend_link']['error_1']);
								return false;	
							}
							
							var obj = $('#J_url');
							if(!obj.val()){
								make_alert_html(arr_language['warn'],arr_language['firend_link']['error_2']);
								return false;	
							}

							if(c.bool_sending){
								return false;	
							}
							$('#J_form_do').val('add');
							c.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');			  
						});
		$('#J_pic').change(function(){
							$('#J_form_do').val('upload');		
							if(c.bool_sending){
								return false;	
							}
							//$('#J_pic').attr('disabled',true);
							c.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),c.upload_success,'send_success');
							$('#J_send_form').submit();
						});
	},
	_listen_del:function(){
		var c = admin_firend_link;
		$('.J_btn_del').click(function(){
								var _this = $(this);
								confirm_cancle(c._del,arr_language['warn'],arr_language['del_2']);
								c._this = _this;
							});
	},
	_del:function(){
		var c = admin_firend_link;
		var _this = c._this;
		var int_fid = _this.attr('data-fid');
		_ajax_jsonp('/admin.php?mod=firend_link&mod_dir=firend_link&extra=del&fid='+int_fid,c._del_success);
	},
	_del_success:function(_data){
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);	
	},
	upload_success:function(_data){
		var c = admin_firend_link;
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
		_remove_ajax_frame('send_success');
		//console.log(_data);
		if(_data['status']!=200){
			c.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}
		make_alert_html(arr_language['notice'],_data['info'],'/admin.php?mod=firend_link&mod_dir=firend_link');
	}
}