var member_yaoqing = {
	_this:'',
	_init:function(){
		var c = member_yaoqing;
		c._listen_load();	
	},
	_listen_load:function(){
		var c = member_yaoqing;
		$('.J_zhankai').unbind('click');
		$('.J_zhankai').click(function(){
								var _this = $(this);
								c._this = _this;
								int_uid = _this.attr('data-uid');
								var obj_sub = $('#J_member_'+int_uid+'_sub');
								if(obj_sub.length==0){
									_this.html(arr_language['loading']);
									_ajax_jsonp('/i/get_member_list?uid='+int_uid,c._load_success);	
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
		var c = member_yaoqing;
		var _this = c._this;
		int_uid = _this.attr('data-uid');
		_this.html(arr_language['shouqi']);
		var _html = '<h2 id="J_member_'+int_uid+'_sub">';
                    	
		if(_data['data']['total']==0){
			_html += '<span>'+arr_language['nothing']+'</span>';
		}
		else{
			for(var i=0,ci;ci=_data['data']['list'][i++];){
				_html += '<span>'+ci['username']+'<em>'+ci['in_date']+'</em></span>';
			}	
		}
		_html += '</h2>';
		$(_html).insertAfter(_this.parent().parent());
	}
}