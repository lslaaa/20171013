<?php

!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
/*
 * 注意：
 * 1、自定义菜单最多包括3个一级菜单，每个一级菜单最多包含5个二级菜单。
 * 2、一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替。
 * 3、创建自定义菜单后，由于微信客户端缓存，需要24小时微信客户端才会展现出来。测试时可以尝试取消关注公众账号后再次关注，则可以看到创建后的效果。
 *
 *
 * */
class mod_weixin
{

    //正式
    private $str_appid;
    private $str_secret;
    private $subscribe = true;

    //推荐数量
    function __construct()
    {
        //$_SERVER['REMOTE_ADDR'] = '192.168.2.235';
        include_once( S_ROOT . 'ssi/config/msocial/weixin.conf.php' );
        $this->str_appid = WEIXIN_APPID;
        $this->str_secret = WEIXIN_APP_SECRET;
        $extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
        if(method_exists($this, 'extra_' . $extra))
        {
            $str_function_name = 'extra_' . $extra;
            $this->$str_function_name();
        }
    }

//=======================================================事件响应start================================================================
    //接收消息和事件推送指定单位
    function extra_index()
    {
        //测试验证有效性，测试完，请注释
       // $obj_weixin = L::loadClass('weixin','index');
       // $obj_weixin->verify();exit;   
        $str_post = $GLOBALS["HTTP_RAW_POST_DATA"];
        
        if(!empty($str_post))
        {
            
            libxml_disable_entity_loader(true); //禁止加载外部实体
            $obj_post = simplexml_load_string($str_post, 'SimpleXMLElement', LIBXML_NOCDATA);
            $str_type = $obj_post->MsgType; //接收消息类型，暂时只要两种类型，text,event
            $mix_xml = $this->response_msgtype_action($str_type, $obj_post); //事件响应动作
            //更新最后回复公众号时间
            //$this->update_last_send_date($obj_post);
//            if($this->subscribe)
//            {
//                $this->update_weixin_remark($obj_post); //设置用户备注名
//            }
            if($mix_xml)
            {
                echo $mix_xml;
            }
        }
        else
        {
            exit("请参照微信官方文档开发");
        }
    }

