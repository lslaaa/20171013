var index_banner = {
	_index:0,
	next_index:0,
	is_begin: false,
	is_touch: false,
	is_move: false,
	is_end: true,
	auto_time:5000,
	auto_dick:'',
	_init:function(){
		var c = index_banner;
		
		$('.J_banner:gt(0)').css({
								opacity:0,
								display:''
							});
		
		var int_banner_height = $('.J_banner').eq(0).height();
		// alert(_height)
		$('.J_banner').parent().css({
									height:int_banner_height+'px'	
								});
		$('.J_banner a').css({
									height:int_banner_height+'px'	
								});
		$('.J_banner').css({
						position:'absolute',
						left:0,
						top:0,
						transition:'all 0.5s ease',
						'-moz-transition': 'all 0.5s ease',
						'-webkit-transition':'all 0.5s ease',
						'-o-transition': 'all 0.5s ease'	   
					});
		
		c._listen();
		c._listen_mobile();
		c.start_auto_move();
	},
	_listen_mobile:function(){
		var c = index_banner;
		var obj_pic_container = get_obj('J_hot_pic');
		obj_pic_container.addEventListener('touchmove', c.touch_move, false);
		obj_pic_container.addEventListener('touchend', c.touch_end, false);
		obj_pic_container.addEventListener('touchstart', c.touch_start, false);	
	},
	_listen:function(){
		var c = index_banner;
		/*
		$('#J_banner_prev').click(function(){
								c.next_index = c._index - 1;
								c._move();
							});
		$('#J_banner_next').click(function(){
								c.next_index = c._index + 1;
								c._move();
							});
		*/
		$('#J_hot_pic').mouseover(function(){
									clearTimeout(c.auto_dick);   
								}).mouseout(function(){
									c.start_auto_move();	
								});
		$('#J_banner_dot a').click(function(){
								c.next_index = $('#J_banner_dot a').index(this);
								c._move();
							});
		
	},
	touch_start: function (e) {
		//console.log('touch start');
		var c = index_banner;
		e = e ? e : event;
		if (!c.is_end) {
			return;
		}
		c.is_begin = true;
		if (e.targetTouches) {
			c.is_touch = true;
			c.start_x = e.targetTouches[0].clientX;
			c.start_y = e.targetTouches[0].clientY;
		} else {
			c.start_x = e.clientX;
			c.start_y = e.clientY;
		}
		
	},
	touch_end: function (e) {
		var c = index_banner;
		e = e ? e : event;
		var move_pos = {
			x: 0,
			y:0
		};

		if (!c.is_begin || !c.is_move) {
			c.is_begin = false;
			c.is_move = false;
			c.is_end = true;
			delete c.horizontal;
			return;
		}
		c.is_end = true;
		c.is_begin = false;
		c.is_move = false;
		delete c.horizontal;
		if(c.dx < 0){
			c.next_index = c._index + 1;
		}
		else{
			c.next_index = c._index - 1;	
		}
		
		c._move();
	},
	touch_move: function (e) {
		var c = index_banner;
		e = e ? e : event;
		var move_pos = {
			x: 0,
			y:0
		};
		if (!c.is_begin) {
			return;
		}
		c.is_move = true;
		var temp_x, temp_y;
		if (c.is_touch) {
			temp_x = e.targetTouches[0].clientX;
			temp_y = e.targetTouches[0].clientY;
		} else {
			temp_x = e.clientX;
			temp_y = e.clientY;
		}
		c.dx = temp_x - c.start_x;
		c.dy = temp_y - c.start_y;
		c.start_x = temp_x;
		c.start_y = temp_y;
		c.x += c.dx;
		c.y += c.dy;
		if (c.horizontal === undefined) {
			if (Math.abs(c.dy) < Math.abs(c.dx)) {
				c.horizontal = true;
			} else {
				c.horizontal = false;
			}
		}
		//console.log(c.horizontal);
		if (c.horizontal) {
			e.preventDefault();
			move_pos.x = c.x;
			move_pos.y = c.y;
			
		} else {
			c.is_begin = false;
			c.is_move = false;
			c.is_end = true;
			delete c.horizontal;
			return;
		}
	},
	_move:function(){
		var c = index_banner;
		if(c.next_index<0){
			c.next_index = $('.J_banner').length-1;
		}
		else if(c.next_index>($('.J_banner').length-1)){
			c.next_index = 0;	
		}
		var obj = $('.J_banner');
		obj.eq(c._index).css({
							 'zIndex':1,
							 opacity:0
							});
		obj.eq(c.next_index).css({
							 'zIndex':2,
							 opacity:1
							});
		var int_banner_height = $('.J_banner').eq(0).height();

		// $('.J_banner').parent().css({
		// 							height:int_banner_height+'px'	
		// 						});
		$('.J_banner').css({
						position:'absolute',
						left:0,
						top:0,
						transition:'all 0.5s ease',

						'-moz-transition': 'all 0.5s ease',
						'-webkit-transition':'all 0.5s ease',
						'-o-transition': 'all 0.5s ease'	   
					});
		var obj_dot = $('#J_banner_dot a');
		obj_dot.eq(c._index).removeClass('on');
		obj_dot.eq(c.next_index).addClass('on');
		c._index = c.next_index;
	},
	start_auto_move:function(){
		var c = index_banner;
		c.auto_dick = setTimeout(function(){
						c.auto_move();	
					},c.auto_time);	
	},
	auto_move:function(){
		var c = index_banner;
		c.next_index++;
		c._move(c.next_index);
		c.start_auto_move();
	},
        
        
        _list_case_auto_scroll:function(){
            if(!$('#J_is_mobile_brower').val()){
                var c = index_banner;
                //两秒后调用
                var _a = setInterval(c.case_auto_scroll,3000);
                $("#J_slide>ul").hover(function(){
                        //鼠标移动DIV上停止                    
                        clearInterval(_a);
                }).mouseleave(function(){
                        _a = setInterval(c.case_auto_scroll,3000);
                });
            }            
        },
        case_auto_scroll:function(){
            var _scroll = $("#J_slide>ul");
            //ul往左边移动250px
            _scroll.animate({marginLeft:"-244px"},1500,function(){
                    //把第一个li丢最后面去
                    _scroll.css({marginLeft:0}).find("li:first").appendTo(_scroll);
            });
        }
        
}
window.onresize = function(){
	var int_banner_height = $('.J_banner').eq(0).height();
	//console.log(int_banner_height);
	//if($(window).width()>=1024){
		// $('.J_banner').parent().css({
		// 							height:int_banner_height+'px'	
		// 						});
	//}
	//else{
		//$('.J_banner').parent().css({
			///						height:''	
				//				});	
	//}
}

