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
		$arr_languages = get_languages(true);
		$arr_languages_b = format_array_val_to_key($arr_languages,'cname');
		$str_language= str_addslashes($_GET['language']);
		$int_cid_1 = intval($_GET['cid_1']);
		$int_cid_2 = intval($_GET['cid_2']);
		$int_cid_3 = intval($_GET['cid_3']);
		$int_page = intval($_GET['page']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 20;
		
		$obj_news = L::loadClass('news','index');
		$arr_data = array();
		$int_cid_1 && $arr_data['cid_1'] = $int_cid_1;
		$int_cid_2 && $arr_data['cid_2'] = $int_cid_2;
		$int_cid_3 && $arr_data['cid_3'] = $int_cid_3;
		$str_language && $arr_data['language'] = $str_language;
		$arr_data['is_del'] = 0;
		$arr_list = $obj_news->get_list($arr_data,$int_page,$int_page_size);
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		
		$arr_cat  = $obj_news->get_cat_list(array('is_del'=>0),1,100);
		$json_cat = json_encode($arr_cat);
		$arr_cat_b= format_array_val_to_key($arr_cat,'cid');
		
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/news/index');
	}
	
	function extra_add(){
		global $_SGLOBAL;
		//var_export($_SGLOBAL['member']);
		$arr_languages= get_languages(true);
		$arr_language = $_SGLOBAL['language'];
		$int_nid      = intval($_GET['nid']);
		$int_copy     = intval($_GET['copy']);
		$obj_news = L::loadClass('news','index');
		if(submit_check('formhash')){
			$str_form_do = str_addslashes($_POST['form_do']);
			if (method_exists($this, 'do_' . $str_form_do)) {
				$str_function_name = 'do_' . $str_form_do;
				$this->$str_function_name();
			}
			exit;
		}
		$arr_cat = $obj_news->get_cat_list(array('is_del'=>0),1,100);
		$json_cat = json_encode($arr_cat);
		$arr_news = array();
		if($int_nid>0){
			$arr_news = $obj_news->get_one($int_nid);
		}
		//var_export($arr_news);
		$arr_config = get_config("'news_pic','news_editor_pic'");
		$str_pic_size = pic_size_des($arr_config['news_pic']['val']);
		include template('template/admin/news/add');
	}
	
	function do_add(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$obj_news = L::loadClass('news','index');
		$str_language= str_addslashes($_POST['language']);
		$int_nid   = intval($_POST['nid']);
		$int_cid_1 = intval($_POST['cid']);
		$int_cid_2 = intval($_POST['cid_2']);
		$int_cid_3 = intval($_POST['cid_3']);
		$int_sort = intval($_POST['sort']);
		$int_top  = intval($_POST['top']);
		$str_title = str_addslashes($_POST['title']);
		$str_pic   = str_addslashes($_POST['pic_url']);
		$str_content = str_addslashes($_POST['content']);
		$str_page_title = str_addslashes($_POST['page_title']);
		$str_page_kwd = str_addslashes($_POST['page_kwd']);
		$str_page_des = str_addslashes($_POST['page_des']);
		$arr_data = array(
						'language'=>$str_language,
						'cid_1'=>$int_cid_1,
						'cid_2'=>$int_cid_2,
						'cid_3'=>$int_cid_3,
						'pic'=>$str_pic,
						'title'=>$str_title,
						'sort'=>$int_sort,
						'top'=>$int_top,
						'author'=>$_SGLOBAL['member']['realname'],
						'is_del'=>0,
						'in_date'=>$_SGLOBAL['timestamp']
					);
		if($int_nid>0){
			unset($arr_data['in_date']);
			$obj_news->update(array('nid'=>$int_nid),$arr_data);
			$arr_data = array(
						'content'=>$str_content,
						'page_title'=>$str_page_title,
						'page_kwd'=>$str_page_kwd,
						'page_des'=>$str_page_des,
					);
			$obj_news->update_detail(array('nid'=>$int_nid),$arr_data);

		}
		else{
			$int_nid = $obj_news->insert($arr_data);
			$arr_data = array(
						'nid'=>$int_nid,
						'content'=>$str_content,
						'page_title'=>$str_page_title,
						'page_kwd'=>$str_page_kwd,
						'page_des'=>$str_page_des,
					);
			$obj_news->insert_detail($arr_data);
		}
		callback(array('info'=>$arr_language['ok'],'status'=>200));
	}
	
	function do_upload(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$arr_config = get_config('news_pic');

		$str_path = save_image($_FILES['pic']['tmp_name'],$arr_config['val']);
		if(empty($str_path)){
			callback(array('info'=>$arr_language['uploads']['error_1'],'status'=>304));		
		}
		callback(array('info'=>'','status'=>200,'data'=>$str_path));
	}
	
	function extra_del(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_nid = intval($_GET['nid']);
		if(empty($int_nid)){
			return false;	
		}
		$obj_news = L::loadClass('news','index');
		$obj_news->update(array('nid'=>$int_nid), array('is_del'=>1));
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
		$obj_news = L::loadClass('news','index');
		$arr_data = $obj_news->get_cat_list(array('is_del'=>0), 1,5000);
		
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
		
		$arr_config = get_config("'news_allow_add','news_allow_pid_add'");
		$arr_allow_add = explode(',',$arr_config['news_allow_add']['val']);
		$arr_allow_pid_add = explode(',',$arr_config['news_allow_pid_add']['val']);
		
		
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/news/news_cat');	
	}
	
	function do_cat_add(){
		global $_SGLOBAL;
		$obj_news = L::loadClass('news','index');
		$arr_languages = get_languages();
		$str_name = str_addslashes($_POST['name']);
		foreach($arr_languages as $v){
			$arr_language_names[$v['cname']] = str_addslashes($_POST['name_'.$v['cname']]);
		}
		$int_sort = intval($_POST['sort']);
		$int_pid  = intval($_POST['pid']);
		$int_level= 1;
		if($int_pid>0){
			$arr_data = $obj_news->get_one_cat($int_pid);
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
		$obj_news->_cat_insert($arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function do_cat_mod(){
		global $_SGLOBAL;
		$obj_news = L::loadClass('news','index');
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
		$obj_news->_cat_update($int_cid,$arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function extra_get_one_cat(){
		global $_SGLOBAL;
		$int_cid = intval($_GET['cid']);
		if(empty($int_cid)){
			callback(array('info'=>'IDΪ��','status'=>304));	
		}
		
		$obj_news = L::loadClass('news','index');
		$arr_data = $obj_news->get_one_cat($int_cid);
		callback(array('info'=>'','status'=>200,'data'=>$arr_data));
	}
}