<?php

!defined('LEM') && exit('Forbidden');

class LEM_money_log extends mysqlDBA {
        function get_type_content($type) {  
                if ($type==1) {
                        return array(
                                '10' => '动态评分扣费',
				'11' => '无效好评返回扣费',
                                '20' => '等待卖家发货',
                                '30' => '中差评扣费',
                                '40' => '修改退款理由扣费',
                                '50' => '售前客服（按单）扣费',
                                '55' => '售后客服（按单）扣费',
                                '60' => '售前客服日常扣费',
                                '65' => '售后客服日常扣费',
                                '70' => '余额充值',
                                '80' => '余额提现',
                                '90' => '群发短信扣费',
                                '100' => '短信回访（团队）',
                                '110' => '违规处罚（团队）',
                                '120' => '红包返现扣费（商家）',
                                '121' => '退还现金红包（商家）',
                        );
                }  
                if ($type==2) {
                        return array(
                                '10' => '动态评分获得',
		                '11' => '无效好评扣除佣金',
                                '20' => '等待卖家发货',
                                '30' => '中差评获得',
                                '40' => '修改退款理由获得',
                                '50' => '售前客服（按单）获得',
                                '55' => '售后客服（按单）获得',
                                '60' => '售前客服日常获得',
                                '65' => '售后客服日常获得',
                                '70' => '余额充值',
                                '80' => '余额提现',
                                '90' => '群发短信扣费',
                                '100' => '短信回访扣费',
                                '110' => '违规处罚扣费',
                        );
                }  
                if ($type==3) {
                        return array(
                                '10' => '动态评分获得',
				'11' => '无效好评扣除佣金',
                                '20' => '等待卖家发货',
                                '30' => '中差评获得',
                                '40' => '修改退款理由获得',
                                '50' => '售前客服（按单）获得',
                                '55' => '售后客服（按单）获得',
                                '60' => '售前客服日常获得',
                                '65' => '售后客服日常获得',
                                '70' => '余额充值',
                                '80' => '余额提现',
                                '90' => '群发短信扣费',
                                '100' => '短信回访',
                                '110' => '违规处罚扣费',
                        );
                }  
        }
        /*
        * 获取详细列表
        */
        function get_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`in_date` DESC') {
                $str_where = make_sql($arr_data,'where');
                $str_sql = 'SELECT count(*) AS `total` FROM ' . $this->index('money_log') .' WHERE '. $str_where;
                $arr_return['total'] = $this->db->get_Value($str_sql);
                $str_sql = 'SELECT * FROM ' . $this->index('money_log') .' WHERE '. $str_where .' ORDER BY'.$str_orderby.$this->page_start($int_page,$int_page_size);
                $arr_return['list'] = $this->db->select($str_sql);
                return $arr_return;
        }

        function get_list_b($arr_data){
            $str_where = make_sql($arr_data,'where');
            $str_sql = "SELECT FROM_UNIXTIME( `in_date` , '%Y-%m' ) AS in_date_b, GROUP_CONCAT(id) AS id FROM " . $this->index('money_log') ." WHERE ". $str_where." GROUP BY in_date_b ORDER BY in_date_b DESC";
            $arr_return = $this->db->select($str_sql);
            return $arr_return;
        }


        /*
        * 添加信息
        */
        function insert_money_log($arr_data,$bool_only_return_sql=false){
                $str_sql = "INSERT INTO " . $this->index('money_log') . make_sql($arr_data, 'insert');
                if($bool_only_return_sql){
                        return $str_sql;
                }
                return $this->db->query($str_sql);
        }

        function get_one($arr_data){
                $str_sql = "SELECT * FROM " . $this->index('money_log') . " WHERE " . make_sql($arr_data,'where');
                return $this->db->get_one($str_sql);
        }

        function get_sum($arr_data){
                $str_where = make_sql($arr_data,'where');
                $str_sql = 'SELECT SUM(`money`) AS `total_money` FROM ' . $this->index('money_log') .' WHERE '. $str_where;
                return  $this->db->get_Value($str_sql);
        }

        function update($arr_data,$arr_data2,$bool_only_return_sql=false) {
        $str_sql = "UPDATE " . $this->index('money_log') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        if($bool_only_return_sql){
                        return $str_sql;
                }
        return $this->db->query($str_sql);
    }
        
}

?>