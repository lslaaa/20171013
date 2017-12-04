var _ajax_frame = function(obj, func,callback) {
	obj || $('<div style="display:none;"><iframe name="ajaxframeid" id="ajaxframeid" loading="1"></iframe></div>').insertAfter("head");
	for (var _form = $("form"), i = 0; i < _form.length; i++) {
		var ci = _form.eq(i);
		0 == ci.find(".J_domain").length && ci.append('<input class="J_domain" type="hidden" name="domain" value="' + document.domain + '">');
		0 == ci.find(".J_callback").length && ci.append('<input class="J_callback" type="hidden" name="callback" value="' + callback + '">');
	}
	window[callback] = func;
}

var _remove_ajax_frame = function(callback) {
	try{
		delete window[callback];
	}catch(e){
			
	}
}

_ajax_post = function(_url,_data,fun){
	$.ajax({
		//提交数据的类型 POST GET
		type:"POST",
		url:_url,
		//提交的数据		            
		data:_data,
		cache:false,
		//返回数据的格式
		datatype: "json",             
		success:function(data){
			if(typeof(fun)=='undefined'){
				return false;	
			}
			var data = $.parseJSON(data);
			fun(data);
		},
		error:function(){
			make_alert_html("提示","网络繁忙请稍后再试");
		}
	 });
}


function _ajax_jsonp(str_url,callback,_data){
	if(typeof(_data)=='undefined'){
		_data = [];	
	}
	$.ajax({
	   dataType: "jsonp",
	   url: str_url,
	   data:_data,
	   success: function(_data){
		 callback(_data);
	   }
	});	
}
//遮罩层
function common_mask(str_color,float_opacity){
	if(typeof(str_color)=='undefined'){
		str_color = '#33393C';	
	}
	if(typeof(float_opacity)=='undefined'){
		float_opacity = 0.4;	
	}
	if($("#J_mask").length>0){
		$("#J_mask").remove();
	}
	var newMask = document.createElement("div");
	newMask.id = 'J_mask';
	newMask.style.position = "absolute";
	newMask.style.zIndex = "9999";
	_scrollWidth = Math.max(document.body.scrollWidth, document.documentElement.scrollWidth);
	_scrollHeight = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
	//_scrollWidth = document.body.clientWidth+document.body.scrollLeft
	//_scrollHeight= document.body.clientHeight+document.body.scrollTop //另一种方法
	newMask.style.width = _scrollWidth + "px";
	newMask.style.height = _scrollHeight + "px";
	newMask.style.top = "0px";
	newMask.style.left = "0px";
	newMask.style.background = str_color;
	newMask.style.filter = "alpha(opacity="+(10*float_opacity)+")";
	newMask.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=4,opacity="+(100*float_opacity)+")";//IE的不透明设置
	newMask.style.opacity = float_opacity;
	document.body.appendChild(newMask);
}

/*
公用提示框
title：提示框标题
content：提示框的提示内容
_url :传递过来的url地址
flg  :点击关闭按钮是否刷新页面 true 刷新 false不刷新
sure_content:传参，下面那个按钮的HTML，比如 是【确定】还是【点击认证】等等
*/
var make_alert_html =function(title,content,_url,flg,sure_content){
	var see_height = $(window).height();  //看到的页面的那部分的高度
	var see_width =   $(window).width();	
	var move_height = 30;//弹出框移动高度
	if($("#J_pop_wrap").length>0){
		$("#J_pop_wrap").remove();//先去除之前的弹出框
		$("#J_mask").remove();//先去除之前的遮罩层
	}
	var _sure_content = typeof(sure_content)=='undefined'?arr_language['sure']:sure_content;
	var str_width  = 'width:400px;';
	if(see_width<400){
		str_width = 'width:80%;';	
	}
	var _alert_html = //提示框代码
	'<div class="pop_wrap" id="J_pop_wrap" style="display:block;'+str_width+'height:auto;background-color:#fff;min-height:150px;z-index:999999;">'+
		'<div class="pop_title">'+
		'  <span class="f_l">'+title+'</span>'+
		'  <a href="javascript:void(0);" class="close f_r" id="J_close">'+arr_language['close']+'</a>'+
		'</div>'+
		'<div class="pop_main">'+
			'<div class="pop_content main_c f16" style="font-size:16px;">'+content+				
			'</div>'+
			'<div class="pop_nav align_c">'+
				'<a href="javascript:void(0);" class="nav_sure" id="J_nav_sure">'+_sure_content+'</a>'+				
			'</div>'+
		'</div>'+
	'</div>';
	common_mask();//调用公用遮罩层		
	
	$(_alert_html).appendTo($('body'));	//添加到body里面
	$("#J_nav_sure").click(function(){
		_sure();
	});
	$("body").focus();	
	$("html").keydown(function(e){
		var flag;
		var e = e||window.event;
		var keycode  =  e.keyCode||e.which; // 按键的keyCode
		if(keycode==13){
			$("html").unbind('keydown');
			_sure();
		}
	});	
	
	$("#J_close").click(function(){
		_close();
	});
	
	var _sure = function(){
		$("#J_pop_wrap").animate({'top': (str_top-move_height)+'px',opacity:'0'}, 200);
		setTimeout(function(){
			if(typeof(_url)=='undefined'||!_url){
				$("#J_pop_wrap").remove();
				$("#J_mask").remove();
			}else{
				$("#J_pop_wrap").remove();
				$("#J_mask").remove();
				window.location.href = _url;
			}	
		},200);
	}
	
	var _close = function(){
		$("#J_pop_wrap").animate({'top': (str_top-move_height)+'px',opacity:'0'}, 200);
		setTimeout(function(){
			if(typeof(flg)=='undefined'||flg ==''){
				$("html").unbind('keydown');
				$("#J_pop_wrap").remove();
				$("#J_mask").remove();
			}else if(flg){
				window.location.href = _url;
			}
		},200);
		/*if(typeof(_url)!='undefined' && _url){
			window.location.href = _url;	
		}	*/
	}
	var newDivHeight = $('#J_pop_wrap').css("height");//弹出框本身的高
	var newDivWidth = $('#J_pop_wrap').css("width");	
	

	var str_left = (see_width -parseInt(newDivWidth))/2;
	var str_top = (see_height -parseInt(newDivHeight))/2;
	var scrollTop = document.body.scrollTop || document.documentElement.scrollTop; //滚轮的高度
	
	if(_verify_browser()=='MSIE6'){//如果是IE6
		$("#J_pop_wrap").css({
			'position':'absolute',
			'top':(str_top-move_height+scrollTop)+'px',
			'left':str_left+'px',
			'zIndex':999999,
			opacity:0
		});
		$("#J_pop_wrap").animate({'top': (str_top+scrollTop)+'px',opacity:'1'}, 200);
	}else{//非IE6
		//alert("str_top:"+str_top+"move_height:"+move_height+"-------str_left:"+str_left);
		$("#J_pop_wrap").css({
			'position':'fixed',
			'top':(str_top-move_height)+'px',
			'left':str_left+'px',
			'zIndex':999999,
			opacity:0
		});
		//alert($("#J_pop_wrap").css("position")+$("#J_pop_wrap").css("top")+$("#J_pop_wrap").css("left"));
	  setTimeout(function(){
		$("#J_pop_wrap").animate({'top': str_top+'px',opacity:'1'}, 200);
	  },10);
	}
	
}

