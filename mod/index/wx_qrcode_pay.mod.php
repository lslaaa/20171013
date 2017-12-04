<?php

!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);

class mod_wx_qrcode_pay {

    function __construct() {
        $extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
        if (method_exists($this, 'extra_' . $extra)) {
            $str_function_name = 'extra_' . $extra;
            $this->$str_function_name();
        }
    }

    //扫码支付
    function extra_index() {

        //扫码支付，接收微信请求
        
        include_once(S_ROOT . "lib/payment/wxpay/WxPayPubHelper.php");
        $nativeCall = new NativeCall_pub();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        //file_put_contents(S_ROOT . 'data/data_wx_qrcode_pay.txt', $xml);
        $nativeCall->saveData($xml);
        if ($nativeCall->checkSign() == FALSE) {
            $nativeCall->setReturnParameter("return_code", "FAIL"); //返回状态码
            $nativeCall->setReturnParameter("return_msg", "签名失败"); //返回信息
        }
        $openid = $nativeCall->getOpenId();
        $int_order_id = $nativeCall->getProductId();
		if(empty($int_order_id)){
			return false;	
		}
		//$int_order_id = 53214;
        $obj_order = L::loadClass('order', 'index');
        $arr_order = $obj_order->get_one_main($int_order_id);
        //file_put_contents(S_ROOT.'data/order_data.txt', $int_order_id.var_export($arr_order,1));
        if (is_array($arr_order) && !empty($arr_order)) {
            $int_order_total_price = number_format($arr_order['total_price'], 2, '.', '')*100;
            $unifiedOrder = new UnifiedOrder_pub();
            $unifiedOrder->setParameter("openid", "$openid"); //商品描述
            $unifiedOrder->setParameter("body", "逸基因购物"); //商品描述
            //自定义订单号，此处仅作举例
            $out_trade_no = WxPayConf_pub::APPID . 'b' . $int_order_id;
            //$out_trade_no = WxPayConf_pub::APPID . "$timeStamp";
            $unifiedOrder->setParameter("out_trade_no", "$out_trade_no"); //商户订单号
            $unifiedOrder->setParameter("total_fee", "$int_order_total_price"); //总金额,分为单位
            $unifiedOrder->setParameter("notify_url", WxPayConf_pub::NOTIFY_URL); //通知地址
            $unifiedOrder->setParameter("trade_type", "NATIVE"); //交易类型
            $unifiedOrder->setParameter("product_id", "$int_order_id"); //用户标识
            $prepay_id = $unifiedOrder->getPrepayId();

            $nativeCall->setReturnParameter("return_code", "SUCCESS"); //返回状态码
            $nativeCall->setReturnParameter("result_code", "SUCCESS"); //业务结果
            $nativeCall->setReturnParameter("prepay_id", "$prepay_id"); //预支付ID
        } else {
            $nativeCall->setReturnParameter("return_code", "SUCCESS"); //返回状态码
            $nativeCall->setReturnParameter("result_code", "FAIL"); //业务结果
            $nativeCall->setReturnParameter("err_code_des", "此订单无效"); //业务结果
        }
        $returnXml = $nativeCall->returnXml();
		//file_put_contents(S_ROOT.'data/xml_data.txt', $int_order_id.var_export($returnXml,1));
        echo $returnXml;
    }

}
