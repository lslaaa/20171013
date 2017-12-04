<?php
!defined('LEM') && exit('Forbidden');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of weixinapi
 *
 * @author Administrator
 */
class LEM_weixinapi
{

    private $_access_token;
    private $_jsapi_ticket;
    private $_jsapi_signature;
    private $_app_id;
    private $_app_secret;

    //const MP_TYPE_SHIPMENT = 'shipment'; //发货提醒
    const MP_TYPE_EXPIRE = 'expire';//审核推送
    const MP_TYPE_PAYMENT = 'payment'; //支付推送
    const MP_TYPE_ORDER = 'order';//下单推送
    const MP_TYPE_PROMOTE = 'promote';//推广推送
    
    const MP_TYPE_EXPIRE_ID = "lhoGz1XqTNhVArhLMbsPkTthtmUa3dkRuShMMI9gKf8";//审核模板id
    const MP_TYPE_PAYMENT_ID = "8BQI21LlRI4R4yJoV_4mW3pRjTQuaYrevdYtR5oqvCM";//支付模板id
    const MP_TYPE_ORDER_ID = "Y_-ICDRBedznamhXmWdzSuTblIEmBpMVZzySJk0G5Nw";//下单模板id
    const MP_TYPE_PROMOTE_ID = "ykO2hg62NXE8C4w1jV9jT8bo1h6v6vh4FEd4Gl7kpM8";//推广模板id
    
    function __construct()
    {
        include_once( S_ROOT . 'ssi/config/msocial/weixin.conf.php' );
        $this->_app_id = WEIXIN_APPID;
        $this->_app_secret = WEIXIN_APP_SECRET;
    }

    public function get_appid()
    {
        return $this->_app_id;
    }

