var district = {
	_init:function(obj_province,obj_city,obj_area){
		var c = district;
		typeof(c.obj_province)!='undefined' && c.obj_province.unbind('change');
		c.obj_province = $('#'+obj_province);
		c.obj_city = $('#'+obj_city);
		c.obj_area = $('#'+obj_area);
		c._listen_province();
	},
	_listen_province:function(){
		var c = district;
		c.curr_loading = 'province';
		c._load(0);
		c.obj_province.change(function(){
							if(typeof(c.obj_city)=='undefined'){
								return false;	
							}
							var _this = $(this);
							var int_pid = _this.val();
							if(int_pid==0){
								c.obj_city.css('display','none');
								return false;
							}
							c.obj_area.css('display','none');
							c.obj_area.html('');
							c._load(int_pid);
							c.curr_loading = 'city';
						});
	},
	_listen_city:function(){
		var c = district;
		c.obj_city.change(function(){
							if(typeof(c.obj_area)=='undefined'){
								return false;	
							}
							var _this = $(this);
							var int_pid = _this.val();
							if(int_pid==0){
								c.obj_city.css('display','none');
								return false;
							}
							c._load(int_pid);
							c.curr_loading = 'area';
						});	
	},
	_load:function(int_pid){
		var c = district;
		_ajax_jsonp('/?mod=district&extra=get_data&pid='+int_pid,c._load_success);
	},
	_load_success:function(_data){
		var c = district;
		var _html = '<option value="0">'+arr_language['select']+'</option>';
		var int_selected_id = 0;
		if(c.curr_loading=='province'){
			int_selected_id = c.obj_province.attr('data-province');
			c.obj_province.removeAttr('data-province');
		}
		else if(c.curr_loading=='city'){
			int_selected_id = c.obj_city.attr('data-city');
			c.obj_city.removeAttr('data-city');
		}
		else if(c.curr_loading=='area'){
			int_selected_id = c.obj_area.attr('data-area');
			c.obj_area.removeAttr('data-area');
		}
		var str_selected = '';
		for(var i=0,ci;ci=_data.data[i++];){
			str_selected = '';
			if(ci['id']==int_selected_id){
				str_selected = ' selected="selected"';
			}
			_html += '<option value="'+ci['id']+'"'+str_selected+'>'+ci['name']+'</option>';
		
		}
		var obj = '';
		var str_load_type = '';
		if(c.curr_loading=='province'){
			obj = c.obj_province;
			c.obj_city.css('display','none');
			c.obj_area.css('display','none');
			obj.html(_html);
			if(typeof(c.obj_city)!='undefined'){
				str_load_type = 'city';
			}
			
		}
		else if(c.curr_loading=='city'){
			obj = c.obj_city;
			obj.css('display','');
			obj.html(_html);
			c._listen_city();
			if(typeof(c.obj_area)!='undefined'){
				str_load_type = 'area'
			}
		}
		else if(c.curr_loading=='area'){
			obj = c.obj_area;
			obj.css('display','');
			obj.html(_html);
		}
		
		if(int_selected_id>0 && str_load_type){
			c.curr_loading = str_load_type;
			c._load(int_selected_id);	
		}
		
	}
};