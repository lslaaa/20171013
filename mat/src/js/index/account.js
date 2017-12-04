var account = {
	_init:function(){
		var c = account;
		c._listen();
		// c._listen_refresh();
		// c._listen_verify_account();
	},
	_listen:function(){
		var c = account;
		$(function(){
			$('.J_file').on('change', function(){
						var _index = $('.J_file').index(this);
						if(c.bool_sending){
							return false;	
						}
						// $('.J_file').eq(_index).next().next().html('图片上传中')
						c.upload_index = _index;
						c.bool_sending = true;
						 lrz(this.files[0], {width: 640})
							.then(function (rst) {
								//console.log(rst.base64);
								$.ajax({
									url: '/index.php?mod=account&extra=upload',
									type: 'post',
									data: {pic: rst.base64},
									dataType: 'json',
									timeout: 200000,
									error:  function(msg){
										 alert( "Data Saved: " + msg );
									   }
									,
									success:  function(msg){
										c.upload_success(msg);
										// alert( "Data Saved: " + msg );
									   }
								});
									
							})
							.catch(function (err) {
				
							})
							.always(function () {
				
							});
					});
		});
		$('#J_send').click(function(){
								if($('#J_form_do').val()=='upload'){
									return true;	
								}
								if(!c._verify()){
									return false;	
								}
								if(c.bool_sending){
									return false;	
								}
								$('#J_error_tip').css('display','none');
								$('.J_file').attr('disabled',true);
								$('#J_form_do').val('add');
								c.bool_sending = true;
								_ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');	
								setTimeout(function(){
											$('#J_send_form').submit();		
										},100);
								
							});		
	},
	_listen_refresh:function(){
		$('#J_refresh_check_code').click(function(){
								$('#J_check_code_pic').attr('src','/index/check_code?'+Math.random())
							});
	},
	_listen_verify_account:function(){
		var c = account;
		$('#J_account').blur(function(){
								var _data = new Object();
								_data.account = $('#J_account').val();
								_ajax_jsonp('/service/verify_account',c._verify_account_success,_data);		
							});	
	},
	_verify_account_success:function(_data){
		if(_data['status']==302){
			$('#J_error_tip').html(_data['info']);	
			$('#J_error_tip').css('display','');
		}	
	},
	_verify:function(){
	
		var obj = $('#J_taobao');
		if(!obj.val()){
			make_alert_html('提示','请填写您的淘宝账号');
			return false;
		}
		
		var obj = $('.J_file_url').eq(0);
		if(!obj.val()){
			make_alert_html('提示','请上传您的淘宝截图');
			return false;
		}

		var obj = $('.J_file_url').eq(1);
		if(!obj.val()){
			make_alert_html('提示','请上传您的个人资料截图');
			return false;
		}
		
		
		return true;
	},
	upload_success:function(_data){
		var c = account;
		var _index = c.upload_index;
		_remove_ajax_frame('upload_success');
		weui_loading_remove();
		$('.J_file').attr('disabled',false);
		$('#J_form_do').val('');
		if(_data['status']!=200){
			c.bool_sending = false;
			make_alert_html('提示',_data['info']);
			return false;
		}
		c.bool_sending = false;
		$('.icon-jia1').eq(_index).css({'background-image':'url('+_data['data']+')','background-size':'120px','background-repeat':'no-repeat'});
		$('.J_file_url').eq(_index).val(_data['data']);
	},
	send_success:function(_data){
		var c = account;
		$('.J_file').attr('disabled',false);
		_remove_ajax_frame('send_success');
		c.bool_sending = false;
		weui_loading_remove();
		//console.log(_data);
		
		if(_data['status']!=200){
			make_alert_html('提示',_data['info']);
			return false;	
		}
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);
		//window.location.href = '/';
	}
}

