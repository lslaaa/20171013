var cart = {
	loading:false,
	_init:function(){
		var c = cart;
		c._listen_remove();
		c._listen_edit();
		c._listen_mobile_edit();
	},
	_listen_edit:function(){
		var c= cart;
		$('.J_pc_item_num').keyup(function(){
						var _this = $(this);
						var _data = new Object;
						_data.item_id = _this.attr('data-item_id');
						_data.sku_1 = _this.attr('data-sku_1');
						_data.sku_2 = _this.attr('data-sku_2');
						_data.num   = parseInt(_this.val());
						_data.type  = 'update';
						c.obj = $('.J_pc_item_num');
						if(_data.num>0){
							_ajax_jsonp('/?mod=mall&extra=add_cart',c._update_item_success,_data);
						}
						
					});
	},
	_listen_mobile_edit:function(){
		var c= cart;
		$('.J_mobile_item_num').keyup(function(){
						var _this = $(this);
						var _data = new Object;
						_data.item_id = _this.attr('data-item_id');
						_data.sku_1 = _this.attr('data-sku_1');
						_data.sku_2 = _this.attr('data-sku_2');
						_data.num   = parseInt(_this.val());
						_data.type  = 'update';
						c.obj = $('.J_mobile_item_num');
						if(_data.num>0){
							_ajax_jsonp('/?mod=mall&extra=add_cart',c._update_item_success,_data);
						}
						
					});
	},
	_update_item_success:function(){
		var c= cart;
		var float_total = 0;
		var obj = c.obj;
		for(var i=0;i<obj.length;i++){
			var ci = obj.eq(i);
			var float_price = parseFloat(ci.attr('data-price'));
			var int_item_id = ci.attr('data-item_id');
			var float_sub_total = float_price * ci.val();
			$('#J_pc_item_subtotal_'+int_item_id).html(float_sub_total);
			$('#J_mobile_item_subtotal_'+int_item_id).html(float_sub_total);
			//alert('#J_mobile_item_subtotal_'+int_item_id);
			float_total += float_sub_total;
		}
		$('#J_total').html(float_total);
		//window.location.href = window.location.href;
	},
	_set_options:function(_name,_val){
		$('#J_options').attr(_name,_val);
	},
	_listen_remove:function(){
		var c = cart;
		$('.J_remove').click(function(){
							var _this = $(this);
							confirm_cancle(c._del,arr_language['warn'],arr_language['del_2']);
							c._this = _this;
						});
	}
}