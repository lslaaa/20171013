<?php
!defined('LEM') && exit('Forbidden');
class carCache {
	/**
	 * 获取缓存路径函数
	 * @param string 	$filePath	文件名称
	 * @param boolean 	$isPack 	是否可选压缩文件
	 */
	function getPath($filePath, $isPack = false, $withCache = true) {
		if (! $withCache || !$isPack ) {
			return $filePath;
		}
		/**
		if( $GLOBALS['db_filecache_to_memcache'] && Perf::checkMemcache() && in_array ( SCR, array ('index', 'read', 'thread' ))){
			$_cacheService = perf::gatherCache('pw_filecache');
			return $_cacheService->getFileCache($filePath);
		}
		**/
		if ( $GLOBALS['db_cachefile_compress'] && in_array ( SCR, array ('index', 'read', 'thread' ) )) {
			return pwPack::cachePath ( $filePath );
		}
		return $filePath;
	}

	function readover($fileName, $method = 'rb'){
		return readover($fileName, $method);
	}

	function writeover($fileName, $data, $method = 'rb+', $ifLock = true, $ifCheckPath = true, $ifChmod = true){
		return writeover($fileName, $data, $method, $ifLock, $ifCheckPath, $ifChmod);
	}

	function getData($fileName,$areaId=0,$isRegister = true, $isReadOver = false){
		dbconnect();
		$_service = L::loadClass('cache');
		return $_service->getData($fileName,$areaId,$isRegister,$isReadOver);
	}

	/**
	 * 写缓存通用函数
	 * @param string 		$filePath	文件名称
	 * @param string|array 	$data		数据
	 * @param string 		$method		读写模式
	 * @param boolean		$ifLock		是否锁文件
	 */
	function setData($area_id,$filePath, $data, $method = 'rb+', $ifLock = true) {
		$_service = L::loadClass('cacheStatic');
		return $_service->setData($area_id,$filePath, $data, $method, $ifLock);
	}
	/**
	 * 删除文件缓存函数
	 * @param string 		$filePath	文件名称
	 */
	function deleteData($filePath) {
		$_service = L::loadClass('cachedistribute','utility');
		return $_service->deleteData($filePath);
	}
}

class LEM_BaseLoader {

	static function _loadClass($className, $dir = '', $isGetInstance = true, $classPrefix = 'LEM_') {
		static $classes = array();
		$dir = LEM_BaseLoader::_formatDir($dir);
		$classToken = $isGetInstance ? $dir .$className : $dir . $className; //避免重名
		if (isset($classes[$classToken])) return $classes[$classToken];//避免重复初始化
		
		$classes[$classToken] = true; //默认值
		$fileDir = S_ROOT . $dir . strtolower($className) . '.class.php';

		if (!$isGetInstance) return (require_once S::escapePath($fileDir)); //未实例化的直接返回

		$class = $classPrefix . $className;
		if (!class_exists($class)) {
			if (file_exists($fileDir)) require_once S::escapePath($fileDir);
			if (!class_exists($class)) { //再次验证是否存在class
				$GLOBALS['className'] = $class;
				echo 'load_class_error';
				exit;
			}
		}
		$classes[$classToken] = new $class(); //实例化
		return $classes[$classToken];
	}

	function _loadBaseDB() {
		if (!class_exists('BaseDB')) require_once (S_ROOT . 'lib/base/basedb.php');
	}

	static function _formatDir($dir) {
		$dir = trim($dir);
		if ($dir) $dir = trim($dir, "\\/") . '/';
		return $dir;
	}
}

/**
 * 加载类(包括通用类和通用配置文件的加载)
 */
class L extends LEM_BaseLoader {

	/**
	 * 类文件的加载入口
	 *
	 * @param string $className 类的名称
	 * @param string $dir 目录：末尾不需要'/'
	 * @param boolean $isGetInstance 是否实例化
	 * @return mixed
	 */
	static function loadClass($className, $dir = '', $isGetInstance = true) {
		return parent::_loadClass($className, 'cls/' . parent::_formatDir($dir), $isGetInstance);
	}

	/**
	 * dao文件加载入口
	 *
	 * @param string $dbName 数据库名称
	 * @param string $dir 目录
	 * @return mixed
	 */
	function loadDB($dbName, $dir = '') {
		parent::_loadBaseDB();
		return L::loadClass($dbName . 'DB', parent::_formatDir($dir) . 'db');
	}

