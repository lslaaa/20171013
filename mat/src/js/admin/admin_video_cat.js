			
var admin_video_cat = {
	_this:'',
	bool_sending:false,
	_init:function(){
		var c = admin_video_cat;
		$('#J_add_level_one').click(function(){
								var _this = $(this);
								var _data = new Object;				 
								_data.pid = 0;
								_data._do = 'add';
								_data.cid = 0;
								_data.name = '';
								if(typeof(json_languages)=='object'){
									for(var i=0,ci;ci=json_languages[i++];){
										_data['name_'+ci['cname']] = '';
									}
								}
								_data.url  = '';
								_data.pic  = '';
								_data['sort'] = 200;
								var _html = admin_video_cat.make_html(_data);
								_this.parent().next('.J_submit_from').html(_html);
								c._listen_upload();
								c.listen_send();
							});
		$('.J_show_hide_menu_add').click(function(){
								$('.J_submit_from').html('');
								var _this = $(this);
								var obj_data = _this.parent().parent();
								var _data = new Object;				 
								_data.pid = obj_data.attr('data-cid');
								_data._do = 'add';
								_data.cid = 0;
								_data.name = '';
								if(typeof(json_languages)=='object'){
									for(var i=0,ci;ci=json_languages[i++];){
										_data['name_'+ci['cname']] = '';
									}
								}
								_data.url  = '';
								_data.pic  = '';
								_data['sort'] = 200;
								var _html = admin_video_cat.make_html(_data);
								_this.parent().next('.J_submit_from').html(_html);
								c._listen_upload();
								c.listen_send();					  
							});
		$('.J_show_hide_menu_mod').click(function(){
								$('.J_submit_from').html('');
								var _this = $(this);
								var obj_data = _this.parent().parent();
								var int_page_id = obj_data.attr('data-cid');
								_ajax_jsonp('/admin.php?mod=video&mod_dir=video&extra=get_one_cat&cid='+int_page_id,admin_video_cat._mod);
								c._this = _this;
							});
		$('.J_show_hide_menu_del').click(function(){
								$('.J_submit_from').html('');
								var _this = $(this);
								confirm_cancle(admin_video_cat._del,arr_language['warn'],arr_language['del_2']);
								c._this = _this;
							});
	},
	listen_send:function(){
		$('#J_btn_send').unbind('click');
		$('#J_btn_cancel').unbind('click');
		$('#J_btn_send').click(function(){
							admin_video_cat.verify();		
						});
		$('#J_btn_cancel').click(function(){
							$('.J_submit_from').html('');
						});
	},
	_mod:function(_data){
		var c = admin_video_cat;
		//console.log(_data);
		var _this = c._this;
		var _data = _data['data'];
		_data._do = 'mod';
		var _html = c.make_html(_data);
		_this.parent().next('.J_submit_from').html(_html);
		c._listen_upload();
		c.listen_send();	
	},
	_del:function(){
		var _this = admin_video_cat._this;
		var obj_data = _this.parent().parent();
		var int_cid = obj_data.attr('data-cid');
		_ajax_jsonp('/admin.php?mod=video&extra=del_cat&mod_dir=video&cid='+int_cid,admin_video_cat._del_success);
	},
	_del_success:function(_data){
		if(_data['status']==200){
			window.location.href = window.location.href;
		}
	},
	verify:function(){
		var obj_name = $('#J_name');
		if(!obj_name.val()){
			make_alert_html(arr_language['warn'],arr_language['page']['error_3']);
			return false;
		}
		if(typeof(json_languages)=='object'){
			for(var i=0,ci;ci=json_languages[i++];){
				var obj = $('#J_name_'+ci['cname']);
				if(!obj.val()){
					make_alert_html(arr_language['warn'],ci['name']+arr_language['mall']['item_cat']['error_1']);
					return false;
				}
			}
		}
		/*
		var obj_url = $('#J_url');
		if(!obj_url.val()){
			make_alert_html(arr_language['warn'],arr_language['setting']['menu']['error_2']);
			return false;
		}
		*/
		var obj_sort = $('#J_sort');
		if(!obj_sort.val()){
			make_alert_html(arr_language['warn'],arr_language['setting']['menu']['error_3']);
			return false;
		}
		
		if(admin_video_cat.bool_sending){
			return false;	
		}
		_ajax_frame(get_obj('ajaxframeid'),admin_video_cat.send_success,'send_success');
		$('#J_send_form').submit();
		admin_video_cat.bool_sending = true;
	},
	send_success:function(_data){
		_remove_ajax_frame('send_success');
		//console.log(_data);
		if(_data['status']!=200){
			admin_video_cat.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}	
		window.location.href = window.location.href;	
	},
	make_html:function(_data){
		console.log(_data);
                var select_html = '';
                if(_data.type=='video'){
                    select_html = '<select name="p_type">'+
                                            '<option value="image">图片</option>'+
                                            '<option value="video" selected="selected">视频</option>'+
                                        '</select>';
                }else{
                    select_html = '<select name="p_type">'+
                                            '<option value="image" selected="selected">图片</option>'+
                                            '<option value="video">视频</option>'+
                                        '</select>';
                }
                
		var _html = '<form action="" method="post" id="J_send_form" target="ajaxframeid" enctype="Multipart/form-data">'+
					'<input type="hidden" name="formhash" value="'+str_formhash+'"/>'+
					'<input type="hidden" id="J_pid" name="pid" value="'+_data.pid+'"/>'+
					'<input type="hidden" id="J_do" name="_do" value="'+_data._do+'"/>'+
					'<input type="hidden" id="J_id" name="cid" value="'+_data.cid+'"/>'+
					'<input class="J_callback" type="hidden" name="callback" value="parent.send_success">'+
					'<div class="add_box">'+
                                                '<p><em>'+arr_language['type']+':</em>'+select_html+'</p>'+
						'<p><em>'+arr_language['cat']+':</em>'+
						'<input type="text" id="J_name" name="name" value="'+_data.name+'"></p>';
			if(typeof(json_languages)=='object'){
				for(var i=0,ci;ci=json_languages[i++];){
					_html += '<em>'+ci['name']+':</em>'+
							'<input type="text" id="J_name_'+ci['cname']+'" name="name_'+ci['cname']+'" value="'+_data['name_'+ci['cname']]+'">';
				}	
			}
			_html += 	'<p>'+
						'<em>'+arr_language['pic']+':</em>'+
						'<a class="blue_nav updown"><input id="J_pic_url" name="pic" type="hidden" value="'+_data['pic']+'"><input id="J_pic" type="file" name="pic">'+arr_language['upload']['upload_2']+'</a>'+
						'<p id="J_pic_show" class="updown_img">'+(_data['pic']?'<img  style="width:100px" src="'+_data['pic']+'">':'')+'</p>'+
					  '</p>'+
						'<p>'+
							'<em>'+arr_language['sort']+':</em>'+
							'<input type="text" id="J_sort" name="sort" value="'+_data['sort']+'">'+
						'</p>'+
						'<p>'+
							'<a id="J_btn_send" class="blue_nav">'+arr_language['send']+'</a>'+
							'<a id="J_btn_cancel" class="gray_nav">'+arr_language['cancel']+'</a>'+
						'</p>'+
					'</div>'+
				'</form>';
		return _html;
	},
	_listen_upload:function(){
		var c = admin_video_cat;
		$('#J_pic').unbind('change');
		$('#J_pic').change(function(){
							c.last_do = $('#J_do').val();
							$('#J_do').val('upload');		
							if(c.bool_sending){
								return false;	
							}
							//$('#J_pic').attr('disabled',true);
							c.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),c.upload_success,'send_success');
							$('#J_send_form').submit();
						});
	},
	upload_success:function(_data){
		var c = admin_video_cat;
		_remove_ajax_frame('upload_success');
		$('#J_form_do').val('');
		if(_data['status']!=200){
			c.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;
		}
		c.bool_sending = false;
		$('#J_do').val(c.last_do);
		$('#J_pic_show').html('<img src="'+_data['data']+'" style="width:100px">');
		$('#J_pic_url').val(_data['data']);
	}
}