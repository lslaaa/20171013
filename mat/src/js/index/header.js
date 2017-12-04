var header = {
	_init:function(){
		header._pc_nav_listen();
		header._mobile_nav_listen();
		header._listen_search();
		header._pc_show_login_menu();
	},
	_pc_nav_listen:function(){
		var dick_show;
		var disk_hide;
		$('.J_btn_pc_menu').mouseover(function(){
								clearTimeout(disk_hide);
								var _index = $('.J_btn_pc_menu').index(this);
								dick_show = setTimeout(function(){
															$('.J_btn_pc_menu').removeClass('on');
															$('.J_btn_pc_menu').eq(_index).addClass('on');
															$('.J_pc_menu').css('display','none');
															$('.J_pc_menu').eq(_index).css('display','');		
														},20);

							}).mouseout(function(){
								clearTimeout(dick_show);
								disk_hide = setTimeout(function(){
															$('.J_btn_pc_menu').removeClass('on');	
															$('.J_pc_menu').css('display','none');
														},20);
							});	
		$('.J_pc_menu').mouseover(function(){
								clearTimeout(disk_hide);				   
							}).mouseout(function(){
								clearTimeout(dick_show);
								disk_hide = setTimeout(function(){
															$('.J_btn_pc_menu').removeClass('on');	
															$('.J_pc_menu').css('display','none');
														},20);
							});
	},
	_mobile_item_cat_listen:function(){
		var c = header;
		$('.J_load_sub_menu_cat').unbind('click');
		$('.J_load_sub_menu_cat').click(function(){
									var _this = $(this);
									var str_type = _this.attr('data-type');
									var obj_data = new Array();
									obj_data['title'] = _this.children('em').html();
									obj_data['list'] = new Array();
									if(str_type=='news'){
										for(var i=0,ci;ci=global_news_cat[i++];){
											var obj_temp = new Array();
											obj_temp['name'] = ci['name'];
											obj_temp['url'] = '/news/cid-'+ci['cid'];
											obj_data['list'].push(obj_temp);	
										}	
									}
									else if(str_type=='page'){
										var int_pid = _this.attr('data-cid');
										for(var i=0,ci;ci=global_page_cat[i++];){
											if(ci['pid']==int_pid){
												var obj_temp = new Array();
												obj_temp['name'] = ci['name'];
												obj_temp['url'] = '/about/p-'+ci['page_id'];
												obj_data['list'].push(obj_temp);	
											}
										}
									}
									else if(str_type=='mall'){
										for(var i=0,ci;ci=global_item_cat[i++];){
												var obj_temp = new Array();
												obj_temp['name'] = ci['name'];
												obj_temp['url'] = '/mall/cid-'+ci['cid'];
												obj_data['list'].push(obj_temp);	
										}
									}
									
									var _html = c._mobile_make_cat(obj_data);		
									$('body').append(_html);
									open_transition._make('','right_to_left',c._open_menu_success,'J_phone_menu_sub_bar',1,'',false);				 
								});	
	},
	_mobile_nav_listen:function(){
		var c = header;
		c._mobile_item_cat_listen();
		$('.J_load_sub_page_cat').click(function(){
									var _this = $(this);
									var int_pid = _this.attr('data-page_id');
									var obj_data = new Array();
									obj_data['title'] = _this.children('em').html();
									obj_data['list'] = new Array();
									for(var i=0,ci;ci=global_page_cat[i++];){
										if(ci['pid']==int_pid){
											var obj_temp = new Array();
											obj_temp['name'] = ci['name'];
											obj_temp['url'] = '/page/p-'+ci['page_id'];
											obj_data['list'].push(obj_temp);	
										}
									}
									var _html = c._mobile_make_cat(obj_data);
									$('body').append(_html);
									open_transition._make('','right_to_left',c._open_menu_success,'J_phone_menu_sub_bar',1,'',false);
								});
		$('#J_btn_mobile_menu').click(function(){
									var _html = c._mobile_make_menu();
									$('body').append(_html);
									var obj_login_info = $('#J_pc_login_info');
									if(obj_login_info.length>0){
										$('#J_mobile_login_info').html('<a class="ml" href="/i/menu">'+obj_login_info.html()+'</a>');
									}
									open_transition._make('','right_to_left',c._open_menu_success,'J_mobile_menu',1,'',false);				
								});
	},
	_open_menu_success:function(){
		var c = header;
		c._mobile_item_cat_listen();
		
	},
	_mobile_make_cat:function(obj_data){
		_height = $(window).height();
		var _html = '<div id="J_phone_menu_sub_bar" class="phone_menu_bar" style="display:none; z-index:999;">'+
					'<div class="f_l bank J_close_transition" data-div-id="J_phone_menu_sub_bar" style="height:'+_height+'px;">'+
						'<i class="ip_ico">&nbsp;</i>'+
					'</div>'+
					'<div class="f_r menu" style="height:'+_height+'px;">'+
						'<h1><span>'+obj_data['title']+'</span></h1>'+
						'<ul>';
			for(var i=0,ci;ci=obj_data['list'][i++];){
				_html += '<li><a href="'+ci['url']+'"><em class="f_l">'+ci['name']+'</em></a></li>';
			}
			_html +='</ul>'+
						'</div>'+
					'</div>';
		return _html;
	},
	_mobile_make_menu:function(){
		_height = $(window).height();
		var str_login_url = $('#J_top_login').attr('data-url');
		var _html = '<div id="J_mobile_menu" class="phone_menu_bar" style="display:none; z-index:99;">'+
						'<div class="f_l bank J_close_transition" data-div-id="J_mobile_menu" style="height:'+_height+'px;">'+
							'<i class="ip_ico">&nbsp;</i>'+
						'</div>'+
						'<div class="f_r menu" style="height:'+_height+'px;">'+
							'<h1 id="J_mobile_login_info"><a href="'+str_login_url+'" class="ml">'+arr_language['menu']['login']+'</a><em>/</em><a href="'+str_login_url+'">'+arr_language['menu']['reg']+'</a></h1>'+
							'<ul>'+
								'<li>'+
									'<a href="/">'+
										'<em class="f_l">'+arr_language['menu']['home']+'</em>'+
									'</a>'+
								'</li>'+
								'<li>'+
									'<a class="J_load_sub_menu_cat" data-type="page" data-cid="1" href="javascript:;">'+
										'<em class="f_l">'+arr_language['menu']['about']+'</em><i class="pc_ico f_r"></i>'+
									'</a>'+
								'</li>'+
								'<li>'+
									'<a class="J_load_sub_menu_cat" data-type="news" href="javascript:;">'+
										'<em class="f_l">'+arr_language['menu']['news']+'</em><i class="pc_ico f_r"></i>'+
									'</a>'+
								'</li>'+
								'<li>'+
									'<a class="J_load_sub_menu_cat" data-type="mall" href="javascript:;">'+
										'<em class="f_l">'+arr_language['menu']['mall']+'</em><i class="pc_ico f_r"></i>'+
									'</a>'+
								'</li>'+
								'<li>'+
									'<a href="/contact">'+
										'<em class="f_l">'+arr_language['menu']['contact']+'</em>'+
									'</a>'+
								'</li>'+
							'</ul>'+
						'</div>'+
					'</div>';
		return _html;	
	},
	_listen_search:function(){
		var c = header;
		$('#J_search_form').submit(function(){
										var obj_kwd = $('#J_search_kwd');
										if(!obj_kwd.val()){
											make_alert_html(arr_language['warn'],arr_language['search_error']);
											return false;
										}
										window.location.href = '/mall/?kwd='+encodeURIComponent(obj_kwd.val());
										return false;
									});
		$('#J_search_kwd_clear').click(function(){
										$('#J_search_kwd').val('');			
									});
		c._listen_mobile_search();
	},
	_listen_mobile_search:function(){
		$('#J_mobile_search').css({
									transition:'all 0.3s ease',
									'-moz-transition': 'all 0.3s ease',
									'-webkit-transition':'all 0.3s ease',
									'-o-transition': 'all 0.3s ease'	   
							});
		$('#J_open_mobile_search').click(function(){
										$('#J_mobile_search').css({
												transition:'all 0.3s ease',
												'-moz-transition': 'all 0.3s ease',
												'-webkit-transition':'all 0.3s ease',
												'-o-transition': 'all 0.3s ease',
												'display':''
										});	
										$('#J_mobile_search').siblings().css({
												transition:'all 0.3s ease',
												'-moz-transition': 'all 0.3s ease',
												'-webkit-transition':'all 0.3s ease',
												'-o-transition': 'all 0.3s ease'	   
										});	
										setTimeout(function(){
													$('#J_mobile_search').css('opacity',1);	
													$('#J_mobile_search').siblings().css('opacity',0);	
												},20);
									});
		$('#J_close_mobile_search').click(function(){
										$('#J_mobile_search').css('opacity',0);	
										$('#J_mobile_search').siblings().css('opacity',1);		
										setTimeout(function(){
													$('#J_mobile_search').css({
															transition:'',
															'-moz-transition': '',
															'-webkit-transition':'',
															'-o-transition': '',
															'display':'none'
													});	
													$('#J_mobile_search').siblings().css({
															transition:'',
															'-moz-transition': '',
															'-webkit-transition':'',
															'-o-transition': ''	   
													});
												},300);
									});
		$('#J_mobile_search_form').submit(function(){
										var obj_kwd = $('#J_mobile_search_kwd');
										if(!obj_kwd.val()){
											make_alert_html(arr_language['warn'],arr_language['search_error']);
											return false;
										}
										window.location.href = '/mall/?kwd='+encodeURIComponent(obj_kwd.val());
										return false;
									});	
	},
	_pc_show_login_menu:function(){
		var dick_show;
		var disk_hide;
		$('#J_pc_login_info').mouseover(function(){
								clearTimeout(disk_hide);
								dick_show = setTimeout(function(){
															$('#J_pc_login_menu').css('display','');
														});
							}).mouseout(function(){
								clearTimeout(dick_show);
								disk_hide = setTimeout(function(){
															$('#J_pc_login_menu').css('display','none');
														},20);
							});
		$('#J_pc_login_menu').mouseover(function(){
								clearTimeout(disk_hide);				   
							}).mouseout(function(){
								clearTimeout(dick_show);
								disk_hide = setTimeout(function(){
															$('#J_pc_login_menu').css('display','none');
														},20);
							});
	}
}
