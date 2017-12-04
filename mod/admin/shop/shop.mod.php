<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_shop {

        function __construct() {
                $extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
                $obj_admin_member = L::loadClass('admin_member','admin');
                if (method_exists($this, 'extra_' . $extra)) {
                        $str_menu_alias_name = MOD_DIR.'_'.SCR.'_'.$extra;
                        isset($this->arr_parent_permission[$str_menu_alias_name]) && $str_menu_alias_name = $this->arr_parent_permission[$str_menu_alias_name];
                        // $bool_permission = $obj_admin_member->verify_permission($str_menu_alias_name);
                        $str_function_name = 'extra_' . $extra;
                }
        $this->$str_function_name();
        }


        function extra_index(){
                global $_SGLOBAL;
                $arr_language = $_SGLOBAL['language'];
                $arr_member = $_SGLOBAL['member'];
                $int_page = intval($_GET['page']);
                $int_page = $int_page?$int_page:1;
                $int_page_size = 20;
                $str_username = str_addslashes($_GET['username']);
                $str_phone = str_addslashes($_GET['phone']);
                $str_shopname = str_addslashes($_GET['shopname']);
                $int_status = intval($_GET['status']);
                $obj_shop = L::loadClass('shop','index');
                $obj_user = L::loadClass('user','index');
                $arr_data = array('is_del'=>0);
                $int_status && $arr_data['status'] = $int_status;
                $str_shopname && $arr_data['shopname'] = array('do'=>'like','val'=>'%'.$str_shopname.'%');
                $str_phone && $arr_data['phone'] = $str_phone;
                if ($str_username) {
                        $arr_user = $obj_user->get_list(array('username'=>array('do'=>'like','val'=>'%'.$str_username.'%')));
                        if ($arr_user['total']>0) {
                                $arr_uids = array();
                                foreach ($arr_user['list'] as $k => $v) {
                                        if (!in_array($v['uid'], $arr_uids)) {
                                                $arr_uids[] = $v['uid'];
                                        }
                                }
                                $arr_data['uid'] = array('do'=>'in','val'=>implode(',', $arr_uids));
                        }
                }
                $arr_shop = $obj_shop->get_list($arr_data,$int_page,$int_page_size);
                $arr_uids = array();
                foreach ($arr_shop['list'] as $k => $v) {
                        if (!in_array($v['uid'], $arr_uids)) {
                                $arr_uids[] = $v['uid'];
                        }
                }
                if ($arr_uids) {
                        $arr_user = $obj_user->get_list(array('uid'=>array('do'=>'in','val'=>implode(',', $arr_uids))));
                        $arr_user = format_array_val_to_key($arr_user['list'],'uid');
                }
                // var_export($arr_user);
                $arr_type_name = $obj_shop->get_type_name();
                // var_export($arr_shop);
                // $arr_shop = $obj_shop->get_list_by_juli(22.616557,114.036892,array('is_del'=>0),1,2,'sid');
                // var_dump($arr_shop);
                $str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
                $str_num_of_page = numofpage($int_page,ceil($arr_shop['total']/$int_page_size),'?'.$str_query_string);
                include template('template/admin/shop/shop/index');
        }

        function extra_add(){
                global $_SGLOBAL;
                $arr_member = $_SGLOBAL['member'];
                $int_sid = intval($_GET['sid']);
                if(submit_check('formhash')){
                        var_export($_POST);
                        $str_form_do = str_addslashes($_POST['form_do']);
                        if (method_exists($this, 'do_' . $str_form_do)) {
                                $str_function_name = 'do_' . $str_form_do;
                                $this->$str_function_name();
                        }
                        
                }
                $obj_shop = L::loadClass('shop','index');
                if ($int_sid) {
                        $arr_shop = $obj_shop->get_one_main($int_sid);
                }
                include template('template/admin/shop/shop/add');
        }

        function do_add(){
                global $_SGLOBAL;
                // var_export($_POST);
                // exit();
                $arr_member = $_SGLOBAL['member'];
                $int_type = intval($_POST['type']);
                $int_status = intval($_POST['status']);
                $int_sid = intval($_POST['sid']);
                $int_status = $int_status ? $int_status :100;
                $str_shopname = str_addslashes($_POST['shopname']);
                $str_username = str_addslashes($_POST['username']);
                $str_phone = str_addslashes($_POST['phone']);
                $str_contact = str_addslashes($_POST['contact']);
                $str_address = str_addslashes($_POST['address']);
                $str_shop_url = str_addslashes($_POST['shop_url']);
                $str_content = str_addslashes($_POST['content']);

                $obj_shop = L::loadClass('shop','index');
                $arr_data = array(
                        'uid'      => $arr_member['uid'],
                        'type'     => $int_type,
                        'shopname' => $str_shopname,
                        'username' => $str_username,
                        'phone'    => $str_phone,
                        'contact'  => $str_contact,
                        'address'  => $str_address,
                        'shop_url' => $str_shop_url,
                        'content'  => $str_content,
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

        function extra_shehe(){
                global $_SGLOBAL;
                $int_sid = intval($_GET['sid']);
                $int_status = intval($_GET['status']);
                $obj_shop = L::loadClass('shop','index');
                if (!$int_sid) {
                        callback(array('status'=>304,'info'=>'审核失败'));
                }
                $obj_shop->update(array('sid'=>$int_sid),array('status'=>$int_status));
                callback(array('status'=>200,'info'=>'审核完成'));
        }

    

        
}