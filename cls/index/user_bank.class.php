<?php

!defined('LEM') && exit('Forbidden');

class LEM_user_bank extends mysqlDBA {
        
        function get_one($arr_data) {
                $str_where = make_sql($arr_data,'where');
                $str_sql = 'SELECT * FROM ' . $this->index('user_bank') .' WHERE '. $str_where;
                return $this->db->get_one($str_sql);
        }
        /*
        *插入数据
        */
        function insert($arr_data){
                $str_sql = "INSERT INTO " . $this->index('user_bank') . make_sql($arr_data, 'insert');
                $this->db->query($str_sql);
                return $this->db->insert_id();
        }

        /*
        * 更新信息
        */
        function update($arr_data, $arr_data2) {
                $str_sql = "UPDATE " . $this->index('user_bank') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
                return $this->db->query($str_sql);
        }
        /*
        * 获取列表
        */
        function get_list($arr_data,$int_page=1,$int_page_size=10) {
                $str_where = make_sql($arr_data,'where');
                $str_sql = 'SELECT count(*) AS `total` FROM ' . $this->index('user_bank') .' WHERE '. $str_where;
                $arr_return['total'] = $this->db->get_Value($str_sql);
                $str_sql = 'SELECT * FROM ' . $this->index('user_bank') .' WHERE '. $str_where .$this->page_start($int_page,$int_page_size);
                $arr_return['list'] = $this->db->select($str_sql);
                return $arr_return;
        }
        
        
}

?>