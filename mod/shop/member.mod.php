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
                $arr_member = $_SGLOBAL['member'];
                $int_page = intval($_GET['page']);
                
                $str_email = trim(str_addslashes($_GET['email']));
                $str_phone = trim(str_addslashes($_GET['phone']));
                $str_realname = trim(str_addslashes($_GET['realname']));
                
                $int_page = $int_page ? $int_page : 1;
                $int_page_size = 20;
                $obj_user = L::loadClass('user','index');
                
                $arr_data = array();
                $str_email && $arr_data['email'] = array('do'=>'like','val'=>'%'.$str_email.'%');
                $str_phone && $arr_data['phone'] = array('do'=>'like','val'=>'%'.$str_phone.'%');
                $str_realname && $arr_data['realname'] = array('do'=>'like','val'=>'%'.$str_realname.'%');
                if ($arr_member['gid']!=1) {
                        $arr_data['uid'] = $arr_member['uid'];
                }else{
                        $arr_data['pid'] = $arr_member['uid'];
                }
                $arr_list = $obj_user->get_list($arr_data,$int_page,$int_page_size);
                $str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
                $str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
                
                $int_show_member_group = get_config('show_member_group');
                $int_show_member_group = $int_show_member_group['val'];
                if($int_show_member_group){
                        $obj_user_group = L::loadClass('user_group','index');
                        $arr_data = array('is_del'=>0);
                        $arr_groups = $obj_user_group->get_list($arr_data,1,20);
                        $arr_groups = format_array_val_to_key($arr_groups['list'],'gid');
                        //var_export($arr_groups);
                }
                
                $arr_language = $_SGLOBAL['language'];
                //var_export($arr_language);
                include template('template/shop/member/index');
        }
        
        function extra_mod(){
                global $_SGLOBAL;
                $arr_language = $_SGLOBAL['language'];
                $arr_member = $_SGLOBAL['member'];
                $int_uid = intval($_GET['uid']);
                $obj_user = L::loadClass('user','index');

                if(submit_check('formhash')){
                        $int_uid      = intval($_POST['uid']);
                        $int_gid      = intval($_POST['gid']);
                        $str_username = str_addslashes($_POST['username']);
                        $str_contacts = str_addslashes($_POST['contacts']);
                        $str_mobile   = str_addslashes($_POST['mobile']);
                        $str_qq       = str_addslashes($_POST['qq']);
                        $str_password = str_addslashes($_POST['password2']);

                        $arr_data = array('uid'=>$int_uid);
                        $arr_data2 = array(
                                   'gid'      => $int_gid,
                                   'username' => $str_username,
                                   'contacts' => $str_contacts,
                                   'mobile'   => $str_mobile,
                                   'qq'       => $str_qq,
                        );
                        $arr_member_check = $obj_user->get_one_b(array('username'=>$str_username));
                        if ($arr_member_check && $int_uid!=$arr_member_check['uid']) {
                                callback(array('info'=>'用户名已存在','status'=>304));
                        }
                        $arr_member['gid']==1 && $arr_data2['pid'] = $arr_member['uid'];
                        $str_password && $arr_data2['password'] = $str_password;
                        $str_password && $arr_data2['password2'] = $str_password;
                        if ($int_uid) {
                                $boole_result = $obj_user->update_member($arr_data, $arr_data2);
                                callback(array('info'=>$arr_language['ok'],'status'=>200));
                        }else{
                                $result = $obj_user->_insert($arr_data2);
                                callback(array('info'=>'添加成功','status'=>200));
                        }
                        
                }
                $arr_member_one  = $obj_user->get_one($int_uid);
                // var_export($arr_member_one);
                $obj_user_group = L::loadClass('user_group','index');
                $arr_data = array('is_del'=>0);
                $arr_groups = $obj_user_group->get_list($arr_data,1,20);
                //var_export($arr_groups);
                //var_export($arr_mingzu);
                include template('template/shop/member/mod');
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
                $obj_user = L::loadClass('user','index');
                $arr_data = array();
                $arr_temp = array('total'=>0);
                if($int_province){
                        $arr_data['province'] = $int_province;
                        $int_city && $arr_data['city'] = $int_city;
                        $int_area && $arr_data['area'] = $int_area;
                        $arr_temp = $obj_user->get_list_b($arr_data,$int_page,$int_page_size);
                        !$arr_temp['list'] && $arr_temp['list'] = array();
                        $arr_data = $arr_uid = array();
                        foreach($arr_temp['list'] as $v){
                                $arr_uid[] = $v['uid'];
                        }
                        $arr_data['uid'] = array('do'=>'in','val'=>implode(',',$arr_uid));
                }
                if($arr_temp['total']>0 || !$int_province){
                        $arr_list = $obj_user->get_list($arr_data,$int_page,$int_page_size);
                        $arr_temp['total']>0  && $arr_list['total'] = $arr_temp['total'];
                        $str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
                        $str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
                }
                
                $arr_language = $_SGLOBAL['language'];
                //var_export($arr_mingzu);
                include template('template/shop/member/list_2');
        }
        
        function extra_get_member_list(){
                global $_SGLOBAL;
                $int_uid = intval($_GET['uid']);
                if(empty($int_uid)){
                        return false;   
                }
                $obj_user = L::loadClass('user','index');
                $arr_data = array('puid'=>$int_uid);
                $arr_list = $obj_user->get_list($arr_data,1,200);
                callback(array('info'=>'','status'=>200,'data'=>$arr_list));
        }
        
        function extra_del(){
                global $_SGLOBAL;
                $arr_language = $_SGLOBAL['language'];
                $int_uid = intval($_GET['uid']);
                if(empty($int_uid)){
                        return false;   
                }
                $obj_user = L::loadClass('user','index');
                $arr_member = $obj_user->get_one_member($int_uid);
                
                if($arr_member['is_del']==0){
                        $arr_data = array('is_del'=>1);
                        $str_info = $arr_language['member']['disable_success'];
                }
                else{
                        $arr_data = array('is_del'=>0);
                        $str_info = $arr_language['member']['enable_success'];
                }
                $obj_user->update_member(array('uid'=>$int_uid),$arr_data);
                callback(array('info'=>$str_info,'status'=>200));
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
                $obj_user = L::loadClass('user','index');
                foreach($arr_uid as $int_uid){
                    $arr_member = $obj_user->get_one_member($int_uid);                
                    if($arr_member['audited']==0){
                            $arr_data = array('audited'=>1);
                            $obj_user->update_member(array('uid'=>$int_uid),$arr_data);                            
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
                $obj_user = L::loadClass('user','index');
                $str_where = '`uid` in ('.$str_uids.')';
                $obj_user->delete_member($str_where);
                callback(array('info'=>$arr_language['ok_2'],'status'=>200));
        }
        
        

}