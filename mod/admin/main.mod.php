<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_main {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
	}

	function extra_index(){
        $str_from = urldecode($_GET['from']);
		if(empty($str_from)){
			$str_from = '/admin.php?mod=main&extra=main_content';	
		}		
		//var_export($_SGLOBAL['member']);
		include template('template/admin/main');
	}
	
	function extra_top(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$obj_setting_menu = L::loadClass('admin_setting_menu','admin');
		$arr_menu = $obj_setting_menu->get_list(array('level'=>1,'is_del'=>0));
		
		$obj_member_group = L::loadClass('admin_member_group','admin');
		$arr_group = $obj_member_group->get_one($arr_member['gid']);
		$mix_permission   = $arr_group['permission'];
		$mix_permission_b = explode(',', $mix_permission);
		
		foreach($arr_menu as $k=>$v){
			if($mix_permission!=='all' && !in_array($v['mid'],$mix_permission_b)){
				unset($arr_menu[$k]);										  
			}
		}
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/top');
	}
	
	function extra_left_menu(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		
		$int_pid = intval($_GET['top_menu_id']);
		$obj_setting_menu = L::loadClass('admin_setting_menu','admin');
		$arr_menu = $obj_setting_menu->get_list(array('pid'=>$int_pid,'is_del'=>0));
		
		$obj_member_group = L::loadClass('admin_member_group','admin');
		$arr_group = $obj_member_group->get_one($arr_member['gid']);
		$mix_permission   = $arr_group['permission'];
		$mix_permission_b = explode(',', $mix_permission);
		
		foreach($arr_menu as $k=>$v){
			if($mix_permission!=='all' && !in_array($v['mid'],$mix_permission_b)){
				unset($arr_menu[$k]);										  
			}
		}
		include template('template/admin/left_menu');
	}
        
        
	//后台首页主体内容
	function extra_main_content(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/main_content');
	}

}