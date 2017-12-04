<?php

!defined('LEM') && exit('Forbidden');

class LEM_item extends mysqlDBA {
	
	const ACTIVE = 200;//上架
	const INACTIVE = 100;//下架
	
	/*
	获取单个商品信息
	*/
	function get_one($int_item_id){
		$arr_return = array();
		$arr_return['main']   = $this->get_one_main($int_item_id);
		$arr_return['detail'] = $this->get_one_detail($int_item_id);
		$arr_return['sku']    = $this->get_one_item_sku($int_item_id);
		return $arr_return;
	}

        /*
        获取单个商品信息模板
        */
        function get_one_temp($int_item_id){
                $arr_return = array();
                $arr_return['main']   = $this->get_one_main_temp($int_item_id);
                $arr_return['detail'] = $this->get_one_detail_temp($int_item_id);
                return $arr_return;
        }

    /*
      获取单个商品主信息
     */

    function get_one_main($int_item_id) {
        $str_sql = "SELECT * FROM " . $this->index('item') . " WHERE `item_id`=$int_item_id";
        $arr_return = $this->db->get_one($str_sql);
        if (empty($arr_return)) {
            return false;
        }
        return $arr_return;
    }


	
	/*
      获取单个商品详细信息
     */

    function get_one_detail($int_item_id) {
        $str_sql = "SELECT * FROM " . $this->index('item_detail') . " WHERE `item_id`=$int_item_id";
        $arr_return = $this->db->get_one($str_sql);
        if (empty($arr_return)) {
            return false;
        }
        return $arr_return;
    }

     /*
      获取单个商品主信息模板
     */

    function get_one_main_temp($int_item_id) {
        $str_sql = "SELECT * FROM " . $this->index('item_temp') . " WHERE `item_id`=$int_item_id";
        $arr_return = $this->db->get_one($str_sql);
        if (empty($arr_return)) {
            return false;
        }
        return $arr_return;
    }

    /*
      获取单个商品详细信息模板
     */

    function get_one_detail_temp($int_item_id) {
        $str_sql = "SELECT * FROM " . $this->index('item_detail_temp') . " WHERE `item_id`=$int_item_id";
        $arr_return = $this->db->get_one($str_sql);
        if (empty($arr_return)) {
            return false;
        }
        return $arr_return;
    }
	
	/*
      获取单个商品所有规格信息
     */

    function get_one_item_sku($int_item_id) {
        $str_sql = "SELECT * FROM " . $this->index('item_sku') . " WHERE `item_id`=$int_item_id AND `is_del`=0";
        return $this->db->select($str_sql);
    }
	
	function get_one_item_sku_b($arr_data) {
        $str_sql = "SELECT * FROM " . $this->index('item_sku') . " WHERE ".make_sql($arr_data, 'where');
        return $this->db->select($str_sql);
    }

    /*
      更新商品主信息
     */

    function update_main($arr_data, $arr_data2,$bool_only_sql=false) {
        $str_sql = "UPDATE " . $this->index('item') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        if ($bool_only_sql) {
                return $str_sql;
        }
        return $this->db->query($str_sql);
    }
	
	/*
      更新商品详细信息
     */

    function update_detail($arr_data, $arr_data2) {
        $str_sql = "UPDATE " . $this->index('item_detail') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }

  
    /*
     * 添加商品主信息模板
     */

    function insert_main_temp($arr_data) {
        $sql = "INSERT INTO " . $this->index('item_temp') . make_sql($arr_data, 'insert');
        $this->db->query($sql);
		return  $this->db->insert_id();
    }
	
	/*
     * 添加商品详细信息模板
     */

    function insert_detail_temp($arr_data) {
        $sql = "INSERT INTO " . $this->index('item_detail_temp') . make_sql($arr_data, 'insert');
        return $this->db->query($sql);
    }



        /*
      更新商品主信息模板
     */

    function update_main_temp($arr_data, $arr_data2,$bool_only_sql=false) {
        $str_sql = "UPDATE " . $this->index('item_temp') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        if ($bool_only_sql) {
                return $str_sql;
        }
        return $this->db->query($str_sql);
    }
        
