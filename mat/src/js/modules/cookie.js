var cookie = {
	cookiepre:'lem_',
	cookiedomain:".smgkjjt.com",
	set:function(key, value, t) {//设置cookie
			var c = cookie;
			key = c.cookiepre+key;
			var oDate = new Date();
			oDate.setDate( oDate.getDate() + t );//如果想换成小时，分钟，秒，修改setDate函数即可
			document.cookie = key + "=" + encodeURI(value) + ";expires=" + oDate.toGMTString()+"; path=/; domain="+c.cookiedomain;
		},
	get:function(key) {//获取cookie
		var c = cookie;
		key = c.cookiepre+key;
		var arr1 = document.cookie.split('; ');
		for (var i=0; i<arr1.length; i++) {
			var arr2 = arr1[i].split('=');
			if ( arr2[0] == key ) {
				return decodeURI(arr2[1]);
			}
		}
	},
	remove:function(key) {//删除cookie
		var c = cookie;
		key = c.cookiepre+key;
		setCookie(key, '', -1);
	}	
}