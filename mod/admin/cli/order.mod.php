<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_order {

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

        function extra_get_order(){
                global $_SGLOBAL;
                // define('SHOW_TRANS_SQL', 1);
                $obj_order = L::loadClass('order','index');
                $obj_item = L::loadClass('item','index');
                $obj_member = L::loadClass('member','index');
                $obj_item_keyword = L::loadClass('item_keyword','index');
                $obj_item_comment = L::loadClass('item_comment','index');
                $arr_data = array(
                        'status'=>105,
                        'end_date'=>array('do'=>'lt','val'=>time()),
                );
                $arr_order = $obj_order->get_list($arr_data);
                foreach ($arr_order['list'] as $key => $value) {
                        $arr_member = $obj_member->get_one_member($value['uid']);
                        $arr_member['weigui_num']==0 && $int_add_time = 86400*3;
                        $arr_member['weigui_num']==1 && $int_add_time = 86400*7;
                        $arr_member['weigui_num']==2 && $int_add_time = 86400*30;
                        $arr_member['weigui_num']==3 && $int_add_time = 86400*360;

                        $arr_sqls = array();
                        $arr_sqls[] = $obj_member->update_member(array('uid'=>$value['uid']),array('weigui_num'=>array('do'=>'inc','val'=>1),'weigui_end_date'=>time()+$int_add_time),true);
                        $arr_sqls[] = $obj_order->update_main(array('order_id'=>$value['order_id']),array('status'=>90),true);
                        $arr_sqls[] = $obj_item->update_main(array('item_id'=>$value['item_id']),array('stock'=>array('do'=>'inc','val'=>1)),true);
                        $arr_sqls[] = $obj_item_keyword->update(array('id'=>$value['key_id']),array('stock'=>array('do'=>'inc','val'=>1)),true);
                        $arr_sqls[] = $obj_item_comment->update(array('id'=>$value['comment_id']),array('order_id'=>0),true);
                        $arr_sqls[] = $obj_item_comment->update_pic(array('id'=>$value['pic_id']),array('order_id'=>0),true);
                        // var_export($arr_sqls);
                        // exit();
                        $bool_return = $_SGLOBAL['trans_db']->multi_query($arr_sqls);

                }
                // var_export($arr_order);
                // define('SHOW_TRANS_SQL', 1);
                // callback(array('status'=>200,'arr_sqls'=>$arr_sqls));
        }
}