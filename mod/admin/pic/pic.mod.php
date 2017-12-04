<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_pic {
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
		
		$obj_pic = L::loadClass('pic','index');
		$arr_data = array();
		$int_cid_1 && $arr_data['cid_1'] = $int_cid_1;
		$int_cid_2 && $arr_data['cid_2'] = $int_cid_2;
		$int_cid_3 && $arr_data['cid_3'] = $int_cid_3;
		$str_language && $arr_data['language'] = $str_language;
		$arr_data['is_del'] = 0;
		$arr_list = $obj_pic->get_list($arr_data,$int_page,$int_page_size);
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		
		$arr_cat  = $obj_pic->get_cat_list(array('is_del'=>0),1,100);
		$json_cat = json_encode($arr_cat);
		$arr_cat_b= format_array_val_to_key($arr_cat,'cid');
		
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/pic/index');
	}
	
	function extra_add(){
		global $_SGLOBAL;
		$arr_languages= get_languages(true);
		$arr_language = $_SGLOBAL['language'];
		$int_id      = intval($_GET['id']);
		$int_copy     = intval($_GET['copy']);
		$obj_pic = L::loadClass('pic','index');
		if(submit_check('formhash')){
			$str_form_do = str_addslashes($_POST['form_do']);
			if (method_exists($this, 'do_' . $str_form_do)) {
				$str_function_name = 'do_' . $str_form_do;
				$this->$str_function_name();
			}
			exit;
		}
		$arr_cat = $obj_pic->get_cat_list(array('is_del'=>0),1,100);
		$json_cat = json_encode($arr_cat);
		$arr_news = array();
		if($int_id>0){
			$arr_pic = $obj_pic->get_one($int_id);
		}
		else{
			$arr_pic = array('detail'=>array('pics'=>array('')))	;
		}
		//var_export($arr_pic);
		$arr_config = get_config('pic_pic');
		$str_pic_size = pic_size_des($arr_config['val']);
		include template('template/admin/pic/add');
	}
	
	function do_add(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$obj_pic = L::loadClass('pic','index');
		$str_language= str_addslashes($_POST['language']);
		$int_id   = intval($_POST['id']);
		$int_cid_1 = intval($_POST['cid']);
		$int_cid_2 = intval($_POST['cid_2']);
		$int_cid_3 = intval($_POST['cid_3']);
		$str_title = str_addslashes($_POST['title']);
		$arr_pic   = str_addslashes($_POST['pic_url']);
		$arr_des   = str_addslashes($_POST['pic_des']);
		$int_sort  = intval($_POST['sort']);
		$arr_data = array(
						'language'=>$str_language,
						'cid_1'=>$int_cid_1,
						'cid_2'=>$int_cid_2,
						'cid_3'=>$int_cid_3,
						'pic'=>$arr_pic[0],
						'title'=>$str_title,
						'sort'=>$int_sort,
						'is_del'=>0,
						'in_date'=>$_SGLOBAL['timestamp']
					);
		if($int_id>0){
			unset($arr_data['in_date']);
			$obj_pic->update(array('id'=>$int_id),$arr_data);
			$arr_data = array(
						'pics'=>str_addslashes(var_export($arr_pic,true)),
						'des'=>str_addslashes(var_export($arr_des,true))
					);
			$obj_pic->update_detail(array('id'=>$int_id),$arr_data);

		}
		else{
			$int_id = $obj_pic->insert($arr_data);
			$arr_data = array(
						'id'=>$int_id,
						'pics'=>str_addslashes(var_export($arr_pic,true)),
						'des'=>str_addslashes(var_export($arr_des,true))
					);
			$obj_pic->insert_detail($arr_data);
		}
		callback(array('info'=>$arr_language['ok'],'status'=>200));
	}
	
	function do_upload(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$arr_config = get_config('pic_pic');

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
		$obj_pic = L::loadClass('pic','index');
		$obj_pic->update(array('id'=>$int_id), array('is_del'=>1));
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
		$obj_pic = L::loadClass('pic','index');
		$arr_data = $obj_pic->get_cat_list(array('is_del'=>0),1,1000);
		
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
		
		$arr_config = get_config("'pic_allow_add','pic_allow_pid_add'");
		$arr_allow_add = explode(',',$arr_config['pic_allow_add']['val']);
		$arr_allow_pid_add = explode(',',$arr_config['pic_allow_pid_add']['val']);
		
		
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/pic/pic_cat');	
	}
	
	function do_cat_add(){
		global $_SGLOBAL;
		$obj_pic = L::loadClass('pic','index');
		$arr_languages = get_languages();
		$str_name = str_addslashes($_POST['name']);
		foreach($arr_languages as $v){
			$arr_language_names[$v['cname']] = str_addslashes($_POST['name_'.$v['cname']]);
		}
		$str_pic  = str_addslashes($_POST['pic']);
		$int_sort = intval($_POST['sort']);
		$int_pid  = intval($_POST['pid']);
		$int_level= 1;
		if($int_pid>0){
			$arr_data = $obj_pic->get_one_cat($int_pid);
			$int_level = $arr_data['level'] + 1;
		}
		$arr_data = array(
						'pid'=>$int_pid,
						'level'=>$int_level,
						'name'=>$str_name,
						'pic'=>$str_pic,
						'sort'=>$int_sort,
					);
		foreach($arr_languages as $v){
			$arr_data['name_'.$v['cname']] = $arr_language_names[$v['cname']];
		}
		$obj_pic->_cat_insert($arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function do_cat_mod(){
		global $_SGLOBAL;
		$obj_pic = L::loadClass('pic','index');
		$arr_languages = get_languages();
		$int_cid  = intval($_POST['cid']);
		$str_name = str_addslashes($_POST['name']);
		$str_pic  = str_addslashes($_POST['pic']);
		foreach($arr_languages as $v){
			$arr_language_names[$v['cname']] = str_addslashes($_POST['name_'.$v['cname']]);
		}
		$int_sort = intval($_POST['sort']);
		$arr_data = array(
						'name'=>$str_name,
						'sort'=>$int_sort,
						'pic'=>$str_pic,
					);
		foreach($arr_languages as $v){
			$arr_data['name_'.$v['cname']] = $arr_language_names[$v['cname']];
		}
		$obj_pic->_cat_update($int_cid,$arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function do_cat_upload(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$arr_config = get_config('pic_cat_pic');

		$str_path = save_image($_FILES['pic']['tmp_name'],$arr_config['val']);
		if(empty($str_path)){
			callback(array('info'=>$arr_language['uploads']['error_1'],'status'=>304));		
		}
		callback(array('info'=>'','status'=>200,'data'=>$str_path));
	}
	
	function extra_get_one_cat(){
		global $_SGLOBAL;
		$int_cid = intval($_GET['cid']);
		if(empty($int_cid)){
			callback(array('info'=>'IDÎª¿Õ','status'=>304));	
		}
		
		$obj_pic = L::loadClass('pic','index');
		$arr_data = $obj_pic->get_one_cat($int_cid);
		callback(array('info'=>'','status'=>200,'data'=>$arr_data));
	}
}