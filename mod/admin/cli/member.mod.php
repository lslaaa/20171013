<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_member {

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

        function extra_get_member(){
                global $_SGLOBAL;
                // define('SHOW_TRANS_SQL', 1);
                $obj_member = L::loadClass('member','index');
                $arr_data = array('weigui_num'=>array('do'=>'lt','val'=>4));
                $obj_member->update_member($arr_data,array('weigui_num'=>0,'weigui_end_date'=>0));
                // var_export($arr_order);
                // define('SHOW_TRANS_SQL', 1);
                // callback(array('status'=>200,'arr_sqls'=>$arr_sqls));
        }
}