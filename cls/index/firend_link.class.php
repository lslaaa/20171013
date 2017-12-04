<?php

!defined('LEM') && exit('Forbidden');

class LEM_firend_link extends mysqlDBA {
   

    function get_one($int_fid) {
        $str_sql = "SELECT * FROM " . $this->index('firend_link') . " WHERE `fid`='$int_fid' AND `is_del`=0";
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
        $sql = "REPLACE INTO " . $this->index('firend_link') . make_sql($arr_data, 'insert');
        return $this->db->query($sql);
    }
	
	/*
     * 更新信息
     */
    function update($arr_data, $arr_data2) {
        $str_sql = "UPDATE " . $this->index('firend_link') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }
	
	/*
     * 获取详细列表
     */
    function get_list($arr_data,$int_page=1,$int_page_size=10) {
		$str_where = make_sql($arr_data,'where');
		$str_sql = 'SELECT count(*) AS `total` FROM ' . $this->index('firend_link') .' WHERE '. $str_where;
		$arr_return['total'] = $this->db->get_Value($str_sql);
		$str_sql = 'SELECT * FROM ' . $this->index('firend_link') .' WHERE '. $str_where .' ORDER BY `sort` ASC,`fid` DESC'.$this->page_start($int_page,$int_page_size);
		$arr_return['list'] = $this->db->select($str_sql);
		return $arr_return;
    }
}
?>