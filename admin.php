<?php
define('D_BUG', 1);
D_BUG ? error_reporting(E_ALL ^ E_NOTICE) : error_reporting(0);
session_start();

if(defined('SHOW_PAGE_TIME')){ 
	//页面速度测试
	$mtime = explode(' ', microtime());
	$sqlstarttime = number_format(($mtime[1] + $mtime[0] - $_SGLOBAL['supe_starttime']), 6) * 1000;
}

$db_htmifopen = 0;
include_once("./common.php");
$_SGLOBAL['from'] = '';

//define('SHOW_SQL',1);
$str_mod_dir = str_addslashes($_GET['mod_dir']);
$str_mod_dir = $str_mod_dir ? $str_mod_dir : 'default';
$mod = str_addslashes($_GET['mod']);
$arr_mod = array(
    'default' => array('main', 'login','page'),
    'setting' => array('menu','member','member_group','seo','config'),
	'member'=>array('member','member_group'),
	'ads'=>array('ads'),
    'firend_link'=>array('firend_link'),
	'mall'=>array('item','item_cat','order','commission'),
    'news'=>array('news'),
	'money'=>array('money'),
	'consult'=>array('consult'),
	'case'=>array('case'),
	'pic'=>array('pic'),
	'video'=>array('video'),
    'file'=>array('file'),
    'cli'=>array('item','order','member','message'),
	'shop'=>array('member_shop','shop','user_group'),
    'join'=>array('join')
);
$arr_mod = $arr_mod[$str_mod_dir];
if ($str_mod_dir == 'default') {
    $str_mod_dir = '';
    $mod = $mod ? $mod : 'login';
}

$obj_admin_member = L::loadClass('admin_member', 'admin');

//判断登录
$bool_login = $obj_admin_member->verify_login();

if (!$bool_login) {
     $json_data = file_get_contents('php://input') ? file_get_contents('php://input') : @gzuncompress($GLOBALS ['HTTP_RAW_POST_DATA']);
    // var_export($_POST);
    if (!empty($json_data)) {
        $str_server = $_POST['str_server'] ? str_addslashes($_POST['str_server']) : str_addslashes($_GET['str_server']); //服务器发送过来的加密字符串
        $str_client = $_POST['str_client'] ? str_addslashes($_POST['str_client']) : str_addslashes($_GET['str_client']); //未加密之前的字符串
    } else {
        $arr_data = json_decode($json_data, true);
        $str_server = $arr_data['str_server'];
        $str_client = $arr_data['str_client'];
    }
    $str_verify = base64_encode(hash_hmac("SHA1", $str_client, SAFE_TOKEN, true)); //计算加密后的字符串

    if ($str_server != $str_verify) {
        $mod = 'login';
        if ($_GET['extra'] != 'check_code') {
            $_GET['extra'] = $_POST['extra'] = 'index';
        }
    }
} else {
    //print_r($_SESSION);
    $_SGLOBAL['member'] = $obj_admin_member->get_one_member($_SESSION['admin_member_login_uid']);
}
define('MOD_DIR',$_GET['mod_dir']);
if(in_array($mod,$arr_mod)){
	define('SCR',$mod);
	$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
	define('EXTRA',$extra);
}
else{
	$str_from = urlencode($_SERVER['REQUEST_URI']);
    echo '<script>parent.location.href="/admin.php?from=' . $str_from . '";</script>';
	exit;
}
$_SGLOBAL['db_htmifopen'] = $db_htmifopen;
require(S_ROOT . 'mod/admin/' . $str_mod_dir . '/' . SCR . '.mod.php');
require(S_ROOT.'language/admin_zh_cn.php');
require(S_ROOT.'ssi/config/shop_menu.php');
$modClassName = 'mod_'.SCR;
$modObj = new $modClassName;

?>