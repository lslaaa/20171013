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
        	$obj_user = L::loadClass('user','index');
		if(empty($str_from)){
			$str_from = '/shop.php?mod=main&extra=main_content';	
		}		
		//var_export($_SGLOBAL['member']);
		include template('template/shop/main');
	}
	
	function extra_top(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$arr_menu = $_SGLOBAL['shop_menu'];
		foreach($arr_menu as $k=>$v){
			if($v['level']!=1){
				unset($arr_menu[$k]);										  
			}
		}
		$obj_user_group = L::loadClass('user_group','index');
		$arr_group = $obj_user_group->get_one($arr_member['gid']);
		$mix_permission   = $arr_group['permission'];
		$mix_permission_b = explode(',', $mix_permission);
		
		foreach($arr_menu as $k=>$v){
			if($mix_permission!=='all' && !in_array($v['mid'],$mix_permission_b)){
				unset($arr_menu[$k]);										  
			}
		}
		// var_export($arr_menu);
		$arr_language = $_SGLOBAL['language'];
		include template('template/shop/top');
	}
	
	function extra_left_menu(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		
		$int_pid = intval($_GET['top_menu_id']);
		$arr_menu = $_SGLOBAL['shop_menu'];
		$arr_menu_2 = array();
		foreach($arr_menu as $k=>$v){
			if($v['pid']==$int_pid){
				$arr_menu_2[] = $v;										  
			}
		}
		$obj_user_group = L::loadClass('user_group','index');
		$arr_group = $obj_user_group->get_one($arr_member['gid']);
		$mix_permission   = $arr_group['permission'];
		$mix_permission_b = explode(',', $mix_permission);
		foreach($arr_menu_2 as $k=>$v){
			if($mix_permission!=='all' && !in_array($v['mid'],$mix_permission_b)){
				unset($arr_menu_2[$k]);										  
			}
		}
		include template('template/shop/left_menu');
	}
        
        
	//后台首页主体内容
	function extra_main_content(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$arr_language = $_SGLOBAL['language'];
		include template('template/shop/main_content');
	}

}