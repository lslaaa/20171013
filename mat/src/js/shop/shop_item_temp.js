var shop_item_temp = {
        _init:function(){
                var c = shop_item_temp
                c._listen()
        },
        _listen:function(){
                var c = shop_item_temp
                $('#J_send_form').submit(function(){
                                        if($('#J_form_do').val()=='upload'){
                                                return true;    
                                        }
                                        $("#J_form_do").val('add_temp');     
                                        if (!shop_item_temp._verify()) {
                                                return false;
                                        }
                                        if(shop_item_temp.bool_sending){
                                                return false;   
                                        }
                                        shop_item_temp.bool_sending = true;
                                        _ajax_frame(get_obj('ajaxframeid'),shop_item_temp.send_success,'send_success');                        
                                });
                $('.J_pic').change(function(){
                            $('#J_form_do').val('upload'); 
                            c._index = $('.J_pic').index(this); 
                            $(this).attr('name','pic') 
                            // alert(c._index);
                            // return false   
                            if(c.bool_sending){
                                return false;   
                            }
                            c.bool_sending = true;
                            _ajax_frame(get_obj('ajaxframeid'),c.upload_success,'send_success');
                            $('#J_send_form').submit();
                        });
                $('#J_comment_pic_box').on('change','.J_comment_pic',function(){
                            $('#J_form_do').val('upload'); 
                            c._index = $('.J_comment_pic').index(this); 
                            $(this).attr('name','pic') 
                            // alert(c._index);
                            // return false   
                            if(c.bool_sending){
                                return false;   
                            }
                            c.bool_sending = true;
                            _ajax_frame(get_obj('ajaxframeid'),c.upload_pic_success,'send_success');
                            $('#J_send_form').submit();
                        });

                $('#J_comment_pic_box').on('click','.pic_ico',function(){
                        $(this).closest('.J_comment_pic_show').remove()
                })
                $('#J_sid').change(function(){
                        var _sku_price = eval("("+c.sku_price+")");
                        var _sku_price2 = eval("("+c.sku_price2+")");
                        // console.log(_sku_price.type_10);
                        // console.log(_sku_price2.type_10);
                        var shop_type = $(this).find('option:selected').attr('data-type');
                        if (shop_type==10) {
                                var str_type = '天猫';
                                if (!c.jichu_price) {
                                        c.jichu_price = _sku_price.type_10;
                                }
                                if (!c.jichu_price2) {
                                        c.jichu_price2 = _sku_price2.type_10;
                                }
                        }else if(shop_type==20){
                                var str_type = '淘宝';
                                if (!c.jichu_price) {
                                        c.jichu_price = _sku_price.type_10;
                                }
                                if (!c.jichu_price2) {
                                        c.jichu_price2 = _sku_price2.type_10;
                                }
                        }else if(shop_type==30){
                                var str_type = '京东';
                                if (!c.jichu_price) {
                                        c.jichu_price = _sku_price.type_40;
                                }
                                if (!c.jichu_price2) {
                                        c.jichu_price2 = _sku_price2.type_40;
                                }
                        }else if(shop_type==40){
                                var str_type = '蘑菇街';
                                if (!c.jichu_price) {
                                        c.jichu_price = _sku_price.type_30;
                                }
                                if (!c.jichu_price2) {
                                        c.jichu_price2 = _sku_price2.type_30;
                                }
                        }else if(shop_type==50){
                                var str_type = '拼多多';
                                if (!c.jichu_price) {
                                        c.jichu_price = _sku_price.type_20;
                                }
                                if (!c.jichu_price2) {
                                        c.jichu_price2 = _sku_price2.type_20;
                                }
                        }else if(shop_type==60){
                                var str_type = '非电商';
                                if (!c.jichu_price) {
                                        c.jichu_price = _sku_price.type_50;
                                }
                                if (!c.jichu_price2) {
                                        c.jichu_price2 = _sku_price2.type_50;
                                }
                        }
                        // alert(str_type)
                        c._sum_total()
                        $('#J_shop_type').text(str_type)
                        $('#J_shop_type').prev().val(shop_type)
                })

                $('#J_add_key').click(function(){
                        var _html = '<dl class="mt12">'+
                                        '<dt>&nbsp;</dt>'+
                                        '<dd>'+
                                            '<input id="J_key_word" type="text" name="keyword[]" class="text" value="" style="width: 150px">'+
                                            '<input type="text" name="keyword_num[]" class="text k_num" style="width: 50px;margin-left: 10px" placeholder="数量">'+
                                            '<a id="J_del_key" style="padding: 5px 10px;margin-left: 10px;cursor: pointer;" class="red_nav">删除</a>'
                                        '</dd>'+
                                    '</dl>';
                        $('#key_box').append(_html)
                })

                $('#key_box').on('click','#J_del_key',function(){
                        $(this).closest('dl').remove();
                })

                $('#J_price').blur(function(){
                        var _sku_price = eval("("+c.sku_price+")");
                        var _sku_price2 = eval("("+c.sku_price2+")");
                        var price = Number($(this).val());
                        if (price>0 && price<=100) {
                                var show_price = _sku_price.price_1
                                var show_price2 = _sku_price2.price_1
                        }else if(price>100 && price<=200){
                                var show_price = _sku_price.price_2
                                var show_price2 = _sku_price2.price_2
                        }else if(price>200 && price<=300){
                                var show_price = _sku_price.price_3
                                var show_price2 = _sku_price2.price_3
                        }else if(price>300){
                                var price_num = Math.floor(price/100);
                                var add_price = _sku_price.add_price;
                                var add_price2 = _sku_price2.add_price;
                                var show_price = add_price*price_num
                                var show_price2 = add_price2*price_num
                        }
                        $(this).parent().find('#J_sku_price').val(show_price)
                        $(this).parent().find('#J_sku_price2').val(show_price2)
                        $(this).parent().find('#J_require_price').val(show_price*buy_num)
                        $(this).parent().find('#J_require_price2').val(show_price2*buy_num)
                        $(this).parent().find('.total').text('+'+show_price*buy_num)
                        $(this).parent().find('.total').attr('price',show_price*buy_num)
                        $(this).parent().find('.total').attr('price2',show_price2*buy_num)
                        c._sum_total()
                })

                $('.trade').on('blur','#J_buy_num,.k_num',function(){
                        if ($(this).attr('id')=='J_buy_num') {
                                var show_price = Number($('#J_sku_price').val());
                                var show_price2 = Number($('#J_sku_price2').val());
                                var buy_num = Number($('#J_buy_num').val());
                                if (!buy_num) {
                                    buy_num = 1;
                                }
                                $('#J_price').parent().find('#J_require_price').val(show_price*buy_num)
                                $('#J_price').parent().find('#J_require_price2').val(show_price2*buy_num)
                                $('#J_price').parent().find('.total').text('+'+show_price*buy_num)
                                $('#J_price').parent().find('.total').attr('price',show_price*buy_num)
                                $('#J_price').parent().find('.total').attr('price2',show_price2*buy_num)
                        }
                        c._sum_total()
                })
                $('.J_cid').click(function(){
                    var cid = $(this).val();
                    if (cid==1) {
                        $('.J_fukuan').show();
                        $('.J_liulan').hide();
                    }else{
                        $('.J_fukuan').hide();
                        $('.J_liulan').show();
                    }
                })
                $('input[type="radio"]').not('.not_price').click(function(){
                        if ($(this).attr('name')!='is_shenhe' && $(this).attr('name')!='is_temp') {
                                var price = $(this).attr('price');
                                var price2 = $(this).attr('price2');
                                if ($(this).attr('name')=='require[huobi]') {
                                        if ($(this).val()=='huobi_1') {
                                                $('#J_img_add_dd').show()
                                                $('#J_img_add_dd2').hide()
                                                $('#J_img_add_dd3').hide()
                                        }
                                        if ($(this).val()=='huobi_2') {
                                                $('#J_img_add_dd').show()
                                                $('#J_img_add_dd2').show()
                                                $('#J_img_add_dd3').hide()
                                        }
                                        if ($(this).val()=='huobi_3') {
                                                $('#J_img_add_dd').show()
                                                $('#J_img_add_dd2').show()
                                                $('#J_img_add_dd3').show()
                                        }
                                        if ($(this).val()=='') {
                                                $('#J_img_add_dd').hide()
                                                $('#J_img_add_dd2').hide()
                                                $('#J_img_add_dd3').hide()
                                        }
                                        
                                }
                                if (price) {
                                        $(this).parent().find('.tips').text('+'+price)
                                        $(this).parent().find('.tips').attr('price',price)
                                        $(this).parent().find('.tips').attr('price2',price2)
                                }else{
                                        $(this).parent().find('.tips').text('')
                                        $(this).parent().find('.tips').attr('price','')
                                        $(this).parent().find('.tips').attr('price2','')
                                }
                                c._sum_total()

                                if ($(this).attr('name')=="require[pic]") {
                                        var k_num = 0;
                                        $('.k_num').each(function(){
                                                if ($(this).val() && isPriceNumber($(this).val())) {
                                                        k_num += Number($(this).val());
                                                }
                                        })
                                        // alert(k_num)
                                        if ($(this).val()=='pic_2' && k_num>0) {
                                                $('#J_comment_pic_box').show()
                                                $('.comment_dd').remove()
                                                for (var i = 0; i < k_num; i++) {
                                                        $('#J_comment_pic_box').append(
                                                                            '<dd class="comment_dd">'+
                                                                            '<span style="float:left">'+(i+1)+'</span>'+
                                                                            '<input name="" class="J_comment_pic comment_file" type="file" data-id="'+i+'">'+
                                                                            '<a class="upload_pic f_l" href="javascript:;">上传</a>'+
                                                                            '<div class="comment_up_pic_box">'+
                                                                            '</div>'+
                                                                        '</dd>')
                                                }
                                        }else{
                                                $('#J_comment_pic_box').hide()
                                                $('.comment_dd').remove()
                                        }
                                }
                                if ($(this).attr('name')=="require[comment]") {
                                        var k_num = 0;
                                        $('.k_num').each(function(){
                                                if ($(this).val() && isPriceNumber($(this).val())) {
                                                        k_num += Number($(this).val());
                                                }
                                        })
                                        // alert(k_num)
                                        if ($(this).val()=='comment_2' && k_num>0) {
                                                $('#J_comment_box').show()
                                                $('.J_comment').remove()
                                                for (var i = 0; i < k_num; i++) {
                                                        $('#J_comment_box').append(
                                                                            '<dd style="margin-left: 13%" class="J_comment">'+(i+1)+'<input type="text" name="comment['+i+']" class="text_z comment" value="" style="width: 600px"><span class="tips red">*</span></dd>')
                                                }
                                        }else{
                                                $('#J_comment_box').hide()
                                                $('.J_comment').remove()
                                        }
                                }
                        }else{
                                if ($(this).attr('name')=='is_temp' && $(this).val()==1) {
                                        $('#J_item_temp_name').show()
                                }
                                if ($(this).attr('name')=='is_temp' && $(this).val()!=1) {
                                        $('#J_item_temp_name').hide()
                                }
                        }
                        
                })

                $('input[name="is_shenhe"]').click(function(){
                        var is_shenhe = $(this).val()
                        if (is_shenhe==1) {
                                $('#J_butie').show()
                        }else{
                                $('#J_butie').hide()
                        }
                })

                $('select').not('#J_sid').change(function(){
                        if ($(this).attr('id')!='J_province' && $(this).attr('id')!='J_city' && $(this).attr('id')!='J_area') {
                                var price = $(this).find('option:selected').attr('price');
                                var price2 = $(this).find('option:selected').attr('price2');
                        }else{
                                var price = $('#J_province').attr('price');
                                var price2 = $('#J_province').attr('price2');
                        }
                        if (price) {
                                $(this).parent().parent().find('.tips').text('+'+price)
                                $(this).parent().parent().find('.tips').attr('price',price)
                                $(this).parent().parent().find('.tips').attr('price2',price2)
                        }else{
                                $(this).parent().parent().find('.tips').text('')
                                $(this).parent().parent().find('.tips').attr('price','')
                                $(this).parent().parent().find('.tips').attr('price2','')
                        }
                        c._sum_total()
                })

                $('#J_local,#J_province').blur(function(){
                        var price = $(this).attr('price');
                        var price2 = $(this).attr('price2');
                        var val = $(this).val();
                        if (price && val) {
                                $(this).parent().find('.tips').text('+'+price)
                                $(this).parent().find('.tips').attr('price',price)
                                $(this).parent().find('.tips').attr('price2',price2)
                        }else{
                                $(this).parent().find('.tips').text('')
                                $(this).parent().find('.tips').attr('price','')
                                $(this).parent().find('.tips').attr('price2','')
                        }
                        c._sum_total()
                })

                $('#J_stock').blur(function(){
                        c._sum_total()
                })
        },
        _verify:function(){

                if(!$('#J_sid').val()){
                    make_alert_html('系统提示','请选择店铺');
                                return false;
                }
                
                if(!$('input[name="keyword[]"]').eq(0).val()){
                         make_alert_html('系统提示','请填写关键词');
                                return false;
                }
                var bool_key = true;
                $('input[name="keyword[]"]').each(function(){
                        if(!$(this).val()){
                                bool_key = false;
                                return false;
                        }
                })
                if (!bool_key) {
                        make_alert_html('系统提示','请填写关键字');
                        return false;
                }

                var bool_k_num = true;
                $('input[name="keyword_num[]"]').each(function(){
                        if(!$(this).val()){
                                bool_k_num = false;
                                return false;
                        }
                })
                if (!bool_k_num) {
                        make_alert_html('系统提示','请填写关键字数量');
                        return false;
                }
                // alert($('input[name="require[pic]"]:checked').val());
                if ($('input[name="require[comment]"]:checked').val()=='comment_2') {
                // alert($('input[name="require[comment]"]:checked').val());
                        var bool_comment = true;
                        $('.comment').each(function(){
                                // console.log($(this).val())
                                if(!$(this).val()){
                                        bool_comment = false;
                                        return false;
                                }
                        })
                        if (!bool_comment) {
                                make_alert_html('系统提示','请填写指定评语');
                                return false;
                        }
                        
                }

                if ($('input[name="require[pic]"]:checked').val()=='pic_2') {
                        var bool_comment_pic = true;
                        var comment_pic_num = 0;
                        $('.J_comment_url').each(function(){
                                if(!$(this).val()){
                                        bool_comment_pic = false;
                                        return false;
                                }else{
                                        comment_pic_num++;
                                }
                        })
                        if (!bool_comment_pic || comment_pic_num==0) {
                                make_alert_html('系统提示','请填写指定买家秀');
                                return false;
                        }
                        
                }

                // if(!$('#J_item_title').val()){
                //     make_alert_html('系统提示','请填写商品名');
                //                 return false;
                // }
                if(!$('#J_price').val()){
                    make_alert_html('系统提示','请填写价格');
                                return false;
                }
                if(!$('#J_buy_num').val()){
                    make_alert_html('系统提示','请填写购买件数');
                                return false;
                }
                var has_pic = $(".J_has_pic");
                if(has_pic.length==0){
                        make_alert_html('系统提示','请至少上传一张商品图片');
                        return false;
                }

                // if(!$('#J_item_link').val()){
                //     make_alert_html('系统提示','请填写产品链接');
                //                 return false;
                // }
                if (!$("#J_pic_box").is(":hidden")) {
                        if ($('input[name="require[huobi]"]:checked').val()=='huobi_1') {
                                if(!$('.J_pic_url').eq(0).val() || !$('.J_shop_name').eq(0).find('input').val()){
                                        make_alert_html('系统提示','请上传货比商品图片和店铺名称');
                                        return false;
                                }
                        }
                        if ($('input[name="require[huobi]"]:checked').val()=='huobi_2') {
                                if(!$('.J_pic_url').eq(0).val() || !$('.J_shop_name').eq(0).find('input').val() || !$('.J_pic_url').eq(1).val() || !$('.J_shop_name').eq(1).find('input').val()){
                                        make_alert_html('系统提示','请上传货比商品图片和店铺名称');
                                        return false;
                                }
                        }
                        if ($('input[name="require[huobi]"]:checked').val()=='huobi_3') {
                                if(!$('.J_pic_url').eq(0).val() || !$('.J_shop_name').eq(0).find('input').val() 
                                        || !$('.J_pic_url').eq(1).val() || !$('.J_shop_name').eq(1).find('input').val() 
                                        || !$('.J_pic_url').eq(2).val() || !$('.J_shop_name').eq(2).find('input').val()){
                                        make_alert_html('系统提示','请上传货比商品图片和店铺名称');
                                        return false;
                                }
                        }
                }
                // return false;
                return true;
                
        },
        _sum_total:function(){
                var c = shop_item_temp;
                var _sku_price = eval("("+c.sku_price+")");
                var _sku_price2 = eval("("+c.sku_price2+")");
                var shop_type = $('input[name="shop_type"]').val();
                var item_price = Number($('#J_price').val())
                var buy_num = Number($('#J_buy_num').val())
                if (!c.jichu_price) {
                        if(shop_type == 10){c.jichu_price = _sku_price.type_10}
                        if(shop_type == 20){c.jichu_price = _sku_price.type_20}
                        if(shop_type == 30){c.jichu_price = _sku_price.type_30}
                        if(shop_type == 40){c.jichu_price = _sku_price.type_40}
                        if(shop_type == 50){c.jichu_price = _sku_price.type_50}
                }
                if (!c.jichu_price2) {
                        if(shop_type == 10){c.jichu_price2 = _sku_price2.type_10}
                        if(shop_type == 20){c.jichu_price2 = _sku_price2.type_20}
                        if(shop_type == 30){c.jichu_price2 = _sku_price2.type_30}
                        if(shop_type == 40){c.jichu_price2 = _sku_price2.type_40}
                        if(shop_type == 50){c.jichu_price2 = _sku_price2.type_50}
                }

                var k_num = 0;
                $('.k_num').each(function(){
                if ($(this).val() && isPriceNumber($(this).val())) {
                        k_num += Number($(this).val());
                }
                })
                console.log(shop_type)
                console.log(c.jichu_price)
                console.log(c.jichu_price2)
                var total = parseFloat(c.jichu_price);
                var total2 = parseFloat(c.jichu_price2);
                $('.total').each(function(){
                        var price = $(this).attr('price')
                        var price2 = $(this).attr('price2')

                        total += Number(price)
                        total2 += Number(price2)
                })
                total += item_price*buy_num;
                total2 += item_price*buy_num;
                console.log(total)
                console.log(total2)
                var stock = Number($('#J_stock').val());
                $('#J_total_price').val(total);
                $('#J_total_price2').val(total2);
                $('#J_payment').val(total*k_num);
                // return total;
            
        },
        upload_success:function(_data){
                var c = shop_item_temp;
                _remove_ajax_frame('upload_success');
                $('#J_form_do').val('');
                $('.J_pic').eq(c._index).attr('name','');
                if(_data['status']!=200){
                        c.bool_sending = false;
                        make_alert_html(arr_language['warn'],_data['info']);
                        return false;
                }
                c.bool_sending = false;
                $('.J_pic_show').eq(c._index).html('<img src="'+_data['data']+'" style="width:100px">');
                $('.J_pic_url').eq(c._index).val(_data['data']);
                $('.J_shop_name').eq(c._index).show();
        },
        upload_pic_success:function(_data){
                var c = shop_item_temp;
                _remove_ajax_frame('upload_success');
                $('#J_form_do').val('');
                $('.J_comment_pic').eq(c._index).attr('name','');
                var id = $('.J_comment_pic').eq(c._index).attr('data-id');
                if(_data['status']!=200){
                        c.bool_sending = false;
                        make_alert_html(arr_language['warn'],_data['info']);
                        return false;
                }
                c.bool_sending = false;
                $('.comment_up_pic_box').eq(c._index).append(' <span class="J_comment_pic_show"><img src="'+_data['data']+'" style="width: 100px"><i class="pic_ico"></i><input type="hidden" class="J_comment_url" name="comment_pic['+id+'][]" value="'+_data['data']+'"></span>');
        },
        send_success:function(_data){
                _remove_ajax_frame('send_success');
                if(_data['status']!=200){
                        shop_item_temp.bool_sending = false;
                        make_alert_html(arr_language['warn'],_data['info']);
                        return false;   
                }
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);
        },
        //批量下架
        _listen_bat_inactive:function(){
                $('#J_bat_inactive').click(function(){
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
                        confirm_cancle(shop_item_temp.bat_inactive,arr_language['warn'],arr_language['del_3']);
                        shop_item_temp._this = _this;
                });
        },
        
        bat_inactive:function(){
                _ajax_jsonp('/shop.php?mod=item&extra=bat_inactive&ids='+_str_ids,shop_item_temp.bat_inactive_success);
        },
        bat_inactive_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);     
        }
}


