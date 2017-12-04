var reg = {
 	_init:function(){
 		var c = reg 
 		c._listen_get_checkcode();
 		c._listen()
 	},

 	_settime:function(val){
            var c = reg;
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
            var c = reg;
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
 		var c = reg
		$('#regsubmit').click(function(){
					if(!c._verify()){
						return false;	
					}

					c.bool_sending = true;
					_ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');		
					$('#J_reg_form').submit();
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
			make_alert_html(arr_language['warn'],'请填写密码');
			return false;
		}
		
		var obj_2 = $('#J_password_2');
		if(!obj_2.val()){
			make_alert_html(arr_language['warn'],'请填写确认密码');
			return false;
		}

		if(obj.val()!=obj_2.val()){
			make_alert_html(arr_language['warn'],'密码和确认密码不一致');
			return false;
		}
		
	
		return true;
	},
	send_success:function(_data){
		var c = reg;
		_remove_ajax_frame('send_success');
		c.bool_sending = false;
		//console.log(_data);
		if(_data['status']!=200){
			make_alert_html(arr_language['notice'],_data['info']);
			return false;	
		}
                make_alert_html('提示',_data['info'],'/');
		// window.location.href = '/';
	}

}