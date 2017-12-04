<?php
!defined('LEM') && exit('Forbidden');

// define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_i {
	function __construct() {
		global $_SGLOBAL;
		// var_export($_SGLOBAL['member']);
		if($_SGLOBAL['member']['uid']<=0){
			jump('/login');	
		}
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
	}

	function extra_index(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$arr_language = $_SGLOBAL['language'];
		$obj_member = L::loadClass('member','index');
		$obj_order = L::loadClass('order','index');
		$obj_money_log = L::loadClass('money_log','index');
		$int_today = strtotime(date('Y-m-d',time()));
		$arr_data_money = array(
			'in_date'=>array('do'=>'gt','val'=>$int_today),
			'uid'=>$arr_member['uid'],
			'type'=>array('do'=>'in','val'=>'20,21,22')
		);
		$arr_member_detail = $obj_member->get_one_member_detail($arr_member['uid']);
		// var_export($arr_member_detail);
		$float_money = $obj_money_log->get_sum($arr_data_money);
		$str_extra = 'index';
		include template('template/index/member/index');
	}

	function extra_password(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$obj_member = L::loadClass('member','index');
		if(submit_check('formhash')){
			$str_password_old = str_addslashes($_POST['password_old']);
			$str_password_new = str_addslashes($_POST['password_new']);
			if ($str_password_old==$str_password_new) {
				callback(array('status'=>304,'info'=>'新密码不能和旧密码一样'));
			}
			if (md5(md5($str_password_old).$arr_member['salt'])!=$arr_member['password']) {
				callback(array('status'=>304,'info'=>'旧密码错误'));
			}
			$arr_data = array('password'=>$str_password_new);
			$result = $obj_member->update_member(array('uid'=>$arr_member['uid']),$arr_data);
			if ($result) {
				$obj_member->login($arr_member['phone'],$str_password_new);
			}
			callback(array('status'=>200,'info'=>'修改成功'));
		}
		
		include template('template/index/member/password');
	}

	function extra_two_password(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$obj_member = L::loadClass('member','index');
		if(submit_check('formhash')){
			$str_password = str_addslashes($_POST['password']);
			$str_phone = str_addslashes($_POST['phone']);
			$str_checkcode = str_addslashes($_POST['checkcode']);
			$obj_checkcode = L::loadClass('checkcode', 'index');
                        //校验手机号码
                        $arr_code = $obj_checkcode->get_one(array('des' => $str_phone, 'type' => 1));
                        if($arr_code['checkcode'] != $str_checkcode){
                            callback(array('info'=>'验证码错误','status'=>304));
                        }
			$arr_data = array('password_2'=>md5(md5($str_password).$arr_member['salt']));
			$result = $obj_member->update_member(array('uid'=>$arr_member['uid']),$arr_data);
			callback(array('status'=>200,'info'=>'修改成功'));
		}
		
		include template('template/index/member/two_password');
	}


	function extra_my_task(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$int_status = intval($_GET['status']);
		$int_status = $int_status ? $int_status :105;
		$obj_order = L::loadClass('order','index');
		//处理30分钟未审核订单
		$obj_member = L::loadClass('member','index');
		$obj_money_log = L::loadClass('money_log','index');
		$obj_shop   = L::loadClass('shop','index');
		$arr_config = get_config('config_contact',true);
                $arr_config = $arr_config['val'];
		$arr_data = array('status'=>100,'in_date'=>array('do'=>'lt','val'=>time()-1800));
		$arr_order = $obj_order->get_list($arr_data,1,100);
		foreach ($arr_order['list'] as $key => $value) {
			$int_time = time();
			$arr_data = array('status'=>90);
			$arr_sqls = array();
			$arr_sqls[] = $obj_order->update_main(array('order_id'=>$value['order_id']),$arr_data,true);
			$arr_sqls[] = $obj_member->update_member(array('uid'=>$value['uid']),array('balance'=>array('do'=>'inc','val'=>$arr_config['data_butie']*30)),true);
			$arr_money = array(
	                        'type'       =>21,
	                        'uid'        =>$value['uid'],
	                        'sid'        =>$value['sid'],
	                        'order_id'   =>$value['order_id'],
	                        'item_id'    =>$value['item_id'],
	                        'money'      =>$arr_config['data_butie']*30,
	                        'content'    =>'自动取消补贴金额',
	                        'in_date'    =>$int_time,
	                );
	                $arr_money_log = $obj_money_log->get_one(array('type'=>21,'uid'=>$arr_money['uid'],'sid'=>$arr_money['sid'],'order_id'=>$arr_money['order_id']));
	                if(!$arr_money_log) {
	                        $arr_sqls[] = $obj_money_log->insert_money_log($arr_money,true);
	                } 
			// var_export($arr_sqls);
			$bool_return = $_SGLOBAL['trans_db']->multi_query($arr_sqls);
		}
		$arr_data = array();
		$int_status && $arr_data['status'] = $int_status;
		$int_status == 105 && $arr_data['status'] = array('do'=>'in','val'=>'100,105');
		$arr_data['uid'] = $arr_member['uid'];
		$arr_data['is_del'] = 0;
		$arr_list = $obj_order->get_list($arr_data,1,100);
                $arr_type_name = $obj_shop->get_type_name();
		
		include template('template/index/member/my_task');
	}

	function extra_task_detail(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$obj_order = L::loadClass('order','index');
		$obj_item = L::loadClass('item','index');
		$obj_shop = L::loadClass('shop','index');
		$obj_item_keyword = L::loadClass('item_keyword','index');
		$obj_item_comment = L::loadClass('item_comment','index');
		$int_order_id = intval($_GET['id']);
		$arr_order_one = $obj_order->get_one($int_order_id);
		$arr_shop = $obj_shop->get_one_main($arr_order_one['main']['sid']);
		if ($arr_order_one['main']['status']==100) {
			$arr_config = get_config('config_contact',true);
                        $arr_config = $arr_config['val'];
                        $arr_data_shenhe = array('sid'=>$arr_order_one['main']['sid'],'status'=>100);
                        $int_shenhe_num = $obj_order->get_list($arr_data_shenhe,1,1,'`order_id` DESC',true);
                        $int_end_time = $arr_order_one['main']['in_date']+1800;
                        $str_end_time = date('Y/m/d H:i:s',$int_end_time);
			include template('template/index/member/task_shenhe');
			exit();
		}
		$arr_type_name = $obj_shop->get_type_name();
		// var_export($arr_shop);
		$arr_order_one['item']['require'] && $arr_order_one['item']['require'] = unserialize($arr_order_one['item']['require']);
		$arr_order_one['item']['huobi_pic'] && $arr_order_one['item']['huobi_pic'] = unserialize($arr_order_one['item']['huobi_pic']);
		$arr_order_one['main']['pics'] && $arr_order_one['main']['pics'] = explode(',',$arr_order_one['main']['pics']);
		$arr_order_one['main']['key_id'] && $arr_order_one['main']['keyword'] = $obj_item_keyword->get_one(array('id'=>$arr_order_one['main']['key_id']));
		$arr_order_one['main']['comment_id'] && $arr_order_one['main']['comment'] = $obj_item_comment->get_one(array('id'=>$arr_order_one['main']['comment_id']));
		$arr_order_one['main']['pic_id'] && $arr_order_one['main']['comment_pic'] = $obj_item_comment->get_one_pic(array('id'=>$arr_order_one['main']['pic_id']));
		$arr_order_one['main']['comment_pic']['pics'] && $arr_order_one['main']['comment_pic']['pics'] = explode(',',$arr_order_one['main']['comment_pic']['pics']);
		// var_export($arr_order_one['item']);
		$arr_price_name = $obj_item->get_price_name();
		// var_export($arr_order_one);
		$arr_config = get_config('config_contact',true);
                $arr_config = $arr_config['val'];
		$arr_data_shenhe = array('sid'=>1,'status'=>100);
                $int_shenhe_num = $obj_order->get_list($arr_data_shenhe,1,1,'`order_id` DESC',true);
                // var_export($arr_config);
		include template('template/index/member/task_detail');
	}

	function extra_cancel_task(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$obj_order = L::loadClass('order','index');
		$obj_item = L::loadClass('item','index');
		$int_order_id = intval($_GET['order_id']);
		if (!$int_order_id) {
			return false;
		}
		$arr_pics = str_addslashes($_POST['pic_url']);
		$arr_data = array(
			'status'=>90,
		);
		$result = $obj_order->update_main(array('order_id'=>$int_order_id),$arr_data);
		if ($result) {
			$arr_order = $obj_order->get_one_main($int_order_id);
			$obj_item->update_main(array('item_id'=>$arr_order['item_id']),array('stock'=>array('do'=>'inc','val'=>1)));
			callback(array('info'=>'取消完成','status'=>200));
		}else{
			callback(array('info'=>'取消失败','status'=>304));
		}
	}

	function extra_send_task(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$obj_order = L::loadClass('order','index');
		$int_order_id = intval($_POST['order_id']);
		if (!$int_order_id) {
			return false;
		}
		$arr_pics = str_addslashes($_POST['pic_url']);
		$arr_data = array(
			'status'=>110,
			'pics'=>implode(',', $arr_pics)
		);
		$result = $obj_order->update_main(array('order_id'=>$int_order_id),$arr_data);
		if ($result) {
			callback(array('info'=>'提交完成','status'=>200));
		}else{
			callback(array('info'=>'提交失败','status'=>304));
		}
	}

	function extra_my_purse(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$arr_language = $_SGLOBAL['language'];
		$obj_member = L::loadClass('member','index');
		$obj_money_log = L::loadClass('money_log','index');
		$int_today = strtotime(date('Y-m-d',time()))-86400*4;
		$arr_data_money = array(
			// 'in_date'=>array('do'=>'gt','val'=>$int_today),
			'uid'=>$arr_member['uid'],
		);
		$arr_money_date = $obj_money_log->get_list_b($arr_data_money);
		$arr_money_log = $obj_money_log->get_list($arr_data_money,1,100);
		foreach ($arr_money_date as $key => $value) {
	                $time = date('Y-m',time());
	                if ($value['in_date_b']==$time) {
	                    $arr_money_date[$key]['date'] = '本月';
	                }else{
	                    $arr_money_date[$key]['date'] = substr($value['in_date_b'],5).'月';
	                }
	                $arr_ids = explode(',', $value['id']);
	                $arr_ids2 = array();
	                $int_ids_num = 0;
	                foreach ($arr_money_log['list'] as $k => $v) {
	                    if (in_array($v['id'], $arr_ids)) {
	                        $arr_ids2[] =$v['id'];
	                        ++$int_ids_num;
	                    }
	                }
	                if ($int_ids_num>0) {
	                    foreach ($arr_ids2 as $k => $val) {
	                        $arr_money_log_one = $obj_money_log->get_one(array('id'=>$val));
	                        $arr_money_date[$key]['money_log'][] = $arr_money_log_one;
	                    }
	                }else{
	                    unset($arr_money_date[$key]);
	                }
	            }
		// var_export($arr_money_date);
		$arr_data_money['type'] = 20;
		$float_money = $obj_money_log->get_sum($arr_data_money);
		include template('template/index/member/my_purse');
	}

	function extra_my_client(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$obj_member = L::loadClass('member','index');
		$arr_list = $obj_member->get_list(array('r_uid'=>$arr_member['uid']),1,10);
		// var_export($arr_list);
		include template('template/index/member/my_client');
	}


	function extra_bd_alipay(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$obj_member = L::loadClass('member','index');
		if(submit_check('formhash')){
			$str_alipay_name = str_addslashes($_POST['alipay_name']);
			$str_alipay = str_addslashes($_POST['alipay']);
			$arr_data = array('alipay_name'=>$str_alipay_name,'alipay'=>$str_alipay);
			$result = $obj_member->update_member(array('uid'=>$arr_member['uid']),$arr_data);
			callback(array('status'=>200,'info'=>'绑定成功'));
		}
		include template('template/index/member/bd_alipay');
	}

	function extra_bd_bank(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$obj_member = L::loadClass('member','index');
		if(submit_check('formhash')){
			$obj_bank = L::loadClass('bank','index');
			$str_bank = str_addslashes($_POST['bank']);
			$str_bank_card = str_addslashes($_POST['bank_card']);
			$str_bank_card_name = str_addslashes($_POST['bank_card_name']);
			$arr_data = array('bank_card'=>$str_bank_card,'bank'=>$str_bank,'bank_card_name'=>$str_bank_card_name);
			$arr_bank = $obj_bank->get_list(array('name'=>$arr_data['bank']));
			if ($arr_bank['total']<1) {
				callback(array('info'=>'开户行错误，请重新填写','status'=>304));
			}
			$result = $obj_member->update_member(array('uid'=>$arr_member['uid']),$arr_data);
			callback(array('status'=>200,'info'=>'绑定成功'));
		}
		include template('template/index/member/bd_bank');
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

	function extra_security(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		include template('template/index/member/security');
	}

	function extra_alerts(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		include template('template/index/member/alerts');
	}

	function extra_gold_detail(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$arr_language = $_SGLOBAL['language'];
		$obj_member = L::loadClass('member','index');
		$obj_money_log = L::loadClass('money_log','index');
		$int_today = strtotime(date('Y-m-d',time()))-86400*4;
		$arr_data_money = array(
			'in_date'=>array('do'=>'gt','val'=>$int_today),
			'uid'=>$arr_member['uid'],
		);
		$arr_money_log = $obj_money_log->get_list($arr_data_money,1,100);
		$arr_data_money['type'] = 20;
		$float_money = $obj_money_log->get_sum($arr_data_money);
		// var_export($arr_money_log);
		include template('template/index/member/gold_detail');
	}

	function extra_qrcode(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$obj_member = L::loadClass('member','index');
		$arr_member = $obj_member->get_one_member($arr_member['uid']);
	        if (!$arr_member['qrcode']) {
	                $str_text = M_URL.'/index/r_uid-'.$arr_member['uid'];
	                $str_pic_path = $this->extra_get_qrcode($str_text);
	                $result = $obj_member->update_member(array('uid'=>$arr_member['uid']),array('qrcode'=>$str_pic_path));
	                $arr_member['qrcode'] = $str_pic_path;
	        }
		include template('template/index/member/qrcode');
	}

	function extra_upload(){
                global $_SGLOBAL;
                $arr_language = $_SGLOBAL['language'];
                $base64_image_content = $_POST['pic'];
                if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
                        $type = $result[2]; //jpeg
                        //去除图片类型
                        $pic = base64_decode(str_replace($result[1], '', $base64_image_content)); //返回文件流
                }
                $str_file_path = '/upload_pic/'.date("ymd").'/';
                if(!file_exists(S_ROOT.$str_file_path)){
                        mkdir(S_ROOT.$str_file_path);   
                }
                $str_file_path .= $_SGLOBAL['timestamp'].rand(1000,9999).'.jpg';
                file_put_contents(S_ROOT.$str_file_path,$pic);
                callback(array('info'=>'','status'=>200,'data'=>$str_file_path));
        }

    	function extra_get_qrcode($text){
	    	global $_SGLOBAL;
	    	$str_text=trim($text);
	        $int_size=16;
		
	        require_once(S_ROOT . 'lib/phpqrcode/phpqrcode.php');
	        $str_file_path  = 'upload_pic/shop_qrcode/'.date("ymd").'/';
	        $file_name  = S_ROOT .'upload_pic/shop_qrcode/'.date("ymd").'/';
			if(!file_exists($file_name)){
				mkdir($file_name,0775,true);
			}
			$file_name  .= $_SGLOBAL['timestamp'].rand(1000,9999).'.png';
			$str_file_path  .= $_SGLOBAL['timestamp'].rand(1000,9999).'.png';
	        QRcode::png($str_text, $str_file_path, QR_ECLEVEL_L, $int_size, 1, false);
	        return $str_file_path;
    	}

	/*
	 * 加水印图片
	 */
	function extra_watermarking($type='', $int_uid=0){
		global $_SGLOBAL;     
		$obj_member = L::loadClass('member','index');
		$arr_member = $_SGLOBAL['member'];
		$arr_member = $obj_member->get_one_member($arr_member['uid']);
	    	if (!$arr_member['qrcode']) {
	    		$str_text = M_URL.'/index/r_uid-'.$arr_member['uid'];
	                $str_pic_path = $this->extra_get_qrcode($str_text);
	                $result = $obj_member->update_member(array('uid'=>$arr_member['uid']),array('qrcode'=>$str_pic_path));
	                $arr_member['qrcode'] = $str_pic_path;
	    	}
	    	$str_bg_img = "/mat/dist/images/index/code.jpg";
	        $str_left_img = $arr_member['qrcode'];

    	   	$this->watermarking_img($str_bg_img, $str_left_img, $str_name);
	}

	function watermarking_img($str_bg_img, $str_left_img,$str_name="a"){
		$str_msyh_file = S_ROOT.'lib/fonts/msyhbd.ttf';
		$str_simkai_file = S_ROOT.'lib/fonts/simkai.ttf';
		$str_bg_img = S_ROOT.$str_bg_img;
		$str_left_img = S_ROOT.$str_left_img;
		$str_left_img = save_image($str_left_img, '145,145');
		$str_left_img = S_ROOT.ltrim($str_left_img,'/');
		//创建图片的实例
		$dst = imagecreatefromstring(file_get_contents($str_bg_img));
		$src = imagecreatefromstring(file_get_contents($str_left_img));
		//获取水印图片的宽高
		list($src_w, $src_h) = getimagesize($str_left_img);
		//将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
		imagecopymerge($dst, $src, 113, 540, 0, 0, $src_w, $src_h, 100);
		//打上文字
		$black = imagecolorallocate($dst, 0x00, 0x00, 0x00);//字体颜色
		// imagefttext($dst, 16, 0, 490, 1050, $black, $str_msyh_file, $str_name);
		//如果水印图片本身带透明色，则使用imagecopy方法
		//imagecopy($dst, $src, 10, 10, 0, 0, $src_w, $src_h);
		//输出图片
		list($dst_w, $dst_h, $dst_type) = getimagesize($str_bg_img);
		switch ($dst_type) {
		case 1://GIF
		    header('Content-Type: image/gif');
		    imagegif($dst);
		    break;
		case 2://JPG
		    header('Content-Type: image/jpeg');
		    imagejpeg($dst);
		    break;
		case 3://PNG
		    header('Content-Type: image/png');
		    imagepng($dst);
		    break;
		default:
		    break;
		}
		unlink($str_left_img);
		imagedestroy($dst);
		imagedestroy($src);
	}

}