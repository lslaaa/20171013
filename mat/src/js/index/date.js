var _date = {
	_init:function(obj_year,obj_month,obj_day){
		var c = _date;
		typeof(c.obj_year)!='undefined' && c.obj_year.unbind('change');
		c.obj_year = $('#'+obj_year);
		c.obj_month = $('#'+obj_month);
		c.obj_day = $('#'+obj_day);
		c._listen_year();
	},
	_listen_year:function(){
		var c = _date;
		c.curr_loading = 'year';
		c._load();
		c.obj_year.change(function(){
							if(typeof(c.obj_month)=='undefined'){
								return false;	
							}
							var _this = $(this);
							c.obj_day.css('display','none');
							c.curr_loading = 'month';
							c._load();
						});
	},
	_listen_month:function(){
		var c = _date;
		c.obj_month.change(function(){
							if(typeof(c.obj_day)=='undefined'){
								return false;	
							}
							var _this = $(this);
							if(_this.val()<=0){
								c.obj_day.css('display','none');
								return false;
							}
							c.curr_loading = 'day';
							c._load();
							
						});	
	},
	_load:function(){
		var c = _date;
		if(c.curr_loading=='year'){
			var obj_date = new Date();
			c.curr_min = 1950;
			c.curr_max = obj_date.getFullYear();
		}
		else if(c.curr_loading=='month'){
			c.curr_min = 1;
			c.curr_max = 12;	
		}
		else{
			c.curr_min = 1;
			var  arr_month_day = new Array();
			arr_month_day[0] = 31;
			arr_month_day[1] = 0;
			arr_month_day[2] = 31;
			arr_month_day[3] = 30;
			arr_month_day[4] = 31;
			arr_month_day[5] = 30;
			arr_month_day[6] = 31;
			arr_month_day[7] = 31;
			arr_month_day[8] = 30;
			arr_month_day[9] = 31;
			arr_month_day[10] = 30;
			arr_month_day[11] = 31;
			var now_month_day;
			now_month_day = arr_month_day[parseInt(c.obj_month.val())-1];//当前点击的月所对应的天数
			var int_year = parseInt(c.obj_year.val());
			if(now_month_day==0){
				now_month_day = ((int_year%4==0 && int_year%100!=0)||(int_year%100==0 && int_year%400==0))? 29 : 28;//闰年的2月只有29天 平年28天
			}
			c.curr_max = now_month_day;	
		}
		c._load_success();
		
	},
	_load_success:function(){
		var c = _date;
		var _html = '<option value="0">'+arr_language['select']+'</option>';
		var int_selected_id = 0;
		if(c.curr_loading=='year'){
			int_selected_id = c.obj_year.attr('data-year');
		}
		else if(c.curr_loading=='month'){
			int_selected_id = c.obj_month.attr('data-month');
		}
		else if(c.curr_loading=='day'){
			int_selected_id = c.obj_day.attr('data-day');
		}
		
		var str_selected = '';
		for(var i=c.curr_min;i<=c.curr_max;i++){
			str_selected = '';
			if(i==int_selected_id){
				str_selected = ' selected="selected"';
			}
			_html += '<option value="'+i+'"'+str_selected+'>'+i+'</option>';
		
		}

		var obj = '';
		var str_load_type = '';
		if(c.curr_loading=='year'){
			obj = c.obj_year;
			c.obj_month.css('display','none');
			c.obj_day.css('display','none');
			obj.html(_html);
			if(typeof(c.obj_month)!='undefined'){
				str_load_type = 'month';
			}
			
		}
		else if(c.curr_loading=='month'){
			obj = c.obj_month;
			obj.css('display','');
			obj.html(_html);
			c._listen_month();
			if(typeof(c.obj_day)!='undefined'){
				str_load_type = 'day'
			}
		}
		else if(c.curr_loading=='day'){
			obj = c.obj_day;
			obj.css('display','');
			obj.html(_html);
		}
		
		if(int_selected_id>0 && str_load_type){
			c.curr_loading = str_load_type;
			c._load();	
		}
		
	}
};