var confirm_cancle =function (fun1,title,content){
	var move_height = 30;//弹出框移动高度	
	if(typeof content=="undefined"){
		content="";
	}
	bool_confirm_cancle = true;
	var see_height = $(window).height();  //看到的页面的那部分的高度
	var see_width =   $(window).width();
	var str_width  = 'width:400px;';
	if(see_width<400){
		str_width = 'width:100%;';	
	}
	var _html = 
		'<div class="pop_wrap" id="J_cc_div" style="left:800px;top:350px;display:block;'+str_width+'height:auto;background-color:#fff;min-height:150px;position:fixed !important;z-index:999999;">'+
			'<div class="pop_title">'+
				'<span class="f_l">'+title+'</span>'+
				'<a href="javascript:void(0);" class="close f_r" id="J_close_cc">关闭</a>'+
			'</div>'+
			'<div class="pop_main">'+
				'<div class="pop_content main_c f16" id="J_content_cc">'+content+'</div>'+					
				'<div class="pop_nav align_c">'+
					'<a href="javascript:void(0);" class="nav_sure" id="J_sure_cc">'+arr_language['sure']+'</a>'+
					'<a href="javascript:void(0);" class="nav_none" id="J_cancel_cc">'+arr_language['cancel']+'</a>'+
				'</div>'+					
			'</div>'+
		'</div>';
	common_mask();//调用公用遮罩层
	$(_html).appendTo($('body'));		
	var newDivHeight = $('#J_cc_div').css("height");
	var newDivWidth = $('#J_cc_div').css("width");	
		
	var str_left = (see_width -parseInt(newDivWidth))/2;
	var str_top = (see_height -parseInt(newDivHeight))/2;
	var scrollTop = document.body.scrollTop || document.documentElement.scrollTop; //滚轮的高度
	if(str_top<move_height){
		str_top = move_height;
	}
	if(_verify_browser()=='MSIE6'){
		$("#J_cc_div").css('position','absolute');
		str_top = $(document).scrollTop()+str_top;//IE6不支持fixed属性，用absolute的时候需要加上滚轮的高度
	}
	else{
		$("#J_cc_div").css('position','fixed');
	}
	if(_verify_browser()=='MSIE6'){//如果是IE6
		$("#J_cc_div").css({
			'top':(str_top-move_height)+'px',
			'left':str_left+'px',
			'zIndex':999999,
			opacity:0
		});
		$("#J_cc_div").animate({'top': (str_top)+'px',opacity:'1'}, 200);
	}else{//非IE6
		$("#J_cc_div").css({
			'top':(str_top-move_height)+'px',
			'left':str_left+'px',
			'zIndex':999999,
			opacity:0
		});
		$("#J_cc_div").animate({'top': str_top+'px',opacity:'1'}, 200);
	}
	$("html").keydown(function(e){
		var flag;
		var e = e||window.event;
		var keycode  =  e.keyCode||e.which; // 按键的keyCode
		if(bool_confirm_cancle && keycode==13){	
			$("html").unbind('keydown');
			$("#J_cc_div").remove();
			$("#J_mask").remove();
			fun1();
			bool_confirm_cancle = false;
		}
	});	
	$("#J_close_cc,#J_cancel_cc").unbind("click");
	$("#J_close_cc,#J_cancel_cc").click(function(){
		$("#J_cc_div").remove();
		$("#J_mask").remove();
		bool_confirm_cancle = false;
		return false;
	});	
	$("#J_sure_cc").unbind("click");
	$("#J_sure_cc")	.click(function(){
		$("#J_cc_div").remove();
		$("#J_mask").remove();
		fun1();
		bool_confirm_cancle = false;
	});
	//document.onkeydown=keyDownSearch;  //绑定回车事件
}

