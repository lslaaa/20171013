<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_member_group {
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
		$obj_member_group = L::loadClass('member_group','index');
		$arr_data = array('is_del'=>0);
		$arr_list = $obj_member_group->get_list($arr_data,$int_page,$int_page_size);
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		$arr_language = $_SGLOBAL['language'];
		//var_export($arr_language);
		include template('template/admin/member/member_group/index');
	}
	
	function extra_add(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_gid = intval($_GET['gid']);
		$obj_member_group = L::loadClass('member_group','index');
		if(submit_check('formhash')){
			$int_gid      = intval($_POST['gid']);
			$str_group_name = str_addslashes($_POST['group_name']);
			$int_min_buy_num = intval($_POST['min_buy_num']);
			$int_sort = intval($_POST['sort']);
			
			if(empty($str_group_name)){
				callback(array('info'=>$arr_language['admin_member_group']['error_1'],'status'=>304));
			}
			
			$arr_data = array(
							'group_name'=>$str_group_name,
							'min_buy_num'=>$int_min_buy_num,
							'sort'=>$int_sort
						);
			if(empty($int_gid)){
				$bool = $obj_member_group->verify_rename($str_group_name);
				if($bool){
					callback(array('info'=>$arr_language['admin_member_group']['rename'],'status'=>304));	
				}
				$obj_member_group->insert($arr_data);
				callback(array('info'=>$arr_language['ok_2'],'status'=>200));
			}
			$arr_group = $obj_member_group->verify_rename($str_group_name,true);
			if($arr_group && $arr_group['gid']!=$int_gid){
				callback(array('info'=>$arr_language['admin_member_group']['rename'],'status'=>304));		
			}
			$obj_member_group->update(array('gid'=>$int_gid),$arr_data);
			callback(array('info'=>$arr_language['ok_2'],'status'=>200));
			
		}
		$arr_group = $obj_member_group->get_one($int_gid);
		//var_export($arr_member);
		include template('template/admin/member/member_group/add');
	}
	
	function extra_del(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_gid = intval($_GET['gid']);
		if(empty($int_gid)){
			return false;	
		}
		$obj_member_group = L::loadClass('member_group','index');
		$obj_member_group->update(array('gid'=>$int_gid),array('is_del'=>1));
		callback(array('info'=>$arr_language['ok_2'],'status'=>200));
	}
	
	function extra_set_permission(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$obj_member_group = L::loadClass('member_group','index');
		if(submit_check('formhash')){
			$int_gid = intval($_POST['gid']);
			$arr_mid = intvals($_POST['mid']);
			$obj_member_group->update(array('gid'=>$int_gid),array('permission'=>implode(',',$arr_mid)));
			callback(array('info'=>$arr_language['ok_2'],'status'=>200));
		}
		
		
		$int_gid = intval($_GET['gid']);
		if(empty($int_gid)){
			return false;	
		}
		$obj_setting_menu = L::loadClass('admin_setting_menu','admin');
		$arr_data = $obj_setting_menu->get_list(array('is_del'=>0));
		$arr_menu = array();
		foreach($arr_data as $k=>$v){
			if($v['level']==1){
				$arr_menu[$v['mid']] = $v;
				unset($arr_data[$k]);	
				foreach($arr_data as $k2=>$v2){
					if($v['mid']==$v2['pid']){
						$arr_menu[$v['mid']]['subs'][$v2['mid']] = $v2;
						unset($arr_data[$k2]);
						foreach($arr_data as $k3=>$v3){
							if($v2['mid']==$v3['pid']){
								$arr_menu[$v['mid']]['subs'][$v2['mid']]['subs'][$v3['mid']] = $v3;
								unset($arr_data[$k3]);
							}
						}
					}
				}
			}
		}
		$arr_group = $obj_member_group->get_one($int_gid);
		$mix_permission = $arr_group['permission'];
		//var_export($arr_menu);
		include template('template/admin/member/member_group/permission');	
	}
	
	

}