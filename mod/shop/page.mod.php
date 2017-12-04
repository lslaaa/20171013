<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_page {
	private $arr_parent_permission = array(
		'_page_add'=>'_page_index',
		'_page_del'=>'_page_index',
		'_page_cat_index'=>'_page_cat_index',
		'_page_get_one_cat'=>'_page_cat_index',
        );
	
	function __construct() {
                $extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
                $obj_admin_member = L::loadClass('admin_member','admin');
                if (method_exists($this, 'extra_' . $extra)) {
                    $str_menu_alias_name = MOD_DIR.'_'.SCR.'_'.$extra;
                    isset($this->arr_parent_permission[$str_menu_alias_name]) && $str_menu_alias_name = $this->arr_parent_permission[$str_menu_alias_name];
                    $bool_permission = $obj_admin_member->verify_permission($str_menu_alias_name);
                    $str_function_name = 'extra_' . $extra;
                }
                if(!$bool_permission){
                    die('没有权限');
                }
                $this->$str_function_name();
                
        }

	function extra_index(){
		global $_SGLOBAL;
		$arr_languages = get_languages(true);
		$arr_languages_b = format_array_val_to_key($arr_languages,'cname');
		$str_language= str_addslashes($_GET['language']);
		$int_page = intval($_GET['page']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 20;
		
		$obj_page = L::loadClass('page','index');
		$arr_data = array();
		$str_language && $arr_data['language'] = $str_language;
		$arr_data['is_del'] = 0;
		$arr_list = $obj_page->get_list($arr_data,$int_page,$int_page_size);
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		$arr_cat = $obj_page->get_cat_list(array('is_del'=>0),1,100);
		
		$arr_cat = format_array_val_to_key($arr_cat,'page_id');
		
		$arr_language = $_SGLOBAL['language'];
		include template('template/shop/page/index');
	}
	
	function extra_add(){
		global $_SGLOBAL;
		$arr_languages = get_languages(true);
		$arr_language = $_SGLOBAL['language'];
		$int_page_id = intval($_GET['page_id']);
		$str_language= str_addslashes($_GET['language']);
		$obj_page = L::loadClass('page','index');
		if(submit_check('formhash')){
			$str_language= str_addslashes($_POST['language']);
			$int_page_id = intval($_POST['page_id']);
			$int_page_id_2 = intval($_POST['page_id_2']);
			$int_page_id_3 = intval($_POST['page_id_3']);
			$int_page_id = $int_page_id_2 ? $int_page_id_2 : $int_page_id;
			$int_page_id = $int_page_id_3 ? $int_page_id_3 : $int_page_id;
			$str_content = str_addslashes($_POST['content']);
                        $str_page_title = str_addslashes($_POST['page_title']);
			$str_page_kwd = str_addslashes($_POST['page_kwd']);
			$str_page_des = str_addslashes($_POST['page_des']);
			$arr_data = array(
							'language'=>$str_language,
							'page_id'=>$int_page_id,
							'content'=>$str_content,
                                                        'page_title'=>$str_page_title,
							'page_kwd'=>$str_page_kwd,
							'page_des'=>$str_page_des,
							'is_del'=>0
						);
			$arr_temp = $obj_page->get_one($int_page_id,$str_language);
			if(empty($arr_temp)){
				$obj_page->insert($arr_data);
			}
			else{
				$arr_data2 = $arr_data;
				$arr_where = array('language' => $str_language, 'page_id' => $int_page_id);
				$obj_page->update($arr_where,$arr_data2);	
			}
			callback(array('info'=>$arr_language['ok'],'status'=>200));
		}
		$arr_cat = $obj_page->get_cat_list(array('is_del'=>0),1,100);
		foreach($arr_cat as $v){
			if($int_page_id==$v['page_id']){
				$arr_curr_cat = $v;
				break;
			}
		}
		if($arr_curr_cat){
			$int_first_id = $arr_curr_cat['pid'] ? $arr_curr_cat['pid'] : $arr_curr_cat['page_id'];
			$int_two_id   = $arr_curr_cat['page_id'];
			$int_three_id = $arr_curr_cat['page_id'];
		}
		foreach($arr_cat as $v){
			if($v['page_id']==$arr_curr_cat['pid']){
				$arr_curr_parent_cat = $v;
				break;
			}
		}
		if($arr_curr_parent_cat && $arr_curr_parent_cat['pid']>0){
			$int_first_id = $arr_curr_parent_cat['pid'];
			$int_two_id = $arr_curr_parent_cat['page_id'];
		}
		$json_cat = json_encode($arr_cat);
		$arr_content = array();
		$arr_content = $obj_page->get_one($int_page_id,$str_language);
		//var_export($arr_content);
		$arr_page_editor_pic = get_config('page_editor_pic');
		include template('template/shop/page/add');
	}
	
	function extra_del(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_page_id = intval($_GET['page_id']);
		$str_language= str_addslashes($_GET['language']);
		if(empty($int_page_id)){
			return false;	
		}
		$obj_page = L::loadClass('page','index');
		$obj_page->update(array('page_id'=>$int_page_id,'language'=>$str_language), array('is_del'=>1));
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
		$obj_page = L::loadClass('page','index');
		$arr_data = $obj_page->get_cat_list(array('is_del'=>0),1,1000);
		$arr_cat = array();
		foreach($arr_data as $k=>$v){
			if($v['level']==1){
				$arr_cat[$v['page_id']] = $v;
				unset($arr_data[$k]);	
				foreach($arr_data as $k2=>$v2){
					if($v['page_id']==$v2['pid']){
						$arr_cat[$v['page_id']]['subs'][$v2['page_id']] = $v2;
						unset($arr_data[$k2]);
						foreach($arr_data as $k3=>$v3){
							if($v2['page_id']==$v3['pid']){
								$arr_cat[$v['page_id']]['subs'][$v2['page_id']]['subs'][$v3['page_id']] = $v3;
								unset($arr_data[$k3]);
							}
						}
					}
				}
			}
		}
		
		$arr_config = get_config('page_allow_add');
		$arr_allow_add = explode(',',$arr_config['val']);
		
		
		//var_export($arr_allow_add);
		$arr_language = $_SGLOBAL['language'];
		include template('template/shop/page/page_cat');	
	}
	
	function do_cat_add(){
		global $_SGLOBAL;
		$obj_page = L::loadClass('page','index');
		$arr_languages = get_languages();
		$str_name = str_addslashes($_POST['name']);
		foreach($arr_languages as $v){
			$arr_language_names[$v['cname']] = str_addslashes($_POST['name_'.$v['cname']]);
		}
		$int_sort = intval($_POST['sort']);
		$int_pid  = intval($_POST['pid']);
		$int_level= 1;
		if($int_pid>0){
			$arr_data = $obj_page->get_one_cat($int_pid);
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
		$obj_page->_cat_insert($arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function do_cat_mod(){
		global $_SGLOBAL;
		$obj_page = L::loadClass('page','index');
		$arr_languages = get_languages();
		$int_page_id  = intval($_POST['page_id']);
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
		$obj_page->_cat_update($int_page_id,$arr_data);
		callback(array('info'=>'','status'=>200));
	}
	
	function extra_get_one_cat(){
		global $_SGLOBAL;
		$int_page_id = intval($_GET['page_id']);
		if(empty($int_page_id)){
			callback(array('info'=>'ID不能为空','status'=>304));	
		}
		$obj_page = L::loadClass('page','index');
		$arr_data = $obj_page->get_one_cat($int_page_id);
		callback(array('info'=>'','status'=>200,'data'=>$arr_data));
	}
}