var admin_page = {
	_init:function(){
		admin_page._listen();	
		admin_page._listen_cat();
	},
	_listen:function(){
		$('#J_send_form').submit(function(){
							var obj = $('#J_language');
							if(obj.length>0){
								if(obj.val()<=0){
									make_alert_html(arr_language['warn'],arr_language['error_2']);
									return false;
								}
							}
							var obj_page_id = $('#J_page_id');
							if(obj_page_id.val()<=0){
								make_alert_html(arr_language['warn'],arr_language['page']['error_2']);
								return false;	
							}
							
							var obj_page_id_2 = $('#J_page_id_2');
							if(obj_page_id_2.css('display')!='none' && obj_page_id_2.val()<=0){
								make_alert_html(arr_language['warn'],arr_language['page']['error_2']);
								return false;	
							}
							
							var obj_page_id_3 = $('#J_page_id_3');
							if(obj_page_id_3.css('display')!='none' && obj_page_id_3.val()<=0){
								make_alert_html(arr_language['warn'],arr_language['page']['error_2']);
								return false;	
							}
							if(!ue.getContent()){
								make_alert_html(arr_language['warn'],arr_language['page']['error_1']);
								return false;
							}
							
							if(admin_page.bool_sending){
								return false;	
							}
							admin_page.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),admin_page.send_success,'send_success');			  
						});
	},
	_listen_cat:function(){
		var c = admin_page;
		var obj_page_id = $('#J_page_id');
		obj_page_id.change(function(){
						var _this = $(this);
						var int_pid = _this.val();
						$('#J_page_id_2').css('display','none');
						$('#J_page_id_3').css('display','none');
						c._cat_select(int_pid);
					});
		if(obj_page_id.attr('data-page_id')>0){
			var int_pid = obj_page_id.attr('data-page_id');
			var obj_options = obj_page_id.children();
			for(var i=0;i<obj_options.length;i++){
				var ci = obj_options.eq(i);
				if(ci.val()==int_pid){
					ci.attr('selected',true);
					break;
				}
			}
			c._cat_select(obj_page_id.attr('data-page_id'));	
		}		
	},
	_listen_cat_2:function(){
		var c = admin_page;
		var obj_page_id = $('#J_page_id_2');
		obj_page_id.unbind('change');
		obj_page_id.change(function(){
						var _this = $(this);
						var int_pid = _this.val();
						c._cat_select_2(int_pid);
					});
		if(obj_page_id.attr('data-page_id')>0){
			var int_pid = obj_page_id.attr('data-page_id');
			var obj_options = obj_page_id.children();
			for(var i=0;i<obj_options.length;i++){
				var ci = obj_options.eq(i);
				if(ci.val()==int_pid){
					ci.attr('selected',true);
					break;
				}
			}
			c._cat_select_2(obj_page_id.attr('data-page_id'));	
		}		
	},
	_cat_select:function(int_pid){
		var _html = '';
		var c = admin_page;
		if(int_pid>0){
			var int_page_id_2 = $('#J_page_id_2').attr('data-page_id');
			for(var ci,i=0;ci=c.json_cat[i++];){
				if(ci['pid']==int_pid){
					var str_selected = int_page_id_2==ci['page_id'] ? ' selected="selected"' : '';
					_html += '<option value="'+ci['page_id']+'"'+str_selected+'>'+ci['name']+'</option>';
				}
			}
		}
		if(_html){
			_html = '<option value="0">'+arr_language['select']+'</option>'+_html;
			$('#J_page_id_2').css('display','');
		}
		else{
			$('#J_page_id_2').css('display','none');	
		}
		$('#J_page_id_2').html(_html);
		c._listen_cat_2();
	},
	_cat_select_2:function(int_pid){
		var _html = '';
		var c = admin_page;
		if(int_pid>0){
			var int_page_id_3 = $('#J_page_id_3').attr('data-page_id');
			for(var ci,i=0;ci=c.json_cat[i++];){
				if(ci['pid']==int_pid){
					var str_selected = int_page_id_3==ci['page_id'] ? ' selected="selected"' : '';
					_html += '<option value="'+ci['page_id']+'"'+str_selected+'>'+ci['name']+'</option>';
				}
			}
		}

		if(_html){
			_html = '<option value="0">'+arr_language['select']+'</option>'+_html;
			$('#J_page_id_3').css('display','');
		}
		else{
			$('#J_page_id_3').css('display','none');	
		}
		$('#J_page_id_3').html(_html);
	},
	_listen_del:function(){
		$('.J_btn_del').click(function(){
								var _this = $(this);
								confirm_cancle(admin_page._del,arr_language['warn'],arr_language['del_2']);
								admin_page._this = _this;
							});
	},
	_del:function(){
		var _this = admin_page._this;
		var _data = new Object();
		_data.page_id = _this.attr('data-page_id');
		_data.language = _this.attr('data-language');
		_ajax_jsonp('/admin.php?mod=page&extra=del',admin_page._del_success,_data);
	},
	_del_success:function(_data){
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);	
	},
	send_success:function(_data){
		_remove_ajax_frame('send_success');
		//console.log(_data);
		if(_data['status']!=200){
			admin_page.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}
		make_alert_html(arr_language['notice'],_data['info'],'/admin.php?mod=page');
	}
}

var admin_page_search = {
	_init:function(){
		admin_page_search._listen_cat();	
	},
	_listen_cat:function(){
		var obj_language = $('#J_language');
		obj_language.change(function(){
						var _this = $(this);
						var str_language = _this.val();
						var str_url = window.location.href.replace(/\&language=[a-z_]+/,'');
						window.location.href = str_url+'&language='+str_language;
					});	
	}
}