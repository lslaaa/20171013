var game = {
        _init:function(){
                var c = game;
                c._listen();
                // c._listen_refresh();
                // c._listen_verify_game();
        },
        _listen:function(){
                var c = game;
                $(function(){
                        $('.J_file').on('change', function(){
                                var _index = $('.J_file').index(this);
                                if(c.bool_sending){
                                        return false;   
                                }
                                // $('.J_file').eq(_index).next().next().html('图片上传中')
                                c.upload_index = _index;
                                c.bool_sending = true;
                                 lrz(this.files[0], {width: 640})
                                        .then(function (rst) {
                                                //console.log(rst.base64);
                                                $.ajax({
                                                        url: '/index.php?mod=game&extra=upload',
                                                        type: 'post',
                                                        data: {pic: rst.base64},
                                                        dataType: 'json',
                                                        timeout: 200000,
                                                        error:  function(msg){
                                                                 alert( "Data Saved: " + msg );
                                                           }
                                                        ,
                                                        success:  function(msg){
                                                                c.upload_success(msg);
                                                                // alert( "Data Saved: " + msg );
                                                           }
                                                });
                                                        
                                        })
                                        .catch(function (err) {
                
                                        })
                                        .always(function () {
                
                                        });
                        });
                });
                $('#J_send').click(function(){
                        if($('#J_form_do').val()=='upload'){
                                return true;    
                        }
                        if(!c._verify()){
                                return false;   
                        }
                        if(c.bool_sending){
                                return false;   
                        }
                        $('#J_error_tip').css('display','none');
                        $('.J_file').attr('disabled',true);
                        $('#J_form_do').val('add');
                        c.bool_sending = true;
                        _ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');      
                        setTimeout(function(){
                                                $('#J_send_form').submit();             
                                        },100);
                        
                });             
        },
        _listen_refresh:function(){
                $('#J_refresh_check_code').click(function(){
                        $('#J_check_code_pic').attr('src','/index/check_code?'+Math.random())
                });
        },
        _listen_verify_game:function(){
                var c = game;
                $('#J_game').blur(function(){
                        var _data = new Object();
                        _data.game = $('#J_game').val();
                        _ajax_jsonp('/service/verify_game',c._verify_game_success,_data);         
                });     
        },
        _verify_game_success:function(_data){
                if(_data['status']==302){
                        $('#J_error_tip').html(_data['info']);  
                        $('#J_error_tip').css('display','');
                }       
        },
        _verify:function(){
                var boole_ok = true;
                $('.J_file_url').each(function(){
                        console.log($(this).val())
                        if(!$(this).val()){
                                make_alert_html('提示','请上传您的淘宝截图');
                                boole_ok = false;
                        }
                })
                if (!boole_ok) {
                        return false;
                }
                
                return true;
        },
        upload_success:function(_data){
                var c = game;
                var _index = c.upload_index;
                _remove_ajax_frame('upload_success');
                weui_loading_remove();
                $('.J_file').attr('disabled',false);
                $('#J_form_do').val('');
                if(_data['status']!=200){
                        c.bool_sending = false;
                        make_alert_html('提示',_data['info']);
                        return false;
                }
                c.bool_sending = false;
                $('.icon-jia1').eq(_index).css({'background-image':'url('+_data['data']+')','background-size':'1.3rem','background-repeat':'no-repeat'});
                $('.J_file_url').eq(_index).val(_data['data']);
        },
        send_success:function(_data){
                var c = game;
                $('.J_file').attr('disabled',false);
                _remove_ajax_frame('send_success');
                c.bool_sending = false;
                weui_loading_remove();
                //console.log(_data);
                
                if(_data['status']!=200){
                        make_alert_html('提示',_data['info']);
                        return false;   
                }
                make_alert_html(arr_language['notice'],_data['info'],'/i/my_task/status-105');
                //window.location.href = '/';
        }
}