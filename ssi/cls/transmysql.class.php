<?php
/*
 * @类名：分布式多库数据库操作类
 * @描述：
 * 本类用作多服务器之间的多库式数据库操作，需要两条配置信息：
 * 1.服务器的配置信息，形如：array('服务器IP的最后一段数字'=>array('mysql:host=127.0.0.1;dbname=i','数据库账号','数据库密码'),....);
 * 2.分布式数据库或者表于服务器之间的对应信息：array('数据库名_表名'=>'服务器IP的最后一段数字','数据库名_*'=>'服务器IP最后一段数字',....);
 * 构造函数会自动将不同的数据库_表分配分配给相应的服务器，但不会立即初始化数据库连接，当真正执行一串sql的时候，才执行数据库连接：
 * 1.执行sql之前会先根据sql分析出当前需要操作哪个数据库_表，从而得到服务器信息或数据库连接信息。
 * 2.先判断当前需要的数据库连接是否已创建，如果没有，则创建连接，连接保存到一个static的数组变量中，以确认不会重复构建数据库连接。
 * 3.执行sql。
 * 本类的主要作用是用于多服务器多库间的事务操作，所以提供比较灵活的事务操作办法：
 * 1.可以直接调用multi_query()方法，直接传递一个sql语句组成的数组参数，所有sql执行完毕之后自动判断是否全部成功，是则执行commit操作，否则回滚。
 * 2.也可以分步调用update(),insert()等方法进行单个操作，操作完成之后，自行判断是否全部执行成功，而后调用commit()方法，或者collback()方法。
 * 3.有一点需要说明：select()方法默认不需要进行事务操作，所以所有sql执行后直接返回数据，如果有特殊需求，请调用query()方法，update(),insert(),
 * delete()默认都支持事务操作，当然，得需要表相应地支持事务，否则和不进行事务没有任何区别。
 * 4.如果单条非select操作不需要事务操作，则在调用相应方法时手动设置$bool_auto_commit 为 true，$bool_auto_commit默认为false;
 * 5.方法的具体使用请自行参照方法源码。
 * */
//define('SHOW_SQL', 1);
!defined('LEM') && exit('Forbidden');
class transmysql{
	private static $db;
	private $servers;
	private $db_table_name;
	private $error_info;
	private $error_no;
	private $current_db_servers;//当前事务连接的服务器
	private $triggers = array();//触发器列表
	private $trigger_post_data = array();//触发器需要发送的数据;
	private $trigger_sql = array();
	private $trigger_sql_type = array();
	private $admin_trigger_table = "`baiji_admin`.`admin_trigger`";//触发器表
	private $affected_rows;//查询影响的行数;
	
