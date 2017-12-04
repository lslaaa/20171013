<?php

!defined('LEM') && exit('Forbidden');

class LEM_member extends mysqlDBA {
    /*
      检测浏览者登录状态
      @return 用户信息
     */

    function verify_login() {
        $str_auth = get_cookie('auth');
        if (trim($str_auth) === "") {
            return false;
        }
        $str_auth = authcode($str_auth, 'DECODE');
		$str_auth = explode("\n", $str_auth);
        if (count($str_auth) !== 2) {
            return false;
        }
        $int_uid = intval($str_auth[0]);
        $str_password = strval($str_auth[1]);
		
        $arr_member = $this->get_one_member($int_uid);
        if (empty($arr_member) || $arr_member['password'] != $str_password) {
            return false;
        }
        return $int_uid;
    }

    /*
      后台用户登录
      @$str_username用户名
      @$str_password密码
      @return 用户信息
     */

    function login($str_phone, $str_password) {
        global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
        $str_sql = "SELECT * FROM " . $this->index('member') . " WHERE `phone`='$str_phone' AND `is_del`=0";
        $arr_member = $this->db->get_one($str_sql);
        if (empty($arr_member)) {
            return array('info' => $arr_language['member']['login_error_1'], 'status' => 304);
        }
        $arr_member['uid'] = intval($arr_member['uid']);
       
        if ($arr_member['password'] != md5(md5($str_password) . $arr_member['salt'])) {
            return array('info' => $arr_language['member']['login_error_2'], 'status' => 304);
        }
        $this->set_login_cookie($arr_member['uid'], $arr_member['password']);
        $arr_return = $this->get_one_member($arr_member['uid']);
        $arr_return['uid'] = intval($arr_return['uid']);
        return array('info' =>'', 'status' => 200,'data'=>$arr_member);
    }

    /*
      设置登录后的seesion或清除
      @$int_uid 用户ID
      @$str_password 用户密码
     */

    function set_login_cookie($int_uid, $str_password, $int_expire_time = 0) {
        shift_cookie('auth');
        cookie('auth', authcode($int_uid . "\n" . $str_password,'ENCODE'), $int_expire_time);
    }
	
	/*
      获取单个用户信息
      @$int_uid用户ID
      @return 用户信息
     */

    function get_one($int_uid) {
        $arr_return = $this->get_one_member($int_uid);
		if(empty($arr_return)){
			return array();	
		}
		$arr_return['detail'] = $this->get_one_member_detail($int_uid);
        return $arr_return;
    }

    /*
      获取单个用户信息
      @$int_uid用户ID
      @return 用户信息
     */

    function get_one_member($int_uid) {
        $str_sql = "SELECT * FROM " . $this->index('member') . " WHERE `uid`='$int_uid'";
        $arr_return = $this->db->get_one($str_sql);
        if (empty($arr_return)) {
            return false;
        }
        return $arr_return;
    }
	
		
	function get_one_member_b($str_openid_2) {
        $str_sql = "SELECT * FROM " . $this->index('member') . " WHERE `openid`='$str_openid_2'";
        $arr_return = $this->db->get_one($str_sql);
        if (empty($arr_return)) {
            return false;
        }
        return $arr_return;
    }
	
	function get_one_member_detail($int_uid) {
        $str_sql = "SELECT * FROM " . $this->index('member_detail') . " WHERE `uid`='$int_uid'";
        $arr_return = $this->db->get_one($str_sql);
        if (empty($arr_return)) {
            return false;
        }
        return $arr_return;
    }

    /*
      更新用户信息
      @$arr_data更新条件
      @$arr_data2需要更新的信息
      @return true or false
     */

    function update_member($arr_data, $arr_data2,$bool_only_slq=false) {
		if(isset($arr_data2['password'])){
			$arr_data2['salt'] = rand(1000,9999);	
			$arr_data2['password'] = md5(md5($arr_data2['password']).$arr_data2['salt']);
		}
        $str_sql = "UPDATE " . $this->index('member') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        if ($bool_only_slq) {
            return $str_sql;
        }
        return $this->db->query($str_sql);
    }
    
