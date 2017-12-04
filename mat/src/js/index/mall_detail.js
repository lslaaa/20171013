var mall_detail = {
	_init:function(){
		var c = mall_detail;
		c._listen_share();
		c._listen_size();
		$('.my-zoom').WMZoom({
					config : {
						inner : false
					}
				});
	},
	_listen_share:function(){
		$('#J_pc_btn_open_share').click(function(){
									var obj = $('#J_pc_share');			  
									if(obj.css('display')!='none'){
										obj.css('display','none');
									}
									else{
										obj.css('display','');
									}
								});	
	},
	_listen_size:function(){
		var c = mall_detail;
		$('#J_btn_pc_size').click(function(){
									var obj = $('#J_pc_size');			  
									if(obj.css('display')!='none'){
										obj.css('display','none');
									}
									else{
										obj.css('display','');
									}		   
								});
		$('#J_pc_size').find('a').click(function(){
									var _this = $(this);
									var float_size = _this.html();
									var int_sku_2  = _this.attr('data-sku_2');
									var str_special_sku_2 = _this.attr('data-special_sku_2');
									c._choose_size(str_special_sku_2,int_sku_2,float_size);
								});
		$('#J_mobile_size').change(function(){
									var _this = $(this);
									var float_size = _this.find("option:selected").html();
									var int_sku_2  = _this.val();
									var obj_options = $('#J_mobile_size').children();
									for(var i=0;i<obj_options.length;i++){
										var ci = obj_options.eq(i);
										if(ci.val()==int_sku_2){
											var str_special_sku_2 = ci.attr('data-special_sku_2');
											break;	
										}
									}
									
									c._choose_size(str_special_sku_2,int_sku_2,float_size);
												
								});
		$('#J_btn_pc_num').click(function(){
							var obj = $('#J_pc_num');			  
							if(obj.css('display')!='none'){
								obj.css('display','none');
							}
							else{
								obj.css('display','');
							}		   
						});
		$('#J_pc_num').find('a').click(function(){
									var _this = $(this);
									var int_num  = _this.attr('data-num');
									c._choose_num(int_num);
								});
		$('#J_mobile_num').change(function(){
									var _this = $(this);
									var int_num  = _this.val();
									c._choose_num(int_num);
												
								});
	},
	_choose_size:function(str_special_sku_2,int_sku_2,float_size){
		var c = mall_detail;
		c._set_options('data-special_sku_2',str_special_sku_2);
		c._set_options('data-sku_2',int_sku_2);
		$('#J_choose_size').html('('+float_size+')');	 
		$('#J_choose_size').css('display','');
		setTimeout(function(){
						$('#J_pc_size').css('display','none');		
					},50);	
	},
	_choose_num:function(int_num){
		var c = mall_detail;
		c._set_options('data-num',int_num);
		$('#J_choose_num').html('('+int_num+')');	 
		$('#J_choose_num').css('display','');
		setTimeout(function(){
						$('#J_pc_num').css('display','none');		
					},50);
	},
	_set_options:function(_name,_val){
		$('#J_options').attr(_name,_val);
	}
}

var mall_add_cart = {
	_init:function(){
		var c = mall_add_cart;
		c._listen();
	},
	_listen:function(){
		var c = mall_add_cart;
		$('#J_add_cart').click(function(){
									var obj_options = $('#J_options');
									if(typeof(obj_options.attr('data-sku_2'))=='undefined'){
										make_alert_html(arr_language['warn'],arr_language['choose_sku_2_error']);
										return false;
									}
									var _data = new Object();
									_data.item_id = obj_options.attr('data-item_id');
									_data.sku_1 = obj_options.attr('data-sku_1');
									_data.sku_2 = obj_options.attr('data-sku_2');
									_data.num = obj_options.attr('data-num');
									_data.special_sku_2 = obj_options.attr('data-special_sku_2');
									//console.log(_data);
									_ajax_jsonp('/?mod=mall&extra=add_cart',c._add_success,_data);
								});
	},
	_add_success:function(_data){
		$('#J_pic_header_cart_num').html(_data['data']);
		$('#J_pic_header_cart_num').css('display','');
		make_alert_html(arr_language['notice'],arr_language['success']);
	}
};