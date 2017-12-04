var send_task = {
        _init:function(){
                var c = send_task;
                c._listen();
                // c._listen_refresh();
                // c._listen_verify_send_task();
        },
        _listen:function(){
                var c = send_task;
                $(function(){
                        $('.up_img').on('change','.J_file', function(){
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
                                                        url: '/index.php?mod=i&extra=upload',
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
                $('#J_btn_cancel').click(function(){
                        confirm_cancle(c._cancel_task,'提示','是否确认取消任务?');
                        
                });        
        },
        _cancel_task:function(){
                var _data = new Object;
                        _data.order_id = $('#J_order_id').val();
                        _ajax_jsonp('/i/cancel_task',send_task._cancel_task_success,_data); 

        },
        _cancel_task_success:function(_data){
                if(_data['status']==302){
                        $('#J_error_tip').html(_data['info']);  
                        $('#J_error_tip').css('display','');
                }       
        },
        _verify:function(){
                var img_num = 0;
                $('.J_file_url').each(function(){
                        console.log($(this).val())
                        if($(this).val()){
                                img_num++
                        }
                })
                if (img_num==0) {
                        make_alert_html('提示','请上传您的截图');
                        return false;
                }
                
                return true;
        },
        upload_success:function(_data){
                var c = send_task;
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
                console.log($('.J_file_url').size())
                var img_num = 0;
                $('.J_file_url').each(function(){
                        if($(this).val()){
                                img_num++;
                        }
                })
                console.log(img_num)
                if (img_num>=1 && img_num<9) {
                        $('.icon-jia1').eq(_index).css({'background-image':'url('+_data['data']+')','background-size':'120px','background-repeat':'no-repeat'});
                        $('.J_file_url').eq(_index).val(_data['data']);
                        var _html = '<li>'+
                                    '<a href="javascript:void(0);" class="input">'+
                                    '<i class="icon iconfont icon-jia1"></i><input type="file" id="file"  class="J_file">'+
                                    '<input type="hidden" name="pic_url[]" class="J_file_url"></a>'+
                                    '<div class="my-3" id="preview"></div>'+
                                '</li>';
                        $('#J_img_box').before(_html)
                }else{
                        $('.icon-jia1').eq(_index).css({'background-image':'url('+_data['data']+')','background-size':'120px','background-repeat':'no-repeat'});
                        $('.J_file_url').eq(_index).val(_data['data']);
                }
        },
        send_success:function(_data){
                var c = send_task;
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