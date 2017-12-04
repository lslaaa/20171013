<?php

!defined('LEM') && exit('Forbidden');
function getIP() {
  $IP = '';
  if (getenv('HTTP_CLIENT_IP')) {
    $IP =getenv('HTTP_CLIENT_IP');
  } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
    $IP =getenv('HTTP_X_FORWARDED_FOR');
  } elseif (getenv('HTTP_X_FORWARDED')) {
    $IP =getenv('HTTP_X_FORWARDED');
  } elseif (getenv('HTTP_FORWARDED_FOR')) {
    $IP =getenv('HTTP_FORWARDED_FOR');
  } elseif (getenv('HTTP_FORWARDED')) {
    $IP = getenv('HTTP_FORWARDED');
  } else {
    $IP = $_SERVER['REMOTE_ADDR'];
  }
return $IP;
} 
//SQL ADDSLASHES
function str_addslashes($string) {
	if(is_array($string)) {
		foreach($string as $key =>$val) {
			$string[$key] = str_addslashes($val);
		}
	} else {
		$string = preg_replace('/(?!<[^>]*)"(?![^<]*>)/','&quot;', $string);
		if(!MAGIC_QUOTES_GPC) $string = addslashes($string);
	}
	return $string;
}

function intvals($int) {
	if(is_array($int)) {
		foreach($int as $key =>$val) {
			$int[$key] = intvals($val);
		}
	} else {
		$int = intval($int);
	}
	return $int;
}

function floatvals($float) {
	if(is_array($float)) {
		foreach($float as $key =>$val) {
			$float[$key] = floatvals($val);
		}
	} else {
		$float = floatval($float);
	}
	return $float;
}