var weui_alter = function(str_content,str_title,str_url){
	if(typeof(str_title)=='undefined'){
		str_title = '提示';	
	}
	var _html = '<div id="J_weui_alter"  style="" id="dialog2" class="weui_dialog_alert">'+
					'<div class="weui_mask"></div>'+
					'<div class="weui_dialog">'+
						'<div class="weui_dialog_hd"><strong class="weui_dialog_title">'+str_title+'</strong></div>'+
						'<div class="weui_dialog_bd">'+str_content+'</div>'+
						'<div class="weui_dialog_ft">'+
							'<a id="J_weui_alter_sure" class="weui_btn_dialog primary" href="javascript:;">确定</a>'+
						'</div>'+
					'</div>'+
				'</div>';
	$('body').append(_html);
	
	$('#J_weui_alter_sure').click(function(){
		if(typeof(str_url)!='undefined'){
			window.location.href = str_url;
		}
		$('#J_weui_alter_sure').unbind('click');
		$('#J_weui_alter').remove();
	});
}

var weui_confirm = function(str_content,callback,str_title){
	if(typeof(str_title)=='undefined'){
		str_title = '提示';	
	}
	var _html = '<div style="" id="J_weui_confirm" class="weui_dialog_confirm">'+
					'<div class="weui_mask"></div>'+
					'<div class="weui_dialog">'+
						'<div class="weui_dialog_hd"><strong class="weui_dialog_title">'+str_title+'</strong></div>'+
						'<div class="weui_dialog_bd">'+str_content+'</div>'+
						'<div class="weui_dialog_ft">'+
							'<a id="J_weui_confirm_cancel" class="weui_btn_dialog default" href="javascript:;">取消</a>'+
							'<a id="J_weui_confirm_sure" class="weui_btn_dialog primary" href="javascript:;">确定</a>'+
						'</div>'+
					'</div>'+
				'</div>';	
	$('body').append(_html);
	$('#J_weui_confirm_sure,#J_weui_confirm_cancel').click(function(){
		var _this = $(this);
		if(_this.attr('id')=='J_weui_confirm_sure'){
			callback();	
		}
		$('#J_weui_confirm_sure').unbind('click');
		$('#J_weui_confirm_cancel').unbind('click');
		$('#J_weui_confirm').remove();
	});
}

var weui_loading = function(str_content){
	if(typeof(str_content)=='undefined'){
		str_content = '数据加载中';	
	}
	var _html = '<div id="J_weui_loading" class="weui_loading_toast">'+
					'<div class="weui_mask_transparent"></div>'+
					'<div class="weui_toast">'+
						'<div class="weui_loading">'+
							'<div class="weui_loading_leaf weui_loading_leaf_0"></div>'+
							'<div class="weui_loading_leaf weui_loading_leaf_1"></div>'+
							'<div class="weui_loading_leaf weui_loading_leaf_2"></div>'+
							'<div class="weui_loading_leaf weui_loading_leaf_3"></div>'+
							'<div class="weui_loading_leaf weui_loading_leaf_4"></div>'+
							'<div class="weui_loading_leaf weui_loading_leaf_5"></div>'+
							'<div class="weui_loading_leaf weui_loading_leaf_6"></div>'+
							'<div class="weui_loading_leaf weui_loading_leaf_7"></div>'+
							'<div class="weui_loading_leaf weui_loading_leaf_8"></div>'+
							'<div class="weui_loading_leaf weui_loading_leaf_9"></div>'+
							'<div class="weui_loading_leaf weui_loading_leaf_10"></div>'+
							'<div class="weui_loading_leaf weui_loading_leaf_11"></div>'+
						'</div>'+
						'<p class="weui_toast_content">'+str_content+'</p>'+
					'</div>'+
				'</div>';
	$('body').append(_html);
}

var weui_loading_remove = function(){
	$('#J_weui_loading').remove();
}

var weui_success = function(str_content,int_timeout){
	if(typeof(str_content)=='undefined'){
		str_content = '已完成';	
	}
	if(typeof(int_timeout)=='undefined'){
		int_timeout = 2000;	
	}
	var _html = '<div id="J_weui_success">'+
					'<div class="weui_mask_transparent"></div>'+
					'<div class="weui_toast">'+
						'<i class="weui_icon_toast"></i>'+
						'<p class="weui_toast_content">'+str_content+'</p>'+
					'</div>'+
				'</div>';
	$('body').append(_html);
	setTimeout(function(){
		$('#J_weui_success').remove();
	},int_timeout);
}



function which_animation_event(){
	var t;
	var el = document.createElement('fakeelement_2');
	var arr_event = {
	  'animation':'animationend',
	  'OAnimation':'oAnimationEnd',
	  'MozAnimation':'mozAnimationEnd',
	  'WebkitAnimation':'webkitAnimationEnd'
	}
	for(t in arr_event){
		if( typeof(el.style[t]) != 'undefined' ){
			return arr_event[t];
		}
	}
}

function which_transition_event(){
	var t;
	var el = document.createElement('fakeelement_2');
	var arr_event = {
	  'transition':'transitionend',
	  'OTransition':'oTransitionEnd',
	  'MozTransition':'mozTransitionEnd',
	  'WebkitTransition':'webkitTransitionEnd'
	}

	for(t in arr_event){
		if( typeof(el.style[t]) != 'undefined' ){
			return arr_event[t];
		}
	}
}

var crop_img = function(url,w,h){
		if(url.split('.gif').length>1)  return url;
		if(!url || (!w && !h)) return false;
		var temp = url.substr(url.lastIndexOf('.'),4);
		var size = '';
		if(w>0&&h>0) size  = w+'x'+h;
		else{
			alert('请传入正确的宽和高');	
		}
		return url+'_crop_'+size+temp;
	}
var get_obj = function(obj){
        return document.getElementById(obj);
    }
	