    function response_msgtype_action($str_receive_type, $obj_post)
    {
        $arr_receive_type = array("text", "image", "voice", "video", "link", "event");
        if(empty($str_receive_type) || !in_array($str_receive_type, $arr_receive_type))
        {
            exit("接受数据包有误，类型传入错误");
        }

        if(!is_object($obj_post) || empty($obj_post))
        {
            exit("接受数据包有误！");
        }
        $str_fromUsername = $obj_post->FromUserName;
        $str_toUsername = $obj_post->ToUserName;
        $arr_head['from'] = $str_fromUsername;
        $arr_head['to'] = $str_toUsername;
        switch($str_receive_type)
        {
           
            //用户点击事件
            case "event":
                $str_event = $obj_post->Event;                
                //$str_key =$obj_post->EventKey;//菜单的自定义的key值，可以根据此值判断用户点击了什么内容，从而推送不同信息
                $arr_return = $this->push_event($str_event, $obj_post); //官方回复用户内容
                $str_xml = "";
                if(isset($arr_return['push']['data']))
                {
                    $str_response_type = is_array($arr_return['push']['data']['list']) ? 'news' : 'text';
                    $arr_baiji_content = is_array($arr_return['push']['data']['list']) ? $this->push_news($arr_return['push']['data']) : array('content' => $arr_return['push']['data']['text']);
                    if($str_response_type == 'text')
                    {
                        $arr_baiji_content['content'] = str_replace(array('\"', '\\\''), array('"', '\''), $arr_baiji_content['content']);
                    }
                    $str_xml = $this->make_xml($str_response_type, $arr_head, $arr_baiji_content);
                }
                //if(!empty($str_baiji_content)){
                //  $str_response_type = 'text';
                //  $arr_baiji_content = array('content'=>$str_baiji_content);//现在主要以文本的形式回复
                //  $str_xml = $this->make_xml($str_response_type,$arr_head,$arr_baiji_content);
                //  } 
                break;

            //接收普通消息，文本消息
            case "text":
                $arr_b_content = array();
                $str_content = $obj_post->Content;
                $obj_weixin = L::loadClass('weixin', 'index');
                $str_response = $obj_weixin->wechat_custom_response($str_content);
                $arr_b_content = array('content' => $str_response);
                $arr_b_content['content'] = str_replace(array('\"', '\\\''), array('"', '\''), $arr_b_content['content']);
                $str_xml = $this->make_xml('text', $arr_head, $arr_b_content);
//                $obj_multi_curl = L::loadClass('multi_curl_mobile');
//                $arr_data = array(
//                    'type' => 'get',
//                    'return_key' => 'push',
//                    'url' => 'http://weixinapi.xxxxxx.com/?mod=get&extra=push',
//                    'fields' => array('kwd' => strtolower($str_content), 'mp_from' => 'maker3000')
//                );
//                $obj_multi_curl->_add($arr_data);
//                $arr_return = $obj_multi_curl->_exec();
                
                
                
//                $str_xml = "";
//                if(isset($arr_return['push']['data']))
//                {
//                    $str_response_type = is_array($arr_return['push']['data']['list']) ? 'news' : 'text';
//                    $arr_baiji_content = is_array($arr_return['push']['data']['list']) ? $this->push_news($arr_return['push']['data']) : array('content' => $arr_return['push']['data']['text']);
//                    if($str_response_type == 'text')
//                    {
//                        $arr_baiji_content['content'] = str_replace(array('\"', '\\\''), array('"', '\''), $arr_baiji_content['content']);
//                    }
//                    $str_xml = $this->make_xml($str_response_type, $arr_head, $arr_baiji_content);
//                }
                //print_r($str_xml);exit;
                break;
            //接收普通消息，图片消息
            case "image":
                $str_xml = "";
                //$this->push_image();
                break;
            //接收普通消息，语音消息
            case "voice":
                /*
                  $str_xml = "";
                  $str_text = strval($obj_post->Recognition);
                  $obj_multi_curl = L::loadClass('multi_curl_mobile');
                  $arr_data = array(
                  'type' => 'post',
                  'return_key' => 'talk',
                  'url' => 'http://staticapi.xxxxxx.com/?mod=talk&extra=insert',
                  'fields' => array('open_id'=>strval($str_fromUsername),'content'=>$str_text, 'mp_from' => 'maker3000'),
                  );
                  $obj_multi_curl->_add($arr_data);
                  $arr_return = $obj_multi_curl->_exec();
                  if($arr_return['talk']['info']){
                  $str_xml = $this->make_xml('text',$arr_head,array('content'=>  $arr_return['talk']['info']));
                  }
                 */
                //$this->push_voice();
                break;
            //接收普通消息，视频消息
            case "video":
                $str_xml = "";
                //$this->push_video();
                break;
            //接收普通消息，小视频消息，需要再加
            //接收普通消息，地理消息，需要再加
            //普通消息，链接消息
            case "link":
                $str_xml = "";
                //$this->push_link();
                break;

            default:
                $str_xml = "";
                break;
        }
        return $str_xml; //推送包
    }

    private function push_news($arr_baiji_mongo)
    {
        if(empty($arr_baiji_mongo))
        {
            return array();
        }
        $arr_content['total'] = $arr_baiji_mongo['total'];
        if($arr_baiji_mongo['total'])
        {
            foreach($arr_baiji_mongo['list'] as $key => $val)
            {
                $arr_content['list'][$key]['title'] = $val['text'];
                //$arr_content['list'][$key]['description'] = $val['remark'];
                $arr_content['list'][$key]['description'] = '';
                $arr_content['list'][$key]['picurl'] = $val['pic'];
                $arr_content['list'][$key]['url'] = $val['url'];
            }
        }
        return $arr_content;
    }

    //推送事件event
    private function push_event($str_event, $obj_post)
    {
        if(empty($str_event))
        {
            return '';
        }
        switch(strtolower($str_event))
        {
            //点击推送事件
            case "click":
                $str_event_key = $obj_post->EventKey;
                $str_content = $this->do_click(strtolower($str_event_key));
                $this->do_click_menu($obj_post->EventKey, $obj_post->FromUserName);
                break;
            //关注推送事件
            case "subscribe":
                $str_content = $this->do_subscribe($obj_post);
                break;
            //取消关注推送事件
            case "unsubscribe":
                $this->subscribe = false;
                $this->do_unsubscribe($obj_post);
                break;
            //卡券通过审核事件
            case "card_pass_check":
                $str_content = $this->do_card_pass_check($obj_post, 1);
                break;
            //卡券通过审核事件
            case "card_not_pass_check":
                $str_content = $this->do_card_pass_check($obj_post, -1);
                break;
            //用户领取卡券事件
            case "user_get_card":
                //$str_content = $this->do_user_get_card();
                $str_content = "";
                break;
            //用户删除卡券事件
            case "user_del_card":
                // $str_content = $this->do_user_del_card();
                $str_content = "";
                break;
            //卡券被核销时事件
            case "user_consume_card":
                // $str_content = $this->do_user_consume_card();
                $str_content = "";
                break;
            //用户在进入会员卡事件
            case "user_view_card":
                //$str_content = $this->do_user_view_card();
                $str_content = "";
                break;
            //用户在卡券里点击查看公众号进入会话时（需要用户已经关注公众号）事件
            case "user_enter_session_from_card":
                //$str_content = $this->do_user_enter_session_from_card();
                $str_content = "";
                break;
            //点击菜单
            case "view":
                $this->do_click_menu($obj_post->EventKey, $obj_post->FromUserName);
                $str_content = "";
                break;
            default:
                $str_content = "";
                break;
        }
        return $str_content;
    }

