<?php

!defined('LEM') && exit('Forbidden');

class LEM_user extends mysqlDBA {
        /*
        * 获取列表
        */
        function get_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`gid` ASC,`in_date` DESC',$fields="*") {

                $str_usernames='';
                $str_balances='';
                $str_afterids='';
                if ($arr_data['usernames']) {
                        $str_usernames = $arr_data['usernames'];
                        unset($arr_data['usernames']);
                }
                if ($arr_data['balances']) {
                        $str_balances = $arr_data['balances'];
                        unset($arr_data['balances']);
                }
                if ($arr_data['status']) {
                        $int_status = $arr_data['status'];
                        if ($arr_data['status']==1) {
                        $str_afterids = ' AND afterid = 0 ';
                }else{
                        $str_afterids = ' AND afterid > 0 ';
                }
                unset($arr_data['status']);
                }
                $str_where = make_sql($arr_data,'where').$str_usernames.$str_balances.$str_afterids.' ORDER BY '.$str_orderby;
                $str_sql = 'SELECT count(*) AS `total` FROM ' . $this->index('user') .' WHERE '. $str_where;
                $arr_return['total'] = $this->db->get_Value($str_sql);
                $str_sql = 'SELECT '.$fields.' FROM ' . $this->index('user') .' WHERE '. $str_where.$this->page_start($int_page,$int_page_size);
                $arr_return['list'] = $this->db->select($str_sql);
                return $arr_return;
        }

        /*
        * 插入
        */
        function _insert($arr_data){
                $arr_data['salt'] = rand(1000,9999);
		        $arr_data['password'] = md5(md5($arr_data['password']).$arr_data['salt']);
                $str_sql = "INSERT INTO " . $this->index('user') . make_sql($arr_data, 'insert');
                $this->db->query($str_sql);
                return $this->db->insert_id();
        }


        /*
        * 更新
        */
        function _update($int_cid,$arr_data,$bool_only_return_sql=false){
                if ($arr_data['password']) {
                        $arr_data['salt'] = rand(1000,9999);
          		        $arr_data['password'] = md5(md5($arr_data['password']).$arr_data['salt']);
                }
                $str_sql = "UPDATE " . $this->index('user') .' SET '. make_sql($arr_data, 'update').' WHERE `uid`='.$int_cid;
		if($bool_only_return_sql){
			return $str_sql;
		}
                return $this->db->query($str_sql);
        }

    /*
     * 更新
     */
    function _update_b($int_cid,$arr_data,$bool_only_return_sql=false){
        $str_sql = "UPDATE " . $this->index('user') .' SET '. make_sql($arr_data, 'update').' WHERE `uid`='.$int_cid;
	if($bool_only_return_sql){
		return 	$str_sql;
	}
        return $this->db->query($str_sql);
    }

    /*
	*获取一条信息
	*/
	function get_one($int_uid){
		$str_sql = 'SELECT * FROM '. $this->index('user') .'WHERE `uid`='.$int_uid;
		return $this->db->get_one($str_sql);
	}

    function get_one_b($arr_data){
        $str_sql = 'SELECT * FROM '. $this->index('user') .' WHERE '. make_sql($arr_data, 'where');
        return $this->db->get_one($str_sql);
    }

    /*
      后台用户登录
      @$str_username用户名
      @$str_password密码
      @return 用户信息
     */

    function login($str_username, $str_password) {
        global $_SGLOBAL;
        $arr_language = $_SGLOBAL['language'];
        $str_sql = "SELECT * FROM " . $this->index('user') . " WHERE `username`='$str_username'";
        $arr_member = $this->db->get_one($str_sql);
        if (empty($arr_member)) {
            return array('info' => $arr_language['login']['error_1'], 'status' => 304);
        }
        if ($arr_member['is_del'] && $arr_member['gid']==1) {
            return array('info' => '请加<b style="color:red"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin='.$_SGLOBAL['data_config']['data_qq'].'&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:'.$_SGLOBAL['data_config']['data_qq'].':52" alt="点击这里给我发消息" title="点击这里给我发消息"/>'.$_SGLOBAL['data_config']['data_qq'].'</a></b>进行审核', 'status' => 304);
        }
        if ($arr_member['is_del'] && $arr_member['gid']!=1) {
            return array('info' => '账号已被禁用', 'status' => 304);
        }
        $arr_member['uid'] = intval($arr_member['uid']);
        if ($arr_member['password'] != md5(md5($str_password) . $arr_member['salt'])) {
            return array('info' => $arr_language['login']['error_2'], 'status' => 304);
        }
        $this->set_login_session($arr_member['uid'], $arr_member['password']);
        $arr_return = $this->get_one_member($arr_member['uid']);
        $arr_return['uid'] = intval($arr_return['uid']);
        return array('info' =>'', 'status' => 200,'data'=>$arr_member);
    }


    /*
      设置登录后的seesion或清除
      @$int_id 用户ID
      @$str_password 用户密码
      @$bool_clear 为真清除session,为假设置session
     */

    function set_login_session($int_id, $str_password, $bool_clear = false) {
        if ($bool_clear) {
            unset($_SESSION['index_user_login_id'], $_SESSION['index_user_login_password']);
            shift_cookie('bool_admin_login');
        } else {
            $_SESSION['index_user_login_id'] = $int_id;
            $_SESSION['index_user_login_password'] = $str_password;
            cookie('bool_admin_login',1);
        }
    }


     /*
      获取单个用户信息
      @$int_id用户ID
      @return 用户信息
     */

        function get_one_member($int_id) {
                $str_sql = "SELECT * FROM " . $this->index('user') . " WHERE `uid`='$int_id' AND `is_del`=0";
                $arr_return = $this->db->get_one($str_sql);
                if (empty($arr_return)) {
                return false;
                }
                return $arr_return;
        }

    function get_sum_balance($arr_data){
        $str_sql = "SELECT SUM(`balance`) as total FROM ". $this->index('user') ." WHERE ". make_sql($arr_data, 'where');
        return $this->db->select($str_sql);
    }


     /*
      更新用户信息
      @$arr_data更新条件
      @$arr_data2需要更新的信息
      @return true or false
     */

        function update_member($arr_data, $arr_data2,$bool_only_return_sql=false) {
                if(isset($arr_data2['password'])){
                        $arr_data2['salt'] = rand(1000,9999);   
                        $arr_data2['password'] = md5(md5($arr_data2['password']).$arr_data2['salt']);
                }
                $str_sql = "UPDATE " . $this->index('user') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
		if($bool_only_return_sql){
			return $str_sql;	
		}
                return $this->db->query($str_sql);
        }

        /*
        检测浏览者登录状态
        @return 用户信息
        */

        function verify_login() {
                if (strstr('index.php?mod=login&extra=regist',$_SERVER["QUERY_STRING"]?$_SERVER["QUERY_STRING"]:'1')) {
                        return true;

                }
                $int_id = $_SESSION['index_user_login_id'];
                $str_password = $_SESSION['index_user_login_password'];
                $arr_member = $this->get_one_member($int_id);
                if (empty($arr_member) || $arr_member['password'] != $str_password) {
                    return false;
                }
                return true;
        }
    
        //用户余额是否还可以进行订单分配
	function verify_user_balance($int_uid,$int_sid=0){
		global $_SGLOBAL;
		$float_good_percent = 0.01;
		$arr_user = $this->get_one($int_uid);
		$obj_tmshop = L::loadClass('tmshop','index');
		$obj_order = L::loadClass('order','index');
		$arr_tmshop = $obj_tmshop->get_list_b(array('uid'=>$int_uid,'is_del'=>0));
		$arr_shopids = array();
		$arr_order_total = array();
		foreach($arr_tmshop['list'] as $v){
			$arr_data = array(
				'shopid'=>$v['sid'],
				'accountid'=>array('do'=>'gt','val'=>0),
				'afterid'=>array('do'=>'gt','val'=>0),
				'buyer_rate'=>0,
				'confirm_time'=>array('do'=>'gt','val'=>($_SGLOBAL['timestamp']-3600*24*7))
			);
			$arr_temp = $obj_order->get_order_count_c($arr_data);
			$arr_order_total[$v['sid']] = array('order_total'=>$arr_temp['order_count'],'shopid'=>$v['shopid'],'commission'=>$v['commission']);
		}
		$float_need_money = 0;
		$int_order_total = 0;
		foreach($arr_order_total as $v){
			$float_need_money += $v['order_total']*$v['commission']*$float_good_percent;
			$int_order_total += $v['order_total'];
		}
		
		$arr_return = array();
		$arr_return['order_total'] = $int_order_total;
		$int_balance = ($arr_user['balance'] + $arr_user['freeze_balance']) - $float_need_money;

		$arr_return['status'] = $int_balance>10 ? 200 : 304;
		//var_export($arr_tmshop);
		//var_export($arr_return);
		if($int_sid && $int_balance>10){
			$arr_return['can_order_total'] = intval($int_balance/$arr_order_total[$int_sid]['commission']/$float_good_percent);
		}
		return $arr_return;
		
	}
}