var open_transition = {
		curr_scroll_top:new Array(),//当前显示窗口滚动条高度
		curr_style:new Array(),//风格
		curr_close_callback:new Array(),//关闭回调函数
		page_width:0,//页面可用高度
		page_height:0,//页面可用宽度	
		obj_close_obj_async_div_id:'',
		obj_open_obj_async_div_id:'',
		open_callback:'',
		dist_event_transition:'',
		listen_hashchange:false,
		current_do:0,//当前操作1为打开
		last_url:'',//最后一次拉开页面的url
		bool_return_do:false,
		bool_allow_page_scroll:false,//页面打开后是否允许滚动
		_init:function(){
			if(open_in_iframe && /(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)){
				open_transition.page_height = $(parent.window).height();
			}
			else{
				open_transition.page_height = document.documentElement.clientHeight;
			}
			open_transition.page_width  = document.documentElement.clientWidth;
			$(".J_async_link").unbind('click');
			$(".J_async_link").click(function(){
				var _this = $(this);  
				var str_url = _this.attr('href');
				var str_style = _this.attr('data-style');
				var str_callback = _this.attr('data-callback');
				var str_content_id = _this.attr('data-content');
				try{
					if(str_callback){
						str_callback = eval(str_callback);
					}
				}catch(e){
					alert('无法找到回调函数：'+str_callback);
					return false;
				}
				open_transition._make(str_url,str_style,str_callback,str_content_id);
				return false;
			});
			open_transition._listen_hashchange();
		},
		_listen_hashchange:function(){
			if(!open_transition.listen_hashchange && open_transition.bool_return_do){
				window.addEventListener("hashchange", function(){
											setTimeout(function(){
												if(open_transition.current_do==1){
													open_transition.current_do = 0;
													return false;
												}
												var obj_async_div_id = open_transition.last_url.replace(window.location.href,'').replace('#','');
												//console.log(obj_async_div_id);
												open_transition._close(obj_async_div_id);
												if(typeof(open_transition.curr_close_callback[obj_async_div_id])=='function'){
													open_transition.curr_close_callback[obj_async_div_id](obj_async_div_id);
												}
											},100);
										});
				open_transition.listen_hashchange = true;
			}	
		},
		_make:function(str_url,str_style,str_callback,str_content_id,bool_return_do,_close_callback,bool_allow_page_scroll){
			if(typeof(bool_allow_page_scroll)=='undefined'){
				bool_allow_page_scroll = false;	
			}
			open_transition.bool_allow_page_scroll = bool_allow_page_scroll;
			if(open_in_iframe && /(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)){
				open_transition.page_height = $(parent.window).height();
			}
			else{
				open_transition.page_height = document.documentElement.clientHeight;
			}
			open_transition.page_width  = document.documentElement.clientWidth;
			if(typeof(bool_return_do)!='undefined'){
				open_transition.bool_return_do = true;	
			}
			open_transition._listen_hashchange();
			open_transition.current_do = 1;
			if(typeof(str_content_id)!='undefined'){
				var obj_async_div_id = str_content_id;
				$("#"+obj_async_div_id).css('position','fixed');
			}
			else{
				var obj_async_div = $(".J_async_div");
				var obj_async_div_id = 'J_async_div_'+(obj_async_div.length);
				var _html = '<div class="J_async_div" id="'+obj_async_div_id+'" style="display:none;position:fixed;background:#fff; width:100%;"></div>';
				$(_html).appendTo("body");
			}
			
			var _this_content = $("#"+obj_async_div_id);
			if(open_in_iframe && /(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)){
				open_transition.curr_scroll_top[obj_async_div_id] = $(parent.window).scrollTop();
			}
			else{
				open_transition.curr_scroll_top[obj_async_div_id] = $("body").scrollTop();
			}
			open_transition.curr_style[obj_async_div_id] = str_style;
			open_transition.curr_close_callback[obj_async_div_id] = _close_callback;

			if(str_style=='bottom_to_top' && typeof(str_content_id)=='string'){//异步的在load之后再计算div实际高度
				//$("#"+obj_async_div_id).css({bottom:-_this_content.height()+'px',left:0,top:''});
				$("#"+obj_async_div_id).css({
					left:0,
					transform:'translate3d(0px,100%,0px)',
					'-ms-transform':'translate3d(0px,100%,0px)',
					'-moz-transform':'translate3d(0px,100%,0px)',
					'-webkit-transform':'translate3d(0px,100%,0px)',
					'-o-transform':'translate3d(0px,100%,0px)'
				});
				if(open_in_iframe && /(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)){
					$("#"+obj_async_div_id).css({
						bottom:($(window).height() - open_transition.curr_scroll_top[obj_async_div_id] - open_transition.page_height)+'px',
						position:'absolute'
					});
				}
			}
			else if(str_style=='top_to_bottom' && typeof(str_content_id)=='string'){//异步的在load之后再计算div实际高度
				//$("#"+obj_async_div_id).css({top:-_this_content.height()+'px',left:0,bottom:''});
				$("#"+obj_async_div_id).css({
					top:0,
					left:0,
					transform:'translate3d(0px,-100%,0px)',
					'-ms-transform':'translate3d(0px,-100%,0px)',
					'-moz-transform':'translate3d(0px,-100%,0px)',
					'-webkit-transform':'translate3d(0px,-100%,0px)',
					'-o-transform':'translate3d(0px,-100%,0px)'
				});
				if(open_in_iframe && /(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)){
					$("#"+obj_async_div_id).css({
						top:open_transition.curr_scroll_top[obj_async_div_id]+'px',
						position:'absolute'
					});
				}
			}
			else if(str_style=='left_to_right'){
				//$("#"+obj_async_div_id).css({left:-open_transition.page_width+'px',top:0});
				$("#"+obj_async_div_id).css({
					top:0,
					left:0,
					transform:'translate3d(-100%,0px,0px)',
					'-ms-transform':'translate3d(-100%,0px,0px)',
					'-moz-transform':'translate3d(-100%,0px,0px)',
					'-webkit-transform':'translate3d(-100%,0px,0px)',
					'-o-transform':'translate3d(-100%,0px,0px)'
				});
			}
			else if(str_style=='right_to_left'){
				//$("#"+obj_async_div_id).css({left:open_transition.page_width+'px',top:0});
				$("#"+obj_async_div_id).css({
					top:0,
					left:0,
					transform:'translate3d(100%,0px,0px)',
					'-ms-transform':'translate3d(100%,0px,0px)',
					'-moz-transform':'translate3d(100%,0px,0px)',
					'-webkit-transform':'translate3d(100%,0px,0px)',
					'-o-transform':'translate3d(100%,0px,0px)'
				});
				
			}
			$("#"+obj_async_div_id).css({
				transition:'all 0.3s ease',
				'-moz-transition': 'all 0.3s ease',
				'-webkit-transition':'all 0.3s ease',
				'-o-transition': 'all 0.3s ease'
			});

			setTimeout(function(){
							$("#"+obj_async_div_id).css('display','');	
						},20);
			if(typeof(str_content_id)!='undefined'){
				setTimeout(function(){
								open_transition._open(obj_async_div_id,str_style,str_callback);	
							},50);
				
			}
			else{
				open_transition._load(obj_async_div_id,str_url,str_style,str_callback);
			}
		},
		_load:function(obj_async_div_id,str_url,str_style,str_callback){//加载异步页面
			$.ajax({
				type : "GET",
				url : str_url,
				dataType :"html",
				success : function(data) {
					data = data.replace(/\n|\r/g, '');
					var str_async_content = data.match(/<!--\[async_content_start\]-->(.+?)<!--\[async_content_end\]-->/g);
					var _this_content = $("#"+obj_async_div_id);
					_this_content.html(str_async_content);
					if(str_style=='top_to_bottom'){
						//$("#"+obj_async_div_id).css({top:-_this_content.height()+'px',bottom:''});	
						$("#"+obj_async_div_id).css({
							transform:'translate3d(0px,0px,0px)',
							'-ms-transform':'translate3d(0px,0px,0px)',
							'-moz-transform':'translate3d(0px,0px,0px)',
							'-webkit-transform':'translate3d(0px,0px,0px)',
							'-o-transform':'translate3d(0px,0px,0px)'
						});
					}
					else if(str_style=='bottom_to_top'){
						//$("#"+obj_async_div_id).css({bottom:-_this_content.height()+'px',top:''});
						$("#"+obj_async_div_id).css({
							transform:'translate3d(0px,0px,0px)',
							'-ms-transform':'translate3d(0px,0px,0px)',
							'-moz-transform':'translate3d(0px,0px,0px)',
							'-webkit-transform':'translate3d(0px,0px,0px)',
							'-o-transform':'translate3d(0px,0px,0px)'
						});
					}
					setTimeout(function(){
									open_transition._open(obj_async_div_id,str_style,str_callback);
								},50);
					
				},
				error : function() {
					alert('失败');
				}
			}); 
			//alert(str_url);
		},
		_open:function(obj_async_div_id,str_style,str_callback){//打开隐藏的div
			setTimeout(function(){
				var _this_content = $("#"+obj_async_div_id);
				if(str_style=='top_to_bottom'){
					//_this_content.css('top',0);
					$("#"+obj_async_div_id).css({
						transform:'translate3d(0px,0px,0px)',
						'-ms-transform':'translate3d(0px,0px,0px)',
						'-moz-transform':'translate3d(0px,0px,0px)',
						'-webkit-transform':'translate3d(0px,0px,0px)',
						'-o-transform':'translate3d(0px,0px,0px)'
					});
				}
				else if(str_style=='bottom_to_top'){
					//_this_content.css('bottom',0);
					$("#"+obj_async_div_id).css({
						transform:'translate3d(0px,0px,0px)',
						'-ms-transform':'translate3d(0px,0px,0px)',
						'-moz-transform':'translate3d(0px,0px,0px)',
						'-webkit-transform':'translate3d(0px,0px,0px)',
						'-o-transform':'translate3d(0px,0px,0px)'
					});
				}
				else if(str_style=='left_to_right' || str_style=='right_to_left'){
					//_this_content.css('left',0);
					$("#"+obj_async_div_id).css({
						transform:'translate3d(0px,0px,0px)',
						'-ms-transform':'translate3d(0px,0px,0px)',
						'-moz-transform':'translate3d(0px,0px,0px)',
						'-webkit-transform':'translate3d(0px,0px,0px)',
						'-o-transform':'translate3d(0px,0px,0px)'
					});
				}
				open_transition.open_callback = str_callback;
				open_transition.obj_open_obj_async_div_id = obj_async_div_id;
				open_transition._listen_transition(obj_async_div_id,open_transition._open_success);
			},50);
			
		},
		_open_success:function(){
			var obj_async_div_id = open_transition.obj_open_obj_async_div_id;
			var str_callback = open_transition.open_callback;
			var str_style    = open_transition.curr_style[obj_async_div_id];
			open_transition._remove_listen_transition(obj_async_div_id,open_transition._open_success);
			if(typeof(str_callback)=='function'){
				str_callback(obj_async_div_id,str_style);
			}
			//$("body").scrollTop(0);
			bool_global_page_scroll = open_transition.bool_allow_page_scroll;
			open_transition._init();
			open_transition._listen_close_btn();
			if(open_transition.bool_return_do){
				//window.location.href = open_transition.last_url = window.location.href+'#'+obj_async_div_id;
				
				open_transition.last_url = window.location.href+'#'+obj_async_div_id;
				history.pushState({},'',open_transition.last_url);
				open_transition.current_do = 0;
			}
		},
		_listen_close_btn:function(){
			$(".J_close_transition").unbind('click');
			$(".J_close_transition").click(function(){
											var _this = $(this);
											var obj_async_div_id = _this.attr('data-div-id');
											if(!obj_async_div_id){
												var _index = $(".J_async_div").length-1;
												obj_async_div_id = 'J_async_div_'+_index;
											}
											open_transition._close(obj_async_div_id);
									});
		},
		_close:function(obj_async_div_id){
			if(open_in_iframe && /(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)){
				$(parent.window).scrollTop(open_transition.curr_scroll_top[obj_async_div_id]);
			}
			else{
				$("body").scrollTop(open_transition.curr_scroll_top[obj_async_div_id]);
			}
			var _this_content = $("#"+obj_async_div_id);
			var str_style = open_transition.curr_style[obj_async_div_id];
			if(str_style=='bottom_to_top'){
				//$("#"+obj_async_div_id).css('bottom',-_this_content.height()+'px');	
				$("#"+obj_async_div_id).css({
						transform:'translate3d(0px,100%,0px)',
						'-ms-transform':'translate3d(0px,100%,0px)',
						'-moz-transform':'translate3d(0px,100%,0px)',
						'-webkit-transform':'translate3d(0,100%,0px)',
						'-o-transform':'translate3d(0px,100%,0px)'
					});
			}
			else if(str_style=='top_to_bottom'){
				//$("#"+obj_async_div_id).css('top',-_this_content.height()+'px');
				$("#"+obj_async_div_id).css({
						transform:'translate3d(0px,-100%,0px)',
						'-ms-transform':'translate3d(0px,-100%,0px)',
						'-moz-transform':'translate3d(0px,-100%,0px)',
						'-webkit-transform':'translate3d(0px,-100%,0px)',
						'-o-transform':'translate3d(0px,-100%,0px)'
					});
			}
			else if(str_style=='left_to_right'){
				//$("#"+obj_async_div_id).css({left:-open_transition.page_width+'px',top:0});
				$("#"+obj_async_div_id).css({
					transform:'translate3d(-100%,0px,0px)',
					'-ms-transform':'translate3d(-100%,0px,0px)',
					'-moz-transform':'translate3d(-100%,0px,0px)',
					'-webkit-transform':'translate3d(-100%,0px,0px)',
					'-o-transform':'translate3d(-100%,0px,0px)'
				});
			}
			else if(str_style=='right_to_left'){
				//$("#"+obj_async_div_id).css({left:open_transition.page_width+'px',top:0});
				$("#"+obj_async_div_id).css({
					transform:'translate3d(100%,0px,0px)',
					'-ms-transform':'translate3d(100%,0px,0px)',
					'-moz-transform':'translate3d(100%,0px,0px)',
					'-webkit-transform':'translate3d(100%,0px,0px)',
					'-o-transform':'translate3d(100%,0px,0px)'
				});
			}
			open_transition._init();
			open_transition._listen_close_btn();
			open_transition.obj_close_obj_async_div_id = obj_async_div_id;
			open_transition._listen_transition(obj_async_div_id,open_transition._close_success);
			
		},
		_close_success:function(){
			//window.history.back();
			//console.log('Transition complete!');
			var obj_async_div_id = open_transition.obj_close_obj_async_div_id;
			open_transition._remove_listen_transition(obj_async_div_id,open_transition._close_success);
			
			var str_style = open_transition.curr_style[obj_async_div_id];
			var _this_content = $("#"+obj_async_div_id);
			_this_content.css('display','none');
			
			if(obj_async_div_id.split('J_async_div_').length>1){//去除异步加载的div
				_this_content.remove();
			}
			else{
				_this_content.removeClass(str_style);	
			}
			if(open_in_iframe && /(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)){//防止拉开窗口过短
				$(parent.window).scrollTop(open_transition.curr_scroll_top[obj_async_div_id]);
			}
			else{
				$("body").scrollTop(open_transition.curr_scroll_top[obj_async_div_id]);
			}
			bool_global_page_scroll = true;
			if(typeof(open_transition.curr_close_callback[obj_async_div_id])=='function' && !open_transition.bool_return_do){
				open_transition.curr_close_callback[obj_async_div_id](obj_async_div_id);
			}
			delete open_transition.curr_style[obj_async_div_id];
			if(open_transition.bool_return_do){
				var int_index = open_transition.last_url.lastIndexOf('#');
				var str_url = open_transition.last_url.substr(0,int_index);
				history.replaceState({},'',str_url);
				open_transition.last_url = window.location.href;
			}
		},
		_listen_transition:function(obj_async_div_id,_fun){
			//console.log('listen');
			if(_verify_browser()=='UC'){
				setTimeout(function(){_fun()},500);
				return false;	
			}
			var event_transition = which_transition_event();
			var obj = get_obj(obj_async_div_id);
			if(obj.addEventListener) {
				event_transition && obj.addEventListener(event_transition,_fun,false);
			} else if(obj.attachEvent) {
				event_transition && obj.attachEvent('on'+event_transition,_fun);
			}
		},
		_remove_listen_transition:function(obj_async_div_id,_fun){
			//console.log('remove');
			if(_verify_browser()=='UC'){
				return false;	
			}
			var event_transition = which_transition_event();
			var obj = get_obj(obj_async_div_id);
			if(obj.removeEventListener) {
				obj.removeEventListener(event_transition, _fun, false);
			}else if(obj.detachEvent) {
				obj.detachEvent('on' + event_transition, _fun);
			}
		}
	};
	
