var cookie={cookiepre:"lem_",cookiedomain:".smgkjjt.com",set:function(a,b,c){var d=cookie;a=d.cookiepre+a;var e=new Date;e.setDate(e.getDate()+c),document.cookie=a+"="+encodeURI(b)+";expires="+e.toGMTString()+"; path=/; domain="+d.cookiedomain},get:function(a){var b=cookie;a=b.cookiepre+a;for(var c=document.cookie.split("; "),d=0;d<c.length;d++){var e=c[d].split("=");if(e[0]==a)return decodeURI(e[1])}},remove:function(a){var b=cookie;a=b.cookiepre+a,setCookie(a,"",-1)}};