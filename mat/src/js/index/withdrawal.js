var withdrawal = {
        _init:function(){
                var c = withdrawal;
                c._listen();
        },
        _listen:function(){
                var c = withdrawal;
                $('#J_send').click(function(){
                                var _data = new Object();
                                _data['money'] = $('#J_money').val();
                                _data['password'] = $('#J_password').val();
                                if (!_data['money']) {
                                        make_alert_html(arr_language['warn'],'请输入提现金额');
                                        return false;
                                }
                                if (!_data['password']) {
                                        make_alert_html(arr_language['warn'],'请输入提现密码');
                                        return false;
                                }
                                if (!isPriceNumber(_data['money'])) {
                                        make_alert_html(arr_language['warn'],'请输入正确的金额');
                                        return false
                                }
                                if (_data['money']<20) {
                                        make_alert_html(arr_language['warn'],'提现金额不得低于20');
                                        return false;
                                }
                                _ajax_jsonp("/withdrawal/add",c.send_success,_data); 
                                
                        });             
        },
        _verify:function(){             
                var obj = $('#J_withdrawal_phone');
                if(!obj.val()){
                        make_alert_html(arr_language['warn'],'请输入手机号');
                        return false;
                }
                
                var obj = $('#J_withdrawal_password');
                if(!obj.val()){
                        make_alert_html(arr_language['warn'],'密码不能为空');
                        return false;
                }
                return true;
        },
        send_success:function(_data){
                var c = withdrawal;
                _remove_ajax_frame('send_success');
                c.bool_sending = false;
                //console.log(_data);
                if(_data['status']!=200){
                        make_alert_html(arr_language['warn'],_data['info']);
                        return false;   
                }
                make_alert_html(arr_language['warn'],_data['info'],window.location.href);
                // window.location.href = window.location.href;
        }
}