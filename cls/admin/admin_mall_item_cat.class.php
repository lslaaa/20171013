<?php

!defined('LEM') && exit('Forbidden');

class LEM_admin_mall_item_cat extends mysqlDBA {
    
    /*
     * 插入菜单
     */
    function _insert($arr_data){
        $str_sql = "INSERT INTO " . $this->index('item_cat') . make_sql($arr_data, 'insert');
        return $this->db->query($str_sql);
    }
	
	/*
     * 更新菜单
     */
    function _update($int_cid,$arr_data){
        $str_sql = "UPDATE " . $this->index('item_cat') .' SET '. make_sql($arr_data, 'update').' WHERE `cid`='.$int_cid;
        return $this->db->query($str_sql);
    }
	
	/*
	*获取一条信息
	*/
	function get_one($int_cid){
		$str_sql = 'SELECT * FROM '. $this->index('item_cat') .'WHERE `cid`='.$int_cid;
		return $this->db->get_one($str_sql);
	}
	
	/*
	获取所有菜单
	*/
	function get_list($arr_data){
		$str_sql = 'SELECT * FROM '. $this->index('item_cat') .'WHERE '.make_sql($arr_data,'where').' ORDER BY `level` ASC,`sort` ASC';
		return $this->db->select($str_sql);
	}
   
}

?>