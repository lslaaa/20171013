			
var admin_page_cat = {
	_this:'',
	_init:function(){
		$('#J_add_level_one').click(function(){
								var _this = $(this);
								var _data = new Object;				 
								_data.pid = 0;
								_data._do = 'add';
								_data.page_id = 0;
								_data.name = '';
								if(typeof(json_languages)=='object'){
									for(var i=0,ci;ci=json_languages[i++];){
										_data['name_'+ci['cname']] = '';
									}
								}
								_data.url  = '';
								_data['sort'] = 200;
								var _html = admin_page_cat.make_html(_data);
								_this.parent().next('.J_submit_from').html(_html);
								admin_page_cat.listen_send();
							});
		$('.J_show_hide_menu_add').click(function(){
								$('.J_submit_from').html('');
								var _this = $(this);
								var obj_data = _this.parent().parent();
								var _data = new Object;				 
								_data.pid = obj_data.attr('data-page_id');
								_data._do = 'add';
								_data.page_id = 0;
								_data.name = '';
								if(typeof(json_languages)=='object'){
									for(var i=0,ci;ci=json_languages[i++];){
										_data['name_'+ci['cname']] = '';
									}
								}
								_data.url  = '';
								_data['sort'] = 200;
								var _html = admin_page_cat.make_html(_data);
								_this.parent().next('.J_submit_from').html(_html);
								admin_page_cat.listen_send();					  
							});
		$('.J_show_hide_menu_mod').click(function(){
								$('.J_submit_from').html('');
								var _this = $(this);
								var obj_data = _this.parent().parent();
								var int_page_id = obj_data.attr('data-page_id');
								_ajax_jsonp('/admin.php?mod=page&extra=get_one_cat&page_id='+int_page_id,admin_page_cat._mod);
								admin_page_cat._this = _this;
							});
		$('.J_show_hide_menu_del').click(function(){
								$('.J_submit_from').html('');
								var _this = $(this);
								confirm_cancle(admin_page_cat._del,arr_language['warn'],arr_language['del_2']);
								admin_page_cat._this = _this;
							});
	},
	listen_send:function(){
		$('#J_btn_send').unbind('click');
		$('#J_btn_cancel').unbind('click');
		$('#J_btn_send').click(function(){
							admin_page_cat.verify();		
						});
		$('#J_btn_cancel').click(function(){
							$('.J_submit_from').html('');
						});
	},
	_mod:function(_data){
		//console.log(_data);
		var _this = admin_page_cat._this;
		var _data = _data['data'];
		_data._do = 'mod';
		var _html = admin_page_cat.make_html(_data);
		_this.parent().next('.J_submit_from').html(_html);
		admin_page_cat.listen_send();	
	},
	_del:function(){
		var _this = admin_page_cat._this;
		var obj_data = _this.parent().parent();
		var int_cid = obj_data.attr('data-page_id');
		//_ajax_jsonp('/admin.php?mod=item_cat&extra=del&mod_dir=mall&mod_dir=mall&cid='+int_cid,admin_page_cat._del_success);
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
		
		if(admin_page_cat.bool_sending){
			return false;	
		}
		_ajax_frame(get_obj('ajaxframeid'),admin_page_cat.send_success,'send_success');
		$('#J_send_form').submit();
		admin_page_cat.bool_sending = true;
	},
	send_success:function(_data){
		_remove_ajax_frame('send_success');
		//console.log(_data);
		if(_data['status']!=200){
			admin_page_cat.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}	
		window.location.href = window.location.href;	
	},
	make_html:function(_data){
		var _html = '<form action="" method="post" id="J_send_form" target="ajaxframeid">'+
					'<input type="hidden" name="formhash" value="'+str_formhash+'"/>'+
					'<input type="hidden" id="J_pid" name="pid" value="'+_data.pid+'"/>'+
					'<input type="hidden" id="J_do" name="_do" value="'+_data._do+'"/>'+
					'<input type="hidden" id="J_id" name="page_id" value="'+_data.page_id+'"/>'+
					'<input class="J_callback" type="hidden" name="callback" value="parent.send_success">'+
					'<div class="add_box">'+
						'<em>'+arr_language['cat']+':</em>'+
						'<input type="text" id="J_name" name="name" value="'+_data.name+'">';
			if(typeof(json_languages)=='object'){
				for(var i=0,ci;ci=json_languages[i++];){
					_html += '<em>'+ci['name']+':</em>'+
							'<input type="text" id="J_name_'+ci['cname']+'" name="name_'+ci['cname']+'" value="'+_data['name_'+ci['cname']]+'">';
				}	
			}
			_html += 	'<em>'+arr_language['sort']+':</em>'+
						'<input type="text" id="J_sort" name="sort" value="'+_data['sort']+'">'+
						'<a id="J_btn_send" class="blue_nav">'+arr_language['send']+'</a>'+
						'<a id="J_btn_cancel" class="gray_nav">'+arr_language['cancel']+'</a>'+
					'</div>'+
				'</form>';
		return _html;
	}
}