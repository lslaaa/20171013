<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_message {

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
                $obj_weixinapi = L::loadClass('weixinapi');
                $arr_data = array(
                        'status'=>105,
                        'is_message'=>0,
                        'end_date'=>array('do'=>'lt','val'=>time()+3600),
                );
                $arr_order = $obj_order->get_list($arr_data);
                // var_export($arr_order);
                // exit();
                foreach ($arr_order['list'] as $key => $value) {
                        $arr_member = $obj_member->get_one_member($value['uid']);
                        $str_openid = $arr_member['openid'];
                        $str_url = M_URL.'/i/my_task';
                        $str_first = '任务到期提醒';
                        $str_keyword1 = $value['order_id'];
                        $str_keyword2 = date('Y-m-d H:i:s',$value['in_date']);
                        $str_keyword3 = date('Y-m-d H:i:s',$value['end_date']);
                        $str_remark = '请您尽快完成任务，否则会受到处罚';
                        $result = $obj_weixinapi->send_mp_expire_msg($str_openid, $str_url, $str_first, $str_keyword1, $str_keyword2,$str_keyword3, $str_remark, $str_color="#173177");
                        // var_export($result);
                        if ($result['errmsg']=='ok') {
                                $obj_order->update_main(array('order_id'=>$value['order_id']),array('is_message'=>1));
                        }

                }
                // var_export($arr_order);
                // define('SHOW_TRANS_SQL', 1);
                // callback(array('status'=>200,'arr_sqls'=>$arr_sqls));
        }
}