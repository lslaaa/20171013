<?php

!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);

class mod_wxpay
{

    function __construct()
    {
        $extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
        if(method_exists($this, 'extra_' . $extra))
        {
            $str_function_name = 'extra_' . $extra;
            $this->$str_function_name();
        }
    }

    //正常流程微信支付
    function extra_index()
    {
        global $_SGLOBAL;
        include_once(S_ROOT . "lib/payment/wxpay/WxPayPubHelper.php");
        if(isset($_GET['order_id']) && isset($_GET['total_price']))
        {
            $order_id = intval($_GET['order_id']);
            $total_price = sprintf("%.2f", floatval(trim($_GET['total_price'])));
            $str_wxpay_return = isset($_GET['wxpay_return']) ? trim($_GET['wxpay_return']) : ('http://20171013.demo.hugelem.cn/wxpay');
			//echo $str_wxpay_return;exit;
            cookie('wxpay_return', $str_wxpay_return);
            $arr_data = array(
                'order_id' => $order_id,
                'total_price' => $total_price
            );
            $str_state = serialize($arr_data);
        }

        //使用jsapi接口
        $jsApi = new JsApi_pub();
        //=========步骤1：网页授权获取用户openid============
        //通过code获得openid
        if(!isset($_GET['code']))
        {
            //触发微信返回code码
            $url = $jsApi->createOauthUrlForCode(urlencode(WxPayConf_pub::JS_API_CALL_URL), $str_state);
			//echo $url;exit;
            Header("Location: $url");
        }
        else
        {
            //获取code码，以获取openid
            $openid = $_SGLOBAL['member']['openid'];
            if(empty($openid))
            {
                $code = $_GET['code'];
                $jsApi->setCode($code);
                $openid = $jsApi->getOpenId();
            }
			
            $state = $_GET['state'];
            $arr_order_data = unserialize(str_replace('\\', '', $state));
            $int_order_id = $arr_order_data['order_id'];
            $float_total_price = $arr_order_data['total_price'];
        }
        $str_wxpay_return = get_cookie('wxpay_return');
        //=========步骤2：使用统一支付接口，获取prepay_id============
        //使用统一支付接口
        $unifiedOrder = new UnifiedOrder_pub();
        $unifiedOrder->setParameter("openid", "$openid"); //商品描述
        $unifiedOrder->setParameter("body", "逸基因购物"); //商品描述
        //自定义订单号，此处仅作举例
        $out_trade_no = WxPayConf_pub::APPID . 'b' . $int_order_id;
        $unifiedOrder->setParameter("out_trade_no", $out_trade_no); //商户订单号
        $unifiedOrder->setParameter("total_fee", $float_total_price * 100); //总金额,分为单位
        $unifiedOrder->setParameter("notify_url", WxPayConf_pub::NOTIFY_URL); //通知地址
        $unifiedOrder->setParameter("trade_type", "JSAPI"); //交易类型
        $prepay_id = $unifiedOrder->getPrepayId();
        //=========步骤3：使用jsapi调起支付============
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsApi->getParameters();
        include template('template/index/wxpay');
    }


        function extra_liushui()
    {
        global $_SGLOBAL;
        include_once(S_ROOT . "lib/payment/wxpay/WxPayPubHelper.php");
        // if(isset($_GET['order_id']) && isset($_GET['total_price']))
        // {
        //     $order_id = intval($_GET['order_id']);
        //     $total_price = sprintf("%.2f", floatval(trim($_GET['total_price'])));
        //     $str_wxpay_return = isset($_GET['wxpay_return']) ? trim($_GET['wxpay_return']) : ('http://20171013.demo.hugelem.cn/wxpay');
        //     //echo $str_wxpay_return;exit;
        //     cookie('wxpay_return', $str_wxpay_return);
        //     $arr_data = array(
        //         'order_id' => $order_id,
        //         'total_price' => $total_price
        //     );
        //     $str_state = serialize($arr_data);
        // }

        //使用jsapi接口
        $jsApi = new JsApi_pub();
        //=========步骤1：网页授权获取用户openid============
        //通过code获得openid
        if(!isset($_GET['code']))
        {
            //触发微信返回code码
            $url = $jsApi->createOauthUrlForCode(urlencode('http://20171013.demo.hugelem.cn/wxpay/liushui'), $str_state);
            //echo $url;exit;
            Header("Location: $url");
        }
        else
        {
            //获取code码，以获取openid
            $openid = $_SGLOBAL['member']['openid'];
            if(empty($openid))
            {
                $code = $_GET['code'];
                $jsApi->setCode($code);
                $openid = $jsApi->getOpenId();
            }
            
            $state = $_GET['state'];
            $arr_order_data = unserialize(str_replace('\\', '', $state));
            $int_order_id = $arr_order_data['order_id'];
            $float_total_price = 0.01;
        }
        $str_wxpay_return = get_cookie('wxpay_return');
        //=========步骤2：使用统一支付接口，获取prepay_id============
        //使用统一支付接口
        $unifiedOrder = new UnifiedOrder_pub();
        $unifiedOrder->setParameter("openid", "$openid"); //商品描述
        $unifiedOrder->setParameter("body", "买家秀"); //商品描述
        //自定义订单号，此处仅作举例
        $out_trade_no = WxPayConf_pub::APPID . 'b' . time();
        $unifiedOrder->setParameter("out_trade_no", $out_trade_no); //商户订单号
        $unifiedOrder->setParameter("total_fee", 0.01 * 100); //总金额,分为单位
        $unifiedOrder->setParameter("notify_url", 'http://20171013.demo.hugelem.cn/third_pay_return/liushui_notify'); //通知地址
        $unifiedOrder->setParameter("trade_type", "JSAPI"); //交易类型
        $prepay_id = $unifiedOrder->getPrepayId();
        //=========步骤3：使用jsapi调起支付============
        // var_export($unifiedOrder->setParameter());
        // exit();
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsApi->getParameters();
        include template('template/index/wxpay');
    }

    function extra_pay_success()
    {
        global $_SGLOBAL;
        echo 'pay success！支付成功！';
        //$int_order_id = intval($_GET['order_id']);
        //include template('template/m_mall/cart/pay_success');
    }

    function extra_pay_fail()
    {
        echo '支付失败！';
    }

}
