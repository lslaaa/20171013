<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_menu {
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
		$int_show_cname = get_config('set_menu_show_cname');
		$int_show_cname = $int_show_cname['val'];
		//var_export($int_show_cname);
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/setting/menu');
	}
	
	function do_add(){
		global $_SGLOBAL;
		$obj_setting_menu = L::loadClass('admin_setting_menu','admin');
		$str_name = str_addslashes($_POST['name']);
		$str_cname= str_addslashes($_POST['cname']);
		$str_url  = str_addslashes($_POST['url']);
		$int_sort = intval($_POST['sort']);
		$int_pid  = intval($_POST['pid']);
		$int_level= 1;
		if($int_pid>0){
			$arr_data = $obj_setting_menu->get_one($int_pid);
			$int_level = $arr_data['level'] + 1;
		}
		$arr_data = array(
						'pid'=>$int_pid,
						'level'=>$int_level,
						'name'=>$str_name,
						'cname'=>$str_cname,
						'url'=>$str_url,
						'sort'=>$int_sort,
					);
		
		$obj_setting_menu->_insert($arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function do_mod(){
		global $_SGLOBAL;
		$obj_setting_menu = L::loadClass('admin_setting_menu','admin');
		$int_mid  = intval($_POST['mid']);
		$str_name = str_addslashes($_POST['name']);
		$str_cname= str_addslashes($_POST['cname']);
		$str_url  = str_addslashes($_POST['url']);
		$int_sort = intval($_POST['sort']);
		$arr_data = array(
						'name'=>$str_name,
						'cname'=>$str_cname,
						'url'=>$str_url,
						'sort'=>$int_sort,
					);
		
		$obj_setting_menu->_update($int_mid,$arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function extra_del(){
		global $_SGLOBAL;
		$obj_setting_menu = L::loadClass('admin_setting_menu','admin');
		$int_mid  = intval($_GET['mid']);
		$arr_data = array(
						'is_del'=>1
					);
		
		$obj_setting_menu->_update($int_mid,$arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function extra_get_one(){
		global $_SGLOBAL;
		$int_mid = intval($_GET['mid']);
		if(empty($int_mid)){
			callback(array('info'=>'ID为空','status'=>304));	
		}
		$obj_setting_menu = L::loadClass('admin_setting_menu','admin');
		$arr_data = $obj_setting_menu->get_one($int_mid);
		callback(array('info'=>'','status'=>200,'data'=>$arr_data));
	}

}