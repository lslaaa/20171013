<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_district {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
		
	}

	function extra_get_data(){
		global $_SGLOBAL;
		$int_pid = intval($_GET['pid']);
		$obj_district = L::loadClass('district','index');
		$arr_data = $obj_district->get_list(array('pid'=>$int_pid,'is_del'=>0));
		callback(array('info'=>'','status'=>200,'data'=>$arr_data));
	}
}