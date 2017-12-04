var admin_order_detail = {
	_init:function(){
		admin_order_detail._listen_change_info();	
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

var admin_order_cancel = {
	_init:function(){
		admin_order_cancel._listen_cancel();	
	},
	_listen_cancel:function(){
		var c = admin_order_cancel;
		$('.J_btn_cancel').click(function(){
								var _this = $(this);
								c._this = _this;
								confirm_cancle(c._cancel,arr_language['warn'],arr_language['mall']['order']['cancle_tip']);
							});
	},
	_cancel:function(){
		var c = admin_order_cancel;
		var _data = new Object();
			_data['order_id'] = c._this.attr('data-order_id');
			_ajax_jsonp("/admin.php?mod=order&mod_dir=mall&extra=cancel",c._cancel_success,_data);	
	},
	_cancel_success:function(_data){
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);
	}
}

var admin_order_remark = {
	_init:function(){
		admin_order_remark._listen_blur();
			
	},
	_listen_blur:function(){
		var c = admin_order_remark;
		$('#J_remark_2').blur(function(){
							if(c.bool_sending){
									return false;	
								}
							c.bool_sending = true;
							var _data = new Object();
								_data['order_id'] = $('#J_hid_order_id').val();
								_data['remark_2'] = $('#J_remark_2').val();
								_ajax_jsonp("/admin.php?mod=order&mod_dir=mall&extra=update_remark",c.send_success,_data);			   
						});
	},
	send_success:function(_data){
		var c = admin_order_remark;
		//console.log(_data);
		if(_data['status']!=200){
			c.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}
		make_alert_html(arr_language['notice'],_data['info']);
	}
};

var admin_order_fahuo = {
	_init:function(){
		admin_order_fahuo._listen_btn();
	},
	_listen_btn:function(){
		var c = admin_order_fahuo;
		$('#J_add_express,#J_mod_express').click(function(){
								setTimeout(function(){
									_ajax_jsonp("/admin.php?mod=order&mod_dir=mall&extra=get_express",c.get_express_success);
								},20);
							});	
	},
	get_express_success:function(_data){
		var c = admin_order_fahuo;
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
								_ajax_jsonp("/admin.php?mod=order&mod_dir=mall&extra=update_express",c.send_success,_data);
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

/*var admin_ads = {
	_init:function(){
		admin_ads._listen_add();	
	},
	_listen_add:function(){
		$('#J_ads_cid').change(function(){
							var _this = $(this);
							var obj_options = _this.children();
							for(var i=0;i<obj_options.length;i++){
								var ci = obj_options.eq(i);
								if(ci.val()==_this.val()){
									var obj_option = ci;
									break;	
								}
							}
							admin_ads._format_options(obj_option);
						});
		$('#J_pic').change(function(){
							$('#J_form_do').val('upload');		
							if(admin_ads.bool_sending){
								return false;	
							}
							$('#J_pic').attr('disabled',true);
							admin_ads.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),admin_ads.upload_success,'send_success');
							$('#J_send_form').submit();
						});
		$('#J_send_form').submit(function(){
							if($('#J_form_do').val()=='upload'){
								return true;	
							}
							
							if(!admin_ads._verify()){
								return false;
							}
							
							if(admin_ads.bool_sending){
								return false;	
							}
							
							$('#J_form_do').val('add');
							admin_ads.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),admin_ads.send_success,'send_success');			  
						});
		if($('#J_ads_cid').val()>0){
			var obj_options = $('#J_ads_cid option');
			for(var i=0;i<obj_options.length;i++){
				var ci = obj_options.eq(i);
				if(ci.val()==$('#J_ads_cid').val()){
					var obj_option = ci;
					break;	
				}
			}
			admin_ads._format_options(obj_option);	
		}
	},
	_verify:function(){
		var obj_title = $('#J_title');
		if(!obj_title.val()){
			make_alert_html(arr_language['warn'],arr_language['ads']['error_1']);
			return false;
		}
		
		var obj_url = $('#J_url');
		if(!obj_url.val()){
			make_alert_html(arr_language['warn'],arr_language['ads']['error_2']);
			return false;
		}
		
		if($('#J_des_box').css('display')!='none'){
			var obj_des = $('#J_des');
			if(!obj_des.val()){
				make_alert_html(arr_language['warn'],arr_language['ads']['error_3']);
				return false;
			}
		}
		if($('#J_pic_box').css('display')!='none'){
			var obj_pic = $('#J_pic_url');
			if(!obj_pic.val()){
				make_alert_html(arr_language['warn'],arr_language['ads']['error_4']);
				return false;
			}
		}
		
		return true;
	},
	_format_options:function(obj_option){
		if(obj_option.attr('data-des')==1){
			$('#J_des_box').css('display','');
		}
		else{
			$('#J_des_box').css('display','none');
		}
		
		if(obj_option.attr('data-pic')==1){
			$('#J_pic_box').css('display','');
			var _curr_width  = obj_option.attr('data-max_width');
			var _curr_height = obj_option.attr('data-max_height');
			if(_curr_width>0 && _curr_height==0){
				var str_size = arr_language['upload']['max_width'].replace('xxx',_curr_width);
			}
			else if(_curr_width==0 && _curr_height>0){
				var str_size = arr_language['upload']['max_height'].replace('xxx',_curr_height);	
			}
			else if(_curr_width>0  && _curr_width>0){
				var str_size = _curr_width+'px*'+_curr_height+'px';	
			}
			$('#J_best_size').html(str_size);
		}
		else{
			$('#J_pic_box').css('display','none');
		}	
	},
	upload_success:function(_data){
		_remove_ajax_frame('upload_success');
		$('#J_form_do').val('');
		if(_data['status']!=200){
			admin_ads.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;
		}
		admin_ads.bool_sending = false;
		$('#J_pic_show').html('<img src="'+_data['data']+'" style="width:100px">');
		$('#J_pic_url').val(_data['data']);
	},
	_listen_del:function(){
		$('.J_btn_del').click(function(){
								var _this = $(this);
								confirm_cancle(admin_ads._del,arr_language['warn'],arr_language['del_2']);
								admin_ads._this = _this;
							});
	},
	_del:function(){
		var _this = admin_ads._this;
		var int_aid = _this.attr('data-aid');
		_ajax_jsonp('/admin.php?mod=ads&extra=del&mod_dir=ads&aid='+int_aid,admin_ads._del_success);
	},
	_del_success:function(_data){
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);	
	},
	send_success:function(_data){
		_remove_ajax_frame('send_success');
		//console.log(_data);
		if(_data['status']!=200){
			$('#J_pic').attr('disabled',false);
			admin_ads.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}
		make_alert_html(arr_language['notice'],_data['info'],'/admin.php?mod=ads&mod_dir=ads');
	}
}
*/