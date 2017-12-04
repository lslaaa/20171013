var mall_list = {
	_init:function(){
		var c = mall_list;
		c._listen();
	},
	_listen:function(){
		var c = mall_list;
		var o = mall_add_cart;
		$('.J_add_cart').click(function(){
							var obj_options = $(this);
							var _data = new Object();
							_data.item_id = obj_options.attr('data-item_id');
							_data.sku_1 = obj_options.attr('data-sku_1');
							_data.sku_2 = obj_options.attr('data-sku_2');
							_data.num = obj_options.attr('data-num');
							_data.special_sku_2 = obj_options.attr('data-special_sku_2');
							//console.log(_data);
							_ajax_jsonp('/?mod=mall&extra=add_cart',o._add_success,_data);				
						});	
	}
}