	function config($var = null, $file = 'config', $dir = 'bbscache', $isStatic = true) {
		static $conf = array();
		$key = $dir . '_' . $file;
		if (!isset($conf[$key])) {
			if (file_exists(D_P . "data/$dir/{$file}.php")) {
				//* include S::escapePath(D_P . "data/$dir/{$file}.php");
				//* $arr = get_defined_vars();
				//* unset($arr['dir'], $arr['file'], $arr['var'], $arr['key'], $arr['conf'], $arr['isStatic']);
				$arr = pwCache::getData(S::escapePath(D_P . "data/$dir/{$file}.php"), false);
				if ($isStatic !== true) {return $var ? $arr[$var] : $arr;}
				$conf[$key] = $arr;
			} else {
				$conf[$key] = array();
			}
		}
		return $var ? $conf[$key][$var] : $conf[$key];
	}

	function reg($var = null) {
		return L::config($var, 'dbreg');
	}

	function style($var = null, $skinco = null, $ispath = false) {
		global $skin, $db_styledb, $db_defaultstyle;
		$skinco && isset($db_styledb[$skinco]) && $skin = $skinco;
		if ($skin && strpos($skin, '..') === false && file_exists(D_P . "data/style/$skin.php") && is_array($db_styledb[$skin]) && $db_styledb[$skin][1] == '1') {

		} elseif ($db_defaultstyle && strpos($db_defaultstyle, '..') === false && file_exists(D_P . "data/style/$db_defaultstyle.php")) {
			$skin = $db_defaultstyle;
		} else {
			$skin = 'wind';
		}
		return !$ispath ? L::config($var, $skin, 'style') : S::escapePath(D_P . 'data/style/' . $skin . '.php');
	}

	function forum($fid) {
		return L::config('foruminfo', 'fid_' . intval($fid), 'forums', false);
	}
}

class S {
	/**
	 * 整型数过滤
	 * @param $param
	 * @return int
	 */
	function int($param) {
		return intval($param);
	}
	/**
	 * 字符过滤
	 * @param $param
	 * @return string
	 */
	function str($param) {
		return trim($param);
	}
	/**
	 * 是否对象
	 * @param $param
	 * @return boolean
	 */
	function isObj($param) {
		return is_object($param) ? true : false;
	}
	/**
	 * 是否数组
	 * @param $params
	 * @return boolean
	 */
	function isArray($params) {
		return (!is_array($params) || !count($params)) ? false : true;
	}
	/**
	 * 变量是否在数组中存在
	 * @param $param
	 * @param $params
	 * @return boolean
	 */
	function inArray($param, $params) {
		return (!in_array((string)$param, (array)$params)) ? false : true;
	}
	/**
	 * 是否是布尔型
	 * @param $param
	 * @return boolean
	 */
	function isBool($param) {
		return is_bool($param) ? true : false;
	}
	/**
	 * 是否是数字型
	 * @param $param
	 * @return boolean
	 */
	function isNum($param) {
		return is_numeric($param) ? true : false;
	}
	
	/**
	 * 是否是有值 主要包含  0 '0' 为真
	 * Enter description here ...
	 * @param unknown_type $param
	 */
	function isNatualValue($param){
		switch (gettype($param)) {
			case 'string':
			case 'integer':
			case 'double':
				return strlen($param) > 0;
			break;
			case 'array':
				return S::isArray($param);
				break;
			default:
				return $param ? true : false;
			break;
		}
	}
	/**
	 * 加载类/函数文件
	 * @param $file
	 */
	function import($file) {
		if (!is_file($file)) return false;
		require_once $file;
	}
	/**
	 * html转换输出
	 * @param $param
	 * @return string
	 */
	function htmlEscape($param) {
		return trim(htmlspecialchars($param, ENT_QUOTES));
	}
	/**
	 * 过滤标签
	 * @param $param
	 * @return string
	 */
	function stripTags($param) {
		return trim(strip_tags($param));
	}
	/**
	 * 初始化$_GET/$_POST全局变量
	 * @param $keys
	 * @param $method
	 * @param $cvtype
	 */
	function gp($keys, $method = null, $cvtype = 1,$istrim = true) {
		!is_array($keys) && $keys = array($keys);
		foreach ($keys as $key) {
			if ($key == 'GLOBALS') continue;
			$GLOBALS[$key] = NULL;
			if ($method != 'P' && isset($_GET[$key])) {
				$GLOBALS[$key] = $_GET[$key];
			} elseif ($method != 'G' && isset($_POST[$key])) {
				$GLOBALS[$key] = $_POST[$key];
			}
			if (isset($GLOBALS[$key]) && !empty($cvtype) || $cvtype == 2) {
				$GLOBALS[$key] = S::escapeChar($GLOBALS[$key], $cvtype == 2, $istrim);
			}
		}
	}

