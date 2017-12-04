<?php
!defined('LEM') && exit('Forbidden');
define("TOKEN", "20171013maijiaxiu");
class LEM_weixin extends mysqlDBA{
    /*
     * 开发者接入验证
     * 通过检验signature对请求进行校验（下面有校验方式）。
     * 若确认此次GET请求来自微信服务器，请原样返回echostr参数内容，
     * 则接入生效，成为开发者成功，否则接入失败。
     * */
    public function verify()
    {
        $str_echostr = $_GET["echostr"];
        //验证signature , option
        if($this->verify_signature()){
            echo $str_echostr;
        }
    }
    //验证微信加密签名signature，signature结合了开发者在开发者中心填写的token参数和请求中的timestamp参数、nonce参数。
    private function verify_signature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('未定义TOKEN');
        }
        $str_signature = $_GET["signature"];
        $int_timestamp = $_GET["timestamp"];//请求时间戳
        $int_nonce = $_GET["nonce"];//随机数

        $str_token = TOKEN;
        $arr_tmp = array($str_token, $int_timestamp, $int_nonce);
        // use SORT_STRING rule
        sort($arr_tmp, SORT_STRING);
        $str_tmp = implode( $arr_tmp );
        $str_tmp = sha1( $str_tmp );
        $bool = ( $str_tmp == $str_signature)? true :false;
        return $bool;
    }

    
    //微信自定义回复
    function wechat_custom_response($str_kwd){
        $obj_weixin = L::loadClass('weixin_custom', 'index');
        $arr_data = $obj_weixin->get_one_by_where(array('ask' => $str_kwd, 'is_del' => 0));
        if(empty($arr_data['answer'])){
            $arr_data['answer'] = '如果您有任何疑问，欢迎拨打客服热线：<a href="tel:4000-518-168">4000-518-168</a>';
        }
        return $arr_data['answer'];
    }
    
    
    
    
    //获取推送weixin_push列表
//    function  get_list_weixin_push($arr_where,$arr_condtion = array(),$arr_fields =array()){
//        $arr_return = array();
//        if(empty($arr_where)){
//            return $arr_return;
//        }
//        $this->obj_mongo->select_db('baiji_weixin');
//        $arr_return['total'] = $this->obj_mongo->count('weixin_push',$arr_where);
//        $arr_return['list']  = empty($arr_fields) ? $this->obj_mongo->find('weixin_push',$arr_where,$arr_condtion): $this->obj_mongo->find('weixin_push',$arr_where,$arr_condtion,$arr_fields);
//        return $arr_return;
//    }

//    function get_one_weixin_push($arr_where,$arr_fields =array()){
//        $arr_return = array();
//        if(empty($arr_where)){
//            return $arr_return;
//        }
//        $this->obj_mongo->select_db('baiji_weixin');
//        $arr_return = empty($arr_fields) ? $this->obj_mongo->find_one('weixin_push',$arr_where): $this->obj_mongo->find('weixin_push',$arr_where,$arr_fields);
//        return $arr_return;
//    }
    
    /* 累计分享量 */
//    function update_share_count($str_share_id){
//        $this->obj_mongo->select_db('baiji_weixin');
//        $arr_share = $this->obj_mongo->find_one('weixin_share', array('_id' => new mongoId($str_share_id)));
//        if(empty($arr_share)){
//            return false;
//        }
//        $int_time = intval(date('ymd'));
//        $arr_share_day = $this->obj_mongo->find_one('weixin_share_day', array('share_id' => new mongoId($str_share_id), 'day' => $int_time));
//        if(!empty($arr_share_day)){
//            return $this->obj_mongo->update('weixin_share_day', array('share_id' => new mongoId($str_share_id), 'day' => $int_time), array('$inc' => array('num' => 1), '$set' => array('day' => $int_time)));
//        }else{
//            return $this->obj_mongo->insert('weixin_share_day', array('share_id' => new mongoId($str_share_id), 'num' => 1, 'day' => $int_time));
//        }
//                
//    }
    
    /* 微信菜单点击量统计 */
//    function update_menu_click($str_url, $str_open_id){
//        global $_SGLOBAL;
//        $int_time = intval(date('ymd'));
//        $str_md5_url = md5($str_url);        
//        $obj_redis = redis_init();
//        $str_redis_menu_name = $_SGLOBAL['redis_name']['weixin_menu_uc'].$str_md5_url.$str_open_id;
//        $bool_exists = $obj_redis->_exists($str_redis_menu_name);
//        $arr_update = $bool_exists ? array('$inc' => array('click_num' => 1)) : array('$inc' => array('click_num' => 1, 'uc' => 1));
//        if(!$bool_exists){
//            $int_wee_time = strtotime(date('Y-m-d', strtotime('+1 day')));
//            $int_expire = ($int_wee_time - $_SGLOBAL['timestamp']) > 0 ? $int_wee_time - $_SGLOBAL['timestamp'] : 1;        
//            $obj_redis->_set($str_redis_menu_name, 1, $int_expire);
//        }
//        $this->obj_mongo->select_db('baiji_weixin');
//        $arr_menu = $this->obj_mongo->find_one('weixin_menu_click_day', array('url' => $str_url, 'day' => $int_time));
//        if(!empty($arr_menu)){
//            $this->obj_mongo->update('weixin_menu_click_day', array('url' => $str_url, 'day' => $int_time), $arr_update);
//        }else{
//            return $this->obj_mongo->insert('weixin_menu_click_day', array('md5_url' => $str_md5_url, 'url' => $str_url, 'click_num' => 1, 'uc' => 1, 'day' => $int_time));
//        }
//        
//    }
    
    //获取微信提问关键词正则串
//    function check_message_is_question($str_message, $str_open_id)
//    {
//        $obj_redis = redis_init();
//        $str_preg = $obj_redis->_get('bbs_question_keywords');
//        if($str_preg)
//        {
//            if(preg_match($str_preg, $str_message, $arr_abc))
//            {
//                $obj_redis->_set('bbs_question_' . $str_open_id, $str_message, 10 * 60);
//                return true;
//            }
//        }
//        return false;
//    }

}