        /*
      更新商品详细信息模板
     */

    function update_detail_temp($arr_data, $arr_data2) {
        $str_sql = "UPDATE " . $this->index('item_detail_temp') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        return $this->db->query($str_sql);
    }

  
    /*
     * 添加商品主信息
     */

    function insert_main($arr_data) {
        $sql = "INSERT INTO " . $this->index('item') . make_sql($arr_data, 'insert');
        $this->db->query($sql);
                return  $this->db->insert_id();
    }
        
        /*
     * 添加商品详细信息
     */

    function insert_detail($arr_data) {
        $sql = "INSERT INTO " . $this->index('item_detail') . make_sql($arr_data, 'insert');
        return $this->db->query($sql);
    }
	
	/*
      更新商品规格
     */

    function update_sku_price($arr_data, $arr_data2,$bool_only_sql=false) {
        $str_sql = "UPDATE " . $this->index('item_sku_price') . " SET " . make_sql($arr_data2, 'update') . " WHERE " . make_sql($arr_data, 'where');
        if ($bool_only_sql) {
                return $str_sql;
        }
        return $this->db->query($str_sql);
    }
	
	//更新规格表
	public function update_one_item_sku($arr_item_sku_data,$int_item_id){
		$arr_item_skus = $this->db->select("SELECT sku_1,sku_2 FROM " . $this->index("item_sku") . " WHERE `item_id`='{$int_item_id}'");
		//已存在需要更新的sku
		$arr_update_skus = array();
		//新插入的sku,使用sku_name做键名，使用sku_id做键值，这样就可以确保去除重复;
		$arr_new_skus = array();
		foreach($arr_item_sku_data as $v){
			if(strpos($v['sku_1'], '|') !== false && strpos($v['sku_2'], '|') !== false){
				$arr_sku_1 = explode('|', $v['sku_1']);
				$arr_sku_2 = explode('|', $v['sku_2']);
				if($arr_sku_1[0] != 0) $arr_update_skus[$v['sku_1']] = $arr_sku_1;
				if($arr_sku_2[0] != 0) $arr_update_skus[$v['sku_2']] = $arr_sku_2;
				//检查是否有删除的，同时检查是否有还未插入的;
				$bool_have_flag = false;
				foreach($arr_item_skus as $a =>$j){
					//把数据库已存在的去掉，剩下的就是已被删除的
					if($arr_sku_1[0] == $j['sku_1'] && $arr_sku_2[0] == $j['sku_2']){
						unset($arr_item_skus[$a]);
						//把$bool_have_flag 标记为true;
						$bool_have_flag = true;
					}
				}
				$v['sku_1'] = $arr_sku_1[0];
				$v['sku_2'] = $arr_sku_2[0];
				//如果当前数据库不存在这一条，则插入;
				if(!$bool_have_flag){
					$str_sql = "INSERT INTO " . $this->index("item_sku") . make_sql($v, 'insert');
					$this->db->query($str_sql);
				}else{
					//更新
					$str_sql = "UPDATE " . $this->index("item_sku") . " SET " .make_sql($v, 'update') . " WHERE `item_id`='{$v['item_id']}' AND `sku_1`='{$arr_sku_1[0]}' AND `sku_2`='{$arr_sku_2[0]}'";
					$this->db->query($str_sql);
				}
			}else{
				if(strpos($v['sku_1'], '|') === false){
					$int_sku_1_id = $arr_new_skus[$v['sku_1']];
					if(!$int_sku_1_id){
						$int_sku_1_id = $this->make_sku_id($v['sku_1']);
						$arr_new_skus[$v['sku_1']] = $int_sku_1_id;
					}
					$v['sku_1'] = $int_sku_1_id;
				}else{
					$arr_sku_1 = explode('|', $v['sku_1']);
					$v['sku_1'] = $arr_sku_1[0];
					if($arr_sku_1[0] != 0) $arr_update_skus[$v['sku_1']] = $arr_sku_1;
				}
				if(strpos($v['sku_2'], '|') === false){
					$int_sku_2_id = $arr_new_skus[$v['sku_2']];
					if(!$int_sku_2_id){
						$int_sku_2_id = $this->make_sku_id($v['sku_2']);
						$arr_new_skus[$v['sku_2']] = $int_sku_2_id;
					}
					$v['sku_2'] = $int_sku_2_id;
				}else{
					$arr_sku_2 = explode('|', $v['sku_2']);
					$v['sku_2'] = $arr_sku_2[0];
					if($arr_sku_2[0] != 0) $arr_update_skus[$v['sku_2']] = $arr_sku_2;
				}
				//插入数据
				$str_sql = "INSERT INTO " . $this->index("item_sku") . make_sql($v, 'insert');
				$this->db->query($str_sql);
			}
		}
		//如果有已经删除的项，则删除;
		if(!empty($arr_item_skus)){
			foreach($arr_item_skus as $k){
				$str_sql = "DELETE FROM " . $this->index("item_sku") . " WHERE `item_id`='{$int_item_id}' AND `sku_1`='{$k['sku_1']}' AND `sku_2`='{$k['sku_2']}'";
				$bool_flag = $this->db->query($str_sql);
			}
		}
		//更新sku名称
		if(!empty($arr_update_skus)){
			foreach($arr_update_skus as $v){
				$this->db->query("UPDATE " . $this->index('item_sku_list') . ' SET `name`=\''.$v[1].'\' WHERE `id`='.intval($v[0]));
			}
		}
	}

