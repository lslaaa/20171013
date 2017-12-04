var member_password = {
	_init:function(){
		var c = member_password;
		c._listen();	
	},
	_listen:function(){
		var c = member_password;
		$('#J_send').click(function(){
					if(!c._verify()){
						return false;	
					}

					c.bool_sending = true;
					_ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');		
					$('#J_send_form').submit();
				});	
	},
	_verify:function(){		
		var obj = $('#J_password_old');
		if(!obj.val()){
			make_alert_html(arr_language['warn'],'请填写旧密码');
			return false;
		}
		
		var obj_2 = $('#J_password_new');
		if(!obj_2.val()){
			make_alert_html(arr_language['warn'],'请填写新密码');
			return false;
		}

		if(obj.val()==obj_2.val()){
			make_alert_html(arr_language['warn'],'旧密码和新密码不能一样');
			return false;
		}
		
	
		return true;
	},
	send_success:function(_data){
		var c = member_password;
		_remove_ajax_frame('send_success');
		c.bool_sending = false;
		//console.log(_data);
		if(_data['status']!=200){
			make_alert_html(arr_language['notice'],_data['info']);
			return false;	
		}
                make_alert_html('提示',_data['info'],'/i/security');
		// window.location.href = '/';
	}
}


var two_password = {
 	_init:function(){
 		var c = two_password 
 		c._listen_get_checkcode();
 		c._listen()
 	},

 	_settime:function(val){
            var c = two_password;
            if (c.countdown == 0) { 
                val.attr("id", 'J_get_checkcode');    
                val.html("获取验证码"); 
                clearTimeout(timeid);
            } else { 
                val.attr("id", ""); 
                val.html(c.countdown+"s后重新获取"); 
                c.countdown--; 
                setTimeout(function() { 
                    timeid = c._settime(val);
                  },1000);
            }      
            
        },
        
        _listen_get_checkcode:function(){      
            var c = two_password;
            $('#J_get_checkcode').live('click',function(){
                var phone = $('#J_phone').val();
                if(!_verify_mobile(phone)){
                    make_alert_html('提示','请填写手机号码');
                    return false;                    
                }
                var param = {}; // 组装发送参数
                param['phone'] = phone;                                    
                $.get(
                        '/reg?extra=get_phone_code',
                        param,
                        function(data){
                            if(data.status == 200){                     
                                    c.countdown = 60;
                                    c._settime($('#J_get_checkcode')); 
                            }else{
                                make_alert_html('提示',data.info);
                            }
                        }
                        ,'json');
                
            });
        },
 	_listen:function(){
 		var c = two_password
		$('#J_send').click(function(){
					if(!c._verify()){
						return false;	
					}

					c.bool_sending = true;
					_ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');		
					$('#J_send_form').submit();
				});	
		// $('#J_refresh_check_code').click(function(){
		//         $('#J_check_code_pic').attr('src','/reg/check_code?'+Math.random())
		// }); 

 	},
 	_verify:function(){		
		var obj = $('#J_phone');
		if(!obj.val()){
			make_alert_html(arr_language['warn'],'请填写手机号码');
			return false;
		}
		var obj = $('#J_checkcode');
		if(!obj.val()){
			make_alert_html(arr_language['warn'],'请填写验证码');
			return false;
		}
		var obj = $('#J_password');
		if(!obj.val()){
			make_alert_html(arr_language['warn'],'请填写二级密码');
			return false;
		}
		
	
		return true;
	},
	send_success:function(_data){
		var c = two_password;
		_remove_ajax_frame('send_success');
		c.bool_sending = false;
		//console.log(_data);
		if(_data['status']!=200){
			make_alert_html(arr_language['notice'],_data['info']);
			return false;	
		}
                make_alert_html('提示',_data['info'],'/i/security');
		// window.location.href = '/';
	}

}


var bd_alipay = {
        _init:function(){
                var c = bd_alipay;
                c._listen();    
        },
        _listen:function(){
                var c = bd_alipay;
                $('#J_send').click(function(){
                                        if(!c._verify()){
                                                return false;   
                                        }

                                        c.bool_sending = true;
                                        _ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');              
                                        $('#J_send_form').submit();
                                });     
        },
        _verify:function(){             
                var obj = $('#J_alipay_name');
                if(!obj.val()){
                        make_alert_html(arr_language['warn'],'请填写姓名');
                        return false;
                }
                
                var obj = $('#J_alipay');
                if(!obj.val()){
                        make_alert_html(arr_language['warn'],'请填写支付宝账号');
                        return false;
                }

                
        
                return true;
        },
        send_success:function(_data){
                var c = bd_alipay;
                _remove_ajax_frame('send_success');
                c.bool_sending = false;
                //console.log(_data);
                if(_data['status']!=200){
                        make_alert_html(arr_language['notice'],_data['info']);
                        return false;   
                }
                make_alert_html('提示',_data['info'],'/i');
                // window.location.href = '/';
        }
}

var bd_bank = {
        _init:function(){
                var c = bd_bank;
                c._listen();    
        },
        _listen:function(){
                var c = bd_bank;
                $('#J_send').click(function(){
                                        if(!c._verify()){
                                                return false;   
                                        }

                                        c.bool_sending = true;
                                        _ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');              
                                        $('#J_send_form').submit();
                                });     
        },
        _verify:function(){             
                var obj = $('#J_bank');
                if(!obj.val()){
                        make_alert_html(arr_language['warn'],'请填写开户行');
                        return false;
                }
                
                var obj = $('#J_bank_card');
                if(!obj.val()){
                        make_alert_html(arr_language['warn'],'请填写银行卡账号');
                        return false;
                }

                var obj = $('#J_bank_card_name');
                if(!obj.val()){
                        make_alert_html(arr_language['warn'],'请填写银行卡姓名');
                        return false;
                }
                
        
                return true;
        },
        send_success:function(_data){
                var c = bd_bank;
                _remove_ajax_frame('send_success');
                c.bool_sending = false;
                //console.log(_data);
                if(_data['status']!=200){
                        make_alert_html(arr_language['notice'],_data['info']);
                        return false;   
                }
                make_alert_html('提示',_data['info'],'/i');
                // window.location.href = '/';
        }
}