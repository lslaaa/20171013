var admin_file = {
	_init:function(){
		var c = admin_file;
		c._listen();	
		c._listen_cat();
	},
	_listen:function(){
		var c = admin_file;
		$('#J_file').change(function(){
							$('#J_form_do').val('upload');		
							if(c.bool_sending){
								return false;	
							}
							//$('#J_pic').attr('disabled',true);
							c.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),c.upload_success,'send_success');
							$('#J_send_form').submit();
						});
		$('#J_send_form').submit(function(){
							if($('#J_form_do').val()=='upload'){
								return true;	
							}
							
							var obj_cat = $('#J_cat');
							if(obj_cat.css('display')!='none'){
								var obj_cid = $('#J_cid');
								if(obj_cid.val()<=0){
									make_alert_html(arr_language['warn'],arr_language['file']['error_1']);
									return false;	
								}
								
								var obj_cid_2 = $('#J_cid_2');
								if(obj_cid_2.css('display')!='none' && obj_cid_2.val()<=0){
									make_alert_html(arr_language['warn'],arr_language['file']['error_1']);
									return false;	
								}
							}
							
							var obj_title = $('#J_title');
							if(!obj_title.val()){
								make_alert_html(arr_language['warn'],arr_language['file']['error_2']);
								return false;	
							}
							
							var obj = $('#J_file_url');
							if(!obj.val()){
								make_alert_html(arr_language['warn'],arr_language['file']['error_3']);
								return false;	
							}
							
							if(c.bool_sending){
								return false;	
							}
							$('#J_form_do').val('add');
							c.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');			  
						});
	},
	upload_success:function(_data){
		var c = admin_file;
		_remove_ajax_frame('upload_success');
		$('#J_form_do').val('');
		if(_data['status']!=200){
			c.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;
		}
		c.bool_sending = false;
		$('#J_file_show').html('<a href="'+_data['data']+'"  target="_blank">'+arr_language['show_file']+'</a>');
		$('#J_file_url').val(_data['data']);
	},
	_listen_cat:function(){
		var c = admin_file;
		var obj_cid = $('#J_cid');
		obj_cid.change(function(){
						var _this = $(this);
						var int_pid = _this.val();
						c._cat_select(int_pid);
					});
		if(obj_cid.attr('data-cid')>0){
			var int_pid = obj_cid.attr('data-cid');
			var obj_options = obj_cid.children();
			for(var i=0;i<obj_options.length;i++){
				var ci = obj_options.eq(i);
				if(ci.val()==int_pid){
					ci.attr('selected',true);
					break;
				}
			}
			c._cat_select(obj_cid.attr('data-cid'));	
		}		
	},
	_cat_select:function(int_pid){
		var _html = '';
		var c = admin_file;
		if(int_pid>0){
			var int_cid_2 = $('#J_cid_2').attr('data-cid');
			for(var ci,i=0;ci=c.json_cat[i++];){
				if(ci['pid']==int_pid){
					var str_selected = int_cid_2==ci['cid'] ? ' selected="selected"' : '';
					_html += '<option value="'+ci['cid']+'"'+str_selected+'>'+ci['name']+'</option>';
				}
			}
		}
		if(_html){
			_html = '<option value="0">'+arr_language['select']+'</option>'+_html;
			$('#J_cid_2').css('display','');
		}
		else{
			$('#J_cid_2').css('display','none');	
		}
		$('#J_cid_2').html(_html);
	},
	_listen_del:function(){
		var c = admin_file;
		$('.J_btn_del').click(function(){
								var _this = $(this);
								confirm_cancle(c._del,arr_language['warn'],arr_language['del_2']);
								c._this = _this;
							});
	},
	_del:function(){
		var c = admin_file;
		var _this = admin_file._this;
		var int_id = _this.attr('data-id');
		_ajax_jsonp('/admin.php?mod=file&mod_dir=file&extra=del&id='+int_id,c._del_success);
	},
	_del_success:function(_data){
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);	
	},
	send_success:function(_data){
		_remove_ajax_frame('send_success');
		//console.log(_data);
		if(_data['status']!=200){
			c.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}
		make_alert_html(arr_language['notice'],_data['info'],'/admin.php?mod=file&mod_dir=file');
	}
}