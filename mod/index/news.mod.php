<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_news {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
	}

	function extra_index(){
		global $_SGLOBAL;
		$int_cid = intval($_GET['cid']);
		$int_page = intval($_GET['page']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 20;
		
		$obj_news = L::loadClass('news','index');		
		$arr_news_cat   = $obj_news->get_cat_list(array('is_del'=>0),1,10);
		$arr_news_cat_b = format_array_val_to_key($arr_news_cat,'cid');
		if(!$int_cid){
			$int_cid = current($arr_news_cat);
			$int_cid = $int_cid['cid'];
		}
		
		$arr_data = array(
						'cid_1'=>$int_cid,
						'is_del'=>0	  
					);
		$arr_list = $obj_news->get_list($arr_data,$int_page,$int_page_size);
		foreach($arr_list['list'] as $k=>$v){
			$arr_list['list'][$k]['detail'] = $obj_news->get_one_detail($v['nid']);
		}

		$str_num_of_page = numofpage_b($int_page,ceil($arr_list['total']/$int_page_size),'/news/cid-'.$int_cid.'-page-');
		
		$obj_ads = L::loadClass('ads','index');
		$arr_banner = $obj_ads->get_list(array('ads_cid'=>8,'is_del'=>0),1,1);
		$arr_banner && $arr_banner = current($arr_banner['list']);
		
		$arr_language = $_SGLOBAL['language'];
		//var_export($arr_news_cat_b);
		$str_page_title = $arr_language['news'];
		include template('template/index/news_list');
	}
	
	function extra_detail(){
		global $_SGLOBAL;
		$int_nid      = intval($_GET['nid']);
		if(empty($int_nid)){
			return false;	
		}
		$obj_news = L::loadClass('news','index');
		$arr_news = $obj_news->get_one($int_nid);
		///var_export($arr_news);
		if(empty($arr_news)){
			return false;	
		}
		
		$obj_ads = L::loadClass('ads','index');
		$arr_banner = $obj_ads->get_list(array('ads_cid'=>8,'is_del'=>0),1,1);
		$arr_banner && $arr_banner = current($arr_banner['list']);
		
		$arr_language = $_SGLOBAL['language'];
		$str_page_title = $arr_news['main']['title'];
		include template('template/index/news_detail');
	}
}