function _verify_browser(){
	if(navigator.userAgent.indexOf("MSIE")>0){
	   if(navigator.userAgent.toLowerCase().indexOf("msie 6.0")!=-1){
		   return "MSIE6";
	   }
		return "MSIE";
   }  
   
   if(isFirefox=navigator.userAgent.indexOf("MicroMessenger")>0){  
		return "weixin";  
   }
   
   if(isFirefox=navigator.userAgent.indexOf("UCBrowser")>0){  
		return "UC";  
   }  
   if(isFirefox=navigator.userAgent.indexOf("Firefox")>0){  
		return "Firefox";  
   }  
   if(isSafari=navigator.userAgent.indexOf("Safari")>0){  
		return "Safari";  
   }   
   if(isCamino=navigator.userAgent.indexOf("Camino")>0){  
		return "Camino";  
   }  
   if(isMozilla=navigator.userAgent.indexOf("Gecko/")>0){  
		return "Gecko";  
   }
}

/*
验证手机号码
mobile手机号码
return true or false
*/
var _verify_mobile = function(mobile){
	if(mobile.match(/^(13|14|15|17|18)[\d]{9}$/)){
		return true;
	}
	return false;
}
/*
图片裁切
*/
var crop_img = function(url,w,h){
	if(url.split('.gif').length>1)  return url;
	if(!url || (!w && !h)) return false;
	var temp = url.substr(url.lastIndexOf('.'),4);
	var size = '';
	if(w>0&&h>0) size  = w+'x'+h;
	else{
		alert('请传入正确的宽和高');	
	}
	return url+'_crop_'+size+temp;
}
/*根据大图获取小图*/
var resize_img = function(url,w,h){
	//console.log(url);
		if(url.split('.gif').length>1)  return url;
		if(!url || (!w && !h)) return false;
		/*if(){
			return url;
		}
		*/
		var temp = url.substr(url.lastIndexOf('.'),4);
		
		if(url.lastIndexOf('_')<10){
			url = url.substr(0,url.lastIndexOf('.'));
		}
		else{
			url = url.substr(0,url.lastIndexOf('_'));
		}
		
		var size = '';
		if(w>0&&h>0) size  = w+'x'+h;
		else if(w>0) size  = w;
		else size = '0x'+h;
		return url+'_'+size+temp;
	}