//取消HTML代码
function shtmlspecialchars($string) {
	if (is_array($string)) {
		foreach ($string as $key => $val) {
			$string[$key] = shtmlspecialchars($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
				str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}

//字符串解密加密
function authcode($string, $operation = 'DECODE', $key = 'd63O_3_3', $expiry = 0) {

	$ckey_length = 4; // 随机密钥长度 取值 0-32;
	// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
	// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
	// 当此值为 0 时，则不产生随机密钥

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya . md5($keya . $keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for ($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for ($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for ($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if ($operation == 'DECODE') {
		if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc . str_replace('=', '', base64_encode($result));
	}
}

function dbconnect() {
	global $_SGLOBAL;
	include_once(S_ROOT . 'ssi/cls/class_mysql.php');
	include_once(S_ROOT . 'ssi/config/mysql.conf.php');
	$intRand = rand(0,count($_dbhost['slave'])-1);
	$_slave = $_dbhost['slave'][$intRand];
	//随机实例化读数据库
	if (empty($_SGLOBAL['db'])) {
		$_SGLOBAL['db'] = new mysql_rw;
		$_SGLOBAL['db']->charset = $_slave['dbcharset'];
		$_SGLOBAL['db']->connect($_slave['dbhost'], $_slave['dbuser'], $_slave['dbpw'], $_slave['dbname'], $_slave['pconnect'],true,'ro');
	}
}

function trans_db_connect() {
        global $_SGLOBAL;
        include_once(S_ROOT . 'ssi/cls/transmysql.class.php');
        include_once(S_ROOT . 'ssi/config/transmysql.conf.php');
        //实例化分布式数据库
        if (empty($_SGLOBAL['trans_db'])) {
                $_SGLOBAL['trans_db'] = new transmysql;
                $_SGLOBAL['trans_db']->_init($arr_server, $arr_db_table_name);
        }
}

//判断提交是否正确
function submit_check($var) {

	if (!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST') {

		if ((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) && $_POST[$var] == formhash()) {
			return true;
		} else {
			//showmessage('submit_invalid');
			return false;
		}
	} else {
		return false;
	}
}



//ob
function obclean() {
	global $_shop;

	ob_end_clean();
	if ($_shop['gzipcompress'] && function_exists('ob_gzhandler')) {
		ob_start('ob_gzhandler');
	} else {
		ob_start();
	}
}

//模板调用
function template($name) {
    global $_shopONFIG, $_SGLOBAL;
    if (str_exists($name, '/')) {
        $tpl = $name;
    } else {
        $tpl = "template/$_shopONFIG[template]/$name";
    }
    //if (file_exists('/dev/shm/tpl_cache') && strstr(S_ROOT, '/data0/htdocs')) {
     //   $objfile = '/dev/shm/tpl_cache/' . str_replace('/', '_', $tpl) . '.php';
    //} else {
        $objfile = S_ROOT . './data/tpl_cache/' . str_replace('/', '_', $tpl) . '.php';
    //}
    if (defined('D_BUG')) {
        @unlink($objfile);
    }
    if (!file_exists($objfile) || @filemtime(S_ROOT . $tpl . '.htm') > @filemtime($objfile)) {
        @unlink($objfile);
        include_once(S_ROOT . './source/function_template.php');
        parse_template($tpl);
    }
    return $objfile;
}
//判断字符串是否存在
function str_exists($haystack, $needle) {
    return !(strpos($haystack, $needle) === FALSE);
}
//子模板更新检查
function sub_template_check($subfiles, $mktime, $tpl) {
    global $_shop, $_shopONFIG;

    if ($_shop['tplrefresh'] && ($_shop['tplrefresh'] == 1 || mt_rand(1, $_shop['tplrefresh']) == 1)) {
        $subfiles = explode('|', $subfiles);
        foreach ($subfiles as $subfile) {
            $tplfile = S_ROOT . './' . $subfile . '.htm';
            if (!file_exists($tplfile)) {
                $tplfile = str_replace('/' . $_shopONFIG['template'] . '/', '/default/', $tplfile);
            }
            @$submktime = filemtime($tplfile);
            if ($submktime > $mktime) {
                include_once(S_ROOT . './source/function_template.php');
                parse_template($tpl);
                break;
            }
        }
    }
}


//获取文件内容
function sreadfile($filename) {
	$content = '';
	if (function_exists('file_get_contents')) {
		@$content = file_get_contents($filename);
	} else {
		if (@$fp = fopen($filename, 'r')) {
			@$content = fread($fp, filesize($filename));
			@fclose($fp);
		}
	}
	return $content;
}

//判断字符串是否存在
function strexists($haystack, $needle) {
	return!(strpos($haystack, $needle) === FALSE);
}

//子模板更新检查
function subtplcheck($subfiles, $mktime, $tpl) {
	global $_shop, $_shopONFIG;

	if ($_shop['tplrefresh'] && ($_shop['tplrefresh'] == 1 || mt_rand(1, $_shop['tplrefresh']) == 1)) {
		$subfiles = explode('|', $subfiles);
		foreach ($subfiles as $subfile) {
			$tplfile = S_ROOT . './' . $subfile . '.htm';
			if (!file_exists($tplfile)) {
				$tplfile = str_replace('/' . $_shopONFIG['template'] . '/', '/default/', $tplfile);
			}
			@$submktime = filemtime($tplfile);
			if ($submktime > $mktime) {
				include_once(S_ROOT . './source/function_template.php');
				parse_template($tpl);
				break;
			}
		}
	}
}

//调整输出
function ob_out() {
	global $_SGLOBAL;
	$content = ob_get_contents();
	$content = $_SGLOBAL['db_htmifopen'] ? preg_replace("/\<a(\s*[^\>]+\s*)href\=([\"|\']?)(\?[^\"\'>\s]+\s?)[\"|\']?/ies", "Htm_cv('\\3','<a\\1href=\"')", $content) : $content;
	obclean();
	echo $content;
}

function Htm_cv($url, $tag){
	echo urlRewrite($url);
	return stripslashes($tag) . urlRewrite($url) . '"';
}

function urlRewrite($url) {
	global $db_htmifopen;
	if (!$db_htmifopen) return $url;
	$tmppos = strpos($url, '#');
	$add = $tmppos !== false ? substr($url, $tmppos) : '';
	$turl = str_replace(array('?mod=', '=', '&amp;', '&', $add), array('mod-','-', '-', '-', ''), $url);
	$turl = preg_replace('/mod-([^-]+)(-|)/','/$1/',$turl);
	$turl = preg_replace('/extra-([^-]+)(-|)/','/$1/',$turl);
	$turl = str_replace(array('//'),array('/'),$turl);
	$turl = preg_replace('/^\/index\/([^\/]+)$/','/$1',$turl);
	$turl != $url && $turl .= $db_ext; 
	return $turl . $add;
}

//产生form防伪码
function formhash() {
	global $_SGLOBAL, $_shopONFIG;

	if (empty($_SGLOBAL['formhash'])) {
		$hashadd = defined('IN_ADMINCP') ? 'Only For UCenter Home AdminCP' : '';
		$_SGLOBAL['formhash'] = substr(md5(substr($_SGLOBAL['timestamp'], 0, -7) . '|' . $_SGLOBAL['supe_uid'] . '|' . md5($_shopONFIG['sitekey']) . '|' . $hashadd), 8, 8);
	}
	return $_SGLOBAL['formhash'];
}

//检查邮箱是否有效
function isemail($email) {
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

//转向
function jump($str_url, $bool_redirect = false) {
    $str_redirect_url = '';
    if ($bool_redirect) {
        include template('template/index/redirect_url');
    } else {
        header("Location: " . $str_url);
    }
    exit;
}

/* * 获取分站车系车款表
 * param
 * int iareaId 分站id
 * int iavg  几个分站一个表
 * */


function postcurl($data,$int_timeout){
	$ch = curl_init();
	// 设置curl允许执行的最长秒数
	curl_setopt($ch, CURLOPT_TIMEOUT, $int_timeout);
	// 获取的信息以文件流的形式返回，而不是直接输出。
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	// 从证书中检查SSL加密算法是否存在
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
	if($data['type'] == "post") {
		//发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
		foreach($data['fileds'] as $key => $value){
			$fields_string.=$key.'='.$value.'&';
		}

		$fields_string = rtrim($fields_string , '&');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $data['url']);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	}else{
		curl_setopt($ch, CURLOPT_URL, $data['url']);
	}
	$res = curl_exec($ch);
	return  $res;
}


/**
 * 生成分页HTML的函数
 * $numofpage 总页数
 * $page 当前页
 * $url 页面链接
  × $total_num 总记录数
 * $max 最大显示页数
 */
        
function numofpage($page, $numofpage, $url='', $bookmark='', $total_num='', $max='', $ajaxurl='') {
	global $rewriteHandler,$_SGLOBAL;
	if (!empty($max)) {
		$max = (int) $max;
		$numofpage > $max && $numofpage = $max;
	}
	$total = $numofpage;
	if ($numofpage <= 1 || !is_numeric($page)) {
		return '';
	} else {
		$prev = $page - 1 > 0 ? $page - 1 : 1;
		$next = $page + 1 < $total ? $page + 1 : $total;
		$prev_text = $_SGLOBAL['language']['prev'];
		$next_text = $_SGLOBAL['language']['next'];
		$page_text = $_SGLOBAL['language']['pages'].':';
		if ($page > 5)
			$pages .= " <a href=\"".$url."&page=1\">1</a><a>...</a>";
		$pages .= '';
		for ($i = $page - 3; $i <= $page - 1; $i++) {
			if ($i < 1)
				continue;
			$pages .= " <a href=\"".$url.'&page='.$i."\">$i</a>";
		}
		$pages .= " <a class=\"on\">$page</a>";
		if ($page < $numofpage) {
			$flag = 0;
			for ($i = $page + 1; $i <= $numofpage; $i++) {
				$pages .= " <a href=\"".$url.'&page='.$i."\">$i</a>";
				$flag++;
				if ($flag == 4)
					break;
			}
		}
		if ($i < $total) {
			$pages .= "<a>...</a><a href=\"".$url.'&page='.$total."\">{$total}</a> ";
		}
		//else $pages .= " <a class=\"na\">{$next_text}</a>";
		return $pages;
	}
}

function numofpage_b($page, $numofpage, $url='', $bookmark='', $total_num='', $max='', $ajaxurl='') {
	global $rewriteHandler,$_SGLOBAL;
	if (!empty($max)) {
		$max = (int) $max;
		$numofpage > $max && $numofpage = $max;
	}
	$total = $numofpage;
	if ($numofpage <= 1 || !is_numeric($page)) {
		return '';
	} else {
		$prev = $page - 1 > 0 ? $page - 1 : 1;
		$next = $page + 1 < $total ? $page + 1 : $total;
		$prev_text = $_SGLOBAL['language']['prev'];
		$next_text = $_SGLOBAL['language']['next'];
		
		if ($page > 5)
			$pages .= " <a href=\"".$url."1\">1</a> ... ";
		for ($i = $page - 3; $i <= $page - 1; $i++) {
			if ($i < 1)
				continue;
			$pages .= " <a href=\"".$url.$i."\">$i</a>";
		}
		$pages .= " <a class=\"on\">$page</b>";
		if ($page < $numofpage) {
			$flag = 0;
			for ($i = $page + 1; $i <= $numofpage; $i++) {
				$pages .= " <a href=\"".$url.$i."\">$i</a>";
				$flag++;
				if ($flag == 4)
					break;
			}
		}
		//else $pages .= " <a class=\"na\">{$next_text}</a>";
		return $pages;
	}
}



function csubstrs($str, $start=0, $length, $charset="utf-8", $suffix=true) {
	if(empty($str)) return false;
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']	  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']	  = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	/*if(function_exists("mb_substr")){
		$str=mb_substr($str, $start, $length, $charset);
		if(count($match[0])>$length) return $str.'...';
		else return $str;
		}*/
	//$slice = join("",array_slice($match[0], $start, $length));
	$tooLong = false;
	$i = 0;
	$j = 0;
	$temp = '';
	$str  = $match[0] ? $match[0] : $match;
	$strCount = count($str);
	do{
		if($strCount<=$j) break;
		if(preg_match("/[\x80-\xff]/",$str[$j])) $i+=2;
		else $i++;
		if($i>$length){
			$tooLong = true;
			break;
		}
		$temp .= $str[$j++];
	}while($i<=$length);
	if($suffix&&$tooLong) return $temp.'...';
	return $temp;
}

/*
  $arr_data = array(
  'c'=>array('do'=>'lte','val'=>123),//单条件
  'd'=>array(//多条件
  array('do'=>'lte','val'=>2),
  array('do'=>'gte','val'=>20)
  )
  );
  do只能是以下操作
  inc 自增或自减
  in
  like
  gt  大于
  lt  小于
  gte 大于等于
  lte 小于等于
 */

function make_sql($arr, $type = 'insert', $tableName = '') {//传入数组，组合sql语句
    $fieldsArr = array_keys($arr);
    $return = '';
    if ($type == 'insert') {
        $fields = $values = '';
        foreach ($fieldsArr as $v) {
            $fields .= "`$v`,";
        }
        $fields = rtrim($fields, ',');
        foreach ($arr as $v) {
            $values .= $v===NULL ? "NULL," : "'$v',";
        }
        $values = rtrim($values, ',');
        $return = "($fields) VALUE ($values)";
    } elseif ($type == 'update') {
        foreach ($fieldsArr as $v) {
            if (is_array($arr[$v])) {
                $return .= make_special_sql($v, $arr[$v]) . ',';
            } else {
                $return .= "`$v`='{$arr[$v]}',";
            }
        }
        $return = rtrim($return, ',');
    } elseif ($type == 'where') {
        foreach ($fieldsArr as $v) {
            if (is_array($arr[$v])) {
                $arr_temp = isset($arr[$v]['do']) ? array($arr[$v]) : $arr[$v]; //判断字段是单条件还是多条件
                $str_temp = '';
                
                foreach ($arr_temp as $v2) {
                    $str_temp .= make_special_sql($v, $v2) . ' AND ';
                }
            } else {
                $str_temp = "`$v`='{$arr[$v]}' AND ";
            }
            if ($tableName) {
                $str_temp = "`$tableName`." . $str_temp;
            }
            $return .= $str_temp;
        }
        $return = rtrim($return, 'AND ');
    }
    return $return;
}

function make_special_sql($str_key, $arr_val) {
    if ($arr_val['do'] == 'inc') {
        $arr_val['val'] = $arr_val['val'] >= 0 ? '+' . $arr_val['val'] : $arr_val['val'];
        $str_return = "`$str_key`=`$str_key`{$arr_val['val']}";
        return $str_return;
    }
    if ($arr_val['do'] == 'in') {
        $str_return = "`$str_key` IN({$arr_val['val']})";
        return $str_return;
    }
    if ($arr_val['do'] == 'not_in') {
        $str_return = "`$str_key` NOT IN({$arr_val['val']})";
        return $str_return;
    }
    if ($arr_val['do'] == 'like') {
        $str_return = "`$str_key` LIKE '{$arr_val['val']}'";
        return $str_return;
    }
    if ($arr_val['do'] == 'gt') {
        $str_return = "`$str_key` > {$arr_val['val']}";
        return $str_return;
    }
    if ($arr_val['do'] == 'lt') {
        $str_return = "`$str_key` < {$arr_val['val']}";
        return $str_return;
    }
    if ($arr_val['do'] == 'gte') {
        $str_return = "`$str_key` >= {$arr_val['val']}";
        return $str_return;
    }
    if ($arr_val['do'] == 'lte') {
        $str_return = "`$str_key` <= {$arr_val['val']}";
        return $str_return;
    }
    if ($arr_val['do'] == 'ne') {
        $str_return = "`$str_key` != '{$arr_val['val']}'";
        return $str_return;
    }
}

function _queryString($queryString){
	global $db_htmifopen;
	if(empty($db_htmifopen)) return $queryString;
	$return = '';
	$self_array = false !== strpos($queryString, '&') ? array() : explode('-',$queryString);

	for ($i=0, $s_count=count($self_array); $i<$s_count-1;$i++) {
		$_key	= $self_array[$i];
		$_value	= rawurlencode($self_array[++$i]);
		$return .= "&$_key=$_value";
	}
	if($i<$s_count) $return .= "&extra={$self_array[$i]}";
	$return = ltrim($return,'&');
	return $return;
}

function cookie($ck_Var,$ck_Value,$ck_Time='',$db_ckpath='/'){
	 global $db_cookiepre,$db_cookiedomain;
	 !$ck_Time && $ck_Time=time()+3600*24;
	 //setcookie($db_cookiepre.'_'.$ck_Var,$ck_Value,$ck_Time,$db_ckpath,$db_cookiedomain);
	 setcookie($db_cookiepre.'_'.$ck_Var,$ck_Value,$ck_Time,$db_ckpath);
	 unset($db_cookiepre,$db_cookiedomain);
}

function shift_cookie($ck_Var,$db_ckpath='/'){
	global $db_cookiepre,$db_cookiedomain;
	$ck_Time=time()-1;
	//setcookie($db_cookiepre.'_'.$ck_Var,'',$ck_Time,$db_ckpath,$db_cookiedomain);
	setcookie($db_cookiepre.'_'.$ck_Var,'',$ck_Time,$db_ckpath);
	unset($db_cookiepre,$db_cookiedomain);
}

function get_cookie($Var){
	global $db_cookiepre;
	$return = $_COOKIE[$db_cookiepre.'_'.$Var];
	unset($db_cookiepre);
	return $return;
}

function _api($str_type,$str_url,$arr_data=array(),$bool_cache=true){
	global $_SGLOBAL;
	$str_cache_name = S_ROOT.'cache/data/'.md5($str_url.var_export($arr_data,true)).'.php';
	if($bool_cache && file_exists($str_cache_name) && ($_SGLOBAL['timestamp'] - filemtime($str_cache_name))<3600){
		include($str_cache_name);
		return $arr_return;
	}
	$str_client_str = rand(1000000,9999999);//未加密之前的字符串
	$str_server_str = base64_encode(hash_hmac("SHA1",$str_client_str,SAFE_TOKEN,true));
	$str_server_str = urlencode($str_server_str);
	$arr_post = array(
					'type'=>$str_type,
					'url'=>$str_url
				);
	if($str_type=='post'){
		$arr_data['client_str'] = $str_client_str;
		$arr_data['server_str'] = $str_server_str;
		$arr_post['fileds'] = $arr_data;
	}
	else{
		$arr_post['url'] .= '?'.make_get_data($arr_data);
		$arr_post['url'] .= "&client_str={$str_client_str}&server_str={$str_server_str}";
	}
	echo $arr_post['url'].'<br>';
	$arr_return = postcurl($arr_post,60);
	$arr_return = preg_replace('/([\d]{10})000/isU',"$1",$arr_return);
	$arr_return = json_decode($arr_return,true);
	if(!in_array($arr_return['status'],array(200,201))){
		echo '数据拉取失败，请稍后重试';
		exit;
	}
	if($arr_return){
		$bool_cache && file_put_contents($str_cache_name,"<?php\r\n\$arr_return=".var_export($arr_return,true)."\r\n?>");
	}
	return $arr_return;
}

function make_get_data($arr_data){
	if(empty($arr_data)){
		return false;
	}
	$str_return = '';
	foreach($arr_data as $k=>$v){
		$str_return .= "&$k=".urlencode($v);
		
	}
	return ltrim($str_return,'&');
}

function format_array_val_to_key($arr,$str_key){
	$arr_return = array();
	foreach($arr as $k=>$v){
		$arr_return[$v[$str_key]] = $v;
	}
	return $arr_return;
}

function _alert2back($str_msg){
	echo '<script>alert("'.$str_msg.'");window.history.go(-1)</script>';
	exit;
}

function callback($arr_data, $str_callback = '', $str_domain = '',$bool_baidu_tongji=false) {
	if($bool_baidu_tongji){
		echo_baidu_tongji();
	}
    if (is_array($str_callback)) {//兼容老的参数顺序,防止修改遗漏
        $temp = $arr_data;
        $arr_data = $str_callback;
        $str_callback = $temp;
    }
    !$str_callback && $str_callback = !empty($_GET['callback']) ? str_addslashes($_GET['callback']) : str_addslashes($_POST['callback']);
    !$str_domain && $str_domain = !empty($_GET['domain']) ? str_addslashes($_GET['domain']) : str_addslashes($_POST['domain']);
    if ($str_callback == 'div') {
        if ($str_domain == 'hugelem.com') {
            echo '<script type="text/javascript">document.domain = "hugelem.com";</script>';
        }
        echo '<div>' . json_encode($arr_data) . '</div>';
    } elseif (strstr($str_callback,'parent.')) {
        $str_echo = '';
        if ($str_domain == 'hugelem.com') {
             $str_echo = 'document.domain = "hugelem.com";';
        }
        echo '<script type="text/javascript">'.$str_echo.'try{'.$str_callback . '(' . json_encode($arr_data) . ')'.'}catch(e){}</script>';
    } elseif ($str_callback) {
        header('Content-type: text/javascript');
        echo $str_callback . '(' . json_encode($arr_data) . ')';
    } else {
        echo json_encode($arr_data);
    }
    exit;
}

function save_image($pic,$str_size='',$str_diy_file_path=''){
        global $_SGLOBAL;
        list($int_width, $int_height,$ext) = getimagesize($pic);

        $arr_size = explode('|',$str_size);
        $str_file_path = '/upload_pic/'.date("ymd").'/';
        
        if(!file_exists(S_ROOT.$str_file_path)){
                mkdir(S_ROOT.$str_file_path,0775,true);
        }
        $str_file_path .= $_SGLOBAL['timestamp'].rand(1000,9999);
        $str_file_path = $str_diy_file_path ? $str_diy_file_path : $str_file_path;
        foreach($arr_size as $v){
                $bool_source = false;
                list($int_new_width,$int_new_height) = explode(',',$v);
                if(empty($int_new_width)&&$int_new_height){
                        $int_new_width = intval($int_width*($int_new_height/$int_height));
                }
                elseif(empty($int_new_height)&&$int_new_width){
                        $int_new_height = intval($int_height*($int_new_width/$int_width));
                }
                elseif(empty($int_new_width)&&empty($int_new_height)){
                        $bool_source = true;
                        $int_new_width  = $int_width;
                        $int_new_height = $int_height;
                }
                $str_save_file_path = $str_file_path;
                if(!$bool_source){
                        $str_save_file_path .= '_'.$int_new_width;
                }
                $obj_new_image = imagecreatetruecolor($int_new_width, $int_new_height);
                switch($ext){
                                case 1: {
                                        $obj_image = imagecreatefromgif($pic);
                                        break;
                                }
                                case 2: {
                                        $obj_image = imagecreatefromjpeg($pic);
                                        break;
                                }
                                case 3: {
                                        $obj_image = imagecreatefrompng($pic);
                                        $bg =imagecolorallocate($obj_new_image, 255, 255, 255);
                                        imagefill($obj_new_image, 0, 0, $bg);
                                        break;
                                } 
                        }//根據圖片類創建新圖片
                imagecopyresampled($obj_new_image, $obj_image, 0, 0, 0, 0, $int_new_width, $int_new_height, $int_width, $int_height);
                switch($ext){
                        case 1: {
                                $str_save_file_path .= '.gif';
                                imagegif($obj_new_image,S_ROOT.$str_save_file_path, 100);
                                break;
                        }
                        case 2: {
                                $str_save_file_path .= '.jpg';
                                imagejpeg($obj_new_image,S_ROOT.$str_save_file_path, 100);
                                break;
                        }
                        case 3: {
                                $str_save_file_path .= '.png';
                                imagepng($obj_new_image,S_ROOT.$str_save_file_path, 9);
                                break;
                        }
                }
                if(!isset($str_return_path)){
                        $str_return_path = $str_save_file_path; 
                }
        }
        return $str_return_path;
}

function save_crop_image($pic,$int_new_width,$int_new_height){
	global $_SGLOBAL;
	list($int_width, $int_height,$ext) = getimagesize($pic);
	$arr_pic = explode('/',$pic);
	$str_name = substr($arr_pic[count($arr_pic)-1], 0,strrpos($arr_pic[count($arr_pic)-1], '.'));
	
	$str_file_path = '/upload_pic/'.date("ymd").'/';	
	
	if(!file_exists($_SERVER['DOCUMENT_ROOT'].$str_file_path)){
		mkdir($_SERVER['DOCUMENT_ROOT'].$str_file_path,0775);
	}
	$str_file_path .= $str_name;
	$obj_new_image = imagecreatetruecolor($int_new_width, $int_new_height);
	switch($ext){
		case 1: {$obj_image = imagecreatefromgif($pic); break;}
		case 2: {$obj_image = imagecreatefromjpeg($pic); break;}
		case 3: {$obj_image = imagecreatefrompng($pic); break;}	
	}//根據圖片類創建新圖片
	if($int_height>$int_width){
		$int_temp_height = $int_height/$int_width*$int_new_width;
		if($int_temp_height>$int_new_height){
			$int_temp_width = $int_new_width;
		}
		else{
			$int_temp_height = $int_new_height;
			$int_temp_width  = $int_width/$int_height*$int_temp_height;
		}
		imagecopyresampled($obj_new_image, $obj_image, 0, 0, 0, ($int_temp_height-$int_new_height)/2, $int_temp_width, $int_temp_height, $int_width, $int_height);
	}
	else{
		$int_temp_width = $int_width/$int_height*$int_new_height;	
		if($int_temp_width>$int_new_width){
			$int_temp_height = $int_new_height;
		}
		else{
			$int_temp_width = $int_new_height;
			$int_temp_height = $int_height/$int_width*$int_temp_width;
		}
		imagecopyresampled($obj_new_image, $obj_image, 0, 0, ($int_temp_width-$int_new_width)/2, 0, $int_temp_width, $int_temp_height, $int_width, $int_height);
	}
	$str_save_file_path = $str_file_path;
	$str_save_file_path .= '_crop_'.$int_new_width.'_'.$int_new_height;
	switch($ext){
		case 1: {
			$str_save_file_path .= '.gif';
			imagegif($obj_new_image,$_SERVER['DOCUMENT_ROOT'].$str_save_file_path, 100);
			break;
		}
		case 2: {
			$str_save_file_path .= '.jpg';
			imagejpeg($obj_new_image,$_SERVER['DOCUMENT_ROOT'].$str_save_file_path, 100);
			break;
		}
		case 3: {
			$str_save_file_path .= '.png';
			imagepng($obj_new_image,$_SERVER['DOCUMENT_ROOT'].$str_save_file_path, 9);
			break;
		}
	}
	return $str_save_file_path;
}

function save_file($obj_file,$str_dir='',$arr_allow=array('.jpg','.gif','.png','.bmp','.xls','.xlsx','.doc','.docx','.pdf','.zip','.rar')){
	$str_ext = strtolower( strrchr( $obj_file['name'] , '.' ) );
	if(!in_array($str_ext,$arr_allow)){
		return array('status'=>190,'data'=>implode(',',$arr_allow));
	}
	$str_file_path = '/upload_file/'.$str_dir.'/'.date("ymd").'/';
	if(!file_exists(S_ROOT.$str_file_path)){
		mkdir(S_ROOT.$str_file_path,0775,true);
	}
	
	$str_file = time() . rand( 1 , 10000 ) . $str_ext;
	move_uploaded_file( $obj_file['tmp_name'] , S_ROOT.$str_file_path.$str_file );
	return $str_file_path.$str_file;
	
}

function echo_price($float_price){
	$float_price = $float_price==intval($float_price) ? intval($float_price) : $float_price;
	return $float_price;
}

function echo_price_2($float_min,$float_max){
	$float_min = $float_min==intval($float_min)	 ? intval($float_min) : $float_min;
	$float_max = $float_min==intval($float_max)	 ? intval($float_max) : $float_max;
	if($float_min==$float_max){
		return $float_min;
	}
	return $float_min.'-'.$float_max;
}

function resize_image($str_url,$int_width){
	if(strstr($str_url,'_')){
		$str_url = preg_replace("/^(.*)_(.*?)\.(jpg|gif|png|bmp)/i","$1_".$int_width.".$3",$str_url);
	}
	else{
		$str_url = preg_replace("/^(.*?)\.(jpg|gif|png|bmp)/i","$1_".$int_width.".$2",$str_url);	
	}
	return $str_url;

}

/**
 * 写文件
 *
 * @param string $fileName 文件绝对路径
 * @param string $data 数据
 * @param string $method 读写模式
 * @param bool $ifLock 是否锁文件
 * @param bool $ifCheckPath 是否检查文件名中的".."
 * @param bool $ifChmod 是否将文件属性改为可读写
 * @return bool 是否写入成功   :注意rb+创建新文件均返回的false,请用wb+
 */
function write_over($data, $fileName, $method = 'rb+', $ifLock = true, $ifCheckPath = true, $ifChmod = true) {
    $filePath = dirname($fileName);
    !file_exists($filePath) && @mkdir($filePath, 0755);
    touch($fileName);
    $handle = fopen($fileName, $method);
	if(!strstr($_SERVER['REMOTE_ADDR'],'192.168') && !strstr($_SERVER['REMOTE_ADDR'],'127.0')){//本地nfs环境不使用
	    $ifLock && flock($handle, LOCK_EX);
	}
	//echo $fileName;exit;
    $writeCheck = fwrite($handle, $data);
    $method == 'rb+' && ftruncate($handle, strlen($data));
    fclose($handle);
    $ifChmod && @chmod($fileName, 0755);
    return $writeCheck;
}

function read_over($fileName, $method = 'rb') {
    $data = '';
    if ($handle = @fopen($fileName, $method)) {
		if(!strstr($_SERVER['REMOTE_ADDR'],'192.168') && !strstr($_SERVER['REMOTE_ADDR'],'127.0')){//本地nfs环境不使用
        	flock($handle, LOCK_SH);
		}
        $data = @fread($handle, filesize($fileName));
        fclose($handle);
    }
    return $data;
}


//时间格式化
function sgmdate($dateformat, $timestamp = 0, $format = 0) {

    $timeoffset = 8;
    $result = "";
    if ($timestamp == 0) {
        return "--";
    }
    if ($format) {
        $time = time() - $timestamp;
        if ($time > 48 * 3600) {
            $result = gmdate($dateformat, $timestamp + $timeoffset * 3600);
        } elseif($time > 24*3600){
            $result = '昨天 '.date("H:i",$timestamp);	
        } elseif ($time > 3600) {
            $result = intval($time / 3600) . "小时前";
        } elseif ($time > 60) {
            $result = intval($time / 60) . "分钟前";
        } elseif ($time > 0) {
            $result = $time . "秒钟前";
        } else if($time < 0){
            $time = -$time;
            if($time > 24*3600){
                $result = intval($time / (3600*24)) . "天";
                $result .= intval(($time % (3600*24))/3600) . "小时";
            }else if($time > 3600){
                $result = intval($time / 3600) . "小时";
                $result .= intval(($time % 3600)/60) . "分钟";
            }else if($time > 60){
                $result = intval($time / 60) . "分钟";
            }else if($time <= 60){
                $result = "1分钟";
            }
        } else {
            $result = "刚刚";
        }
    } else {
        $result = gmdate($dateformat, $timestamp + $timeoffset * 3600);
    }
    return $result;
}

function is_ajax() {
    $str_callback = !empty($_GET['callback']) ? str_addslashes($_GET['callback']) : str_addslashes($_POST['callback']);
    if ($str_callback || $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        return true;
    }
    return false;
}

function verify_browser($str_user_agent = '') {
    $str_user_agent = $str_user_agent ? $str_user_agent : $_SERVER['HTTP_USER_AGENT'];
    $str_browser = '其他';
    if (strstr($str_user_agent, 'MSIE 6.0')) {
        $str_browser = 'IE6';
    } elseif (strstr($str_user_agent, 'MSIE 7.0')) {
        $str_browser = 'IE7';
    } elseif (strstr($str_user_agent, 'MSIE 8.0')) {
        $str_browser = 'IE8';
    } elseif (strstr($str_user_agent, 'MSIE 9.0')) {
        $str_browser = 'IE9';
    } elseif (strstr($str_user_agent, 'MSIE 10.0')) {
        $str_browser = 'IE10';
    } elseif (strstr($str_user_agent, 'MicroMessenger')) {
		$str_browser = 'weixin';
	}elseif (strstr($str_user_agent, 'Firefox')) {
        $str_browser = 'Firefox';
    } elseif (strstr($str_user_agent, 'Chrome')) {
        $str_browser = 'Google';
    } elseif (strstr($str_user_agent, '360')) {
        $str_browser = '360安全浏览器';
    }
    return $str_browser;
}

function post_curl($data, $int_time_out = 10) {
    $ch = curl_init();
    // 设置curl允许执行的最长秒数
    curl_setopt($ch, CURLOPT_TIMEOUT, $int_time_out);
    // 获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('CLIENT-IP:'.$_SERVER['REMOTE_ADDR']));
    if ($data['type'] == "post") {
        //发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
        foreach ($data['fields'] as $key => $value) {
            $fields_string.=$key . '=' . urlencode($value) . '&';
        }
        $fields_string = rtrim($fields_string, '&');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $data['url']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    } else {
		if($data['fields']){
			$data['url'] .= strstr($data['url'],'?') ? '&'. http_build_query($data['fields']) : '?'. http_build_query($data['fields']);
		}
        curl_setopt($ch, CURLOPT_URL, $data['url']);
    }
    $res = curl_exec($ch);
    return $res;
}

//可上传二进制
function post_curl_b($data, $int_time_out = 10) {
    $ch = curl_init();
    // 设置curl允许执行的最长秒数
    curl_setopt($ch, CURLOPT_TIMEOUT, $int_time_out);
    // 获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $str_ip = $_SERVER['HTTP_CLIENT_IP'] ? $_SERVER['HTTP_CLIENT_IP'] : $_SERVER['REMOTE_ADDR'];
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('CLIENT-IP:'.$str_ip));
    if ($data['type'] == "post") {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $data['url']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data['fields']);
    } else {
        curl_setopt($ch, CURLOPT_URL, $data['url']);
    }
    $res = curl_exec($ch);
    return $res;
}

function get_config($str_names,$bool_array=false){
	global $_SGLOBAL;
	$db = $_SGLOBAL['db'];
	
	if(strstr($str_names,',')){
		$str_sql = "SELECT * FROM `admin_config` WHERE `name` IN($str_names)";
		return $db->select($str_sql,'name');
	}
	$str_sql = "SELECT * FROM `admin_config` WHERE `name`='$str_names'";
	$arr_return = $db->get_one($str_sql);
	if($bool_array){
		strstr($arr_return['val'],'array') && eval('$arr_return[val] ='."$arr_return[val]".';');	
	}
	return $arr_return;
}

function set_config($arr_data){
	global $_SGLOBAL;
	$db = $_SGLOBAL['db'];
	$str_sql = "REPLACE INTO `admin_config` ".make_sql($arr_data,'insert');
	return $db->query($str_sql);	
}

function pic_size_des($str_size){
	global $_SGLOBAL;
	$arr_language = $_SGLOBAL['language'];
	if(strstr($str_size,'|')){
		$str_size = explode('|',$str_size);	
		$str_size = $str_size[0];
	}
	$arr_temp = explode(',',$str_size);
	$str_size = '';
	if(!$arr_temp[0]){
		$str_size = $arr_language['height'];
	}
	else{
		if(!$arr_temp[1]){
			$str_size = $arr_language['width'].' '.$arr_temp[0].'px';
		}
		else{
			$str_size = $arr_temp[0].'px';	
		}
		
	}

	if($arr_temp[1]){
		if($arr_temp[0]){
			$str_size .= '*'.$arr_temp[1].'px';
		}
		else{
			$str_size .= ' '.$arr_temp[1].'px';
		}
		
	}
	else{
		$str_size .= ' '.$arr_language['height_auto'];	
	}
	
	if(!$arr_temp[0]){
		$str_size .= ' '.$arr_language['width_auto'];	
	}
	
	return $str_size;
}

function sys_sort_array($ArrayData, $KeyName1, $SortOrder1 = "SORT_ASC", $SortType1 = "SORT_REGULAR") {
    if (!is_array($ArrayData)) {
        return $ArrayData;
    }

    // Get args number.
    $ArgCount = func_num_args();

    // Get keys to sort by and put them to SortRule array.
    for ($I = 1; $I < $ArgCount; $I ++) {
        $Arg = func_get_arg($I);
        if (!preg_match("/SORT/", $Arg)) {
            $KeyNameList[] = $Arg;
            $SortRule[] = '$' . $Arg;
        } else {
            $SortRule[] = $Arg;
        }
    }

    // Get the values according to the keys and put them to array.
    foreach ($ArrayData AS $Key => $Info) {
        foreach ($KeyNameList AS $KeyName) {
            ${$KeyName}[$Key] = $Info[$KeyName];
        }
    }

    // Create the eval string and eval it.
    $EvalString = 'array_multisort(' . join(",", $SortRule) . ',$ArrayData);';
    eval($EvalString);
    return $ArrayData;
}

function striptags($str) {//过滤HTML
    $str = trim(strip_tags($str));
    $str = str_replace('&nbsp;', '', $str);
    //$str=str_replace(' ','',$str);
    $str = preg_replace('/(\t|\r\n|\n)/', '', $str);
    return trim($str);
}

function _query_string($query_string) {
    global $db_htmifopen;
    if (empty($db_htmifopen))
        return $query_string;
    $return = '';
    $self_array = false !== strpos($query_string, '&') ? array() : explode('-', $query_string);

    for ($i = 0, $sum_count = count($self_array); $i < $sum_count - 1; $i++) {
        $_key = $self_array[$i];
        $_value = rawurlencode($self_array[++$i]);
        $return .= "&$_key=$_value";
    }
    if ($i < $sum_count)
        $return .= "&extra={$self_array[$i]}";
    $return = ltrim($return, '&');
    return $return;
}

function get_languages($bool_all=false){
	global $_SGLOBAL;
	static $arr_return;
	if($arr_return){
		if(!$bool_all){
			foreach($arr_return as $k=>$v){
				if($v['default']==1){
					unset($arr_return[$k]);
					break;
				}
			}
		}
		return array_values($arr_return);	
	}
	$db = $_SGLOBAL['db'];

	$str_sql = "SELECT * FROM `index_language` ORDER BY `sort` ASC ";
	$arr_return = $db->select($str_sql);
	$arr_return = $arr_return ? $arr_return : array();
	if(!$bool_all){
		foreach($arr_return as $k=>$v){
			if($v['default']==1){
				unset($arr_return[$k]);
				break;
			}
		}
	}
	return array_values($arr_return);
}

function identity_code_valid($vStr){
    $vCity = array(
        '11','12','13','14','15','21','22',
        '23','31','32','33','34','35','36',
        '37','41','42','43','44','45','46',
        '50','51','52','53','54','61','62',
        '63','64','65','71','81','82','91'
    );
 
    if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;
 
    if (!in_array(substr($vStr, 0, 2), $vCity)) return false;
 
    $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
    $vLength = strlen($vStr);
 
    if ($vLength == 18)
    {
        $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
    } else {
        $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
    }
 
    if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
    if ($vLength == 18)
    {
        $vSum = 0;
 
        for ($i = 17 ; $i >= 0 ; $i--)
        {
            $vSubStr = substr($vStr, 17 - $i, 1);
            $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
        }
 
        if($vSum % 11 != 1) return false;
    }
 
    return true;
}

/*对数组进行urlencode编码*/
function array_urlencode($arr_data){
    $arr_new_data = array();
    foreach($arr_data as $key => $val){
        // 这里对键也进行了urlencode
        $arr_new_data[urlencode($key)] = is_array($val) ? array_urlencode($val) : urlencode($val);
    }
    return $arr_new_data;
}

//保存视频文件
function save_video($obj_file,$str_dir,$arr_allow=array('.mp4','.mov','.avi','.rmvb','.mkv')){
	$str_ext = strtolower( strrchr( $obj_file['name'] , '.' ) );
	if(!in_array($str_ext,$arr_allow)){
		return array('status'=>190,'data'=>implode(',',$arr_allow));
	}
	$str_file_path = '/upload_file/'.$str_dir.'/'.date("ymd").'/';
	if(!file_exists(S_ROOT.$str_file_path)){
		mkdir(S_ROOT.$str_file_path,0775,true);
	}
	
	//$str_file = time() . rand( 1 , 10000 ) . $str_ext;
	$str_file = $obj_file['name'];
	move_uploaded_file( $obj_file['tmp_name'] , S_ROOT.$str_file_path.$str_file );
	return $str_file_path.$str_file;
	
}

//根据用户输入地址获取该地址的经度和纬度
function get_lgt_lat($str_dizhi){
        $str_dizhi = urlencode($str_dizhi);
        $url = "http://api.map.baidu.com/geocoder/v2/?address=".$str_dizhi."&output=json&ak=yF49BkY8N1jt1MtePymQW902";
        $address_data = file_get_contents($url);
        $json_data = json_decode($address_data);
        $arr_return = array();
        $arr_return['lng'] = $json_data->result->location->lng;//经度
        $arr_return['lat'] = $json_data->result->location->lat;//纬度
        return $arr_return;
}
//xml转数组
function std_class_object_to_array($stdclassobject){
    $_array = is_object($stdclassobject) ? get_object_vars($stdclassobject) : $stdclassobject;
    foreach ($_array as $key => $value) {
	$value = (is_array($value) || is_object($value)) ? std_class_object_to_array($value) : $value;
	$array[$key] = $value;
    }
    return $array;
}


/**
 * 计算两点地理坐标之间的距离
 * @param  Decimal $longitude1 起点经度
 * @param  Decimal $latitude1  起点纬度
 * @param  Decimal $longitude2 终点经度 
 * @param  Decimal $latitude2  终点纬度
 * @param  Int     $unit       单位 1:米 2:公里
 * @param  Int     $decimal    精度 保留小数位数
 * @return Decimal
 */
function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit=2, $decimal=2){

    $EARTH_RADIUS = 6370.996; // 地球半径系数
    $PI = 3.1415926;

    $radLat1 = $latitude1 * $PI / 180.0;
    $radLat2 = $latitude2 * $PI / 180.0;

    $radLng1 = $longitude1 * $PI / 180.0;
    $radLng2 = $longitude2 * $PI /180.0;

    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;

    $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
    $distance = $distance * $EARTH_RADIUS * 1000;

    if($unit==2){
        $distance = $distance / 1000;
    }

    return round($distance, $decimal);

}


?>