	/**
	 * 指定key获取$_GET/$_POST变量
	 * @param $key
	 * @param $method
	 */
	function getGP($key, $method = null) {
		if ($method == 'G' || $method != 'P' && isset($_GET[$key])) {return $_GET[$key];}
		return $_POST[$key];
	}
	/**
	 * 全局变量过滤
	 */
	function filter() {
		$allowed = array('GLOBALS' => 1,'_GET' => 1,'_POST' => 1,'_COOKIE' => 1,'_FILES' => 1,'_SERVER' => 1,
						'P_S_T' => 1);
		foreach ($GLOBALS as $key => $value) {
			if (!isset($allowed[$key])) {
				$GLOBALS[$key] = null;
				unset($GLOBALS[$key]);
			}
		}
		if (!get_magic_quotes_gpc()) {
			S::slashes($_POST);
			S::slashes($_GET);
			S::slashes($_COOKIE);
		}
		S::slashes($_FILES);
		$GLOBALS['pwServer'] = S::getServer(array('HTTP_REFERER','HTTP_HOST','HTTP_X_FORWARDED_FOR','HTTP_USER_AGENT',
													'HTTP_CLIENT_IP','HTTP_SCHEME','HTTPS','PHP_SELF',
													'REQUEST_URI','REQUEST_METHOD','REMOTE_ADDR','SCRIPT_NAME',
													'QUERY_STRING'));
		!$GLOBALS['pwServer']['PHP_SELF'] && $GLOBALS['pwServer']['PHP_SELF'] = S::getServer('SCRIPT_NAME');
	}

