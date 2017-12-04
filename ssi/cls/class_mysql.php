<?php

/*
  [UCenter Home] (C) 2007-2008 Comsenz Inc.
  $Id: class_mysql.php 10484 2008-12-05 05:46:59Z liguode $
 */
/*
触发器实现逻辑
1.insert 直接发主键,警告不允许一条sql插入多条数据
2.update 不取老数据和新数据，匹配set字段和where字段 set值覆盖where值，然后当做条件发送
3.delete 执行之前取老数据，根据配置需要的字段发送
 */
!defined('LEM') && exit('Forbidden');
class mysql_rw {

	var $querynum = 0;
	var $link;
	var $link_ro;
	var $link_rw;
	private $dbhost;
	private $dbuser;
	private $dbpw;
	private $dbname;
	private $dbport;
	private $dbcharset;
	private $halt;
	private $db_type;
	private	$admin_trigger_table = "`LEM_admin`.`admin_trigger`";//触发器表 
	private $affected_rows;//查询影响的行数;
	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $dbport = 0,$dbcharset='utf8', $halt = TRUE,$db_type='ro',$boolConnectNow=false){
		if(!$boolConnectNow){//只赋值数据库信息暂时不连接数据库
			$this->dbhost   = $dbhost;
			$this->dbuser   = $dbuser;
			$this->dbpw     = $dbpw;
			$this->dbname   = $dbname;
			$this->dbport   = $dbport;
			$this->dbcharset  = $dbcharset;
			$this->halt     = $halt;
			$this->db_type  = $db_type;
			return true;
		}
		$this->link = mysqli_init();
		//$this->link->options(MYSQL_OPT_READ_TIMEOUT, 10);
		//$this->link->options(MYSQL_OPT_WRITE_TIMEOUT, 10);
		if(!$this->link->real_connect($dbhost, $dbuser,$dbpw)){
		//if (!mysqli_real_connect($this->link,$dbhost, $dbuser,$dbpw,$dbname,$dbport)) {
			$halt && $this->halt('Can not connect to MySQL server',$dbhost);
		}
		mysqli_set_charset($this->link, 'utf8');
		if ($dbname) {
			$this->link->select_db($dbname);
		}
		if($db_type=='ro'){
			$this->link_ro = $this->link;
		}
		else{
			$this->link_rw = $this->link;
		}
	}

	function select_db($dbname) {
		return mysqli_select_db($this->link, $dbname);
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysqli_fetch_array($query, $result_type);
		//return mysqli_fetch_array($query);
	}

	function query($sql){
		if(empty($this->link)){
			$this->connect($this->dbhost, $this->dbuser, $this->dbpw, $this->dbname, $this->dbport,$this->dbcharset,$this->halt,$this->db_type,true);
		}
		$arrRW = '/^SELECT(.*?)FROM/is';//读SQL特征
		if(preg_match($arrRW,trim($sql))){
			$db_type = 'ro';
		}
		else{
			$db_type = 'rw';
		}
		
		if(!empty($this->link_rw)){//如果写连接已经打开过，所有读写都用它
			$db_type = 'rw';
		}
		$this->change_db($db_type);
		if(defined('SHOW_SQL')){
			global $_SGLOBAL;
			$sqlstarttime = $sqlendttime = 0;
			$m_time = explode(' ', microtime());
			$sqlstarttime = number_format(($m_time[1] + $m_time[0] - $_SGLOBAL['supe_starttime']), 6) * 1000;
			echo $sql.'<br>';
		}
		//匹配出当前sql的库名，表名;
		$match_db_table_operate = $this->match_db_table_operate($sql);
		//非select 语句才做触发器操作;
		$bool_trigger_after = false;//是否sql执行之后触发触发器;
		$bool_get_pk_val = false;//是否获取主键值
		$arr_post_data = array();
		if($match_db_table_operate[3] != 'select'){
			//查询当前表的触发器
			/*
			$str_trigger_sql = "SELECT * FROM {$this->admin_trigger_table} WHERE `db_name`='{$match_db_table_operate[1]}' AND `table_name`='{$match_db_table_operate[2]}' AND `tcondition`='{$match_db_table_operate[3]}' AND `is_work`=1 ";
			$arr_trigger = $this->select($str_trigger_sql);
			//如果触发器存在
			if(!empty($arr_trigger)){
			//则判断是否需要提取主键;
				foreach($arr_trigger as $t){
					if($t['pk_field']){
						$bool_get_pk_val = true;
						$str_pk_filed = $t['pk_field'];
						break;
					}
				}
			}
			$bool_trigger_after = true;//触发器存在，则需要在sql执行成功之后出发触发器
			//如果需要提取主键;
			if($bool_get_pk_val && $match_db_table_operate[3] != 'insert'){
				$this->link->query("SET @pk_val := null;");
				$sql .= " AND ( SELECT @pk_val := CONCAT_WS(',', {$str_pk_filed}, @pk_val) )";
			}
			*/
		}

		if (!($query = $this->link->query($sql))) {
			if($this->errno()==2013){
				$this->halt('QUERY SQL TIMEOUT', $sql,false);
				$this->link_ro = $this->link_rw = '';
				$int_thread_id = $this->link->thread_id;
				$this->change_db($db_type);
				$this->link->kill($int_thread_id);
				exit;
			}
			else{
				$this->halt('MySQL Query Error', $sql);
			}
		}
		if($match_db_table_operate[3] == 'update' || $match_db_table_operate[3] == 'delete'){
			$this->affected_rows = $this->link->affected_rows;
			$query = $this->link->affected_rows>0 ? true : false;
		}

		if (defined('SHOW_SQL')) {
			$m_time = explode(' ', microtime());
			$sqlendttime = number_format(($m_time[1] + $m_time[0] - $_SGLOBAL['supe_starttime']), 6) * 1000;
			$sqltime = round(($sqlendttime - $sqlstarttime), 3);
			echo $sqltime.'<br>';
			$explain = array();
			//$info = mysql_info();
			$info = $this->link->info;
			if ($query && preg_match("/^(select )/i", $sql)) {
				$explain = mysqli_fetch_assoc($this->link->query("EXPLAIN ".$sql));
			}
			$arr_debug_query = array('sql' => $sql, 'time' => $sqltime, 'info' => $info, 'explain' => $explain);
		}
		$this->querynum++;
		//继续执行触发器
		//如果需要提取主键;
		if($bool_get_pk_val && $match_db_table_operate[3] != 'insert'){
			$result = $this->link->query("SELECT @pk_val");
			$str_pk_val = current($this->fetch_array($result));
			$arr_post_data['pkids'] = $str_pk_val;
		}
		elseif($bool_get_pk_val && $match_db_table_operate[3] == 'insert'){//insert操作取得主键;
			$int_insert_id = $this->insert_id();
			$arr_post_data = array();
			$arr_post_data['pkids'] = $int_insert_id;
		}

		
		//执行触发器
		if($bool_trigger_after && $query){
			$this->do_triggers($arr_trigger, $arr_post_data,$sql,$match_db_table_operate[3]);
		}
		return $query;
	}

	/**
	 * 执行触发器
	 * */
	private function do_triggers($arr_triggers,$arr_post_data,$str_sql,$str_sql_type){
		if(empty($arr_triggers)){
			return false;
		}
		foreach($arr_triggers as $arr_trigger){
			//万事俱备，开始发送请求
			//update操作匹配出最新的where条件;
			if(empty($arr_post_data)){
				if(empty($arr_trigger['fields'])){
					echo "触发器：【{$arr_trigger['trigger_name']}】，必须设置主键和发送字段其中一个，请重新配置";
					exit;	
				}
				if($str_sql_type == 'update'){
					$arr_post_data = $this->match_update_new_data($str_sql,$arr_trigger['fields']);
				}
				elseif($str_sql_type == 'delete'){
					$arr_post_data = $this->match_delete_new_data($str_sql,$arr_trigger['fields']);
				}
				elseif($str_sql_type == 'insert'){
					$arr_post_data = $this->match_insert_new_data($str_sql,$arr_trigger['fields']);	
				}
			}
			return $this->post_trigger($arr_trigger, $arr_post_data,$str_sql);
		}
	}
	
	//从update sql中匹配出需要的字段值
	function match_update_new_data($str_sql,$str_fileds){
		//$str_sql = strtolower($str_sql);
		$str_sql = str_replace(array('UPDATE ',' WHERE ',' SET ',' IN',' AND'),array('update ',' where ',' set ',' in',' and'),$str_sql);
		if(!strstr($str_sql, 'update')){
			return false;
		}
		$arr_new_data = array();
		$arr_fileds = explode(',',$str_fileds);
		$arr_sql = explode('where', $str_sql);
		$arr_update = explode('set', trim($arr_sql[0]));
	
		preg_match_all('/`([\w]+)`[\s]{0,100}=[\s]{0,100}([^\s()]+)/i',trim($arr_sql[1]),$arr_out_where);//查找等于的
		preg_match_all('/`([\w]+)`[\s]{0,100}=[\s]{0,100}([^,`]+)/i',trim($arr_update[1]),$arr_out_set);//查找等于的，不包含累加和累减
		foreach($arr_fileds as $v){
			$int_temp_key = array_search($v,$arr_out_where[1]);
			is_numeric($int_temp_key) && $arr_new_data['eq'][$v] = trim($arr_out_where[2][$int_temp_key],'\'');
			$int_temp_key = array_search($v,$arr_out_set[1]);
			is_numeric($int_temp_key) && $arr_new_data['eq'][$v] = trim($arr_out_set[2][$int_temp_key],'\'');
		}
		
		//strstr判断 in如果存在则执行
		if(strstr(trim($arr_sql[1]), 'in')){
			preg_match_all('/`([\w]+)`[\s]{0,100}in[\s]{0,100}\(([^)]+)\)/i',trim($arr_sql[1]),$arr_out_where_in);
		}
		$arr_out_where_in[1] = $arr_out_where_in[1] ? $arr_out_where_in[1] : array();
		foreach($arr_out_where_in[1] as $k=>$v){
			$int_temp_key = array_search($v,$arr_fileds);
			if(!isset($arr_new_data['eq'][$v]) && is_numeric($int_temp_key)){//如果存在说明set里面已经有重新设置值
				$arr_new_data['in'][$v] = $arr_out_where_in[2][$k];
			}
		}
		$arr_has_fileds = array();
		!empty($arr_new_data['eq']) && $arr_has_fileds = array_merge($arr_has_fileds,array_keys($arr_new_data['eq']));
		!empty($arr_new_data['in']) && $arr_has_fileds = array_merge($arr_has_fileds,array_keys($arr_new_data['in']));
		foreach($arr_fileds as $v){
			$int_temp_key = array_search($v,$arr_has_fileds);
			if(!is_numeric($int_temp_key)){
				echo "sql:{$str_sql}无法匹配出字段{$v}，请重新配置";
				exit;
			}
		}
		return $arr_new_data;
	}
	//从delete sql中匹配出需要的字段值
	function match_delete_new_data($str_sql,$str_fileds){
		//$str_sql = strtolower($str_sql);
		$str_sql = str_replace(array('DELETE ',' WHERE ',' IN',' AND'),array('delete ',' where ',' in',' and'),$str_sql);
		if(!strstr($str_sql, 'delete')){
			return false;
		}
		$arr_new_data = array();
		$arr_fileds = explode(',',$str_fileds);
		$arr_sql = explode('where', $str_sql);
	
		preg_match_all('/`([\w]+)`[\s]{0,100}=[\s]{0,100}([^\s()]+)/i',trim($arr_sql[1]),$arr_out_where);//查找等于的
		foreach($arr_fileds as $v){
			$int_temp_key = array_search($v,$arr_out_where[1]);
			is_numeric($int_temp_key) && $arr_new_data['eq'][$v] = trim($arr_out_where[2][$int_temp_key],'\'');
		}
		
		//strstr判断 in如果存在则执行
		if(strstr(trim($arr_sql[1]), 'in')){
			preg_match_all('/`([\w]+)`[\s]{0,100}in[\s]{0,100}\(([^)]+)\)/i',trim($arr_sql[1]),$arr_out_where_in);
		}
		$arr_out_where_in[1] = $arr_out_where_in[1] ? $arr_out_where_in[1] : array();
		foreach($arr_out_where_in[1] as $k=>$v){
			$int_temp_key = array_search($v,$arr_fileds);
			if(!isset($arr_new_data['eq'][$v]) && is_numeric($int_temp_key)){//如果存在说明set里面已经有重新设置值
				$arr_new_data['in'][$v] = $arr_out_where_in[2][$k];
			}
		}
		$arr_has_fileds = array();
		!empty($arr_new_data['eq']) && $arr_has_fileds = array_merge($arr_has_fileds,array_keys($arr_new_data['eq']));
		!empty($arr_new_data['in']) && $arr_has_fileds = array_merge($arr_has_fileds,array_keys($arr_new_data['in']));
		foreach($arr_fileds as $v){
			$int_temp_key = array_search($v,$arr_has_fileds);
			if(!is_numeric($int_temp_key)){
				echo "sql:{$str_sql}无法匹配出字段{$v}，请重新配置";
				exit;
			}
		}
		return $arr_new_data;
	}
	//从insert sql中匹配出需要的字段值
	function match_insert_new_data($str_sql,$str_fileds){
		$str_sql = strtolower($str_sql);
		if(!strstr($str_sql, 'insert')){
			return false;
		}

		$arr_new_data = array();
		$arr_fileds = explode(',',$str_fileds);
		$str_sql = preg_replace('/^insert into[^(]+/i','', $str_sql);
		$arr_sql = explode('value',$str_sql);
		
		$arr_out_fileds = explode(',',str_replace(array('(',')'),'',trim($arr_sql[0])));//匹配sql中的字段
		
		$arr_sql[1] = str_replace(array('(',')',"\'"),array('','','|我是单引号|'),trim($arr_sql[1]));//匹配sql中的字段的值
		for($i=0;$i<count($arr_out_fileds);$i++){
			$arr_sql[1] = trim($arr_sql[1]);
			$str_temp = substr($arr_sql[1],0,1);
			if($str_temp=='\''){
				$arr_sql[1] = mb_substr($arr_sql[1],1,mb_strlen($arr_sql[1]));
				$int_index = strpos($arr_sql[1],'\'');
				$arr_out_values[$i] = str_replace('|我是单引号|','\'',mb_substr($arr_sql[1],0,$int_index));
				$arr_sql[1] = mb_substr($arr_sql[1],$int_index,mb_strlen($arr_sql[1]));
				
			}
			elseif(is_numeric($str_temp)){
				$int_index = strpos($arr_sql[1],',');
				$arr_out_values[$i] = trim(mb_substr($arr_sql[1],0,$int_index));
				$arr_sql[1] = mb_substr($arr_sql[1],$int_index,mb_strlen($arr_sql[1]));
			}
			$int_index = strpos($arr_sql[1],',');
			$arr_sql[1] = mb_substr($arr_sql[1],$int_index+1,mb_strlen($arr_sql[1]));
		}
		
		foreach($arr_fileds as $k=>$v){
			$int_temp_key = array_search('`'.$v.'`',$arr_out_fileds);
			is_numeric($int_temp_key) && $arr_new_data['eq'][$v] = trim($arr_out_values[$int_temp_key],'\'');
		}
	
		$arr_has_fileds = array();
		!empty($arr_new_data['eq']) && $arr_has_fileds = array_merge($arr_has_fileds,array_keys($arr_new_data['eq']));
		foreach($arr_fileds as $v){
			$int_temp_key = array_search($v,$arr_has_fileds);
			if(!is_numeric($int_temp_key)){
				echo "sql:{$str_sql}无法匹配出字段{$v}，请重新配置";
				exit;
			}
		}
		return $arr_new_data;
	}
	
	/**
	 * 发送触发器请求
	 * */
	private function post_trigger($arr_trigger,$arr_fields,$str_sql = ''){
		$str_url = $arr_trigger['url'];
		if(!$str_url){
			return false;
		}
		$arr_fields['str_server'] = 'uTyNfgYa6JFA1XyDc1CjaxtDU+Q=';
		$arr_fields['str_client'] = 'aBbexcvC28';
		$mix_return = post_curl_b(array('type'=>'post','url'=>$str_url,'fields'=>json_encode($arr_fields)));
		if($str_url=='http://triggers.xxxxxx?mod=mall&extra=clear_weixin_card_redis'){
			//echo $str_url;
			//var_export($arr_fields);
			//var_export($mix_return);exit;
		}
		//如果请求未返回正确返回值1，则请求出错，写入出错记录，等待下一次请求;
		$obj_mongo = mongo_init();
		$obj_mongo->select_db('LEM_admin');
		$int_today = intval(strtotime(date('Y-m-d 00:00',time())));
		if($mix_return != 1){
			$obj_mongo->insert('admin_trigger_failed', array('failed_time'=>1,'successed'=>0,'trigger_id'=>intval($arr_trigger['id']),'url'=>$str_url,'data'=>json_encode($arr_fields),'failed_info'=>$mix_return,'sql'=>$str_sql,'from_url'=>$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],'in_date'=>time()));
			//统计
			$obj_mongo->update('admin_trigger_count', array('count_date'=>$int_today,'trigger_id'=>intval($arr_trigger['id'])), array('$set'=>array('count_date'=>$int_today,'trigger_id'=>intval($arr_trigger['id'])),'$inc'=>array('failed_count'=>1,'success_count'=>0)),array('upsert'=>1));
		}else{
			//统计
			$obj_mongo->update('admin_trigger_count', array('count_date'=>$int_today,'trigger_id'=>intval($arr_trigger['id'])), array('$set'=>array('count_date'=>$int_today,'trigger_id'=>intval($arr_trigger['id'])),'$inc'=>array('failed_count'=>0,'success_count'=>1)),array('upsert'=>1));
		}
		return true;
	}
	//获取数组
	function select($sql, $keyfield = '') {

		$arr_data = array();
		$result = $this->query($sql);
		//$r = $this->fetch_array($result);
		while ($r = $this->fetch_array($result)) {
			if ($keyfield) {
				$key = $r["$keyfield"];
				$arr_data[$key] = $r;
			} else {
				$arr_data[] = $r;
			}
		}
		$this->free_result($result);
		return $arr_data;
	}

	//获取一维数组
	function get_one($sql, $keyfield = '') {
		$array = array();
		$result = $this->query($sql);
		$array = $this->fetch_array($result);
		$this->free_result($result);
		return $array;
	}

	//获取结果
	function get_value($sql, $colName = '', $type = '') {
		$query = $this->query($sql, $type);
		$result = $this->fetch_array($query, MYSQL_BOTH);
		return $result[$colName] ? $result[$colName] : $result[0];
	}

	/**
	 * 方法：执行Sql命令，返回最后插入ID号
	 * @sql -- Sql语句
	 */
	function get_max_id($sql) {
		$this->query($sql);
		return $this->insert_id();
	}

	/**
	 * 方法：执行Sql命令，没有记录返回
	 * @sql -- Sql语句
	 */
	function update($sql){
		$this->query($sql);
		if ($this->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	function affected_rows() {
		return $this->affected_rows;
	}

	function error() {
		return $this->link->error;
	}

	function errno() {
		return $this->link->errno;
	}

	function num_rows($query) {
		$query = mysqli_num_rows($query);
		return $query;
	}

	function num_fields($query) {
		return mysqli_num_fields($query);
	}

	function free_result($query) {
		return mysqli_free_result($query);
	}

	function insert_id() {
		$this->change_db('rw');
		return $this->link->insert_id;
		//$arr_return =  $this->fetch_array($this->query("SELECT last_insert_id() AS `insert_id`"));
		//return intval($arr_return['insert_id']);
	}

	function fetch_row($query) {
		$query = mysqli_fetch_row($query);
		return $query;
	}

	function fetch_fields($query) {
		return mysqli_fetch_field($query);
	}

	function close() {
		return mysqli_close($this->link);
	}
	
	function change_db($db_type='ro'){
		if($db_type=='ro'){//读数据库
			if(empty($this->link_ro)){
				$this->connect($this->dbhost, $this->dbuser, $this->dbpw, $this->dbname, $this->dbport,$this->dbcharset,$this->halt,$this->db_type,true);	
			}
			$this->link = $this->link_ro;
		}
		else{
			include(S_ROOT . 'ssi/config/mysql.conf.php');
			if(empty($this->link_rw)){
				if(empty($_dbhost)){
					include(S_ROOT . 'ssi/config/mysql.conf.php');
				}
				$this->connect($_dbhost['master']['dbhost'], $_dbhost['master']['dbuser'], $_dbhost['master']['dbpw'], $_dbhost['master']['dbname'], $_dbhost['master']['dbport'], $_dbhost['master']['dbcharset'],true,'rw',true);
			}
			$this->link = $this->link_rw;
		}
	}

	function halt($message = '', $sql = '',$bool_stop=true) {
		$dberror = $this->error();
		$dberrno = $this->errno();
		$strLog  = 'Date:'.date("Y-m-d H:i:s")."\r\n";
		$strLog  .= 'REFERER:'.$_SERVER['HTTP_REFERER']."\r\n";
		$strLog .= 'URL:'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."\r\n";
		$strLog .= 'IP:'.($_SERVER['HTTP_CLIENT_IP'] ? $_SERVER['HTTP_CLIENT_IP'] : $_SERVER['REMOTE_ADDR'])."\r\n";
		$strLog .= 'HTTP_USER_AGENT:'.$_SERVER['HTTP_USER_AGENT']."\r\n";
		$strLog .= 'Message:'.$message."\r\n";
		$strLog .= 'SQL:'.$sql."\r\n";
		$strLog .= 'MYSQL Error:'.$dberror."\r\n";
		$strLog .= 'MYSQL ErrorNo:'.$dberrno."\r\n";
		$strLog .= "\r\n\r\n";
		file_put_contents(S_ROOT.'ssi/log/mysql_error.txt',$strLog,FILE_APPEND);
		header('HTTP/1.1 500 Internal Server Error');
		echo '服务器累的回火星了，请按F5刷新或稍后再试。';
		$bool_stop && exit();
	}
	//根据sql匹配出数据库、表、操作方式;
	public function match_db_table_operate($str_sql){
		$arr_preg = array(
				'select'=>'/^select.*from[\s]+`([a-z0-9_]+)`\.`([a-z0-9_]+)`/i',
				'update'=>'/^update[\s]+`([a-z0-9_]+)`\.`([a-z0-9_]+)`/i',
				'insert'=>'/^insert[\s]+into[\s]+`([a-z0-9_]+)`\.`([a-z0-9_]+)`/i',
				'delete'=>'/^delete[\s]+from[\s]+`([a-z0-9_]+)`\.`([a-z0-9_]+)`/i',
				'replace'=>'/^replace[\s]+into[\s]+`([a-z0-9_]+)`\.`([a-z0-9_]+)`/i',
		);
		$str_sql = trim($str_sql);
		$arr_sql = explode(' ', $str_sql);
		$str_sql_type = strtolower($arr_sql[0]);
		preg_match($arr_preg[$str_sql_type],$str_sql,$arr_db_table);
		$arr_db_table[0] = strtolower($arr_db_table[0]);
		$arr_db_table[1] = strtolower($arr_db_table[1]);
		$arr_db_table[] = $str_sql_type;
		!isset($arr_preg[$str_sql_type]) && $arr_db_table[3]='select';//匹配不成功默认为select
		return $arr_db_table;
	}
        
        /*
         * $str_tablename   表名
         * @return 总记录数
         */
        function get_all_count($str_tablename, $str_where = ''){
            if(empty($str_where))
                $sql = "select count(*) as all_count from ".$str_tablename;
            else
                $sql = "select count(*) as all_count from ".$str_tablename." ".$str_where;
            $arr_count = $this->get_one($sql);
            return $arr_count['all_count'];
        }
}

?>