    private function do_click($str_event_key)
    {
        return '';
    }

    private function do_click_menu($str_url, $str_open_id)
    {
        return '';
    }

    private function do_subscribe($obj_post)
    {
	    file_put_contents(S_ROOT.'data/reg.txt', 'weixin push:'.var_export($obj_post,true).PHP_EOL,FILE_APPEND);
        $str_event_key = $obj_post->EventKey;
        $int_tuijian_uid = 0;
        if($str_event_key){
            $arr_event_key = explode('_', $str_event_key);
            $int_tuijian_uid = $arr_event_key[1]; //上线id
            $int_tuijian_uid = intval($int_tuijian_uid);
        }
        $obj_member = L::loadClass('member', 'index');
        $obj_member->subscribe_reg_queue($obj_post->FromUserName,$int_tuijian_uid); //推荐人id
        
        //推送  关注欢迎语
        $obj_weixin_custom = L::loadClass('weixin_custom', 'index');
        $str_content = $obj_weixin_custom->get_subscribe_msg();
        $arr_return = array();
        $arr_return['push']['data']['text'] = $str_content;
        
        return $arr_return;
    }

    private function do_unsubscribe($obj_post)
    {
        
        //file_put_contents('./b.txt',var_export($arr_return,true));
    }

    //status =1为通过审核，-1为不通过
    private function do_card_pass_check($obj_post, $int_status = 1)
    {
        
    }

    //更新最后回复公众号时间
    function update_last_send_date($obj_post)
    {
       
    }

    //设置用户备注名
    function update_weixin_remark($obj_post)
    {
        
    }

    /*
     * 输出给微信端的xml组装，
     * $str_response_type回复信息分类有:text文字类型   news：图文类型，image：图片消息，voice语音回复，video视频回复
     * $arr_header  所有回复公用头部，为发送方和接收方
     * $arr_content  发送的内容，视我方设计的回复类型而定
     * */

    private function make_xml($str_response_type, $arr_header, $arr_content)
    {
        $str_tpl = "";
        $int_time = time();
        $str_tpl.=$this->xml_header($arr_header['from'], $arr_header['to'], $int_time, $str_response_type);
        $str_tpl.=$this->xml_content($str_response_type, $arr_content);
        $str_tpl.=$this->xml_footer();
        return $str_tpl;
    }

    //xml头部公共体
    /*
     * $from_user  微信开发者
     * $to_user
     * $int_time
     * $str_type   事件类型
     * */
    private function xml_header($from_user, $to_user, $int_time, $str_response_type)
    {
        $str_tpl = "<xml>
                    <ToUserName><![CDATA[{$from_user}]]></ToUserName>
                    <FromUserName><![CDATA[{$to_user}]]></FromUserName>
                    <CreateTime>{$int_time}</CreateTime>
                    <MsgType><![CDATA[{$str_response_type}]]></MsgType>";
        return $str_tpl;
    }

    //xml内容体
    /*
     * $str_response_type  回复信息分类有:text文字类型   news：图文类型，image：图片消息，voice语音回复，video视频回复
     *
     *
     * */
    private function xml_content($str_response_type, $arr_content)
    {
        $str_tpl = "";
        switch($str_response_type)
        {
            //回复文本消息
            case "text":
                $str_tpl.="<Content><![CDATA[{$arr_content['content']}]]></Content>";
            //回复图片消息
            case "image":
                $str_tpl.="";
                break;
            //回复语音消息
            case "voice":
                $str_tpl.="";
                break;
            //回复视频消息
            case "video":
                $str_tpl.="";
                break;
            //回复音乐消息
            case "music":
                $str_tpl.="";
                break;
            //回复图文消息
            case "news":
                $str_tpl.="<ArticleCount>{$arr_content['total']}</ArticleCount>";
                $str_tpl.="<Articles>";
                if($arr_content['total'])
                {
                    foreach($arr_content['list'] as $val)
                    {
                        $str_tpl.="<item>
                                        <Title><![CDATA[{$val['title']}]]></Title>
                                        <Description><![CDATA[{$val['description']}]]></Description>
                                        <PicUrl><![CDATA[{$val['picurl']}]]></PicUrl>
                                        <Url><![CDATA[{$val['url']}]]></Url>
                                    </item>";
                    }
                }
                $str_tpl.="</Articles>";
                break;
            default:
                break;
        }
        return $str_tpl;
    }

