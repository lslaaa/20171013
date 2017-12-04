<?php
 //Code By Safe3 
//Add HTTP_REFERER by D. 
$referer=empty($_SERVER['HTTP_REFERER']) ? array() : array($_SERVER['HTTP_REFERER']);
function customError($errno, $errstr, $errfile, $errline)
{ 
 echo "<b>Error number:</b> [$errno],error on line $errline in $errfile<br />";
 die();
}
set_error_handler("customError",E_ERROR);
$getfilter="'|\\b(and|or)\\b.+?(>|<|=|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
$postfilter="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
$cookiefilter="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
function StopAttack($StrFiltKey,$StrFiltValue,$ArrFiltReq){  



$StrFiltValue=arr_foreach($StrFiltValue);
if (preg_match("/".$ArrFiltReq."/is",$StrFiltValue)==1){   
        slog("<br><br>操作IP: ".$_SERVER["REMOTE_ADDR"]."<br>操作时间: ".strftime("%Y-%m-%d %H:%M:%S")."<br>操作页面:".$_SERVER["PHP_SELF"]."<br>提交方式: ".$_SERVER["REQUEST_METHOD"]."<br>来源: ".$_SERVER['HTTP_REFERER']."<br>提交参数: ".$StrFiltKey."<br>提交数据: ".$StrFiltValue);
		header('HTTP/1.1 400 Bad Request'); 
		header('status: 400 Bad Request');
        exit();
}
if (preg_match("/".$ArrFiltReq."/is",$StrFiltKey)==1){   
        slog("<br><br>操作IP: ".$_SERVER["REMOTE_ADDR"]."<br>操作时间: ".strftime("%Y-%m-%d %H:%M:%S")."<br>操作页面:".$_SERVER["PHP_SELF"]."<br>提交方式: ".$_SERVER["REQUEST_METHOD"]."<br>来源: ".$_SERVER['HTTP_REFERER']."<br>提交参数: ".$StrFiltKey."<br>提交数据: ".$StrFiltValue);
		header('HTTP/1.1 400 Bad Request'); 
		header('status: 400 Bad Request');
        exit();
}  
}  
//$ArrPGC=array_merge($_GET,$_POST,$_COOKIE);
foreach($_GET as $key=>$value){ 
	//StopAttack($key,$value,$getfilter);
}
foreach($_POST as $key=>$value){ 
	//StopAttack($key,$value,$postfilter);
}
foreach($_COOKIE as $key=>$value){ 
	StopAttack($key,$value,$cookiefilter);
}
foreach($referer as $key=>$value){ 
  //StopAttack($key,$value,$getfilter);
}
function slog($logs)
{
  $toppath=$_SERVER["DOCUMENT_ROOT"]."/cache/temp/logs_error";
  $Ts=fopen($toppath,"a+");
  fputs($Ts,$logs."\r\n");
  fclose($Ts);
}
function arr_foreach($arr) {
  static $str;
  if (!is_array($arr)) {
  return $arr;
  }
  foreach ($arr as $key => $val ) {

    if (is_array($val)) {

        arr_foreach($val);
    } else {

      $str[] = $val;
    }
  }
  return implode($str);
}
/*
  [UCenter Home] (C) 2007-2008 Comsenz Inc.
  $Id: common.php 13217 2009-08-21 06:57:53Z liguode $
 */
define('LEM', TRUE);
define('SAFE_TOKEN','eAbeZPcv04');
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
header("Content-type: text/html; charset=utf-8");
$_SGLOBAL = $_SCONFIG = $_SBLOCK = $_TPL = $_SCOOKIE = $_SN = $space = array();
//程序目录
define('S_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('WWW_URL','http://www.hugelem.com');
define('MAT_URL','http://mat.hugelem.com');
define('M_URL','http://20171013.demo.hugelem.cn');
//Cookie配置
$db_cookiepre = 'lem';
$db_cookiedomain = '.hugelem.com';
require_once(S_ROOT."cls/base.php");
include_once(S_ROOT . './source/function_common.php');
require_once(S_ROOT . './cls/mysqlDBA.php'); 
$_SGLOBAL['formhash'] = formhash();
$_SGLOBAL['code_version'] = '1.1237h';
//启用GIP
//var_export($_SGLOBAL);
if ($_SC['gzipcompress'] && function_exists('ob_gzhandler')) {
	ob_start('ob_gzhandler');
} else {
	ob_start();
}
//时间

$mtime = explode(' ', microtime());
$_SGLOBAL['timestamp'] = $mtime[1];
$_SGLOBAL['supe_starttime'] = $_SGLOBAL['timestamp'] + $mtime[0];
$_SGLOBAL['query_string']  = $_SERVER['QUERY_STRING'];

if ($db_htmifopen) {
	$_NGET = parseRewriteQueryString($_SGLOBAL['query_string']);
	!empty($_NGET['mod']) && $_GET = $_NGET;
	$_SGLOBAL['query_string'] = _queryString($_SGLOBAL['query_string']);
}

function parseRewriteQueryString($str_query_string){
	$arr_get = array();
	list($str_query_string,$str_query_string_2) = explode('??',$str_query_string);
	$str_query_string_2 && $str_query_string_2 = explode('&',$str_query_string_2);
	$arr_data = false !== strpos($str_query_string, '&') ? array() : explode('-',$str_query_string);

	for ($i=0, $int_count=count($arr_data); $i<$int_count-1;$i++) {
		$k	= $arr_data[$i];
		$v	= rawurldecode($arr_data[++$i]);
		$arr_get[$k] = addslashes($v);
	}
	if($i<$int_count) $arr_get['extra'] = addslashes(rawurldecode($arr_data[$i]));
	$str_query_string_2 = $str_query_string_2 ? $str_query_string_2 : array();
	foreach($str_query_string_2 as $v){
		$arr_temp = explode('=',$v);
		$arr_temp[1] = rawurldecode($arr_temp[1]);
		$arr_get[$arr_temp[0]] = addslashes($arr_temp[1]);
	}
	return $arr_get;
}

switch ($_SERVER['HTTP_HOST']){
    case '20171013.demo.hugelem.cn':
        break;
    case 'localhost':
        break;
    default :
        sleep(10);
}

dbconnect();//连接数据库
trans_db_connect();//连接数据库
?>