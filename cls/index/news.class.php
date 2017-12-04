<?php

!defined('LEM') && exit('Forbidden');

class LEM_news extends mysqlDBA {
   

    function get_one($int_nid) {
        $arr_return['main'] = $this->get_one_main($int_nid);
		if(empty($arr_return['main'])){
			return false;	
		}
        $arr_return['detail'] = $this->get_one_detail($int_nid);
        return $arr_return;
    }  
	
	function get_one_main($int_nid){
		$str_sql = "SELECT * FROM " . $this->index('news') . " WHERE `nid`='$int_nid' AND `is_del`=0";
        return $this->db->get_one($str_sql);	
	}
	
	function get_one_detail($int_nid){
		$str_sql = "SELECT * FROM " . $this->index('news_detail') . " WHERE `nid`='$int_nid'";
        return $this->db->get_one($str_sql);	
	}
    /*
     * 添加信息
     */
    function insert($arr_data) {
        $sql = "INSERT INTO " . $this->index('news') . make_sql($arr_data, 'insert');
        $this->db->query($sql);
		return $this->db->insert_id();
    }
	
	/*
     * 添加信息
     */
    function insert_detail($arr_data) {
        $sql = "INSERT INTO " . $this->index('news_detail') . make_sql($arr_data, 'insert');
        return $this->db->query($sql);
    }
	
	/*
     * 更新信息
     */
    function update($arr_data, $arr_data2) {
        $str_sql = "UPDATE " . $this->index('news') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }
	
	/*
     * 更新信息
     */
    function update_detail($arr_data, $arr_data2) {
        $str_sql = "UPDATE " . $this->index('news_detail') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }
	
	/*
     * 获取详细列表
     */
    function get_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`nid` DESC') {
		$str_where = make_sql($arr_data,'where');
		$str_sql = 'SELECT count(*) AS `total` FROM ' . $this->index('news') .' WHERE '. $str_where;
		$arr_return['total'] = $this->db->get_Value($str_sql);
		$str_sql = 'SELECT * FROM ' . $this->index('news') .' WHERE '. $str_where .' ORDER BY'.$str_orderby.$this->page_start($int_page,$int_page_size);
		$arr_return['list'] = $this->db->select($str_sql);
		return $arr_return;
    }

    /*
     * 获取分类列表
     */
    function get_cat_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`sort` ASC ,`cid` ASC') {
		$str_where = make_sql($arr_data,'where');
		$str_sql = 'SELECT * FROM ' . $this->index('news_cat') .' WHERE '. $str_where .' ORDER BY '.$str_orderby.$this->page_start($int_page,$int_page_size);
		return $this->db->select($str_sql);
    }
	
	/*
     * 插入分类
     */
    function _cat_insert($arr_data){
        $str_sql = "INSERT INTO " . $this->index('news_cat') . make_sql($arr_data, 'insert');
        return $this->db->query($str_sql);
    }
	
	/*
     * 更新分类
     */
    function _cat_update($int_cid,$arr_data){
        $str_sql = "UPDATE " . $this->index('news_cat') .' SET '. make_sql($arr_data, 'update').' WHERE `cid`='.$int_cid;
        return $this->db->query($str_sql);
    }
	
	/*
	*获取一条分类
	*/
	function get_one_cat($int_cid){
		$str_sql = 'SELECT * FROM '. $this->index('news_cat') .'WHERE `cid`='.$int_cid;
		return $this->db->get_one($str_sql);
	}
	
	function get_prev_next($int_nid){
		$arr_return = array();
		$str_sql = 'SELECT * FROM ' . $this->index('news') .' WHERE `nid`<'. $int_nid .' AND `is_del`=0 ORDER BY `nid` DESC LIMIT 0,1';
		$arr_return['prev'] = $this->db->get_one($str_sql);
		$str_sql = 'SELECT * FROM ' . $this->index('news') .' WHERE `nid`>'. $int_nid .' AND `is_del`=0 ORDER BY `nid` ASC LIMIT 0,1';
		$arr_return['next'] = $this->db->get_one($str_sql);
		return $arr_return;
	}
}

?>