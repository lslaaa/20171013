var admin_item_edit = {
        str_ids:'',
	_init:function(){
		admin_item_edit._listen_cat();	
		admin_item_edit._listen_upload();
		admin_item_edit._listen_send();
		//_attr._init();
	},
	_listen_cat:function(){
		var c = admin_item_edit;
		var obj_cid_1 = $('#J_cid_1');
		obj_cid_1.change(function(){
						var _this = $(this);
						var int_pid = _this.val();
						c._cat_select(int_pid);
					});
		if(obj_cid_1.attr('data-cid')>0){
			var int_pid = obj_cid_1.attr('data-cid');
			var obj_options = obj_cid_1.children();
			for(var i=0;i<obj_options.length;i++){
				var ci = obj_options.eq(i);
				if(ci.val()==int_pid){
					ci.attr('selected',true);
					break;
				}
			}
			c._cat_select(obj_cid_1.attr('data-cid'));	
		}		
	},
	_cat_select:function(int_pid){
		var _html = '';
		var c = admin_item_edit;
		if(int_pid>0){
			var int_cid_2 = $('#J_cid_2').attr('data-cid');
			for(var ci,i=0;ci=c.json_cat[i++];){
				if(ci['pid']==int_pid){
					var str_selected = int_cid_2==ci['cid'] ? ' selected="selected"' : '';
					_html += '<option value="'+ci['cid']+'"'+str_selected+'>'+ci['name']+'</option>';
				}
			}
		}
		if(_html){
			_html = '<option value="0">'+arr_language['select']+'</option>'+_html;
			$('#J_cid_2').css('display','');
		}
		else{
			$('#J_cid_2').css('display','none');	
		}
		$('#J_cid_2').html(_html);
	},
	_listen_upload:function(){
		var c = admin_item_edit;
		$('#J_pic').change(function(){
							$('#J_form_do').val('upload');
							$('#J_upload_type').val('main_pic');	
							if(c.bool_sending){
								return false;	
							}
							$('.J_sku_pic_upload_btn').attr('disabled',true);
							c.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),c.upload_success,'send_success');
							$('#J_send_form').submit();
						});	
	},
	_listen_sku_upload:function(){
		var c = admin_item_edit;
		$('.J_sku_pic_upload_btn').unbind('change');
		$('.J_sku_pic_upload_btn').change(function(){
							var _index = $('.J_sku_pic_upload_btn').index(this);
							$('#J_form_do').val('upload');
							$('#J_upload_type').val('sku_pic');	
							if(c.bool_sending){
								return false;	
							}
							$('.J_sku_pic_upload_btn,#J_pic').attr('disabled',true);
							$('.J_sku_pic_upload_btn').eq(_index).attr('disabled',false);
							c.upload_sku_index = _index;
							c.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),c.upload_success,'send_success');
							$('#J_send_form').submit();
						});		
	},
	upload_success:function(_data){
		var c = admin_item_edit;
		_remove_ajax_frame('send_success');
		$('#J_form_do').val('');
		if(_data['status']!=200){
			c.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;
		}
		c.bool_sending = false;
		var int_w = c.json_config['item_'+$('#J_upload_type').val()]['val'];
		int_w = int_w.split('|');
		int_w = int_w[1].split(',');
		int_w = int_w[0];
		if($('#J_upload_type').val()=='main_pic'){
			$('#J_pic_show').html('<img src="'+resize_img(_data['data'],int_w)+'" style="width:100px">');
			$('#J_pic_url').val(_data['data']);
		}
		else{
			var _html = '<span style=" display:table;"><em style="display:table-cell;text-align:center;vertical-align:middle; height:40px;"><img src="'+resize_img(_data['data'],int_w)+'" style="width:100%;"></em><input class="J_sku_pic" type="hidden" value="'+_data['data']+'"></span>';
			$(".J_sku_pic_list").eq(c.upload_sku_index).html(_html);	
		}
		$('.J_sku_pic_upload_btn,#J_pic').attr('disabled',false);
	},
	_listen_send:function(){
		var c = admin_item_edit;
		$('#J_send_form').submit(function(){
								if($('#J_form_do').val()=='upload'){
									return true;	
								}
								if(!admin_item_edit_verify._verify()){
									return false;	
								}
								if(!admin_item_edit_verify._verify_sku_info()){
									return false;	
								}
								admin_item_edit_verify._data_format();
								$('.J_sku_pic_upload_btn,#J_pic').attr('disabled',true);
								$('#J_form_do').val('add');
								if(c.bool_sending){
									return false;	
								}
								c.bool_sending = true;
								_ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');	
							});
	},
	send_success:function(_data){
		_remove_ajax_frame('send_success');
		//console.log(_data);
		if(_data['status']!=200){
			admin_item_edit.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			$('.J_sku_pic_upload_btn,#J_pic').attr('disabled',false);
			return false;	
		}
		make_alert_html(arr_language['notice'],_data['info'],'/admin.php?mod=item&mod_dir=mall');
	},
        //批量下架
        _listen_bat_inactive:function(){
            $('#J_bat_inactive').click(function(){
                    var _this = $(this);
                    var obj = $('.J_checkall_sub');				
                     var str_ids = '';
                     for(var i=0;i<obj.length;i++){
                             var ci = obj.eq(i);
                             if(ci.attr('checked')=='checked'){
                                     str_ids += ci.val()+',';	
                             }
                     }
                     if(!str_ids){
                             make_alert_html(arr_language['warn'],arr_language['no_select']);
                             return false;	
                     }
                     _str_ids = str_ids;
                     confirm_cancle(admin_item_edit.bat_inactive,arr_language['warn'],arr_language['del_3']);
                     admin_item_edit._this = _this;
             });
        },
        
        bat_inactive:function(){
		_ajax_jsonp('/admin.php?mod=item&mod_dir=mall&extra=bat_inactive&ids='+_str_ids,admin_item_edit.bat_inactive_success);
	},
	bat_inactive_success:function(_data){
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);	
	}
}

