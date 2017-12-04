var left_menu = {
	_init:function(){
		$('.J_menu_one').click(function(){
							var _this = $(this);
							var _index = $('.J_menu_one').index(this);
							if(_this.attr('href')=='javascript:;'){
								$('.J_menu_two').css('display','none');
								$('.J_menu_two').eq(_index).css('display','');
							}
						});
	}
}