<?php
!defined('LEM') && exit('Forbidden');

class mod_sms {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
	}

	function extra_index(){

	}

	function extra_verify_mobile(){
		$str_mobile = $_POST['mobile'];
		if (!$str_mobile) {
			$str_mobile = $_GET['mobile'];
		}
		$int_verify = rand(10000,99999);
		$_SESSION['verify'] = array('code'=>$int_verify,'mobile'=>$str_mobile);
		$str_content = '您的验证码是:'.$int_verify.'【动态猫】';
		$this->extra_verify_send($str_mobile,$str_content);
		callback(array('status'=>200,'code'=>$int_verify));
	}

	function extra_forget(){
		$obj_user = L::loadClass('user','index');
		$str_username = str_addslashes($_POST['username']);
		$arr_user_one = $obj_user->get_one_b(array('username'=>$str_username,'is_del'=>0));
		if (empty($arr_user_one)) {
			echo "用户名不存在";
		}else{
			$str_content = '您的密码是:'.$arr_user_one['password2'].'【淘宝服务平台】';
			$result = $this->extra_verify_send($arr_user_one['mobile'],$str_content);
			if ($result['returnstatus'] == 'Success') {
				echo "密码已发送到您的手机上，请注意查看";
			}
		}
	}

	function extra_verify_send($mobile,$str_content){
		$data = array();
		$data["type"] = 'post';
		$data["url"] = "https://dx.ipyy.net/smsJson.aspx";
		$data["fields"] = array(
			"action" => "send",
			"userid"=>'',
			"account"=>'AA00647',
			"password"=>strtoupper(md5('AA00646266')),
			"mobile"=>$mobile,
			"content"=>$str_content,
			'sendTime'=>'',
			'extno'=>'',
			);
		// var_dump($data);
		// die;
		$test = post_curl($data);
		$test = json_decode($test,true);
		// var_dump($test) ;
		return $test;
	}

	function extra_verify_code(){
		$int_code = $_POST['code'];
		$int_mobile = $_POST['mobile'];
		if ($int_code!=$_SESSION['verify']['code'] || $int_mobile!=$_SESSION['verify']['mobile']) {
			echo 'false';die;
		}else{
			echo 'true';die;
		}
	}


}
