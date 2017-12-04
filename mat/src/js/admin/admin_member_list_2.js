var admin_member_list_2 = {
	_this:'',
	_init:function(){
		var c = admin_member_list_2;
		c._listen_search();	
		c._listen_load();	
	},
	_listen_search:function(){
		$('#J_search').click(function(){
							var int_province = $('#J_province').val();
							var int_city = $('#J_city').val();
							var int_area = $('#J_area').val();
							window.location.href = '/admin.php?mod=member&mod_dir=member&extra=list_2&province='+int_province+'&city='+int_city+'&area='+int_area; 
						});
	},
	_listen_load:function(){
		var c = admin_member_list_2;
		$('.J_zhankai').unbind('click');
		$('.J_zhankai').click(function(){
								var _this = $(this);
								c._this = _this;
								int_uid = _this.attr('data-uid');
								var obj_sub = $('#J_member_'+int_uid+'_sub');
								if(obj_sub.length==0){
									_this.html(arr_language['loading']);
									_ajax_jsonp('/admin.php?mod=member&mod_dir=member&extra=get_member_list&uid='+int_uid,c._load_success);	
									return false;
								}
								if(obj_sub.css('display')=='none'){
									obj_sub.css('display','');
									_this.html(arr_language['shouqi']);
								}
								else{
									obj_sub.css('display','none');	
									_this.html(arr_language['zhankai']);
								}
									   
							});
	},
	_listen_load_2:function(){
		var c = admin_member_list_2;
		$('.J_zhankai_2').unbind('click');
		$('.J_zhankai_2').click(function(){
								var _this = $(this);
								c._this = _this;
								int_uid = _this.attr('data-uid');
								var obj_sub = $('#J_member_2_'+int_uid+'_sub');
								if(obj_sub.length==0){
									_this.html(arr_language['loading']);
									_ajax_jsonp('/admin.php?mod=member&mod_dir=member&extra=get_member_list&uid='+int_uid,c._load_success_2);	
									return false;
								}
								if(obj_sub.css('display')=='none'){
									obj_sub.css('display','');
									_this.html(arr_language['shouqi']);
								}
								else{
									obj_sub.css('display','none');	
									_this.html(arr_language['zhankai']);
								}
									   
							});	
	},
	_load_success:function(_data){
		var c = admin_member_list_2;
		var _this = c._this;
		int_uid = _this.attr('data-uid');
		_this.html(arr_language['shouqi']);
		var _html = '<div id="J_member_'+int_uid+'_sub">';
		if(_data['data']['total']==0){
			_html += '<h1><em>'+arr_language['nothing']+'</em></h1>';
		}
		else{
			for(var i=0,ci;ci=_data['data']['list'][i++];){
				_html += '<h1 id="J_member_2_'+ci['uid']+'">'+
							'<span><a class="J_zhankai_2 nav_run blue_nav" href="javascript:;" data-level="1" data-uid="'+ci['uid']+'">'+arr_language['zhankai']+'</a></span>'+
							'<em>'+ci['username']+'</em>'+
						'</h1>';
			}	
		}
		_html += '</div>';
		$(_html).insertAfter(_this.parent().parent());
		c._listen_load_2();
	},
	_load_success_2:function(_data){
		var c = admin_member_list_2;
		var _this = c._this;
		int_uid = _this.attr('data-uid');
		_this.html(arr_language['shouqi']);
		var _html = '<div id="J_member_2_'+int_uid+'_sub">';
		if(_data['data']['total']==0){
			_html += '<h2><em>'+arr_language['nothing']+'</em></h2>';
		}
		else{
			for(var i=0,ci;ci=_data['data']['list'][i++];){
				_html += '<h2 id="J_member_3_'+ci['uid']+'">'+
							'<em>'+ci['username']+'</em>'+
						'</h2>';
			}	
		}
		_html += '</div>';
		$(_html).insertAfter(_this.parent().parent());
		c._listen_load_2();
	}
}