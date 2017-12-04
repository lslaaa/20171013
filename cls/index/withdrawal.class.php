<?php

!defined('LEM') && exit('Forbidden');

class LEM_withdrawal extends mysqlDBA {
    
    const STATUS_DO = 100;  //申请中
    const STATUS_PASS = 110; //审核通过
    const STATUS_SUCCESS = 200;  //交易成功
    const STATUS_FAIL = 304;  //交易成功
    //const STATUS_YICHANG = 0;  //异常




    /*
     * 添加信息
     */
    function insert($arr_data,$boole_return_sql=false) {
        $sql = "INSERT INTO " . $this->index('withdrawal') . make_sql($arr_data, 'insert');
        if ($boole_return_sql) {
            return $sql;
        }
        $this->db->query($sql);
        return $this->db->insert_id();
    }
    
    
    function get_one($int_id){
        $str_sql = "SELECT * FROM " . $this->index('withdrawal') . " WHERE `id`=$int_id";
        return $this->db->get_one($str_sql);
    }

    function trans_insert($arr_sql){
        return $this->trans_db->multi_query($arr_sql);
    }
    
    
    
    /*
     * 更新信息
     */
    function update($arr_data, $arr_data2,$boole_return_sql=false) {
        $str_sql = "UPDATE " . $this->index('withdrawal') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        if ($boole_return_sql) {
            return $str_sql;
        }
        return $this->db->query($str_sql);
    }
    
	
    /*
     * 获取详细列表
     */
    function get_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`in_date` DESC',$str_special_where='') {
        $str_where = make_sql($arr_data,'where');
        $str_special_where && $str_where .= $str_special_where;
        $str_sql = 'SELECT count(*) AS `total` FROM ' . $this->index('withdrawal') .' WHERE '. $str_where;
        $arr_return['total'] = $this->db->get_Value($str_sql);
        $str_sql = 'SELECT * FROM ' . $this->index('withdrawal') .' WHERE '. $str_where .' ORDER BY'.$str_orderby.$this->page_start($int_page,$int_page_size);
        $arr_return['list'] = $this->db->select($str_sql);
        return $arr_return;
    }


    function get_list_b($arr_data){
        $str_where = make_sql($arr_data,'where');
        $str_sql = "SELECT FROM_UNIXTIME( `in_date` , '%Y-%m' ) AS in_date_b, GROUP_CONCAT(id) AS id FROM " . $this->index('withdrawal') ." WHERE ". $str_where." GROUP BY in_date_b ORDER BY in_date_b DESC";
        $arr_return = $this->db->select($str_sql);
        return $arr_return;
    }
    
    
    public static function get_status_name($int_status){
        $str_name = "";
        switch($int_status){
            case self::STATUS_DO;
                $str_name = "申请中";
                break;
            case self::STATUS_PASS;
                $str_name = "审核通过";
                break;
            case self::STATUS_SUCCESS:
                $str_name = "提现成功";
                break;        
            case self::STATUS_FAIL:
                $str_name = "提现失败";
                break;   
        }
        return $str_name;
    }
    
    
    //插入微信提现队列
    public function insert_withdrawal_queue($arr_data,$boole_return_sql=false){
        $sql = "INSERT INTO " . $this->index('withdrawal_queue') . make_sql($arr_data, 'insert');
	if ($boole_return_sql) {
            return $sql;
        }
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    //插入微信提现队列
    public function insert_withdrawal_alipay_queue($arr_data,$boole_return_sql=false){
        $sql = "INSERT INTO " . $this->index('withdrawal_alipay_queue') . make_sql($arr_data, 'insert');
        if ($boole_return_sql) {
            return $sql;
        }
        $this->db->query($sql);
        return $this->db->insert_id();
    }
    
    
    
    
    
    
}