	public function _init($arr_server,$arr_db_table_name){
		//配置信息;
		$this->servers = $arr_server;
		$this->db_table_name = $arr_db_table_name;
	}
	public function multi_query($arr_sql){
		$this->current_db_servers = array();
		//从sql中正则匹配出数据库和表:库_表
		//根据库_表从服务器配置列表得出服务器
		$arr_exec_result = array();
		$bool_flag = true;
		foreach($arr_sql as $v){
			$mix_result = $this->query($v,false);
			$arr_exec_result[] = $mix_result;
			if(defined('SHOW_TRANS_SQL')){
				echo $v.'<br>';
				var_export($mix_result);
				echo '<br>';
			}
			if($mix_result===false){
				$bool_flag = false;
			}
		}
		//判断是否全部执行成功，全部成功就执行commit操作;
		if($bool_flag){
			//echo 'sucess';
			$this->commit();
			return true;
		}else{
			//echo 'failed';
			$this->rollback();
			return false;
		}
	}
	//单条sql执行
	public function query($str_sql,$bool_auto_commit = true,$str_key_field=''){
		$this->current_db_servers[] = $int_server = $this->match_db_table($str_sql);
		if(defined('SHOW_SQL')){
			global $_SGLOBAL;
			$sqlstarttime = $sqlendttime = 0;
			$m_time = explode(' ', microtime());
			$sqlstarttime = number_format(($m_time[1] + $m_time[0] - $_SGLOBAL['supe_starttime']), 6) * 1000;
			echo $str_sql.'<br>';
		}
		//取得数据库连接执行sql;
		$db = $this->connect_db($int_server);
		$db->query("SET names utf8");
		if(!$bool_auto_commit){
			$db->query("SET AUTOCOMMIT=0");
		}
		$mix_return = false;
		//匹配出当前sql的库名，表名;
		$match_db_table_operate = $this->match_db_table_operate($str_sql);
		//非select 语句才做触发器操作;
		$bool_trigger_after = false;//是否sql执行之后触发触发器;
		$bool_get_pk_val = false;//是否获取主键值
		if($match_db_table_operate[3] != 'select'){
			/*
			//查询当前表的触发器
			$str_trigger_sql = "SELECT * FROM {$this->admin_trigger_table} WHERE `db_name`='{$match_db_table_operate[1]}' AND `table_name`='{$match_db_table_operate[2]}' AND `tcondition`='{$match_db_table_operate[3]}' AND `is_work`=1 ";
			$arr_trigger = $this->query($str_trigger_sql);
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
				$bool_trigger_after = true;//触发器存在，则需要在sql执行成功之后出发触发器
			}
			//如果需要提取主键;
			if($bool_get_pk_val && $match_db_table_operate[3] != 'insert'){
				$db->query("SET @pk_val := null;");
				$str_sql .= " AND ( SELECT @pk_val := CONCAT_WS(',', {$str_pk_filed}, @pk_val) )";
			}
			*/
		}
		//查询
		$mix_res = $db->query($str_sql);
		if(!$mix_res){
			//查询出错，写入日志信息;
			$mix_return = false;
			$this->error_no[] = $db->errno;
			$this->error_info[] = $db->error;
			$str_message = "MySQL Query Error";
			if($db->errno == '2013'){
				$str_message = 'QUERY SQL TIMEOUT';
				//手动杀死线程;
				$int_thread_id = $db->thread_id;
				//重新连接一次数据库;
				self::$db[$int_server] = '';
				$db = $this->connect_db($int_server);
				$db->kill($int_thread_id);
			}
			$this->halt($str_message,$str_sql);
			return $mix_return;
		}
		if($match_db_table_operate[3] == 'select'){
			$mix_return = $this->fetch_array($mix_res,$str_key_field);
		}elseif ($match_db_table_operate[3] == 'insert'){
			//插入操作直接返回主键id;	
			$mix_return = $db->insert_id;
		}elseif($match_db_table_operate[3] == 'update' || $match_db_table_operate[3] == 'delete' ){
			//保存影响行数
			$this->affected_rows = $db->affected_rows;
			//其他操作;
			$mix_return = $db->affected_rows>0 ? true : false;
		}
		if (defined('SHOW_SQL')) {
			$m_time = explode(' ', microtime());
			$sqlendttime = number_format(($m_time[1] + $m_time[0] - $_SGLOBAL['supe_starttime']), 6) * 1000;
			$sqltime = round(($sqlendttime - $sqlstarttime), 3);
			echo $sqltime.'<br>';
		}
		//继续执行触发器
		//如果需要提取主键;
		if($bool_get_pk_val && $match_db_table_operate[3] != 'insert'){
			$result = $db->query("SELECT @pk_val");
			$str_pk_val = current(current($this->fetch_array($result)));
			$arr_post_data = array();
			$arr_post_data['pkids'] = $str_pk_val;
		}
		elseif($bool_get_pk_val && $match_db_table_operate[3] == 'insert'){//insert操作取得主键;
			$arr_post_data = array();
			$arr_post_data['pkids'] = $db->insert_id;
		}
		
		if($bool_auto_commit){
			//执行触发器
			if($bool_trigger_after){
				$this->do_triggers($arr_trigger, $arr_post_data,$str_sql,$match_db_table_operate[3]);
			}
		}else{
			//如果不是自动事务提交，则先把触发器和事务保存起来，在提交时再发送请求;
			$this->triggers[] = $arr_trigger;
			$this->trigger_post_data[] = $arr_post_data;
			$this->trigger_sql[] = $str_sql;
			$this->trigger_sql_type[] = $match_db_table_operate[3];
		}
		return $mix_return;
	}
	//重组select返回资源;
	public function fetch_array($mix_res,$str_key_field = ''){
		if(empty($str_key_field)){
			while($arr = $mix_res->fetch_assoc()){
				$mix_return[] = $arr;
			}
		}
		else{
			while($arr = $mix_res->fetch_assoc()){
				$mix_return[$arr[$str_key_field]] = $arr;
			}
		}
		$this->free_result($mix_res);
		
		return $mix_return;
	}
	/*获取单条信息*/
	public function get_one($str_sql){
		$arr_return = $this->query($str_sql);
		if(is_array($arr_return)){
			return $arr_return[0];
		}
		return $arr_return;
	}
	
