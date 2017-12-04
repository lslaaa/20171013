var shop_order_detail = {
        _init:function(){
                shop_order_detail._listen_change_info();       
        },
        _listen_change_info:function(){
                $('.J_change_info').click(function(){
                                                                var _this = $(this);
                                                                var _index = $('.J_change_info').index(this);
                                                                $('.J_change_info').children('a').removeClass('on');
                                                                $('.J_change_info').eq(_index).children('a').addClass('on');
                                                                $('.J_info').css('display','none');
                                                                $('.J_info').eq(_index).css('display','');
                                                        });
        }
}

var shop_order = {
        _init:function(){
                shop_order._listen_bat_shenhe();       
        },
        //批量下架
        _listen_bat_shenhe:function(){
                var c = shop_order
                $('#J_bat_shenhe').click(function(){
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
                        var _html = c._make_html();
                        make_common_open_html(_html);
                        $('#J_tongguo_cc').click(function(){
                                var _data = new Object();
                                _data['order_id'] = _str_ids;
                                _data['status'] = 105;
                                _ajax_jsonp("/shop.php?mod=order&extra=bat_shenhe",c.bat_shenhe_success,_data); 
                        })

                        $('#J_jujue_cc').click(function(){
                                var _data = new Object();
                                _data['order_id'] = _str_ids;
                                _data['status'] = 304;
                                _ajax_jsonp("/shop.php?mod=order&extra=bat_shenhe",c.bat_shenhe_success,_data); 
                        })
                });
        },
        
        bat_shenhe:function(){
                _ajax_jsonp('/shop.php?mod=item&extra=bat_shenhe&ids='+_str_ids,shop_item.bat_shenhe_success);
        },
        bat_shenhe_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);     
        },
        _make_html:function(){
                //console.log(_data);
                var _html =
                        '<div class="pop_wrap" id="J_open_div" style="width:400px;height:190px;z-index:99999">'+
                                '<div class="pop_title">'+
                                        '<span class="f_l">审核</span>'+
                                '<a href="javascript:;" class="close f_r" id="J_close_tan">¹Ø±Õ</a>'+
                            '</div>'+
                            '<div class="pop_main">'+
                                '<div class="pop_content main_c f16" id="J_content_cc">请选择你的审核结果</div>'+
                                '<div class="pop_nav align_c">'+
                                    '<a href="javascript:void(0);" class="nav_sure" id="J_tongguo_cc">通过</a>'+
                                    '<a href="javascript:void(0);" class="nav_none" id="J_jujue_cc">拒绝</a>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                        return _html;   
        } 
}

var shop_order_cancel = {
        _init:function(){
                shop_order_cancel._listen_cancel();    
        },
        _listen_cancel:function(){
                var c = shop_order_cancel;
                $('.J_btn_cancel').click(function(){
                                var _this = $(this);
                                c._this = _this;
                                confirm_cancle(c._cancel,arr_language['warn'],arr_language['mall']['order']['cancle_tip']);
                        });
        },
        _cancel:function(){
                var c = shop_order_cancel;
                var _data = new Object();
                        _data['order_id'] = c._this.attr('data-order_id');
                        _ajax_jsonp("/shop.php?mod=order&extra=cancel",c._cancel_success,_data);  
        },
        _cancel_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);
        }
}

var shop_order_confirm_money = {
        _init:function(){
                shop_order_confirm_money._listen_cancel();    
        },
        _listen_cancel:function(){
                var c = shop_order_confirm_money;
                $('.J_btn_confirm_money').click(function(){
                                var _this = $(this);
                                c._this = _this;
                                confirm_cancle(c._cancel,arr_language['warn'],'是否确认支付佣金？');
                        });
        },
        _cancel:function(){
                var c = shop_order_confirm_money;
                var _data = new Object();
                        _data['order_id'] = c._this.attr('data-order_id');
                        _ajax_jsonp("/shop.php?mod=order&extra=confirm_money",c._cancel_success,_data);  
        },
        _cancel_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);
        }
}