/*弹出html*/
var make_common_open_html =function(_alert_html,str){
	var int_move_height = 30;//弹出框移动高度
	if($("#J_open_div").length>0){
		$("#J_open_div").remove();//先去除之前的公用弹窗
		$("#J_mask").remove();//先去除之前的遮罩层
	}
	
	common_mask();//调用公用遮罩层
	
	$(_alert_html).appendTo($('body'));
	var close_tan_btn = $("#J_close_tan");
	if($(".J_close_tan").length>0){
		close_tan_btn = $(".J_close_tan");
	}		
	var newDivHeight = $('#J_open_div').css("height");
	var newDivHeight_2 = $('#J_open_div').outerHeight();
	var newDivWidth = $('#J_open_div').css("width");	
	var see_height = $(window).height();  //看到的页面的那部分的高度
	var see_width =   $(window).width();
	var int_left = (see_width -parseInt(newDivWidth))/2;
	var int_top = (see_height -parseInt(newDivHeight))/2;
	if(int_top<int_move_height){
		int_top = int_move_height;
	}
	if(_verify_browser()=='MSIE6'||typeof(str)!='undefined'){
		$("#J_open_div").css('position','absolute');
		int_top = $(document).scrollTop()+int_top;//IE6不支持fixed属性，用absolute的时候需要加上滚轮的高度
	}
	else{
		$("#J_open_div").css('position','fixed');
	}
	close_tan_btn.click(function(){
		common_open_html_close();	
	});    
	
	$("#J_open_div").css({
		'top':(int_top-int_move_height)+'px',
		'left':int_left+'px',
		'zIndex':99999,
		'opacity':0,
		transition:'all 0.2s ease',
		'-moz-transition': 'all 0.2s ease',
		'-webkit-transition':'all 0.2s ease',
		'-o-transition': 'all 0.2s ease'
	});
	setTimeout(function(){
		//$("#J_open_div").animate({top:int_top+'px',opacity:'1'}, 200); 
		$("#J_open_div").css({
					transform:'translate3d(0px,'+int_move_height+'px,0px)',
					'-ms-transform':'translate3d(0px,'+int_move_height+'px,0px)',
					'-moz-transform':'translate3d(0px,'+int_move_height+'px,0px)',
					'-webkit-transform':'translate3d(0px,'+int_move_height+'px,0px)',
					'-o-transform':'translate3d(0px,'+int_move_height+'px,0px)',
					opacity:1
				});
	},10);
}
var common_open_html_close = function(){
	$("#J_open_div").css({
				transform:'translate3d(0px,0px,0px)',
				'-ms-transform':'translate3d(0px,0px,0px)',
				'-moz-transform':'translate3d(0px,0px,0px)',
				'-webkit-transform':'translate3d(0px,0px,0px)',
				'-o-transform':'translate3d(0px,0px,0px)',
				opacity:0
			});
	setTimeout(function(){
		if($('#J_open_div').attr('data-remove')!=-1){
			$("#J_open_div").remove();
		}
		$("#J_mask").remove();						
	},200);
}

