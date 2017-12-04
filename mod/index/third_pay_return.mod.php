<?php

!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_third_pay_return {

    function __construct() {
        $extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
        if (method_exists($this, 'extra_' . $extra)) {
            $str_function_name = 'extra_' . $extra;
            $this->$str_function_name();
        }
    }

    //购物异步回调
    function extra_shopping_notify() {
        
        global $_SGLOBAL;
        $obj_alipay = L::loadClass('alipay', 'payment');
        if (!$obj_alipay->verify_notify()) {//验证未通过
            //file_put_contents(S_ROOT.'data/failed_'.time().'.txt',var_export($_POST,true));
            echo "fail"; //反馈给支付宝,请不要修改或删除
            exit();
        }

        $str_order_id = strtolower(trim($_POST['out_trade_no'])); //获得订单号
        $int_order_id = intval(ltrim($str_order_id, 'b'));
        $str_total_fee = number_format(trim($_POST['total_fee']), 2, '.', '');

        //记录日志
        $arr_log_data = array(
            'order_id' => $int_order_id, //订单id
            'pay_type' => 0, //支付类型为购物
            'is_update_ok' => 0, //默认更新失败
            'return_info' => str_addslashes(var_export($_POST, TRUE)),
            'in_date' => $_SGLOBAL['timestamp'],
        );

        if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
            //file_put_contents(S_ROOT.'data/success_order_id_'.time().'.txt','订单ID:'.$int_order_id);
            //1.根据订单号，获取订单信息
            $obj_order = L::loadClass('order', 'index');
            $arr_order = $obj_order->get_one_main($int_order_id);
            $str_main_total_fee = number_format($arr_order['total_price'], 2, '.', '');
            if ($str_total_fee != $str_main_total_fee) {
				//file_put_contents(S_ROOT.'data/price_error'.time().'.txt',$int_total_fee.','.$int_main_total_fee);
                echo "success";  ////反馈给支付宝,请不要修改或删除
            } else {
                //第三方交易信息
                $arr_third_pay_data = array(
                    'third_id' => str_addslashes($_POST['trade_no']), //交易号
                );
                $bool_update = $obj_order->update_main(array('order_id'=>$int_order_id),array('status'=>LEM_order::ORDER_PAY,'third_id'=>$arr_third_pay_data['third_id'],'pay_date'=>$_SGLOBAL['timestamp']));
				//file_put_contents(S_ROOT.'data/update_bool'.time().'.txt',$bool_update);
                if ($bool_update) {
                    $arr_log_data['is_update_ok'] = 1; //更新成功
                    echo "success";  ////反馈给支付宝,请不要修改或删除
                } else {
                    echo 'fail';
                }
            }
            //include template('template/mall/cart/pay_success');
        } else {
            echo "fail"; //反馈给支付宝,请不要修改或删除
        }
        $obj_alipay->log($arr_log_data);   //记录日志
    }

    //微信购物回调
    function extra_weixin_notify() {
        
        global $_SGLOBAL;
        include_once(S_ROOT . "lib/payment/wxpay/WxPayPubHelper.php");
        //使用通用通知接口
        $notify = new Notify_pub();

        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);

        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if ($notify->checkSign() == FALSE) {//这个验证签名要线上测试才知道
            //file_put_contents(S_ROOT.'data/failed_'.time().'.txt',var_export($_POST,true));
            $notify->setReturnParameter("return_code", "FAIL"); //返回状态码
            $notify->setReturnParameter("return_msg", "签名失败"); //返回信息
        } else {

            //记录日志
            $int_order_id = str_replace($notify->data["appid"] . 'b', '', $notify->data["out_trade_no"]);
            $int_order_id = intval($int_order_id);
            $int_total_fee = number_format($notify->data["total_fee"], 2, '.', '');
            $str_fee_type = strtoupper(trim($notify->data["fee_type"]));

            //file_put_contents(S_ROOT.'data/success_order_id_'.time().'.txt','订单ID:'.$int_order_id);
            $obj_wxpay = L::loadClass('wxpay', 'payment');
            $arr_log_data = array(
                'order_id' => $int_order_id, //订单id
                'pay_type' => 0, //支付类型为购物
                'is_update_ok' => 0, //默认更新失败
                'return_info' => str_addslashes(var_export($xml, TRUE)),
                'in_date' => $_SGLOBAL['timestamp'],
            );
            //设置默认通知微信的返回码为SUCCESS,如果微信提示支付成功但是本地更新数据库失败则让通知微信接收失败，让微信再次来通知

            if ($notify->data["return_code"] != "FAIL" && $notify->data["result_code"] != "FAIL") {

                $obj_order = L::loadClass('order', 'index');
                $arr_order = $obj_order->get_one_main($int_order_id);

                $int_main_total_fee = ($arr_order['total_price']) * 100;
				$int_main_total_fee = number_format($int_main_total_fee, 2, '.', '');//请不要调整此处与上面$int_total_fee = number_format($notify->data["total_fee"], 2, '.', '');处否则会出现付款金额为19.9 bug
                                
                if ($str_fee_type != 'CNY' || $int_total_fee != $int_main_total_fee) {//币种或者金额非法
					//file_put_contents(S_ROOT.'data/price_error'.time().'.txt',$int_total_fee.','.$int_main_total_fee);
                    $notify->setReturnParameter("return_code", "SUCCESS");
                } else {
                    //第三方交易信息
                    $arr_third_pay_data = array(
                        'third_id' => str_addslashes($notify->data["transaction_id"]), //交易号
                        //'pay_type'=>LEM_order::PAY_TYPE_WEIXINPAY
                    );
                    $bool_update = $obj_order->update_main(array('order_id'=>$int_order_id),array('status'=>LEM_order::ORDER_PAY,'third_id'=>$arr_third_pay_data['third_id'],'pay_date'=>$_SGLOBAL['timestamp']));
                    //file_put_contents(S_ROOT.'data/update_bool'.time().'.txt',$bool_update);
                    if ($bool_update) {
                        $arr_log_data['is_update_ok'] = 1; //更新成功  
                        $notify->setReturnParameter("return_code", "SUCCESS");
                    } else {
                        $notify->setReturnParameter("return_code", "FAIL"); //返回状态码
                    }
                }
            }

            $obj_wxpay->log($arr_log_data);
        }

        $returnXml = $notify->returnXml();
        echo $returnXml;
    }

    function extra_liushui_notify() {
        
        global $_SGLOBAL;
        include_once(S_ROOT . "lib/payment/wxpay/WxPayPubHelper.php");
        //使用通用通知接口
        $notify = new Notify_pub();

        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);

        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if ($notify->checkSign() == FALSE) {//这个验证签名要线上测试才知道
            //file_put_contents(S_ROOT.'data/failed_'.time().'.txt',var_export($_POST,true));
            $notify->setReturnParameter("return_code", "FAIL"); //返回状态码
            $notify->setReturnParameter("return_msg", "签名失败"); //返回信息
        } else {
            $notify->setReturnParameter("return_code", "SUCCESS");
        }

        $returnXml = $notify->returnXml();
        echo $returnXml;
    }

}
