<?php
!defined('LEM') && exit('Forbuidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_login {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
	}

	function extra_index(){//用户登录模块
		global $_SGLOBAL;
		$str_from = str_addslashes($_GET['from']);
		if(submit_check('formhash')){
            	session_start();
			$obj_member = L::loadClass('user','index');
			$str_username   = str_addslashes($_POST['username']);
			$str_password   = str_addslashes($_POST['password']);
			$arr_return = $obj_member->login($str_username,$str_password);
			unset($_SESSION['admin_login_check_code']);
			if($arr_return['data']['uid']>0){
				$arr_data = array(
							  'last_ip'=>$arr_return['data']['ip'],
							  'ip'=>$_SERVER['REMOTE_ADDR'],
							  'last_date'=>$arr_return['data']['login_date'],
							  'login_date'=>$_SGLOBAL['timestamp']
						);
				$obj_member->update_member(array('uid'=>$arr_return['data']['uid']),$arr_data);
			}
			callback($arr_return);
			unset($_SESSION['admin_login_check_code']);
			exit;	
		}
		$bool_check_code = get_cookie('admin_login_show_check_code');
		$arr_language = $_SGLOBAL['language'];
		include template('template/shop/login_new');
	}


	function extra_reg(){
		global $_SGLOBAL;
		if (submit_check('formhash')) {
			var_export($_POST);
			$obj_user = L::loadClass('user','index');
			$str_username = str_addslashes($_POST['username']);
			$arr_user_one = $obj_user->get_one_b(array('username'=>$str_username,'is_del'=>0));
			if ($arr_user_one) {
				callback(array('status'=>304,'info'=>'用户名已存在'));
			}
			if ($_POST['code']!=$_SESSION['verify']['code'] || $_POST['mobile']!=$_SESSION['verify']['mobile']) {
				callback(array('status'=>304,'info'=>'验证码错误'));
			}
			$arr_data['contacts'] = str_addslashes($_POST['contacts']); 
			$arr_data['mobile'] = str_addslashes($_POST['mobile']); 
			$arr_data['qq'] = str_addslashes($_POST['qq']); 
			$arr_data['username'] = str_addslashes($_POST['username']); 
			$arr_data['password'] = str_addslashes($_POST['password']); 
			$arr_data['password2'] = str_addslashes($_POST['password2']);
			$arr_data['gid'] = 1;
			$arr_data['is_del'] = 1;
			$arr_data['in_date'] = time();

			$result = $obj_user->_insert($arr_data);
			if ($result) {
				$obj_user->update_member(array('uid'=>$result),array('pid'=>$result));
				$obj_user->login($arr_data['username'],$arr_data['password']);
				callback(array('status'=>200,'info'=>'注册成功'));
			}else{
				callback(array('status'=>304,'info'=>'注册失败'));
			}


		}
		$bool_check_code = get_cookie('admin_login_show_check_code');
		include template('template/shop/reg_new');
	}
	
	function extra_logout(){//用户退出模块
		$obj_member = L::loadClass('user','index');
		$obj_member->set_login_session('','',true);
		jump('/shop.php');
	}
	
	
}