var _verify_email = function(email){
	var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	if(!myreg.test(email)){
		return false;
	}
	return true;
}


var checkall = {
	_init:function(){
		var c = checkall;
		c._listen();
	},	
	_listen:function(){
		$('#J_checkall').click(function(){
							var _this = $(this);				
							if(_this.attr('checked')=='checked'){
								$('.J_checkall_sub').attr('checked','checked');
							}
							else{
								$('.J_checkall_sub').attr('checked',false);	
							}
						});	
	}
};

/*
 时间转换
 format里面的值只能包含Y,m,d,H,i,s几个变量
 */            
var _date_format = function(ns,format) {
        if(typeof(format)=='undefined' || !format){
            format = 'Y-m-d H:i:s';
        }
        if(typeof(ns)=='undefined' || !ns){
            var _date = new Date();
        }
        else{
            var _date = new Date(parseInt(ns) * 1000);
        }
        if(format.split('Y').length>1){
            var _year = _date.getFullYear();
            format = format.replace('Y',_year);
        }
        if(format.split('m').length>1){
            var _month = _date.getMonth() + 1;
            if(_month<10){
                _month = '0'+_month;
            }
            format = format.replace('m',_month);
        }
        if(format.split('d').length>1){
            var _day = _date.getDate();
            if(_day<10){
                _day = '0'+_day;
            }
            format = format.replace('d',_day);
        }
        if(format.split('H').length>1){
            var _hours = _date.getHours();
            format = format.replace('H',_hours);
        }
        if(format.split('i').length>1){
            var _minutes =  _date.getMinutes()
            if(_minutes<10){
                _minutes = '0'+_minutes;
            }
            format = format.replace('i',_minutes);
        }
        if(format.split('s').length>1){
            var _seconds = _date.getSeconds();
            if(_seconds<10){
                _seconds = '0'+_seconds;
            }
            format = format.replace('s',_seconds);
        }
        return format;
    };