var shop_order_confirm = {
        _init:function(){
                shop_order_confirm._listen_cancel();    
        },
        _listen_cancel:function(){
                var c = shop_order_confirm;
                $('.J_btn_confirm').click(function(){
                                var _this = $(this);
                                var order_id = $(this).attr('data-order_id')
                                var _html = c._make_html(order_id);
                                make_common_open_html(_html);
                                $('#J_tongguo_cc').click(function(){
                                        var _data = new Object();
                                        _data['order_id'] = order_id;
                                        _data['status'] = 200;
                                        _ajax_jsonp("/shop.php?mod=order&extra=confirm",c._confirm_success,_data); 
                                })

                                $('#J_jujue_cc').click(function(){
                                        var _html2 = c._make_html2(order_id);
                                        make_common_open_html(_html2);
                                        //return false;
                                        $('#J_fail_btn').click(function(){
                                                console.log($('#J_fail_reason').val())
                                                // return false
                                                var _data = new Object();
                                                _data['order_id'] = order_id;
                                                _data['fail_reason'] = $('#J_fail_reason').val();
                                                _data['status'] = 404;
                                                _ajax_jsonp("/shop.php?mod=order&extra=confirm",c._confirm_success,_data); 
                                        })
                                })
                        });
        },

        _confirm:function(){
                var c = shop_order_confirm;
                var _data = new Object();
                        _data['order_id'] = c._this.attr('data-order_id');
                        _ajax_jsonp("/shop.php?mod=order&extra=confirm",c._confirm_success,_data);  
        },
        _confirm_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);
        },
        _make_html:function(order_id){
                //console.log(_data);
                var _html =
                        '<div class="pop_wrap" id="J_open_div" style="width:400px;height:190px;z-index:99999">'+
                                '<div class="pop_title">'+
                                        '<span class="f_l">审核是否完成</span>'+
                                '<a href="javascript:;" class="close f_r" id="J_close_tan">¹Ø±Õ</a>'+
                            '</div>'+
                            '<div class="pop_main">'+
                                '<div class="pop_content main_c f16" id="J_content_cc">请选择你的审核结果</div>'+
                                '<div class="pop_nav align_c">'+
                                    '<a href="javascript:void(0);" class="nav_sure" id="J_tongguo_cc" data-order_id="'+order_id+'">通过</a>'+
                                    '<a href="javascript:void(0);" class="nav_none" id="J_jujue_cc" data-order_id="'+order_id+'">拒绝</a>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                        return _html;   
        },
        _make_html2:function(order_id){
                //console.log(_data);
                var _html =
                        '<div class="pop_wrap" id="J_open_div" style="width:400px;height:250px;z-index:99999">'+
                                '<div class="pop_title">'+
                                        '<span class="f_l">拒绝理由</span>'+
                                '<a href="javascript:;" class="close f_r" id="J_close_tan">¹Ø±Õ</a>'+
                            '</div>'+
                            '<div class="pop_main">'+
                                '<div class="pop_content main_c f16" id="J_content_cc"><textarea id="J_fail_reason" style="width:350px;height:80px"></textarea></div>'+
                                '<div class="pop_nav align_c">'+
                                    '<a href="javascript:void(0);" class="nav_sure" id="J_fail_btn" data-order_id="'+order_id+'">提交</a>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                        return _html;   
        }
}

var shop_order_shenhe = {
        _init:function(){
                shop_order_shenhe._listen_shenhe();    
        },
        _listen_shenhe:function(){
                var c = shop_order_shenhe;
                $('.J_btn_shenhe').click(function(){
                                var _this = $(this);
                                c._this = _this;
                                var order_id = $(this).attr('data-order_id')
                                var _html = c._make_html(order_id);
                                make_common_open_html(_html);
                                $('#J_tongguo_cc').click(function(){
                                        var _data = new Object();
                                        _data['order_id'] = c._this.attr('data-order_id');
                                        _data['status'] = 105;
                                        _ajax_jsonp("/shop.php?mod=order&extra=shenhe",c._shenhe_success,_data); 
                                })

                                $('#J_jujue_cc').click(function(){

                                        var _data = new Object();
                                        _data['order_id'] = c._this.attr('data-order_id');
                                        _data['status'] = 304;
                                        _ajax_jsonp("/shop.php?mod=order&extra=shenhe",c._shenhe_success,_data); 
                                })
                                // confirm_cancle(c._shenhe,arr_language['warn'],arr_language['mall']['order']['cancle_tip']);
                        });
                
        },
        _shenhe:function(){
                var c = shop_order_shenhe;
                var _data = new Object();
                        _data['order_id'] = c._this.attr('data-order_id');
                        _ajax_jsonp("/shop.php?mod=order&extra=shenhe",c._shenhe_success,_data);  
        },
        _shenhe_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);
        },
        _make_html:function(order_id){
                //console.log(_data);
                var _html =
                        '<div class="pop_wrap" id="J_open_div" style="width:400px;height:190px;z-index:99999">'+
                                '<div class="pop_title">'+
                                        '<span class="f_l">审核</span>'+
                                '<a href="javascript:;" class="close f_r" id="J_close_tan">¹Ø±Õ</a>'+
                            '</div>'+
                            '<div class="pop_main">'+
                                '<div class="pop_content main_c f16" id="J_content_cc">请选择你的审核结果</div>'+
                                '<div class="pop_nav align_c">'+
                                    '<a href="javascript:void(0);" class="nav_sure" id="J_tongguo_cc" data-order_id="'+order_id+'">通过</a>'+
                                    '<a href="javascript:void(0);" class="nav_none" id="J_jujue_cc" data-order_id="'+order_id+'">拒绝</a>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                        return _html;   
        },
        
}

