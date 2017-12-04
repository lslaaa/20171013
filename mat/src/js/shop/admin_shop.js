var admin_shop = {
	_init:function(){
		var c = admin_shop
		c._listen()
		c._listen_edit_pwd()
	},
	_listen:function(){
		$('#J_send_form').submit(function(){
							if (!admin_shop._verify()) {
								return false;
							}
							if(admin_shop.bool_sending){
								return false;	
							}
                                                        $('#J_form_do').val('add')
							admin_shop.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),admin_shop.send_success,'send_success');			  
						});

	},
	_verify:function(){

		//检查数据是否符合提交条件
                if(!$('#J_type').val()){
                    make_alert_html('系统提示','请选择店铺类型');
        			return false;
                }
                if(!$('#J_shopname').val()){
                    make_alert_html('系统提示','请填写店铺名称');
        			return false;
                }
                if(!$('#J_username').val()){
                    make_alert_html('系统提示','请填写用户昵称');
        			return false;
                }
                if(!$('#J_phone').val()){
                    make_alert_html('系统提示','请填写联系电话');
        			return false;
                }
                if(!$('#J_contact').val()){
                    make_alert_html('系统提示','请填写联系人');
        			return false;
                }
                if(!$('#J_address').val()){
                    make_alert_html('系统提示','请填写联系地址');
                                return false;
                }
                if(!$('#J_shop_url').val()){
                    make_alert_html('系统提示','请填写店铺地址');
                                return false;
                }
                // if(!$('#J_content').val()){
                //     make_alert_html('系统提示','请填写店铺描述');
                //                 return false;
                // }
        	return true;
		
	},
	send_success:function(_data){
		_remove_ajax_frame('send_success');
		if(_data['status']!=200){
			admin_shop.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}
		make_alert_html(arr_language['notice'],_data['info'],'/shop.php?mod=shop');
	},
	//修改会员密码
        _listen_edit_pwd:function(){
            $('#J_edit_pwd').click(function(){
                var c = admin_shop;
                var uid = $('#J_uid').val();
                var _html = '<div id="J_open_div" class="pop_wrap" style="width:400px;">'+
                                                '<div class="pop_title">'+
                                                        '<span class="f_l">修改密码</span>'+
                                                        '<a id="J_close_tan" class="close f_r" href="javascript:void(0);">关闭</a>'+
                                                '</div>'+
                                                '<div class="pop_main">'+
                                                        '<form id="J_edit_form" action="/shop.php?mod=shop&extra=mod_detail" method="post" target="ajaxframeid" >'+
                                                                '<input class="J_callback" type="hidden" value="parent.send_success" name="callback">'+
                                                                '<input type="password" name="password" id="J_old_pwd" style="height:32px;">'+
                                                                '<p id="J_error" class="red" style="height:15px; padding:5px 0;"></p>'+
                                                                '<input type="submit" style="border-radius:3px;height:52px; line-height:52px; width:158px; border:0; font-weight:700; font-size:16px;" value="确认" class="blue_nav">'+
                                                        '</form>'+
                                                '</div>'+
                                        '</div>';
                make_common_open_html(_html);
                c._listen_send_pwd();
            });            
        },
        
        
        _listen_send_pwd:function(){
            var c = admin_shop;
            $('#J_edit_form').unbind('submit');
            $('#J_edit_form').submit(function(){							
                        var obj = $('#J_old_pwd');
                        if(!obj.val()){
                                $('#J_error').css('display','');
                                $('#J_error').html("密码不能为空");
                                return false;
                        }

                        if(c.bool_sending){
                                return false;	
                        }
                        c.bool_sending = true;
                        _ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');			  
                });	
        }

}

var admin_shop_del = {
        _init:function(){
                admin_shop_del._listen_del();
        },
        _listen_del:function(){
                var c = admin_shop_del;
                $('.J_btn_del').click(function(){
                                                                var _this = $(this);
                                                                confirm_cancle(c._del,arr_language['warn'],arr_language['del_2']);
                                                                c._this = _this;
                                                        });
        },
        _del:function(){
                var c = admin_shop_del;
                var _this = c._this;
                var int_sid = _this.attr('data-id');
                _ajax_jsonp('/shop.php?mod=shop&extra=del&sid='+int_sid,c._del_success);
        },
        _del_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);     
        }       
}