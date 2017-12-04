<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_firend_link {
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
		
		$obj_firend_link = L::loadClass('firend_link','index');
		$arr_list = $obj_firend_link->get_list(array('is_del'=>0),$int_page,$int_page_size);
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/firend_link/index');
	}
	
	function extra_add(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_fid = intval($_GET['fid']);
		
		if(submit_check('formhash')){
			$str_form_do = str_addslashes($_POST['form_do']);
			if (method_exists($this, 'do_' . $str_form_do)) {
				$str_function_name = 'do_' . $str_form_do;
				$this->$str_function_name();
			}
			exit;
		}
		$obj_firend_link = L::loadClass('firend_link','index');
		$arr_firend_link = $obj_firend_link->get_one($int_fid);
		$arr_config = get_config('firend_link_pic');
		$str_pic_size = pic_size_des($arr_config['val']);
		//var_export($arr_content);
		
		include template('template/admin/firend_link/add');
	}
	
	function do_add(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$obj_firend_link = L::loadClass('firend_link','index');
		$int_fid  = intval($_POST['fid']);
		$str_name = str_addslashes($_POST['name']);
		$str_url  = str_addslashes($_POST['url']);
		$str_pic  = str_addslashes($_POST['pic_url']);
		$int_sort = intval($_POST['sort']);
		$arr_data = array(
						'name'=>$str_name,
						'url'=>$str_url,
						'sort'=>$int_sort,
						'pic'=>$str_pic
					);
		if(empty($int_fid)){
			$obj_firend_link->insert($arr_data);
		}
		else{
			$obj_firend_link->update(array('fid'=>$int_fid),$arr_data);	
		}
		callback(array('info'=>$arr_language['ok'],'status'=>200));
	}
	
	function do_upload(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$arr_config = get_config('firend_link_pic');
		$str_path = save_image($_FILES['pic']['tmp_name'],$arr_config['val']);
		if(empty($str_path)){
			callback(array('info'=>$arr_language['uploads']['error_1'],'status'=>304));		
		}
		callback(array('info'=>'','status'=>200,'data'=>$str_path));
	}
	
	function extra_del(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_fid = intval($_GET['fid']);
		if(empty($int_fid)){
			return false;	
		}
		$obj_firend_link = L::loadClass('firend_link','index');
		$obj_firend_link->update(array('fid'=>$int_fid), array('is_del'=>1));
		callback(array('info'=>$arr_language['del_success'],'status'=>200));
	}
}