<?php
!defined('LEM') && exit('Forbidden');

// define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_order {

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
		$int_page = intval($_GET['page']);
		$int_page = $int_page?$int_page:1;
		$int_page_size = 20;
		$int_status = intval($_GET['status']);
		$arr_language = $_SGLOBAL['language'];
		$arr_member  = $_SGLOBAL['member'];
		$obj_shop = L::loadClass('shop','index');
		$obj_item = L::loadClass('item','index');
		$obj_member = L::loadClass('member','index');
		$obj_order = L::loadClass('order','index');
		$arr_data = array('is_del'=>0);
		$arr_data = array('shop_uid'=>$arr_member['uid']);
		$int_status && $arr_data['status'] = $int_status;
		$arr_list = $obj_order->get_list($arr_data);
		// var_export($arr_list);
		$obj_shop   = L::loadClass('shop','index');
                $arr_type_name = $obj_shop->get_type_name();
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
                $str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		include template('template/shop/order/index');

	}

	function extra_detail(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_order_id = intval($_GET['order_id']);
		$obj_order = L::loadClass('order','index');
		$obj_item = L::loadClass('item','index');
		$obj_district = L::loadClass('district','index');
		$arr_data = $obj_order->get_one($int_order_id);
		$arr_price_name = $obj_item->get_price_name();
		$arr_data['item']['require'] && $arr_data['item']['require'] = unserialize($arr_data['item']['require']);
		// var_export($arr_data);
		$arr_district_ids = array();
		$arr_district_ids[] = $arr_data['buyer_detail']['province'];
		$arr_district_ids[] = $arr_data['buyer_detail']['city'];
		$arr_district_ids[] = $arr_data['buyer_detail']['area'];
		$arr_district_ids = array_filter(array_unique($arr_district_ids));
		if($arr_district_ids){
			$arr_data_district = array('id'=>array('do'=>'in','val'=>implode(',',$arr_district_ids)));
			$arr_district = $obj_district->get_list($arr_data_district,'id');
		}
		// var_export($arr_district);
		// var_export($arr_price_name);
		include template('template/shop/order/detail');
	}



	function extra_confirm(){
		global $_SGLOBAL;
		$int_order_id = intval($_GET['order_id']);
		$int_status = intval($_GET['status']);
		$str_fail_reason = str_addslashes($_GET['fail_reason']);
		// var_export($_GET);
		// exit();
		$obj_order = L::loadClass('order','index');
		$obj_member = L::loadClass('member','index');
		$obj_money_log = L::loadClass('money_log','index');
		$arr_order = $obj_order->get_one_main($int_order_id);
		$arr_member = $obj_member->get_one_member($arr_order['uid']);
		$arr_config = get_config('config_contact',true);
                $arr_config = $arr_config['val'];
		$int_time = time();
		$arr_data = array('status'=>$int_status,'confirm_date'=>$int_time);
		$str_fail_reason && $arr_data['fail_reason'] = $str_fail_reason;
		$arr_sqls = array();
		$arr_sqls[] = $obj_order->update_main(array('order_id'=>$int_order_id),$arr_data,true);
		if ($int_status==200) {
			$arr_data_member = array('commission'=>array('do'=>'inc','val'=>$arr_order['price']));
			if ($arr_member['r_uid']) {
				if ($arr_member['jie_commission']>=$arr_config['data_jie_gold']) {
					$float_jie_commission = $arr_config['data_jie_gold'];
				}else{
					$float_jie_commission = $arr_member['jie_commission'];
				}
				if ($float_jie_commission>0) {
					$arr_sqls[] = $obj_member->update_member(array('uid'=>$arr_member['r_uid']),array('commission'=>array('do'=>'inc','val'=>-$float_jie_commission),'balance'=>array('do'=>'inc','val'=>$float_jie_commission)),true);
					$arr_data_member['jie_commission'] = array('do'=>'inc','val'=>-$float_jie_commission);
				}
			}
			$arr_sqls[] = $obj_member->update_member(array('uid'=>$arr_order['uid']),$arr_data_member,true);
			$arr_money = array(
	                        'type'       =>20,
	                        'uid'        =>$arr_order['uid'],
	                        'sid'        =>$arr_order['sid'],
	                        'order_id'   =>$int_order_id,
	                        'item_id'    =>$arr_order['item_id'],
	                        'money'      =>$arr_order['price'],
	                        'content'    =>'用户完成任务',
	                        'in_date'    =>$int_time,
	                );
	                $arr_money_log = $obj_money_log->get_one(array('type'=>20,'uid'=>$arr_money['uid'],'sid'=>$arr_money['sid'],'order_id'=>$arr_money['order_id']));
	                if(!$arr_money_log) {
	                        $arr_sqls[] = $obj_money_log->insert_money_log($arr_money,true);
	                } 
		}
		// var_export($arr_sqls);
		// exit();
		$bool_return = $_SGLOBAL['trans_db']->multi_query($arr_sqls);
		callback(array('info'=>'确认完成','status'=>200));
	}

	function extra_confirm_money(){
		global $_SGLOBAL;
		$int_order_id = intval($_GET['order_id']);
		$int_status = intval($_GET['status']);
		$obj_order = L::loadClass('order','index');
		$obj_member = L::loadClass('member','index');
		$obj_money_log = L::loadClass('money_log','index');
		$arr_order = $obj_order->get_one_main($int_order_id);
		$arr_member = $obj_member->get_one_member($arr_order['uid']);
		$arr_config = get_config('config_contact',true);
                $arr_config = $arr_config['val'];
		$int_time = time();
		$arr_data = array('pay_date'=>$int_time,'is_pay'=>1);
		$arr_sqls = array();
		$arr_sqls[] = $obj_order->update_main(array('order_id'=>$int_order_id),$arr_data,true);
		$arr_data_member = array(
			'commission'=>array('do'=>'inc','val'=>-$arr_order['price']),
			'balance'=>array('do'=>'inc','val'=>$arr_order['price']),
		);
		$arr_sqls[] = $obj_member->update_member(array('uid'=>$arr_order['uid']),$arr_data_member,true);

		$bool_return = $_SGLOBAL['trans_db']->multi_query($arr_sqls);
		callback(array('info'=>'确认完成','status'=>200));
	}

	function extra_cancel(){
		global $_SGLOBAL;
		$int_order_id = intval($_GET['order_id']);
		$int_status = intval($_GET['status']);
		// var_export($_GET);
		// exit();
		$obj_order = L::loadClass('order','index');
		$arr_data = array('status'=>90);
		$result = $obj_order->update_main(array('order_id'=>$int_order_id),$arr_data);
		if ($result) {
			$arr_order = $obj_order->get_one_main($int_order_id);
			$obj_item->update_main(array('item_id'=>$arr_order['item_id']),array('stock'=>array('do'=>'inc','val'=>1)));
		}
		callback(array('info'=>'取消成功','status'=>200));
	}


	function extra_shenhe(){
		global $_SGLOBAL;
		$int_order_id = intval($_GET['order_id']);
		$int_status = intval($_GET['status']);
		// var_export($_GET);
		// exit();
		$obj_order = L::loadClass('order','index');
		$obj_item = L::loadClass('item','index');
		$obj_member = L::loadClass('member','index');
		$obj_money_log = L::loadClass('money_log','index');
		$arr_order = $obj_order->get_one_main($int_order_id);
		$arr_data = array('status'=>$int_status);
		$int_time = time();
		$arr_config = get_config('config_contact',true);
                $arr_config = $arr_config['val'];
		$arr_sqls = array();
		if ($int_status==304) {
			$arr_sqls = array();
			$arr_sqls[] = $obj_order->update_main(array('order_id'=>$int_order_id),$arr_data,true);
			$arr_sqls[] = $obj_item->update_main(array('item_id'=>$arr_order['item_id']),array('stock'=>array('do'=>'inc','val'=>1)),true);
		}
		if ($int_status==105) {
			$arr_sqls = array();
			$int_chaoshi = intval(($int_time-$arr_order['in_date'])/60);
			$arr_sqls[] = $obj_order->update_main(array('order_id'=>$int_order_id),$arr_data,true);
			if ($int_chaoshi>5) {
				$arr_sqls[] = $obj_member->update_member(array('uid'=>$arr_order['uid']),array('balance'=>array('do'=>'inc','val'=>$arr_config['data_butie']*$int_chaoshi)),true);
				$arr_money = array(
		                        'type'       =>22,
		                        'uid'        =>$arr_order['uid'],
		                        'sid'        =>$arr_order['sid'],
		                        'order_id'   =>$int_order_id,
		                        'item_id'    =>$arr_order['item_id'],
		                        'money'      =>$arr_config['data_butie']*$int_chaoshi,
		                        'content'    =>'审核超时补贴金额',
		                        'in_date'    =>$int_time,
		                );
		                $arr_money_log = $obj_money_log->get_one(array('type'=>22,'uid'=>$arr_money['uid'],'sid'=>$arr_money['sid'],'order_id'=>$arr_money['order_id']));
		                if(!$arr_money_log) {
		                        $arr_sqls[] = $obj_money_log->insert_money_log($arr_money,true);
		                } 
			}
		}
		// var_export($arr_sqls);
		// exit();
		$bool_return = $_SGLOBAL['trans_db']->multi_query($arr_sqls);
		callback(array('info'=>'审核完成','status'=>200));
	}

	function extra_bat_shenhe(){
		global $_SGLOBAL;
		$str_order_id = str_addslashes($_GET['order_id']);
		$int_status = intval($_GET['status']);
		$str_order_id = trim($arr_order_id,',');
		if (!$str_order_id) {
			return false;
		}
		$obj_order = L::loadClass('order','index');
		$arr_data = array('status'=>$int_status);
		$obj_order->update_main(array('order_id'=>array('do'=>'in','val'=>$str_order_id)),$arr_data);
		callback(array('info'=>'审核完成','status'=>200));
	}

	function extra_bokked_confirm(){
		global $_SGLOBAL;
		$int_booked_id = intval($_GET['booked_id']);
		$obj_order = L::loadClass('order','index');
		$arr_data = array('status'=>200);
		$obj_order->update_booked(array('booked_id'=>$int_booked_id),$arr_data);
		callback(array('info'=>'确认完成','status'=>200));
	}


	function extra_booked_del(){
		global $_SGLOBAL;
		$int_booked_id = intval($_GET['booked_id']);
		$obj_order = L::loadClass('order','index');
		$arr_data = array('status'=>90);
		$obj_order->update_booked(array('booked_id'=>$int_booked_id),$arr_data);
		callback(array('info'=>'确认完成','status'=>200));
	}
}