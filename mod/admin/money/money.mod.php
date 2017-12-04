<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_money {


	 private $arr_parent_permission = array(
	 	'money_money_index'=>'money_money_index',
	 	'money_money_cancle'=>'money_money_index',
	 	'money_money_recharge'=>'money_money_index',
	 	'money_money_recharge_list'=>'money_money_index',
	 	'money_money_confirm_recharge'=>'money_money_index',
	 	'money_money_report'=>'money_money_index',
	 	'money_money_withdrawal'=>'money_money_index',
	 	'money_money_reply'=>'money_money_index',
	 	'money_money_cancel'=>'money_money_index',
	 	'money_money_export'=>'money_money_index',
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
		$int_page = intval($_GET['page']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 10;
		$int_type = intval($_GET['type']);
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
		$int_type && $arr_data['type'] = $int_type;
		$int_status && $arr_data['status'] = $int_status;
		$arr_list = $obj_recharge->get_list($arr_data,'*',$int_page,$int_page_size);
		foreach ($arr_list['list'] as $key => $value) {
			$int_uid = intval($value['uid']);
			$arr_user_one = $obj_user->get_one($int_uid,'uid,cname,username');
			$arr_list['list'][$key]['user'] = $arr_user_one;
		}
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/money/recharge_list');
	}


	function extra_recharge(){
		global $_SGLOBAL;
		$obj_user = L::loadclass('user','index');
		$obj_recharge = L::loadclass('recharge','index');
		$int_uid = intval($_POST['uid']);
		$arr_user_one = $obj_user->get_one($int_uid);
		$arr_data = array(
			'uid'=>$int_uid,
			'money'=>$_POST['money'],
			'in_date'=>time(),
			'cname'=>$arr_user_one['cname'],
			);
		$arr_user_data = array(
			'balance'=>$_POST['money']+$arr_user_one['balance'],
			);
		$obj_recharge->_insert($arr_data);
		$obj_user->_update($int_uid,$arr_user_data);
		callback(array('info'=>'','status'=>200));
	}


	function extra_recharge_list(){
		global $_SGLOBAL;
		$obj_user = L::loadclass('user','index');
		$obj_recharge = L::loadclass('recharge','index');
		$int_uid = intval($_GET['uid']);
		$arr_user_one = $obj_user->get_one($int_uid,'uid,cname,username');
		$arr_list = $obj_recharge->get_list(array('uid'=>$int_uid));
		include template('template/admin/money/recharge_list');
	}

	function extra_confirm_recharge(){
		global $_SGLOBAL;
		define('SHOW_TRANS_SQL', 1);
		$obj_recharge = L::loadclass('recharge','index');
		$obj_user = L::loadclass('user','index');
		$int_id = $_POST['id'];
		$arr_recharge_one = $obj_recharge->get_one($int_id);
		$int_uid = $arr_recharge_one['uid'];
		$arr_user_one = $obj_user->get_one($int_uid);
		
		$arr_data = array(
			'status'=>$_POST['status'],
			);
		$arr_user_data = array(
			'balance'=>array('do'=>'inc','val'=>$arr_recharge_one['money'])
			);
		$arr_sqls = array();
		$arr_sqls[] = $obj_recharge->_update($int_id,$arr_data,true);
		if ($_POST['status']==200) {
			$arr_sqls[] = $obj_user->_update_b($int_uid,$arr_user_data,true);
			$obj_money_log = L::loadClass('money_log','index');
			$arr_money = array(
				'type'=>30,
				'shop_uid'=>$arr_recharge_one['uid'],
				'money'=>$arr_recharge_one['money'],
				'content'=>'商家余额充值',
				'in_date'=>time(),
			);
			$arr_sqls[] = $obj_money_log->insert_money_log($arr_money,true);
			// var_export($arr_sqls);
			// exit();
			$bool_return = $_SGLOBAL['trans_db']->multi_query($arr_sqls);
			if(!$bool_return){
				callback(array('info'=>'操作失败，请稍后重试','status'=>304));	
			}
			$arr_data = array(
				'reply'=>$_SGLOBAL['member']['realname'].':'.$_POST['reply'].'('.date('Y-m-d H:i:s',time()).')',
			);
			$obj_recharge->_update($int_id,$arr_data);
		}
		callback(array('info'=>'操作成功','status'=>200));

	}


	function extra_report(){
		global $_SGLOBAL;
		$obj_recharge = L::loadclass('recharge','index');
		$obj_user = L::loadclass('user','index');
		$obj_withdrawal = L::loadClass('withdrawal','index');
		$obj_after = L::loadClass('after','index');
		$obj_admin_member = L::loadClass('admin_member','admin');
		$arr_recharge_shop = $obj_recharge->get_sum_money(array('status'=>1));
		$arr_recharge_shop = $obj_recharge->get_sum_money(array('status'=>1));
		$arr_balance_shop = $obj_user->get_sum_balance(array('is_del'=>0));
		$arr_balance_after = $obj_after->get_sum_balance(array('is_del'=>0));
		$arr_balance_worker = $obj_admin_member->get_sum_balance(array('is_del'=>0));
		$arr_withdrawal_shop = $obj_withdrawal->get_sum_money(array('type'=>1,'status'=>array('do'=>'in','val'=>'100,200'),'is_del'=>0));
		$arr_withdrawal_after = $obj_withdrawal->get_sum_money(array('type'=>2,'status'=>array('do'=>'in','val'=>'100,200'),'is_del'=>0));
		$arr_withdrawal_worker = $obj_withdrawal->get_sum_money(array('type'=>3,'status'=>array('do'=>'in','val'=>'100,200'),'is_del'=>0));
		// var_export($arr_withdrawal);
		include template('template/admin/money/report');
	}

	function extra_withdrawal(){
		global $_SGLOBAL;
		$int_status = intval($_GET['status']);
		$int_page = intval($_GET['page']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 20;
		$int_type = intval($_GET['type']);
		
		$obj_withdrawal = L::loadClass('withdrawal','index');
		$obj_user = L::loadClass('user','index');
		$obj_member = L::loadClass('member','index');
		$int_status && $arr_data['status'] = $int_status;
		$int_type && $arr_data['type'] = $int_type;
		if ($_POST['username']) {
			$arr_data['names'] = ' AND name LIKE "%'.$_POST['username'].'%"';
		}
		if ($_POST['mobile']) {
			$arr_data['phone'] = $_POST['mobile'];
		}
		$arr_data['is_del'] = 0;
		$arr_list = $obj_withdrawal->get_list($arr_data,$int_page,$int_page_size);
		foreach ($arr_list['list'] as $key => $value) {
			if ($int_type==2) {
				$arr_member_one = $obj_user->get_one($value['uid']);
			}elseif($int_type==1){
				$arr_member_one = $obj_member->get_one_member($value['uid']);
			}
			// var_export($arr_member_one);
			$arr_list['list'][$key]['member'] = $arr_member_one;
		}
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		$arr_language = $_SGLOBAL['language'];
		include template('template/admin/money/withdrawal');
	}

	function extra_reply(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_id = intval($_POST['id']);
		$str_reply = str_addslashes($_POST['reply']);
		if(empty($int_id)){
			return false;	
		}
		$obj_withdrawal = L::loadClass('withdrawal','index');
		$bool_ok = $obj_withdrawal->update(array('id'=>$int_id), array('reply'=>$str_reply,'status'=>200,'deal_date'=>time()));
		callback(array('info'=>$arr_language['ok_2'],'status'=>200));
	}

	function extra_cancel(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_id = intval($_POST['id']);
		$str_reply = str_addslashes($_POST['reply']);
		$float_amount = floatval($_GET['money']);
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
		$arr_sqls[] = $obj_withdrawal->update(array('id'=>$int_id), array('reply'=>$str_reply,'status'=>304,'deal_date'=>$int_time),true);
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


	function extra_export(){
                global $_SGLOBAL;
                $arr_member = $_SGLOBAL['member'];
                
                // var_export($_POST['export']);
                // exit();
                //导出操作
                if (isset($_GET['export']) && intval($_GET['export']) == 1) {  

                        $int_status = intval($_GET['status']);
			$int_page = intval($_GET['page']);
			$int_page = $int_page ? $int_page : 1;
			$int_page_size = 1000;
			$int_type = intval($_GET['type']);
			
			$obj_withdrawal = L::loadClass('withdrawal','index');
			$obj_user = L::loadClass('user','index');
			$obj_member = L::loadClass('member','index');
			$int_status && $arr_data['status'] = $int_status;
			$int_type && $arr_data['type'] = $int_type;
			$arr_data['is_del'] = 0;
			$arr_list = $obj_withdrawal->get_list($arr_data,$int_page,$int_page_size);
			foreach ($arr_list['list'] as $key => $value) {
				if ($int_type==2) {
					$arr_member_one = $obj_user->get_one($value['uid']);
				}elseif($int_type==1){
					$arr_member_one = $obj_member->get_one_member($value['uid']);
				}
				// var_export($arr_member_one);
				$arr_list['list'][$key]['member'] = $arr_member_one;
			}

                        $str_client = md5(rand()); //未加密之前的字符串
                        $str_server = base64_encode(hash_hmac("SHA1", $str_client, SAFE_TOKEN, true)); //服务器发送过来的加密字符串
                        $json_data = array(
                                'str_client' => $str_client,
                                'str_server' => $str_server,
                                'label'      => array('开户行','银行卡号', '银行卡姓名', '提现金额', '平台用户名')//第一行
                        );


                        if (!empty($arr_list['list'])) {
                                foreach ($arr_list['list'] as $k => $v) {
                                	$int_type == 1 && $str_name = $v['member']['nickname'];
                                	$int_type == 2 && $str_name = $v['member']['username'];
                                        $json_data['content'][] = array(
                                                $v['bank_name'],
                                                $v['bank_card'],
                                                $v['card_name'],
                                                $v['amount'],
                                                $str_name,
                                        ); //第四行以后的导入数据   
                                        // unset($arr_orders[$key]);
                                }
                        }
                        $json_data['cell_width'] = array(
                                                'A'=>20,
                                                'B'=>30,
                                                'C'=>20,
                                                'D'=>20,
                                                'E'=>30,
                                                'F'=>20,
                                        );
                        $json_data['text_type'] = array('K'=>1,'L'=>1);
                        //$json_data = json_encode($json_data);
                        $str_file_name = 'money' . date('YmdHm', $_SGLOBAL['timestamp']) .'-1.xls';
                        header("Cache-Control: no-cache, must-revalidate");
                        header("Pragma: no-cache");
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $str_file_name . '"');
                        header('Cache-Control: max-age=0');
                        $obj_excel = L::loadClass('excel', 'index');
                        $obj_excel->export($json_data);
                        exit();
                }
        }

}