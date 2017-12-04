var admin_taobao = {
        _this:'',
        str_ids:'',
        _init:function(){
                admin_taobao._listen(); 
        },
        _listen:function(){
                $('#J_send_form').submit(function(){
                                                        /*
                                                        var obj_password = $('#J_password');
                                                        if(!obj_password.val()){
                                                                make_alert_html(arr_language['warn'],arr_language['admin_taobao']['error_2']);
                                                                return false;
                                                        }
                                                        */
                                                        if(admin_taobao.bool_sending){
                                                                return false;   
                                                        }
                                                        admin_taobao.bool_sending = true;
                                                        _ajax_frame(get_obj('ajaxframeid'),admin_taobao.send_success,'send_success');                     
                                                });
        },
        _listen_del:function(){
                $('.J_btn_del').click(function(){
                                                                var _this = $(this);
                                                                confirm_cancle(admin_taobao._del,arr_language['warn'],arr_language['del_3']);
                                                                admin_taobao._this = _this;
                                                        });
        },
        _del:function(){
                var _this = admin_taobao._this;
                var int_id = _this.attr('data-id');
                _ajax_jsonp('/admin.php?mod=member&mod_dir=member&extra=del_taobao&id='+int_id,admin_taobao._del_success);
        },
        _del_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);     
        },
        send_success:function(_data){
                _remove_ajax_frame('send_success');
                //console.log(_data);
                if(_data['status']!=200){
                        admin_taobao.bool_sending = false;
                        make_alert_html(arr_language['warn'],_data['info']);
                        return false;   
                }
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);
        },
        
        _listen_audited:function(){
                $('.J_audited').click(function(){
                        var _this = $(this);
                        confirm_cancle(admin_taobao._audited,arr_language['warn'],arr_language['del_3']);
                        admin_taobao._this = _this;
                });
        },
        _audited:function(){
                var _this = admin_taobao._this;
                var int_id = _this.attr('data-id');
                _ajax_jsonp('/admin.php?mod=member&mod_dir=member&extra=audited_taobao&id='+int_id,admin_taobao._audited_success);
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
                        _str_ids = str_ids;
                        confirm_cancle(admin_taobao._bat_audited,arr_language['warn'],arr_language['del_3']);
                        admin_taobao._this = _this;
                });
        },
        _bat_audited:function(){
                var _this = admin_taobao._this;
                _ajax_jsonp('/admin.php?mod=member&mod_dir=member&extra=bat_audited_taobao&ids='+_str_ids,admin_taobao._bat_audited_success);
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
                        _str_ids = str_ids;
                        confirm_cancle(admin_taobao._bat_phy_del,arr_language['warn'],arr_language['del_3']);
                        admin_taobao._this = _this;
                });
        },
        _bat_phy_del:function(){
                var _this = admin_taobao._this;
                _ajax_jsonp('/admin.php?mod=member&mod_dir=member&extra=bat_phy_del_taobao&ids='+_str_ids,admin_taobao._bat_phy_del_success);
        },
        _bat_phy_del_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);     
        }
}