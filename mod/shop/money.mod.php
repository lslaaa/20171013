<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_money {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		$obj_admin_member = L::loadClass('admin_member','admin');
		if (method_exists($this, 'extra_' . $extra)) {
	            $str_menu_alias_name = MOD_DIR.'_'.SCR.'_'.$extra;
	            isset($this->arr_parent_permission[$str_menu_alias_name]) && $str_menu_alias_name = $this->arr_parent_permission[$str_menu_alias_name];
	            // $bool_permission = $obj_admin_member->verify_permission($str_menu_alias_name);
	            $str_function_name = 'extra_' . $extra;
	        }
        // if(!$bool_permission){
        //     die('没有权限');
        // }
        $this->$str_function_name();
	}


	function extra_index(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$int_page = intval($_GET['page']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 10;
		$int_status = intval($_GET['status']);
		$obj_user = L::loadclass('user','index');
		$obj_recharge = L::loadclass('recharge','index');
		$arr_data['is_del'] = 0;
		if ($_GET['username']) {
			$str_username = str_addslashes($_GET['username']);
			$arr_data['usernames'] = ' AND username LIKE "%'.$str_username.'%"';
		}
		if ($_GET['mobile']) {
			$str_mobile = str_addslashes($_GET['mobile']);
			$arr_data['mobile'] = $str_mobile;
		}
		if ($_GET['qq']) {
			$str_qq = str_addslashes($_GET['qq']);
			$arr_data['qq'] = $str_qq;
		}
		if ($_GET['shopid']) {
			$str_shopid = str_addslashes($_GET['shopid']);
			$arr_tmshop_data['shopid'] = array('do'=>'like','val'=>'%'.$str_shopid.'%');
			$arr_tmshop = $obj_tmshop->get_one_c($arr_tmshop_data);
			// var_dump($arr_tmshop);
			$arr_data['uid'] = $arr_tmshop['uid'];
		}
		$int_status && $arr_data['status'] = $int_status;
		$arr_data['uid'] = $arr_member['uid'];
		$arr_list = $obj_recharge->get_list($arr_data,'*',$int_page,$int_page_size);
		foreach ($arr_list['list'] as $key => $value) {
			$int_uid = intval($value['uid']);
			$arr_user_one = $obj_user->get_one($int_uid,'uid,cname,username');
			$arr_list['list'][$key]['user'] = $arr_user_one;
		}
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'/shop.php?mod=money');
		$arr_language = $_SGLOBAL['language'];
		include template('template/shop/money/recharge_list');
	}

	function extra_add_recharge(){
		global $_SGLOBAL;
		$int_uid = $_SGLOBAL['member']['uid'];
		$obj_recharge = L::loadclass('recharge','index');
		$obj_user = L::loadclass('user','index');
		$obj_user_bank = L::loadclass('user_bank','index');
		$arr_bank_list = $obj_user_bank->get_list(array('uid'=>$int_uid,'is_del'=>0));
		// var_export($arr_bank_list);
		$arr_user = $obj_user->get_one($int_uid);
		if (submit_check('formhash')) {
			$str_form_do = str_addslashes($_POST['form_do']);
			if (method_exists($this, 'do_' . $str_form_do) && $str_form_do=='upload') {
				$str_function_name = 'do_' . $str_form_do;
				$this->$str_function_name();
				exit();
			}
			// var_export($_POST);
			// exit();
			$arr_data = array();
			$arr_data['uid'] = $int_uid;
			// $arr_data['money'] = floatval($_POST['money'])+$this->sqlnum();
			// $arr_data['money'] = number_format($arr_data['money'], 2,'.','');
			$arr_data['money'] = floatval($_POST['money']);
			$arr_data['old_balance'] = $arr_user['balance'];
			$arr_data['trade_no'] = str_addslashes($_POST['trade_no']);
			$arr_data['order_no'] = str_addslashes($_POST['order_no']);
			$arr_data['contacts'] = str_addslashes($_POST['contacts']);
			$arr_data['mobile'] = str_addslashes($_POST['mobile']);
			$arr_data['bank_name'] = str_addslashes($_POST['bank']);
			$arr_data['bank_card'] = str_addslashes($_POST['bank_card']);
			$arr_data['card_name'] = str_addslashes($_POST['bank_card_name']);
			$arr_data['pic'] = str_addslashes($_POST['data_pic']);
			$arr_data['type'] = intval($_POST['type']);
			$arr_data['in_date'] = time();
			// var_export($arr_data);
			// exit();
			$obj_recharge->_insert($arr_data);
			$money=$arr_data['money'];
			$type=$arr_data['type'];
			$order_no=$arr_data['order_no'];
			callback(array('money'=>$money,'type'=>$type,'order_no'=>$order_no,'status'=>200,'type'=>$arr_data['type']));
		}
		
		include template('template/shop/money/add_recharge');
	}

	function extra_recharge_pay(){
		global $_SGLOBAL;
		$money=floatval($_GET['money']);
		$type=intval($_GET['type']);
		$order_no=str_addslashes($_GET['order_no']);
		include template('template/shop/money/recharge_pay');
	}

	function do_upload(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$str_config_pic = str_addslashes($_POST['config_pic_name']);
		$arr_config = get_config($str_config_pic);

		$str_path = save_image($_FILES['pic']['tmp_name'],$arr_config['val']);
		if(empty($str_path)){
			callback(array('info'=>$arr_language['uploads']['error_1'],'status'=>304));		
		}
		callback(array('info'=>'','status'=>200,'data'=>$str_path));
	}

	function extra_recharge_chek(){
		global $_SGLOBAL;
		$order_no = intval($_POST['order_no']);
		$obj_recharge = L::loadClass('recharge','index');
		$arr_recharge = $obj_recharge->get_one_b(array('order_no'=>$order_no,'is_del'=>0));
		$rtime=$arr_recharge['in_date'];
		$time=time()-180;
		if($rtime<$time){
			$pay['code']=99;
		}else{
			$pay['code']=0;
		}

		$pay['status']=$arr_recharge['status'];
		echo json_encode($pay);
	}

	function randomFloat($min = 0, $max = 1) {
    		return $min + mt_rand() / mt_getrandmax() * ($max - $min);
	}
	function sqlnum(){

		$num=$this->randomFloat();
		$newNum  = sprintf("%.2f",$num);
		$otime=time()-180;
		$obj_recharge = L::loadClass('recharge','index');
		$arr_recharge = $obj_recharge->get_one_paynum($otime,$newNum);

		if($arr_recharge){
			$this->sqlnum();
		}else{
			return $newNum;
		}
	}

	

	function extra_withdrawal(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$int_status = intval($_GET['status']);
		$int_page = intval($_GET['page']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 20;
		
		$obj_withdrawal = L::loadClass('withdrawal','index');
		$obj_user = L::loadClass('user','index');
		$obj_member = L::loadClass('member','index');
		$int_status && $arr_data['status'] = $int_status;
		$arr_data['type'] = 2;
		$arr_data['uid'] = $arr_member['uid'];
		if ($_POST['username']) {
			$arr_data['names'] = ' AND name LIKE "%'.$_POST['username'].'%"';
		}
		if ($_POST['mobile']) {
			$arr_data['phone'] = $_POST['mobile'];
		}
		$arr_data['is_del'] = 0;
		$arr_list = $obj_withdrawal->get_list($arr_data,$int_page,$int_page_size);
		foreach ($arr_list['list'] as $key => $value) {
			if ($value['type']==2) {
				$arr_member_one = $obj_user->get_one($value['uid']);
			}elseif($value['type']==1){
				$arr_member_one = $obj_member->get_one_member($value['uid']);
			}
			// var_export($arr_member_one);
			$arr_list['list'][$key]['member'] = $arr_member_one;
		}
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		$arr_language = $_SGLOBAL['language'];
		include template('template/shop/money/withdrawal');
	}


	function extra_add_withdrawal(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$obj_user_bank = L::loadclass('user_bank','index');
		$arr_bank_list = $obj_user_bank->get_list(array('uid'=>$arr_member['uid'],'is_del'=>0));
		if(submit_check('formhash')){
			// var_export($_POST);
			define('SHOW_TRANS_SQL', 1);
			$obj_user = L::loadClass('user','index');
	                $obj_money_log = L::loadClass('money_log','index');
	                $obj_withdrawal = L::loadClass('withdrawal','index');
			$float_amount = floatval($_POST['money']);
			$str_bank = str_addslashes($_POST['bank']);
			$str_bank_card = str_addslashes($_POST['bank_card']);
			$str_bank_card_name = str_addslashes($_POST['bank_card_name']);
			if ($arr_member['balance']<$float_amount) {
				callback(array('status'=>304,'info'=>'余额不足！'));
			}
			$int_time = time();
	                $arr_sqls = array();
	                $arr_sqls[] = $obj_user->_update_b($arr_member['uid'],array('balance'=>array('do'=>'inc','val'=>-$float_amount)),true);
	                $arr_data = array(
				'uid'       => $arr_member['uid'],
				'type'      => 2,
				'amount'    => $float_amount,
				'status'    => 100,
				'card_name' => $str_bank_card_name,
				'bank_card' => $str_bank_card,
				'bank_name' => $str_bank,
				'in_date'   => $int_time
	                );
	                $arr_sqls[] = $obj_withdrawal->insert($arr_data,true);
	                $arr_money = array(
				'type'     =>40,
				'shop_uid' =>$arr_member['uid'],
				'money'    =>$float_amount,
				'content'  =>'商家提现',
				'in_date'  =>$int_time,
	                );
	                $arr_sqls[] = $obj_money_log->insert_money_log($arr_money,true);
	                $bool_return = $_SGLOBAL['trans_db']->multi_query($arr_sqls);
	                if ($bool_return) {
	                        callback(array('status'=>200,'info'=>'提交成功'));
	                }
	                callback(array('status'=>304,'info'=>'提交失败'));
			exit();
		}
		include template('template/shop/money/add_withdrawal');
	}


	function extra_cancel(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_id = intval($_GET['id']);
                $obj_member = L::loadClass('member','index');
                $obj_user = L::loadClass('user','index');
                $obj_money_log = L::loadClass('money_log','index');
                $obj_withdrawal = L::loadClass('withdrawal','index');
		if(empty($int_id)){
			return false;	
		}
		$arr_withdrawal_one = $obj_withdrawal->get_one($int_id);
                $int_time = time();
                $arr_sqls = array();
		$arr_sqls[] = $obj_withdrawal->update(array('id'=>$int_id), array('status'=>304,'deal_date'=>$int_time),true);
                if ($arr_withdrawal_one['type']==1) {
	                $arr_sqls[] = $obj_member->update_member(array('uid'=>$arr_withdrawal_one['uid']),array('balance'=>array('do'=>'inc','val'=>$arr_withdrawal_one['amount'])),true);
	                $int_money_type = 55;
                }elseif($arr_withdrawal_one['type']==2){
			$arr_sqls[] = $obj_user->_update_b($arr_withdrawal_one['uid'],array('balance'=>array('do'=>'inc','val'=>$arr_withdrawal_one['amount'])),true);
			$int_money_type = 45;
                }
                $arr_money = array(
                        'type'       =>$int_money_type,
                        'money'      =>$arr_withdrawal_one['amount'],
                        'content'    =>'提现撤销',
                        'in_date'    =>$int_time,
                );
                $arr_withdrawal_one['type']==1 && $arr_money['uid'] = $arr_withdrawal_one['uid'];
                $arr_withdrawal_one['type']==2 && $arr_money['shop_uid'] = $arr_withdrawal_one['uid'];
                $arr_sqls[] = $obj_money_log->insert_money_log($arr_money,true);
                // var_export($arr_sqls);
                // exit();
                $bool_return = $_SGLOBAL['trans_db']->multi_query($arr_sqls);
		callback(array('info'=>$arr_language['ok_2'],'status'=>200));
	}


	function extra_bank_card(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$arr_language = $_SGLOBAL['language'];
		$obj_user_bank = L::loadclass('user_bank','index');
		$arr_list = $obj_user_bank->get_list(array('uid'=>$arr_member['uid'],'is_del'=>0));
		include template('template/shop/money/bank_card');
	}

	function extra_add_bank_card(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$arr_language = $_SGLOBAL['language'];
		if(submit_check('formhash')){
			// var_export($_POST);
			// define('SHOW_SQL', 1);
			$obj_user_bank = L::loadclass('user_bank','index');
			$obj_bank = L::loadclass('bank','index');
			$str_bank = str_addslashes($_POST['bank']);
			$str_bank_card = str_addslashes($_POST['bank_card']);
			$str_bank_card_name = str_addslashes($_POST['bank_card_name']);
			$arr_data = array(
				'uid'=>$arr_member['uid'],
				'bank'=>$str_bank,
				'bank_card'=>$str_bank_card,
				'bank_card_name'=>$str_bank_card_name,

			);
			$arr_bank = $obj_bank->get_list(array('name'=>$arr_data['bank']));
			if ($arr_bank['total']<1) {
				callback(array('info'=>'开户行错误，请重新填写','status'=>304));
			}
			$reslut = $obj_user_bank->insert($arr_data);
			if ($reslut) {
				callback(array('info'=>'添加成功','status'=>200));
			}
			callback(array('info'=>'添加失败','status'=>304));
		}
		include template('template/shop/money/add_bank_card');
	}

	function extra_bank_list(){
		global $_SGLOBAL;
		$str_name = str_addslashes($_POST['name']);
		$obj_bank = L::loadClass('bank','index');
		if ($str_name=='all') {
			$arr_bank = $obj_bank->get_list(array('id'=>array('do'=>'gt','val'=>0)),1,200);
		}else{
			$arr_bank = $obj_bank->get_list(array('name'=>array('do'=>'like','val'=>'%'.$str_name.'%')),1,200);
		}
		// var_export($arr_bank);
		callback($arr_bank['list']);
	}

	function extra_bank_cancel(){
		global $_SGLOBAL;
		$int_id = str_addslashes($_GET['id']);
		$obj_user_bank = L::loadClass('user_bank','index');
		$obj_user_bank->update(array('id'=>$int_id),array('is_del'=>1));
		// var_export($arr_bank);
		callback(array('info'=>'删除成功','status'=>200));
	}

}