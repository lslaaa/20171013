<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_member {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
	}

	function extra_index(){
		global $_SGLOBAL;
		$int_page = intval($_GET['page']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 20;
		$obj_member = L::loadClass('admin_member','admin');
		$arr_data = array('is_del'=>0);
		$arr_list = $obj_member->get_list($arr_data,$int_page,$int_page_size);
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		
		$int_show_member_group = get_config('show_admin_member_group');
		$int_show_member_group = $int_show_member_group['val'];
		if($int_show_member_group){
			$obj_member_group = L::loadClass('admin_member_group','admin');
			$arr_data = array('is_del'=>0);
			$arr_groups = $obj_member_group->get_list($arr_data,1,20);
			$arr_groups = format_array_val_to_key($arr_groups['list'],'gid');
			//var_export($arr_groups);
		}
		
		$arr_language = $_SGLOBAL['language'];
		//var_export($arr_language);
		include template('template/admin/setting/member/index');
	}
	
	function extra_add(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_uid = intval($_GET['uid']);
		$obj_member = L::loadClass('admin_member','admin');
		if(submit_check('formhash')){
			$int_uid      = intval($_POST['uid']);
			$int_gid      = intval($_POST['gid']);
			$str_username = str_addslashes($_POST['name']);
			$str_password = str_addslashes($_POST['password']);
			$str_realname = str_addslashes($_POST['realname']);
			
			if(empty($str_username) || empty($str_realname)){
				callback(array('info'=>$arr_language['admin_member']['error_1'],'status'=>304));
			}
			
			$arr_data = array(
							'gid'=>$int_gid,
							'username'=>$str_username,
							'password'=>$str_password,
							'realname'=>$str_realname,
							'in_date' =>$_SGLOBAL['timestamp']
						);
			if(empty($int_uid)){
				if(empty($str_password)){
					callback(array('info'=>$arr_language['admin_member']['error_1'],'status'=>304));	
				}
				$bool = $obj_member->verify_rename($str_username);
				if($bool){
					callback(array('info'=>$arr_language['admin_member']['rename'],'status'=>304));	
				}
				$obj_member->insert_member($arr_data);
				callback(array('info'=>$arr_language['admin_member']['add_success'],'status'=>200));
			}
			unset($arr_data['in_date']);	
			if(empty($str_password)){
				unset($arr_data['password']);	
			}
			$arr_member = $obj_member->verify_rename($str_username,true);
			if($arr_member && $arr_member['uid']!=$int_uid){
				callback(array('info'=>$arr_language['admin_member']['rename'],'status'=>304));		
			}
			$obj_member->update_member(array('uid'=>$int_uid),$arr_data);
			callback(array('info'=>$arr_language['admin_member']['mod_success'],'status'=>200));
			
		}
		
		$int_show_member_group = get_config('show_admin_member_group');
		$int_show_member_group = $int_show_member_group['val'];
		
		if($int_show_member_group){
			$obj_member_group = L::loadClass('admin_member_group','admin');
			$arr_data = array('is_del'=>0);
			$arr_groups = $obj_member_group->get_list($arr_data,1,20);
			//var_export($arr_groups);
		}
		
		$arr_member = $obj_member->get_one_member($int_uid);
		//var_export($arr_member);
		include template('template/admin/setting/member/add');
	}
	
	function extra_del(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_uid = intval($_GET['uid']);
		if(empty($int_uid)){
			return false;	
		}
		$obj_member = L::loadClass('admin_member','admin');
		$obj_member->update_member(array('uid'=>$int_uid),array('is_del'=>1));
		callback(array('info'=>$arr_language['admin_member']['del_success'],'status'=>200));
	}
	
	

}