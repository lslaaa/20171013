<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_join {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
	}

	function extra_index(){
		global $_SGLOBAL;
		$arr_languages = get_languages(true);
		$arr_languages_b = format_array_val_to_key($arr_languages,'cname');
		$int_page = intval($_GET['page']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 20;

		$obj_case = L::loadClass('join','index');

		$arr_data = array();

		$arr_data['is_del'] = 0;
		$arr_list = $obj_case->get_list($arr_data,$int_page,$int_page_size);
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);

		
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/join/index');
	}
	
	function extra_add(){
		global $_SGLOBAL;
		$arr_languages= get_languages(true);
		$arr_language = $_SGLOBAL['language'];
		$int_id      = intval($_GET['id']);
		$int_copy     = intval($_GET['copy']);
		$obj_case = L::loadClass('join','index');
		if(submit_check('formhash')){
			$str_form_do = str_addslashes($_POST['form_do']);

			if (method_exists($this, 'do_' . $str_form_do)) {
				$str_function_name = 'do_' . $str_form_do;
				$this->$str_function_name();
			}
			exit;
		}

		if($int_id>0){
			$arr_case = $obj_case->get_one($int_id);
		}

		include template('template/admin/join/add');
	}
	
	function do_add(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$obj_case = L::loadClass('join','index');
		$int_id   = intval($_POST['id']);
		$str_title = str_addslashes($_POST['title']);
		$str_des_1 = str_addslashes($_POST['des_1']);
		$str_des_2 = str_addslashes($_POST['des_2']);
		$str_content = str_addslashes($_POST['content']);
		$arr_data = array(
						'title'=>$str_title,
						'des_1'=>$str_des_1,
						'des_2'=>$str_des_2,
						'is_del'=>0,
						'in_date'=>time()
					);
		// var_dump($arr_data);exit();
		if($int_id>0){
			unset($arr_data['in_date']);
			$obj_case->update(array('id'=>$int_id),$arr_data);
			$arr_data = array(
						'content'=>$str_content
					);
			$obj_case->update_detail(array('id'=>$int_id),$arr_data);

		}
		else{
			$int_id = $obj_case->insert($arr_data);
			$arr_data = array(
						'id'=>$int_id,
						'content'=>$str_content
					);
			$obj_case->insert_detail($arr_data);
		}
		callback(array('info'=>$arr_language['ok'],'status'=>200));
	}
	
	function do_upload(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$arr_config = get_config('case_pic');

		$str_path = save_image($_FILES['pic']['tmp_name'],$arr_config['val']);
		if(empty($str_path)){
			callback(array('info'=>$arr_language['uploads']['error_1'],'status'=>304));		
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
		$obj_case = L::loadClass('join','index');
		$obj_case->update(array('id'=>$int_id), array('is_del'=>1));
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
		$obj_case = L::loadClass('join','index');
		$arr_data = $obj_case->get_cat_list(array('is_del'=>0),1,1000);
		
		$arr_cat = array();
		foreach($arr_data as $k=>$v){
			if($v['level']==1){
				$arr_cat[$v['cid']] = $v;
				unset($arr_data[$k]);	
				foreach($arr_data as $k2=>$v2){
					if($v['cid']==$v2['pid']){
						$arr_cat[$v['cid']]['subs'][$v2['cid']] = $v2;
						unset($arr_data[$k2]);
						foreach($arr_data as $k3=>$v3){
							if($v2['cid']==$v3['pid']){
								$arr_cat[$v['cid']]['subs'][$v2['cid']]['subs'][$v3['cid']] = $v3;
								unset($arr_data[$k3]);
							}
						}
					}
				}
			}
		}
		
		$arr_config = get_config("'case_allow_add','case_allow_pid_add'");
		$arr_allow_add = explode(',',$arr_config['case_allow_add']['val']);
		$arr_allow_pid_add = explode(',',$arr_config['case_allow_pid_add']['val']);
		
		
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/join/case_cat');	
	}
	
	function do_cat_add(){
		global $_SGLOBAL;
		$obj_case = L::loadClass('join','index');
		$arr_languages = get_languages();
		$str_name = str_addslashes($_POST['name']);
		foreach($arr_languages as $v){
			$arr_language_names[$v['cname']] = str_addslashes($_POST['name_'.$v['cname']]);
		}
		$int_sort = intval($_POST['sort']);
		$int_pid  = intval($_POST['pid']);
		$int_level= 1;
		if($int_pid>0){
			$arr_data = $obj_case->get_one_cat($int_pid);
			$int_level = $arr_data['level'] + 1;
		}
		$arr_data = array(
						'pid'=>$int_pid,
						'level'=>$int_level,
						'name'=>$str_name,
						'sort'=>$int_sort,
					);
		foreach($arr_languages as $v){
			$arr_data['name_'.$v['cname']] = $arr_language_names[$v['cname']];
		}
		$obj_case->_cat_insert($arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function do_cat_mod(){
		global $_SGLOBAL;
		$obj_case = L::loadClass('join','index');
		$arr_languages = get_languages();
		$int_cid  = intval($_POST['cid']);
		$str_name = str_addslashes($_POST['name']);
		foreach($arr_languages as $v){
			$arr_language_names[$v['cname']] = str_addslashes($_POST['name_'.$v['cname']]);
		}
		$int_sort = intval($_POST['sort']);
		$arr_data = array(
						'name'=>$str_name,
						'sort'=>$int_sort,
					);
		foreach($arr_languages as $v){
			$arr_data['name_'.$v['cname']] = $arr_language_names[$v['cname']];
		}
		$obj_case->_cat_update($int_cid,$arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function extra_get_one_cat(){
		global $_SGLOBAL;
		$int_cid = intval($_GET['cid']);
		if(empty($int_cid)){
			callback(array('info'=>'IDÎª¿Õ','status'=>304));	
		}
		
		$obj_case = L::loadClass('case','index');
		$arr_data = $obj_case->get_one_cat($int_cid);
		callback(array('info'=>'','status'=>200,'data'=>$arr_data));
	}
}