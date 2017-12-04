<?php
define('D_BUG', 1);
D_BUG ? error_reporting(E_ALL ^ E_NOTICE) : error_reporting(0);

if(defined('SHOW_PAGE_TIME')){ 
	//页面速度测试
	$mtime = explode(' ', microtime());
	$sqlstarttime = number_format(($mtime[1] + $mtime[0] - $_SGLOBAL['supe_starttime']), 6) * 1000;
}

$db_htmifopen = 1;
include_once("./common.php");
$_SGLOBAL['from'] = '';

//define('SHOW_SQL',1);
$mod = str_addslashes($_GET['mod']);
$arrMod = array('index','mall','reg','login','news','about','contact','help','page','i','district','wx_qrcode_pay','third_pay_return','wxpay','weixin','find','game','account','withdrawal');
$mod = $mod ? $mod : 'index';

if(in_array($mod,$arrMod)){
	define('SCR',$mod);
	$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
	define('EXTRA',$extra);
}
else{
	//header('location: /');
	echo 1;
	exit;
}

$obj_member = L::loadClass('member', 'index');
$obj_member_taobao = L::loadClass('member_taobao', 'index');
$int_uid = $obj_member->verify_login();
//$int_uid = 8;
if($int_uid>0){
	$_SGLOBAL['member'] = $obj_member->get_one_member($int_uid);
	$_SGLOBAL['taobao'] = $obj_member_taobao->get_one(array('uid'=>$_SGLOBAL['member']['uid'],'is_check'=>1));

	//今日订单
	$obj_order = L::loadClass('order','index');
	$int_today = strtotime(date('Y-m-d',time()));
	$arr_data_order = array(
		'in_date'=>array('do'=>'gt','val'=>$int_today),
		'uid'=>$int_uid,
		'status'=>array('do'=>'in','val'=>'100,105,110,200,404'),
	);
	$_SGLOBAL['today_order'] = $obj_order->get_list($arr_data_order,1,1,'`order_id` DESC',true);
	$_SGLOBAL['success_order'] = $obj_order->get_list(array('status'=>200,'uid'=>$int_uid),1,1,'`order_id` DESC',true);
}
$arr_config = get_config('config_contact',true);
$_SGLOBAL['data_config'] = $arr_config['val'];


$arr_cart = get_cookie('my_cart');

if(!$arr_cart){
	$arr_cart = array();	
}
$arr_cart && $arr_cart = unserialize($arr_cart);
$_SGLOBAL['cart_num'] = count($arr_cart);



$obj_seo = L::loadClass('seo','index');
$_SGLOBAL['seo'] = $obj_seo->get();
$_SGLOBAL['db_htmifopen'] = $db_htmifopen;
require(S_ROOT.'mod/index/'.SCR.'.mod.php');
require(S_ROOT.'language/zh_cn.php');
$modClassName = 'mod_'.SCR;

$modObj = new $modClassName;

?>