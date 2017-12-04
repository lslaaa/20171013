<?php

!defined('LEM') && exit('Forbidden');

class LEM_member_group extends mysqlDBA {
   
    function get_one($int_gid) {
        $str_sql = "SELECT * FROM " . $this->index('member_group') . " WHERE `gid`='$int_gid' AND `is_del`=0";
        $arr_return = $this->db->get_one($str_sql);
        if (empty($arr_return)) {
            return false;
        }
        return $arr_return;
    }

    /*
      更新信息
      @$arr_data更新条件
      @$arr_data2需要更新的信息
      @return true or false
     */

    function update($arr_data, $arr_data2) {
        $str_sql = "UPDATE " . $this->index('member_group') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }

  
    /*
     * 添加信息
     * @$arr_data
     * @return true or false
     */

    function insert($arr_data) {
        $sql = "INSERT INTO " . $this->index('member_group') . make_sql($arr_data, 'insert');
        return $this->db->query($sql);
    }
	/*
	检测用户名是否存在
	*/
	function verify_rename($str_group_name,$bool_return_data=false){
		$sql = "SELECT * FROM " . $this->index('member_group') . " WHERE `group_name`='{$str_group_name}' AND `is_del`=0";
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
     * 获取所有列表
	 *@$arr_data where数组 只能是等于的条件
	 *@$int_page当前页码
	 *@$int_page_size每个个数
	 *@$str_orderby排序规则
	 *@$bool_only_total是否只返回总数
     */
    function get_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`sort` ASC,`gid` ASC',$bool_only_total=false) {
		$str_where = make_sql($arr_data,'where');
		$str_special_where && $str_where .= ' AND '.$str_special_where;
		
        $arr_return = array();
		$str_sql = "SELECT COUNT(`gid`) FROM " . $this->index('member_group')." WHERE ".$str_where;
		$arr_return['total'] = $this->db->get_value($str_sql);
		if($bool_only_total){
			return $arr_return['total'];
		}
		$str_sql = 'SELECT * FROM ' . $this->index('member_group') .' WHERE '. $str_where .' ORDER BY '.$str_orderby.$this->page_start($int_page,$int_page_size);
		$arr_return['list'] = $this->db->select($str_sql);
        return $arr_return;
    }
}

?>