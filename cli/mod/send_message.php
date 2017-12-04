#!/usr/local/webserver/php/bin/php
<?php
(php_sapi_name() !== 'cli') && exit('Forbidden');
include dirname(__FILE__).DIRECTORY_SEPARATOR.'../common.php';
define('D_BUG',1);
defined('D_BUG') ? error_reporting(E_ALL ^ E_NOTICE) : error_reporting(0);      
class server
{
	private $_SGLOBAL;
	/*
	根据需要可自行初始化一些数据库连接方法
	*/
	function __construct() {
		global $_SGLOBAL;
		$this->_SGLOBAL = $_SGLOBAL;
		$this->db = $_SGLOBAL['db'];
		if($this->get_server_ip()=='192.168.0.251'){
			$this->domain = 'http://20171013.demo.hugelem.cn';
		}
		else{
			$this->domain = 'http://www.dongtaimao.com';
		}
	}
		
	function start(){
		$this->_log('Server '.$this->_SGLOBAL['server_name'].' start.');
		$_SERVER['REMOTE_ADDR'] = '192.168.0.190';
		$this->str_client = time();//未加密之前的字符串
		$this->str_safe_token = SAFE_TOKEN;//未加密之前的字符串
		$this->str_server = base64_encode(hash_hmac("SHA1",$this->str_client,$this->str_safe_token,true));//计算加密后的字符串
		// while(true){
			$this->_do();
			usleep(100000);//100毫秒触发一次任务
		// }
	}

	/*
	 * 执行具体任务
	 * 执行异常请调用_log方法写日志
	 * 执行成功无需写入日志
	 * 每次可执行n条任务，不要超过内存最大值即可，请根据任务优先级从数据库或者队列出取出任务
	 */

	private function _do()
	{
		$arr_data = array();                                    
                $arr_data["type"] = 'post';
                $arr_data["url"] = $this->domain."/admin.php?mod=message&mod_dir=cli&extra=get_order";
                $arr_data["fields"]['str_client'] = $this->str_client;
                $arr_data["fields"]['str_server'] = $this->str_server;
                do{
                        $arr_return = post_curl($arr_data,30);
                        // var_export($arr_return);exit;
                        //file_put_contents(S_ROOT.'data/insert_order.txt', var_export($arr_result,true).PHP_EOL,FILE_APPEND);
                        $bool_ok = false;
                        if($arr_return && strstr($arr_return,'status')){
                                $arr_return = json_decode($arr_return,true);
                                if($arr_return['status']==200){
                                                $bool_ok = true;
                                }
                        }
                        if(!$bool_ok){
                                file_put_contents(S_ROOT.'data/mall_order_log.txt', 'ERROR:'.var_export($arr_return,true).PHP_EOL,FILE_APPEND);
                                chmod(S_ROOT.'data/mall_order_log.txt',0777);
                                sleep(10);
                        }
                }while(!$bool_ok);
                var_export($arr_return);
	}


	
	
	
	function get_server_ip(){ 
		if(file_exists('/sbin/ifconfig')){
			$ss = exec('/sbin/ifconfig eth0 | sed -n \'s/^ *.*addr:\\([0-9.]\\{7,\\}\\) .*$/\\1/p\'',$arr);      
			if(preg_match("/[\d]{2,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}/", $arr[0])){
				return $arr[0];
			}
		}
		return "192.168.0.251";
	}

	/*
	写日志
	*/
	private function _log($str_msg){
		$str_msg = date("Y-m-d H:i:s").' '.$str_msg.PHP_EOL;
		if(defined('D_BUG')){
			echo $str_msg;
		}
		else{
			$str_dir = S_ROOT.'ssi/log/cli/';
			!file_exists($str_dir) && mkdir($str_dir,0755);
			write_over($str_msg,$str_dir.$this->_SGLOBAL['server_name'].'.log','ab+');  
		}
	}
	
}
$obj = new server;
$obj->start();
?>
