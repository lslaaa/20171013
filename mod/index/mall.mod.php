<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_mall {
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
		$int_cid = intval($_GET['cid']);
		$int_page = intval($_GET['page']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 1;
		$obj_item = L::loadClass('item','index');
		$arr_data = array();
		$int_cid && $arr_data['cid_1'] = $int_cid;
		$arr_data['status'] = LEM_item::ACTIVE;
		$arr_data['is_del'] = 0;
		
		$arr_item = $obj_item->get_list($arr_data,$int_page,$int_page_size);
		$str_num_of_page = numofpage_b($int_page,ceil($arr_item['total']/$int_page_size),'/mall/page-');
		
		foreach($arr_item['list'] as $k=>$v){
			$arr_item['list'][$k]['detail'] = $obj_item->get_one_detail($v['item_id']);
			
		}
		
		$obj_ads = L::loadClass('ads','index');
		$arr_banner = $obj_ads->get_list(array('ads_cid'=>9,'is_del'=>0),1,1);
		$arr_banner && $arr_banner = current($arr_banner['list']);
		$str_page_title = $arr_language['mall'];
		include template('template/index/mall_list');
	}
	
	function extra_detail(){
		global $_SGLOBAL;
		$int_item_id = intval($_GET['id']);
		if(empty($int_item_id)){
			return false;	
		}
		$obj_item = L::loadClass('item','index');
		$arr_item = $obj_item->get_one($int_item_id);
		if(empty($arr_item)){
			return false;	
		}
		$obj_ads = L::loadClass('ads','index');
		$arr_banner = $obj_ads->get_list(array('ads_cid'=>9,'is_del'=>0),1,1);
		$arr_banner && $arr_banner = current($arr_banner['list']);
	//var_export($arr_size);
		$str_page_title = $arr_item['main']['title'];
		$arr_language = $_SGLOBAL['language'];
		include template('template/index/mall_detail');
	}
	
	function extra_cart(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$obj_item = L::loadClass('item','index');
		$obj_item_cat = L::loadClass('admin_mall_item_cat','admin');
		
		$arr_cart = get_cookie('my_cart');
		if(!$arr_cart){
			$arr_cart = array();	
		}
		$arr_cart && $arr_cart = unserialize($arr_cart);

		if(count($arr_cart)<=0){
			jump('/mall');	
		}
		$arr_item_main = array();
		$arr_item = array();
		$i = 0;
		$arr_sku_cat = $obj_item->get_list_sku();
		//var_export($arr_sku_cat);
		foreach($arr_cart as $v){
			!isset($arr_item_main[$v['item_id']]) && $arr_item_main[$v['item_id']] = $obj_item->get_one_main($v['item_id']);
			$arr_data = array(
							'item_id'=>$v['item_id'],
							'sku_1'=>$v['sku_1'],
							'sku_2'=>$v['sku_2'],
						);
			$arr_item[$i] = $arr_item_main[$v['item_id']];
			$arr_item[$i]['sku'] = $obj_item->get_one_item_sku_b($arr_data);
			$arr_item[$i]['sku'] = current($arr_item[$i]['sku']);
			$arr_item[$i]['sku']['num'] = $v['num'];
			$arr_item[$i]['sku']['pic'] && $arr_item[$i]['pic'] = $arr_item[$i]['sku']['pic'];
			$arr_item[$i]['sku']['special_sku_2'] = $v['special_sku_2'];
			$i++;
		}
		$float_yun_fei = get_config('yunfei');
		$float_yun_fei = floatval($float_yun_fei['val']);
		//var_export($arr_item);
		$str_page_title = $arr_language['item']['mall_cart'];
		
		include template('template/index/mall_cart');
	}
	
	function extra_checkout(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		$arr_language = $_SGLOBAL['language'];
		$obj_item = L::loadClass('item','index');
		$obj_address = L::loadClass('address','index');
		$obj_item_cat = L::loadClass('admin_mall_item_cat','admin');
		$obj_district = L::loadClass('district','index');
		
		$arr_cart = get_cookie('my_cart');
		if(!$arr_cart){
			$arr_cart = array();	
		}
		$arr_cart && $arr_cart = unserialize($arr_cart);

		if(count($arr_cart)<=0){
			jump('/mall');	
		}
		$arr_item_main = array();
		$arr_item = array();
		$i = 0;
		$arr_sku_cat = $obj_item->get_list_sku();
		//var_export($arr_sku_cat);
		$float_total = 0;
		foreach($arr_cart as $v){
			!isset($arr_item_main[$v['item_id']]) && $arr_item_main[$v['item_id']] = $obj_item->get_one_main($v['item_id']);
			$arr_data = array(
							'item_id'=>$v['item_id'],
							'sku_1'=>$v['sku_1'],
							'sku_2'=>$v['sku_2'],
						);
			$arr_item[$i] = $arr_item_main[$v['item_id']];
			$arr_item[$i]['sku'] = $obj_item->get_one_item_sku_b($arr_data);
			$arr_item[$i]['sku'] = current($arr_item[$i]['sku']);
			$arr_item[$i]['sku']['num'] = $v['num'];
			$arr_item[$i]['sku']['pic'] && $arr_item[$i]['pic'] = $arr_item[$i]['sku']['pic'];
			$float_total += $arr_item[$i]['sku']['price'] * $v['num'];
			$i++;
		}
		if($arr_member['uid']>0){
			$str_district_ids = '';
			$arr_address = $obj_address->get_list($arr_member['uid']);
			foreach($arr_address as $v){
				$str_district_ids .= $v['province'].','.$v['city'].','.$v['area'].',';	
			}
			$str_district_ids = rtrim($str_district_ids,',');
			$str_district_ids && $arr_district = $obj_district->get_list(array('id'=>array('do'=>'in','val'=>$str_district_ids)),'id');
		}
		
		
		$float_yun_fei = get_config('yunfei');
		$float_yun_fei = floatval($float_yun_fei['val']);
		//var_export($arr_address);
		$str_page_title = $arr_language['order']['checkout'];
		//var_export($str_page_title);
		include template('template/index/checkout');
	}
	
	function extra_insert_order(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$arr_member = $_SGLOBAL['member'];
		$arr_cart = get_cookie('my_cart');
		if(!$arr_cart){
			$arr_cart = array();	
		}
		$arr_cart && $arr_cart = unserialize($arr_cart);

		if(count($arr_cart)<=0){
			jump('/mall');	
		}
		$int_address_id = intval($_POST['address_id']);
		
		$obj_item    = L::loadClass('item','index');
		$obj_order   = L::loadClass('order','index');
		if($int_address_id){
			$obj_address = L::loadClass('address','index');
			$arr_address = $obj_address->get_one($int_address_id);
			$str_contact = $arr_address['contact'];
			$str_phone   = $arr_address['phone'];
			$str_address    = $arr_address['address'];
			$int_province   = $arr_address['province'];
			$int_city       = $arr_address['city'];
			$int_area       = $arr_address['area'];
			$str_zip_code   = $arr_address['zip_code'];
		}
		else{
			return false;	
		}
		
		$float_yun_fei = 0;
		
		$float_total = 0;
		$arr_order_sku = array();
		$i = 0;
		foreach($arr_cart as $v){
			$arr_data = array(
							'item_id'=>$v['item_id'],
							'sku_1'=>$v['sku_1'],
							'sku_2'=>$v['sku_2']
						);
			$arr_item[$i]['sku'] = $obj_item->get_one_item_sku_b($arr_data);
			$arr_item[$i]['sku'] = current($arr_item[$i]['sku']);
			$float_total += $arr_item[$i]['sku']['price'] * $v['num'];
			unset($v['special_sku_2']);
			$arr_order_sku[$i] = $v;
			
			$arr_order_sku[$i]['price'] = $arr_item[$i]['sku']['price'];
			$i++;
		}
		$arr_data = array(
						'uid'=>$arr_member['uid'],
						'status'=>LEM_order::ORDER_NO_PAY,
						'price'=>$float_total,
						'yunfei'=>$float_yun_fei,
						'total_price'=>($float_total+$float_yun_fei),
						'in_date'=>$_SGLOBAL['timestamp']
					);
		$int_order_id = $obj_order->insert($arr_data);
		$arr_data = array(
								'order_id'=>$int_order_id,
								'contact'=>$str_contact,
								'phone'=>$str_phone,
								'zip_code'=>$str_zip_code,
								'province'=>$int_province,
								'city'=>$int_city,
								'area'=>$int_area,
								'address'=>$str_address,
							);
		$obj_order->insert_detail($arr_data);
		foreach($arr_order_sku as $v){
			$v['order_id'] = $int_order_id;
			var_export($v);
			$obj_order->insert_item($v);
		}
		cookie('my_cart','',-1);
		callback(array('info'=>'','status'=>200,'data'=>$int_order_id));
	}
	
	function extra_pay(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$arr_member = $_SGLOBAL['member'];
		$int_order_id = intval($_GET['id']);
		if(empty($int_order_id)){
			jump('/mall');	
		}
		$obj_order = L::loadClass('order','index');
		$arr_order = $obj_order->get_one_main($int_order_id);

		if(empty($int_order_id) || $arr_order['uid']!=$arr_member['uid']){
			jump('/mall');	
		}
		
		include_once(S_ROOT . "lib/payment/wxpay/WxPayPubHelper.php");
        $nativeLink_pub = new NativeLink_pub();
        $nativeLink_pub->setParameter('product_id', $int_order_id);
        $str_pay_url = urlencode($nativeLink_pub->getUrl());
		//var_export($str_pay_url);
		$str_page_title = $arr_language['order']['pay'];
		//var_export($str_page_title);
		include template('template/index/pay');
	}
	
	function extra_get_item_data(){
		global $_SGLOBAL;
		$int_item_id = intval($_GET['item_id']);
		$int_sku_1 = intval($_GET['sku_1']);
		$int_sku_2 = intval($_GET['sku_2']);
		$arr_data = array(
						'item_id'=>$int_item_id,
						'sku_1'=>$int_sku_1,
						'sku_2'=>$int_sku_1,
					);
		
		$obj_item = L::loadClass('item','index');
		$obj_item_cat = L::loadClass('admin_mall_item_cat','admin');

		$int_curr_sku_1 = $int_sku_1;
		$arr_item = $obj_item->get_one($int_item_id);
		$arr_sku_cat = $obj_item->get_list_sku();
		
		$arr_sku = array();
		$arr_size = array();
		foreach($arr_item['sku'] as $k=>$v){
			if($v['sku_1']==$int_sku_1 && $v['sku_2']==$int_sku_2){
				$arr_item['main']['price'] = $v['price'];
				if($v['pic']){
					$arr_item['main']['pic_curr'] = $v['pic'];
				}
				else{
					$arr_item['main']['pic_curr'] = $arr_item['main']['pic'];	
				}
				$arr_item['main']['sku_1'] = $v['sku_1'];
			}
			if(!isset($arr_sku[$v['sku_1']])){
				//var_export($v['pic']);
				!$v['pic'] && $v['pic'] = $arr_item['main']['pic'];
				$v['sku_1_cat'] = $arr_sku_cat[$v['sku_1']];
				$arr_sku[$v['sku_1']] = $v;	
			}
			if($int_curr_sku_1==$v['sku_1']){
				if($arr_item['main']['special_size']>0){
					$arr_sku_cat[$v['sku_2']]['special_name'] = 'M'.$arr_sku_cat[$v['sku_2']]['name'];
					$arr_size[] = $arr_sku_cat[$v['sku_2']];
					$arr_sku_cat[$v['sku_2']]['special_name'] = 'L'.$arr_sku_cat[$v['sku_2']]['name'];
					$arr_size[] = $arr_sku_cat[$v['sku_2']];
					unset($arr_sku_cat[$v['sku_2']]['special_name']);
				}
				else{
					$arr_size[] = $arr_sku_cat[$v['sku_2']];
				}
			}
		}
		$arr_curr_cat = $obj_item_cat->get_one($arr_item['main']['cid_2']);
		$arr_size = sys_sort_array($arr_size,'name');
		$arr_return = array('item'=>$arr_item['main'],'size'=>$arr_size,'sku_1'=>$int_sku_1,'sku_2'=>$int_sku_2);
		callback(array('info'=>'','status'=>200,'data'=>$arr_return));
	}

	
	function extra_add_cart(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_item_id = intval($_GET['item_id']);
		$int_sku_1 = intval($_GET['sku_1']);
		$int_sku_2 = intval($_GET['sku_2']);
		$int_num   = intval($_GET['num']);
		$str_type = $_GET['type'];
		$str_old_cart_item = $_GET['old_cart_item'];
		$arr_data = array(
						'item_id'=>$int_item_id,
						'sku_1'=>$int_sku_1,
						'sku_2'=>$int_sku_2,
						'num'=>$int_num,
						'special_sku_2'=>$str_special_sku_2,
					);
		$str_cart_key = $int_item_id.'_'.$int_sku_1.'_'.$int_sku_2;
		$arr_cart = get_cookie('my_cart');

		if(!$arr_cart){
			$arr_cart = array();	
		}
		$arr_cart && $arr_cart = unserialize($arr_cart);
		if($str_old_cart_item){
			unset($arr_cart[$str_old_cart_item]);
		}
		if(isset($arr_cart[$str_cart_key]) && $str_type!='update'){
			$arr_cart[$str_cart_key]['num']	 = $arr_cart[$str_cart_key]['num'] + $int_num;
		}
		else{
			$arr_cart[$str_cart_key] = $arr_data;
		}
		//var_export($arr_cart);
		cookie('my_cart',serialize($arr_cart),$_SGLOBAL['timestamp']+3600*24*30);
		callback(array('info'=>$arr_language['ok_2'],'status'=>200,'data'=>count($arr_cart)));
	}
	
	function extra_del_cart(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_item_id = intval($_GET['item_id']);
		$int_sku_1 = intval($_GET['sku_1']);
		$int_sku_2 = intval($_GET['sku_2']);
		
		$str_cart_key = $int_item_id.'_'.$int_sku_1.'_'.$int_sku_2;
		$arr_cart = get_cookie('my_cart');

		if(!$arr_cart){
			$arr_cart = array();	
		}
		$arr_cart && $arr_cart = unserialize($arr_cart);

		unset($arr_cart[$str_cart_key]);
		cookie('my_cart',serialize($arr_cart),$_SGLOBAL['timestamp']+3600*24*30);
		callback(array('info'=>$arr_language['ok_2'],'status'=>200,'data'=>count($arr_cart)));
		
	}
	
	function extra_get_address(){
		global $_SGLOBAL;
		$int_address_id = intval($_GET['address_id']);

		if(!$int_address_id){
			return false;	
		}
		$obj_address = L::loadClass('address','index');
		$arr_data = $obj_address->get_one($int_address_id);
		callback(array('info'=>'','status'=>200,'data'=>$arr_data));
	}
	
	function extra_add_address(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		if($arr_member['uid']<=0){
			return false;	
		}
		$obj_address = L::loadClass('address','index');
		$int_address_id = intval($_POST['address_id']);
		$str_address_nickname = str_addslashes($_POST['address_nickname']);
		
		$str_contact  = str_addslashes($_POST['contact']);
		$str_phone    = str_addslashes($_POST['phone']);
		$int_province = intval($_POST['province']);
		$int_city     = intval($_POST['city']);
		$int_area     = intval($_POST['area']);
		$str_address  = str_addslashes($_POST['address']);
		$str_zip_code = str_addslashes($_POST['zip_code']);
		
		
		
		$arr_data = array(
						'uid'=>$arr_member['uid'],
						//'address_nickname'=>$str_address_nickname,
						'contact'=>$str_contact,
						'phone'=>$str_phone,
						'province'=>$int_province,
						'city'=>$int_city,
						'area'=>$int_area,
						'address'=>$str_address,
						'zip_code'=>$str_zip_code
					);
		if(empty($int_address_id)){
			$int_address_id = $obj_address->insert($arr_data);
		}
		else{
			$obj_address->update(array('address_id'=>$int_address_id,'uid'=>$arr_member['uid']), $arr_data);	
		}
		$arr_data['address_id'] = $int_address_id;
		$obj_district   = L::loadClass('district','index');
		$str_district_ids = $int_province.','.$int_city.','.$int_area;
		$arr_district = $obj_district->get_list(array('id'=>array('do'=>'in','val'=>$str_district_ids)),'id');
		$arr_data['address'] = $arr_district[$int_province]['name'].' '.$arr_district[$int_city]['name'].' '.$arr_district[$int_area]['name'].' '.$arr_data['address'];
		callback(array('info'=>'','status'=>200,'data'=>$arr_data));
	}
	
	function extra_verify_saoma(){
		global $_SGLOBAL;
		$arr_member = $_SGLOBAL['member'];
		if($arr_member['uid']<=0){
			return false;	
		}
		$obj_order = L::loadClass('order','index');
		$int_order_id = intval($_GET['id']);
		if(empty($int_order_id)){
			return false;	
		}
		$arr_order = $obj_order->get_one_main($int_order_id);
		if($arr_member['uid']!=$arr_order['uid']){
			return false;	
		}
		$bool = false;
		if($arr_order['status']==LEM_order::ORDER_PAY){
			$bool = true;
		}
		callback(array('info'=>'','status'=>200,'data'=>$bool));
	}
}