var mall_detail={_init:function(){var a=mall_detail;a._listen_share(),a._listen_size(),$(".my-zoom").WMZoom({config:{inner:!1}})},_listen_share:function(){$("#J_pc_btn_open_share").click(function(){var a=$("#J_pc_share");"none"!=a.css("display")?a.css("display","none"):a.css("display","")})},_listen_size:function(){var a=mall_detail;$("#J_btn_pc_size").click(function(){var a=$("#J_pc_size");"none"!=a.css("display")?a.css("display","none"):a.css("display","")}),$("#J_pc_size").find("a").click(function(){var b=$(this),c=b.html(),d=b.attr("data-sku_2"),e=b.attr("data-special_sku_2");a._choose_size(e,d,c)}),$("#J_mobile_size").change(function(){for(var b=$(this),c=b.find("option:selected").html(),d=b.val(),e=$("#J_mobile_size").children(),f=0;f<e.length;f++){var g=e.eq(f);if(g.val()==d){var h=g.attr("data-special_sku_2");break}}a._choose_size(h,d,c)}),$("#J_btn_pc_num").click(function(){var a=$("#J_pc_num");"none"!=a.css("display")?a.css("display","none"):a.css("display","")}),$("#J_pc_num").find("a").click(function(){var b=$(this),c=b.attr("data-num");a._choose_num(c)}),$("#J_mobile_num").change(function(){var b=$(this),c=b.val();a._choose_num(c)})},_choose_size:function(a,b,c){var d=mall_detail;d._set_options("data-special_sku_2",a),d._set_options("data-sku_2",b),$("#J_choose_size").html("("+c+")"),$("#J_choose_size").css("display",""),setTimeout(function(){$("#J_pc_size").css("display","none")},50)},_choose_num:function(a){var b=mall_detail;b._set_options("data-num",a),$("#J_choose_num").html("("+a+")"),$("#J_choose_num").css("display",""),setTimeout(function(){$("#J_pc_num").css("display","none")},50)},_set_options:function(a,b){$("#J_options").attr(a,b)}},mall_add_cart={_init:function(){var a=mall_add_cart;a._listen()},_listen:function(){var a=mall_add_cart;$("#J_add_cart").click(function(){var b=$("#J_options");if("undefined"==typeof b.attr("data-sku_2"))return make_alert_html(arr_language.warn,arr_language.choose_sku_2_error),!1;var c=new Object;c.item_id=b.attr("data-item_id"),c.sku_1=b.attr("data-sku_1"),c.sku_2=b.attr("data-sku_2"),c.num=b.attr("data-num"),c.special_sku_2=b.attr("data-special_sku_2"),_ajax_jsonp("/?mod=mall&extra=add_cart",a._add_success,c)})},_add_success:function(a){$("#J_pic_header_cart_num").html(a.data),$("#J_pic_header_cart_num").css("display",""),make_alert_html(arr_language.notice,arr_language.success)}};