var admin_item_del = {
	_init:function(){
		admin_item_del._listen_del();
	},
	_listen_del:function(){
		var c = admin_item_del;
		$('.J_btn_del').click(function(){
								var _this = $(this);
								confirm_cancle(c._del,arr_language['warn'],arr_language['del_2']);
								c._this = _this;
							});
	},
	_del:function(){
		var c = admin_item_del;
		var _this = c._this;
		var int_item_id = _this.attr('data-item_id');
		_ajax_jsonp('/admin.php?mod=item&mod_dir=mall&extra=del&item_id='+int_item_id,c._del_success);
	},
	_del_success:function(_data){
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);	
	}	
}

var admin_item_edit_att = {
	_json_att_type:new Object(),//编辑时初始化商品属性 
	_json_temp_sku_data:new Object(),//编辑时临时保存的商品sku信息 
	_init:function(_json_att_type){
		admin_item_edit_att._json_att_type = _json_att_type;
		admin_item_edit_att._listen_type_change();
		if(parseInt($("#J_item_id").val())>0){
			admin_item_edit_att._init_type();
		}
	},
	_init_type:function(){//初始化商品属性
		var _sku_title = admin_item_edit_att._json_att_type.sku_title;
		if(admin_item_edit_att._json_att_type.sku_title==''){
			$("#J_bar_code").val(admin_item_edit_att._json_att_type['item_sku']['0_0']['bar_code']);
			$("#J_stock").val(admin_item_edit_att._json_att_type['item_sku']['0_0']['stock']);
			$("#J_free_buy_stock").val(admin_item_edit_att._json_att_type['item_sku']['0_0']['free_buy_stock']);
			return false;	
		}
		var _sku_title_num = _sku_title.length;
		var _att_type_change = $(".J_att_type");
		/*开始选中属性类型与显示属性名称*/
		var _sku_1 = new Object,_sku_2 = new Object;
		if(_sku_title_num==1){
			_sku_1 = admin_item_edit_att._json_att_type.sku_1;
			_att_type_change.eq(1).attr('checked','checked');
			$(".J_sku_title").eq(0).css('display','');
			$(".J_sku_title").eq(0).find('input').val(_sku_title[0]);
			admin_item_edit_att._listen_sku_title(0);
			$(".J_no_att_type").css('display','none');
		}
		else if(_sku_title_num==2){
			_sku_1 = admin_item_edit_att._json_att_type.sku_1;
			_sku_2 = admin_item_edit_att._json_att_type.sku_2;
			_att_type_change.eq(2).attr('checked','checked');
			$(".J_sku_title").css('display','');
			$(".J_sku_title").eq(0).find('input').val(_sku_title[0]);
			$(".J_sku_title").eq(1).find('input').val(_sku_title[1]);
			admin_item_edit_att._listen_sku_title(0);
			admin_item_edit_att._listen_sku_title(1);
			$(".J_no_att_type").css('display','none');
		}
		/*结束选中属性类型与显示属性名称*/
		/*开始生成属性值*/
		var temp = new Object();
		for(var i=0,ci;ci=_sku_1[i++];){
			//temp[ci['name']] = ci;//用名字为键名，方便之后处理数据
			if(i==1){
				var _data = admin_item_edit_att._make_set_sku_html(0,ci['name'],ci['id']);
				$(_data._html).insertAfter($(".J_sku_title").eq(1));
			}
			else{
				admin_item_edit_att._set_sku_add(0,ci['name'],ci['id']);
			}
		}
		//admin_item_edit_att._json_att_type.sku_1_name = temp;
		admin_item_edit_att._listen_set_sku_add(0);
		var temp = new Object();
		for(var i=0,ci;ci=_sku_2[i++];){
			//temp[ci['name']] = ci;//用名字为键名，方便之后处理数据
			if(i==1){
				var _data = admin_item_edit_att._make_set_sku_html(1,ci['name'],ci['id']);
				$(_data._html).insertAfter($(".J_set_sku").eq(0));
			}
			else{
				admin_item_edit_att._set_sku_add(1,ci['name'],ci['id']);
			}
		}
		//admin_item_edit_att._json_att_type.sku_2_name = temp;
		admin_item_edit_att._listen_set_sku_add(1);
		/*结束生成属性值*/
		admin_item_edit_att._make_sku_list(true);
		admin_item_edit._listen_sku_upload();
		
	},
	_listen_type_change:function(){//监听属性类型改变，无，一个，两个
		$(".J_att_type").change(function(){
			var _this = $(this);		
			if(_this.val()==0){
				admin_item_edit_att._remove_set_sku_html();
			}
			else if(_this.val()==1){
				admin_item_edit_att._remove_set_sku_html();
				admin_item_edit_att._listen_sku_title(0);
				$(".J_no_att_type").css('display','none');
			}
			else if(_this.val()==2){
				$(".J_sku_title").css('display','');
				admin_item_edit_att._listen_sku_title(0);
				admin_item_edit_att._listen_sku_title(1);
				$(".J_no_att_type").css('display','none');
			}
		});
	},
	_listen_sku_title:function(_index){//监听属性名称
		var _this = $(".J_sku_title").eq(_index).find('input');
		_this.unbind('keyup');
		_this.keyup(function(){
			var _data = admin_item_edit_att._make_set_sku_html(_index);
			if(_data.has_html){
				$(".J_sku_list_title").eq(_index).html(_data.sku_title);
				$(".J_set_sku").eq(_index).children('dt').html(_data.sku_title+'：');
			}
			else{
				if(_index==0){
					$(_data._html).insertAfter($(".J_sku_title").eq(1));
				}
				else{
					$(_data._html).insertAfter($(".J_set_sku").eq(0));
				}
				admin_item_edit_att._listen_set_sku_add(_index);
				admin_item_edit_att._listen_set_sku();
			}
		});
	},
	_make_set_sku_html:function(_index,_sku_name,_sku_id){//生成添加属性值
		if(typeof(_sku_name)=='undefined'){
			_sku_name = '',_sku_id=0;
		}
		var _this_sku_title = $(".J_sku_title").eq(_index).find('input').val();
		var _return = new Object();
		if($(".J_set_sku").eq(_index).length==1){
			_return.has_html = true;
			_return.sku_title = _this_sku_title;
			return _return;
		}
		var _html = '<dl class="J_set_sku mt12">';
			_html += '<dt>'+_this_sku_title+'：</dt>';
			_html += '<dd><span style="float:left;padding-bottom:5px;"><input data-sku-id="'+_sku_id+'" class="text_z J_sku_'+(_index+1)+'_value" value="'+_sku_name+'"><a href="javascript:;" class="add_lsit nico J_set_sku_add" title="添加"><!--添加--></a></span></dd>';
			_html += '</dl>';
		_return.has_html = false;
		_return._html = _html;
		return _return;
	},
	_listen_set_sku_add:function(_index){//监听某个属性下增加按钮
		var _this = $(".J_set_sku_add").eq(_index);
		_this.click(function(){
			var _this_values = _this.parent().parent();
			if(_this_values.find('input').length>=20){
				make_alert_html(arr_language['notice'],'每种属性最多添加20个值');
				return false;	
			}
			admin_item_edit_att._set_sku_add(_index);
		});
	},
	_set_sku_add:function(_index,_sku_name,_sku_id){//为某个属性增加输入框
		if(typeof(_sku_name)=='undefined'){
			_sku_name = '';
		}
		var _this = $(".J_set_sku_add").eq(_index);
		var _this_values = _this.parent().parent();
		var _html = '<span style="float:left;padding-bottom:5px;"><input data-sku-id="'+_sku_id+'" class="text_z J_sku_'+(_index+1)+'_value" value="'+_sku_name+'"><a href="javascript:;" class="del_lsit nico J_set_sku_del" title="删除"><!--删除--></a></span>';
		_this_values.append(_html);
		admin_item_edit_att._listen_set_sku_del(_index);
		admin_item_edit_att._listen_set_sku();
	},
	_listen_set_sku_del:function(_index){//监听某个属性下删除按钮
		var _this = $(".J_set_sku").eq(_index).find('.J_set_sku_del');
		_this.unbind('click');
		_this.click(function(){
			var _del_index = _this.index(this);
			if(!confirm('该商品确定要减少一个属性值吗？执行后规格数据将无法恢复！如属性值只需修改名称，请不要继续此操作！')){
				return false;
			}
			
			_this.eq(_del_index).parent().remove();
			admin_item_edit_att._listen_set_sku_del(_index);
			
			var _temp_sku_1_del = _temp_sku_2_del = 9999999;
			var _sku_1_list = $(".J_sku_1_value");
			var _sku_2_list = $(".J_sku_2_value");
			if(_index==0){//第一种属性有被删除
				_temp_sku_1_del = _del_index+1;//第一种属性被删除的索引数
			}
			else{//第二种属性有被删除
				_temp_sku_2_del = _del_index+1;//第二种属性被删除的索引数
			}
			//重新整理数据
			admin_item_edit_att._json_temp_sku_data = new Object;
			for(var i=0;i<_sku_1_list.length;i++){
				if(_sku_2_list.length>0){
					for(var j=0;j<_sku_2_list.length;j++){                                                    
						var temp_i = (i < _temp_sku_1_del) ? i : (i+1);
						var temp_j = (j < _temp_sku_2_del) ? j : (j+1);
						var temp_key_1 = i+'_'+j;
						var temp_key_2 = temp_i+'_'+temp_j;
						var _current_sku_id = 'J_sku_list_'+temp_key_2;
						admin_item_edit_att._json_temp_sku_data[temp_key_1] = new Object;
						if(j==0){
							admin_item_edit_att._json_temp_sku_data[temp_key_1]['pic'] = $("#"+_current_sku_id+' .J_sku_pic').val();
						}
						admin_item_edit_att._json_temp_sku_data[temp_key_1]['price'] = $("#"+_current_sku_id+' .J_sku_price').val();
						admin_item_edit_att._json_temp_sku_data[temp_key_1]['stock'] = $("#"+_current_sku_id+' .J_sku_stock').val();
						admin_item_edit_att._json_temp_sku_data[temp_key_1]['sku_code'] = $("#"+_current_sku_id+' .J_sku_code').val();
						admin_item_edit_att._json_temp_sku_data[temp_key_1]['bar_code'] = $("#"+_current_sku_id+' .J_sku_bar_code').val();                                                       
					}
				}
				else{
					var j = 0;
					var temp_i = (i < _temp_sku_1_del) ? i : (i+1);
					var temp_key_1 = i+'_'+j;
					var temp_key_2 = temp_i+'_'+j;
					var _current_sku_id = 'J_sku_list_'+temp_key_2;
					admin_item_edit_att._json_temp_sku_data[temp_key_1] = new Object;
					admin_item_edit_att._json_temp_sku_data[temp_key_1]['pic'] = $("#"+_current_sku_id+' .J_sku_pic').val();
					admin_item_edit_att._json_temp_sku_data[temp_key_1]['price'] = $("#"+_current_sku_id+' .J_sku_price').val();
					admin_item_edit_att._json_temp_sku_data[temp_key_1]['stock'] = $("#"+_current_sku_id+' .J_sku_stock').val();
					admin_item_edit_att._json_temp_sku_data[temp_key_1]['sku_code'] = $("#"+_current_sku_id+' .J_sku_code').val();
					admin_item_edit_att._json_temp_sku_data[temp_key_1]['bar_code'] = $("#"+_current_sku_id+' .J_sku_bar_code').val();
				}
			}
			//console.log(admin_item_edit_att._json_temp_sku_data);
			$("#J_sku_list table").html('');
			admin_item_edit_att._make_sku_list();
			admin_item_edit._listen_sku_upload();
		});
		
	},
	_remove_set_sku_html:function(){//移除属性，只有无属性和单属性时进行移除
		if($(".J_att_type").eq(0).attr('checked')){
			$(".J_no_att_type").css('display','');
			$(".J_sku_title").css('display','none').val('');
			$(".J_sku_title").find('input').val('');
			$(".J_set_sku").remove();
			$("#J_sku_list").css('display','none');
			$("#J_sku_list input").attr('disabled',true);
		}
		else if($(".J_att_type").eq(1).attr('checked')){
			$("#J_sku_list input").attr('disabled',false);
			var _sku_1_list = $(".J_sku_1_value");
			var _sku_2_list = $(".J_sku_2_value");
			for(var i=0;i<_sku_1_list.length;i++){
				for(var j=1;j<_sku_2_list.length;j++){
					admin_item_edit_att._remove_sku_list_html(i,j);
				}
			}
			$(".J_sku_title").eq(0).css('display','');
			$(".J_sku_title").eq(1).css('display','none');
			$(".J_sku_title").eq(1).find('input').val('');
			$(".J_set_sku").eq(1).remove();
			$(".J_sku_list_title").eq(1).css('display','none');
			var _sku_list = $("#J_sku_list tr:gt(0)");
			for(var i=0;i<_sku_list.length;i++){
				if(_sku_list.eq(i).children('td').length==8){
					_sku_list.eq(i).children('td').eq(1).css('display','none');
				}
				else{
					_sku_list.eq(i).children('td').eq(0).css('display','none');	
				}
			}
			admin_item_edit_att._make_sku_list();
		}
		
	},
	_listen_set_sku:function(){//监听属性值变化
		var _sku_input = $('.J_set_sku').find('input');
		_sku_input.unbind('keyup');
		_sku_input.keyup(function(){
			admin_item_edit_att._make_sku_list();
			admin_item_edit._listen_sku_upload();
		});
	},
	_make_sku_list:function(bool_set_sku_data){//组合商品属性列表
		if(typeof(bool_set_sku_data)=='undefined'){
			bool_set_sku_data = false;	
		}
		$("#J_sku_list").css('display','');
		var _sku_1_list = $(".J_sku_1_value");
		var _sku_2_list = $(".J_sku_2_value");
		var _old_sku_list = $("#J_sku_list").find('tr');//当前已有的规格列表数据
		var temp = new Array();
		/*for(var i=1;i<_old_sku_list.length;i++){
			var _current_id = _old_sku_list.eq(i).attr('id').replace('J_sku_list_','');
			temp[i-1] = _current_id;
		}*/
		_old_sku_list = temp;
		var _html = '',_new_sku_list = new Array();
		for(var i=0;i<_sku_1_list.length;i++){
			if(_sku_2_list.length>0){
				for(var j=0;j<_sku_2_list.length;j++){
					//_new_sku_list[i+'_'+j] = 1;
					
					admin_item_edit_att._make_sku_list_html(i,j);
					if(bool_set_sku_data){
						admin_item_edit_att.set_sku_data(i,j);
					}
				}
			}
			else{
				var j = 0;
				//_new_sku_list[i+'_'+j] = 1;
				admin_item_edit_att._make_sku_list_html(i,j);
				if(bool_set_sku_data){
					admin_item_edit_att.set_sku_data(i,j);
				}
			}
		}
/*
		for(var i=0;i<_old_sku_list.length;i++){
			_current_id = _old_sku_list[i];
			if(typeof(_new_sku_list[_current_id])=='undefined'){
				temp = 	_current_id.split('_');
				admin_item_edit_att._remove_sku_list_html(temp[0],temp[1]);
			}
		}
*/			
	},
	set_sku_data:function(_sku_1_index,_sku_2_index){//写入商品sku数据
		if(typeof(admin_item_edit_att._json_att_type.item_sku)=='undefined'){
			return false;	
		}
		var _current_sku_id = 'J_sku_list_'+_sku_1_index+'_'+_sku_2_index;
		var _sku_1 = admin_item_edit_att._json_att_type.sku_1;
		var _sku_2 = admin_item_edit_att._json_att_type.sku_2;
		var _sku_1_id = _sku_1[_sku_1_index]['id'];
		if(_sku_2==''){
			var _sku_2_id = 0;
		}
		else{
			var _sku_2_id = _sku_2[_sku_2_index]['id'];	
		}
		var _item_sku = admin_item_edit_att._json_att_type.item_sku[_sku_1_id+'_'+_sku_2_id];
		
		if(_sku_2_index==0 && _item_sku.pic){//写入sku图片
			var _html = '<span style=" display:table;"><em style="display:table-cell;text-align:center;vertical-align:middle; height:40px;"><img width="40" src="'+_item_sku.pic+'"></em><input class="J_sku_pic" type="hidden" value="'+_item_sku.pic+'"></span>';
			$(".J_sku_pic_list").eq(_sku_1_index).html(_html);
		}
		$("#"+_current_sku_id+' .J_sku_price').val(_item_sku.price);
		$("#"+_current_sku_id+' .J_sku_sales_price').val(_item_sku.sales_price);
		$("#"+_current_sku_id+' .J_sku_stock').val(_item_sku.stock);
		$("#"+_current_sku_id+' .J_sku_free_buy_stock').val(_item_sku.free_buy_stock);
		$("#"+_current_sku_id+' .J_sku_code').val(_item_sku.sku_code);
		$("#"+_current_sku_id+' .J_sku_bar_code').val(_item_sku.bar_code);
	},
	_make_sku_list_html:function(_sku_1_index,_sku_2_index){//生成产品属性列表的HTML
		var _current_sku_id = 'J_sku_list_'+_sku_1_index+'_'+_sku_2_index;
		var _current_sku_1 = $(".J_sku_1_value").eq(_sku_1_index);
		var _current_sku_2 = $(".J_sku_2_value").eq(_sku_2_index);
		
		var _table_list = $("#J_sku_list").find('table');
		var _sku_title = $(".J_sku_title");
		var _sku_title_1 = _sku_title.eq(0).find('input').val(),_sku_title_2 = _sku_title.eq(1).find('input').val();;
		var _html = '';
		if(_table_list.html()==''){//初始化列表标题HTML
			_html = '<tr>'+
						'<th class="J_sku_list_title"></th>'+
						'<th class="J_sku_list_title"></th>'+
						'<th class="width_a">价格(元)</th>'+
						//'<th class="width_a">活动价格(元)</th>'+
						//'<th class="width_a">数量(件)</th>'+
						//'<th class="width_a">免单数量(件)</th>'+
						//'<th class="width_b">规格编码</th>'+
						//'<th class="width_b">条形码</th>'+
					'</tr>';
			_table_list.append(_html);
		}
		/*开始列表标题属性名写入*/
		$(".J_sku_list_title").eq(0).html(_sku_title_1);
		$(".J_sku_list_title").eq(1).html(_sku_title_2).css('display','');	
		/*结束列表标题属性名写入*/
		/*开始按行写入商品sku信息*/
		if($("#"+_current_sku_id).length==0){//不存在时进行生成
			_html = '<tr id="'+_current_sku_id+'">';
			if(_sku_2_index==0){//写入的是带图片的
				_html += '<td>'+
							'<div style="position:relative;">'+
								'<a href="javascript:;" class="img_upload nico J_sku_pic_list"><input class="J_sku_pic" type="hidden" value=""></a>'+
								'<input type="file" accept="image/*" name="pic" style="position:absolute;left:0;top:0;width:300px;height:40px;filter:alpha(opacity=0);-moz-opacity:0;opacity:0;cursor:pointer;" class="J_sku_pic_upload_btn" accept="image/*">'+
							'</div>'+
							'<span class="J_sku_list_title_1"></span>'+
						'</td>';
			}
			_html+= '<td class="J_sku_list_title_'+_sku_1_index+'_2"></td>'+
					'<td><input class="text_a J_sku_price" name="sku_price[]"></td>'+
					//'<td><input class="text_a J_sku_sales_price" name="sku_sales_price[]"></td>'+
					//'<td><input class="text_a J_sku_stock" name="sku_stock[]"></td>'+
					//'<td><input class="text_a J_sku_free_buy_stock" name="sku_free_buy_stock[]"></td>'+
					//'<td><input class="text_b J_sku_code" name="sku_code[]"></td>'+
					//'<td><input class="text_b J_sku_bar_code" name="sku_bar_code[]"></td>'+
				'</tr>';
			if(_sku_2_index==0){
				_table_list.append(_html);	
			}
			else{
				$(_html).insertAfter('#J_sku_list_'+_sku_1_index+'_'+(_sku_2_index-1));
			}
			var _price = $("#J_price").val();//一口价
			$("#"+_current_sku_id+' .J_sku_price').val(_price);
			var _price = $("#J_sales_price").val();//活动价
			$("#"+_current_sku_id+' .J_sku_sales_price').val(_price);
		}
		if(_sku_2_index==0){//重新计算列的合并数量
			var _rowspan_num = $(".J_sku_2_value").length;
			_rowspan_num = _rowspan_num ? _rowspan_num : 1;
			$("#"+_current_sku_id).children('td').eq(0).attr('rowspan',_rowspan_num);
		}
		var _item_sku = admin_item_edit_att._json_temp_sku_data[_sku_1_index+'_'+_sku_2_index];
		if(typeof(_item_sku)!='undefined'){
			if(_sku_2_index==0 && _item_sku.pic){//写入sku图片
				var _html = '<span style=" display:table;"><em style="display:table-cell;text-align:center;vertical-align:middle; height:40px;"><img src="'+resize_img(_item_sku.pic,40)+'"></em><input class="J_sku_pic" type="hidden" value="'+_item_sku.pic+'"></span>';
				$(".J_sku_pic_list").eq(_sku_1_index).html(_html);
			}
			$("#"+_current_sku_id+' .J_sku_price').val(_item_sku.price);
			$("#"+_current_sku_id+' .J_sku_sales_price').val(_item_sku.sales_price);
			$("#"+_current_sku_id+' .J_sku_stock').val(_item_sku.stock);
			$("#"+_current_sku_id+' .J_sku_free_buy_stock').val(_item_sku.free_buy_stock);
			$("#"+_current_sku_id+' .J_sku_code').val(_item_sku.sku_code);
			$("#"+_current_sku_id+' .J_sku_bar_code').val(_item_sku.bar_code);
		}
		/*结束按行写入商品sku信息*/
		/*开始写入商品sku名*/
		$("#"+_current_sku_id+' .J_sku_list_title_1').html(_current_sku_1.val());
		$("#"+_current_sku_id+' .J_sku_list_title_'+_sku_1_index+'_2').html(_current_sku_2.val());
		/*结束写入商品sku名*/
		/*开始单属性和双属性判断*/
		if(_sku_title.eq(1).css('display')=='none'){
			$(".J_sku_list_title").eq(1).css('display','none');
			$("#"+_current_sku_id+' .J_sku_list_title_'+_sku_1_index+'_2').css('display','none');
		}
		else{
			$(".J_sku_list_title").eq(1).css('display','');
			$("#"+_current_sku_id+' .J_sku_list_title_'+_sku_1_index+'_2').css('display','');
		}
		/*结束单属性和双属性判断*/
		return true;
	},
	_remove_sku_list_html:function(_sku_1_index,_sku_2_index){//删除属性列表的某一行
		var _current_sku_id = 'J_sku_list_'+_sku_1_index+'_'+_sku_2_index;
		if($("#"+_current_sku_id).length<=0){
			return false;	
		}
		$("#"+_current_sku_id).remove();
	}
};

