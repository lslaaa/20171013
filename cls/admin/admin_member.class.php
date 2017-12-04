<?php

!defined('LEM') && exit('Forbidden');

class LEM_admin_member extends mysqlDBA {
    /*
      检测浏览者登录状态
      @return 用户信息
     */

    function verify_login() {
        $int_uid = $_SESSION['admin_member_login_uid'];
        $str_password = $_SESSION['admin_member_login_password'];
        $arr_member = $this->get_one_member($int_uid);
        if (empty($arr_member) || $arr_member['password'] != $str_password) {
            return false;
        }
        return true;
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
        $str_sql = "SELECT * FROM " . $this->admin('member') . " WHERE `username`='$str_username' AND `is_del`=0";
        $arr_member = $this->db->get_one($str_sql);
        if (empty($arr_member)) {
            return array('info' => $arr_language['login']['error_1'], 'status' => 304);
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
      @$int_uid 用户ID
      @$str_password 用户密码
      @$bool_clear 为真清除session,为假设置session
     */

    function set_login_session($int_uid, $str_password, $bool_clear = false) {
        if ($bool_clear) {
            unset($_SESSION['admin_member_login_uid'], $_SESSION['admin_member_login_password']);
			shift_cookie('bool_admin_login');
        } else {
            $_SESSION['admin_member_login_uid'] = $int_uid;
            $_SESSION['admin_member_login_password'] = $str_password;
			cookie('bool_admin_login',1);
        }
    }

    /*
      获取单个用户信息
      @$int_uid用户ID
      @return 用户信息
     */

    function get_one_member($int_uid) {
        $str_sql = "SELECT * FROM " . $this->admin('member') . " WHERE `uid`='$int_uid' AND `is_del`=0";
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

    function update_member($arr_data, $arr_data2) {
		if(isset($arr_data2['password'])){
			$arr_data2['salt'] = rand(1000,9999);	
			$arr_data2['password'] = md5(md5($arr_data2['password']).$arr_data2['salt']);
		}
        $str_sql = "UPDATE " . $this->admin('member') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }

  
    /*
     * 添加管理员
     * @$int_group_id
     * @$arr_data
     * @return true or false
     */

    function insert_member($arr_data) {
		$arr_data['salt'] = rand(1000,9999);
		$arr_data['password'] = md5(md5($arr_data['password']).$arr_data['salt']);
        $sql = "INSERT INTO " . $this->admin('member') . make_sql($arr_data, 'insert');
        return $this->db->query($sql);
    }
	/*
	检测用户名是否存在
	*/
	function verify_rename($str_username,$bool_return_data=false){
		$sql = "SELECT * FROM " . $this->admin('member') . " WHERE `username`='{$str_username}' AND `is_del`=0";
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
     * 获取所有管理员列表
	 *@$arr_data where数组 只能是等于的条件
	 *@$int_page当前页码
	 *@$int_page_size每个个数
	 *@$str_orderby排序规则
	 *@$bool_only_total是否只返回总数
     */
    function get_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`uid` DESC',$bool_only_total=false) {
		$str_where = make_sql($arr_data,'where');
		$str_special_where && $str_where .= ' AND '.$str_special_where;
		
        $arr_return = array();
		$str_sql = "SELECT COUNT(`uid`) FROM " . $this->admin('member')." WHERE ".$str_where;
		$arr_return['total'] = $this->db->get_value($str_sql);
		if($bool_only_total){
			return $arr_return['total'];
		}
		$str_sql = 'SELECT * FROM ' . $this->admin('member') .' WHERE '. $str_where .' ORDER BY '.$str_orderby.$this->page_start($int_page,$int_page_size);
		$arr_return['list'] = $this->db->select($str_sql);
        return $arr_return;
    }


        /*
      权限判断
      @$int_menu_id菜单ID
    @$arr_special_all特殊允许的权限，只有当判断来源时有值
      @return  true or false
     */

    function verify_permission($str_menu_alias_name,$arr_special_all=array()) {
        global $_SGLOBAL;
    /*
        if(in_array($_SERVER['REMOTE_ADDR'],$_SGLOBAL['safe_ip'])){
            return true;
        }
    */
        //echo $str_menu_alias_name;exit();
    
      if(empty($str_menu_alias_name) && empty($arr_special_all)){
      echo '传入了空的菜单名';
      exit;
    }

    
    $obj_menu = L::loadClass('admin_setting_menu','admin');
    $arr_menu = $obj_menu->get_list(array('cname'=>$str_menu_alias_name));
    if(empty($arr_menu)){//临时将没有菜单的访问都列入拥有权限
      echo '此菜单还为设置别名，请设置为：'.$str_menu_alias_name;
      return false;
    }
    if($arr_special_all){
      $arr_val = array();
      $str_referer = $_SERVER['HTTP_REFERER'];
      $arr_temp = explode('?',$str_referer);
      preg_match('/^http:\/\/[^\/]+\/([^\/]+)/',$arr_temp[0],$arr_out);
      $arr_val['mod_dir'] = $arr_out[1];
      preg_match_all('/([\w]+)=([^\&]+)(|\&)/i',$arr_temp[1],$arr_out);
      
      foreach($arr_out[1] as $k=>$v){
        $arr_val[$v] = $arr_out[2][$k];
      }
      $int_key = array_search($arr_val['mod_dir'].'_'.$arr_val['mod'].'_'.$arr_val['extra'],$arr_special_all);
      if(!is_numeric($int_key)){
        return false;
      }
      $str_menu_alias_name = $arr_val['mod_dir'].'_'.$arr_val['mod'].'_'.$arr_val['extra'];
    }
    
    $str_menu_id = ','.$arr_menu[0]['mid'].',';
    $obj_admin_member_group = L::loadClass('admin_member_group','admin');
        $arr_group = $obj_admin_member_group->get_one($_SGLOBAL['member']['gid']);
        
        $str_group_permission = $arr_group['permission'];
        $str_user_permission = $_SGLOBAL['member']['detail']['permission'];

        if ($str_group_permission == 'all') {
            return true;
        }

        if (strstr(',' . $str_group_permission . ',', $str_menu_id)) {//判断用户组权限和用户特殊权限
            return true;
        }
        return false;
    }
}

?>