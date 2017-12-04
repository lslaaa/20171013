<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_help {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
	}

	function extra_index(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_page_id = 6;
		$obj_page = L::loadClass('page','index');
		$obj_ads = L::loadClass('ads','index');
		$arr_banner = $obj_ads->get_list(array('ads_cid'=>11,'is_del'=>0),1,1);
		$arr_banner && $arr_banner = current($arr_banner['list']);
		
		$arr_content = $obj_page->get_one($int_page_id);
		$arr_page_cat = $obj_page->get_cat_list(array('is_del'=>0),1,100);
		$arr_page_cat_b = format_array_val_to_key($arr_page_cat,'page_id');
//var_export($arr_page_cat);
		$str_page_title = $arr_page_cat_b[$int_page_id]['name'];
		include template('template/index/page');
	}
}