	//制造一个sku_id
	private function make_sku_id($str_sku){
		$arr_data = array();
		$arr_data['name'] = $str_sku;
		$this->db->query("INSERT INTO " . $this->index('item_sku_list') . make_sql($arr_data,'insert'));
		$int_sku_id = $this->db->insert_id();
		return $int_sku_id;
	}
	
	//获取一个sku;
	public function get_one_sku($int_sku_id){
		return $this->db->get_one("SELECT * FROM " . $this->index('item_sku_list') . ' WHERE `id`='.$int_sku_id);
	}

    /*
     * 获取所有商品
	 *@$arr_data where数组 只能是等于的条件
	 *@$int_page当前页码
	 *@$int_page_size每个个数
	 *@$str_orderby排序规则
	 *@$bool_only_total是否只返回总数
     */
    function get_list($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`sort` ASC',$bool_only_total=false,$str_fileds='*') {
		$str_where = make_sql($arr_data,'where');
		$str_where && $str_where = ' WHERE '.$str_where;
		
        $arr_return = array();
		$str_sql = "SELECT COUNT(`item_id`) FROM " . $this->index('item').$str_where;
		$arr_return['total'] = $this->db->get_value($str_sql);
		if($bool_only_total){
			return $arr_return['total'];
		}
		$str_sql = 'SELECT '.$str_fileds.' FROM ' . $this->index('item') . $str_where .' ORDER BY '.$str_orderby.$this->page_start($int_page,$int_page_size);
		$arr_return['list'] = $this->db->select($str_sql);
        return $arr_return;
    }


    /*
     * 获取所有商品
         *@$arr_data where数组 只能是等于的条件
         *@$int_page当前页码
         *@$int_page_size每个个数
         *@$str_orderby排序规则
         *@$bool_only_total是否只返回总数
     */
    function get_list_temp($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`sort` ASC',$bool_only_total=false,$str_fileds='*') {
                $str_where = make_sql($arr_data,'where');
                $str_where && $str_where = ' WHERE '.$str_where;
                
                $arr_return = array();
                $str_sql = "SELECT COUNT(`item_id`) FROM " . $this->index('item_temp').$str_where;
                $arr_return['total'] = $this->db->get_value($str_sql);
                if($bool_only_total){
                        return $arr_return['total'];
                }
                $str_sql = 'SELECT '.$str_fileds.' FROM ' . $this->index('item_temp') . $str_where .' ORDER BY '.$str_orderby.$this->page_start($int_page,$int_page_size);
                $arr_return['list'] = $this->db->select($str_sql);
        return $arr_return;
    }
	
