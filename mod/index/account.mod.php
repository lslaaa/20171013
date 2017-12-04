<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_account {
        function __construct() {
                global $_SGLOBAL;
                if($_SGLOBAL['member']['uid']<=0){
                        jump('/login'); 
                }
                $extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
                
                if (method_exists($this, 'extra_' . $extra)) {
                        $str_function_name = 'extra_' . $extra;
                        $this->$str_function_name();
                }
        }

        function extra_perfecting_data(){
                global $_SGLOBAL;
                $arr_member = $_SGLOBAL['member'];
                $obj_member = L::loadClass('member','index');
                if(submit_check('formhash')){
                        var_export($_POST);
                        // exit();
                        $int_card_type  = intval($_POST['card_type']);
                        $str_card_num   = str_addslashes($_POST['card_num']);
                        $str_card_name  = str_addslashes($_POST['card_name']);
                        $int_province   = intval($_POST['province']);
                        $int_city       = intval($_POST['city']);
                        $int_area       = intval($_POST['area']);
                        $int_sex        = intval($_POST['sex']);
                        $int_age        = intval($_POST['age']);
                        $int_shengao    = intval($_POST['shengao']);
                        $arr_sanwei     = str_addslashes($_POST['sanwei']);
                        $int_tizhong    = intval($_POST['tizhong']);
                        $str_shoes      = str_addslashes($_POST['shoes']);
                        $str_pants      = str_addslashes($_POST['pants']);
                        $str_clothes    = str_addslashes($_POST['clothes']);
                        $int_is_jinshi  = intval($_POST['is_jinshi']);
                        $int_is_shengyu = intval($_POST['is_shengyu']);
                        $int_is_hunyin  = intval($_POST['is_hunyin']);
                        $str_xueli      = str_addslashes($_POST['xueli']);
                        $str_job        = str_addslashes($_POST['job']);
                        $int_income     = str_addslashes($_POST['income']);
                        $str_nickname   = str_addslashes($_POST['nickname']);
                        $str_content    = str_addslashes($_POST['content']);
                        $arr_data  = array(
                                'sex'        =>$int_sex,
                                'realname'   =>$str_nickname,

                        );
                        $arr_data_detail = array(
                                'uid'        =>$arr_member['uid'],
                                'card_type'  =>$int_card_type,
                                'age'        =>$int_age,
                                'card_num'   =>$str_card_num,
                                'card_name'  =>$str_card_name,
                                'sanwei'     =>implode(',', $arr_sanwei),
                                'shoes'      =>$str_shoes,
                                'pants'      =>$str_pants,
                                'clothes'    =>$str_clothes,
                                'xueli'      =>$str_xueli,
                                'job'        =>$str_job,
                                'income'     =>$int_income,
                                'content'    =>$str_content,
                                'province'   =>$int_province,
                                'city'       =>$int_city,
                                'area'       =>$int_area,
                                'shengao'    =>$int_shengao,
                                'tizhong'    =>$int_tizhong,
                                'is_jinshi'  =>$int_is_jinshi,
                                'is_shengyu' =>$int_is_shengyu,
                                'is_hunyin'  =>$int_is_hunyin,
                                'is_jinshi'  =>$int_is_jinshi,
                        );
                        $result = $obj_member->update_member(array('uid'=>$arr_member['uid']),$arr_data);
                        $result = $obj_member->update_member_detail(array('uid'=>$arr_member['uid']),$arr_data_detail);
                        callback(array('status'=>200,'info'=>'提交完成'));

                }
                $arr_member = $obj_member->get_one($arr_member['uid']);
                $arr_member['detail']['sanwei'] && $arr_member['detail']['sanwei'] = explode(',',$arr_member['detail']['sanwei']);
                // var_export($arr_member);
                include template('template/index/member/perfecting_data');
        }

        function extra_binding_account(){
                global $_SGLOBAL;
                $arr_member = $_SGLOBAL['member'];
                $obj_member_taobao = L::loadClass('member_taobao','index');
                $arr_dingwei = unserialize(get_cookie('district'));
                $int_type = intval($_GET['type']);
                $int_type = $int_type ? $int_type:1;
                if(submit_check('formhash')){
                        if (!$arr_dingwei['city']) {
                                callback(array('status'=>304,'info'=>'系统未获取你的位置，请退出微信重新进入系统'));
                        }
                        // var_export($_POST);
                        $str_taobao = str_addslashes($_POST['taobao']);
                        $int_sex = str_addslashes($_POST['sex']);
                        $arr_pic = str_addslashes($_POST['pic_url']);
                        $int_type = intval($_POST['type']);

                        $arr_data = array(
                                'uid' => $arr_member['uid'],
                                'type' => $int_type,
                                'taobao' => $str_taobao,
                                'sex' => $int_sex,
                                'pics' => implode(',', $arr_pic),
                                'in_date' => time()
                        );
                        $arr_taobao_one = $obj_member_taobao->get_one(array('uid'=>$arr_member['uid'],'type'=>$int_type));
                        if ($arr_taobao_one) {
                                $arr_data['is_check'] = 0;
                                $obj_member_taobao->update(array('id'=>$arr_taobao_one['id']),$arr_data);
                                callback(array('status'=>200,'info'=>'提交成功，等待审核'));
                        }else{
                                $result = $obj_member_taobao->insert($arr_data);
                        }
                        // var_export($result);
                        if ($result) {
                                callback(array('status'=>200,'info'=>'提交成功，等待审核'));
                        }else{
                                callback(array('status'=>304,'info'=>'提交失败'));
                        }
                }
                $arr_data = array('uid'=>$arr_member['uid'],'type'=>$int_type);
                $arr_member_taobao = $obj_member_taobao->get_one($arr_data);
                $arr_member_taobao['pics'] = explode(',', $arr_member_taobao['pics']);
                include template('template/index/binding_account');
        }

        function extra_screenshots(){
                global $_SGLOBAL;
                $arr_member = $_SGLOBAL['member'];
                include template('template/index/screenshots');
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