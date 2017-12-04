<?php
(php_sapi_name() !== 'cli') && exit('Forbidden1');
define('S_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR."../");
define('LEM', TRUE);
define('SAFE_TOKEN', 'eAbeZPcv04');
//define('M_URL','http://m.100id.com');
include S_ROOT.'cli/source/function_common.php';
require_once(S_ROOT . "cli/cls/base.php");
$_SGLOBAL = array();
$_SGLOBAL['server_name'] = str_replace(array(S_ROOT."cli/",'\\','/','.php'),array('','_','_',''),$_SERVER['PHP_SELF']);//服务名称以当前文件名命名
$m_time = explode(' ', microtime());
$_SGLOBAL['timestamp'] = intval($m_time[1]);
$_SGLOBAL['supe_starttime'] = $_SGLOBAL['timestamp'] + $m_time[0];
/*
function shutdown()
{
    // This is our shutdown function, in 
    // here we can do any last operations
    // before the script is complete.

    echo 'Script executed with success'.PHP_EOL;
}

register_shutdown_function('shutdown');

declare(ticks = 1);
//信号处理函数
function sig_handler($str_signo){
     switch ($str_signo) {
        case SIGTERM:{
				// 处理SIGTERM信号
				echo 1;
				exit;
				break;
			}
        case SIGHUP:{
				echo 2;
				//处理SIGHUP信号
				break;
			}
        case SIGUSR1:{
				echo "Caught SIGUSR1...\n";
				break;
			}
        default:
            // 处理所有其他信号
     }
}
//安装信号处理器

pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGHUP,  "sig_handler");
pcntl_signal(SIGUSR1, "sig_handler");
*/
db_connect();
trans_db_connect();
?>