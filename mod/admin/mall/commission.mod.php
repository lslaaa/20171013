<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_commission {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
	}

	function extra_index(){
		global $_SGLOBAL;
		$int_is_pay = (isset($_GET['is_pay'])) ? intval($_GET['is_pay']) : -2;
		$bool_list  = intval($_GET['list']);
		$int_order_id = intval($_GET['order_id']);
		$int_page = intval($_GET['page']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 20;
		$obj_commission = L::loadClass('commission','index');
		$obj_member = L::loadClass('member','index');
		$arr_data = array();
		$int_order_id && $arr_data['order_id'] = $int_order_id;
		$int_is_pay>-2 && $arr_data['is_pay'] = $int_is_pay;
		$arr_data['is_del'] = 0;
		$arr_list = $obj_commission->get_list($arr_data,$int_page,$int_page_size,$bool_list);
		$arr_temp_member = array();
		foreach($arr_list['list'] as $k=>$v){
			!isset($arr_temp_member[$v['uid']]) && $arr_temp_member[$v['uid']] = $obj_member->get_one_member($v['uid']);
			$arr_list['list'][$k]['username'] = $arr_temp_member[$v['uid']]['username'];
		}
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		$arr_language = $_SGLOBAL['language'];
		//var_export($arr_language);
		include template('template/admin/mall/commission/index');
	}
	
	
	function extra_del(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_id = intval($_GET['id']);
		if(empty($int_id)){
			return false;	
		}
		$obj_commission = L::loadClass('commission','index');
		$arr_data = array('is_del'=>1);
		$obj_commission->update(array('id'=>$int_id),$arr_data);
		callback(array('info'=>$arr_language['ok_2'],'status'=>200));
	}
	
	function extra_pay(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_i = intval($_GET['i']);
		$int_i = $int_i ? $int_i : 1;
		$int_page_size = 1;

		$obj_commission = L::loadClass('commission','index');
		if($int_i==1){
			$obj_commission->update(array('is_pay'=>-1),array('is_pay'=>0));
		}
		$arr_data = array('is_pay'=>0,'is_del'=>0);
		$arr_data = $obj_commission->get_list($arr_data,1,$int_page_size,false);
		if($arr_data['total']==0){
			jump('/admin.php?mod=commission&mod_dir=mall');	
		}
		$obj_member = L::loadClass('member','index');
		$arr_member = $obj_member->get_one_member($arr_data['list'][0]['uid']);

		$obj_wxpay = L::loadClass('wxpay','payment');
		$arr_params = array(
							'desc'=>'逸基因提现',	
							'check_name'=>'NO_CHECK',
							'amount'=>$arr_data['list'][0]['total_price']*100,
							'spbill_create_ip'=>'113.118.244.189',
							'partner_trade_no'=>$_SGLOBAL['timestamp'],
							'openid'=>$arr_member['openid']
						);
		//var_export($arr_params);
		$xml_content = $obj_wxpay->create_pay_to_user_params($arr_params);
		$xml_result  = $obj_wxpay->mch_pay($xml_content);
		$arr_res = $obj_wxpay->xmlToArray($xml_result);
		
		//$arr_res['return_code'] = 'SUCCESS';
		if($arr_res['result_code']=='SUCCESS'){
			$obj_commission->update(array('uid'=>$arr_data['list'][0]['uid']),array('is_pay'=>1));
			$obj_member->update_member(array('uid'=>$arr_data['list'][0]['uid']),array('commission'=>array('do'=>'inc','val'=>$arr_data['list'][0]['total_price'])));
		}
		else{
			$obj_commission->update(array('uid'=>$arr_data['list'][0]['uid']),array('is_pay'=>-1));	
			$int_i++;
		}
		//var_export($arr_res);
		include template('template/admin/mall/commission/pay');
	}
	
	

}