var member_order = {
	_init:function(){
		var c = member_order;
		c._listen_cancel();	
		c._listen_confirm();
	},
	_listen_cancel:function(){
		var c = member_order;
		$('.J_btn_cancel').click(function(){
								var _this = $(this);
								c._this = _this;
								confirm_cancle(c._cancel,arr_language['warn'],arr_language['order']['cancle_tip']);
							});
	},
	_cancel:function(){
		var c = member_order;
		var _data = new Object();
			_data['order_id'] = c._this.attr('data-order_id');
			_ajax_jsonp("/i/order/cancel",c._success,_data);	
	},
	
	_listen_cancel:function(){
		var c = member_order;
		$('.J_btn_cancel').click(function(){
								var _this = $(this);
								c._this = _this;
								confirm_cancle(c._cancel,arr_language['warn'],arr_language['order']['cancle_tip']);
							});
	},
	_listen_confirm:function(){
		var c = member_order;
		$('.J_btn_confirm').click(function(){
								var _this = $(this);
								c._this = _this;
								confirm_cancle(c._confirm,arr_language['warn'],arr_language['order']['confirm_arrival']);
							});
	},
	_confirm:function(){
		var c = member_order;
		var _data = new Object();
			_data['order_id'] = c._this.attr('data-order_id');
			_ajax_jsonp("/i/order/confirm",c._success,_data);	
	},
	_success:function(_data){
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);
	}
}

