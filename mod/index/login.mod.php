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

	function extra_index(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
                
                if(submit_check('formhash')){
                        $obj_member = L::loadClass('member','index');
                        $str_phone     = str_addslashes($_POST['phone']);
                        $str_password  = str_addslashes($_POST['password']);
                        $arr_data = $obj_member->login($str_phone, $str_password);
                        if($arr_data){
                            $obj_member->update_member(array('uid'=>$arr_data['data']['uid']), array('last_ip'=>$_SERVER['REMOTE_ADDR'],'last_date'=>$_SGLOBAL['timestamp']));
                        }
                        callback($arr_data);    
                }

                // include template('template/index/login');


		include S_ROOT.'ssi/config/msocial/weixin.conf.php';
		$str_sid = rand();
                // shift_cookie('member_weixin_data');
                // exit();
		if(verify_browser()!='weixin' || get_cookie('member_weixin_data')){
                        // var_export(get_cookie('member_weixin_data'));
			include template('template/index/login');
                        exit();
		}
		else{
			$str_weixin_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . weixin_login::APPID . '&redirect_uri=' . weixin_login::CALLBACK_URL . '&response_type=code&scope=snsapi_userinfo&state='.$str_sid.'#wechat_redirect';

		}
		jump($str_weixin_url);
	}
	
	function extra_logout(){
		global $_SGLOBAL;
		$obj_member = L::loadClass('member','index');
		$obj_member->set_login_cookie('');
		jump('/');
	}
	
	function extra_weixin_callback() {
                global $_SGLOBAL;
                $int_expire_time = $_SGLOBAL['timestamp'] + 7 * 24 * 3600;
                if (isset($_GET['code']) && trim($_GET['code']) != "") {
        		include S_ROOT.'ssi/config/msocial/weixin.conf.php';
                        $arr_fields = array(
                                'appid' => weixin_login::APPID,
                                'secret' => weixin_login::APPSECRET,
                                'code' => trim($_GET['code']),
                        );

                        $arr_data = array(
                                'url' => 'https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code&' . http_build_query($arr_fields)
                        );
			$arr_data['url'] = 'https://api.weixin.qq.com/sns/oauth2/access_token?' . http_build_query($arr_fields) . '&grant_type=authorization_code';

                        //»ñÈ¡open id,access_token£»È»ºó»ñÈ¡ÓÃ»§ÐÅÏ¢¡£
                        try {
                                $str_result = post_curl($arr_data);
                                $arr_result = json_decode($str_result, TRUE);
                                $str_unionid = $arr_result['unionid'];
                                $arr_fields2 = array(
                                    'access_token' => $arr_result['access_token'],
                                    'openid' => $arr_result['openid']
                                );
                                $arr_data2 = array(
                                    'url' => 'https://api.weixin.qq.com/sns/userinfo?' . http_build_query($arr_fields2)
                                );
                				
                                $str_weixin_result = post_curl($arr_data2);
                                $arr_weixin_result = json_decode($str_weixin_result, TRUE);
                                // echo '<pre>';print_r($arr_weixin_result);exit();
                        } catch (Exception $ex) {
                        
                        }

                        if (empty($arr_weixin_result['openid'])){
                                jump('/');
                        }
        		$obj_member = L::loadClass('member','index');
        		cookie('member_weixin_data',serialize($arr_weixin_result));
                        $arr_member = $obj_member->get_one_member_b($str_unionid);
                        if(verify_browser()!='weixin'){
                                if($arr_member){
                                         $obj_member->set_login_cookie($arr_member['uid'], $arr_member['password']);
                                         echo '<script>window.opener.location.reload();window.close();</script>';
                                         exit;
                                }
                                else{
                                        echo '<script>window.opener.location="/login";window.close();</script>';  
                                }
                        }
                        else{
                                if($arr_member){
                                         $obj_member->set_login_cookie($arr_member['uid'], $arr_member['password']);
                                         echo '<script>window.location.href="/i";</script>';
                                         exit;
                                }
                                else{
                                        echo '<script>window.location.href="/login";</script>';
                                }       
                        }
        			//$this->refresh_parent();
                }
        	else {
                    jump('/');
                }
        }
}