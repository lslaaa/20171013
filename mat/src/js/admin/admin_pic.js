var admin_pic = {
	_init:function(){
		var c = admin_pic;
		c._listen();	
		c._listen_cat();
		c._listen_upload();
		c._listen_pic_add();
		c._listen_pic_del();
	},
	_listen:function(){
		var c = admin_pic;
		
		$('#J_send_form').submit(function(){
							if($('#J_form_do').val()=='upload'){
								return true;	
							}
							
							var obj = $('#J_language');
							if(obj.length>0){
								if(obj.val()<=0){
									make_alert_html(arr_language['warn'],arr_language['error_2']);
									return false;
								}
							}
							
							var obj_cat = $('#J_cid');
							if(obj_cat.css('display')!='none'){
								var obj_cid = $('#J_cid');
								if(obj_cid.val()<=0){
									make_alert_html(arr_language['warn'],arr_language['pics']['error_1']);
									return false;	
								}
								
								var obj_cid_2 = $('#J_cid_2');
								if(obj_cid_2.css('display')!='none' && obj_cid_2.val()<=0){
									make_alert_html(arr_language['warn'],arr_language['pics']['error_1']);
									return false;	
								}
								
								var obj = $('#J_cid_3');
								if(obj.css('display')!='none' && obj.val()<=0){
									make_alert_html(arr_language['warn'],arr_language['pics']['error_1']);
									return false;
								}
							}
							
							var obj_title = $('#J_title');
							if(!obj_title.val()){
								make_alert_html(arr_language['warn'],arr_language['pics']['error_2']);
								return false;	
							}
							
							var obj = $('.J_pic_url').eq(0);
							if(!obj.val()){
								make_alert_html(arr_language['warn'],arr_language['pics']['error_3']);
								return false;	
							}
							
							if(c.bool_sending){
								return false;	
							}
							$('#J_form_do').val('add');
							c.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),c.send_success,'send_success');			  
						});
	},
	_listen_upload:function(){
		var c = admin_pic;
		$('.J_pic').unbind('change');
		$('.J_pic').change(function(){
							var _index = $('.J_pic').index(this);
							c.upload_index = _index;
							$('#J_form_do').val('upload');		
							if(c.bool_sending){
								return false;	
							}
							$('.J_pic').attr('disabled',true);
							$('.J_pic').eq(_index).attr('disabled',false);
							c.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),c.upload_success,'send_success');
							$('#J_send_form').submit();
						});	
	},
	upload_success:function(_data){
		var c = admin_pic;
		var _index = c.upload_index;
		$('.J_pic').attr('disabled',false);
		_remove_ajax_frame('upload_success');
		$('#J_form_do').val('');
		if(_data['status']!=200){
			c.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;
		}
		c.bool_sending = false;
		$('.J_pic_show').eq(_index).html('<img src="'+_data['data']+'" style="width:100px">');
		$('.J_pic_url').eq(_index).val(_data['data']);
	},
	_listen_pic_add:function(){
		var c = admin_pic;
		$('#J_add_pic').click(function(){
							var _html = c._make_pic_html();
							
							var obj = $('.J_pic_box').last();
							
							$(_html).insertAfter(obj);
							c._listen_upload();
							c._listen_pic_del();
						});
	},
	_listen_pic_del:function(){
		var c = admin_pic;
		var obj_del = $('.J_del_pic');
		obj_del.unbind('click');
		obj_del.click(function(){
							var _index = obj_del.index(this)+1;
							c.del_pic_index = _index;
							confirm_cancle(c._pic_del,arr_language['warn'],arr_language['del_2']);
							
						});
			
	},
	_pic_del:function(){
		var c = admin_pic;
		var _index = c.del_pic_index;
		$('.J_pic_box').eq(_index).remove();
		c._listen_upload();
		c._listen_pic_del();	
	},
	_make_pic_html:function(){
		var _html = '<dl class="J_pic_box mt12">'+
						'<dt>&nbsp;</dt>'+
						'<dd style="position:relative;" id="J_img_add_dd">'+
							'<input type="hidden" name="pic_url[]" value="" class="J_pic_url" style="float:left;"/>'+
							'<input name="pic" class="J_pic" type="file" style="filter:alpha(opacity=0);-moz-opacity:0;opacity:0;width:100px;height:36px;position:absolute;left:0;top:0;cursor: pointer;float:left;">'+
							'<span><a href="javascript:;" class="J_del_pic del_lsit nico" title="É¾³ý"><!--É¾³ý--></a></span>'+
							'<a class="upload_pic f_l" href="javascript:;">'+arr_language['upload']['upload_2']+'</a>'+
							'<div class="up_pic_box">'+
								'<span class="J_pic_show"></span>'+
								'<div style="overflow:hidden; height:0; clear:both;"></div>'+
								'<input type="text" name="pic_des[]" class="J_pic_des text_s" autocomplete="off" value="" style="width:400px;">'+
							'</div>'+
						'</dd>'+
					'</dl>';
		return _html;
	},
	_listen_cat:function(){
		var c = admin_pic;
		var obj_cid = $('#J_cid');
		obj_cid.change(function(){
						var _this = $(this);
						var int_pid = _this.val();
						$('#J_cid_2').css('display','none');
						$('#J_cid_3').css('display','none');
						c._cat_select(int_pid);
					});
		if(obj_cid.attr('data-cid')>0){
			var int_pid = obj_cid.attr('data-cid');
			var obj_options = obj_cid.children();
			for(var i=0;i<obj_options.length;i++){
				var ci = obj_options.eq(i);
				if(ci.val()==int_pid){
					ci.attr('selected',true);
					break;
				}
			}
			c._cat_select(obj_cid.attr('data-cid'));	
		}		
	},
	_listen_cat_2:function(){
		var c = admin_pic;
		var obj_cid = $('#J_cid_2');
		obj_cid.unbind('change');
		obj_cid.change(function(){
						var _this = $(this);
						var int_pid = _this.val();
						c._cat_select_2(int_pid);
					});
		if(obj_cid.attr('data-cid')>0){
			var int_pid = obj_cid.attr('data-cid');
			var obj_options = obj_cid.children();
			for(var i=0;i<obj_options.length;i++){
				var ci = obj_options.eq(i);
				if(ci.val()==int_pid){
					ci.attr('selected',true);
					break;
				}
			}
			c._cat_select_2(obj_cid.attr('data-cid'));
		}
	},
	_cat_select:function(int_pid){
		var _html = '';
		var c = admin_pic;
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
		c._listen_cat_2();
	},
	_cat_select_2:function(int_pid){
		var _html = '';
		var c = admin_pic;
		if(int_pid>0){
			var int_cid_3 = $('#J_cid_3').attr('data-cid');
			for(var ci,i=0;ci=c.json_cat[i++];){
				if(ci['pid']==int_pid){
					var str_selected = int_cid_3==ci['cid'] ? ' selected="selected"' : '';
					_html += '<option value="'+ci['cid']+'"'+str_selected+'>'+ci['name']+'</option>';
				}
			}
		}
		if(_html){
			_html = '<option value="0">'+arr_language['select']+'</option>'+_html;
			$('#J_cid_3').css('display','');
		}
		else{
			$('#J_cid_3').css('display','none');
		}
		$('#J_cid_3').html(_html);
	},
	_listen_del:function(){
		var c = admin_pic;
		$('.J_btn_del').click(function(){
								var _this = $(this);
								confirm_cancle(c._del,arr_language['warn'],arr_language['del_2']);
								c._this = _this;
							});
	},
	_del:function(){
		var c = admin_pic;
		var _this = admin_pic._this;
		var int_id = _this.attr('data-id');
		_ajax_jsonp('/admin.php?mod=pic&mod_dir=pic&extra=del&id='+int_id,c._del_success);
	},
	_del_success:function(_data){
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);	
	},
	send_success:function(_data){
		var c = admin_pic;
		_remove_ajax_frame('send_success');
		//console.log(_data);
		if(_data['status']!=200){
			c.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}
		make_alert_html(arr_language['notice'],_data['info'],'/admin.php?mod=pic&mod_dir=pic');
	}
}

var admin_pic_search = {
	_init:function(){
		admin_pic_search._listen_cat();	
	},
	_listen_cat:function(){
		var obj_cid_1 = $('#J_cid');
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
		var obj_cid_3 = $('#J_cid_3');
		obj_cid_3.change(function(){
						var _this = $(this);
						var int_cid = _this.val();
						var str_url = window.location.href.replace(/\&cid_3=[\d]+/,'');
						window.location.href = str_url+'&cid_3='+int_cid;
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