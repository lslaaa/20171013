<?php

!defined('LEM') && exit('Forbidden');

class LEM_sms extends mysqlDBA {
   
	function get_list($arr_data,$fields='*',$int_page=1,$int_page_size=10,$str_orderby='`mid` DESC') {
        $str_where = make_sql($arr_data,'where');
        $str_sql = 'SELECT count(*) AS `total` FROM ' . $this->index('sms') .' WHERE '. $str_where;
        $arr_return['total'] = $this->db->get_Value($str_sql);
        $str_sql = 'SELECT * FROM ' . $this->index('sms') .' WHERE '. $str_where .' ORDER BY'.$str_orderby.$this->page_start($int_page,$int_page_size);
        $arr_return['list'] = $this->db->select($str_sql);
        return $arr_return;
    }

    function get_list_b($arr_data,$str_orderby='`mid` DESC') {
        $str_where = make_sql($arr_data,'where');
        $str_sql = 'SELECT * FROM ' . $this->index('sms') .' WHERE '. $str_where .' ORDER BY'.$str_orderby;
        $arr_return = $this->db->select($str_sql);
        return $arr_return;
    }


    function get_one($int_mid){
		$str_sql = 'SELECT * FROM '. $this->index('sms') .'WHERE `mid`='.$int_mid;
		return $this->db->get_one($str_sql);
	}


	
	function _update($int_mid,$arr_data){
		$str_sql = "UPDATE " . $this->index('sms') .' SET '. make_sql($arr_data, 'update').' WHERE `mid`='.$int_mid;
		return $this->db->query($str_sql);
	}

    /*
     * 插入
     */
    function _insert($arr_data){
        $str_sql = "INSERT INTO " . $this->index('sms') . make_sql($arr_data, 'insert');
        return $this->db->query($str_sql);
    }

    function _insert_detail($arr_data){
        $str_sql = "INSERT INTO " . $this->index('sms_detail') . make_sql($arr_data, 'insert');
        return $this->db->query($str_sql);
    }

    function get_sms_detail($arr_data) {
        $str_where = make_sql($arr_data,'where');
        $str_sql = 'SELECT * FROM ' . $this->index('sms_detail') .' WHERE '. $str_where;
        $arr_return = $this->db->select($str_sql);
        return $arr_return;
    }
    
        
    function get_sms_detail_b($arr_data) {
        $str_where = make_sql($arr_data,'where');
        $str_sql = "SELECT `accountid`, count( * ) as `total` FROM " . $this->index('sms_detail')." WHERE ". make_sql($arr_data, 'where').' GROUP BY `accountid`';
        $arr_return = $this->db->select($str_sql);
        return $arr_return;
    }
    
    function get_sms_detail_c($arr_data) {
        $str_where = make_sql($arr_data,'where');
        $str_sql = "SELECT FROM_UNIXTIME( `in_date` , '%Y-%m-%d' ) AS `in_date_b`, count(*) as `total` FROM " . $this->index('sms_detail')." WHERE ". make_sql($arr_data, 'where').' GROUP BY `in_date_b`';
        $arr_return = $this->db->select($str_sql);
        return $arr_return;
    }
    
    function get_sms_detail_d($arr_data=array()) {
        $str_where = make_sql($arr_data,'where');
	$str_where && $str_where = ' WHERE '.$str_where;
        $str_sql = 'SELECT `aid`,COUNT(*) as `total` FROM ' . $this->index('sms_detail') .$str_where.' GROUP BY `aid`';
	return  $this->db->select($str_sql);
    }

    function get_list_detail($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`in_date` DESC') {
        $str_where = make_sql($arr_data,'where');
        $str_sql = 'SELECT count(*) AS `total` FROM ' . $this->index('sms_detail') .' WHERE '. $str_where;
        $arr_return['total'] = $this->db->get_Value($str_sql);
        $str_sql = 'SELECT * FROM ' . $this->index('sms_detail') .' WHERE '. $str_where .' ORDER BY'.$str_orderby.$this->page_start($int_page,$int_page_size);
        $arr_return['list'] = $this->db->select($str_sql);
        return $arr_return;
    }
}

?>