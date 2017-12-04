<?php
define('D_BUG', 1);
D_BUG ? error_reporting(E_ALL ^ E_NOTICE) : error_reporting(0);
session_start();

if(defined('SHOW_PAGE_TIME')){ 
    //页面速度测试
    $mtime = explode(' ', microtime());
    $sqlstarttime = number_format(($mtime[1] + $mtime[0] - $_SGLOBAL['supe_starttime']), 6) * 1000;
}

$db_htmifopen = 1;
include_once("./common.php");
$_SGLOBAL['from'] = '';
//define('SHOW_SQL',1);
$str_mod_dir = str_addslashes($_GET['mod_dir']);
$str_mod_dir = $str_mod_dir ? $str_mod_dir : 'default';
$mod = str_addslashes($_GET['mod']);
$arr_mod = array(
    'default' => array('main', 'login','home','sms','package','pay','callback','notify','wxpayx','authorization','wxmsg','weixin','huibo_call','authorization2','hongbao'),
    'setting' => array('menu','member','member_group','seo','config'),
    'member'=>array('member','member_group'),
    'ads'=>array('ads'),
    'firend_link'=>array('firend_link'),
    'mall'=>array('item','item_cat','order','commission'),
    'news'=>array('news'),
    'consult'=>array('consult'),
    'case'=>array('case'),
    'pic'=>array('pic'),
    'file'=>array('file'),
    'account'=>array('account_tmshop','account_after'),
    'setbase'=>array('setbase'),
    'order'=>array('order'),
    'money'=>array('money','money_log'),
    'data'=>array('data','data_refund','data_bad_neutral','data_kefu','data_shkefu'),
    'user'=>array('user'),
    'message'=>array('message'),
);
$arr_mod = $arr_mod[$str_mod_dir];
if ($str_mod_dir == 'default') {
    $str_mod_dir = '';
    $mod = $mod ? $mod : 'index';
}
define('MOD_DIR', $str_mod_dir);
$arr_config = get_config('config_contact',true);
$_SGLOBAL['data_config'] = $arr_config['val'];
$obj_index_user = L::loadClass('user', 'index');

//判断登录
$bool_login = $obj_index_user->verify_login();
if (!$bool_login && $mod!='sms') {
    $json_data = file_get_contents('php://input') ? file_get_contents('php://input') : @gzuncompress($GLOBALS ['HTTP_RAW_POST_DATA']);
    if (empty($json_data)) {
        $str_server = str_addslashes($_POST['str_server']); //服务器发送过来的加密字符串
        $str_client = str_addslashes($_POST['str_client']); //未加密之前的字符串 
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
    $_SGLOBAL['member'] = $obj_index_user->get_one_member($_SESSION['index_user_login_id']);
}


define('SCR',$mod);
define('EXTRA',$extra);
$_SGLOBAL['db_htmifopen'] = $db_htmifopen;
require(S_ROOT . 'mod/shop/' . $str_mod_dir . '/' . SCR . '.mod.php');
require(S_ROOT.'language/admin_zh_cn.php');
require(S_ROOT.'ssi/config/shop_menu.php');
$modClassName = 'mod_'.SCR;
$modObj = new $modClassName;

?>