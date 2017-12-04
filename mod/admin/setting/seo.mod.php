<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_seo {
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
		$obj_seo = L::loadClass('seo','index');
		if(submit_check('formhash')){
			$str_title       = str_addslashes($_POST['title']);
			$str_kwd         = str_addslashes($_POST['kwd']);
			$str_des         = str_addslashes($_POST['des']);
			$str_short_title = str_addslashes($_POST['short_title']);
			foreach($arr_languages as $v){
				$arr_language_names['title_'.$v['cname']] = str_addslashes($_POST['title_'.$v['cname']]);
				$arr_language_names['kwd_'.$v['cname']] = str_addslashes($_POST['kwd_'.$v['cname']]);
				$arr_language_names['des_'.$v['cname']] = str_addslashes($_POST['des_'.$v['cname']]);
				$arr_language_names['short_title_'.$v['cname']] = str_addslashes($_POST['short_title_'.$v['cname']]);
			}
			$arr_data = array(
							'title'=>$str_title,
							'kwd'=>$str_kwd,
							'des'=>$str_des,
							'short_title'=>$str_short_title,
						);
			foreach($arr_languages as $v){
				$arr_data['title_'.$v['cname']] = $arr_language_names['title_'.$v['cname']];
				$arr_data['kwd_'.$v['cname']] = $arr_language_names['kwd_'.$v['cname']];
				$arr_data['des_'.$v['cname']] = $arr_language_names['des_'.$v['cname']];
				$arr_data['short_title_'.$v['cname']] =$arr_language_names['short_title_'.$v['cname']];
			}
			$obj_seo->update($arr_data);
			callback(array('info'=>$arr_language['ok'],'status'=>200));
		}
		$arr_data = array('is_del'=>0);
		$arr_seo = $obj_seo->get();
		
		//var_export($arr_language);
		include template('template/admin/setting/seo/index');
	}
}