var admin_item_edit_verify = {
	_verify:function(){
		var c = admin_item_edit_verify;
		$('.J_data_format').remove();
		
		var obj = $('#J_language');
		if(obj.length>0){
			if(obj.val()<=0){
				make_alert_html(arr_language['warn'],arr_language['error_2']);
				return false;
			}
		}

		var obj_cid_1 = $('#J_cid_1');
		if(obj_cid_1.val()<=0){
			make_alert_html(arr_language['warn'],arr_language['mall']['item']['error_1']);
			return false;
		}
		
		var obj_cid_2 = $('#J_cid_2');
		if(obj_cid_2.css('display')!='none'){
			if(obj_cid_2.val()<=0){
				make_alert_html(arr_language['warn'],arr_language['mall']['item']['error_1']);
				return false;
			}
		}
		
		var obj_title = $('#J_title');
		if(!obj_title.val()){
			make_alert_html(arr_language['warn'],arr_language['mall']['item']['error_2']);
			return false;
		}
		
		var obj_price = $('#J_price');
		
		if(!obj_price.val() || parseFloat(obj_price.val())<=0){
			make_alert_html(arr_language['warn'],arr_language['mall']['item']['error_5']);
			return false;
		}
		var obj = $('.J_has_pic');
		if(obj.length<=0){
			make_alert_html(arr_language['warn'],arr_language['mall']['item']['error_3']);
			return false;
		}
		var att_type = $(".J_att_type");
		var att_type_val = 0;
		for(var i=0;i<att_type.length;i++){
			var ci = att_type.eq(i);
			if(ci.attr('checked')){
				att_type_val = ci.val();
				break;
			}
		}

		if(att_type_val>0){
			var arr_temp = new Array();
			if(!(arr_temp = admin_item_edit_verify._verify_sku_value(1))){//判断第一级属性值是否合法
				return false;
			}
			if(att_type_val==2 && !admin_item_edit_verify._verify_sku_value(2,arr_temp)){//判断第二级属性值是否合法
				return false;
			}
			if(!admin_item_edit_verify._verify_sku_info()){
				return false;	
			}
		}
		return true;
	},
	_append_input_hidden:function(_input_name,_input_value){
		var _html = '<input class="J_data_format" type="hidden" name="'+_input_name+'" value="'+_input_value+'">';
		$("#J_send_form").append(_html);
	},
	_verify_sku_info:function(){//检查商品规格列表信息
		var sku_price = $(".J_sku_price");
		for(var i=0;i<sku_price.length;i++){
			var ci = sku_price.eq(i);
			var current_sku_list_id = ci.parent().parent().attr('id');
			var _sku_name = admin_item_edit_verify._make_sku_name(current_sku_list_id);
			if($.trim(ci.val())==''){
				make_alert_html(arr_language['notice'],arr_language['mall']['item']['error_7'].replace('$s$',_sku_name));
				return false;
			}
			else if(!ci.val().match(/^[\d]+(|\.[\d]+)$/)){
				make_alert_html(arr_language['notice'],arr_language['mall']['item']['error_7'].replace('$s$',_sku_name));
				return false;
			}
		}
		/*
		var sku_stock = $(".J_sku_stock");
		for(var i=0;i<sku_stock.length;i++){
			var ci = sku_stock.eq(i);
			var current_sku_list_id = ci.parent().parent().attr('id');
			var _sku_name = admin_item_edit_verify._make_sku_name(current_sku_list_id);
			if($.trim(ci.val())==''){
				make_alert_html('系统提示','请填写'+_sku_name+'的商品库存');
				return false;
			}
			else if(!ci.val().match(/^[\d]+$/)){
				make_alert_html('系统提示',_sku_name+'的商品库存只能是纯数字');	
				return false;
			}
		}
		
		var arr_temp = new Array();
		var sku_code = $(".J_sku_code");
		for(var i=0;i<sku_code.length;i++){
			var ci = sku_code.eq(i);
			var current_sku_list_id = ci.parent().parent().attr('id');
			var _sku_name = admin_item_edit_verify._make_sku_name(current_sku_list_id);
			if($.trim(ci.val())==''){
				make_alert_html('系统提示','请填写'+_sku_name+'的规格编码');
				return false;
			}
			else if(!ci.val().match(/^[a-zA-Z\d]+$/)){
				make_alert_html('系统提示',_sku_name+'的规格编码只能包含数字和字母');
				return false;
			}
			else if(ci.val()!=0 && typeof(arr_temp[ci.val()])!='undefined'){
				make_alert_html('提示','【'+ci.val()+'】规格编码不能重复');
				return false;	
			}
			arr_temp[ci.val()] = 1;
		}
		var arr_temp = new Array();
		var sku_bar_code = $(".J_sku_bar_code");
		for(var i=0;i<sku_bar_code.length;i++){
			var ci = sku_bar_code.eq(i);
			var current_sku_list_id = ci.parent().parent().attr('id');
			var _sku_name = admin_item_edit_verify._make_sku_name(current_sku_list_id);
			if($.trim(ci.val())==''){
				make_alert_html('系统提示','请填写'+_sku_name+'的条形码');
				return false;
			}
			else if(ci.val()!=0 && typeof(arr_temp[ci.val()])!='undefined'){
				make_alert_html('提示','【'+ci.val()+'】条形码不能重复');
				return false;	
			}
			arr_temp[ci.val()] = 1;
		}
		*/
		return true;
	},
	_verify_sku_value:function(_index,arr_temp){
		var sku_value = $(".J_sku_"+_index+"_value");
		if(typeof(arr_temp)=='undefined'){
			var arr_temp = new Array();
		}
		for(var i=0;i<sku_value.length;i++){
			var ci = sku_value.eq(i);
			if($.trim(ci.val())==''){
				make_alert_html(arr_language['notice'],arr_language['mall']['item']['error_9']);
				return false;	
			}
			else if(ci.val().split('|').length>1){
				make_alert_html(arr_language['notice'],arr_language['mall']['item']['error_10'].replace('$s$',ci.val()));
				return false;
			}
			else if(typeof(arr_temp[ci.val()])!='undefined'){
				make_alert_html(arr_language['notice'],arr_language['mall']['item']['error_11'].replace('$s$',ci.val()));
				return false;	
			}
			arr_temp[ci.val()] = 1;
		}
		return arr_temp;
	},
	_make_sku_name:function(sku_list_id){//获得当前sku的名称
		var sku_list_id = sku_list_id.split('_');
		var sku_1 = sku_list_id[3];
		var sku_2 = sku_list_id[4];
		var _name = '';
		var sku_1_value = $(".J_sku_1_value");
		var sku_2_value = $(".J_sku_2_value");
		_name = sku_1_value.eq(sku_1).val();
		if(sku_2_value.length==0){
			return _name;
		}
		
		_name += '-'+sku_2_value.eq(sku_2).val();
		return _name;
	},
	_data_format:function(){//整理提交的数据
		$(".J_data_format").remove();
		var att_type = $(".J_att_type");
		var att_type_val = 0;
		for(var i=0;i<att_type.length;i++){
			var ci = att_type.eq(i);
			if(ci.attr('checked')){
				att_type_val = ci.val();
				break;
			}
		}
		if(att_type_val==0){
			admin_item_edit_verify._append_input_hidden('sku_1[]','');
			admin_item_edit_verify._append_input_hidden('sku_2[]','');
			admin_item_edit_verify._append_input_hidden('sku_price[]',$("#J_price").val());
			admin_item_edit_verify._append_input_hidden('sku_sales_price[]',$("#J_sales_price").val());
			admin_item_edit_verify._append_input_hidden('sku_stock[]',$("#J_stock").val());
			admin_item_edit_verify._append_input_hidden('sku_free_buy_stock[]',$("#J_free_buy_stock").val());
			admin_item_edit_verify._append_input_hidden('sku_code[]',$("#J_item_code").val());
			admin_item_edit_verify._append_input_hidden('sku_bar_code[]',$("#J_bar_code").val());
			return false;
		}
		
		var _sku_1_list = $(".J_sku_1_value");
		var _sku_2_list = $(".J_sku_2_value");
		for(var i=0;i<_sku_1_list.length;i++){
			if(_sku_2_list.length>0){
				for(var j=0;j<_sku_2_list.length;j++){
					admin_item_edit_verify.sku_data_format(i,j);
				}
			}
			else{
				var j = 0;
				admin_item_edit_verify.sku_data_format(i,j);
			}
		}
		
	},
	sku_data_format:function(sku_1,sku_2){//整理sku列表的数据
		var _sku_1_list = $(".J_sku_1_value");
		var _sku_2_list = $(".J_sku_2_value");
		var _value = _sku_1_list.eq(sku_1).val();
		var _value2 = _sku_1_list.eq(sku_1).attr('data-sku-id');
		if(_value2>0){
			_value = _value2+'|'+_value;
		}
		admin_item_edit_verify._append_input_hidden('sku_1[]',_value);
		if(_sku_2_list.length>0){
			_value = _sku_2_list.eq(sku_2).val();
			_value2 = _sku_2_list.eq(sku_2).attr('data-sku-id');
			if(_value2>0){
				_value = _value2+'|'+_value;
			}
			admin_item_edit_verify._append_input_hidden('sku_2[]',_value);
		}
		else{
			admin_item_edit_verify._append_input_hidden('sku_2[]','');	
		}
		var _current_sku_pic = $(".J_sku_pic").eq(sku_1);
		if(_current_sku_pic.length==1){
			_value = _current_sku_pic.val();	
		}
		else{
			_value = '';	
		}
		admin_item_edit_verify._append_input_hidden('sku_pic[]',_value);
		return true;
		
	}
}

