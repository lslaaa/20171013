<?php

!defined('LEM') && exit('Forbidden');

class LEM_recharge extends mysqlDBA {
	/*
     * 获取列表
     */
    function get_list($arr_data,$fields='*',$int_page=1,$int_page_size=10,$str_orderby='`in_date` DESC') {
        $str_username='';
        if ($arr_data['usernames']) {
            $str_username = $arr_data['usernames'];
            unset($arr_data['usernames']);
        }
        $str_where = make_sql($arr_data,'where').$str_username;
        $str_sql = 'SELECT count(*) AS `total` FROM ' . $this->index('recharge') .' WHERE '. $str_where;
        $arr_return['total'] = $this->db->get_Value($str_sql);
        $str_sql = 'SELECT '.$fields.' FROM ' . $this->index('recharge') .' WHERE '. $str_where .' ORDER BY'.$str_orderby.$this->page_start($int_page,$int_page_size);
        $arr_return['list'] = $this->db->select($str_sql);
        return $arr_return;
    }
    
    function get_list_b($arr_data=array()) {
        $str_where = make_sql($arr_data,'where');
        $str_sql = 'SELECT `uid`,SUM(`money`) AS `total_money` FROM ' . $this->index('recharge') .' WHERE '. $str_where.' GROUP BY `uid`';
	return  $this->db->select($str_sql);
    }

    /*
     * 插入
     */
    function _insert($arr_data,$bool_only_return_sql=false){
        $str_sql = "INSERT INTO " . $this->index('recharge') . make_sql($arr_data, 'insert');
        if($bool_only_return_sql){
            return  $str_sql;
        }
        return $this->db->query($str_sql);
    }

    /*
	*获取一条信息
	*/
	function get_one($int_cid){
		$str_sql = 'SELECT * FROM '. $this->index('recharge') .'WHERE `id`='.$int_cid;
		return $this->db->get_one($str_sql);
	}

    function get_one_b($arr_data){
        $str_sql = 'SELECT * FROM '. $this->index('recharge') .'WHERE '. make_sql($arr_data, 'where');
        return $this->db->get_one($str_sql);
    }


    function get_one_paynum($otime,$newNum){
        $str_sql = 'SELECT * FROM '. $this->index('recharge') .' WHERE in_date>'.$otime.' AND substr(money,instr(money,"."))='.$newNum.' and is_del=0';
        return $this->db->get_one($str_sql);
    }
    function get_one_payok($otime,$money){
        $str_sql = "SELECT * FROM ". $this->index('recharge') ." WHERE in_date>".$otime." AND money='".$money."' and is_del=0 and status=0";
        return $this->db->get_one($str_sql);
    }


    function get_sum_money($arr_data){
        $str_sql = "SELECT SUM(`money`) as total FROM ". $this->index('recharge') ." WHERE ". make_sql($arr_data, 'where');
        return $this->db->select($str_sql);
    }


	/*
     * 更新
     */
    function _update($int_cid,$arr_data,$bool_only_return_sql=false){
        $str_sql = "UPDATE " . $this->index('recharge') .' SET '. make_sql($arr_data, 'update').' WHERE `id`='.$int_cid;
	if($bool_only_return_sql){
		return 	$str_sql;
	}
        return $this->db->query($str_sql);
    }
}