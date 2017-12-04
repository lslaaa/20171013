<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_item {

        private $arr_parent_permission = array(
                'shop_shop_item_index'=>'admin_shop',
        );



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

        function extra_get_item(){
                define('SHOW_TRANS_SQL', 1);
                global $_SGLOBAL;
                $obj_item = L::loadClass('item','index');
                $obj_item_keyword = L::loadClass('item_keyword','index');
                $obj_item_comment = L::loadClass('item_comment','index');
                $arr_data = array(
                        'status'=>200,
                        'is_pay'=>1,
                        'is_del'=>0,
                        'last_stock'=>array('do'=>'gt','val'=>0),
                        'release_time'=>array('do'=>'lt','val'=>time())
                );
                $arr_item = $obj_item->get_list($arr_data,1,1000);
                foreach ($arr_item['list'] as $k => $v) {
                        if (!$v['hour_send']) {
                                continue;
                        }
                        $arr_key_data = array(
                                'item_id'=>$v['item_id'],
                                'last_stock'=>array('do'=>'gt','val'=>0),
                        );
                        $arr_item_keyword = $obj_item_keyword->get_list($arr_key_data,1,100);
                        $arr_item_comment = $obj_item_comment->get_list(array('item_id'=>$v['item_id']));
                        $arr_item_comment_pic = $obj_item_comment->get_list_pic(array('item_id'=>$v['item_id']));
                        if (!$arr_item_keyword['total']) {
                                continue;
                        }
                        $arr_sqls = array();
                        $int_hour_send = $v['hour_send'];
                        foreach ($arr_item_keyword['list'] as $k2 => $v2) {
                                if ($int_hour_send>=$v2['last_stock']) {
                                        $int_stock_one = $v2['last_stock'];
                                        $int_last_one = 0;
                                        $int_hour_send = $int_hour_send-$v2['last_stock'];
                                }else{
                                        $int_stock_one = $int_hour_send;
                                        $int_last_one = $v2['last_stock']-$int_hour_send;
                                        $int_hour_send = $int_hour_send-$v2['last_stock'];
                                }

                                $arr_keyword_data = array(
                                        'stock'=>$int_stock_one,
                                        'last_stock'=>$int_last_one
                                );
                                if ($arr_keyword_data['last_stock']!=$v2['last_stock']) {
                                        $arr_sqls[] = $obj_item_keyword->update(array('id'=>$v2['id']),$arr_keyword_data,true);
                                }
                        }
                        if ($v['hour_send']>=$v['last_stock']) {
                                $int_stock_item = $v['last_stock'];
                                $int_last_item = 0;
                        }else{
                                $int_stock_item = $v['hour_send'];
                                $int_last_item = $v['last_stock']-$v['hour_send'];
                        }
                        $arr_item_data = array(
                                'last_stock'=>$int_last_item,
                                'stock'=>array('do'=>'inc','val'=>$int_stock_item)
                        );
                        $arr_sqls[] = $obj_item->update_main(array('item_id'=>$v['item_id']),$arr_item_data,true);
                        if ($arr_item_comment['total']>0) {
                                $arr_sqls[] = 'update index_item_comment set `is_use` = 1 where `item_id` = '.$v['item_id'].' and `is_use` = 0 limit '.$v['hour_send'];
                        }
                        if ($arr_item_comment_pic['total']) {
                                $arr_sqls[] = 'update index_item_pic set `is_use` = 1 where `item_id` = '.$v['item_id'].' and `is_use` = 0 limit '.$v['hour_send'];
                        }
                        $bool_return = $_SGLOBAL['trans_db']->multi_query($arr_sqls);
                }
                // callback(array('status'=>200,'arr_sqls'=>$arr_sqls));
        }
}