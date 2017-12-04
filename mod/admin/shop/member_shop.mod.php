<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_member_shop {
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
                
                $str_username = str_addslashes($_GET['username']);
                $str_mobile = str_addslashes($_GET['mobile']);
                $int_page = $int_page ? $int_page : 1;
                $int_page_size = 20;
                $obj_user = L::loadClass('user','index');
                
                $arr_data = array();
                $str_mobile && $arr_data['mobile'] = array('do'=>'like','val'=>'%'.$str_mobile.'%');
                $str_username && $arr_data['username'] = array('do'=>'like','val'=>'%'.$str_username.'%');
                // $arr_data['is_del'] = 0;
                $arr_data['gid'] = 1;
                $arr_list = $obj_user->get_list($arr_data,$int_page,$int_page_size);
                $str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
                $str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
                $obj_user_group = L::loadClass('user_group','index');
                $arr_data = array('is_del'=>0);
                $arr_groups = $obj_user_group->get_list($arr_data,1,20);
                $arr_groups = format_array_val_to_key($arr_groups['list'],'gid');
                
                $arr_language = $_SGLOBAL['language'];
                //var_export($arr_language);
                include template('template/admin/shop/member_shop/index');
        }

        function extra_user_list(){
                global $_SGLOBAL;
                $int_page = intval($_GET['page']);
                $int_page = $int_page ? $int_page : 1;
                $int_page_size = 20;
                $str_username = str_addslashes($_GET['username']);
                $str_mobile = str_addslashes($_GET['mobile']);
                $int_pid = intval($_GET['pid']);
                $obj_user = L::loadClass('user','index');
        
                $arr_data = array();
                // $str_email && $arr_data['email'] = array('do'=>'like','val'=>'%'.$str_email.'%');
                
                // $arr_data['is_del'] = 0;
                $arr_data['pid'] = $int_pid;
                $arr_data['uid'] = array('do'=>'ne','val'=>$int_pid);
                $arr_list = $obj_user->get_list($arr_data,$int_page,$int_page_size);
                $str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
                $str_num_of_page = numofpage($int_page,ceil($arr_list['total']/$int_page_size),'?'.$str_query_string);
                $obj_user_group = L::loadClass('user_group','index');
                $arr_data = array('is_del'=>0);
                $arr_groups = $obj_user_group->get_list($arr_data,1,20);
                $arr_groups = format_array_val_to_key($arr_groups['list'],'gid');
                
                $arr_language = $_SGLOBAL['language'];
                //var_export($arr_language);
                include template('template/admin/shop/member_shop/user_list');
        }
        
        function extra_mod(){
                global $_SGLOBAL;
                $arr_language = $_SGLOBAL['language'];
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
                        $str_password && $arr_data2['password'] = $str_password;
                        $str_password && $arr_data2['password2'] = $str_password;
                        if ($int_uid) {
                                $boole_result = $obj_user->update_member($arr_data, $arr_data2);
                                if ($boole_result && $arr_member['uid'] == $int_uid) {
                                        $obj_user->login($arr_data2['username'],$arr_data2['password']);
                                }
                                callback(array('info'=>$arr_language['ok'],'status'=>200));
                        }else{
                                $result = $obj_user->_insert($arr_data2);
                                callback(array('info'=>'添加成功','status'=>200));
                        }
                        
                }
                $arr_member_one  = $obj_user->get_one($int_uid);
                $arr_member_parent = $obj_user->get_one($arr_member_one['pid']);
                // var_export($arr_member_one);
                $obj_user_group = L::loadClass('user_group','index');
                $arr_data = array('is_del'=>0);
                $arr_groups = $obj_user_group->get_list($arr_data,1,20);
                //var_export($arr_mingzu);
                include template('template/admin/shop/member_shop/mod');
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
                $obj_user = L::loadClass('user','index');
                $arr_user = $obj_user->get_one($int_uid);
                // var_export($arr_user);
                // exit();
                if($arr_user['is_del']==0){
                        $arr_data = array('is_del'=>1);
                        $str_info = $arr_language['member']['disable_success'];
                }
                else{
                        $arr_data = array('is_del'=>0);
                        $str_info = $arr_language['member']['enable_success'];
                }
                if ($arr_user['gid']==1) {
                        $obj_user->update_member(array('pid'=>$int_uid),$arr_data);
                }else{
                        $arr_user_parent = $obj_user->get_one($arr_user['pid']); 
                        if ($arr_data['is_del']==0) {
                                if ($arr_user_parent['is_del']==0) {
                                        $obj_user->update_member(array('uid'=>$int_uid),$arr_data);
                                }else{
                                        callback(array('info'=>'主账号未启用','status'=>200));
                                }
                        }else{
                                $obj_user->update_member(array('uid'=>$int_uid),$arr_data);
                        }
                }
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
                    $arr_user = $obj_user->get_one($int_uid);                
                    if($arr_user['audited']==0){
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
                $arr_uid = explode(',', $str_uids);
                $obj_user = L::loadClass('user','index');
                // $str_where = '`uid` in ('.$str_uids.')';
                foreach($arr_uid as $int_uid){
                        $arr_user = $obj_user->get_one($int_uid);                
                        if($arr_user['gid']==1){
                                $arr_data = array('is_del'=>1);
                                $obj_user->update_member(array('pid'=>$int_uid),$arr_data);                           
                        }else{
                                $arr_data = array('is_del'=>1);
                                $obj_user->update_member(array('uid'=>$int_uid),$arr_data);
                        }
                                       
                }  
                callback(array('info'=>$arr_language['ok_2'],'status'=>200));
        }

        function extra_bat_phy_open(){
                global $_SGLOBAL;
                $arr_language = $_SGLOBAL['language'];
                $str_uids = str_addslashes($_GET['uids']);
                if(empty($str_uids)){
                    return false;
                }
                $str_uids = substr($str_uids, 0, -1);
                $arr_uid = explode(',', $str_uids);
                $obj_user = L::loadClass('user','index');
                // $str_where = '`uid` in ('.$str_uids.')';
                foreach($arr_uid as $int_uid){
                        $arr_user = $obj_user->get_one($int_uid);                
                        if($arr_user['gid']==1){
                                $arr_data = array('is_del'=>0);
                                $obj_user->update_member(array('uid'=>$int_uid),$arr_data);                           
                        }else{
                                $arr_user_parent = $obj_user->get_one($arr_user['pid']); 
                                if ($arr_user_parent['is_del']==0) {
                                        $arr_data = array('is_del'=>0);
                                        $obj_user->update_member(array('uid'=>$int_uid),$arr_data);
                                }
                        }
                                       
                }
                callback(array('info'=>$arr_language['ok_2'],'status'=>200));
        }

        function extra_recharge(){
                global $_SGLOBAL;
                // define('SHOW_TRANS_SQL', 1);
                $int_uid = intval($_GET['uid']);
                $float_money = floatval($_GET['money']);
                if (!$int_uid) {
                        callback(array('info'=>'充值失败u','status'=>200));
                }
                if (!$float_money) {
                        callback(array('info'=>'充值失败m','status'=>200));
                }
                $obj_user = L::loadClass('user','index');
                $obj_money_log = L::loadClass('money_log','index');
                $obj_recharge = L::loadClass('recharge','index');
                $arr_user = $obj_user->get_one($int_uid);
                $int_time = time();
                $arr_sqls = array();
                $arr_sqls[] = $obj_user->_update_b($arr_user['uid'],array('balance'=>array('do'=>'inc','val'=>$float_money)),true);
                $arr_data = array(
                        'uid'         => $arr_user['uid'],
                        'type'        => 1,
                        'money'       => $float_money,
                        'status'      => 200,
                        'old_balance' => $arr_user['balance'],
                        'mobile'      => $arr_user['mobile'],
                        'in_date'     => $int_time
                );
                $arr_sqls[] = $obj_recharge->_insert($arr_data,true);
                $arr_money = array(
                        'type'     =>30,
                        'shop_uid' =>$arr_user['uid'],
                        'money'    =>$float_money,
                        'content'  =>'商家充值',
                        'in_date'  =>$int_time,
                );
                $arr_sqls[] = $obj_money_log->insert_money_log($arr_money,true);
                // var_export($arr_sqls);
                // exit();
                $bool_return = $_SGLOBAL['trans_db']->multi_query($arr_sqls);
                if ($bool_return) {
                        callback(array('status'=>200,'info'=>'提交成功'));
                }
                callback(array('status'=>304,'info'=>'提交失败'));

        }


        function extra_add_shop(){
                global $_SGLOBAL;
                $int_uid = intval($_GET['uid']);
                if (submit_check('formhash')) {
                        $int_uid = intval($_POST['uid']);
                        $obj_shop = L::loadClass('shop','index');
                        var_export($_POST);

                        $int_type = intval($_POST['type']);
                        $int_status = intval($_POST['status']);
                        $int_status = $int_status ? $int_status :100;
                        $str_shopname = str_addslashes($_POST['shopname']);
                        $str_shop_url = str_addslashes($_POST['shop_url']);

                        $arr_data = array(
                                'uid'      => $int_uid,
                                'type'     => $int_type,
                                'shopname' => $str_shopname,
                                'shop_url' => $str_shop_url,
                                'in_date'  => time(),
                                'status'   => $int_status,
                        );
                        $arr_shop_one = $obj_shop->get_one_b(array('shopname'=>$arr_data['shopname'],'type'=>$arr_data['type'],'is_del'=>0));
                        if ($int_sid) {
                                unset($arr_data['in_date']);
                                $obj_shop->update(array('sid'=>$int_sid),$arr_data);
                                callback(array('status'=>200,'info'=>'修改成功'));
                        }
                        if (empty($arr_shop_one) && !$int_sid) {
                                $obj_shop->insert($arr_data);
                                callback(array('status'=>200,'info'=>'添加成功'));
                        }
                        if ($arr_shop_one) {
                                callback(array('status'=>304,'info'=>'店铺名已存在'));
                        }
                }
                include template('template/admin/shop/member_shop/add_shop');
        }
        
        

}