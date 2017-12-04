var admin_member_shop = {
        _this:'',
        str_uids:'',
        _init:function(){
                admin_member_shop._listen(); 
        },
        _listen:function(){
                $('#J_send_form').submit(function(){
                                                        /*
                                                        var obj_password = $('#J_password');
                                                        if(!obj_password.val()){
                                                                make_alert_html(arr_language['warn'],arr_language['admin_member_shop']['error_2']);
                                                                return false;
                                                        }
                                                        */
                                                        if(admin_member_shop.bool_sending){
                                                                return false;   
                                                        }
                                                        admin_member_shop.bool_sending = true;
                                                        _ajax_frame(get_obj('ajaxframeid'),admin_member_shop.send_success,'send_success');                     
                                                });
        },
        _listen_del:function(){
                $('.J_btn_del').click(function(){
                                                                var _this = $(this);
                                                                confirm_cancle(admin_member_shop._del,arr_language['warn'],arr_language['del_3']);
                                                                admin_member_shop._this = _this;
                                                        });
        },
        _del:function(){
                var _this = admin_member_shop._this;
                var int_uid = _this.attr('data-uid');
                _ajax_jsonp('/admin.php?mod=member_shop&mod_dir=shop&extra=del&uid='+int_uid,admin_member_shop._del_success);
        },
        _del_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);     
        },
        send_success:function(_data){
                _remove_ajax_frame('send_success');
                //console.log(_data);
                if(_data['status']!=200){
                        admin_member_shop.bool_sending = false;
                        make_alert_html(arr_language['warn'],_data['info']);
                        return false;   
                }
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);
        },
        
        _listen_audited:function(){
                $('.J_audited').click(function(){
                                                                var _this = $(this);
                                                                confirm_cancle(admin_member_shop._audited,arr_language['warn'],arr_language['del_3']);
                                                                admin_member_shop._this = _this;
                                                        });
        },
        _audited:function(){
                var _this = admin_member_shop._this;
                var int_uid = _this.attr('data-uid');
                _ajax_jsonp('/admin.php?mod=member_shop&mod_dir=shop&extra=audited&uid='+int_uid,admin_member_shop._audited_success);
        },
        _audited_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);     
        },
        
        
        //批量审核
        _listen_bat_audited:function(){
                $('#J_bat_audited').click(function(){
                       var _this = $(this);
                       var obj = $('.J_checkall_sub');                          
                        var str_ids = '';
                        for(var i=0;i<obj.length;i++){
                                var ci = obj.eq(i);
                                if(ci.attr('checked')=='checked'){
                                        str_ids += ci.val()+',';        
                                }
                        }
                        if(!str_ids){
                                make_alert_html(arr_language['warn'],arr_language['no_select']);
                                return false;   
                        }
                        _str_uids = str_ids;
                        confirm_cancle(admin_member_shop._bat_audited,arr_language['warn'],arr_language['del_3']);
                        admin_member_shop._this = _this;
                });
        },
        _bat_audited:function(){
                var _this = admin_member_shop._this;
                _ajax_jsonp('/admin.php?mod=member_shop&mod_dir=shop&extra=bat_audited&uids='+_str_uids,admin_member_shop._bat_audited_success);
        },
        _bat_audited_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);     
        },
        
        //批量删除
        _listen_bat_phy_del:function(){
                $('#J_bat_phy_del').click(function(){
                       var _this = $(this);
                       var obj = $('.J_checkall_sub');                          
                        var str_ids = '';
                        for(var i=0;i<obj.length;i++){
                                var ci = obj.eq(i);
                                if(ci.attr('checked')=='checked'){
                                        str_ids += ci.val()+',';        
                                }
                        }
                        if(!str_ids){
                                make_alert_html(arr_language['warn'],arr_language['no_select']);
                                return false;   
                        }
                        _str_uids = str_ids;
                        confirm_cancle(admin_member_shop._bat_phy_del,arr_language['warn'],arr_language['del_3']);
                        admin_member_shop._this = _this;
                });
        },
        _bat_phy_del:function(){
                var _this = admin_member_shop._this;
                _ajax_jsonp('/admin.php?mod=member_shop&mod_dir=shop&extra=bat_phy_del&uids='+_str_uids,admin_member_shop._bat_phy_del_success);
        },
        _bat_phy_del_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);     
        },


        //批量删除
        _listen_bat_phy_open:function(){
                $('#J_bat_phy_open').click(function(){
                       var _this = $(this);
                       var obj = $('.J_checkall_sub');                          
                        var str_ids = '';
                        for(var i=0;i<obj.length;i++){
                                var ci = obj.eq(i);
                                if(ci.attr('checked')=='checked'){
                                        str_ids += ci.val()+',';        
                                }
                        }
                        if(!str_ids){
                                make_alert_html(arr_language['warn'],arr_language['no_select']);
                                return false;   
                        }
                        _str_uids = str_ids;
                        confirm_cancle(admin_member_shop._bat_phy_open,arr_language['warn'],arr_language['del_3']);
                        admin_member_shop._this = _this;
                });
        },
        _bat_phy_open:function(){
                var _this = admin_member_shop._this;
                _ajax_jsonp('/admin.php?mod=member_shop&mod_dir=shop&extra=bat_phy_open&uids='+_str_uids,admin_member_shop._bat_phy_open_success);
        },
        _bat_phy_open_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);     
        }
}

var member_recharge = {
        _init:function(){
                member_recharge._listen(); 
        },
        _listen:function(){
                var c = member_recharge
                $('.J_recharge').click(function(){
                        var int_uid = $(this).attr('data-uid')
                        var _html = c._make_html();
                        make_common_open_html(_html);
                        $('#J_queren_cc').click(function(){
                                var _data = new Object();
                                _data['uid'] = int_uid;
                                _data['money'] = $('#J_money').val();
                                if (!isPriceNumber(_data['money'])) {
                                        make_alert_html(arr_language['warn'],'请输入正确的金额');
                                        return false
                                }
                                _ajax_jsonp("/admin.php?mod=member_shop&mod_dir=shop&extra=recharge",c._recharge_success,_data); 
                        })
                })
        },
        _make_html:function(){
                //console.log(_data);
                var _html =
                        '<div class="pop_wrap" id="J_open_div" style="width:400px;height:190px;z-index:99999">'+
                                '<div class="pop_title">'+
                                        '<span class="f_l">充值</span>'+
                                '<a href="javascript:;" class="close f_r" id="J_close_tan">¹Ø±Õ</a>'+
                            '</div>'+
                            '<div class="pop_main">'+
                                '<div class="pop_content main_c f16" id="J_content_cc">充值金额<input id="J_money" type="text"></div>'+
                                '<div class="pop_nav align_c">'+
                                    '<a href="javascript:void(0);" class="nav_sure" id="J_queren_cc">确认</a>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                        return _html;   
        },
        _recharge_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);     
        }
}

var admin_add_shop = {
        _init:function(){
                var c = admin_add_shop
                c._listen()
        },
         _listen:function(){
                $('#J_send_form').submit(function(){
                        if (!admin_add_shop._verify()) {
                                return false;
                        }
                        if(admin_add_shop.bool_sending){
                                return false;   
                        }
                        $('#J_form_do').val('add')
                        admin_add_shop.bool_sending = true;
                        _ajax_frame(get_obj('ajaxframeid'),admin_add_shop.send_success,'send_success');           
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
                        admin_add_shop.bool_sending = false;
                        make_alert_html(arr_language['warn'],_data['info']);
                        return false;   
                }
                make_alert_html(arr_language['notice'],_data['info'],'/admin.php?mod=member_shop&mod_dir=shop');
        },
}