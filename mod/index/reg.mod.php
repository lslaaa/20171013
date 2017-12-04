<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_reg {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
	}

	

	function extra_index(){
		global $_SGLOBAL;  
                // var_export(get_cookie('member_weixin_data'));
		if(submit_check('formhash')){
			// var_export($_POST);
                        $str_phone = str_addslashes($_POST['phone']);
                        $str_password = str_addslashes($_POST['password']);
                        $str_checkcode = str_addslashes($_POST['checkcode']);
                        $obj_member = L::loadClass('member', 'index');
                        $obj_checkcode = L::loadClass('checkcode', 'index');
                        $obj_money_log = L::loadClass('money_log', 'index');
                        $arr_weixin_data = unserialize(get_cookie('member_weixin_data'));

                        if($obj_member->verify_rephone($str_phone)){
                            callback(array('info'=>'该手机号码已存在','status'=>304));		
                        }
                        //校验手机号码
                        $arr_code = $obj_checkcode->get_one(array('des' => $str_phone, 'type' => 1));
                        if($arr_code['checkcode'] != $str_checkcode){
                            callback(array('info'=>'验证码错误','status'=>304));
                        }
                        $arr_set = array(
                        	'phone'=>$str_phone,
                        	'password'=>$str_password,
                                'r_uid'=>get_cookie('r_uid'),
                                'in_date'=>time(),
                        	);
                        if (get_cookie('r_uid')) {
                                $arr_set['jie_commission'] = $_SGLOBAL['data_config']['data_tui_gold'];
                        }
                        if (!empty($arr_weixin_data['nickname'])) {
                                $arr_set['openid'] = $arr_weixin_data['openid'];
                                $arr_set['nickname'] = $arr_weixin_data['nickname'];
                                $arr_set['face'] = $arr_weixin_data['headimgurl'];
                        }
                        $int_uid = $obj_member->insert_member($arr_set);
                        //更新推荐人金币
                        $arr_sqls = array();
                        $arr_sqls[] = $obj_member->update_member(array('uid'=>get_cookie('r_uid')),array('yaoqing'=>array('do'=>'inc','val'=>1),'commission'=>array('do'=>'inc','val'=>$_SGLOBAL['data_config']['data_tui_gold'])),true);
                        $arr_money = array(
                                'type'       =>60,
                                'uid'        =>get_cookie('r_uid'),
                                'tui_uid'    =>$int_uid,
                                'money'      =>$_SGLOBAL['data_config']['data_tui_gold'],
                                'content'    =>'推荐获得奖励',
                                'in_date'    =>time(),
                        );
                        $arr_money_log = $obj_money_log->get_one(array('type'=>60,'uid'=>$arr_money['uid'],'tui_uid'=>$arr_money['tui_uid']));
                        if(!$arr_money_log) {
                                $arr_sqls[] = $obj_money_log->insert_money_log($arr_money,true);
                        }
                        $bool_return = $_SGLOBAL['trans_db']->multi_query($arr_sqls);
                        $arr_detail = array('uid'=>$int_uid);
                        $obj_member->insert_member_detail($arr_detail);
                        $arr_member = $obj_member->get_one_member($int_uid);
                        $obj_member->set_login_cookie($int_uid, $arr_member['password']);
                        shift_cookie('member_weixin_data');
                        shift_cookie('r_uid');
                        callback(array('info'=>'注册成功','status'=>200));
                }
                include template('template/index/reg');
	}


	function extra_get_phone_code(){
		global $_SGLOBAL;     
		$str_phone = str_addslashes($_GET['phone']);
		$str_check_code = rand(100000,999999);
		$str_content = '您的验证码是:'.$str_check_code.'【买家秀】';
            
		$obj_checkcode = L::loadClass('checkcode','index');
		$arr_data = array();
		$arr_data['type'] = 1;
		$arr_data['checkcode'] = $str_check_code;
		$arr_data['des'] = $str_phone;
		$obj_checkcode->insert($arr_data);
		$this->extra_verify_send($str_phone,$str_content);
		callback(array('info'=>'请求验证码成功','status'=>200));
                        
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
}