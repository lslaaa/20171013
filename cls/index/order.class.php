<?php

!defined('LEM') && exit('Forbidden');

class LEM_order extends mysqlDBA {
   
   const ORDER_CANCEL = 90;
   const ORDER_NO_PAY = 100;
   const ORDER_PAY    = 105;
   const ORDER_NOT    = 304;
   const ORDER_CONFIRM= 110;
   const ORDER_SUCCESS= 200;
   const ORDER_FAIL   = 404;
   

    /*
	获取单个订单信息
	*/
	function get_one($int_order_id){
		$arr_return = array();
		$obj_item = L::loadClass('item','index');
                $obj_shop = L::loadClass('shop','index');
                $obj_member = L::loadClass('member','index');
                $obj_member_taobao = L::loadClass('member_taobao','index');
                //var_export($arr_sku);exit;
                // $arr_return['list'][$k]['detail'] = $this->get_one_detail($v['order_id']);
                $arr_order_one = $this->get_one_main($int_order_id);
                $arr_return['main']         = $arr_order_one;
                $arr_return['item']         = $obj_item->get_one_main($arr_order_one['item_id']);
                $arr_return['item_detail']  = $obj_item->get_one_detail($arr_order_one['item_id']);
                $arr_return['buyer']        = $obj_member->get_one_member($arr_order_one['uid']);
                $arr_return['buyer_detail'] = $obj_member->get_one_member_detail($arr_order_one['uid']);
                $arr_return['shop']         = $obj_shop->get_one_main($arr_order_one['sid']);
                $arr_return['taobao']       = $obj_member_taobao->get_one(array('uid'=>$arr_order_one['uid']));
		return $arr_return;
	}

    /*
      获取单个订单主信息
     */

    function get_one_main($int_order_id) {
        $str_sql = "SELECT * FROM " . $this->index('order') . " WHERE `order_id`=$int_order_id";
        $arr_return = $this->db->get_one($str_sql);
        if (empty($arr_return)) {
            return false;
        }
        return $arr_return;
    }
	
	/*
      获取单个订单详细信息
     */

    function get_one_detail($int_order_id) {
        $str_sql = "SELECT * FROM " . $this->index('order_detail') . " WHERE `order_id`=$int_order_id";
        $arr_return = $this->db->get_one($str_sql);
        if (empty($arr_return)) {
            return false;
        }
        return $arr_return;
    }
	
	/*
      获取单个所有商品
     */

    function get_one_item($int_order_id) {
        $str_sql = "SELECT * FROM " . $this->index('order_item') . " WHERE `order_id`=$int_order_id";
        return $this->db->select($str_sql);
    }
	
	/*
	更新订单主信息
	*/
	function update_main($arr_data,$arr_data2,$bool_only_sql=false) {
         $str_sql = "UPDATE " . $this->index('order') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
         if ($bool_only_sql) {
             return $str_sql;
         }
        return $this->db->query($str_sql);
    }
	
	/*
	更新订单详细
	*/
	function update_detail($arr_data,$arr_data2) {
         $str_sql = "UPDATE " . $this->index('order_detail') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }
	
	/*
     * 获取所有订单
	 *@$arr_data where数组 只能是等于的条件
	 *@$int_page当前页码
	 *@$int_page_size每个个数
	 *@$str_orderby排序规则
	 *@$bool_only_total是否只返回总数
     */
    function get_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`order_id` DESC',$bool_only_total=false) {
		$str_where = make_sql($arr_data,'where');
		$str_where && $str_where = ' WHERE '.$str_where;
		