var shop_order_remark = {
        _init:function(){
                shop_order_remark._listen_blur();
                        
        },
        _listen_blur:function(){
                var c = shop_order_remark;
                $('#J_remark_2').blur(function(){
                                                        if(c.bool_sending){
                                                                        return false;   
                                                                }
                                                        c.bool_sending = true;
                                                        var _data = new Object();
                                                                _data['order_id'] = $('#J_hid_order_id').val();
                                                                _data['remark_2'] = $('#J_remark_2').val();
                                                                _ajax_jsonp("/shop.php?mod=order&extra=update_remark",c.send_success,_data);                         
                                                });
        },
        send_success:function(_data){
                var c = shop_order_remark;
                //console.log(_data);
                if(_data['status']!=200){
                        c.bool_sending = false;
                        make_alert_html(arr_language['warn'],_data['info']);
                        return false;   
                }
                make_alert_html(arr_language['notice'],_data['info']);
        }
};

var shop_order_fahuo = {
        _init:function(){
                shop_order_fahuo._listen_btn();
        },
        _listen_btn:function(){
                var c = shop_order_fahuo;
                $('#J_add_express,#J_mod_express').click(function(){
                                                                setTimeout(function(){
                                                                        _ajax_jsonp("/shop.php?mod=order&extra=get_express",c.get_express_success);
                                                                },20);
                                                        });     
        },
        get_express_success:function(_data){
                var c = shop_order_fahuo;
                var _html = c._make_html(arr_language['express'],_data);
                make_common_open_html(_html);
                var obj_options = $('#J_express option');
                for(var i=0;i<obj_options.length;i++){
                        var ci = obj_options.eq(i);
                        if(ci.val()==$('#J_hid_express').val()){
                                ci.attr('selected',true);
                                break;
                        }
                }
                $('#J_express_id').val($('#J_hid_express_id').val());
                $('#J_express_send').click(function(){
                                                                var obj_express_id = $('#J_express_id');
                                                                if(!obj_express_id.val()){
                                                                        $('#J_tips').html(arr_language['mall']['order']['error_1']);
                                                                        return false;   
                                                                }
                                                                if(c.bool_sending){
                                                                        return false;   
                                                                }
                                                                $('#J_tips').html('');
                                                                c.bool_sending = true;
                                                                var _data = new Object();
                                                                _data['order_id'] = $('#J_hid_order_id').val();
                                                                _data['express'] = $('#J_express').val();
                                                                _data['express_id'] = $('#J_express_id').val();
                                                                _ajax_jsonp("/shop.php?mod=order&extra=update_express",c.send_success,_data);
                                                        });
                
        },
        send_success:function(_data){
                //console.log(_data);
                if(_data['status']!=200){
                        c.bool_sending = false;
                        make_alert_html(arr_language['warn'],_data['info']);
                        return false;   
                }
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);
        },
        _make_html:function(str_title,_data){
                //console.log(_data);
                var _html =
                        '<div class="pop_wrap" id="J_open_div" style="width:400px;height:190px;z-index:99999">'+
                                '<div class="pop_title">'+
                                        '<span class="f_l">'+str_title+'</span>'+
                                '<a href="javascript:;" class="close f_r" id="J_close_tan">¹Ø±Õ</a>'+
                            '</div>'+
                            '<div class="pop_main">'+
                                '<div class="privacy_sms">'+
                                        '<div class="express">'+
                                                '<select id="J_express" name="express">';
                             for(var i=0;i<_data.data.length;i++){
                                   _html+='<option value="'+_data.data[i]['id']+'">'+_data.data[i]['name']+'</option>';
                             }
                         
                            _html+='</select>'+
                                        '<input type="text" id="J_express_id" name="express_id">'+
                                        '<p id="J_tips"></p>'+
                                        '<a href="javascript:;"  class="sure_nav" id="J_express_send">'+arr_language['send']+'</a>'+
                                  '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                        return _html;   
        }       
}