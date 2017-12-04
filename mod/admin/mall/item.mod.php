<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_item {
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
		$int_page = intval($_GET['page']);
		$str_language = str_addslashes($_GET['language']);
		$str_kwd   = trim(str_addslashes($_GET['kwd']));
		$int_cid_1 = intval($_GET['cid_1']);
		$int_cid_2 = intval($_GET['cid_2']);
		$int_status = intval($_GET['status']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 20;
		$obj_item_cat = L::loadClass('admin_mall_item_cat','admin');
		$obj_item   = L::loadClass('item','index');
		$int_status = $int_status ? $int_status : LEM_item::ACTIVE;
		$arr_data = array();
		$str_kwd && $arr_data['title'] = array('do'=>'like','val'=>"%{$str_kwd}%");
		$int_cid_1 && $arr_data['cid_1'] = $int_cid_1;
		$int_cid_2 && $arr_data['cid_2'] = $int_cid_2;
		$str_language && $arr_data['language'] = $str_language;
		$int_status && $arr_data['status'] = $int_status;
		$arr_data['is_del'] = 0;

		$arr_list = $obj_item->get_list($arr_data,$int_page,$int_page_size,'`sort` ASC,`item_id` DESC');
		$arr_data = array('is_del'=>0);
		$arr_cat  = $obj_item_cat->get_list($arr_data);
		$json_cat = json_encode($arr_cat);
		$arr_cat  = format_array_val_to_key($arr_cat,'cid');
		
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		$arr_language = $_SGLOBAL['language'];
		//var_export($arr_language);
		include template('template/admin/mall/item/index');
	}
	
	function extra_add(){
		global $_SGLOBAL;
		$arr_languages= get_languages(true);
		$arr_language = $_SGLOBAL['language'];
		$int_item_id  = intval($_GET['item_id']);
		$int_copy     = intval($_GET['copy']);
		$obj_item_cat = L::loadClass('admin_mall_item_cat','admin');
		$obj_item     = L::loadClass('item','index');
		$int_active = LEM_item::ACTIVE;
		$int_inactive = LEM_item::INACTIVE;
		if(submit_check('formhash')){
			$str_form_do = str_addslashes($_POST['form_do']);
			if (method_exists($this, 'do_' . $str_form_do)) {
				$str_function_name = 'do_' . $str_form_do;
				$this->$str_function_name();
			}
			exit;
		}
		$arr_data = array('is_del'=>0);
		$arr_cat  = $obj_item_cat->get_list($arr_data);
		$json_cat = json_encode($arr_cat);
		$arr_config = get_config("'item_main_pic','item_sku_pic','item_editor_pic'");
		$json_config = json_encode($arr_config);
		$str_main_pic_size = explode('|',$arr_config['item_main_pic']['val']);
		$str_main_pic_size = str_replace(',','px*',$str_main_pic_size[0]).'px';
		$arr_sku = $obj_item->get_list_sku();

		if($int_item_id){
			$arr_item = $obj_item->get_one($int_item_id);
			$arr_pics = array();
			$arr_item['detail']['pics'] && $arr_pics = explode(',',$arr_item['detail']['pics']);
			$arr_item_sku_1 = format_array_val_to_key($arr_item['sku'],'sku_1');
			$arr_item_sku_2 = format_array_val_to_key($arr_item['sku'],'sku_2');
			$arr_item_sku = array();
			foreach ($arr_item['sku'] as $k=>$v) {
                $arr_sku_1[$v['sku_1']] = $obj_item->get_one_sku($v['sku_1']);
                $arr_sku_2[$v['sku_2']] = $obj_item->get_one_sku($v['sku_2']);
                $arr_ret_item_sku[$v['sku_1'] . '_' . $v['sku_2']] = $v;
                if($k == 0){
                    $arr_temp_first_sku = $v;
                }
            }
            $arr_sku_title = array($arr_item['main']['sku_title_1'],$arr_item['main']['sku_title_2']);
            $arr_item_sku = array('sku_title' => array_filter($arr_sku_title), 'sku_1' => array_values($arr_sku_1), 'sku_2' => array_values($arr_sku_2), 'item_sku' => $arr_ret_item_sku);
			$json_item_sku = json_encode($arr_item_sku);
		}
		//var_export($arr_item_sku);
		include template('template/admin/mall/item/add');
	}
	
	function do_add(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		
		$str_language= str_addslashes($_POST['language']);
		$int_item_id = intval($_POST['item_id']);
		$int_cid_1   = intval($_POST['cid_1']);
		$int_cid_2   = intval($_POST['cid_2']);
		//$str_short_des = str_addslashes($_POST['short_des']);
		$int_status  = intval($_POST['status']);
		$str_title   = str_addslashes($_POST['title']);
                $int_is_hot   = $_POST['is_hot'] == "on" ? 1 : 0;
		$int_is_remind   = $_POST['is_remind'] == "on" ? 1 : 0;
		$str_des     = str_addslashes($_POST['des']);
		$str_des_2   = str_addslashes($_POST['des_2']);
		//$str_pic_url = str_addslashes($_POST['pic_url']);
		$arr_pic = str_addslashes($_POST['pic']);
		$str_pic_url = $arr_pic[0];
		$str_content = str_addslashes($_POST['content']);
		$str_page_title = str_addslashes($_POST['page_title']);
		$str_page_kwd = str_addslashes($_POST['page_kwd']);
		$str_page_des = str_addslashes($_POST['page_des']);
		$int_sort    = intval($_POST['sort']);
		$arr_sku_1 = str_addslashes($_POST['sku_1']);
		$arr_sku_2 = str_addslashes($_POST['sku_2']);
		$arr_sku_price = $_POST['sku_price'];
		$arr_sku_sales_price = $_POST['sku_sales_price'];
		$arr_sku_stock = $_POST['sku_stock'];
		$arr_sku_code  = $_POST['sku_code'];
		$arr_sku_bar_code  = $_POST['sku_bar_code'];
		$arr_sku_title= str_addslashes($_POST['sku_title']);
		
		
		$float_price = floatval($_POST['price']);

		$int_timestamp = $_SGLOBAL['timestamp'];
		$obj_item = L::loadClass('item','index');
		$arr_data = array(
						'title'=>$str_title,
						'is_del'=>0
					);
		$arr_temp = $obj_item->get_list($arr_data,1,1);
		if($arr_temp['total']>0 && $arr_temp['list'][0]['item_id']!=$int_item_id){
			callback(array('info'=>$arr_language['item']['error_1'],'status'=>304));	
		}
		
		$arr_data = array(
						'language'=>$str_language,
						'cid_1'=>$int_cid_1,
						'cid_2'=>$int_cid_2,
						'status'=>$int_status,
						'is_hot'=>$int_is_hot,
						'is_remind'=>$int_is_remind,
						'title'=>$str_title,
						'short_des'=>$str_short_des,
						'pic'=>$str_pic_url,
						'price'=>$float_price,
						'sort'=>$int_sort,
						'sku_title_1'=>$arr_sku_title[0],
						'sku_title_2'=>$arr_sku_title[1],
						'mod_date'=>$int_timestamp,
						'in_date'=>$int_timestamp,
					);
		$bool_do = 'insert';
		if($int_item_id>0){
			$bool_do = 'update';
			unset($arr_data['in_date']);
			//var_export($arr_data);
			$obj_item->update_main(array('item_id'=>$int_item_id),$arr_data);
			$arr_item = $obj_item->get_one_main($int_item_id);
			$int_timestamp = $arr_item['in_date'];
		}
		else{
			$int_item_id = $obj_item->insert_main($arr_data);	
		}
		$arr_data = array(
						'item_id'=>$int_item_id,
						'des'=>$str_des,
						'des_2'=>$str_des_2,
						'pics'=>implode(',',$arr_pic),
						'content'=>$str_content,
						'page_title'=>$str_page_title,
						'page_kwd'=>$str_page_kwd,
						'page_des'=>$str_page_des,
					);
		if($bool_do=='update'){
			$obj_item->update_detail(array('item_id'=>$int_item_id),$arr_data);
		}
		else{
			$obj_item->insert_detail($arr_data);
		}
		
		$arr_sku = array();
	
		foreach ($arr_sku_1 as $k => $v) {
			$arr_sku[$k]['sku_1'] = strval($v) == '' ? '0|' : strval($v);
			$arr_sku[$k]['sku_2'] = strval($_POST['sku_2'][$k]) == '' ? '0|' : strval($_POST['sku_2'][$k]);
			$arr_sku[$k]['price'] = $_POST['sku_price'][$k];
			//$arr_sku[$k]['sales_price'] = $_POST['sku_sales_price'][$k];
			$arr_sku[$k]['stock'] = $_POST['sku_stock'][$k];
			//$arr_sku[$k]['free_buy_stock'] = $_POST['sku_free_buy_stock'][$k];
			//$arr_sku[$k]['sku_code'] = $_POST['sku_code'][$k];
			//$arr_sku[$k]['bar_code'] = $_POST['sku_bar_code'][$k];
			$arr_sku[$k]['pic'] = $_POST['sku_pic'][$k];
			$arr_sku[$k]['item_id'] = $int_item_id;
			$arr_sku[$k]['cid_1'] = $int_cid_1;
			$arr_sku[$k]['cid_2'] = $int_cid_2;
			$arr_sku[$k]['status'] = $int_status;
		}
		$obj_item->update_one_item_sku($arr_sku,$int_item_id);
		callback(array('info'=>$arr_language['ok_2'],'status'=>200));
	}
	
	function do_upload(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$str_upload_type = str_addslashes($_POST['upload_type']);
		$arr_config = get_config('item_main_pic');
		//var_export($arr_config);exit;
		$str_path = save_image($_FILES['pic']['tmp_name'],$arr_config['val']);
		if(empty($str_path)){
			callback(array('info'=>$arr_language['uploads']['error_1'],'status'=>304));		
		}
		callback(array('info'=>'','status'=>200,'data'=>$str_path));
	}
	
	function extra_del(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_item_id     = intval($_GET['item_id']);
		if(empty($int_item_id)){
			return false;	
		}
		$obj_item = L::loadClass('item','index');
		$obj_item->update_main(array('item_id'=>$int_item_id),array('is_del'=>1));
		$obj_item->update_sku(array('item_id'=>$int_item_id),array('is_del'=>1));
		callback(array('info'=>$arr_language['del_success'],'status'=>200));
	}
	
        function extra_bat_inactive(){
                global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];                
                $str_ids     = str_addslashes($_GET['ids']);                
                if(empty($str_ids)){
                    return false;
                }
                $str_ids = substr($str_ids, 0, -1);
                $arr_id = explode(',', $str_ids);
                
               $obj_item = L::loadClass('item','index');
                foreach($arr_id as $int_id){
                    $arr_item = $obj_item->get_one_main($int_id);		
                    if($arr_item['status']==LEM_item::ACTIVE){
                            $arr_data = array('status'=>LEM_item::INACTIVE);
                            $obj_item->update_main(array('item_id'=>$int_id),$arr_data);                            
                    }
                                       
                }
                callback(array('info'=>$arr_language['ok_2'],'status'=>200));
        }
	

}