    //xml闭合
    private function xml_footer()
    {
        return "</xml>";
    }

//=======================================================事件响应end================================================================
//=======================================================创建自定义菜单start======================================================
    //创建自定义菜单
    function extra_create_menu()
    {
        //exit;
        $access_token = $this->get_access_token(); //获取access_token
        $this->create_menu($access_token); //创建菜单
    }

    //获取token_access
    private function get_access_token()
    {
	$obj_weixinapi = L::loadClass('weixinapi');
        $access_token = $obj_weixinapi->get_access_token();
        return $access_token;
    }

    //创建菜单
    private function create_menu($access_token)
    {
        //Step2,通过code换取网页授权access_token
        $arr_data['url'] = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $access_token;
        $arr_data['type'] = 'post';//{"type":"view","name":"点击进入","url":"http://m.smgkjjt.com/"},{"type":"view","name":"购物车","url":"http://m.smgkjjt.com/mall/cart"},{"type":"view","name":"企业介绍","url":"http://m.smgkjjt.com/i/company"}, {"type":"view","name":"激活账户","url":"http://m.smgkjjt.com/i/index/activation"},
        $json = '{"button":[

        {"name":"激活用户",
			"sub_button":
			[
				{"type":"view","name":"新手指南","url":"http://m.smgkjjt.com/i/company"},
				{"type":"view","name":"激活账户","url":"http://m.smgkjjt.com/i/index/activation"},
				{"type":"view","name":"商家入驻申请","url":"http://m.smgkjjt.com/seller/index/join"},
				{"type":"view","name":"体验店加盟申请","url":"http://m.smgkjjt.com/?mod=index&extra=tiyan"}
			]
        },
        {"name":"点击进入",
			"sub_button":
			[    
			 
				{"type":"view","name":"线下体验店","url":"http://m.smgkjjt.com/tiyan"},
				{"type":"view","name":"附近美食","url":"http://m.smgkjjt.com/seller/index/cid_1-1"},
				{"type":"view","name":"附近娱乐休闲","url":"http://m.smgkjjt.com/seller/index/cid_1-2"},
				{"type":"view","name":"成本价购物","url":"http://m.smgkjjt.com/mall/index/cate"},
				{"type":"view","name":"进入平台","url":"http://m.smgkjjt.com/"}
			]
        },
        {"name":"更多服务",
			"sub_button":
			[
				{"type":"view","name":"推广二维码","url":"http://m.smgkjjt.com/index/my_qrcode"},
				{"type":"view","name":"大学生创业","url":"http://m.smgkjjt.com/index/my_qrcode?type=b"},
				{"type":"view","name":"余额提现","url":"http://m.smgkjjt.com/i/account/withdraw"},
				{"type":"view","name":"个人中心","url":"http://m.smgkjjt.com/i"}
			]
        }]
        }';

        $arr_data['fields'] = $json;
        $json_result = post_curl_b($arr_data);
        $arr_result = json_decode($json_result, 1);
        echo $json_result;
    }

    //删除微信菜单
//    function extra_delete_menu()
//    {
//        exit;
//        global $_SGLOBAL;
//        //Step1,获取access_token
//        $arr_fields = array(
//            'appid' => $this->str_appid,
//            'secret' => $this->str_secret,
//        );
//        $arr_data['url'] = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&' . http_build_query($arr_fields);
//        $json_result = post_curl($arr_data);
//        $arr_result = json_decode($json_result, 1);
//        if(!$arr_result['access_token'])
//        {
//            echo '获取token出现错误';
//            exit;
//        }
//        $access_token = $arr_result['access_token']; //获取到的access_token
//        $arr_data['url'] = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=' . $access_token;
//        $json_result = post_curl($arr_data);
//        $arr_result = json_decode($json_result, 1);
//        print_r($arr_result);
//        exit;
//    }

//=======================================================创建自定义菜单end======================================================
}
