<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_ads {
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
		$str_language= str_addslashes($_GET['language']);
		$int_ads_cid = intval($_GET['ads_cid']);
		$int_page = intval($_GET['page']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 20;
		$obj_ads = L::loadClass('ads','index');
		$arr_data = array();
		$int_ads_cid && $arr_data['ads_cid'] = $int_ads_cid;
		$str_language && $arr_data['language'] = $str_language;
		$arr_data['is_del'] = 0;

		$arr_list = $obj_ads->get_list($arr_data,$int_page,$int_page_size);
		$arr_data = array('is_del'=>0);
		$arr_cat  = $obj_ads->get_list_cat($arr_data);
		$arr_cat  = format_array_val_to_key($arr_cat,'ads_cid');
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		$arr_language = $_SGLOBAL['language'];
		//var_export($arr_language);
		include template('template/admin/ads/index');
	}
	
	function extra_add(){
		global $_SGLOBAL;
		$arr_languages = get_languages(true);
		$arr_language = $_SGLOBAL['language'];
		$int_aid = intval($_GET['aid']);
		$int_copy= intval($_GET['copy']);
		$obj_ads = L::loadClass('ads','index');
		if(submit_check('formhash')){
			$str_form_do = str_addslashes($_POST['form_do']);
			if (method_exists($this, 'do_' . $str_form_do)) {
				$str_function_name = 'do_' . $str_form_do;
				$this->$str_function_name();
			}
			exit;
		}
		$arr_data = array('is_del'=>0);
		$arr_cat  = $obj_ads->get_list_cat($arr_data);
		$arr_cat  = format_array_val_to_key($arr_cat,'ads_cid');
		$arr_ads  = $obj_ads->get_one($int_aid);
		//var_export($arr_ads);
		include template('template/admin/ads/add');
	}
	
	function do_add(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$str_language= str_addslashes($_POST['language']);
		$int_aid     = intval($_POST['aid']);
		$int_ads_cid = intval($_POST['ads_cid']);
		$str_title   = str_addslashes($_POST['title']);
		$str_url     = str_addslashes($_POST['url']);
		$str_des     = str_addslashes($_POST['des']);
		$str_des_2   = str_addslashes($_POST['des_2']);
		$str_des_3   = str_addslashes($_POST['des_3']);
		$int_sort    = str_addslashes($_POST['sort']);
		
		$str_pic_url = str_addslashes($_POST['pic_url']);
		$obj_ads = L::loadClass('ads','index');
		$arr_cat = $obj_ads->get_one_cat($int_ads_cid);
		$arr_data = array(
						'language'=>$str_language,
						'ads_cid'=>$int_ads_cid,
						'title'=>$str_title,
						'url'=>$str_url,
						'sort'=>$int_sort,
						'in_date'=>$_SGLOBAL['timestamp']
					);
		$arr_cat['has_pic'] && $arr_data['pic'] = $str_pic_url;
		$arr_cat['has_des'] && $arr_data['des'] = $str_des;
		$arr_cat['has_des_2'] && $arr_data['des_2'] = $str_des_2;
		$arr_cat['has_des_3'] && $arr_data['des_3'] = $str_des_3;
		if($int_aid>0){
			unset($arr_data['in_date']);
			$obj_ads->update(array('aid'=>$int_aid),$arr_data);
			callback(array('info'=>$arr_language['ok'],'status'=>200));
		}
		else{
			$obj_ads->insert($arr_data);
			callback(array('info'=>$arr_language['ok_2'],'status'=>200));
		}
	}
	
	function do_upload(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_ads_cid = str_addslashes($_POST['ads_cid']);
		$obj_ads = L::loadClass('ads','index');
		$arr_cat = $obj_ads->get_one_cat($int_ads_cid);
		$str_path = save_image($_FILES['pic']['tmp_name'],$arr_cat['max_width'].','.$arr_cat['max_height']);
		if(empty($str_path)){
			callback(array('info'=>$arr_language['uploads']['error_1'],'status'=>304));		
		}
		callback(array('info'=>'','status'=>200,'data'=>$str_path));
	}
	
	function extra_del(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_aid     = intval($_GET['aid']);
		if(empty($int_aid)){
			return false;	
		}
		$obj_ads = L::loadClass('ads','index');
		$obj_ads->update(array('aid'=>$int_aid),array('is_del'=>1));
		callback(array('info'=>$arr_language['del_success'],'status'=>200));
	}
	
	

}