//中文检测
var is_chinese = function(temp){
 	  var re = /[\u0000-\u00FF]/;
 	  if(re.test(temp)) return false;
	  return true;
}
//身份证号码检测
function identity_code_valid(idCard){
	var regIdCard = /^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/;  
    //如果通过该验证，说明身份证格式正确，但准确性还需计算  
    if (regIdCard.test(idCard)) {  
        if (idCard.length == 18) {  
            var idCardWi = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); //将前17位加权因子保存在数组里  
            var idCardY = new Array(1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2); //这是除以11后，可能产生的11位余数、验证码，也保存成数组  
            var idCardWiSum = 0; //用来保存前17位各自乖以加权因子后的总和  
            for (var i = 0; i < 17; i++) {  
                idCardWiSum += idCard.substring(i, i + 1) * idCardWi[i];  
            }  
            var idCardMod = idCardWiSum % 11;//计算出校验码所在数组的位置  
            var idCardLast = idCard.substring(17);//得到最后一位身份证号码  
            //如果等于2，则说明校验码是10，身份证号码最后一位应该是X  
            if (idCardMod == 2) {  
                if (idCardLast == "X" || idCardLast == "x") {  
                    return true;  
                    //alert("恭喜通过验证啦！");  
                } else {  
                    return false;  
                    //alert("身份证号码错误！");  
                }  
            } else {  
                //用计算出的验证码与最后一位身份证号码匹配，如果一致，说明通过，否则是无效的身份证号码  
                if (idCardLast == idCardY[idCardMod]) {  
                    //alert("恭喜通过验证啦！");  
                    return true;  
                } else {  
                    return false;  
                    //alert("身份证号码错误！");  
                }  
            }  
        }  
    } else {  
        //alert("身份证格式不正确!");  
        return false;  
    }  
}

//根据身份证号码提取生日及性别
var card_formart_birthday = function(card_id){
	if(!identity_code_valid(card_id)){
		return false;	
	}
	var arr_return = new Array();
	arr_return['year'] = card_id.substr(6,4);
	arr_return['month'] = card_id.substr(10,2);
	arr_return['day'] = card_id.substr(12,2);
	arr_return['sex'] = card_id.substr(16,1);
	arr_return['sex'] = arr_return['sex']%2 ? '男' : '女';
	return arr_return;
}

function load_image(url, callback) { 
	var img = new Image(); //创建一个Image对象，实现图片的预下载 
	img.src = url; 
	 
	if(img.complete) { // 如果图片已经存在于浏览器缓存，直接调用回调函数 
		callback.call(img); 
		return; // 直接返回，不用再处理onload事件 
	} 
	img.onload = function () { //图片下载完毕时异步调用callback函数。 
		callback.call(img);//将回调函数的this替换为Image对象 
	}; 
};

var _select = {
	hide:false,
	already_listen_options:[],
	_init:function(){
		var c = _select;
		c._listen();
	},
	_listen:function(){
		var c = _select;
		var obj_option = $('.J_select_options');
		$('.J_select').click(function(){
							var _index = $('.J_select').index(this);
							if(obj_option.eq(_index).css('display')=='none'){
								obj_option.css('display','none');
								c.hide = false;	
							}
							obj_option.eq(_index).css('display','');
							setTimeout(function(){
											c.hide = true;	
										},50);
							c._index = _index;
							c._listen_options(_index);
						});
		$('body').click(function(){
							if(c.hide){
								setTimeout(function(){
											obj_option.css('display','none');
											c.hide = false;
										},50);
							}
									 
						});
	},
	_listen_options:function(_index){
		var c = _select;
		var already_listen_options = c.already_listen_options.toString();
		if(already_listen_options.indexOf(','+_index+',')>-1){
			return false;	
		}
		c.already_listen_options.push(','+_index+',');
		$('.J_select_options').eq(_index).children('a').click(function(){
																var _this = $(this);
																$('.J_selected_des').eq(_index).html(_this.html());
																$('.J_select_val').eq(_index).val(_this.attr('data-val'));
															});
	}
};

function isPriceNumber(_keyword){  
    if(_keyword == "0" || _keyword == "0." || _keyword == "0.0" || _keyword == "0.00"){  
        _keyword = "0"; return true;  
    }else{  
        var index = _keyword.indexOf("0");  
        var length = _keyword.length;  
        if(index == 0 && length>1){/*0开头的数字串*/  
            var reg = /^[0]{1}[.]{1}[0-9]{1,2}$/;  
            if(!reg.test(_keyword)){  
                return false;  
            }else{  
                return true;  
            }  
        }else{/*非0开头的数字*/  
            var reg = /^[1-9]{1}[0-9]{0,10}[.]{0,1}[0-9]{0,2}$/;  
            if(!reg.test(_keyword)){  
                return false;  
            }else{  
                return true;  
            }  
        }             
        return false;  
    }  
}  

var _width = $(window).width();
var _height= $(window).height();
var str_url = window.location.href;
var open_in_iframe = str_url.split('open_in_iframe').length>1 ? true : false;