<?php

!defined('LEM') && exit('Forbidden');

class LEM_consult extends mysqlDBA {
   const NO_DO = 100;
   const SUCCESS = 200;
    /*
     * 添加信息
     */
    function insert($arr_data) {
        $sql = "INSERT INTO " . $this->index('consult') . make_sql($arr_data, 'insert');
        $this->db->query($sql);
		return $this->db->insert_id();
    }
	
	/*
     * 更新信息
     */
    function update($arr_data, $arr_data2) {
        $str_sql = "UPDATE " . $this->index('consult') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }
	
	/*
     * 获取详细列表
     */
    function get_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`id` DESC') {
		$str_where = make_sql($arr_data,'where');
		$str_sql = 'SELECT count(*) AS `total` FROM ' . $this->index('consult') .' WHERE '. $str_where;
		$arr_return['total'] = $this->db->get_Value($str_sql);
		$str_sql = 'SELECT * FROM ' . $this->index('consult') .' WHERE '. $str_where .' ORDER BY'.$str_orderby.$this->page_start($int_page,$int_page_size);
		$arr_return['list'] = $this->db->select($str_sql);
		return $arr_return;
    }

	static function get_status($int_status){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		if($int_status==self::NO_DO){
			return $arr_language['consult']['no_do'];
		}
        elseif($int_status==self::SUCCESS){
           return $arr_language['consult']['success'];
		}
	}
}

?>