    /*
     * 物理删除用户
     */
    function delete_member($str_where){
        $str_sql = "DELETE FROM " . $this->index('member') . " WHERE " . $str_where;
        return $this->db->query($str_sql);
    }
	
    function update_member_detail($arr_data, $arr_data2) {
        $str_sql = "UPDATE " . $this->index('member_detail') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }

  
    /*
     * 
     * @$arr_data
     * @return true or false
     */

    function insert_member($arr_data) {
		$arr_data['salt'] = rand(1000,9999);
		$arr_data['password'] = md5(md5($arr_data['password']).$arr_data['salt']);
        $sql = "INSERT INTO " . $this->index('member') . make_sql($arr_data, 'insert');
        $this->db->query($sql);
		return $this->db->insert_id();
    }
	
	function insert_member_detail($arr_data){
		$sql = "INSERT INTO " . $this->index('member_detail') . make_sql($arr_data, 'insert');
        return $this->db->query($sql);	
	}
	/*
	检测用户名是否存在
	*/
	function verify_rename($str_username,$bool_return_data=false){
		$sql = "SELECT * FROM " . $this->index('member') . " WHERE `username`='{$str_username}'";
        $arr_data = $this->db->get_one($sql);
		if($bool_return_data){
			return $arr_data;
		}
		if(empty($arr_data)){
			return false;
		}
		return true;
	}
	
	/*
	检测phone是否存在
	*/
	function verify_rephone($str_phone,$bool_return_data=false){
		$sql = "SELECT * FROM " . $this->index('member') . " WHERE `phone`='{$str_phone}'";
        $arr_data = $this->db->get_one($sql);
		if($bool_return_data){
			return $arr_data;
		}
		if(empty($arr_data)){
			return false;
		}
		return true;
	}

    /*
     * 获取所有会员列表
	 *@$arr_data where数组 只能是等于的条件
	 *@$int_page当前页码
	 *@$int_page_size每个个数
	 *@$str_orderby排序规则
	 *@$bool_only_total是否只返回总数
     */
    function get_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`uid` DESC',$bool_only_total=false) {
		$str_where = make_sql($arr_data,'where');
		$str_where && $str_where = ' WHERE '.$str_where;
		
        $arr_return = array();
		$str_sql = "SELECT COUNT(`uid`) FROM " . $this->index('member').$str_where;
		$arr_return['total'] = $this->db->get_value($str_sql);
		if($bool_only_total){
			return $arr_return['total'];
		}
		$str_sql = 'SELECT * FROM ' . $this->index('member') . $str_where .' ORDER BY '.$str_orderby.$this->page_start($int_page,$int_page_size);
		$arr_return['list'] = $this->db->select($str_sql);
        return $arr_return;
    }
	
	function get_list_b($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`uid` DESC',$bool_only_total=false){
		$str_where = make_sql($arr_data,'where');
		$str_where && $str_where = ' WHERE '.$str_where;
		
        $arr_return = array();
		$str_sql = "SELECT COUNT(`uid`) FROM " . $this->index('member_detail').$str_where;
		$arr_return['total'] = $this->db->get_value($str_sql);
		if($bool_only_total){
			return $arr_return['total'];
		}
		$str_sql = 'SELECT `uid` FROM ' . $this->index('member_detail') . $str_where .' ORDER BY '.$str_orderby.$this->page_start($int_page,$int_page_size);
		$arr_return['list'] = $this->db->select($str_sql);
        return $arr_return;	
	}

    function get_yaoqing_num($arr_data){
        $str_sql = 'SELECT r_uid, count(R_uid) as total FROM index_member where '.make_sql($arr_data,'where').' GROUP BY r_uid ORDER BY total DESC';
        $arr_return = $this->db->select($str_sql);
        return $arr_return; 
    }
}

?>