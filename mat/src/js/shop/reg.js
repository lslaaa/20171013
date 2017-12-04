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
                val.text("获取验证码"); 
                clearTimeout(timeid);
            } else { 
                val.attr("id", ""); 
                val.text(c.countdown+"s后可重新获取"); 
                c.countdown--; 
                setTimeout(function() { 
                    timeid = c._settime(val);
                  },1000);
            }      
            
        },
        
        _listen_get_checkcode:function(){      
            var c = reg;
            $('#J_get_checkcode').live('click',function(){
                var mobile = $('input[name="mobile"]').val();
                if(!_verify_mobile(mobile)){
                    make_alert_html('提示','请填写手机号码');
                    return false;                    
                }
                var param = {}; // 组装发送参数
                param['mobile'] = mobile;                   
                $.get(
                        '/shop.php?mod=sms&extra=verify_mobile',
                        param,
                        function(data){
                            // _data = eval("("+data+")")
                            console.log(data.status)
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
                $('#J_send').click(function(){
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
                var contacts = $('input[name="contacts"]').val();
                var mobile = $('input[name="mobile"]').val();
                var qq = $('input[name="qq"]').val();
                var username = $('input[name="username"]').val();
                var password = $('input[name="password"]').val();
                var password2 = $('input[name="password2"]').val();
                var code = $('input[name="code"]').val();
                if (username =='') {
                    make_alert_html("警告",'用户名不能为空');
                    return false;
                }
                if (contacts=='') {
                    make_alert_html("警告",'联系人不能为空');
                    return false;
                }
                if (mobile=='') {
                    make_alert_html("警告",'联系电话不能为空');
                    return false;
                }
                if (password=='') {
                    make_alert_html("警告",'密码不能为空');
                    return false;
                }
                if (password2=='') {
                    make_alert_html("警告",'重复密码不能为空');
                    return false;
                }
                if (code=='') {
                    make_alert_html("警告",'验证码不能为空');
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
                make_alert_html('提示',_data['info'],'/shop.php?mod=main');
                // window.location.href = '/';
        }

}