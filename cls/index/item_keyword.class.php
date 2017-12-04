<?php

!defined('LEM') && exit('Forbidden');

class LEM_item_keyword extends mysqlDBA {


    function get_one($arr_data){
                $str_sql = "SELECT * FROM " . $this->index('item_keyword') . " WHERE " . make_sql($arr_data, 'where');
                return $this->db->get_one($str_sql);
    }

    /*
     * 添加信息
     */
    function insert($arr_data) {
                $sql = "INSERT INTO " . $this->index('item_keyword') . make_sql($arr_data, 'insert');
                $this->db->query($sql);
                return $this->db->insert_id();
    }


    /*
     * 更新信息
     */
    function update($arr_data, $arr_data2,$bool_only_sql=false) {
                $str_sql = "UPDATE " . $this->index('item_keyword') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
                if ($bool_only_sql) {
                        return $str_sql;
                }
                return $this->db->query($str_sql);
    }


    /*
     * 获取详细列表
     */
    function get_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`id` DESC') {
                $str_where = make_sql($arr_data,'where');
                $str_sql = 'SELECT count(*) AS `total` FROM ' . $this->index('item_keyword') .' WHERE '. $str_where;
                $arr_return['total'] = $this->db->get_Value($str_sql);
                $str_sql = 'SELECT * FROM ' . $this->index('item_keyword') .' WHERE '. $str_where .' ORDER BY'.$str_orderby.$this->page_start($int_page,$int_page_size);
                $arr_return['list'] = $this->db->select($str_sql);
                return $arr_return;
    }

    function del($arr_data){
                $str_sql = "DELETE FROM " . $this->index('item_keyword') . " WHERE " . make_sql($arr_data, 'where');
                return $this->db->query($str_sql);

        }

    function get_one_temp($arr_data){
                $str_sql = "SELECT * FROM " . $this->index('item_keyword_temp') . " WHERE " . make_sql($arr_data, 'where');
                return $this->db->get_one($str_sql);
    }

    /*
     * 添加信息
     */
    function insert_temp($arr_data) {
                $sql = "INSERT INTO " . $this->index('item_keyword_temp') . make_sql($arr_data, 'insert');
                $this->db->query($sql);
                return $this->db->insert_id();
    }


    /*
     * 更新信息
     */
    function update_temp($arr_data, $arr_data2) {
                $str_sql = "UPDATE " . $this->index('item_keyword_temp') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
                return $this->db->query($str_sql);
    }


    /*
     * 获取详细列表
     */
    function get_list_temp($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`id` DESC') {
                $str_where = make_sql($arr_data,'where');
                $str_sql = 'SELECT count(*) AS `total` FROM ' . $this->index('item_keyword_temp') .' WHERE '. $str_where;
                $arr_return['total'] = $this->db->get_Value($str_sql);
                $str_sql = 'SELECT * FROM ' . $this->index('item_keyword_temp') .' WHERE '. $str_where .' ORDER BY'.$str_orderby.$this->page_start($int_page,$int_page_size);
                $arr_return['list'] = $this->db->select($str_sql);
                return $arr_return;
    }

    function del_temp($arr_data){
                $str_sql = "DELETE FROM " . $this->index('item_keyword_temp') . " WHERE " . make_sql($arr_data, 'where');
                return $this->db->query($str_sql);

        }


}

?>
