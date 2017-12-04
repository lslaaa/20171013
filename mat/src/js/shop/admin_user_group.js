var admin_user_group = {
	_this:'',
	_init:function(){
		admin_user_group._listen();	
	},
	_listen:function(){
		$('#J_send_form').submit(function(){
							var obj_name = $('#J_group_name');
							if(!obj_name.val()){
								make_alert_html(arr_language['warn'],arr_language['admin_user_group']['error_1']);
								return false;
							}
							
							if(admin_user_group.bool_sending){
								return false;	
							}
							admin_user_group.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),admin_user_group.send_success,'send_success');			  
						});
	},
	_listen_del:function(){
		$('.J_btn_del').click(function(){
								var _this = $(this);
								confirm_cancle(admin_user_group._del,arr_language['warn'],arr_language['del_2']);
								admin_user_group._this = _this;
							});
	},
	_del:function(){
		var _this = admin_user_group._this;
		var int_gid = _this.attr('data-gid');
		_ajax_jsonp('/shop.php?mod=user_group&extra=del&gid='+int_gid,admin_user_group._del_success);
	},
	_del_success:function(_data){
		make_alert_html(arr_language['notice'],_data['info'],window.location.href);	
	},
	send_success:function(_data){
		_remove_ajax_frame('send_success');
		//console.log(_data);
		if(_data['status']!=200){
			admin_user_group.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}
		make_alert_html(arr_language['notice'],_data['info'],'/shop.php?mod=user_group');
	}
}

var admin_user_group_permission = {
	_init:function(){
		var c = admin_user_group_permission;
		c._listen();
		c._listen_select();
	},
	_listen_select:function(){
		$(".J_menu_checkbox").click(function(){
			var _index = $(".J_menu_checkbox").index(this);
			var obj_current = $(".J_menu_checkbox").eq(_index);
			var temp = obj_current.attr('id').replace('J_menu_','').split('_');
			var bool_current_checked = obj_current.attr('checked');
			var current_level = temp[1];
			
			if(current_level==1){
				if(bool_current_checked){//ѡ��
					obj_current.parent().parent().find('input').attr('checked',true);
				}
				else{//ȡ��
					obj_current.parent().parent().find('input').attr('checked',false);
				}
			}
			else if(current_level==2){
				if(bool_current_checked){//ѡ��
					obj_current.parent().parent().find('dt input').attr('checked',true);//ѡ��1��
					if(obj_current.parent().next('h2')){
						obj_current.parent().next('h2').find('input').attr('checked',true);//ѡ������3��
					}
					
				}
				else{//ȡ��
					if(obj_current.parent().next().is('h2')){
						obj_current.parent().next().find('input').attr('checked',false);//ȡ������3��ѡ��
					}
					var temp = obj_current.parent().parent().find('.J_menu_checkbox_2:checked').length;//��ǰѡ�е�2���˵�����
					if(temp<=0){
						obj_current.parent().parent().find('dt input').attr('checked',false);//ȡ��ѡ��һ���˵�
					}
				}
			}
			else if(current_level==3){
				if(bool_current_checked){//ѡ��
					obj_current.parent().parent().parent().find('dt input').attr('checked',true);//ѡ��1��
					obj_current.parent().parent().prev().find('input').attr('checked',true);//ѡ��2��
				}
				else{//ȡ��
					var temp = obj_current.parent().parent().find('input:checked').length;//����3��ѡ�еĸ���
					if(temp<=0){
						obj_current.parent().parent().prev().find('input').attr('checked',false);//ȡ��ѡ��2��
					}
					var temp = obj_current.parent().parent().find('input:checked').length;//��ǰѡ�е�2���˵�����
					if(temp<=0){
						obj_current.parent().parent().parent().find('dt input').attr('checked',false);//ȡ��ѡ��һ���˵�
					}
				}
			}
		});	
	},
	_listen:function(){
		var c = admin_user_group_permission;
		$('#J_send_form').submit(function(){
							if(c.bool_sending){
								return false;	
							}
							c.bool_sending = true;
							_ajax_frame(get_obj('ajaxframeid'),admin_user_group.send_success,'send_success');			  
						});
	},
	send_success:function(_data){
		var c = admin_user_group_permission;
		_remove_ajax_frame('send_success');
		//console.log(_data);
		if(_data['status']!=200){
			c.bool_sending = false;
			make_alert_html(arr_language['warn'],_data['info']);
			return false;	
		}
		make_alert_html(arr_language['notice'],_data['info'],'/shop.php?mod=user_group');
	}
};