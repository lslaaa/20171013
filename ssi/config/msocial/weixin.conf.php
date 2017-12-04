<?php
!defined('LEM') && exit('Forbidden');
//微信登录
define('WEIXIN_APPID', 'wxa5eafebdfbde34dd');
define('WEIXIN_APP_SECRET', 'a16bfb7d7fd703c006130f9c31896a14');
define("WEIXIN_CALLBACK_URL", urlencode(M_URL.'/login/weixin_callback'));
//define("WEIXIN_M_CALLBACK_URL", '');
define('WEIXIN_APPNAME', '买家秀');

//微信消息->模板Id
$arr_template_id = array(
    'shipment' => '-Byc-sBNgrRxcEQ1ReUinAjUSxYB3kii7OKvn2daFOc',
    'payment_reminder' => 'rDSUJ3AoDeK8HAdbMVMyYMCJbSqaEC9giCMQN-d0r3I'//订单未支付通知
);


//微信登录

class weixin_login
{
    const APPID = 'wxa5eafebdfbde34dd';
    const APPSECRET = 'a16bfb7d7fd703c006130f9c31896a14';
    const CALLBACK_URL = 'http://20171013.demo.hugelem.cn/login/weixin_callback';
}




