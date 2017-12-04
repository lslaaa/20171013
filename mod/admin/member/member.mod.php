<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_member {
	function __construct() {
		$extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
		if (method_exists($this, 'extra_' . $extra)) {
			$str_function_name = 'extra_' . $extra;
			$this->$str_function_name();
		}
	}

	function extra_index(){
		global $_SGLOBAL;
		$int_page = intval($_GET['page']);
		
		$str_email = trim(str_addslashes($_GET['email']));
		$str_phone = trim(str_addslashes($_GET['phone']));
		$str_realname = trim(str_addslashes($_GET['realname']));
		
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 20;
		$obj_member = L::loadClass('member','index');
		
		$arr_data = array();
		$str_email && $arr_data['email'] = array('do'=>'like','val'=>'%'.$str_email.'%');
		$str_phone && $arr_data['phone'] = array('do'=>'like','val'=>'%'.$str_phone.'%');
		$str_realname && $arr_data['realname'] = array('do'=>'like','val'=>'%'.$str_realname.'%');
		
		$arr_list = $obj_member->get_list($arr_data,$int_page,$int_page_size);
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		
		$int_show_member_group = get_config('show_member_group');
		$int_show_member_group = $int_show_member_group['val'];
		if($int_show_member_group){
			$obj_member_group = L::loadClass('member_group','index');
			$arr_data = array('is_del'=>0);
			$arr_groups = $obj_member_group->get_list($arr_data,1,20);
			$arr_groups = format_array_val_to_key($arr_groups['list'],'gid');
			//var_export($arr_groups);
		}
		
		$arr_language = $_SGLOBAL['language'];
		//var_export($arr_language);
		include template('template/admin/member/index');
	}
	
	function extra_mod(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_uid = intval($_GET['uid']);
		$obj_member = L::loadClass('member','index');
		$obj_district = L::loadClass('district','index');
		$obj_mingzu  = L::loadClass('mingzu','index');
		if(submit_check('formhash')){
			$int_uid = intval($_POST['uid']);
			$str_password = str_addslashes($_POST['password']);
			$int_gid = intval($_POST['gid']);
			$arr_data = array('uid'=>$int_uid);
			$arr_data2 = array(
							   'gid'=>$int_gid
						);
			$str_password && $arr_data2['password'] = $str_password;
			$obj_member->update_member($arr_data, $arr_data2);
			callback(array('info'=>$arr_language['ok'],'status'=>200));
		}
		$arr_member  = $obj_member->get_one($int_uid);
		$arr_member['detail']['sanwei'] && $arr_member['detail']['sanwei'] = unserialize($arr_member['detail']['sanwei']);
		$arr_district_ids = array();
		$arr_district_ids[] = $arr_member['detail']['province'];
		$arr_district_ids[] = $arr_member['detail']['province_2'];
		$arr_district_ids[] = $arr_member['detail']['city'];
		$arr_district_ids[] = $arr_member['detail']['city_2'];
		$arr_district_ids[] = $arr_member['detail']['area'];
		$arr_district_ids = array_filter(array_unique($arr_district_ids));
		$arr_member['detail']['mingzu'] && $arr_mingzu = $obj_mingzu->get_one($arr_member['detail']['mingzu']);
		if($arr_district_ids){
			$arr_data = array('id'=>array('do'=>'in','val'=>implode(',',$arr_district_ids)));
			$arr_district = $obj_district->get_list($arr_data,'id');
		}
		
		$int_show_member_group = get_config('show_member_group');
		$int_show_member_group = $int_show_member_group['val'];
		
		if($int_show_member_group){
			$obj_member_group = L::loadClass('member_group','index');
			$arr_data = array('is_del'=>0);
			$arr_groups = $obj_member_group->get_list($arr_data,1,20);
			//var_export($arr_groups);
		}
		//var_export($arr_mingzu);
		include template('template/admin/member/mod');
	}


	//淘宝账号
	function extra_taobao(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
                $str_taobao = trim(str_addslashes($_GET['taobao']));
		$str_phone = trim(str_addslashes($_GET['phone']));
		
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 20;
                $int_type = intval($_GET['type']);
		$obj_member_taobao = L::loadClass('member_taobao','index');
		$obj_member = L::loadClass('member','index');
		$arr_data = array('is_del'=>0);
                $int_type && $arr_data['type'] = $int_type;
                if ($str_phone) {
                        $arr_member_list = $obj_member->get_list(array('phone'=>$str_phone));
                        if ($arr_member_list['total']>0) {
                                $arr_uids = array();
                                foreach ($arr_member_list['list'] as $k => $v) {
                                        $arr_uids[] = $v['uid'];
                                }
                                $arr_data['uid'] = array('do'=>'in','val'=>implode(',', $arr_uids));
                        }
                }
		if (isset($_GET['is_check'])) {
			$int_is_check = intval($_GET['is_check']);
			$arr_data['is_check'] = intval($_GET['is_check']);
		}
		$arr_member_taobao = $obj_member_taobao->get_list($arr_data);
		foreach ($arr_member_taobao['list'] as $key => $value) {
			$arr_member_one = $obj_member->get_one_member($value['uid']);
			$arr_member_taobao['list'][$key]['member'] = $arr_member_one;
			$arr_member_taobao['list'][$key]['pics'] = explode(',', $value['pics']);
		}
		$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
		$str_num_of_page = numofpage($int_page,ceil($arr_member_taobao['total']/$int_page_size),'?'.$str_query_string);
		// var_export($arr_member_taobao);
		include template('template/admin/member/taobao');
	}
	/*Èý¼¶·ÖÏúÓÃ»§²ã¼¶*/
	function extra_list_2(){
		global $_SGLOBAL;
		$int_province = intval($_GET['province']);
		$int_city     = intval($_GET['city']);
		$int_area     = intval($_GET['area']);
		$int_page = intval($_GET['page']);
		$int_page = $int_page ? $int_page : 1;
		$int_page_size = 50;
		$obj_member = L::loadClass('member','index');
		$arr_data = array();
		$arr_temp = array('total'=>0);
		if($int_province){
			$arr_data['province'] = $int_province;
			$int_city && $arr_data['city'] = $int_city;
			$int_area && $arr_data['area'] = $int_area;
			$arr_temp = $obj_member->get_list_b($arr_data,$int_page,$int_page_size);
			!$arr_temp['list'] && $arr_temp['list'] = array();
			$arr_data = $arr_uid = array();
			foreach($arr_temp['list'] as $v){
				$arr_uid[] = $v['uid'];
			}
			$arr_data['uid'] = array('do'=>'in','val'=>implode(',',$arr_uid));
		}
		if($arr_temp['total']>0 || !$int_province){
			$arr_list = $obj_member->get_list($arr_data,$int_page,$int_page_size);
			$arr_temp['total']>0  && $arr_list['total'] = $arr_temp['total'];
			$str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
			$str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
		}
		
		$arr_language = $_SGLOBAL['language'];
		//var_export($arr_mingzu);
		include template('template/admin/member/list_2');
	}
	
	function extra_get_member_list(){
		global $_SGLOBAL;
		$int_uid = intval($_GET['uid']);
		if(empty($int_uid)){
			return false;	
		}
		$obj_member = L::loadClass('member','index');
		$arr_data = array('puid'=>$int_uid);
		$arr_list = $obj_member->get_list($arr_data,1,200);
		callback(array('info'=>'','status'=>200,'data'=>$arr_list));
	}
	
	function extra_del(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_uid = intval($_GET['uid']);
		if(empty($int_uid)){
			return false;	
		}
		$obj_member = L::loadClass('member','index');
		$arr_member = $obj_member->get_one_member($int_uid);
		
		if($arr_member['is_del']==0){
			$arr_data = array('is_del'=>1);
			$str_info = $arr_language['member']['disable_success'];
		}
		else{
			$arr_data = array('is_del'=>0);
			$str_info = $arr_language['member']['enable_success'];
		}
		$obj_member->update_member(array('uid'=>$int_uid),$arr_data);
		callback(array('info'=>$str_info,'status'=>200));
	}

	function extra_audited_taobao(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_id = intval($_GET['id']);
		if(empty($int_id)){
			return false;	
		}
		$obj_member_taobao = L::loadClass('member_taobao','index');
		$arr_member_taobao = $obj_member_taobao->get_one(array('id'=>$int_id));
		$arr_data = array('is_check'=>1);
		$obj_member_taobao->update(array('id'=>$int_id),$arr_data);
		callback(array('info'=>'审核完成','status'=>200));
	}

	function extra_del_taobao(){
		global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
		$int_id = intval($_GET['id']);
		if(empty($int_id)){
			return false;	
		}
		$obj_member_taobao = L::loadClass('member_taobao','index');
		$arr_member_taobao = $obj_member_taobao->get_one(array('id'=>$int_id));
		$arr_data = array('is_check'=>2);
		$obj_member_taobao->update(array('id'=>$int_id),$arr_data);
		callback(array('info'=>'审核完成','status'=>200));
	}

	function extra_bat_audited_taobao(){
                global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
                $str_ids = str_addslashes($_GET['ids']);
                if(empty($str_ids)){
                    return false;
                }
                $str_ids = substr($str_ids, 0, -1);
                $arr_id = explode(',', $str_ids);
                $obj_member_taobao = L::loadClass('member_taobao','index');
                foreach($arr_id as $int_id){
                    $arr_member_taobao = $obj_member_taobao->get_one(array('id'=>$int_id));		
                    if($arr_member_taobao['is_check']==0){
                            $arr_data = array('is_check'=>1);
                            $obj_member_taobao->update(array('id'=>$int_id),$arr_data);                            
                    }
                                       
                }
                callback(array('info'=>$arr_language['ok_2'],'status'=>200));
        }

        function extra_bat_phy_del_taobao(){
                global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
                $str_ids = str_addslashes($_GET['ids']);
                if(empty($str_ids)){
                    return false;
                }
                $str_ids = substr($str_ids, 0, -1);
                $arr_id = explode(',', $str_ids);
                $obj_member_taobao = L::loadClass('member_taobao','index');
                foreach($arr_id as $int_id){
                    $arr_member_taobao = $obj_member_taobao->get_one(array('id'=>$int_id));		
                    $arr_data = array('is_check'=>2);
                    $obj_member_taobao->update(array('id'=>$int_id),$arr_data);                            
                                       
                }
                callback(array('info'=>$arr_language['ok_2'],'status'=>200));
        }
        
                
        function extra_bat_audited(){
                global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
                $str_uids = str_addslashes($_GET['uids']);
                if(empty($str_uids)){
                    return false;
                }
                $str_uids = substr($str_uids, 0, -1);
                $arr_uid = explode(',', $str_uids);
                $obj_member = L::loadClass('member','index');
                foreach($arr_uid as $int_uid){
                    $arr_member = $obj_member->get_one_member($int_uid);		
                    if($arr_member['audited']==0){
                            $arr_data = array('audited'=>1);
                            $obj_member->update_member(array('uid'=>$int_uid),$arr_data);                            
                    }
                                       
                }
                callback(array('info'=>$arr_language['ok_2'],'status'=>200));
        }
        
        function extra_bat_phy_del(){
                global $_SGLOBAL;
		$arr_language = $_SGLOBAL['language'];
                $str_uids = str_addslashes($_GET['uids']);
                if(empty($str_uids)){
                    return false;
                }
                $str_uids = substr($str_uids, 0, -1);
                $obj_member = L::loadClass('member','index');
                $str_where = '`uid` in ('.$str_uids.')';
                $obj_member->delete_member($str_where);
                callback(array('info'=>$arr_language['ok_2'],'status'=>200));
        }
	
	

}