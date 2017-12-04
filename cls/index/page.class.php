<?php

!defined('LEM') && exit('Forbidden');

class LEM_page extends mysqlDBA {
   

    function get_one($int_page_id,$str_language='') {
        $str_sql = "SELECT * FROM " . $this->index('page') . " WHERE `page_id`='$int_page_id' AND `language`='$str_language' AND `is_del`=0";
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
        $sql = "INSERT INTO " . $this->index('page') . make_sql($arr_data, 'insert');
        return $this->db->query($sql);
    }
	
	/*
     * 更新信息
     */
    function update($arr_data, $arr_data2) {
        $str_sql = "UPDATE " . $this->index('page') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }
	
	/*
     * 获取单页详细列表
     */
    function get_list($arr_data,$int_page=1,$int_page_size=10) {
		$str_where = make_sql($arr_data,'where');
		$str_sql = 'SELECT count(*) AS `total` FROM ' . $this->index('page') .' WHERE '. $str_where;
		$arr_return['total'] = $this->db->get_Value($str_sql);
		$str_sql = 'SELECT * FROM ' . $this->index('page') .' WHERE '. $str_where .$this->page_start($int_page,$int_page_size);
		$arr_return['list'] = $this->db->select($str_sql);
		return $arr_return;
    }

    /*
     * 获取单页分类列表
     */
    function get_cat_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`sort` ASC') {
		$str_where = make_sql($arr_data,'where');
		$str_sql = 'SELECT * FROM ' . $this->index('page_cat') .' WHERE '. $str_where .' ORDER BY '.$str_orderby.$this->page_start($int_page,$int_page_size);
		return $this->db->select($str_sql);
    }
	
	/*
     * 插入分类
     */
    function _cat_insert($arr_data){
        $str_sql = "INSERT INTO " . $this->index('page_cat') . make_sql($arr_data, 'insert');
        return $this->db->query($str_sql);
    }
	
	/*
     * 更新分类
     */
    function _cat_update($int_page_id,$arr_data){
        $str_sql = "UPDATE " . $this->index('page_cat') .' SET '. make_sql($arr_data, 'update').' WHERE `page_id`='.$int_page_id;
        return $this->db->query($str_sql);
    }
	
	/*
	*获取一条分类
	*/
	function get_one_cat($int_page_id){
		$str_sql = 'SELECT * FROM '. $this->index('page_cat') .'WHERE `page_id`='.$int_page_id;
		return $this->db->get_one($str_sql);
	}
}
?>