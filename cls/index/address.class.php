<?php

!defined('LEM') && exit('Forbidden');

class LEM_address extends mysqlDBA {
   

    function get_one($int_address_id) {
        $str_sql = "SELECT * FROM " . $this->index('address') . " WHERE `address_id`='$int_address_id' AND `is_del`=0";
        $arr_return = $this->db->get_one($str_sql);
        if (empty($arr_return)) {
            return false;
        }
        return $arr_return;
    }  
    /*
     * 添加信息
     */
    function insert($arr_data) {
        $sql = "INSERT INTO " . $this->index('address') . make_sql($arr_data, 'insert');
        $this->db->query($sql);
		return $this->db->insert_id();
    }
	
	/*
     * 更新信息
     */
    function update($arr_data, $arr_data2) {
        $str_sql = "UPDATE " . $this->index('address') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }
	
	/*
     * 获取列表
     */
    function get_list($int_uid) {
		$arr_data = array(
						'uid'=>$int_uid,
						'is_del'=>0
					);
		$str_sql = 'SELECT * FROM ' . $this->index('address') .' WHERE '. make_sql($arr_data, 'where');
		return $this->db->select($str_sql);
    }
}

?>