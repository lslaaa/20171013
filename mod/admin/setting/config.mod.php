<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_config {
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
		$arr_languages = get_languages();
		
		$str_config = 'config_contact';
		$str_config_pic = 'config_contact_pic';
		
		$arr_data = get_config($str_config,true);
		$arr_data = $arr_data['val'];
		$arr_config_pic = get_config($str_config_pic);
		$str_pic_size = pic_size_des($arr_config_pic['val']);

		$obj_item = L::loadclass('item','index');
		$arr_sku_price = $obj_item->get_one_sku_price(array('id'=>1));
                $arr_sku_price2 = $obj_item->get_one_sku_price(array('id'=>2));
		$arr_price_name = $obj_item->get_price_name();
		// var_export($arr_sku_price);
		// var_export($arr_price_name);
		include template('template/admin/setting/config/contact');
	}
	
	function extra_add(){
		if(submit_check('formhash')){
			$str_form_do = str_addslashes($_POST['form_do']);
			if (method_exists($this, 'do_' . $str_form_do)) {
				$str_function_name = 'do_' . $str_form_do;
				$this->$str_function_name();
			}
			exit;
		}	
	}
	
	function do_add(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$str_config = str_addslashes($_POST['config_name']);
		$arr_data = $_POST;
		
		$arr_sku = $arr_data['sku'];
		$arr_sku2 = $arr_data['sku2'];
		foreach($arr_data as $k=>$v){
			if(!strstr($k,'data_')){
				unset($arr_data[$k]);	
			}
			
		}
		$obj_item = L::loadclass('item','index');
		$arr_sql = $obj_item->update_sku_price(array('id'=>1),$arr_sku);
		$arr_sql2 = $obj_item->update_sku_price(array('id'=>2),$arr_sku2);
		$arr_data = array(
						'name'=>$str_config,
						'val'=>str_addslashes(var_export($arr_data,true))
					);
		set_config($arr_data);
		callback(array('info'=>$arr_language['ok'],'status'=>200));
	}
	
	function do_upload(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$str_config_pic = str_addslashes($_POST['config_pic_name']);
		$arr_config = get_config($str_config_pic);

		$str_path = save_image($_FILES['pic']['tmp_name'],$arr_config['val']);
		if(empty($str_path)){
			callback(array('info'=>$arr_language['uploads']['error_1'],'status'=>304));		
		}
		callback(array('info'=>'','status'=>200,'data'=>$str_path));
	}
}