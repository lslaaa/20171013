var shop_money = {
        _init:function(){
                var c = shop_money
                c._listen()
        },
        _listen:function(){
                $('#J_send_form').submit(function(){
                                if (!shop_money._verify()) {
                                        return false;
                                }
                                if(shop_money.bool_sending){
                                        return false;   
                                }
                                $('#J_form_do').val('add')
                                shop_money.bool_sending = true;
                                _ajax_frame(get_obj('ajaxframeid'),shop_money.send_success,'send_success');                       
                        });

        },
        _verify:function(){

                //检查数据是否符合提交条件
                if(!$('#J_money').val()){ 
                    make_alert_html('系统提示','请输入提现金额');
                                return false;
                }
                if(!isPriceNumber($('#J_money').val())){
                    make_alert_html('系统提示','请输入正确的金额');
                                return false;
                }
                if(!$('#J_bank').val()){
                    make_alert_html('系统提示','开户行不能为空');
                                return false;
                }
                if(!$('#J_bank_card').val()){
                    make_alert_html('系统提示','银行卡号不能为空');
                                return false;
                }
                if(!$('#J_bank_card_name').val()){
                    make_alert_html('系统提示','银行卡姓名不能为空');
                                return false;
                }

                return true;
                
        },
        send_success:function(_data){
                console.log(_data)
                _remove_ajax_frame('send_success');
                if(_data['status']!=200){
                        shop_money.bool_sending = false;
                        make_alert_html(arr_language['warn'],_data['info']);
                        return false;   
                }
                make_alert_html(arr_language['notice'],_data['info'],'/shop.php?mod=shop');
        },
}

var money_cancel = {
        _init:function(){
                money_cancel._listen_cancel();    
        },
        _listen_cancel:function(){
                var c = money_cancel;
                $('.J_btn_cancel').click(function(){
                                var _this = $(this);
                                c._this = _this;
                                confirm_cancle(c._cancel,arr_language['warn'],'你确定要撤销此提现吗？');
                        });
        },
        _cancel:function(){
                var c = money_cancel;
                var _data = new Object();
                        _data['id'] = c._this.attr('data-id');
                        _ajax_jsonp("/shop.php?mod=money&extra=cancel",c._cancel_success,_data);  
        },
        _cancel_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);
        }
}


var add_bank = {
        _init:function(){
                var c = add_bank
                c._listen()
        },
        _listen:function(){
                $('#J_send_form').submit(function(){
                                if (!add_bank._verify()) {
                                        return false;
                                }
                                if(add_bank.bool_sending){
                                        return false;   
                                }
                                $('#J_form_do').val('add')
                                add_bank.bool_sending = true;
                                _ajax_frame(get_obj('ajaxframeid'),add_bank.send_success,'send_success');                       
                        });

        },
        _verify:function(){

                //检查数据是否符合提交条件
                if(!$('#J_bank').val()){ 
                    make_alert_html('系统提示','请填写开户银行');
                                return false;
                }
                if(!$('#J_bank_card').val()){
                    make_alert_html('系统提示','请输入银行卡账号');
                                return false;
                }
                if(!$('#J_bank_card_name').val()){
                    make_alert_html('系统提示','请输入银行卡姓名');
                                return false;
                }

                return true;
                
        },
        send_success:function(_data){
                console.log(_data)
                _remove_ajax_frame('send_success');
                if(_data['status']!=200){
                        add_bank.bool_sending = false;
                        make_alert_html(arr_language['warn'],_data['info']);
                        return false;   
                }
                make_alert_html(arr_language['notice'],_data['info'],'/shop.php?mod=money&extra=bank_card');
        },
}

var add_recharge = {
        _init:function(){
                var c = add_recharge
                c._listen()
        },
        _listen:function(){
                var c = add_recharge
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
                                if (!add_recharge._verify()) {
                                        return false;
                                }
                                if(add_recharge.bool_sending){
                                        return false;   
                                }
                                $('#J_form_do').val('add')
                                add_recharge.bool_sending = true;
                                _ajax_frame(get_obj('ajaxframeid'),add_recharge.send_success,'send_success');                       
                        });

        },
        _verify:function(){
                //检查数据是否符合提交条件
                if(!$('input[name="money"]').val()){ 
                    make_alert_html('系统提示','请填写充值金额');
                                return false;
                }
                var type = $('.J_type:checked').val()
                if (type==2) {
                        if(!$('#J_bank').val()){ 
                            make_alert_html('系统提示','请填写开户行');
                                        return false;
                        }
                        if(!$('#J_bank_card').val()){ 
                            make_alert_html('系统提示','请填写银行卡号');
                                        return false;
                        }
                        if(!$('#J_bank_card_name').val()){ 
                            make_alert_html('系统提示','请填写银行卡姓名');
                                        return false;
                        }
                }
                if(!$('#J_pic_url').val()){ 
                    make_alert_html('系统提示','请上传转账截图');
                                return false;
                }

                return true;
                
        },
        upload_success:function(_data){
                var c = add_recharge;
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
                console.log(_data)
                _remove_ajax_frame('send_success');
                if(_data['status']!=200){
                        add_recharge.bool_sending = false;
                        make_alert_html(arr_language['warn'],_data['info']);
                        return false;   
                }
                // if (_data['type']==1) {
                //         window.location.href='/shop.php?mod=money&extra=recharge_pay&money='+_data['money']+'&type='+_data['type']+'&order_no='+_data['order_no']
                // }
                make_alert_html(arr_language['notice'],'提交成功','/shop.php?mod=money');
        },
}

var bank_cancel = {
        _init:function(){
                bank_cancel._listen_cancel();    
        },
        _listen_cancel:function(){
                var c = bank_cancel;
                $('.J_btn_cancel').click(function(){
                                var _this = $(this);
                                c._this = _this;
                                confirm_cancle(c._cancel,arr_language['warn'],'你确定要删除此账号吗？');
                        });
        },
        _cancel:function(){
                var c = bank_cancel;
                var _data = new Object();
                        _data['id'] = c._this.attr('data-id');
                        _ajax_jsonp("/shop.php?mod=money&extra=bank_cancel",c._cancel_success,_data);  
        },
        _cancel_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);
        }
}