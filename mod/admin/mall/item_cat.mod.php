<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_item_cat {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
	}

	function extra_index(){
		global $_SGLOBAL;
		if(submit_check('formhash')){
			$str_do = str_addslashes($_POST['_do']);
			$str_function = 'do_'.$str_do;
			if (method_exists($this, $str_function)) {
				$this->$str_function();
				exit;
			}
		}
		$obj_item_cat = L::loadClass('admin_mall_item_cat','admin');
		$arr_data = $obj_item_cat->get_list(array('is_del'=>0),1,1000);
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
		
		//var_export($arr_cat);
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/mall/item_cat');
	}
	
	function do_add(){
		global $_SGLOBAL;
		$obj_item_cat = L::loadClass('admin_mall_item_cat','admin');
		$arr_languages = get_languages();
		$str_name = str_addslashes($_POST['name']);
		$arr_language_names = array();
		foreach($arr_languages as $v){
			$arr_language_names[$v['cname']] = str_addslashes($_POST['name_'.$v['cname']]);
		}
		$int_sort = intval($_POST['sort']);
		$int_pid  = intval($_POST['pid']);
		$int_level= 1;
		if($int_pid>0){
			$arr_data = $obj_item_cat->get_one($int_pid);
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

		$obj_item_cat->_insert($arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function do_mod(){
		global $_SGLOBAL;
		$obj_item_cat = L::loadClass('admin_mall_item_cat','admin');
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
		$obj_item_cat->_update($int_cid,$arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function extra_del(){
		global $_SGLOBAL;
		$obj_item_cat = L::loadClass('admin_mall_item_cat','admin');
		$int_cid  = intval($_GET['cid']);
		$arr_data = array(
						'is_del'=>1
					);
		
		$obj_item_cat->_update($int_cid,$arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function extra_get_one(){
		global $_SGLOBAL;
		$int_cid = intval($_GET['cid']);
		if(empty($int_cid)){
			callback(array('info'=>'ID为空','status'=>304));	
		}
		$obj_item_cat = L::loadClass('admin_mall_item_cat','admin');
		$arr_data = $obj_item_cat->get_one($int_cid);
		callback(array('info'=>'','status'=>200,'data'=>$arr_data));
	}

}