<?php
!defined('LEM') && exit('Forbidden');

//define('SHOW_SQL', 1);
//define('SHOW_REDIS_KEY', 1);
//define('CLEAR_REDIS', 1);
//define('SHOW_MONGO', 1);
class mod_find {
        function __construct() {
                $extra = !empty($_GET['extra']) ? $_GET['extra'] : (!empty($_POST['extra']) ? $_POST['extra'] : 'index');
                if (method_exists($this, 'extra_' . $extra)) {
                        $str_function_name = 'extra_' . $extra;
                        $this->$str_function_name();
                }
        }

        function extra_index(){
                global $_SGLOBAL;
                // var_export(get_cookie('r_uid'));
                $arr_language = $_SGLOBAL['language'];
                $int_page_id = 6;
                $obj_page = L::loadClass('page','index');
                $obj_ads = L::loadClass('ads','index');
                $arr_banner = $obj_ads->get_list(array('ads_cid'=>11,'is_del'=>0),1,1);
                $arr_banner && $arr_banner = current($arr_banner['list']);
                
                $arr_content = $obj_page->get_one($int_page_id);
                $arr_page_cat = $obj_page->get_cat_list(array('is_del'=>0),1,100);
                $arr_page_cat_b = format_array_val_to_key($arr_page_cat,'page_id');
                $str_page_title = $arr_page_cat_b[$int_page_id]['name'];
                include template('template/index/find_out');
        }


        function extra_news(){
                global $_SGLOBAL;
                $obj_news = L::loadClass('news','index');
                $arr_data = array('is_del'=>0);
                $arr_news = $obj_news->get_list($arr_data);
                // var_export($arr_news);
                include template('template/index/news');
        }

        function extra_news_show(){
                global $_SGLOBAL;
                $obj_news = L::loadClass('news','index');
                $int_nid = intval($_GET['nid']);
                if (!$int_nid) {
                        return false;
                }
                $arr_news_one = $obj_news->get_one($int_nid);
                // var_export($arr_news_one);
                include template('template/index/news_show');
        }


        function extra_invite_friends(){
                global $_SGLOBAL;
                $arr_member = $_SGLOBAL['member'];
                include template('template/index/invite_friends');
        }
}