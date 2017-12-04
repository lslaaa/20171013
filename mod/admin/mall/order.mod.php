<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_order {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
	}

	function extra_index(){
		global $_SGLOBAL;
		$int_page = intval($_GET['page']);
		$int_status = intval($_GET['status']);
		$int_order_id = intval($_GET['order_id']);
		$str_buyer_name = str_addslashes($_GET['buyer_name']);
		$str_phone = str_addslashes($_GET['phone']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 20;
		$obj_order     = L::loadClass('order','index');
		$obj_district   = L::loadClass('district','index');
		
		$arr_data = array();
		if($str_buyer_name){
			$obj_member = L::loadClass('member','index');
			$arr_temp_member = $obj_member->get_list(array('username'=>array('do'=>'like','val'=>"%{$str_buyer_name}%")),1,100);
			if($arr_temp_member['total']>0){
				$str_temp_uids = '';
				foreach($arr_temp_member['list'] as $v){
					$str_temp_uids .= $v['uid'].',';
				}
				$str_temp_uids = rtrim($str_temp_uids,',');
				$arr_data['uid'] = array('do'=>'in','val'=>$str_temp_uids);
			}
			else{
				$arr_data['order_id'] = -1;	
			}
		}
		if($str_phone){
			$arr_temp_order = $obj_order->get_list_b(array('phone'=>array('do'=>'like','val'=>"%{$str_phone}%")),1,100);
			if($arr_temp_order['total']>0){
				$str_temp_order_ids = '';
				foreach($arr_temp_order['list'] as $v){
					$str_temp_order_ids .= $v['order_id'].',';
				}
				$str_temp_order_ids = rtrim($str_temp_order_ids,',');
				$arr_data['order_id'] = array('do'=>'in','val'=>$str_temp_order_ids);
			}
			else{
				$arr_data['order_id'] = -1;	
			}
			//var_export($arr_temp_order);
		}
		$int_status && $arr_data['status'] = $int_status;
		$int_order_id && $arr_data['order_id'] = $int_order_id;
		
		$arr_order = $obj_order->get_list($arr_data,$int_page,$int_page_size);
		$str_district_ids = '';
		foreach($arr_order['list'] as $v){
			$str_district_ids .= $v['detail']['province'].','.$v['detail']['city'].','.$v['detail']['area'].',';
		}
		$str_district_ids = rtrim($str_district_ids,',');
		$str_district_ids && $arr_district = $obj_district->get_list(array('id'=>array('do'=>'in','val'=>$str_district_ids)),'id');
		//var_export($arr_district);
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_order['total']/$int_page_size),'?'.$str_query_string);
		$arr_language = $_SGLOBAL['language'];
		
		$int_order_cancel = LEM_order::ORDER_CANCEL;
		$int_order_no_pay = LEM_order::ORDER_NO_PAY;
		$int_order_pay = LEM_order::ORDER_PAY;
		$int_order_send = LEM_order::ORDER_SEND;
		$int_order_success = LEM_order::ORDER_SUCCESS;
		//var_export($arr_language);
		include template('template/admin/mall/order/index');
	}
	
	function extra_detail(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_order_id  = intval($_GET['order_id']);
		if(empty($int_order_id)){
			return false;	
		}
		$obj_order = L::loadClass('order','index');
		$obj_item = L::loadClass('item','index');
		$obj_member = L::loadClass('member','index');
		$obj_district   = L::loadClass('district','index');
		$arr_data  = $obj_order->get_one($int_order_id);
		$arr_sku = $obj_item->get_list_sku();
		$arr_data['buyer']  = $obj_member->get_one_member($arr_data['main']['uid']);
		foreach($arr_data['item'] as $k=>$v){
			$arr_data['item'][$k]['item_main'] = $obj_item->get_one_main($v['item_id']);
			$arr_data['item'][$k]['item_main']['sku_1_name'] = $arr_sku[$v['sku_1']]['name'];
			$arr_data['item'][$k]['item_main']['sku_2_name'] = $arr_sku[$v['sku_2']]['name'];
		}
		$str_district_ids .= $arr_data['detail']['province'].','.$arr_data['detail']['city'].','.$arr_data['detail']['area'];
		$arr_district = $obj_district->get_list(array('id'=>array('do'=>'in','val'=>$str_district_ids)),'id');
		$arr_express = $obj_order->get_express('id');
		//var_export($arr_data);
		include template('template/admin/mall/order/detail');
	}
	
	function extra_update_express(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_order_id  = intval($_GET['order_id']);
		$int_express   = intval($_GET['express']);
		$str_express_id= str_addslashes($_GET['express_id']);
		$obj_order = L::loadClass('order','index');
		$arr_order = $obj_order->get_one_main($int_order_id);
		$arr_data  = array('order_id'=>$int_order_id);
		if($arr_order['status']==LEM_order::ORDER_PAY){
			$arr_data2 = array('send_date'=>$_SGLOBAL['timestamp'],'status'=>LEM_order::ORDER_SEND);
			$obj_order->update_main($arr_data,$arr_data2);
		}
		
		$arr_data2 = array('express'=>$int_express,'express_id'=>$str_express_id);
		$obj_order->update_detail($arr_data,$arr_data2);
		callback(array('info'=>$arr_language['ok_2'],'status'=>200));
	}
	
	function extra_update_quyang_code(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_order_id  = intval($_GET['order_id']);
		$str_quyang_code= str_addslashes($_GET['quyang_code']);
		$obj_order = L::loadClass('order','index');
		$arr_order = $obj_order->get_one_main($int_order_id);
		$arr_data  = array('order_id'=>$int_order_id);
		if($arr_order['status']>=LEM_order::ORDER_PAY){
			$arr_data2 = array('quyang_code'=>$str_quyang_code);
			$obj_order->update_detail($arr_data,$arr_data2);
		}
		callback(array('info'=>$arr_language['ok_2'],'status'=>200));
	}
	
	function extra_update_remark(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_order_id  = intval($_GET['order_id']);
		$str_remark_2= str_addslashes($_GET['remark_2']);
		$obj_order = L::loadClass('order','index');
		$arr_data  = array('order_id'=>$int_order_id);
		$arr_data2 = array('remark_2'=>$str_remark_2);
		$obj_order->update_detail($arr_data,$arr_data2);
		callback(array('info'=>$arr_language['ok_2'],'status'=>200));	
	}
	
	function extra_cancel(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_order_id  = intval($_GET['order_id']);
		
		$obj_order = L::loadClass('order','index');
		$arr_data  = array('order_id'=>$int_order_id);
		$arr_data2 = array('status'=>LEM_order::ORDER_CANCEL);
		$obj_order->update_main($arr_data,$arr_data2);
		callback(array('info'=>$arr_language['ok_2'],'status'=>200));	
	}
	
	function extra_get_express(){
		$obj_order = L::loadClass('order','index');
		$arr_data = $obj_order->get_express();
		callback(array('info'=>'','status'=>200,'data'=>$arr_data));
	}
	
	

}