                $arr_return = array();
		$str_sql = "SELECT COUNT(`order_id`) FROM " . $this->index('order').$str_where;
		$arr_return['total'] = $this->db->get_value($str_sql);
		if($bool_only_total){
			return $arr_return['total'];
		}
		$str_sql = 'SELECT * FROM ' . $this->index('order') . $str_where .' ORDER BY '.$str_orderby.$this->page_start($int_page,$int_page_size);
		$arr_return['list'] = $this->db->select($str_sql);
                $obj_item = L::loadClass('item','index');
		$obj_shop = L::loadClass('shop','index');
                $obj_member = L::loadClass('member','index');
		$obj_member_taobao = L::loadClass('member_taobao','index');
		//var_export($arr_sku);exit;
		foreach($arr_return['list'] as $k=>$v){
			// $arr_return['list'][$k]['detail'] = $this->get_one_detail($v['order_id']);
			$arr_return['list'][$k]['item']   = $obj_item->get_one_main($v['item_id']);
			$arr_return['list'][$k]['buyer']  = $obj_member->get_one_member($v['uid']);
                        $arr_return['list'][$k]['shop']   = $obj_shop->get_one_main($v['sid']);
                        $arr_return['list'][$k]['taobao']   = $obj_member_taobao->get_one(array('uid'=>$v['uid']));
			
		}
        return $arr_return;
    }
	
	function get_list_b($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`order_id` DESC',$bool_only_total=false) {
		$str_where = make_sql($arr_data,'where');
		$str_where && $str_where = ' WHERE '.$str_where;
		
        $arr_return = array();
		$str_sql = "SELECT COUNT(`order_id`) FROM " . $this->index('order_detail').$str_where;
		$arr_return['total'] = $this->db->get_value($str_sql);
		if($bool_only_total){
			return $arr_return['total'];
		}
		$str_sql = 'SELECT * FROM ' . $this->index('order_detail') . $str_where .' ORDER BY '.$str_orderby.$this->page_start($int_page,$int_page_size);
		$arr_return['list'] = $this->db->select($str_sql);
        return $arr_return;
    }
	/*返回订单状态名称*/
	static function get_status($int_status){
		global $_SGLOBAL;
                // var_export($int_status);
		$arr_language = $_SGLOBAL['language'];
		if($int_status==self::ORDER_CANCEL){
			return '已取消';
		}
                elseif($int_status==self::ORDER_NO_PAY){
                        return '待审核';
		}
                elseif($int_status==self::ORDER_PAY){
			return '待完成';	
		}
                elseif($int_status==self::ORDER_NOT){
                        return '接单审核失败';   
                }
                elseif($int_status==self::ORDER_CONFIRM){
                        return '待确认';   
                }
                elseif($int_status==self::ORDER_SUCCESS){
			return '已完成';	
		}
                elseif($int_status==self::ORDER_FAIL){
                        return '审核失败';   
                }
	}
	
	function get_express($str_arr_key=''){
		$str_sql = "SELECT * FROM " . $this->index('express') . " WHERE `is_del`=0";
		if(empty($str_arr_key)){
			return $this->db->select($str_sql);		
		}
        return $this->db->select($str_sql,$str_arr_key);	
	}
	
	/*
        * 添加信息
        */
        function insert($arr_data,$bool_only_sql=false) {
                $sql = "INSERT INTO " . $this->index('order') . make_sql($arr_data, 'insert');
                if ($bool_only_sql) {
                        return $sql;
                }
                $this->db->query($sql);
        	return $this->db->insert_id();
        }
	
	function insert_detail($arr_data) {
        $sql = "INSERT INTO " . $this->index('order_detail') . make_sql($arr_data, 'insert');
        return $this->db->query($sql);
    }
	
	function insert_item($arr_data) {
        $sql = "INSERT INTO " . $this->index('order_item') . make_sql($arr_data, 'insert');
        return $this->db->query($sql);
    }
	
	function make_alipay_form($arr_order) {
        $obj_alipay = L::loadClass('alipay', 'payment');
        $arr_data = array(
            "return_url" => trim($obj_alipay->get_configure_value('return_url')), //WWW_URL . '/mall/?mod=cart&extra=pay_return', //必填            
            "notify_url" => trim($obj_alipay->get_configure_value('notify_url')), //WWW_URL . '/third_pay_return/shopping_notify', //必填
            "service" => "create_direct_pay_by_user", //即时付款            
            "payment_type" => 1, //
            "seller_email" => 'szxyking@163.com',
            "out_trade_no" => 'b' . $arr_order['order_id'], //订单号//必填
            "subject" => "逸基因商城购物", //订单名称//必填
            "total_fee" => number_format($arr_order['total_price'], 2, '.', ''), //订单总金额//必填
        );
		//var_export($arr_data);exit;
        if (isset($arr_order['paymethod']) && isset($arr_order['defaultbank']) && $arr_order['paymethod'] === "bankPay" && $arr_order['defaultbank'] != "") {

            $arr_data['paymethod'] = "bankPay";
            $arr_data['defaultbank'] = $arr_order['defaultbank'];
        }

        $str_pay_html = $obj_alipay->make_form($arr_data, true);
        return $str_pay_html;
    }
}

?>