var shop_item_temp_del = {
        _init:function(){
                shop_item_temp_del._listen_del();
        },
        _listen_del:function(){
                var c = shop_item_temp_del;
                $('.J_btn_del').click(function(){
                                                                var _this = $(this);
                                                                confirm_cancle(c._del,arr_language['warn'],arr_language['del_2']);
                                                                c._this = _this;
                                                        });
        },
        _del:function(){
                var c = shop_item_temp_del;
                var _this = c._this;
                var int_item_id = _this.attr('data-item_id');
                _ajax_jsonp('/shop.php?mod=item_temp&extra=del&item_id='+int_item_id,c._del_success);
        },
        _del_success:function(_data){
                make_alert_html(arr_language['notice'],_data['info'],window.location.href);     
        }       
}


var admin_item_pics = {
        uploading:false,
                pic_max_num:5,//商品图片最大上传数量
                _init:function(){
                var c = admin_item_pics;
                        c._listen_pic_upload_btn();
                        /*如果有初始化有商品图片html*/
                        var _pic_list = $("#J_pic_list input"),_html='';
                        for(var i=0;i<_pic_list.length;i++){
                                var _pic_source = _pic_list.eq(i).val();
                                var pid = _pic_list.eq(i).attr('data-pid');
                                _html = c.make_pic_html(i,_pic_source,pid);
                                $("#J_pic_list").children('li').eq(i).html(_html);
                                if(i>=c.pic_max_num-1){
                                        $("#J_pic_upload").css('display','none');       
                                }
                        }
                        c._listen_move_del_pic();
                },
                _listen_pic_upload_btn:function(){//监听商品图片上传按钮
                var c = admin_item_pics;
                        $("#J_pic_upload_btn").change(function(){
                                $(this).attr('name','pic')
                                if(c.uploading){
                                        make_alert_html(arr_language['warn'],'有一张图片正在上传中，请稍后...');
                                        return false;
                                }
                                $("#J_do").val('upload_image');         
                                setTimeout(function(){
                                        _ajax_frame(get_obj('ajaxframeid'),c._pic_upload_success,'send_success');
                                        get_obj('J_send_form').submit();                        
                                },10);
                                $(".J_sku_pic_upload_btn").attr('disabled',true);
                                c.uploading = true;
                                $('#J_form_do').val('upload');
                                $("#J_pic_upload_status").val(arr_language['upload']['uploading']);
                        });
                },
                _pic_upload_success:function(_data){//商品图片上传成功
                var c = admin_item_pics;
                        $('#J_form_do').val('');
                        $("#J_pic_upload_btn").attr('name','')
                        $(".J_sku_pic_upload_btn").attr('disabled',false);
                        c.uploading = false;
                        $("#J_pic_upload_status").val(arr_language['upload']['upload']);
                        _remove_ajax_frame('send_success');
                        if(_data.status==200){
                                var pic_list = $("#J_pic_list").children('li');
                                var pic_index = $(".J_has_pic").length;//当前图片的位置，从0开始
                                
                                pic_list.eq(pic_index).html(c.make_pic_html(pic_index,_data.data));
                                if(pic_index>=c.pic_max_num-1){
                                        $("#J_pic_upload").css('display','none');       
                                }
                                c._reset_listen_move_del_pic();
                        }else{
                                make_alert_html(arr_language['warn'],arr_language['error_1']);
                        }
                        
                },
                make_pic_html:function(_index,_pic_source){//组合商品图片html
                var c = admin_item_pics;
                        var _html = _html_move_del_btn = left_btn_style = right_btn_style = '';
                        if(_index==0){
                                left_btn_style = ' style="display:none;"';
                        }
                        if(_index==4){
                                right_btn_style = ' style="display:none;"';
                        }
                        
                        _html_move_del_btn = '<i'+left_btn_style+' class="J_move_pic_left nico g_l" title="向左移"><!--左--></i>';
                        _html_move_del_btn += '<i'+right_btn_style+' class="J_move_pic_right nico g_r" title="向右移"><!--右--></i>';
                        _html_move_del_btn += '<i class="J_del_pic nico g_c" title="删除"><!--删除--></i>';
                        
                        var _html = '<div class="goods_nav J_has_pic">';
                                _html += _html_move_del_btn+'</div>';
                                _html += '<span><img src="'+_pic_source+'"></span>';
                                _html += '<input type="hidden" name="pic[]" value="'+_pic_source+'">';
                                
                        return _html;
                },
                _listen_move_del_pic:function(){//监听图片移动和删除按钮
                var c = admin_item_pics;
                        $(".J_move_pic_left").click(function(){
                                var _index = $(".J_move_pic_left").index(this);
                                c._move_pic(_index,'left');                                                                     
                        });
                        $(".J_move_pic_right").click(function(){
                                var _index = $(".J_move_pic_right").index(this);
                                c._move_pic(_index,'right');                                                                                    
                        });
                        
                        $(".J_del_pic").click(function(){
                                var _index = $(".J_del_pic").index(this);
                                c._del_pic(_index);                                                                             
                        });
                },
                _remove_listen_move_del_pic:function(){//去除监听图片移动和删除按钮
                var c = admin_item_pics;
                        $(".J_move_pic_left").unbind('click');
                        $(".J_move_pic_right").unbind('click');
                        $(".J_del_pic").unbind('click');
                },
                _reset_listen_move_del_pic:function(){//重置所有监听图片移动和删除按钮
                var c = admin_item_pics;
                        c._remove_listen_move_del_pic();
                        c._listen_move_del_pic();
                        var _pic_list = $(".J_has_pic");
                        _pic_list.children('.J_move_pic_left').css('display','');
                        _pic_list.children('.J_move_pic_right').css('display','');
                        _pic_list.eq(0).children('.J_move_pic_left').css('display','none');
                        _pic_list.eq((_pic_list.length-1)).children('.J_move_pic_right').css('display','none');
                },
                _move_pic:function(_index,_do){//移动图片_do = left or right
                var c = admin_item_pics;
                        var _this = $("#J_pic_list").children('li').eq(_index);
                        if(_do=='left'){
                                var _other = $("#J_pic_list").children('li').eq(_index-1);
                                _other.before(_this);
                        }
                        else if(_do=='right'){
                                var _other = $("#J_pic_list").children('li').eq(_index+1);      
                                _this.before(_other);
                        }
                        c._reset_listen_move_del_pic();
                },
                _del_pic:function(_index){
                var c = admin_item_pics;
                        if(!confirm(arr_language['del_2'])){
                                return false;   
                        }
                        c._remove_listen_move_del_pic();//在节点移除之前清除所有监听
                        var _this = $("#J_pic_list").children('li').eq(_index);
                        var _data = new Object();
                        _data.filename = _this.children('input').val();
                        _data.pid = _this.children('input').attr('data-pid');
                        _data.item_id = $("#J_item_id").val();
                        _this.remove();
                        if($(".J_has_pic").length==0){//已经没有图片了
                                $("#J_pic_list").children('li').eq(0).before('<li><font><em>*</em> '+arr_language['mall']['main_pic']+'</font></li>');
                        }
                        else{
                                $("#J_pic_list").append('<li></li>');
                        }
                        $("#J_pic_upload").css('display','');
                        c._reset_listen_move_del_pic();
                }
};