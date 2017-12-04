var admin_shop_shehe = {
        _init:function(){
                admin_shop_shehe._listen_shehe();
        },
        _listen_shehe:function(){
                var c = admin_shop_shehe;
                $('.J_btn_yes').click(function(){
                                                                var _this = $(this);
                                                                confirm_cancle(c._yes,arr_language['warn'],'确定审核通过吗？');
                                                                c._this = _this;
                                                        });
                $('.J_btn_no').click(function(){
                                                                var _this = $(this);
                                                                confirm_cancle(c._no,arr_language['warn'],'确定审核不通过吗？');
                                                                c._this = _this;
                                                        });
        },
        _yes:function(){
                var c = admin_shop_shehe;
                var _this = c._this;
                var int_sid = _this.attr('data-id');
                _ajax_jsonp('/admin.php?mod=shop&mod_dir=shop&extra=shehe&status=110&sid='+int_sid,c._shehe_success);
        },
        _no:function(){
                var c = admin_shop_shehe;
                var _this = c._this;
                var int_sid = _this.attr('data-id');
                _ajax_jsonp('/admin.php?mod=shop&mod_dir=shop&extra=shehe&status=304&sid='+int_sid,c._shehe_success);
        },
        _shehe_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);     
        }       
}