    public function get_access_token()
    {
        global $_SGLOBAL;
    /*
        $str_file_path = S_ROOT.'data/weixin_access_token.txt';             
        $str_access_token = read_over($str_file_path);
        if($str_access_token && ($_SGLOBAL['timestamp']-filemtime($str_file_path))>7200){
                $str_access_token = ''; 
        }
    */
    $str_access_token = get_config('access_token',true);
    if($str_access_token && ($_SGLOBAL['timestamp']-$str_access_token['val']['in_date'])>3600){
                $str_access_token = ''; 
        }
    else{
        $str_access_token = $str_access_token['val']['access_token'];   
    }

    
    // if(!strstr($_SERVER['REMOTE_ADDR'],'192.168') && !strstr($_SERVER['REMOTE_ADDR'],'127.0.0.1')){
        if(!$str_access_token && $str_access_token != 'empty')
        {
            $arr_data['url'] = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->_app_id}&secret={$this->_app_secret}";
            $json_result = post_curl($arr_data);
            $arr_result = json_decode($json_result, 1);
            if($arr_result['access_token'])
            {
                $str_access_token = $arr_result['access_token'];
                set_config(array('name'=>'access_token','val'=>str_addslashes(var_export(array('access_token'=>$str_access_token,'in_date'=>time()),true))));
                    //write_over($str_access_token,$str_file_path);
            }
        }
    // }
        $this->_access_token = $str_access_token;
        return $str_access_token;
        
    }

    public function get_jsapi_ticket()
    {        
        global $_SGLOBAL;
        $str_file_path = S_ROOT.'data/weixin_jsapi_ticket.txt';
        $str_jsapi_ticket = read_over($str_file_path);
        if($str_jsapi_ticket && ($_SGLOBAL['timestamp']-filemtime($str_file_path))>7200){
                $str_jsapi_ticket = '';	
        }
        $str_access_token = $this->get_access_token();

	// if(!strstr($_SERVER['REMOTE_ADDR'],'192.168') && !strstr($_SERVER['REMOTE_ADDR'],'127.0.0.1')){
		if(!$str_jsapi_ticket)
		{
		    $arr_data['url'] = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$str_access_token}&type=jsapi";
		    $json_result = post_curl($arr_data);
		    $arr_result = json_decode($json_result, 1);
		    if($arr_result['ticket'])
		    {
			$str_jsapi_ticket = $arr_result['ticket'];
			write_over($str_jsapi_ticket,$str_file_path);
		    }
		}
	// }
        $this->_jsapi_ticket = $str_jsapi_ticket;
        return $str_jsapi_ticket;
       
    }

    public function get_jsapi_signature($str_url, $str_noncestr = '')
    {
        global $_SGLOBAL;
        $str_jsapi_ticket = $this->get_jsapi_ticket();
        $str_str = "jsapi_ticket={$str_jsapi_ticket}&noncestr={$str_noncestr}&timestamp={$_SGLOBAL['timestamp']}&url={$str_url}";
        $str_jsapi_signature = sha1($str_str);
        $this->_jsapi_signature = $str_jsapi_signature;
        return $str_jsapi_signature;
    }

    public function __get($name)
    {
        if($this->$name)
        {
            return $this->$name;
        }
        else
        {
            $str_func_name = 'get' . $name;
            return $this->$str_func_name();
        }
    }




    //发送客服信息
    public function send_custom_message($arr_fields)
    {

        $str_access_token = $this->get_access_token();
        $arr_data = array();
        $arr_data['url'] = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $str_access_token;
        $arr_data['type'] = 'post';
        $arr_data['fields'] = json_encode($arr_fields);
        $arr_data['fields'] = urldecode($arr_data['fields']);
        $json_result = post_curl_b($arr_data);
        return json_decode($json_result, true);
    }

    //下载图片
    public function download_image($arr_media_ids = array())
    {
        if(!$arr_media_ids)
        {
            return false;
        }
        if(is_string($arr_media_ids))
        {
            $arr_media_ids = array($arr_media_ids);
        }
        $str_access_token = $this->get_access_token();
        $obj_multi_curl = L::loadClass('multi_curl');
        foreach($arr_media_ids as $str_media_id)
        {
            if(!$str_media_id)
            {
                continue;
            }
            $arr_data = array();
            $arr_data['url'] = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$str_access_token}&media_id={$str_media_id}";
            $arr_data['type'] = 'get';
            $arr_data['return_key'] = $str_media_id;
            $obj_multi_curl->_add($arr_data,20);
        }
        $arr_medias = $obj_multi_curl->_exec_binary();
        return $arr_medias;
    }



    //发送模板消息
    public function send_template_message($arr_fields)
    {
        $str_access_token = $this->get_access_token();
        $arr_data = array();
        $arr_data['url'] = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $str_access_token;
        $arr_data['type'] = 'post';
        $arr_data['fields'] = json_encode($arr_fields);
        $arr_data['fields'] = urldecode($arr_data['fields']);
        $json_result = post_curl_b($arr_data);
        return json_decode($json_result, true);
    }


     public function send_mp_expire_msg($str_openid, $str_url, $str_first, $str_keyword1, $str_keyword2,$str_keyword3, $str_remark, $str_color="#173177"){
        $arr_msg_data = array();
        $arr_msg_data['first'] = array('value' => $str_first, 'color' => $str_color);
        $arr_msg_data['keyword1'] = array('value' => $str_keyword1, 'color' => $str_color);
        $arr_msg_data['keyword2'] = array('value' => $str_keyword2, 'color' => $str_color);
        $arr_msg_data['keyword3'] = array('value' => $str_keyword3, 'color' => $str_color);
        $arr_msg_data['remark'] = array('value' => $str_remark, 'color' => $str_color);
        $arr_data = array();
        $arr_data["touser"] = $str_openid;
        $arr_data["template_id"] = self::MP_TYPE_EXPIRE_ID;
        $arr_data["url"] = $str_url;
        $arr_data["topcolor"] = $str_color;
        $arr_data["data"] = $arr_msg_data;        
        $arr_result = $this->send_template_message($arr_data);
        return $arr_result;
    }
    
    
    
    
    //获取用户基本信息
    public function get_user_info($str_openid_id)
    {
        $str_access_token = $this->get_access_token();
        $obj_multi_curl = L::loadClass('multi_curl');
        $arr_data = array();
        $arr_data['url'] = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$str_access_token}&openid={$str_openid_id}&lang=zh_CN";
        $arr_data['type'] = 'get';
        $arr_data['return_key'] = "user";
        $obj_multi_curl->_add($arr_data);
        $json_result = $obj_multi_curl->_exec();
        return $json_result['user'];
    }
    
    
    //生成带参数的二维码（永久）
    public function create_limit_qrcode($int_id){
        $str_access_token = $this->get_access_token();
        $arr_fields = array();
        $arr_fields['action_name'] = "QR_LIMIT_STR_SCENE";
        $arr_fields['action_info'] = array('scene' => array('scene_str' => $int_id));
        $arr_data = array();
        $arr_data['url'] = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$str_access_token}";
        $arr_data['type'] = 'post';
        $arr_data['fields'] = json_encode($arr_fields);
        $arr_data['fields'] = urldecode($arr_data['fields']);
        $json_result = post_curl_b($arr_data);
        $arr_result = json_decode($json_result, true);
	file_put_contents(S_ROOT.'data/qrcode.txt', 'ERROR:'.$int_id.'-----'.var_export($arr_result,true).PHP_EOL,FILE_APPEND);
        if($arr_result['ticket']){
            $arr_data = array();
            $arr_data['url'] = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($arr_result['ticket']);
            $arr_data['type'] = 'get';
            $json_result = post_curl_b($arr_data,30);
            $str_dir = 'upload_pic/user_qrcode/'.date('Ymd').'/';
            $str_file_path = S_ROOT.$str_dir;
            if(!is_dir($str_file_path)){
                mkdir($str_file_path,0775,true);
            }            
            $str_fname = md5($int_id).'.jpg';
            $str_file_name = $str_file_path.$str_fname;
            if(!file_exists($str_file_name)){
                $fopen = fopen($str_file_name, 'wb');
                fputs($fopen, $json_result);
                fclose($fopen);
            }else{
                file_put_contents($str_file_name, $json_result);
            }
            $obj_member = L::loadClass('member', 'index');
            $obj_member->update_member(array('uid' => $int_id), array('qrcode' => '/'.$str_dir.$str_fname));
            return $str_file_name;
        }else{
            return false;
        }
        
    }
    
    
    

}
