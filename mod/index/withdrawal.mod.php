<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_withdrawal {
        function __construct() {
                global $_SGLOBAL;
                if($_SGLOBAL['member']['uid']<=0){
                        jump('/login'); 
                }
                $extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
                
                if (method_exists($this, 'extra_' . $extra)) {
                        $str_function_name = 'extra_' . $extra;
                        $this->$str_function_name();
                }
        }

        function extra_index(){
                global $_SGLOBAL;
                $arr_member = $_SGLOBAL['member'];
                include template('template/index/member/withdrawal');
        }

        function extra_add(){
                global $_SGLOBAL;
                $arr_member = $_SGLOBAL['member'];
                if (!$arr_member['bank_card']) {
                        callback(array('status'=>304,'info'=>'请先绑定银行卡'));
                }
                if (!$arr_member['password_2']) {
                        callback(array('status'=>304,'info'=>'请先设置提现密码'));
                }
                $float_amount = floatval($_GET['money']);
                $str_password = floatval($_GET['password']);
                $obj_member = L::loadClass('member','index');
                $obj_money_log = L::loadClass('money_log','index');
                $obj_withdrawal = L::loadClass('withdrawal','index');
                if (md5(md5($str_password).$arr_member['salt'])!=$arr_member['password_2']) {
                        callback(array('status'=>304,'info'=>'提现密码错误'));
                }
                $int_time = time();
                $arr_sqls = array();
                $arr_sqls[] = $obj_member->update_member(array('uid'=>$arr_member['uid']),array('balance'=>array('do'=>'inc','val'=>-$float_amount)),true);
                $arr_data = array(
                    'uid'       => $arr_member['uid'],
                    'type'      => 2,
                    'amount'    => $float_amount,
                    'status'    => 100,
                    'card_name' => $arr_member['bank_card_name'],
                    'bank_name' => $arr_member['bank'],
                    'bank_card' => $arr_member['bank_card'],
                    'in_date'   => $int_time
                );
                $arr_sqls[] = $obj_withdrawal->insert($arr_data,true);
                $arr_money = array(
                        'type'       =>50,
                        'uid'        =>$arr_member['uid'],
                        'money'      =>$float_amount,
                        'content'    =>'用户提现',
                        'in_date'    =>$int_time,
                );
                $arr_sqls[] = $obj_money_log->insert_money_log($arr_money,true);
                $bool_return = $_SGLOBAL['trans_db']->multi_query($arr_sqls);
                if ($bool_return) {
                        callback(array('status'=>200,'info'=>'提交成功'));
                }
                callback(array('status'=>304,'info'=>'提交失败'));
        }
}