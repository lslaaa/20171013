<?php

!defined('LEM') && exit('Forbidden');

class LEM_admin_setting_menu extends mysqlDBA {
    
    /*
     * 插入菜单
     */
    function _insert($arr_data){
        $str_sql = "INSERT INTO " . $this->admin('menu') . make_sql($arr_data, 'insert');
        return $this->db->query($str_sql);
    }
	
	/*
     * 更新菜单
     */
    function _update($int_mid,$arr_data){
        $str_sql = "UPDATE " . $this->admin('menu') .' SET '. make_sql($arr_data, 'update').' WHERE `mid`='.$int_mid;
        return $this->db->query($str_sql);
    }
	
	/*
	*获取一条信息
	*/
	function get_one($int_mid){
		$str_sql = 'SELECT * FROM '. $this->admin('menu') .'WHERE `mid`='.$int_mid;
		return $this->db->get_one($str_sql);
	}
	
	/*
	获取所有菜单
	*/
	function get_list($arr_data){
		$str_sql = 'SELECT * FROM '. $this->admin('menu') .'WHERE '.make_sql($arr_data,'where').' ORDER BY `level` ASC,`sort` ASC';
		return $this->db->select($str_sql);
	}
   
}

?>