<?php
(php_sapi_name() !== 'cli') && exit('Forbidden');

function db_connect() {
    global $_SGLOBAL;
    include_once(S_ROOT.'ssi/cls/class_mysql.php');
    include_once(S_ROOT.'ssi/config/mysql.conf.php');
    //随机实例化读数据库
    if (empty($_SGLOBAL['db'])) {
		$intRand = mt_rand(0, count($_dbhost['slave']) - 1); //mt_rand比rand产生随机数快些
    	$_slave = $_dbhost['slave'][$intRand];
        $_SGLOBAL['db'] = new mysql_rw;
        $_SGLOBAL['db']->connect($_slave['dbhost'], $_slave['dbuser'], $_slave['dbpw'], $_slave['dbname'], $_slave['dbport'], $_slave['dbcharset'], true, 'ro');
    }
    trans_db_connect();
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
//function redis_init() {
//    static $obj;
//    if (!isset($obj)) {
//        include S_ROOT . 'ssi/cls/redis.class.php';
//        $obj = new BAIJI_redis;
//    }
//    return $obj;
//}

//function sphinx_init($serverId = 0) {
//    static $obj;
//    if (!isset($obj[$serverId])) {
//        include S_ROOT . 'ssi/cls/sphinx.class.php';
//        include_once(S_ROOT . 'ssi/config/sphinx.conf.php');
//        if (!isset($arrSphinxHost[$serverId])) {
//            echo 'can\'t find this sphinx server';
//            exit;
//        }
//        $obj[$serverId] = new BAIJI_sphinx;
//        $obj[$serverId]->connect($arrSphinxHost[$serverId]['host'], $arrSphinxHost[$serverId]['port']);
//    }
//    return $obj[$serverId];
//}

//function mongo_init($intServer = 0) {
//    static $obj;
//    if (!isset($obj[$intServer]) && empty($obj[$intServer])) {
//        include S_ROOT . 'ssi/cls/mongodb.class.php';
//        include_once(S_ROOT . 'ssi/config/mongodb.conf.php');
//        if (!isset($arrMongoConfig[$intServer])) {
//            echo 'can\'t find this mongodb server';
//            exit;
//        }
//        $int_connect_num = 0;
//        do {
//            if ($int_connect_num++ > 5) {
//                echo 'can\'t connect this mongodb server';
//                exit;
//            }
//            $obj[$intServer] = new BAIJI_mongodb;
//        } while (!$obj[$intServer] && $int_connect_num <= 5);
//        $obj[$intServer]->connect($arrMongoConfig[$intServer]);
//    }
//    return $obj[$intServer];
//}


function post_curl($data, $int_time_out = 10) {
    $ch = curl_init();
    // 设置curl允许执行的最长秒数
    curl_setopt($ch, CURLOPT_TIMEOUT, $int_time_out);
    // 获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('CLIENT-IP:'.$_SERVER['REMOTE_ADDR'],'X-Forwarded-For:'.$_SERVER['REMOTE_ADDR'],'USER-AGENT:'.$_SERVER['REMOTE_ADDR'],'Referer:'.'http://'.$_SERVER['HTTP_HOST'].$_SERVER['HTTP_REL_REQUEST_URI']));
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
        if($data['fields'])
        {
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
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('CLIENT-IP:'.$_SERVER['REMOTE_ADDR'],'X-Forwarded-For:'.$_SERVER['REMOTE_ADDR'],'USER-AGENT:'.$_SERVER['REMOTE_ADDR'],'Referer:'.'http://'.$_SERVER['HTTP_HOST'].$_SERVER['HTTP_REL_REQUEST_URI']));
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
//    if(!strstr($_SERVER['REMOTE_ADDR'],'192.168') && !strstr($_SERVER['REMOTE_ADDR'],'127.0')){//本地nfs环境不使用
//        $ifLock && flock($handle, LOCK_EX);
//    }
    $writeCheck = fwrite($handle, $data);    
    $method == 'rb+' && ftruncate($handle, strlen($data));
    fclose($handle);    
    $ifChmod && @chmod($fileName, 0755);    
    return $writeCheck;
}

function read_over($fileName, $method = 'rb') {
    $data = '';
    if ($handle = @fopen($fileName, $method)) {
        //flock($handle, LOCK_SH);
        $data = @fread($handle, filesize($fileName));
        fclose($handle);
    }
    return $data;
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

function _echo_price($float_price) {
    $int_price = intval($float_price);
    if ($int_price == $float_price) {
        return $int_price;
    }
    return sprintf('%.2f', $float_price);
}

/*
  保留允许的html或者过滤禁止的
  $str字符串
  $str_tag保留或过滤的标签多个用|分割 比如a|input
  $bool_allow 是保留标签还是过滤标签
  $bool_filter不允许出现的标签是过滤还是进行转义,true为过滤,false为转义
 */

function striptags_b($str, $str_tag = 'input', $bool_allow = false, $bool_filter = true) {//过滤HTML
    $arr_skip_event = array(
        'onabort', 'onactivate', 'onafterprint', 'onafterupdate',
        'onbeforeactivate', 'onbeforecopy', 'onbeforecut',
        'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste',
        'onbeforeprint', 'onbeforeunload', 'onbeforeupdate',
        'onblur', 'onbounce', 'oncellchange', 'onchange',
        'onclick', 'oncontextmenu', 'oncontrolselect',
        'oncopy', 'oncut', 'ondataavailable',
        'ondatasetchanged', 'ondatasetcomplete', 'ondblclick',
        'ondeactivate', 'ondrag', 'ondragend',
        'ondragenter', 'ondragleave', 'ondragover',
        'ondragstart', 'ondrop', 'onerror', 'onerrorupdate',
        'onfilterchange', 'onfinish', 'onfocus', 'onfocusin',
        'onfocusout', 'onhelp', 'onkeydown', 'onkeypress',
        'onkeyup', 'onlayoutcomplete', 'onload',
        'onlosecapture', 'onmousedown', 'onmouseenter',
        'onmouseleave', 'onmousemove', 'onmouseout',
        'onmouseover', 'onmouseup', 'onmousewheel',
        'onmove', 'onmoveend', 'onmovestart', 'onpaste',
        'onpropertychange', 'onreadystatechange', 'onreset',
        'onresize', 'onresizeend', 'onresizestart',
        'onrowenter', 'onrowexit', 'onrowsdelete',
        'onrowsinserted', 'onscroll', 'onselect',
        'onselectionchange', 'onselectstart', 'onstart',
        'onstop', 'onsubmit', 'onunload', 'javascript',
        'script', 'eval', 'behaviour', 'expression'
    );
    $arr_must_replace = array(//强制转义的内容
        'from' => array('<input', '<textarea','<?php'),
        'to' => array('&lt;input', '&lt;textarea','&lt;?php')
    );
    $str_skip_event = implode('|', $arr_skip_event);
    unset($arr_skip_event);
    $str = preg_replace(array("/($str_skip_event)/i"), 'nm', $str); //干掉所有事件
    if (!$bool_allow) {//过滤不允许的标签
        //$str = preg_replace('/<('.$str_tag.')(.*?)(|\/)>/i', '', $str);
        if ($bool_filter) {
            $str = preg_replace('/<\/?(' . $str_tag . ')\b([^<>]*>)/sim', '', $str);
        } else {
            $str = preg_replace('/<(\/?)(' . $str_tag . ')\b([^<>]*)>/sim', "&lt;$1$2&gt;", $str);
        }
    } else {//保留允许的标签
        if ($bool_filter) {
            $str = preg_replace('/<\/?(?!(' . $str_tag . '))\b([^<>]*>)/sim', '', $str);
        } else {
            $str = preg_replace('/<(\/?)(?!(' . $str_tag . '))\b([^<>]*)>/sim', "&lt;$1$3&gt;", $str);
        }
    }
    $str = str_replace($arr_must_replace['from'], $arr_must_replace['to'], $str);
    return trim($str);
}

/*
过滤标签属性
@$str需要过滤的字符串
@$str_attr需要过滤的属性名称
*/

function filter_attr($str, $str_attr = 'width|height'){
    $str = preg_replace("/($str_attr)=('|\").*?('|\")/i", '', $str);
    return trim($str);	
}

/*
  随机图片域名
  @$str_url图片地址
  return 图片地址
 */

function set_pic_url($str_url = '',$bool_return_domain=false) {
    static $arr_current_image;
    if (!isset($arr_current_image)) {
        global $arr_image;
        $arr_current_image = $arr_image;
    }
	if($bool_return_domain){
		return $arr_current_image[array_rand($arr_current_image, 1)];
	}
    if ($str_url && strstr($str_url, 'http://img.100id.com')) {
        $str_url = str_replace('http://img.100id.com/M00', '', $str_url);
    } elseif (!strstr($str_url, 'http://')) {
        return MAT_URL . '/dist/images/common/no_pic.jpg';
    } else {
        return $str_url;
    }
    return $arr_current_image[array_rand($arr_current_image, 1)] . $str_url;
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


?>