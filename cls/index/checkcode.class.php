<?php

!defined('LEM') && exit('Forbidden');
class LEM_checkcode extends mysqlDBA {
    /*
     * 添加信息
     */
    function insert($arr_data) {
        $sql = "INSERT INTO " . $this->index('checkcode') . make_sql($arr_data, 'insert');
        $this->db->query($sql);
        return $this->db->insert_id();
    }
    
    
    function get_one($arr_where) {
        $str_sql = "SELECT * FROM " . $this->index('checkcode') . " WHERE ". make_sql($arr_where, 'where') . " ORDER BY `id` desc";
       // echo $str_sql;
        return $this->db->get_one($str_sql);	
    }  
    
    
    
    //阿里大于发送手机注册验证码
    // function alidayu_send($str_mobile, $str_code){
    //     require_once(S_ROOT."lib/alidayu/top/TopClient.php");
    //     require_once(S_ROOT."lib/alidayu/top/request/AlibabaAliqinFcSmsNumSendRequest.php");
    //     $c = new TopClient;
    //     $req = new AlibabaAliqinFcSmsNumSendRequest;
    //     $req ->setExtend("");
    //     $req ->setSmsType("normal");
    //     $req ->setSmsFreeSignName("百星珠宝");
    //     $json_param = json_encode(array('number' => $str_code));        
    //     $req ->setSmsParam($json_param);
    //     $req ->setRecNum($str_mobile);
    //     $req ->setSmsTemplateCode("SMS_60260033");
    //     return $c ->execute( $req );
    // }

        //阿里大于发送手机注册验证码
    function alidayu_send($str_mobile, $str_code){
        $product = "Dysmsapi";
        //短信API产品域名
        $domain = "dysmsapi.aliyuncs.com";
        //暂时不支持多Region
        $region = "cn-hangzhou";
        
        //初始化访问的acsCleint
        $profile = DefaultProfile::getProfile($region, self::ACCESS_KEY_ID, self::ACCESS_KEY_SECRET);
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        $acsClient= new DefaultAcsClient($profile);
        
        $request = new Dysmsapi\Request\V20170525\SendSmsRequest;
        //必填-短信接收号码
        $request->setPhoneNumbers($str_mobile);
        //必填-短信签名
        $request->setSignName("海归知产");
        //必填-短信模板Code
        $request->setTemplateCode("SMS_94170006");
        //选填-假如模板中存在变量需要替换则为必填(JSON格式)
        $request->setTemplateParam("{\"code\":\"".$str_code."\"}");
        //选填-发送短信流水号
        $request->setOutId("1234");
        //发起访问请求
        $acsResponse = $acsClient->getAcsResponse($request);
        return $acsResponse;
    }
    
    
    
}