var ipad_scrollable_a = {
	_index:0,
	next_index:0,
	is_begin: false,
	is_touch: false,
	is_move: false,
	is_end: true,
	auto_time:5000,
	auto_dick:'',
	_init:function(obj,int_width){
		var c = ipad_scrollable_a;
		c.width = int_width;
		c.obj = obj;
		c._listen_mobile();
	},
	_listen_mobile:function(){
		var c = ipad_scrollable_a;
		var obj_pic_container = get_obj(c.obj);
		obj_pic_container.addEventListener('touchmove', c.touch_move, false);
		obj_pic_container.addEventListener('touchend', c.touch_end, false);
		obj_pic_container.addEventListener('touchstart', c.touch_start, false);	
	},
	touch_start: function (e) {
		//console.log('touch start');
		var c = ipad_scrollable_a;

		e = e ? e : event;
		if (!c.is_end) {
			return;
		}
		c.is_begin = true;
		if (e.targetTouches) {
			c.is_touch = true;
			c.start_x = e.targetTouches[0].clientX;
			c.start_y = e.targetTouches[0].clientY;
		} else {
			c.start_x = e.clientX;
			c.start_y = e.clientY;
		}
		
	},
	touch_end: function (e) {
		var c = ipad_scrollable_a;
		e = e ? e : event;
		var move_pos = {
			x: 0,
			y:0
		};

		if (!c.is_begin || !c.is_move) {
			c.is_begin = false;
			c.is_move = false;
			c.is_end = true;
			delete c.horizontal;
			return;
		}
		c.is_end = true;
		c.is_begin = false;
		c.is_move = false;
		delete c.horizontal;
		if(c.dx < 0){
			c._index += 1;
		}
		else{
			c._index -= 1;	
		}
		
		c._move();
	},
	touch_move: function (e) {
		var c = ipad_scrollable_a;
		e = e ? e : event;
		var move_pos = {
			x: 0,
			y:0
		};
		if (!c.is_begin) {
			return;
		}
		c.is_move = true;
		var temp_x, temp_y;
		if (c.is_touch) {
			temp_x = e.targetTouches[0].clientX;
			temp_y = e.targetTouches[0].clientY;
		} else {
			temp_x = e.clientX;
			temp_y = e.clientY;
		}
		c.dx = temp_x - c.start_x;
		c.dy = temp_y - c.start_y;
		c.start_x = temp_x;
		c.start_y = temp_y;
		c.x += c.dx;
		c.y += c.dy;
		if (c.horizontal === undefined) {
			if (Math.abs(c.dy) < Math.abs(c.dx)) {
				c.horizontal = true;
			} else {
				c.horizontal = false;
			}
		}
		//console.log(c.horizontal);
		if (c.horizontal) {
			e.preventDefault();
			move_pos.x = c.x;
			move_pos.y = c.y;
			
		} else {
			c.is_begin = false;
			c.is_move = false;
			c.is_end = true;
			delete c.horizontal;
			return;
		}
	},
	_move:function(){
		var c = ipad_scrollable_a;
		var _index = c._index;

		var obj = $('#'+c.obj).children('.J_scroll_data');
		var length = obj.children().length;
		if(_index>length-1){
			_index = length-1;
		}
		else if(_index<0){
			_index = 0;
		}
		c.x = -c.width*_index;
		obj.css({
				transform:'translate3d('+c.x+'px,0px,0px)',
				'-ms-transform':'translate3d('+c.x+'px,0px,0px)',
				'-moz-transform':'translate3d('+c.x+'px,0px,0px)',
				'-webkit-transform':'translate3d('+c.x+'px,0px,0px)',
				'-o-transform':'translate3d('+c.x+'px,0px,0px)',
				 transition:'all 0.3s ease',
				'-moz-transition': 'all 0.3s ease',
				'-webkit-transition':'all 0.3s ease',
				'-o-transition': 'all 0.3s ease' 
			});
		c._index = _index;
	}
};