	/**
	 * 路径转换
	 * @param $fileName
	 * @param $ifCheck
	 * @return string
	 */
	static function escapePath($fileName, $ifCheck = true) {
		if (!S::_escapePath($fileName, $ifCheck)) {
			exit('Path Forbidden');
		}
		return $fileName;
	}
	/**
	 * 私用路径转换
	 * @param $fileName
	 * @param $ifCheck
	 * @return boolean
	 */
	static function _escapePath($fileName, $ifCheck = true) {
		$tmpname = strtolower($fileName);
		$tmparray = array('://',"\0");
		$ifCheck && $tmparray[] = '..';
		if (str_replace($tmparray, '', $tmpname) != $tmpname) {
			return false;
		}
		return true;
	}
	/**
	 * 目录转换
	 * @param unknown_type $dir
	 * @return string
	 */
	function escapeDir($dir) {
		$dir = str_replace(array("'",'#','=','`','$','%','&',';'), '', $dir);
		return rtrim(preg_replace('/(\/){2,}|(\\\){1,}/', '/', $dir), '/');
	}
	/**
	 * 通用多类型转换
	 * @param $mixed
	 * @param $isint
	 * @param $istrim
	 * @return mixture
	 */
	function escapeChar($mixed, $isint = false, $istrim = false) {
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
				$mixed[$key] = S::escapeChar($value, $isint, $istrim);
			}
		} elseif ($isint) {
			$mixed = (int) $mixed;
		} elseif (!is_numeric($mixed) && ($istrim ? $mixed = trim($mixed) : $mixed) && $mixed) {
			$mixed = S::escapeStr($mixed);
		}
		return $mixed;
	}
	/**
	 * 字符转换
	 * @param $string
	 * @return string
	 */
	function escapeStr($string) {
		$string = str_replace(array("\0","%00","\r"), '', $string); //modified@2010-7-5
		$string = preg_replace(array('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','/&(?!(#[0-9]+|[a-z]+);)/is'), array('', '&amp;'), $string);
		$string = str_replace(array("%3C",'<'), '&lt;', $string);
		$string = str_replace(array("%3E",'>'), '&gt;', $string);
		$string = str_replace(array('"',"'","\t",'  '), array('&quot;','&#39;','    ','&nbsp;&nbsp;'), $string);
		return $string;
	}
	/**
	 * 变量检查
	 * @param $var
	 */
	function checkVar(&$var) {
		if (is_array($var)) {
			foreach ($var as $key => $value) {
				S::checkVar($var[$key]);
			}
		} elseif (P_W != 'admincp') {
			$var = str_replace(array('..',')','<','='), array('&#46;&#46;','&#41;','&#60;','&#61;'), $var);
		} elseif (str_replace(array('<iframe','<meta','<script'), '', $var) != $var) {
			global $basename;
			$basename = 'javascript:history.go(-1);';
			adminmsg('word_error');
		}
	}

	/**
	 * 变量转义
	 * @param $array
	 */
	function slashes(&$array) {
		if (is_array($array)) {
			foreach ($array as $key => $value) {
				if (is_array($value)) {
					S::slashes($array[$key]);
				} else {
					$array[$key] = addslashes($value);
				}
			}
		}
	}

	/**
	 * 获取服务器变量
	 * @param $keys
	 * @return string
	 */
	function getServer($keys) {
		$server = array();
		$array = (array) $keys;
		foreach ($array as $key) {
			$server[$key] = NULL;
			if (isset($_SERVER[$key])) {
				$server[$key] = str_replace(array('<','>','"',"'",'%3C','%3E','%22','%27','%3c','%3e'), '', $_SERVER[$key]);
			}
		}
		return is_array($keys) ? $server : $server[$keys];
	}

	/**
	 * 通用多类型混合转义函数
	 * @param $var
	 * @param $strip
	 * @param $isArray
	 * @return mixture
	 */
	function sqlEscape($var, $strip = true, $isArray = false) {
		if (is_array($var)) {
			if (!$isArray) return " '' ";
			foreach ($var as $key => $value) {
				$var[$key] = trim(S::sqlEscape($value, $strip));
			}
			return $var;
		} elseif (is_numeric($var)) {
			return " '" . $var . "' ";
		} else {
			return " '" . addslashes($strip ? stripslashes($var) : $var) . "' ";
		}
	}
	/**
	 * 通过","字符连接数组转换的字符
	 * @param $array
	 * @param $strip
	 * @return string
	 */
	function sqlImplode($array, $strip = true) {
		return implode(',', S::sqlEscape($array, $strip, true));
	}
	/**
	 * 组装单条 key=value 形式的SQL查询语句值 insert/update
	 * @param $array
	 * @param $strip
	 * @return string
	 */
	function sqlSingle($array, $strip = true) {
		if (!S::isArray($array)) return ''; // modified@2010-7-2
		$array = S::sqlEscape($array, $strip, true);
		$str = '';
		foreach ($array as $key => $val) {
			$str .= ($str ? ', ' : ' ') . S::sqlMetadata($key) . '=' . $val;
		}
		return $str;
	}
	/**
	 * 组装多条 key=value 形式的SQL查询语句 insert
	 * @param $array
	 * @param $strip
	 * @return string
	 */
	function sqlMulti($array, $strip = true) {
		if (!S::isArray($array)) return ''; // modified@2010-7-2
		$str = '';
		foreach ($array as $val) {
			if (!empty($val) && S::isArray($val)) { //modified@2010-7-2
				$str .= ($str ? ', ' : ' ') . '(' . S::sqlImplode($val, $strip) . ') ';
			}
		}
		return $str;

	}
	/**
	 * 组装SQL查询的限制条件
	 * @param $start
	 * @param $num
	 * @return string
	 */
	function sqlLimit($start, $num = false) {
		return ' LIMIT ' . ($start <= 0 ? 0 : (int) $start) . ($num ? ',' . abs($num) : '');
	}
	/**
	 * 过滤SQL元数据，数据库对象(如表名字，字段等)
	 * @param $data 元数据
	 * @param $tlists 白名单
	 * @return string 经过转义的元数据字符串
	 */
	function sqlMetadata($data ,$tlists=array()) {
		if (empty($tlists) || !S::inArray($data , $tlists)) {
			$data = str_replace(array('`', ' '), '',$data);
		}
		return ' `'.$data.'` ';
	}

}
?>