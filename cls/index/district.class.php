<?php

!defined('LEM') && exit('Forbidden');

class LEM_district extends mysqlDBA {
	
	function get_one($int_id) {
		$str_sql = 'SELECT * FROM ' . $this->index('district') .' WHERE `id`='.$int_id;
		return $this->db->get_one($str_sql);
    }
	/*
     * 获取列表
     */
    function get_list($arr_data,$str_key='') {
		$str_sql = 'SELECT * FROM ' . $this->index('district') .' WHERE '. make_sql($arr_data, 'where')." ORDER BY `sort` ASC,`id` ASC";
		if($str_key){
			return $this->db->select($str_sql,$str_key);	
		}
		return $this->db->select($str_sql);
    }
	
	
}

?>