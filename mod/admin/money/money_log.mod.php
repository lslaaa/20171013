<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_money_log {


         private $arr_parent_permission = array(
                'money_money_log_index'=>'money_money_log_index',
                'money_money_cancle'=>'money_money_log_index',
                'money_money_recharge_list'=>'money_money_log_index',
                'money_money_recharge_pay'=>'money_money_log_index',
                'money_money_recharge_chek'=>'money_money_log_index',
                'money_money_add'=>'money_money_log_index',
                'money_money_add_recharge'=>'money_money_log_index',
                'money_money_confirm_recharge'=>'money_money_log_index',
                'money_money_log_export'=>'money_money_log_index',
     );
        
        function __construct() {
                $extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
                $obj_admin_member = L::loadClass('admin_member','admin');
                if (method_exists($this, 'extra_' . $extra)) {
                        $str_menu_alias_name = MOD_DIR.'_'.SCR.'_'.$extra;
                        isset($this->arr_parent_permission[$str_menu_alias_name]) && $str_menu_alias_name = $this->arr_parent_permission[$str_menu_alias_name];
                        $bool_permission = $obj_admin_member->verify_permission($str_menu_alias_name);
                        $str_function_name = 'extra_' . $extra;
                }
                // if(!$bool_permission){
                //     die('没有权限');
                // }
                $this->$str_function_name();
        }



        function extra_index(){
                global $_SGLOBAL;
                // $obj_after = L::loadclass('after','index');
                $arr_member = $_SGLOBAL['member'];
                // $after_one = $obj_after->get_one($arr_member['aid']);
                $int_page = intval($_GET['page']);
                $int_page = $int_page ? $int_page:1;
                $int_page_size = 20;
                $str_shop = str_addslashes($_GET['shop']);
                $str_tid = str_addslashes($_GET['tid']);
                if ($_GET['start_time'] || $_GET['end_time']) {
                        $str_time1 = strtotime($_GET['start_time'])?strtotime($_GET['start_time']):0;
                        $str_time2 = strtotime($_GET['end_time'])?strtotime($_GET['end_time']):time();
                        $arr_time = array(
                                array('do'=>'gt','val'=>$str_time1),
                                array('do'=>'lt','val'=>$str_time2)
                        );
                }
                $obj_money_log = L::loadClass('money_log','index');
                $obj_tmshop = L::loadClass('tmshop','index');
                $arr_data = array();
                $arr_data['workerid'] = $arr_member['uid'];
                if ($_GET['start_time'] || $_GET['end_time']) {
                        $arr_data['in_date'] = $arr_time;
                }
                if ($str_shop) {
                        $arr_tmshop_search = $obj_tmshop->get_list(array('shopid'=>array('do'=>'like','val'=>'%'.$str_shop.'%')));
                        if ($arr_tmshop_search) {
                                $arr_sids = array();
                                foreach ($arr_tmshop_search as $key => $value) {
                                        $arr_sids[] = $value['sid'];
                                }
                                $arr_data['sid'] = array('do'=>'in','val'=>implode(',', $arr_sids));
                        }
                }
                if ($str_tid) {
                        $arr_data['tid'] = $str_tid;
                }
                $arr_data['wk_money'] = array('do'=>'gt','val'=>0);
		$arr_data['is_pay'] = 1;
                $arr_data['is_deal'] = 1;
                $arr_data['is_del'] = 0;
                $arr_money_log = $obj_money_log->get_list($arr_data,$int_page,$int_page_size);
                $arr_sids = array();
                foreach ($arr_money_log['list'] as $key => $value) {
                       $value['sid'] && $arr_sids[] = $value['sid'];
                }
                if ($arr_sids) {
                        $arr_tmshop = $obj_tmshop->get_list(array('sid'=>array('do'=>'in','val'=>implode(',', $arr_sids))));
                        $arr_tmshop = format_array_val_to_key($arr_tmshop,'sid');
                }
                $arr_type_content = $obj_money_log->get_type_content(3);
                // var_export($arr_type_content);
                $str_query_string = preg_replace('/(?|&)page=[\d]+/','',$_SGLOBAL['query_string']);
                $str_num_of_page = numofpage($int_page,ceil($arr_money_log['total']/$int_page_size),'?'.$str_query_string);
                // var_export($arr_money_log);
                include template('template/admin/money/money_log');
        }


        function extra_export(){
                global $_SGLOBAL;
                $arr_member = $_SGLOBAL['member'];
                
                // var_export($_POST['export']);
                // exit();
                //导出操作

                if (isset($_POST['export']) && intval($_POST['export']) == 1) {  

                        $obj_money_log    = L::loadClass('money_log','index');
                        if ($_POST['start_time'] || $_POST['end_time']) {
                                $str_time1 = strtotime($_POST['start_time'])?strtotime($_POST['start_time']):0;
                                $str_time2 = strtotime($_POST['end_time'])?strtotime($_POST['end_time']):time();
                                if (($str_time2-$str_time1)<86400) {
                                        callback(array('status'=>204,'info'=>'导出时间最短为一天'));
                                }
                                if (($str_time2-$str_time1)>86400*31) {
                                        callback(array('status'=>204,'info'=>'导出时间最长为一个月'));
                                }
                                $arr_time = array(
                                        array('do'=>'gt','val'=>$str_time1),
                                        array('do'=>'lt','val'=>$str_time2)
                                );
                        }
                        $arr_data = array();
                        $arr_data['workerid'] = $arr_member['uid'];
                        if ($_POST['start_time'] || $_POST['end_time']) {
                                $arr_data['in_date'] = $arr_time;
                        }
                        $arr_data['wk_money'] = array('do'=>'gt','val'=>0);
                        $arr_data['is_pay'] = 1;
                        $arr_data['is_deal'] = 1;
                        $arr_data['is_del'] = 0;
                        $arr_money_log = $obj_money_log->get_list($arr_data,1,1);
                        // if ($arr_money_log['total']>5000) {
                        //         callback(array('status'=>204,'info'=>'最多只能导出5000条数据，请按时间筛选后再导出'));
                        // }
                        callback(array('status'=>200,'info'=>'准备导出'.$arr_money_log['total'].'条记录'));
                }
                if (isset($_GET['export']) && intval($_GET['export']) == 1) {  

                        $obj_money_log    = L::loadClass('money_log','index');
                        $obj_tmshop    = L::loadClass('tmshop','index');
                        if ($_GET['start_time'] || $_GET['end_time']) {
                                $str_time1 = strtotime($_GET['start_time'])?strtotime($_GET['start_time']):0;
                                $str_time2 = strtotime($_GET['end_time'])?strtotime($_GET['end_time']):time();
                                if (($str_time2-$str_time1)<86400) {
                                        callback(array('status'=>204,'info'=>'导出时间最短为一天'));
                                }
                                if (($str_time2-$str_time1)>86400*31) {
                                        callback(array('status'=>204,'info'=>'导出时间最长为一个月'));
                                }
                                $arr_time = array(
                                        array('do'=>'gt','val'=>$str_time1),
                                        array('do'=>'lt','val'=>$str_time2)
                                );
                        }
                        $arr_data = array();
                        $arr_data['workerid'] = $arr_member['uid'];
                        if ($_GET['start_time'] || $_GET['end_time']) {
                                $arr_data['in_date'] = $arr_time;
                        }
                        $arr_data['wk_money'] = array('do'=>'gt','val'=>0);
                        $arr_data['is_pay'] = 1;
                        $arr_data['is_deal'] = 1;
                        $arr_data['is_del'] = 0;
                        $arr_money_log = $obj_money_log->get_list($arr_data,1,1);
                        $int_page_all = ceil($arr_money_log['total']/2000);
                        $int_page = 1;
                        $arr_sids = array();
                        $arr_money_log_all = array();
                        do{
                                $arr_money_log = $obj_money_log->get_list($arr_data,$int_page,2000);
                                $arr_money_log_all = array_merge($arr_money_log_all,$arr_money_log['list']);
                                foreach ($arr_money_log['list'] as $key => $value) {
                                       !in_array($value['sid'], $arr_sids) && $arr_sids[] = $value['sid'];
                                }
                                $int_page ++;
                        }while ( $int_page <= $int_page_all);
                        if ($arr_sids) {
                                $arr_tmshop = $obj_tmshop->get_list(array('sid'=>array('do'=>'in','val'=>implode(',', $arr_sids))));
                                $arr_tmshop = format_array_val_to_key($arr_tmshop,'sid');
                        }
                        $arr_type_content = $obj_money_log->get_type_content(3);
                        $str_client = md5(rand()); //未加密之前的字符串
                        $str_server = base64_encode(hash_hmac("SHA1", $str_client, SAFE_TOKEN, true)); //服务器发送过来的加密字符串
                        $json_data = array(
                                'str_client' => $str_client,
                                'str_server' => $str_server,
                                'label'      => array('时间','金额', '说明', '店铺名', '订单号','备注')//第一行
                        );


                        if (!empty($arr_money_log_all)) {
                                foreach ($arr_money_log_all as $key => $value) {
                                        if ($v['type']!=11 && $v['type']!=80 && $v['type']!=100 && $v['type']!=110) {
                                                $str_jiajian = '';
                                        }else{
                                                $str_jiajian = '-';
                                        }
                                        $json_data['content'][] = array(
                                                date('Y-m-d H:i:s',$value['in_date']),
                                                $str_jiajian.$value['wk_money'],
                                                $arr_type_content[$value['type']],
                                                $arr_tmshop[$value['sid']]['shopid'],
                                                $value['tid'],
                                                ''
                                        ); //第四行以后的导入数据   
                                        // unset($arr_orders[$key]);
                                }
                        }
                        $json_data['cell_width'] = array(
                                                'A'=>20,
                                                'B'=>30,
                                                'C'=>20,
                                                'D'=>20,
                                                'E'=>30,
                                                'F'=>20,
                                        );
                        $json_data['text_type'] = array('K'=>1,'L'=>1);
                        //$json_data = json_encode($json_data);
                        $str_file_name = 'money' . date('YmdHm', $_SGLOBAL['timestamp']) .'-1.xls';
                        header("Cache-Control: no-cache, must-revalidate");
                        header("Pragma: no-cache");
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $str_file_name . '"');
                        header('Cache-Control: max-age=0');
                        $obj_excel = L::loadClass('excel', 'index');
                        $obj_excel->export($json_data);
                        exit();
                }
        }

}