<?php

!defined('LEM') && exit('Forbidden');

class LEM_commission extends mysqlDBA {
		
	function send_commission($int_order_id){
		global $_SGLOBAL;
		$obj_order = L::loadClass('order','index');
		$arr_order = $obj_order->get_one($int_order_id);
		if($arr_order['main']['status']<LEM_order::ORDER_PAY){
			return false;	
		}
		$obj_item = L::loadClass('item','index');
		$obj_member = L::loadClass('member','index');
		$arr_item = array();
		$arr_commission = array();
		foreach($arr_order['item'] as $v){
			$arr_temp = $obj_item->get_one_main($v['item_id']);
			//$arr_commission[0] += number_format($v['price']*$v['num']*$arr_temp['commission']/100, 2, '.', '');
			$arr_commission[0] += number_format($v['price']*$v['num']*$arr_temp['commission_1']/100, 2, '.', '');
			$arr_commission[1] += number_format($v['price']*$v['num']*$arr_temp['commission_2']/100, 2, '.', '');
			$arr_commission[2] += number_format($v['price']*$v['num']*$arr_temp['commission_3']/100, 2, '.', '');
		}
		$int_uid = $arr_order['main']['uid'];

		$arr_member = array();
		$arr_temp = $obj_member->get_list(array('uid'=>$int_uid),1,1);
		$arr_temp = $arr_temp['list'][0];
		$arr_member[] = $arr_temp['puid'];
		if($arr_temp['puid']>0){
			$arr_temp = $obj_member->get_list(array('uid'=>$arr_temp['puid']),1,1);	
			$arr_temp = $arr_temp['list'][0];
			$arr_member[] = $arr_temp['puid'];
		}
		if($arr_temp['puid']>0){
			$arr_temp = $obj_member->get_list(array('uid'=>$arr_temp['puid']),1,1);
			$arr_temp = $arr_temp['list'][0];
			$arr_member[] = $arr_temp['puid'];
		}
		$arr_data = array(
						'uid'=>$int_uid,
						'from_uid'=>$int_uid,
						'order_id'=>$int_order_id,
						'is_pay'=>0,
						'in_date'=>$_SGLOBAL['timestamp']
					);
		//var_export($arr_member);
		foreach($arr_member as $k=>$v){
			$arr_data['uid'] = $v;
			//$arr_data['from_uid'] = (($k-1)<0) ? $int_uid : $arr_member[$k-1]; 
			$arr_data['price'] = $arr_commission[$k];
			$this->insert($arr_data);
		}
	}
	
	function get_list($arr_data,$int_page=1,$int_page_size=10,$bool_list=true,$str_orderby='`id` DESC',$bool_only_total=false,$str_fileds='*') {
		$str_where = make_sql($arr_data,'where');
		$str_where && $str_where = ' WHERE '.$str_where;
		$str_sum = '';
		$str_group_by = '';
		if(!$bool_list){
			$str_group_by = ' GROUP BY `uid`';
			$str_sum = ',SUM(`price`) AS `total_price`';
		}
        $arr_return = array();
		if($str_group_by){
			$str_sql = "SELECT COUNT(DISTINCT `uid`) FROM " . $this->index('commission').$str_where;	
		}
		else{
			$str_sql = "SELECT COUNT(`id`) FROM " . $this->index('commission').$str_where;	
		}
		$arr_return['total'] = $this->db->get_value($str_sql);
		if($bool_only_total){
			return $arr_return['total'];
		}
		$str_sql = 'SELECT '.$str_fileds.$str_sum.' FROM ' . $this->index('commission') . $str_where .$str_group_by.' ORDER BY '.$str_orderby.$this->page_start($int_page,$int_page_size);
		$arr_return['list'] = $this->db->select($str_sql);
        return $arr_return;
    }
	
	function insert($arr_data) {
        $sql = "INSERT INTO " . $this->index('commission') . make_sql($arr_data, 'insert');
        $this->db->query($sql);
		return  $this->db->insert_id();
    }
	
	function update($arr_data, $arr_data2) {
        $str_sql = "UPDATE " . $this->index('commission') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }
}

?>