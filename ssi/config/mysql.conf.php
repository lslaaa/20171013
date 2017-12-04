<?php
!defined('LEM') && exit('Forbidden');
//Ucenter Home配置参数

$_dbhost = array
    (
    //写数据库帐号
    'master' => array
	(
	'dbhost' => '127.0.0.1', //服务器地址
	'dbuser' => 'root', //用户
	'dbpw' => '123456', //密码
	'dbcharset' => 'utf8', //字符集
	'dbport' => 3306, //端口
	'dbname' => 'demo_20171013', //数据库
	'tablepre' => '', //表名前缀
	'charset' => 'utf8', //页面字符集
	'gzipcompress' => 0, //启用gzip
    ),
	'slave'=>array(//读数据库帐号
		0=>array(
			'dbhost' => '127.0.0.1', //服务器地址
			'dbuser' => 'root', //用户
			'dbpw' => '123456', //密码
			'dbcharset' => 'utf8', //字符集
			'dbport' => 3306, //端口
			'dbname' => 'demo_20171013', //数据库
			'tablepre' => '', //表名前缀
			'charset' => 'utf8', //页面字符集
			'gzipcompress' => 0, //启用gzip
		),
		 1 => array(
			'dbhost' => '127.0.0.1', //服务器地址
			'dbuser' => 'root', //用户
			'dbpw' => '123456', //密码
			'dbcharset' => 'utf8', //字符集
			'dbport' => 3306, //端口
			'dbname' => 'demo_20171013', //数据库
			'tablepre' => '', //表名前缀
			'charset' => 'utf8', //页面字符集
			'gzipcompress' => 0, //启用gzip
		),
		2 => array(
			'dbhost' => '127.0.0.1', //服务器地址
			'dbuser' => 'root', //用户
			'dbpw' => '123456', //密码
			'dbcharset' => 'utf8', //字符集
			'dbport' => 3306, //端口
			'dbname' => 'demo_20171013', //数据库
			'tablepre' => '', //表名前缀
			'charset' => 'utf8', //页面字符集
			'gzipcompress' => 0, //启用gzip
		),
		3 => array(
			'dbhost' => '127.0.0.1', //服务器地址
			'dbuser' => 'root', //用户
			'dbpw' => '123456', //密码
			'dbcharset' => 'utf8', //字符集
			'dbport' => 3306, //端口
			'dbname' => 'demo_20171013', //数据库
			'tablepre' => '', //表名前缀
			'charset' => 'utf8', //页面字符集
			'gzipcompress' => 0, //启用gzip
		),
	)
);
?>