var admin_item_search = {
	_init:function(){
		admin_item_search._listen_cat();	
	},
	_listen_cat:function(){
		var obj_cid_1 = $('#J_cid_1');
		obj_cid_1.change(function(){
						var _this = $(this);
						var int_cid = _this.val();
						var str_url = window.location.href.replace(/\&(cid_1|cid_2|cid_3)=[\d]+/g,'');
						window.location.href = str_url+'&cid_1='+int_cid;
					});	
		var obj_cid_2 = $('#J_cid_2');
		obj_cid_2.change(function(){
						var _this = $(this);
						var int_cid = _this.val();
						var str_url = window.location.href.replace(/\&(cid_2|cid_3)=[\d]+/g,'');
						window.location.href = str_url+'&cid_2='+int_cid;
					});	
		var obj_language = $('#J_language');
		obj_language.change(function(){
						var _this = $(this);
						var str_language = _this.val();
						var str_url = window.location.href.replace(/\&language=[a-z_]+/,'');
						window.location.href = str_url+'&language='+str_language;
					});	
	}
}

var admin_item_pics = {
	uploading:false,
		pic_max_num:5,//商品图片最大上传数量
		_init:function(){
        	var c = admin_item_pics;
			c._listen_pic_upload_btn();
			/*如果有初始化有商品图片html*/
			var _pic_list = $("#J_pic_list input"),_html='';
			for(var i=0;i<_pic_list.length;i++){
				var _pic_source = _pic_list.eq(i).val();
				var pid = _pic_list.eq(i).attr('data-pid');
				_html = c.make_pic_html(i,_pic_source,pid);
				$("#J_pic_list").children('li').eq(i).html(_html);
				if(i>=c.pic_max_num-1){
					$("#J_pic_upload").css('display','none');	
				}
			}
			c._listen_move_del_pic();
		},
		_listen_pic_upload_btn:function(){//监听商品图片上传按钮
        	var c = admin_item_pics;
			$("#J_pic_upload_btn").change(function(){
				if(c.uploading){
					make_alert_html(arr_language['warn'],arr_language['mall']['item']['error_12']);
					return false;
				}
				$("#J_do").val('upload_image');		
				setTimeout(function(){
					_ajax_frame(get_obj('ajaxframeid'),c._pic_upload_success,'send_success');
					get_obj('J_send_form').submit();			
				},10);
				$(".J_sku_pic_upload_btn").attr('disabled',true);
				c.uploading = true;
				$('#J_form_do').val('upload');
				$("#J_pic_upload_status").val(arr_language['upload']['uploading']);
			});
		},
		_pic_upload_success:function(_data){//商品图片上传成功
        	var c = admin_item_pics;
			$('#J_form_do').val('');
			$(".J_sku_pic_upload_btn").attr('disabled',false);
			c.uploading = false;
			$("#J_pic_upload_status").val(arr_language['upload']['upload']);
			_remove_ajax_frame('send_success');
			if(_data.status==200){
				var pic_list = $("#J_pic_list").children('li');
				var pic_index = $(".J_has_pic").length;//当前图片的位置，从0开始
				
				pic_list.eq(pic_index).html(c.make_pic_html(pic_index,_data.data));
				if(pic_index>=c.pic_max_num-1){
					$("#J_pic_upload").css('display','none');	
				}
				c._reset_listen_move_del_pic();
			}else{
				make_alert_html(arr_language['warn'],arr_language['error_1']);
			}
			
		},
		make_pic_html:function(_index,_pic_source){//组合商品图片html
        	var c = admin_item_pics;
			var _html = _html_move_del_btn = left_btn_style = right_btn_style = '';
			if(_index==0){
				left_btn_style = ' style="display:none;"';
			}
			if(_index==4){
				right_btn_style = ' style="display:none;"';
			}
			
			_html_move_del_btn = '<i'+left_btn_style+' class="J_move_pic_left nico g_l" title="向左移"><!--左--></i>';
			_html_move_del_btn += '<i'+right_btn_style+' class="J_move_pic_right nico g_r" title="向右移"><!--右--></i>';
			_html_move_del_btn += '<i class="J_del_pic nico g_c" title="删除"><!--删除--></i>';
			
			var _html = '<div class="goods_nav J_has_pic">';
				_html += _html_move_del_btn+'</div>';
				_html += '<span><img src="'+_pic_source+'"></span>';
				_html += '<input type="hidden" name="pic[]" value="'+_pic_source+'">';
				
			return _html;
		},
		_listen_move_del_pic:function(){//监听图片移动和删除按钮
        	var c = admin_item_pics;
			$(".J_move_pic_left").click(function(){
				var _index = $(".J_move_pic_left").index(this);
				c._move_pic(_index,'left');									
			});
			$(".J_move_pic_right").click(function(){
				var _index = $(".J_move_pic_right").index(this);
				c._move_pic(_index,'right');											
			});
			
			$(".J_del_pic").click(function(){
				var _index = $(".J_del_pic").index(this);
				c._del_pic(_index);										
			});
		},
		_remove_listen_move_del_pic:function(){//去除监听图片移动和删除按钮
        	var c = admin_item_pics;
			$(".J_move_pic_left").unbind('click');
			$(".J_move_pic_right").unbind('click');
			$(".J_del_pic").unbind('click');
		},
		_reset_listen_move_del_pic:function(){//重置所有监听图片移动和删除按钮
        	var c = admin_item_pics;
			c._remove_listen_move_del_pic();
			c._listen_move_del_pic();
			var _pic_list = $(".J_has_pic");
			_pic_list.children('.J_move_pic_left').css('display','');
			_pic_list.children('.J_move_pic_right').css('display','');
			_pic_list.eq(0).children('.J_move_pic_left').css('display','none');
			_pic_list.eq((_pic_list.length-1)).children('.J_move_pic_right').css('display','none');
		},
		_move_pic:function(_index,_do){//移动图片_do = left or right
        	var c = admin_item_pics;
			var _this = $("#J_pic_list").children('li').eq(_index);
			if(_do=='left'){
				var _other = $("#J_pic_list").children('li').eq(_index-1);
				_other.before(_this);
			}
			else if(_do=='right'){
				var _other = $("#J_pic_list").children('li').eq(_index+1);	
				_this.before(_other);
			}
			c._reset_listen_move_del_pic();
		},
		_del_pic:function(_index){
        	var c = admin_item_pics;
			if(!confirm(arr_language['del_2'])){
				return false;	
			}
			c._remove_listen_move_del_pic();//在节点移除之前清除所有监听
			var _this = $("#J_pic_list").children('li').eq(_index);
			var _data = new Object();
			_data.filename = _this.children('input').val();
			_data.pid = _this.children('input').attr('data-pid');
			_data.item_id = $("#J_item_id").val();
			_this.remove();
			if($(".J_has_pic").length==0){//已经没有图片了
				$("#J_pic_list").children('li').eq(0).before('<li><font><em>*</em> '+arr_language['mall']['main_pic']+'</font></li>');
			}
			else{
				$("#J_pic_list").append('<li></li>');
			}
			$("#J_pic_upload").css('display','');
			c._reset_listen_move_del_pic();
		}
};