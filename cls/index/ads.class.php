<?php

!defined('LEM') && exit('Forbidden');

class LEM_ads extends mysqlDBA {
	

    /*
      获取单个分类信息
     */

    function get_one_cat($int_ads_cid) {
        $str_sql = "SELECT * FROM " . $this->index('ads_cat') . " WHERE `ads_cid`=$int_ads_cid";
        $arr_return = $this->db->get_one($str_sql);
        if (empty($arr_return)) {
            return false;
        }
        return $arr_return;
    }
	
	/*
      获取单个广告信息
     */

    function get_one($int_aid) {
        $str_sql = "SELECT * FROM " . $this->index('ads') . " WHERE `aid`=$int_aid";
        $arr_return = $this->db->get_one($str_sql);
        if (empty($arr_return)) {
            return false;
        }
        return $arr_return;
    }

    /*
      更新广告信息
     */

    function update($arr_data, $arr_data2) {
        $str_sql = "UPDATE " . $this->index('ads') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }

  
    /*
     * 添加广告信息
     */

    function insert($arr_data) {
        $sql = "INSERT INTO " . $this->index('ads') . make_sql($arr_data, 'insert');
        return $this->db->query($sql);
    }

    /*
     * 获取所有广告列表
	 *@$arr_data where数组 只能是等于的条件
	 *@$int_page当前页码
	 *@$int_page_size每个个数
	 *@$str_orderby排序规则
	 *@$bool_only_total是否只返回总数
     */
    function get_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`sort` ASC,`aid` DESC',$bool_only_total=false) {
		$str_where = make_sql($arr_data,'where');
		$str_where && $str_where = ' WHERE '.$str_where;
		
        $arr_return = array();
		$str_sql = "SELECT COUNT(`aid`) FROM " . $this->index('ads').$str_where;
		$arr_return['total'] = $this->db->get_value($str_sql);
		if($bool_only_total){
			return $arr_return['total'];
		}
		$str_sql = 'SELECT * FROM ' . $this->index('ads') . $str_where .' ORDER BY '.$str_orderby.$this->page_start($int_page,$int_page_size);
		$arr_return['list'] = $this->db->select($str_sql);
        return $arr_return;
    }
	
	function get_list_cat($arr_data) {
		$str_where = make_sql($arr_data,'where');
		$str_where && $str_where = ' WHERE '.$str_where;
		$str_sql = 'SELECT * FROM ' . $this->index('ads_cat') . $str_where;
		return $this->db->select($str_sql);
    }
}

?>