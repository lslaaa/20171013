			
var menu = {
	_this:'',
	_init:function(){
		$('#J_add_level_one').click(function(){
								var _this = $(this);
								var _data = new Object;				 
								_data.pid = 0;
								_data._do = 'add';
								_data.mid = 0;
								_data.name = '';
								_data.cname = '';
								_data.url  = '';
								_data['sort'] = 200;
								var _html = menu.make_html(_data);
								_this.parent().next('.J_submit_from').html(_html);
								menu.listen_send();
							});
		$('.J_show_hide_menu_add').click(function(){
								$('.J_submit_from').html('');
								var _this = $(this);
								var obj_data = _this.parent().parent();
								var _data = new Object;				 
								_data.pid = obj_data.attr('data-mid');
								_data._do = 'add';
								_data.mid = 0;
								_data.name = '';
								_data.cname = '';
								
								_data.url  = '';
								_data['sort'] = 200;
								var _html = menu.make_html(_data);
								_this.parent().next('.J_submit_from').html(_html);
								menu.listen_send();					  
							});
		$('.J_show_hide_menu_mod').click(function(){
								$('.J_submit_from').html('');
								var _this = $(this);
								var obj_data = _this.parent().parent();
								var int_mid = obj_data.attr('data-mid');
								_ajax_jsonp('/admin.php?mod=menu&extra=get_one&mod_dir=setting&mid='+int_mid,menu._mod);
								menu._this = _this;
							});
		$('.J_show_hide_menu_del').click(function(){
								$('.J_submit_from').html('');
								var _this = $(this);
								confirm_cancle(menu._del,arr_language['warn'],arr_language['del_2']);
								menu._this = _this;
							});
	},
	listen_send:function(){
		$('#J_btn_send').unbind('click');
		$('#J_btn_cancel').unbind('click');
		$('#J_btn_send').click(function(){
							menu.verify();		
						});
		$('#J_btn_cancel').click(function(){
							$('.J_submit_from').html('');
						});
	},
	_mod:function(_data){
		//console.log(_data);
		var _this = menu._this;
		var _data = _data['data'];
		_data._do = 'mod';
		var _html = menu.make_html(_data);
		_this.parent().next('.J_submit_from').html(_html);
		menu.listen_send();	
	},
	_del:function(){
		var _this = menu._this;
		var obj_data = _this.parent().parent();
		var int_mid = obj_data.attr('data-mid');
		_ajax_jsonp('/admin.php?mod=menu&extra=del&mod_dir=setting&mid='+int_mid,menu._del_success);
	},
	_del_success:function(_data){
		if(_data['status']==200){
			window.location.href = window.location.href;
		}
	},
	verify:function(){
		var obj_name = $('#J_name');
		if(!obj_name.val()){
			make_alert_html(arr_language['warn'],arr_language['setting']['menu']['error_1']);
			return false;
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
		
		if(menu.bool_sending){
			return false;	
		}
		_ajax_frame(get_obj('ajaxframeid'),menu.send_success,'send_success');
		$('#J_send_form').submit();
		menu.bool_sending = true;
	},
	send_success:function(_data){
		_remove_ajax_frame('send_success');
		//console.log(_data);
		if(_data['status']!=200){
			menu.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}	
		window.location.href = window.location.href;	
	},
	make_html:function(_data){
		var c = menu;
		var _html = '<form action="" method="post" id="J_send_form" target="ajaxframeid">'+
					'<input type="hidden" name="formhash" value="'+str_formhash+'"/>'+
					'<input type="hidden" id="J_pid" name="pid" value="'+_data.pid+'"/>'+
					'<input type="hidden" id="J_do" name="_do" value="'+_data._do+'"/>'+
					'<input type="hidden" id="J_id" name="mid" value="'+_data.mid+'"/>'+
					'<input class="J_callback" type="hidden" name="callback" value="parent.send_success">'+
					'<div class="add_box">'+
						'<em>'+arr_language['setting']['menu']['name']+':</em>'+
						'<input type="text" id="J_name" name="name" value="'+_data.name+'">';
		if(menu.show_cname){
			_html +=	'<em>'+arr_language['setting']['menu']['cname']+':</em>'+
						'<input type="text" id="J_name" name="cname" value="'+_data.cname+'">';
		}
			
			_html += 	'<em>'+arr_language['url']+':</em>'+
						'<input type="text" id="J_url" name="url" value="'+_data.url+'">'+
						'<em>'+arr_language['sort']+':</em>'+
						'<input type="text" id="J_sort" name="sort" value="'+_data['sort']+'">'+
						'<a id="J_btn_send" class="blue_nav">'+arr_language['send']+'</a>'+
						'<a id="J_btn_cancel" class="gray_nav">'+arr_language['cancel']+'</a>'+
					'</div>'+
				'</form>';
		return _html;
	}
}