var account_data = {
	_init:function(){
		var c = account_data;
		c._listen();
		// c._listen_refresh();
		// c._listen_verify_account_data();
	},
	_listen:function(){
		var c = account_data;
		$(function(){
			$('.J_file').on('change', function(){
						var _index = $('.J_file').index(this);
						if(c.bool_sending){
							return false;	
						}
						// $('.J_file').eq(_index).next().next().html('图片上传中')
						c.upload_index = _index;
						c.bool_sending = true;
						 lrz(this.files[0], {width: 640})
							.then(function (rst) {
								//console.log(rst.base64);
								$.ajax({
									url: '/index.php?mod=account&extra=upload',
									type: 'post',
									data: {pic: rst.base64},
									dataType: 'json',
									timeout: 200000,
									error:  function(msg){
										 alert( "Data Saved: " + msg );
									   }
									,
									success:  function(msg){
										c.upload_success(msg);
										// alert( "Data Saved: " + msg );
									   }
								});
									
							})
							.catch(function (err) {
				
							})
							.always(function () {
				
							});
					});
		});
		$('#J_send').click(function(){
								if($('#J_form_do').val()=='upload'){
									return true;	
								}
								if(!c._verify()){
									return false;	
								}
								if(c.bool_sending){
									return false;	
								}
								$('#J_error_tip').css('display','none');
								$('.J_file').attr('disabled',true);
								$('#J_form_do').val('add');
								c.bool_sending = true;
								_ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');	
								setTimeout(function(){
											$('#J_send_form').submit();		
										},100);
								
							});		
	},
	_listen_refresh:function(){
		$('#J_refresh_check_code').click(function(){
								$('#J_check_code_pic').attr('src','/index/check_code?'+Math.random())
							});
	},
	_listen_verify_account_data:function(){
		var c = account_data;
		$('#J_account_data').blur(function(){
								var _data = new Object();
								_data.account_data = $('#J_account_data').val();
								_ajax_jsonp('/service/verify_account_data',c._verify_account_data_success,_data);		
							});	
	},
	_verify_account_data_success:function(_data){
		if(_data['status']==302){
			$('#J_error_tip').html(_data['info']);	
			$('#J_error_tip').css('display','');
		}	
	},
	_verify:function(){
	
		var obj = $('input[name="card_num"]');
		if(!obj.val()){
			make_alert_html('提示','请填写证件号码');
			return false;
		}

		var obj = $('input[name="card_name"]');
		if(!obj.val()){
			make_alert_html('提示','请填写证件姓名');
			return false;
		}

		var obj = $('input[name="age"]');
		if(!obj.val()){
			make_alert_html('提示','请填写年龄');
			return false;
		}

		var obj = $('#J_province');
		console.log(obj.val())
		if(obj.val()<1){
			make_alert_html('提示','请选择省');
			return false;
		}

		var obj = $('#J_city');
		// alert(obj.val())
		if(obj.val()<1){
			make_alert_html('提示','请选择城市');
			return false;
		}

		var obj = $('#J_area');
		if(obj.val()<1){
			make_alert_html('提示','请选择区/县');
			return false;
		}

		var obj = $('input[name="job"]');
		if(!obj.val()){
			make_alert_html('提示','请填写职业');
			return false;
		}
		
		
		return true;
	},
	upload_success:function(_data){
		var c = account_data;
		var _index = c.upload_index;
		_remove_ajax_frame('upload_success');
		weui_loading_remove();
		$('.J_file').attr('disabled',false);
		$('#J_form_do').val('');
		if(_data['status']!=200){
			c.bool_sending = false;
			make_alert_html('提示',_data['info']);
			return false;
		}
		c.bool_sending = false;
		$('.icon-jia1').eq(_index).css({'background-image':'url('+_data['data']+')','background-size':'120px','background-repeat':'no-repeat'});
		$('.J_file_url').eq(_index).val(_data['data']);
	},
	send_success:function(_data){
		var c = account_data;
		$('.J_file').attr('disabled',false);
		_remove_ajax_frame('send_success');
		c.bool_sending = false;
		weui_loading_remove();
		//console.log(_data);
		
		if(_data['status']!=200){
			make_alert_html('提示',_data['info']);
			return false;	
		}
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);
		//window.location.href = '/';
	}
}