	function get_value($str_sql, $str_col_name = '') {
		$arr_return = $this->get_one($str_sql);
		if($str_col_name){
			return 	$arr_return[$str_col_name];
		}
		return current($arr_return);
	}
	
	function free_result($mix_res) {
		return mysqli_free_result($mix_res);
	}
	
	//查询;
	public function select($str_table,$arr_condition = array(),$arr_fields = array('*')){
		$str_sql = "SELECT ".implode(',', $arr_fields)." FROM " . $this->db_table($str_table) . " WHERE ".make_sql($arr_condition['where'],'where');
		if($arr_condition['order']){
			$str_sql .= " ORDER BY ".$arr_condition['order'];
		}
		if($arr_condition['group']){
			$str_sql .= " GROUP BY ".$arr_condition['group'];
		}
		if($arr_condition['limit']){
			$str_sql .= " limit ".$arr_condition['limit'];
		}
		return $this->query($str_sql);
	}
	//更新
	public function update($str_table,$arr_condition,$arr_data,$bool_auto_commit = false){
		$str_sql = "UPDATE " . $this->db_table($str_table) . " SET ".make_sql($arr_data,'update')." WHERE ".make_sql($arr_condition,'where');
		return $this->query($str_sql,$bool_auto_commit);
	}
	//删除
	public function delete($str_table,$arr_condition,$bool_auto_commit = false){
		$str_sql = "DELETE FROM " . $this->db_table($str_table) . " WHERE ".make_sql($arr_condition,'where');
		return $this->query($str_sql,$bool_auto_commit);
	}
	//插入
	public function insert($str_table,$arr_data,$bool_auto_commit = false){
		$str_sql = "INSERT INTO " . $this->db_table($str_table) . make_sql($arr_data,'insert');
		return $this->query($str_sql,$bool_auto_commit);
	}
	public function db_table($str_db_table){
		list($str_db,$str_table) = explode('.',$str_db_table);
		return '`'.trim($str_db,'`').'`.`'.trim($str_table,'`').'`';
	}
	//连接数据库并返回
	public function connect_db($int_server){
		//判断是否已经有数据库连接，有直接返回，否则创建一个连接;
		if (self::$db[$int_server]){
			if(self::$db[$int_server]->ping()){
				return self::$db[$int_server];
			}
		}
		if(defined('SHOW_SQL')){
			global $_SGLOBAL;
			$sqlstarttime = $sqlendttime = 0;
			$m_time = explode(' ', microtime());
			$sqlstarttime = number_format(($m_time[1] + $m_time[0] - $_SGLOBAL['supe_starttime']), 6) * 1000;
			echo '连接 '.$int_server.'服务器<br>';
		}
		self::$db[$int_server] = mysqli_init();
		//设置数据库超时时间，时长3秒;
		//self::$db[$int_server]->options(MYSQL_OPT_READ_TIMEOUT, 10);
		//self::$db[$int_server]->options(MYSQL_OPT_WRITE_TIMEOUT, 10);
		if(!self::$db[$int_server]->real_connect($this->servers[$int_server][0], $this->servers[$int_server][1],$this->servers[$int_server][2],$this->servers[$int_server][4])){
			//连接数据库错误，写入日志信息;
			$this->halt('Can not connect to MySQL server',$this->servers[$int_server][0]);
		}
		if (defined('SHOW_SQL')) {
			$m_time = explode(' ', microtime());
			$sqlendttime = number_format(($m_time[1] + $m_time[0] - $_SGLOBAL['supe_starttime']), 6) * 1000;
			$sqltime = round(($sqlendttime - $sqlstarttime), 3);
			echo $sqltime.'<br>';
		}
		return self::$db[$int_server];
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
	private function post_trigger($arr_trigger,$arr_fields,$str_url = ''){
		$str_url = $arr_trigger['url'];
		if(!$str_url){
			return false;
		}
		$arr_fields['str_server'] = 'uTyNfgYa6JFA1XyDc1CjaxtDU+Q=';
		$arr_fields['str_client'] = 'aBbexcvC28';
		$mix_return = post_curl_b(array('type'=>'post','url'=>$str_url,'fields'=>json_encode($arr_fields)));
		//如果请求未返回正确返回值1，则请求出错，写入出错记录，等待下一次请求;
		$obj_mongo = mongo_init();
		$obj_mongo->select_db('baiji_admin');
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
	//根据sql匹配出数据库、表、操作方式;
	public function match_db_table_operate($str_sql){
		$arr_preg = array(
				'select'=>'/^select.*from[\s]+`([a-z0-9_]+)`\.`([a-z0-9_]+)`/i',
				'update'=>'/^update[\s]+`([a-z0-9_]+)`\.`([a-z0-9_]+)`/i',
				'insert'=>'/^insert[\s]+into[\s]+`([a-z0-9_]+)`\.`([a-z0-9_]+)`/i',
				'delete'=>'/^delete[\s]+from[\s]+`([a-z0-9_]+)`\.`([a-z0-9_]+)`/i',
		);
		$str_sql = trim($str_sql);
		$arr_sql = explode(' ', $str_sql);
		$str_sql_type = strtolower($arr_sql[0]);
		preg_match($arr_preg[$str_sql_type],$str_sql,$arr_db_table);
		$arr_db_table[0] = strtolower($arr_db_table[0]);
		$arr_db_table[1] = strtolower($arr_db_table[1]);
		$arr_db_table[3] = $str_sql_type;
		!isset($arr_preg[$str_sql_type]) && $arr_db_table[3]='select';//匹配不成功默认为select
		return $arr_db_table;
	}
	//根据sql匹配出数据库_表;
	public function match_db_table($str_sql){
		$arr_preg = array(
				'select'=>'/^select.*from[\s]+`([a-z0-9_]+)`\.`([a-z0-9_]+)`/i',
				'update'=>'/^update[\s]+`([a-z0-9_]+)`\.`([a-z0-9_]+)`/i',
				'insert'=>'/^insert[\s]+into[\s]+`([a-z0-9_]+)`\.`([a-z0-9_]+)`/i',
				'delete'=>'/^delete[\s]+from[\s]+`([a-z0-9_]+)`\.`([a-z0-9_]+)`/i',
				'replace'=>'/^replace[\s]+into[\s]+`([a-z0-9_]+)`\.`([a-z0-9_]+)`/i',
				);
		$str_sql = trim($str_sql);
		$arr_sql = explode(' ', $str_sql);
		preg_match($arr_preg[strtolower($arr_sql[0])],$str_sql,$arr_db_table);
		if(empty($arr_db_table)){
			return 251;
			echo '无法匹配到数据库名和表名';
			exit;	
		}
		$str_db_table_name = $arr_db_table[1]. ':'.$arr_db_table[2];
		foreach($this->db_table_name as $k=>$v){
			if(preg_match('/^'.$k.'$/',$str_db_table_name)){
				$int_server = $v;
				break;
			}
		}
		return $int_server;
	}
	public function commit(){
		//提交参数$db的所有操作;
		foreach($this->current_db_servers as $v){
			$db = self::$db[$v];
			$db->query("COMMIT");
		}
		//执行所有触发器;
		if(!empty($this->triggers)){
			foreach($this->triggers as $k => $arr_trigger){
				$this->do_triggers($arr_trigger, $this->trigger_post_data[$k],$this->trigger_sql[$k],$this->trigger_sql_type[$k]);
			}
			unset($this->triggers);
			unset($this->trigger_post_data);
			unset($this->trigger_sql);
			unset($this->trigger_sql_type);
		}
		$db->query("SET AUTOCOMMIT=1");//当事务成功提交后，设置回自动执行sql
	}
	public function rollback(){
		//回滚参数$db的错又操作;
		foreach($this->current_db_servers as $v){
			$db = self::$db[$v];
			$db->query("ROLLBACK");
		}
	}
	//写日志
	function halt($message = '', $sql = '') {
		$dberror = $this->get_last_errorinfo();
		$dberrno = $this->get_last_errorno();
		$help_link = "http://faq.comsenz.com/?type=mysql&dberrno=" . rawurlencode($dberrno) . "&dberror=" . rawurlencode($dberror);
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
		@file_put_contents(S_ROOT.'ssi/log/mysql_error.txt',$strLog,FILE_APPEND);
		//header('HTTP/1.1 500 Internal Server Error');
		//echo '服务器累的回火星了，请按F5刷新或稍后再试。';
		//die();
	}
	//取得最后一次错误信息
	public function get_last_errorinfo(){
		$error_info = end($this->error_info);
		//$this->error_info = array();
		return $error_info;
	}
	//取得最后一次错误编号
	public function get_last_errorno(){
		$error_no = end($this->error_no);
		//$this->error_no = array();
		return $error_no;
	}
	//获取影响行数;
	function affected_rows() {
		return $this->affected_rows;
	}
}