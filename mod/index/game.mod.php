<?php
!defined('LEM') && exit('Forbidden');

// define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_game {
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
                $arr_taobao = $_SGLOBAL['taobao'];
                $obj_item = L::loadClass('item','index');
                $obj_item_keyword = L::loadClass('item_keyword','index');
                $obj_shop = L::loadClass('shop','index');
                $obj_order = L::loadClass('order','index');
                $obj_member = L::loadClass('member','index');
                if (!$arr_member['uid']) {
                        _alert2back('请先登录！');
                        exit();
                }
                if (!$arr_taobao['id']) {
                        _alert2back('淘宝账号未绑定或未审核通过');
                        exit();
                }
                if ($arr_member['weigui_end_date']>time()) {
                        _alert2back('你的账号因为违规,'.date('Y-m-d H:i:s',$arr_member['weigui_end_date']).'后才能领取任务');
                        exit();
                }
                $int_page = intval($_GET['pege']);
                $int_page = $int_page ? $int_page :1;
                $int_page_size = 1000;
                $int_cid = intval($_GET['cid']);
                $int_sort = intval($_GET['sort']);
                $arr_data = array();
                $int_today_time = strtotime(date('Y-m-d',time()));
                $int_shop_day = $_SGLOBAL['data_config']['data_shop_day']?$_SGLOBAL['data_config']['data_shop_day']:30;
                $arr_order_shop = $obj_order->get_list(array('uid'=>$arr_member['uid'],'in_date'=>array('do'=>'gt','val'=>($int_today_time-86400*$int_shop_day)),'status'=>array('do'=>'in','val'=>'100,105,110,200,304,404')));
                if ($arr_order_shop['total']>0) {
                        $arr_sids = array();
                        foreach ($arr_order_shop['list'] as $key => $value) {
                                $arr_sids[] = $value['sid'];
                        }
                        $arr_data['sid'] = array('do'=>'not_in','val'=>implode(',', $arr_sids));
                }
                $arr_data['is_pay'] = 1;
                $arr_data['is_del'] = 0;
                $arr_data['stock'] = array('do'=>'gt','val'=>0);
                $arr_data['release_time'] = array('do'=>'lt','val'=>time());
                $int_cid && $arr_data['cid_1'] = $int_cid;
                $str_order_by ='`item_id` DESC';
                $int_sort == 1 && $str_order_by = '`total_price` DESC,`item_id` DESC';
                $int_sort == 2 && $str_order_by = '`total_price` ASC,`item_id` DESC';
                $arr_list = $obj_item->get_list($arr_data,$int_page,$int_page_size,$str_order_by,false);
                $arr_member_detail = $obj_member->get_one_member_detail($arr_member['uid']);
                // var_export($arr_list['list']);
                // exit();
                foreach ($arr_list['list'] as $key => $value) {
                        $arr_list['list'][$key]['shop'] = $obj_shop->get_one_main($value['sid']);
                        $value['require'] && $arr_require = unserialize($value['require']);
                        // var_export($value);
                        
                        // echo $value['item_id'].'<br>';
                        if ($arr_require['height']) {
                                $arr_height = explode(',', $arr_require['height']['val']);
                                if ($arr_member_detail['shengao'] < $arr_height[0] || $arr_member_detail['shengao'] > $arr_height[1] ) {
                                        // echo '1'.'<br>';
                                        unset($arr_list['list'][$key]);
                                }
                        }
                        if ($arr_require['weight']) {
                                $arr_weight = explode(',', $arr_require['weight']['val']);
                                if ($arr_member_detail['tizhong'] < $arr_weight[0] || $arr_member_detail['tizhong'] > $arr_weight[1] ) {
                                        // echo '2'.'<br>';
                                        unset($arr_list['list'][$key]);
                                }
                        }

                        if ($value['province'] && $arr_member_detail['province'] != $value['province']) {//判断省
                                        // echo '3'.'<br>';
                                unset($arr_list['list'][$key]);
                        }

                        if ($value['city'] && $value['city'] !=$arr_member_detail['city']) {//判断城市
                                        // echo '4'.'<br>';
                                unset($arr_list['list'][$key]);
                        }

                        if ($value['area'] && $value['area'] !=$arr_member_detail['area']) {//判断地区
                                        // echo '5'.'<br>';
                                unset($arr_list['list'][$key]);
                        }

                        // var_export('sex_'.$arr_member['sex']);
                        if ($arr_require['sex'] && intval($arr_require['sex']['val']) != 'sex_'.$arr_member['sex']) {//判断性别
                                        // echo '6'.'<br>';
                                unset($arr_list['list'][$key]);
                        }
                        if ($arr_require['age']) {//判断性别
                                if ($arr_require['age']['val']=='age_1' && $arr_member_detail['age']>18) {
                                        unset($arr_list['list'][$key]);
                                }elseif($arr_require['age']['val']=='age_2' && ($arr_member_detail['age']<19 || $arr_member_detail['age']>24)){
                                        unset($arr_list['list'][$key]);
                                }elseif($arr_require['age']['val']=='age_3' && ($arr_member_detail['age']<25 || $arr_member_detail['age']>29)){
                                        unset($arr_list['list'][$key]);
                                }elseif($arr_require['age']['val']=='age_4' && ($arr_member_detail['age']<30 || $arr_member_detail['age']>34)){
                                        unset($arr_list['list'][$key]);
                                }elseif($arr_require['age']['val']=='age_5' && ($arr_member_detail['age']<35 || $arr_member_detail['age']>39)){
                                        unset($arr_list['list'][$key]);
                                }elseif($arr_require['age']['val']=='age_6' && ($arr_member_detail['age']<40 || $arr_member_detail['age']>49)){
                                        unset($arr_list['list'][$key]);
                                }elseif($arr_require['age']['val']=='age_7' && $arr_member_detail['age']<50){
                                        unset($arr_list['list'][$key]);
                                }
                        }
                        if ($arr_require['local']) {
                                // var_export($arr_require);
                                $arr_order = $obj_order->get_list(array('item_id'=>$value['item_id'],'in_date'=>array('do'=>'gt','val'=>strtotime(date('Y-m-d',time())))),1,100);
                                if ($arr_order['total']) {
                                        // var_export($arr_order);
                                        $arr_uids = array();
                                        foreach ($arr_order['list'] as $k => $v) {
                                                $arr_uids[] = $v['uid'];
                                        }
                                        $arr_member_local = $obj_member->get_list(array('uid'=>array('do'=>'in','val'=>implode(',', $arr_uids))));
                                        foreach ($arr_member_local['list'] as $k => $v) {
                                                $distance = getDistance($arr_member['lng'], $arr_member['lat'], $v['lng'], $v['lat'], 2);
                                                $float_juli = number_format($distance, 3,'.','');
                                                if ($float_juli<$arr_require['local']['val']) {
                                                        unset($arr_list['list'][$key]);
                                                        break;
                                                }
                                        }
                                }

                        }

                        // exit();
                }
                // var_export($arr_list['list']);
                $arr_list['total'] = count($arr_list['list']);
                $arr_data = array('uid'=>$arr_member['uid'],'status'=>105,'is_del'=>0);
                $int_order_num = $obj_order->get_list($arr_data,1,1,'`order_id` DESC',true);

                $obj_shop   = L::loadClass('shop','index');
                $arr_type_name = $obj_shop->get_type_name();
                // var_export($int_order_num);
                // var_export($arr_list);
                include template('template/index/games_list');
        }

        function extra_get_task(){
                global $_SGLOBAL;
                $arr_member  = $_SGLOBAL['member'];
                $str_config = 'config_contact';
                
                $arr_data = get_config($str_config,true);
                $arr_data = $arr_data['val'];
                if(submit_check('formhash')){
                        // var_export($_POST);
                        $obj_item = L::loadClass('item','index');
                        $obj_order = L::loadClass('order','index');
                        $obj_member = L::loadClass('member','index');
                        $obj_shop = L::loadClass('shop','index');
                        $obj_item_keyword = L::loadClass('item_keyword','index');
                        $obj_item_comment = L::loadClass('item_comment','index');
                        $int_item_id = intval($_POST['item_id']);
                        $arr_pics = str_addslashes($_POST['pic_url']);
                        $int_today_time = strtotime(date('Y-m-d',time()));
                        $arr_item_one = $obj_item->get_one_main($int_item_id);
                        $arr_item_one['require'] && $arr_require = unserialize($arr_item_one['require']);
                        $int_shop_day = $_SGLOBAL['data_config']['data_shop_day']?$_SGLOBAL['data_config']['data_shop_day']:30;
                        $arr_order_shop = $obj_order->get_list(array('uid'=>$arr_member['uid'],'in_date'=>array('do'=>'gt','val'=>($int_today_time-86400*$int_shop_day)),'status'=>array('do'=>'in','val'=>'100,105,110,200,304,404'),'sid'=>$arr_item_one['sid']));
                        if ($arr_order_shop['total']>0) {
                                callback(array('info'=>'同一个商家'.$int_shop_day.'天内只能领取一次','status'=>304));
                        }
                        if ($_SGLOBAL['today_order']>=30) {
                                callback(array('info'=>'一个用户1天内只能接'.$_SGLOBAL['data_config']['data_order_day'].'次任务','status'=>304));
                        }
                        $int_end_date = time()+3600;
                        if ($arr_require['collect']) {
                                if ($arr_require['collect']['val']=='collect_1') {
                                        $int_end_date += 3600*2;
                                }elseif ($arr_require['collect']['val']=='collect_2') {
                                        $int_end_date += 3600*22;
                                }elseif ($arr_require['collect']['val']=='collect_3') {
                                        $int_end_date += 3600*34;
                                }elseif ($arr_require['collect']['val']=='collect_4') {
                                        $int_end_date += 3600*58;
                                }
                        }
                        $arr_keyword_data = array(
                                'item_id'=>$int_item_id,
                                'stock'=>array('do'=>'gt','val'=>0)
                        );
                        $arr_keyword = $obj_item_keyword->get_list($arr_keyword_data);
                        if (!$arr_keyword['total']) {
                                callback(array('info'=>'当前任务无法领取','status'=>304));
                        }
                        $arr_shop = $obj_shop->get_one_main($arr_item_one['sid']);
                        $arr_comment = $obj_item_comment->get_list(array('item_id'=>$int_item_id,'is_use'=>1,'order_id'=>array('do'=>'lt','val'=>1)));
                        $arr_pic = $obj_item_comment->get_list_pic(array('item_id'=>$int_item_id,'is_use'=>1,'order_id'=>array('do'=>'lt','val'=>1)));
                        $arr_keyword = $arr_keyword['list'];
                        $arr_comment = $arr_comment['list'];
                        $arr_pic = $arr_pic['list'];
                        // var_export(date('Y-m-d H:i:s',$int_end_date));
                        // var_export($arr_require);
                        // exit();
                        $arr_data = array(
                                'uid'         =>$arr_member['uid'],
                                'order_no'    =>"MJX".date('mdHis',time()).rand(100,999),
                                'sid'         =>$arr_item_one['sid'],
                                'shop_uid'    =>$arr_shop['uid'],
                                'item_id'     =>$int_item_id,
                                'key_id'      =>$arr_keyword[0]['id'],
                                'comment_id'  =>$arr_comment[0]['id'],
                                'pic_id'      =>$arr_pic[0]['id'],
                                'status'      =>100,
                                'price'       =>$arr_item_one['commission'],
                                'total_price' =>$arr_item_one['total_price'],
                                'check_pics'  =>implode(',', $arr_pics),
                                'end_date'    =>$int_end_date,
                                'in_date'     =>time()
                        );
                        // var_export($arr_data);
                        // exit();
                        if ($arr_item_one['is_shenhe']!=1) {
                                $arr_data['status'] = 105;
                        }
                        $result = $obj_order->insert($arr_data);
                        if ($result) {
                                $arr_sqls = array();
                                $arr_sqls[] = $obj_item->update_main(array('item_id'=>$int_item_id),array('stock'=>array('do'=>'inc','val'=>-1)),true);
                                $arr_update_key = array('stock'=>array('do'=>'inc','val'=>-1));
                                $arr_sqls[] = $obj_item_keyword->update(array('id'=>$arr_data['key_id']),$arr_update_key,true);
                                if ($arr_data['comment_id']) {
                                        $arr_sqls[] = $obj_item_comment->update(array('id'=>$arr_data['comment_id']),array('order_id'=>$result),true);
                                }
                                if ($arr_data['pic_id']) {
                                        $arr_sqls[] = $obj_item_comment->update_pic(array('id'=>$arr_data['pic_id']),array('order_id'=>$result),true);
                                }
                                // var_export($arr_sqls);
                                // exit();
                                $bool_return = $_SGLOBAL['trans_db']->multi_query($arr_sqls);
                        }

                        $arr_config = get_config('config_contact',true);
                        $arr_config = $arr_config['val'];
                        $arr_data_shenhe = array('sid'=>$arr_data['sid'],'status'=>100);
                        $int_shenhe_num = $obj_order->get_list($arr_data_shenhe,1,1,'`order_id` DESC',true);
                        if ($int_shenhe_num>0) {
                                callback(array('info'=>'前面还有'.$int_shenhe_num.'人在审核，请耐心等待，超过5分钟未审核系统每超过1分钟补贴'.$arr_config['data_butie'].'个金币，超过30分钟未审核任务自动取消，补贴金币直接打入钱包！','status'=>200));
                        }
                        callback(array('info'=>'提交完成','status'=>200));
                }
                // var_export($arr_data);
                include template('template/index/games_grab');
        }

        function extra_upload(){
                global $_SGLOBAL;
                $arr_language = $_SGLOBAL['language'];
                $base64_image_content = $_POST['pic'];
                if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
                        $type = $result[2]; //jpeg
                        //去除图片类型
                        $pic = base64_decode(str_replace($result[1], '', $base64_image_content)); //返回文件流
                }
                $str_file_path = '/upload_pic/'.date("ymd").'/';
                if(!file_exists(S_ROOT.$str_file_path)){
                        mkdir(S_ROOT.$str_file_path);   
                }
                $str_file_path .= $_SGLOBAL['timestamp'].rand(1000,9999).'.jpg';
                file_put_contents(S_ROOT.$str_file_path,$pic);
                callback(array('info'=>'','status'=>200,'data'=>$str_file_path));
        }
}