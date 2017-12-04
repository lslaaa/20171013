var share = {
	_init:function(){
		var c = share;
		c._listen();
	},
	_listen:function(){
		$('.J_share_facebook').click(function(){
									window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(window.location.href)+'&t='+encodeURIComponent(window.document.title));	  
								});
		$('.J_share_twitter').click(function(){
									window.open('http://twitter.com/home/?status='.concat(encodeURIComponent(window.document.title)) .concat(' ') .concat(encodeURIComponent(window.location.href)));
								});
		
		$('.J_share_pinterest').click(function(){
									window.open('http://pinterest.com/pin/create/button/?url='+encodeURIComponent(window.location.href)+'&media='+encodeURIComponent($('#J_options').attr('data-share_pic'))+'&description='+encodeURIComponent(window.document.title));
								});
		
		
		
	}
}