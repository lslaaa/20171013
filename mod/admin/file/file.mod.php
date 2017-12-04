<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_file {
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
		
		$obj_file = L::loadClass('file','index');
		$arr_list = $obj_file->get_list(array('is_del'=>0),$int_page,$int_page_size);
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		$arr_cat = $obj_file->get_cat_list(array('is_del'=>0),1,100);
		
		$arr_cat = format_array_val_to_key($arr_cat,'cid');
		
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/file/index');
	}
	
	function extra_add(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_id      = intval($_GET['id']);
		
		$obj_file = L::loadClass('file','index');
		if(submit_check('formhash')){
			$str_form_do = str_addslashes($_POST['form_do']);
			if (method_exists($this, 'do_' . $str_form_do)) {
				$str_function_name = 'do_' . $str_form_do;
				$this->$str_function_name();
			}
			exit;
		}
		$arr_cat = $obj_file->get_cat_list(array('is_del'=>0),1,100);
		$json_cat = json_encode($arr_cat);
		$arr_file = array();
		if($int_id>0){
			$arr_file = $obj_file->get_one($int_id);
		}
		include template('template/admin/file/add');
	}
	
	function do_add(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$obj_file = L::loadClass('file','index');
		$int_id   = intval($_POST['id']);
		$int_cid_1 = intval($_POST['cid']);
		$int_cid_2 = intval($_POST['cid_2']);
		$str_title = str_addslashes($_POST['title']);
		$str_file   = str_addslashes($_POST['file_url']);
		$arr_data = array(
						'cid_1'=>$int_cid_1,
						'cid_2'=>$int_cid_2,
						'file'=>$str_file,
						'title'=>$str_title,
						'is_del'=>0,
						'in_date'=>$_SGLOBAL['timestamp']
					);
		if($int_id>0){
			unset($arr_data['in_date']);
			$obj_file->update(array('id'=>$int_id),$arr_data);

		}
		else{
			$obj_file->insert($arr_data);
		}
		callback(array('info'=>$arr_language['ok'],'status'=>200));
	}
	
	function do_upload(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];

		$str_path = save_file($_FILES['file']);
		if(is_array($str_path)){
			if($str_path['status']==190){
				callback(array('info'=>$arr_language['file']['error_1'].':'.$str_path['data'],'status'=>304));	
			}
			
		}
		callback(array('info'=>'','status'=>200,'data'=>$str_path));
	}
	
	function extra_del(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_id = intval($_GET['id']);
		if(empty($int_id)){
			return false;	
		}
		$obj_file = L::loadClass('file','index');
		$obj_file->update(array('id'=>$int_id), array('is_del'=>1));
		callback(array('info'=>$arr_language['del_success'],'status'=>200));
	}
	
	function extra_cat_index(){
		global $_SGLOBAL;
		if(submit_check('formhash')){
			$str_do = str_addslashes($_POST['_do']);
			$str_function = 'do_cat_'.$str_do;
			if (method_exists($this, $str_function)) {
				$this->$str_function();
				exit;
			}
		}
		$obj_file = L::loadClass('file','index');
		$arr_data = $obj_file->get_cat_list(array('is_del'=>0),1,1000);
		$arr_cat = array();
		foreach($arr_data as $k=>$v){
			if($v['level']==1){
				$arr_cat[$v['cid']] = $v;
				unset($arr_data[$k]);	
				foreach($arr_data as $k2=>$v2){
					if($v['cid']==$v2['pid']){
						$arr_cat[$v['page_id']]['subs'][$v2['page_id']] = $v2;
						unset($arr_data[$k2]);
						foreach($arr_data as $k3=>$v3){
							if($v2['cid']==$v3['pid']){
								$arr_cat[$v['page_id']]['subs'][$v2['page_id']]['subs'][$v3['page_id']] = $v3;
								unset($arr_data[$k3]);
							}
						}
					}
				}
			}
		}
		
		$arr_config = get_config('file_allow_add');
		$arr_allow_add = explode(',',$arr_config['val']);
		$arr_config = get_config('file_allow_pid_add');
		$arr_allow_pid_add = explode(',',$arr_config['val']);
		
		
		//var_export($arr_allow_add);
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/file/file_cat');	
	}
	
	function do_cat_add(){
		global $_SGLOBAL;
		$obj_file = L::loadClass('file','index');
		$str_name = str_addslashes($_POST['name']);
		$int_sort = intval($_POST['sort']);
		$int_pid  = intval($_POST['pid']);
		$int_level= 1;
		if($int_pid>0){
			$arr_data = $obj_file->get_one_cat($int_pid);
			$int_level = $arr_data['level'] + 1;
		}
		$arr_data = array(
						'pid'=>$int_pid,
						'level'=>$int_level,
						'name'=>$str_name,
						'sort'=>$int_sort,
					);
		
		$obj_file->_cat_insert($arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function do_cat_mod(){
		global $_SGLOBAL;
		$obj_file = L::loadClass('file','index');
		$int_cid  = intval($_POST['cid']);
		$str_name = str_addslashes($_POST['name']);
		$int_sort = intval($_POST['sort']);
		$arr_data = array(
						'name'=>$str_name,
						'sort'=>$int_sort,
					);
		$obj_file->_cat_update($int_cid,$arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function extra_get_one_cat(){
		global $_SGLOBAL;
		$int_cid = intval($_GET['cid']);
		if(empty($int_cid)){
			callback(array('info'=>'IDÎª¿Õ','status'=>304));	
		}
		
		$obj_file = L::loadClass('file','index');
		$arr_data = $obj_file->get_one_cat($int_cid);
		callback(array('info'=>'','status'=>200,'data'=>$arr_data));
	}
}