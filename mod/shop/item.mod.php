<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_item {

        private $arr_parent_permission = array(
		'shop_shop_item_index'=>'admin_shop',
        );



	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
                $obj_admin_member = L::loadClass('admin_member','admin');
		if (method_exists($this, 'extra_' . $extra)) {
                        $str_menu_alias_name = MOD_DIR.'_'.SCR.'_'.$extra;
                        isset($this->arr_parent_permission[$str_menu_alias_name]) && $str_menu_alias_name = $this->arr_parent_permission[$str_menu_alias_name];
                        // $bool_permission = $obj_admin_member->verify_permission($str_menu_alias_name);
			$str_function_name = 'extra_' . $extra;
		}
                $this->$str_function_name();
	}

	function extra_index(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$arr_member = $_SGLOBAL['member'];
		$int_page = intval($_GET['pege']);
		$int_page = $int_page ? $int_page :1;
		$int_page_size = 20;

                $obj_item_cat = L::loadClass('admin_mall_item_cat','admin');
                $obj_item   = L::loadClass('item','index');
                $int_status = $int_status ? $int_status : LEM_item::ACTIVE;
                $arr_data = array();
                $str_kwd && $arr_data['title'] = array('do'=>'like','val'=>"%{$str_kwd}%");
                $int_cid_1 && $arr_data['cid_1'] = $int_cid_1;
                $int_cid_2 && $arr_data['cid_2'] = $int_cid_2;
                $str_language && $arr_data['language'] = $str_language;
                // $int_status && $arr_data['status'] = $int_status;
                $arr_data['uid'] = $arr_member['uid'];
                $arr_data['is_del'] = 0;

                $arr_list = $obj_item->get_list($arr_data,$int_page,$int_page_size,'`sort` ASC,`item_id` DESC');
                $arr_data = array('is_del'=>0);
                $arr_cat  = $obj_item_cat->get_list($arr_data);
                $json_cat = json_encode($arr_cat);
                $arr_cat  = format_array_val_to_key($arr_cat,'cid');

                $obj_shop   = L::loadClass('shop','index');
                $arr_type_name = $obj_shop->get_type_name();
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		include template('template/shop/item/index');
	}


	function extra_add(){
		global $_SGLOBAL;
                $arr_member = $_SGLOBAL['member'];
                $arr_language = $_SGLOBAL['language'];
                $int_item_id = intval($_GET['item_id']);
                $int_temp_id = intval($_GET['temp_id']);
                $obj_item = L::loadClass('item','index');
                $obj_shop = L::loadClass('shop','index');
                $obj_item_keyword = L::loadClass('item_keyword','index');
                $obj_item_comment = L::loadClass('item_comment','index');
                $arr_config = get_config("'item_main_pic','item_sku_pic','item_editor_pic'");
                $json_config = json_encode($arr_config);
                $str_main_pic_size = explode('|',$arr_config['item_main_pic']['val']);
                $str_main_pic_size = str_replace(',','px*',$str_main_pic_size[0]).'px';
		if(submit_check('formhash')){
                        $str_form_do = str_addslashes($_POST['form_do']);
                                if (method_exists($this, 'do_' . $str_form_do)) {
                                        $str_function_name = 'do_' . $str_form_do;
                                        $this->$str_function_name();
                                }
                                exit;
		}
                $arr_data = array('uid'=>$arr_member['pid'],'is_del'=>0);
                $arr_data['status'] = 110;
                $arr_shop = $obj_shop->get_list($arr_data,1,100);
                if ($int_item_id) {
                        $arr_item = $obj_item->get_one($int_item_id);
                }elseif ($int_temp_id) {
                        $arr_item = $obj_item->get_one_temp($int_temp_id);
                }
                $arr_type_name = $obj_shop->get_type_name();
                $arr_item['detail']['pics'] && $arr_pics = explode(',', $arr_item['detail']['pics']);
                // var_export($arr_pics);
                $arr_item['main']['require'] && $arr_require = unserialize($arr_item['main']['require']);
                $arr_item['main']['huobi_pic'] && $arr_huobi_pic = unserialize($arr_item['main']['huobi_pic']);
                // var_export($arr_require);
                $arr_sku_price = $obj_item->get_one_sku_price(array('id'=>1));
                $arr_sku_price2 = $obj_item->get_one_sku_price(array('id'=>2));
                $json_sku_price = json_encode($arr_sku_price);
                $json_sku_price2 = json_encode($arr_sku_price2);
                $arr_price_name = $obj_item->get_price_name();
                if ($int_item_id) {
                $arr_keyword = $obj_item_keyword->get_list(array('item_id'=>$int_item_id),1,100);
                $arr_comment = $obj_item_comment->get_list(array('item_id'=>$int_item_id),1,100);
                $arr_comment_pic = $obj_item_comment->get_list_pic(array('item_id'=>$int_item_id),1,100);
                }elseif ($int_temp_id) {
                        $arr_keyword = $obj_item_keyword->get_list_temp(array('item_id'=>$int_temp_id),1,100);
                        $arr_comment = $obj_item_comment->get_list_temp(array('item_id'=>$int_temp_id),1,100);
                        $arr_comment_pic = $obj_item_comment->get_list_pic_temp(array('item_id'=>$int_temp_id),1,100);
                }
                // var_export($arr_comment);

                $arr_temp_list = $obj_item->get_list_temp(array('uid'=>$arr_member['uid'],'is_del'=>0),1,100,'`sort` ASC,`item_id` DESC');
		include template('template/shop/item/add');
	}

        function do_add(){
                global $_SGLOBAL;
                $arr_member = $_SGLOBAL['member'];
                // var_export($_POST);
                // exit();
                $obj_item            = L::loadClass('item','index');
                $obj_item_comment    = L::loadClass('item_comment','index');
                $obj_item_keyword    = L::loadClass('item_keyword','index');
                $str_language        = str_addslashes($_POST['language']);
                $int_item_id         = intval($_POST['item_id']);
                $int_cid_1           = intval($_POST['cid_1']);
                $int_sid             = intval($_POST['sid']);
                $int_shop_type       = intval($_POST['shop_type']);
                $int_buy_num         = intval($_POST['buy_num']);
                $int_huabei          = intval($_POST['huabei']);
                $int_wangwang        = intval($_POST['wangwang']);
                $int_is_collect_item = intval($_POST['is_collect_item']);
                $int_is_collect_shop = intval($_POST['is_collect_shop']);
                $int_is_add_cart     = intval($_POST['is_add_cart']);
                $int_duankou         = intval($_POST['duankou']);
                $int_total_stock     = intval($_POST['total_stock']);
                $int_hour_send       = intval($_POST['hour_send']);
                $int_province        = intval($_POST['province']);
                $int_city            = intval($_POST['city']);
                $int_area            = intval($_POST['area']);
                $int_is_shenhe       = intval($_POST['is_shenhe']);
                $int_is_temp         = intval($_POST['is_temp']);
                $str_title           = str_addslashes($_POST['title']);
                $str_item_title      = str_addslashes($_POST['item_title']);
                $arr_pic             = str_addslashes($_POST['pic']);
                $float_price         = floatval($_POST['price']);
                $float_total_price   = floatval($_POST['total_price']);
                $float_total_price_2 = floatval($_POST['total_price_2']);
                $arr_require         = str_addslashes($_POST['require']);
                $arr_keyword         = str_addslashes($_POST['keyword']);
                $arr_keyword_num     = str_addslashes($_POST['keyword_num']);
                $arr_comment         = str_addslashes($_POST['comment']);
                $arr_comment_pic     = str_addslashes($_POST['comment_pic']);
                $str_kouling         = str_addslashes($_POST['kouling']);
                $arr_key_word        = str_addslashes($_POST['key_word']);
                $str_item_link       = str_addslashes($_POST['item_link']);
                $str_coupon_link     = str_addslashes($_POST['coupon_link']);
                $str_item_attr       = str_addslashes($_POST['item_attr']);
                $str_send_address    = str_addslashes($_POST['send_address']);
                $str_release_time    = str_addslashes($_POST['release_time']);
                $str_remark          = str_addslashes($_POST['remark']);
                $str_content         = str_addslashes($_POST['content']);
                $arr_huobi_pics      = str_addslashes($_POST['data_pic']);
                $arr_huobi_name      = str_addslashes($_POST['shop_name']);
                $int_total_stock     = array_sum($arr_keyword_num);
                $int_stock           = 0;
                $int_send_stock      = 0;
                if ($int_hour_send) {
                        !$_POST['release_time'] && $int_stock = $int_hour_send;
                        !$_POST['release_time'] && $int_send_stock = $int_hour_send;
                }else{
                        !$_POST['release_time'] && $int_stock = $int_total_stock;
                        !$_POST['release_time'] && $int_send_stock = $int_total_stock;
                }

                if (!empty($arr_huobi_pics)) {
                        $arr_huobi_pic = array();
                        foreach ($arr_huobi_pics as $k => $v) {
                                $arr_huobi_pic[] = array('pic'=>$v,'shop_name'=>$arr_huobi_name[$k]);
                        }
                }

                $str_pic_url    = $arr_pic[0];
                $float_price    = floatval($_POST['price']);
                $arr_sku_price  = $obj_item->get_one_sku_price(array('id'=>1));
                $arr_sku_price2 = $obj_item->get_one_sku_price(array('id'=>2));
                foreach ($arr_require as $key => $value) {
                        if (!$value) {
                                unset($arr_require[$key]);
                                continue;
                        }
                        if (!in_array($key, array('height','weight','local','province','priority','price','price2'))) {
                                $arr_require[$key] = array('val'=>$value,'price'=>$arr_sku_price[$value],'price2'=>$arr_sku_price2[$value]);
                        }else{
                                if ($key!='price' && $key!='price2') {
                                        $arr_require[$key] = array('val'=>$value,'price'=>$arr_sku_price[$key],'price2'=>$arr_sku_price2[$key]);
                                }
                        }

                }
                if ($arr_require['price']) {
                        $arr_require['price'] = array('val'=>'price','price'=>$arr_require['price'],'price2'=>$arr_require['price2']);
                        unset($arr_require['price2']);
                }
                // var_export($arr_require);
                // exit();
                $str_require = serialize($arr_require);
                $int_timestamp = $_SGLOBAL['timestamp'];
                $arr_data = array(
                                'status'          =>100,
                                'sid'             =>$int_sid,
                                'uid'             =>$arr_member['uid'],
                                'shop_type'       =>$int_shop_type,
                                'cid_1'           =>$int_cid_1,
                                'buy_num'         =>$int_buy_num,
                                'huabei'          =>$int_huabei,
                                'wangwang'        =>$int_wangwang,
                                'is_collect_item' =>$int_is_collect_item,
                                'is_collect_shop' =>$int_is_collect_shop,
                                'is_add_cart'     =>$int_is_add_cart,
                                'duankou'         =>$int_duankou,
                                'stock'           =>$int_stock,
                                'total_stock'     =>$int_total_stock,
                                'hour_send'       =>$int_hour_send,
                                'province'        =>$int_province,
                                'city'            =>$int_city,
                                'area'            =>$int_area,
                                'is_shenhe'       =>$int_is_shenhe,
                                'last_stock'      =>$int_total_stock-$int_send_stock,
                                'title'           =>$str_title,
                                'item_title'      =>$str_item_title,
                                'kouling'         =>$str_kouling,
                                'keyword'         =>implode(',',$arr_keyword),
                                'item_link'       =>$str_item_link,
                                'coupon_link'     =>$str_coupon_link,
                                'item_attr'       =>$str_item_attr,
                                'require'         =>$str_require,
                                'remark'          =>$str_remark,
                                'send_address'    =>$str_send_address,
                                'release_time'    =>strtotime($str_release_time),
                                'pic'             =>$str_pic_url,
                                'price'           =>$float_price,
                                'total_price'     =>$float_total_price,
                                'total_price_2'   =>$float_total_price_2,
                                'payment'         =>$float_total_price*$int_total_stock,
                                'commission'      =>$float_total_price_2-$float_price*$int_buy_num,
                                'huobi_pic'       =>serialize($arr_huobi_pic),
                                'mod_date'        =>$int_timestamp,
                                'in_date'         =>$int_timestamp,
                        );
                // var_export($arr_data);
                // exit();
                $bool_do = 'insert';
                if($int_item_id>0){
                        $bool_do = 'update';
                        unset($arr_data['in_date']);
                        //var_export($arr_data);
                        $obj_item->update_main(array('item_id'=>$int_item_id),$arr_data);
                        // $arr_item = $obj_item->get_one_main($int_item_id);
                        // $int_timestamp = $arr_item['in_date'];
                }
                else{
                        $int_item_id = $obj_item->insert_main($arr_data);



                }
                if ($int_is_temp) {
                        $arr_data['title'] = $str_title;
                        $int_item_temp_id = $obj_item->insert_main_temp($arr_data);
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
                if ($int_is_temp) {
                        $arr_data['item_id'] = $int_item_temp_id;
                        $obj_item->insert_detail_temp($arr_data);
                }

                // 删除旧数据
                if($bool_do=='insert'){
                        // $obj_item_keyword->del(array('item_id'=>$int_item_id));
                        // $obj_item_comment->del(array('item_id'=>$int_item_id));
                        // $obj_item_comment->del_pic(array('item_id'=>$int_item_id));
                
                        //插入关键字
                        foreach ($arr_keyword as $k => $v) {
                                $int_stock_one = 0;
                                if ($int_stock>=$arr_keyword_num[$k]) {
                                        $int_stock_one = $arr_keyword_num[$k];
                                        $int_last_one = 0;
                                        $int_stock = $int_stock-$arr_keyword_num[$k];
                                }else{
                                        $int_stock_one = $int_stock;
                                        $int_last_one = $arr_keyword_num[$k]-$int_stock;
                                        $int_stock = 0;
                                }
                                $arr_data_keyword = array(
                                        'item_id'=>$int_item_id,
                                        'keyword'=>$v,
                                        'stock'=>$int_stock_one,
                                        'last_stock'=>$int_last_one,
                                        'total_stock'=>$arr_keyword_num[$k]
                                );
                                $obj_item_keyword->insert($arr_data_keyword);
                                if ($int_is_temp) {
                                        $arr_data_keyword['item_id'] = $int_item_temp_id;
                                        $obj_item_keyword->insert_temp($arr_data_keyword);
                                }
                        }
                        
                        foreach ($arr_comment as $k => $v) {
                                $arr_data_comment = array(
                                        'item_id'=>$int_item_id,
                                        'comment'=>$v,
                                );
                                $obj_item_comment->insert($arr_data_comment);
                                if ($int_is_temp) {
                                        $arr_data_comment['item_id'] = $int_item_temp_id;
                                        $obj_item_comment->insert_temp($arr_data_comment);
                                }
                                $arr_data_pic = array(
                                        'item_id'=>$int_item_id,
                                        'pics'=>implode(',', $arr_comment_pic[$k])
                                );
                                $obj_item_comment->insert_pic($arr_data_pic);
                                if ($int_is_temp) {
                                        $arr_data_pic['item_id'] = $int_item_temp_id;
                                        $obj_item_comment->insert_pic_temp($arr_data_pic);
                                }
                        }
                }
                $arr_item = $obj_item->get_one_main($int_item_id);
                // var_export($arr_item);
                if ($arr_item['is_pay']!=1 && $arr_item['total_stock']) {
                        $obj_user = L::loadClass('user','index');
                        $obj_money_log = L::loadClass('money_log','index');
                        $arr_member = $obj_user->get_one($arr_member['uid']);
                        if ($arr_member['balance']<$arr_item['total_price']*$arr_item['total_stock']) {
                                $obj_item->update_main(array('item_id'=>$int_item_id),array('status'=>100,'is_pay'=>0));
                                callback(array('info'=>'余额不足','status'=>200,'data'=>$str_path));
                        }
                        $int_time = time();
                        $arr_sqls = array();
                        $arr_sqls[] = $obj_user->_update($arr_member['uid'],array('balance'=>array('do'=>'inc','val'=>-$arr_item['total_price']*$arr_item['total_stock'])),true);
                        $arr_sqls[] = $obj_item->update_main(array('item_id'=>$int_item_id),array('is_pay'=>1,'status'=>200),true);
                        $arr_money = array(
                                'type'       =>10,
                                'uid'        =>$arr_member['uid'],
                                'sid'        =>$arr_item['sid'],
                                'item_id'    =>$arr_item['item_id'],
                                'money'      =>$arr_item['total_price']*$arr_item['total_stock'],
                                'content'    =>'发布任务',
                                'in_date'    =>$int_time,
                        );
                        $arr_money_log = $obj_money_log->get_one(array('type'=>10,'uid'=>$arr_money['uid'],'sid'=>$arr_money['sid'],'item_id'=>$arr_money['item_id']));
                        if(!$arr_money_log) {
                                $arr_sqls[] = $obj_money_log->insert_money_log($arr_money,true);
                        }
                        var_export($arr_sqls);
                        $bool_return = $_SGLOBAL['trans_db']->multi_query($arr_sqls);
                        if ($bool_return) {
                                callback(array('info'=>'添加完成，并支付'.$arr_item['total_price']*$arr_item['total_stock'].'元','status'=>200));
                        }else{
                                callback(array('info'=>'支付失败，添加失败','status'=>304));
                        }
                }


                callback(array('info'=>'添加完成','status'=>200,'data'=>$str_path));
        }



	function do_upload(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$str_upload_type = str_addslashes($_POST['upload_type']);
		$arr_config = get_config('item_main_pic');
                var_export($_FILES['pic']['tmp_name']);
		$str_path = save_image($_FILES['pic']['tmp_name'],'500');
		if(empty($str_path)){
			callback(array('info'=>$arr_language['uploads']['error_1'],'status'=>304));
		}
		callback(array('info'=>'','status'=>200,'data'=>$str_path));
	}


	function extra_del(){
		global $_SGLOBAL;
                $arr_member = $_SGLOBAL['member'];
		$arr_language = $_SGLOBAL['language'];
		$int_item_id  = intval($_GET['item_id']);
		if(empty($int_item_id)){
			return false;
		}
		$obj_item = L::loadClass('item','index');
                $obj_user = L::loadClass('user','index');
                $obj_money_log = L::loadClass('money_log','index');
                $int_time = time();
                $arr_sqls = array();
                $arr_item = $obj_item->get_one_main($int_item_id);
                $arr_sqls[] = $obj_user->_update($arr_member['uid'],array('balance'=>array('do'=>'inc','val'=>$arr_item['total_price']*$arr_item['last_stock'])),true);
                $arr_sqls[] = $obj_item->update_main(array('item_id'=>$int_item_id),array('is_del'=>1),true);
                $arr_money = array(
                        'type'       =>11,
                        'uid'        =>$arr_member['uid'],
                        'sid'        =>$arr_item['sid'],
                        'item_id'    =>$arr_item['item_id'],
                        'money'      =>$arr_item['total_price']*$arr_item['last_stock'],
                        'content'    =>'删除任务',
                        'in_date'    =>$int_time,
                );
                $arr_money_log = $obj_money_log->get_one(array('type'=>11,'uid'=>$arr_money['uid'],'sid'=>$arr_money['sid'],'item_id'=>$arr_money['item_id']));
                if(!$arr_money_log) {
                        $arr_sqls[] = $obj_money_log->insert_money_log($arr_money,true);
                }
                // var_export($arr_sqls);
                // exit();
                $bool_return = $_SGLOBAL['trans_db']->multi_query($arr_sqls);
                if ($bool_return) {
                        callback(array('info'=>$arr_language['del_success'],'status'=>200));
                }else{
                        callback(array('info'=>'删除失败','status'=>304));
                }
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

       $obj_shop_item = L::loadClass('shop_item','index');
        foreach($arr_id as $int_id){
            $arr_item = $obj_shop_item->get_one(array('item_id'=>$int_id));
            if($arr_item['status']==200){
                    $arr_data = array('status'=>100);
                    $obj_shop_item->update(array('item_id'=>$int_id),$arr_data);
            }
            if($arr_item['status']==100){
                    $arr_data = array('status'=>200);
                    $obj_shop_item->update(array('item_id'=>$int_id),$arr_data);
            }

        }
        callback(array('info'=>$arr_language['ok_2'],'status'=>200));
    }
}
