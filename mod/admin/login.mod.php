<?php
!defined('LEM') && exit('Forbidden');

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
			$obj_member = L::loadClass('admin_member','admin');
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
		include template('template/admin/login');
	}
	
	function extra_logout(){//用户退出模块
		$obj_member = L::loadClass('admin_member','admin');
		$obj_member->set_login_session('','',true);
		jump('/admin.php');
	}
}