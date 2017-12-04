<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_consult {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
	}

	function extra_index(){
		global $_SGLOBAL;
		$int_status = intval($_GET['status']);
		$int_page = intval($_GET['page']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 20;
		
		$obj_consult = L::loadClass('consult','index');
		$int_status && $arr_data['status'] = $int_status;
		$arr_data['is_del'] = 0;
		$arr_list = $obj_consult->get_list($arr_data,$int_page,$int_page_size);
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		//var_export($arr_list);
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/consult/index');
	}
	
	function extra_reply(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_id = intval($_POST['id']);
		$str_reply = str_addslashes($_POST['reply']);
		if(empty($int_id)){
			return false;	
		}
		$obj_consult = L::loadClass('consult','index');
		$obj_consult->update(array('id'=>$int_id), array('reply'=>$str_reply,'status'=>LEM_consult::SUCCESS));
		callback(array('info'=>$arr_language['ok_2'],'status'=>200));
	}
	
	
	function extra_success(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_id = intval($_GET['id']);
		if(empty($int_id)){
			return false;	
		}
		$obj_consult = L::loadClass('consult','index');
		$obj_consult->update(array('id'=>$int_id), array('status'=>LEM_consult::SUCCESS));
		callback(array('info'=>$arr_language['ok_2'],'status'=>200));
	}
	
	function extra_del(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_id = intval($_GET['id']);
		if(empty($int_id)){
			return false;	
		}
		$obj_consult = L::loadClass('consult','index');
		$obj_consult->update(array('id'=>$int_id), array('is_del'=>1));
		callback(array('info'=>$arr_language['del_success'],'status'=>200));
	}
}