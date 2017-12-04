<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_index {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
	}

	function extra_index(){
		global $_SGLOBAL;
		// var_export($_GET);
		if ($_GET['r_uid']) {
			cookie('r_uid',intval($_GET['r_uid']));
		}
		$arr_member = $_SGLOBAL['member'];
		$arr_language = $_SGLOBAL['language'];
		$obj_ads = L::loadClass('ads','index');
		$arr_banner = $obj_ads->get_list(array('ads_cid'=>1,'is_del'=>0),1,5);
		$arr_about = $obj_ads->get_list(array('ads_cid'=>6,'is_del'=>0),1,3);
		// var_export($_SGLOBAL['today_order']);
		if ($arr_member['uid']) {
			//今日收入
			$obj_money_log = L::loadClass('money_log','index');
			$int_today = strtotime(date('Y-m-d',time()));
			$arr_data_money = array(
				'in_date'=>array('do'=>'gt','val'=>$int_today),
				'uid'=>$arr_member['uid'],
				'type'=>array('do'=>'in','val'=>'20,21,22')
			);
			$float_money = $obj_money_log->get_sum($arr_data_money);

		}

		//邀请排行
		$obj_member = L::loadClass('member','index');
		$int_today = strtotime(date('Y-m-d',time()));
		$int_month = strtotime(date('Y-m-1',time()));
		$arr_yaoqing = $obj_member->get_yaoqing_num(array('r_uid'=>array('do'=>'gt','val'=>0)));
		$arr_yaoqing = format_array_val_to_key($arr_yaoqing,'r_uid');
		$arr_yaoqing1 = $obj_member->get_yaoqing_num(array('r_uid'=>array('do'=>'gt','val'=>0),'in_date'=>array('do'=>'gt','val'=>$int_today)));
		$arr_yaoqing1 = format_array_val_to_key($arr_yaoqing1,'r_uid');
		$arr_yaoqing2 = $obj_member->get_yaoqing_num(array('r_uid'=>array('do'=>'gt','val'=>0),'in_date'=>array('do'=>'gt','val'=>$int_month)));
		$arr_yaoqing2 = format_array_val_to_key($arr_yaoqing2,'r_uid');
		// var_export($arr_yaoqing2);
		$arr_yaoqing_member = $obj_member->get_list(array('yaoqing'=>array('do'=>'gt','val'=>0)),1,100,'`yaoqing` DESC');
		$arr_yaoqing_member['list'] = format_array_val_to_key($arr_yaoqing_member['list'],'uid');
		foreach ($arr_yaoqing_member['list'] as $k => $v) {
			if ($arr_yaoqing[$k]) {
				$arr_yaoqing[$k]['member'] = $v;
			}else{
				$arr_yaoqing[$k]['total']  = 0;
				$arr_yaoqing[$k]['member']  = $v;
			}
			if ($arr_yaoqing1[$k]) {
				$arr_yaoqing1[$k]['member'] = $v;
			}else{
				$arr_yaoqing1[$k]['total']  = 0;
				$arr_yaoqing1[$k]['member']  = $v;
			}
			if ($arr_yaoqing2[$k]) {
				$arr_yaoqing2[$k]['member'] = $v;
			}else{
				$arr_yaoqing2[$k]['total']  = 0;
				$arr_yaoqing2[$k]['member'] = $v;
			}
		}

		// var_export($arr_yaoqing1);
		//var_export($arr_news);
		include template('template/index/index');
	}
	
	function extra_qrcode() {
		$str_text=trim($_GET['text'])==""?WWW_URL:urldecode(trim($_GET['text']));		
		$int_size=intval($_GET['size'])>0?intval($_GET['size']):3;
		
        	require_once(S_ROOT . 'lib/phpqrcode/phpqrcode.php');
        	echo QRcode::png($str_text, false, QR_ECLEVEL_L, $int_size, 1, false);
    	}
	
	function extra_check_code(){//µÇÂ¼ÑéÖ¤Âë

		$str_rand = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';
		$str_code_len = 4;
		$str_width = 90;
		$str_height = 30;
		for ($i=0;$i<$str_code_len;$i++) {
			$str_check_code .= $str_rand[mt_rand(0,(strlen($str_rand)-1))];
		}
        session_start();
		$_SESSION['check_code'] = strtolower($str_check_code);
		$obj_pic = imagecreatetruecolor($str_width, $str_height);
		$obj_color = imagecolorallocate($obj_pic, 255,255,255);//ÉèÖÃ±³¾°ÑÕÉ«
		imagefill($obj_pic,0,0,$obj_color);
		imagecolortransparent($obj_pic,$obj_color);
		$str_font = S_ROOT.'lib/fonts/arial.ttf';
		for ($i=0;$i<$str_code_len;$i++) {
			$obj_color = imagecolorallocate($obj_pic,0,0,0);
			imagettftext($obj_pic,15,0,15*$i,25,$obj_color,$str_font,$str_check_code[$i]);
		}
		header('Content-type: image/png');  
		imagepng($obj_pic);  
	}

	function extra_set_dingwei(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$str_city = str_addslashes($_GET['city']);
		$str_district = str_addslashes($_GET['district']);
		$str_district = mb_substr($str_district,0,mb_strlen($str_district,'utf-8')-1,'utf-8');
		// callback(array('info'=>'','status'=>200,'data'=>$str_district));
		// var_export($_GET);
		// exit();
		$str_lat = str_addslashes($_GET['lat']);
		$str_lng = str_addslashes($_GET['lng']);
		$obj_district = L::loadClass('district','index');
		$arr_data = array(
			'name'=>array('do'=>'like','val'=>'%'.$str_city.'%'),
			'level'=>2
		);
		$arr_city = $obj_district->get_list($arr_data);
		$arr_data = array(
			'name'=>array('do'=>'like','val'=>'%'.$str_district.'%'),
			'pid'=>$arr_city[0]['id'],
			'level'=>3
		);
		$arr_area = $obj_district->get_list($arr_data);

		$arr_district = unserialize(get_cookie('district'));
		$arr_district['lat'] = $str_lat;
		$arr_district['lng'] = $str_lng;
		$arr_district['d_province'] = $arr_city[0]['pid'];
		$arr_district['d_city'] = $arr_city[0]['id'];
		$arr_district['d_city_name'] = $arr_city[0]['name'];
		$arr_district['d_area'] = $arr_area[0]['id'];
		$arr_district['d_area_name'] = $arr_area[0]['name'];
		if (!$arr_district['city']) {
			$arr_district['city'] = $arr_city[0]['id'];
			$arr_district['city_name'] = $arr_city[0]['name'];
		}
		if (!$arr_district['area']) {
			$arr_district['area'] = $arr_area[0]['id'];
			$arr_district['area_name'] = $arr_area[0]['name'];
		}
		if ($arr_member['uid']) {
			$obj_member = L::loadClass('member','index');
			$obj_member->update_member(array('uid'=>$arr_member['uid']),array('lat'=>$arr_district['lat'],'lng'=>$arr_district['lng']));
			$obj_member->update_member_detail(array('uid'=>$arr_member['uid']),array('province'=>$arr_district['d_province'],'city'=>$arr_district['d_city'],'area'=>$arr_district['d_area']));
		}
		// var_export($arr_district);
		cookie('district',serialize($arr_district),time()+3600);
		callback(array('info'=>'','status'=>200,'data'=>$arr_district['city_name']));
        }
}