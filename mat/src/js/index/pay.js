var pay = {
	loading:false,
	_init:function(){
		var c = pay;
		c._listen_send();
		c._listen_saoma();
	},
	_listen_send:function(){
		var c = pay;
		$('#J_send_pay').click(function(){
								var int_order_id = $('#J_order_id').val();
								var float_price  = $('#J_total_price').val();
								if($('#J_alipay').css('display')!='none'){
									
								}
								else{
									window.location.href = '/wxpay/?fdfdf&order_id='+int_order_id+'&total_price='+float_price;	
								}
							});	
	},
	_listen_saoma:function(){
		var c = pay;
		var _data = new Object();
		_data.id = $('#J_order_id').val();
		_ajax_jsonp('/mall/verify_saoma/',c._saoma_success,_data);	
	},
	_saoma_success:function(_data){
		var c = pay;
		if(_data['data']){
			window.location.href = '/i/order';	
			return false;
		}
		setTimeout(function(){
						pay._listen_saoma();		
					},500);
	}
}