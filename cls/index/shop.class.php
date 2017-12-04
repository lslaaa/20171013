<?php

!defined('LEM') && exit('Forbidden');

class LEM_shop extends mysqlDBA {
   

    function get_one($int_id) {
        $arr_return['main'] = $this->get_one_main($int_id);
                if(empty($arr_return['main'])){
                        return false;   
                }
        $arr_return['detail'] = $this->get_one_detail($int_id);
        return $arr_return;
    }  
        
    function get_one_main($int_id){
        $str_sql = "SELECT * FROM " . $this->index('shop') . " WHERE `sid`='$int_id' AND `is_del`=0";
        return $this->db->get_one($str_sql);    
    }

    function get_one_b($arr_data){
        $str_sql = "SELECT * FROM " . $this->index('shop') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->get_one($str_sql);    
    }
        
    function get_one_detail($int_id){
        $str_sql = "SELECT * FROM " . $this->index('shop_detail') . " WHERE `sid`='$int_id'";
        return $this->db->get_one($str_sql);    
    }
    /*
     * 添加信息
     */
    function insert($arr_data) {
        $sql = "INSERT INTO " . $this->index('shop') . make_sql($arr_data, 'insert');
        $this->db->query($sql);
        return $this->db->insert_id();
    }
        
        /*
     * 添加信息
     */
    function insert_detail($arr_data) {
        $sql = "INSERT INTO " . $this->index('shop_detail') . make_sql($arr_data, 'insert');
        return $this->db->query($sql);
    }
        
        /*
     * 更新信息
     */
    function update($arr_data, $arr_data2) {
        $str_sql = "UPDATE " . $this->index('shop') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }
        
        /*
     * 更新信息
     */
    function update_detail($arr_data, $arr_data2) {
        $str_sql = "UPDATE " . $this->index('shop_detail') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }
        
        /*
     * 获取详细列表
     */
    function get_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`sid` DESC') {
                $str_where = make_sql($arr_data,'where');
                $str_sql = 'SELECT count(*) AS `total` FROM ' . $this->index('shop') .' WHERE '. $str_where;
                $arr_return['total'] = $this->db->get_Value($str_sql);
                $str_sql = 'SELECT * FROM ' . $this->index('shop') .' WHERE '. $str_where .' ORDER BY'.$str_orderby.$this->page_start($int_page,$int_page_size);
                $arr_return['list'] = $this->db->select($str_sql);
                return $arr_return;
    }

    function get_type_name(){
            $arr_name = array(
                    '10'=>'天猫',
                    '20'=>'淘宝',
                    '30'=>'京东',
                    '40'=>'蘑菇街',
                    '50'=>'拼多多',
                    '60'=>'非电商',
            );
            return $arr_name;
    }




}

?>