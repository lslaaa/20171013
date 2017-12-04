var admin_ads = {
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
							//$('#J_pic').attr('disabled',true);
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
		var obj = $('#J_language');
		if(obj.length>0){
			if(obj.val()<=0){
				make_alert_html(arr_language['warn'],arr_language['error_2']);
				return false;
			}
		}
		
		var obj_title = $('#J_title');
		if(!obj_title.val()){
			make_alert_html(arr_language['warn'],arr_language['ads']['error_1']);
			return false;
		}
		if($('#J_url_box').css('display')!='none'){
			var obj_url = $('#J_url');
			if(!obj_url.val()){
				make_alert_html(arr_language['warn'],arr_language['ads']['error_2']);
				return false;
			}
		}
		
		if($('#J_des_box').css('display')!='none'){
			var obj_des = $('#J_des');
			if(!obj_des.val()){
				make_alert_html(arr_language['warn'],arr_language['ads']['error_3']);
				return false;
			}
		}
		
		if($('#J_des_2_box').css('display')!='none'){
			var obj_des = $('#J_des_2');
			if(!obj_des.val()){
				make_alert_html(arr_language['warn'],arr_language['ads']['error_5']);
				return false;
			}
		}
		if($('#J_des_3_box').css('display')!='none'){
			var obj_des = $('#J_des_3');
			if(!obj_des.val()){
				make_alert_html(arr_language['warn'],arr_language['ads']['error_6']);
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
		if(obj_option.attr('data-url')==1){
			$('#J_url_box').css('display','');
		}
		else{
			$('#J_url_box').css('display','none');
		}
		if(obj_option.attr('data-des')==1){
			$('#J_des_box').css('display','');
		}
		else{
			$('#J_des_box').css('display','none');
		}
		
		if(obj_option.attr('data-des_2')==1){
			$('#J_des_2_box').css('display','');
		}
		else{
			$('#J_des_2_box').css('display','none');
		}
		
		if(obj_option.attr('data-des_3')==1){
			$('#J_des_3_box').css('display','');
		}
		else{
			$('#J_des_3_box').css('display','none');
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

var admin_ads_search = {
	_init:function(){
		admin_ads_search._listen_cat();	
	},
	_listen_cat:function(){
		var obj_ads_cid = $('#J_ads_cid');
		obj_ads_cid.change(function(){
						var _this = $(this);
						var int_ads_cid = _this.val();
						var str_url = window.location.href.replace(/\&ads_cid=[\d]+/,'');
						window.location.href = str_url+'&ads_cid='+int_ads_cid;
					});	
		var obj_language = $('#J_language');
		obj_language.change(function(){
						var _this = $(this);
						var str_language = _this.val();
						var str_url = window.location.href.replace(/\&language=[a-z_]+/,'');
						window.location.href = str_url+'&language='+str_language;
					});	
	}
}