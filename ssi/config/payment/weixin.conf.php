<?php

!defined('LEM') && exit('Forbidden');

//神秘果商户key   8845077c91aa599cb5520362c3a0bsmg

/**
 * 	配置账号信息
 */
class WxPayConf_pub
{

    //=======【基本信息设置】=====================================
    //微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
    const APPID = 'wxa5eafebdfbde34dd';
    //受理商ID，身份标识
    const MCHID = '1490556992';
    //商户支付密钥Key。审核通过后，在微信发送的邮件中查看
    const KEY = '5d6f4f6b5b415e7841a0433a8bbf9a4c';
    //JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
    const APPSECRET = 'a16bfb7d7fd703c006130f9c31896a14';
    //=======【JSAPI路径设置】===================================
    //获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
    const JS_API_CALL_URL = 'http://20171013.demo.hugelem.cn/wxpay'; //微信支付
    const CHARGE_JS_API_CALL_URL = 'http://20171013.demo.hugelem.cn/?mod=wxpay'; //微信充值支付地址
    //=======【证书路径设置】=====================================
    //证书路径,注意应该填写绝对路径
    const SSLCERT_PATH = '/data0/htdocs/privacy/wx_zhengshu/apiclient_cert.pem';
    const SSLKEY_PATH = '/data0/htdocs/privacy/wx_zhengshu/apiclient_key.pem';
    //=======【异步通知url设置】===================================
    //异步通知url，商户根据实际开发过程设定
    const NOTIFY_URL = 'http://20171013.demo.hugelem.cn/third_pay_return/weixin_notify';
    //=======【curl超时设置】===================================
    //本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
    const CURL_TIMEOUT = 30;
	
	
	//临时提现公众号
	//=======【基本信息设置】=====================================
    //微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
    const APPID_B = 'wx15174efb7405bd38';
    //受理商ID，身份标识
    const MCHID_B = '1415717802';
    //商户支付密钥Key。审核通过后，在微信发送的邮件中查看
    const KEY_B = '4ed448436ee259d456ede46dfc14gf65';
    //JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
    const APPSECRET_B = '4ed448436ee259d456ede46dfc1420ed';
    
    const SSLCERT_PATH_B = '/data0/htdocs/privacy/wx_zhengshu/apiclient_cert_b.pem';
    const SSLKEY_PATH_B = '/data0/htdocs/privacy/wx_zhengshu/apiclient_key_b.pem';
    
    //测试路径
    //const SSLCERT_PATH_B = '/data0/coder/htdocs_ljf/demo.hugelem.cn/smgkjjt/ssi/config/payment/apiclient_cert_b.pem';
    //const SSLKEY_PATH_B = '/data0/coder/htdocs_ljf/demo.hugelem.cn/smgkjjt/ssi/config/payment/apiclient_key_b.pem';
}

?>