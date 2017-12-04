<?php
define('D_BUG', 1);
D_BUG ? error_reporting(E_ALL ^ E_NOTICE) : error_reporting(0);
define('S_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
$arrHeader = array(
    '.js' => 'Content-Type: application/x-javascript',
    '.css' => 'Content-Type: text/css',
	'.jpg'=>'Content-type: image/jpg',
	'.gif'=>'Content-type: image/gif',
);
$str_file_dir = $_GET['file_dir'];
$split_a= explode("??",$_SERVER['REQUEST_URI']);
$strPath = $split_a[0].'/';
$arrFiles = explode(",",$split_a[1]);
$strFiletype = strrchr(preg_replace('/\?.*/','',$arrFiles[0]),'.');
header("Expires: " . date("D, j M Y H:i:s", strtotime("now - 10 years")) ." GMT");
header("Cache-Control: max-age=-1");
if($str_file_dir){
	$strFiletype = strrchr($str_file_dir,'.');;
	header($arrHeader[$strFiletype]);
}
header($arrHeader[$strFiletype]);
if($_GET['file_dir']){
	echo file_get_contents(S_ROOT.'src/'.$str_file_dir);	
	exit;
}
foreach($arrFiles as $v){
	$v = preg_replace('/\?.*/','',$v);
	if(!file_exists(S_ROOT.$strPath.$v)){
		echo 'can\'t open file '.S_ROOT.$strPath.$v;
		exit;
	}
	if(!strstr($v,'hash.js')){
		$str_current = str_replace('/dist/','/src/',$strPath.$v);	
	}
	else{
		$str_current = 	$strPath.$v;
	}
	echo "\r\n".'/*'.$str_current.'*/'."\r\n";
	
	echo file_get_contents(S_ROOT.$str_current);
}
?>