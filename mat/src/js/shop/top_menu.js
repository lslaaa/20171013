var top_menu = {
	_init:function(){
		$('.J_top_menu').click(function(){
							var _this = $(this);
							$('.J_top_menu').removeClass('on');
							_this.addClass('on');
							var str_url = _this.attr('data-url')
							if(str_url){
								window.parent.document.getElementById('ifr_main').src = str_url;	
							}
							
						});
	}
}