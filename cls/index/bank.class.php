<?php

!defined('LEM') && exit('Forbidden');

class LEM_bank extends mysqlDBA {
        
        function get_one($int_id) {
                $str_sql = 'SELECT * FROM ' . $this->index('bank') .' WHERE `id`='.$int_id;
                return $this->db->get_one($str_sql);
        }
        /*
        * 获取列表
        */
        function get_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`sort` ASC,`id` ASC') {
                $str_where = make_sql($arr_data,'where');
                $str_sql = 'SELECT count(*) AS `total` FROM ' . $this->index('bank') .' WHERE '. $str_where;
                $arr_return['total'] = $this->db->get_Value($str_sql);
                $str_sql = 'SELECT * FROM ' . $this->index('bank') .' WHERE '. $str_where .' ORDER BY'.$str_orderby.$this->page_start($int_page,$int_page_size);
                $arr_return['list'] = $this->db->select($str_sql);
                return $arr_return;
        }
        
        
}

?>