	function get_list_b($arr_data,$int_page=1,$int_page_size=10,$str_orderby='`in_date` DESC'){
		$str_where = make_sql($arr_data,'where');
		$str_where && $str_where = ' WHERE '.$str_where;
		
        $arr_return = array();
		$str_sql = "SELECT COUNT(DISTINCT `item_id`) FROM " . $this->index('item_sku').$str_where;
		
		$arr_return['total'] = $this->db->get_value($str_sql);

		$str_sql = 'SELECT DISTINCT(`item_id`) FROM ' . $this->index('item_sku') . $str_where .' ORDER BY '.$str_orderby.$this->page_start($int_page,$int_page_size);
		$arr_return['list'] = $this->db->select($str_sql);
        return $arr_return;	
	}
	
	function get_list_sku(){
		$str_sql = 'SELECT * FROM ' . $this->index('sku');
		return $this->db->select($str_sql,'sku_id');	
	}

        function get_one_sku_price($arr_data){
                $str_where = make_sql($arr_data,'where');
                $str_where && $str_where = ' WHERE '.$str_where;
                $str_sql = 'SELECT * FROM ' . $this->index('item_sku_price').$str_where;;
                return $this->db->get_one($str_sql);    
        }

            function get_price_name(){
            $arr_price = array(
                    'type_10'=>'淘宝/天猫 基础佣金' ,
                    'type_20'=>'拼多多 基础佣金' ,
                    'type_30'=>'蘑菇街/美丽说 基础佣金' ,
                    'type_40'=>'京东 基础佣金' ,
                    'type_50'=>'其他基础佣金' ,
                    'collect_0'=>'收藏加后立即付款' ,
                    'collect_1'=>'收藏加后1小时后付款' ,
                    'collect_2'=>'收藏加后12小时后付款' ,
                    'collect_3'=>'收藏加后24小时后付款' ,
                    'collect_4'=>'收藏加后48小时后付款' ,
                    'level_1'=>'旺旺等级随机' ,
                    'level_2'=>'旺旺等级2心' ,
                    'level_3'=>'旺旺等级3心' ,
                    'level_4'=>'旺旺等级4心' ,
                    'level_5'=>'旺旺等级5心' ,
                    'level_6'=>'旺旺等级1钻以上' ,
                    'reg_time_1'=>'旺旺注册时长随机' ,
                    'reg_time_2'=>'旺旺注册时长6个月以上' ,
                    'reg_time_3'=>'旺旺注册时长12个月以上' ,
                    'sex_1'=>'男' ,
                    'sex_2'=>'女' ,
                    'province'=>'省份要求' ,
                    'price_1'=>'客单价0－100元' ,
                    'price_2'=>'客单价101－200元' ,
                    'price_3'=>'客单价201－300元' ,
                    'add_price'=>'客单每增加100元' ,
                    'age_1'=>'年龄要求18岁以下' ,
                    'age_2'=>'年龄要求19－24岁' ,
                    'age_3'=>'年龄要求25－29岁' ,
                    'age_4'=>'年龄要求30－34岁' ,
                    'age_5'=>'年龄要求35－39岁' ,
                    'age_6'=>'年龄要求40－49岁' ,
                    'age_7'=>'年龄要求50岁以上' ,
                    'comment_1'=>'评语自由发挥' ,
                    'comment_2'=>'指定评语' ,
                    'pic_1'=>'买家提供买家秀' ,
                    'pic_2'=>'指定买家秀' ,
                    'video_1'=>'买家提供视频秀' ,
                    'video_2'=>'指定视频秀' ,
                    'zhitongche'=>'直通车点击' ,
                    'collect_no_pay'=>'收藏加购不付款' ,
                    'local'=>'位置距离' ,
                    'huobi_1'=>'指定1家货比' ,
                    'huobi_2'=>'指定2家货比' ,
                    'huobi_3'=>'指定3家货比' ,
                    'height'=>'身高' ,
                    'weight'=>'体重' ,
                    'priority'=>'优先匹配' ,
                    'confirm_1'=>'发货后立即收货' ,
                    'confirm_2'=>'24小时后收货' ,
                    'confirm_3'=>'48小时后收货' ,
                    'confirm_4'=>'72小时后收货' ,
            );
            return $arr_price;
    }
}

?>