var ipad_scrollable_b = {
	_index:0,
	next_index:0,
	is_begin: false,
	is_touch: false,
	is_move: false,
	is_end: true,
	auto_time:5000,
	auto_dick:'',
	_init:function(obj,int_width){
		var c = ipad_scrollable_b;
		c.width = int_width;
		c.obj = obj;
		c._listen_mobile();
	},
	_listen_mobile:function(){
		var c = ipad_scrollable_b;
		var obj_pic_container = get_obj(c.obj);
		obj_pic_container.addEventListener('touchmove', c.touch_move, false);
		obj_pic_container.addEventListener('touchend', c.touch_end, false);
		obj_pic_container.addEventListener('touchstart', c.touch_start, false);	
	},
	touch_start: function (e) {
		//console.log('touch start');
		var c = ipad_scrollable_b;
		e = e ? e : event;
		if (!c.is_end) {
			return;
		}
		c.is_begin = true;
		if (e.targetTouches) {
			c.is_touch = true;
			c.start_x = e.targetTouches[0].clientX;
			c.start_y = e.targetTouches[0].clientY;
		} else {
			c.start_x = e.clientX;
			c.start_y = e.clientY;
		}
		
	},
	touch_end: function (e) {
		var c = ipad_scrollable_b;
		e = e ? e : event;
		var move_pos = {
			x: 0,
			y:0
		};

		if (!c.is_begin || !c.is_move) {
			c.is_begin = false;
			c.is_move = false;
			c.is_end = true;
			delete c.horizontal;
			return;
		}
		c.is_end = true;
		c.is_begin = false;
		c.is_move = false;
		delete c.horizontal;
		if(c.dx < 0){
			c._index += 1;
		}
		else{
			c._index -= 1;	
		}
		
		c._move();
	},
	touch_move: function (e) {
		var c = ipad_scrollable_b;
		e = e ? e : event;
		var move_pos = {
			x: 0,
			y:0
		};
		if (!c.is_begin) {
			return;
		}
		c.is_move = true;
		var temp_x, temp_y;
		if (c.is_touch) {
			temp_x = e.targetTouches[0].clientX;
			temp_y = e.targetTouches[0].clientY;
		} else {
			temp_x = e.clientX;
			temp_y = e.clientY;
		}
		c.dx = temp_x - c.start_x;
		c.dy = temp_y - c.start_y;
		c.start_x = temp_x;
		c.start_y = temp_y;
		c.x += c.dx;
		c.y += c.dy;
		if (c.horizontal === undefined) {
			if (Math.abs(c.dy) < Math.abs(c.dx)) {
				c.horizontal = true;
			} else {
				c.horizontal = false;
			}
		}
		//console.log(c.horizontal);
		if (c.horizontal) {
			e.preventDefault();
			move_pos.x = c.x;
			move_pos.y = c.y;
			
		} else {
			c.is_begin = false;
			c.is_move = false;
			c.is_end = true;
			delete c.horizontal;
			return;
		}
	},
	_move:function(){
		var c = ipad_scrollable_b;
		var _index = c._index;

		var obj = $('#'+c.obj).children('.J_scroll_data');
		var length = obj.children().length;
		if(_index>length-1){
			_index = length-1;
		}
		else if(_index<0){
			_index = 0;
		}
		//console.log(_index)
		c.x = -c.width*_index;
		obj.css({
				transform:'translate3d('+c.x+'px,0px,0px)',
				'-ms-transform':'translate3d('+c.x+'px,0px,0px)',
				'-moz-transform':'translate3d('+c.x+'px,0px,0px)',
				'-webkit-transform':'translate3d('+c.x+'px,0px,0px)',
				'-o-transform':'translate3d('+c.x+'px,0px,0px)',
				 transition:'all 0.3s ease',
				'-moz-transition': 'all 0.3s ease',
				'-webkit-transition':'all 0.3s ease',
				'-o-transition': 'all 0.3s ease' 
			});
		c._index = _index;
	}
};