<?php

!defined('LEM') && exit('Forbidden');

class LEM_mingzu extends mysqlDBA {
	
	function get_one($int_id) {
		$str_sql = 'SELECT * FROM ' . $this->index('mingzu') .' WHERE `id`='.$int_id;
		return $this->db->get_one($str_sql);
    }
	/*
     * 获取列表
     */
    function get_list($str_key='') {
		$str_sql = 'SELECT * FROM ' . $this->index('mingzu').' ORDER BY `sort` ASC,`id` ASC';
		if($str_key){
			return $this->db->select($str_sql,$str_key);	
		}
		return $this->db->select($str_sql);
    }
	
	
}

?>