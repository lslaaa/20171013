<?php

/*
  [UCenter Home] (C) 2007-2008 Comsenz Inc.
  $Id: function_template.php 12678 2009-07-15 03:21:21Z xupeng $
 */

!defined('LEM') && exit('Forbidden');

$_SGLOBAL['i'] = 0;
$_SGLOBAL['block_search'] = $_SGLOBAL['block_replace'] = array();

function parse_template($tpl) {
	global $_SGLOBAL, $_SC, $_SCONFIG;

	//包含模板
	$_SGLOBAL['sub_tpls'] = array($tpl);

	$tplfile = S_ROOT . './' . $tpl . '.htm';
	//if (file_exists('/dev/shm/tpl_cache') && strstr(S_ROOT,'/data0/htdocs')) {
	//	$objfile = '/dev/shm/tpl_cache/' . str_replace('/', '_', $tpl) . '.php';
	//} else {
		$objfile = S_ROOT . './data/tpl_cache/' . str_replace('/', '_', $tpl) . '.php';
	//}
	
	//read
	if (!file_exists($tplfile)) {
		$tplfile = str_replace('/' . $_SCONFIG['template'] . '/', '/default/', $tplfile);
	}
	$template = read_over($tplfile);
	if (empty($template)) {
		exit("Template file : $tplfile Not found or have no access!");
	}

	//模板
	$template = preg_replace_callback("/\<\!\-\-\{template\s+([a-z0-9_\/]+)\}\-\-\>/i", function ($matches) {
																								return readtemplate($matches[1]);
																							}, $template);
	//处理子页面中的代码
	$template = preg_replace_callback("/\<\!\-\-\{template\s+([a-z0-9_\/]+)\}\-\-\>/i", function ($matches) {
																								return readtemplate($matches[1]);
																							}, $template);
	
	//解析模块调用
	$template = preg_replace_callback("/\<\!\-\-\{block\/(.+?)\}\-\-\>/i", function ($matches) {
																	return blocktags($matches[1]);
																}, $template);
	//解析广告
	$template = preg_replace_callback("/\<\!\-\-\{ad\/(.+?)\}\-\-\>/i", function ($matches) {
																	return adtags($matches[1]);
																}, $template);
	//时间处理
	$template = preg_replace_callback("/\<\!\-\-\{date\((.+?)\)\}\-\-\>/i", function ($matches) {
																	return datetags($matches[1]);
																}, $template);
	//头像处理
	//$template = preg_replace("/\<\!\-\-\{avatar\((.+?)\)\}\-\-\>/ie", "avatartags('\\1')", $template);
	//PHP代码
	$template = preg_replace_callback("/\<\!\-\-\{eval\s+(.+?)\s*\}\-\-\>/is", function ($matches) {
																	return evaltags($matches[1]);
																}, $template);

	//开始处理
	//变量
	$var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
	$template = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template);
	$template = preg_replace("/([\n\r]+)\t+/s", "\\1", $template);
	$template = preg_replace("/(\\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\.([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/s", "\\1['\\2']", $template);
	$template = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $template);
	$template = preg_replace("/\{([a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)::([a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1::\\2?>", $template);//输出const变量
	$template = preg_replace_callback("/$var_regexp/s", function ($matches) {
																	return addquote('<?='.$matches[1].'?>');
																}, $template);
	$template = preg_replace_callback("/\<\?\=\<\?\=$var_regexp\?\>\?\>/s", function ($matches) {
																	return addquote('<?='.$matches[1].'?>');
																}, $template);
	//逻辑
	$template = preg_replace_callback("/\{elseif\s+(.+?)\}/is", function ($matches) {
																	return stripvtags('<?php } elseif('.$matches[1].') { ?>','');
																}, $template);
	$template = preg_replace("/\{else\}/is", "<?php } else { ?>", $template);
	//循环
	for ($i = 0; $i < 6; $i++) {
		$template = preg_replace_callback("/\{loop\s+(\S+)\s+(\S+)\}(.+?)\{\/loop\}/is", function ($matches) {
																	return stripvtags('<?php if(is_array('.$matches[1].')) { foreach('.$matches[1].' as '.$matches[2].') { ?>',$matches[3].'<?php } } ?>');
																}, $template);
		$template = preg_replace_callback("/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}(.+?)\{\/loop\}/is", function ($matches) {
																	return stripvtags('<?php if(is_array('.$matches[1].')) { foreach('.$matches[1].' as '.$matches[2].' => '.$matches[3].') { ?>',$matches[4].'<?php } } ?>');
																}, $template);
		$template = preg_replace_callback("/\{if\s+(.+?)\}(.+?)\{\/if\}/is", function ($matches) {
																	return stripvtags('<?php if('.$matches[1].') { ?>',$matches[2].'<?php } ?>');
																}, $template);
	}
	
	//常量
	$template = preg_replace("/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/s", "<?=\\1?>", $template);

	//替换
	if (!empty($_SGLOBAL['block_search'])) {
		$template = str_replace($_SGLOBAL['block_search'], $_SGLOBAL['block_replace'], $template);
	}

	//换行
	$template = preg_replace("/ \?\>[\n\r]*\<\? /s", " ", $template);

	//附加处理
	$template = "<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('" . implode('|', $_SGLOBAL['sub_tpls']) . "', '$_SGLOBAL[timestamp]', '$tpl');?>$template<?php ob_out();?>";
	if (!write_over($template,$objfile,'w')) {
		exit("File: $objfile can not be write!");
	}
	
}

function addquote($var) {
	return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
}

function striptagquotes($expr) {
	$expr = preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr);
	$expr = str_replace("\\\"", "\"", preg_replace("/\[\'([a-zA-Z0-9_\-\.\x7f-\xff]+)\'\]/s", "[\\1]", $expr));
	return $expr;
}

function evaltags($php) {
	global $_SGLOBAL;

	$_SGLOBAL['i']++;
	$search = "<!--EVAL_TAG_{$_SGLOBAL['i']}-->";
	$_SGLOBAL['block_search'][$_SGLOBAL['i']] = $search;
	$_SGLOBAL['block_replace'][$_SGLOBAL['i']] = "<?php " . stripvtags($php) . " ?>";

	return $search;
}

function blocktags($parameter) {
	global $_SGLOBAL;

	$_SGLOBAL['i']++;
	$search = "<!--BLOCK_TAG_{$_SGLOBAL['i']}-->";
	$_SGLOBAL['block_search'][$_SGLOBAL['i']] = $search;
	$_SGLOBAL['block_replace'][$_SGLOBAL['i']] = "<?php block(\"$parameter\"); ?>";
	return $search;
}

function adtags($pagetype) {
	global $_SGLOBAL;

	$_SGLOBAL['i']++;
	$search = "<!--AD_TAG_{$_SGLOBAL['i']}-->";
	$_SGLOBAL['block_search'][$_SGLOBAL['i']] = $search;
	$_SGLOBAL['block_replace'][$_SGLOBAL['i']] = "<?php adshow('$pagetype'); ?>";
	return $search;
}

function datetags($parameter) {
	global $_SGLOBAL;

	$_SGLOBAL['i']++;
	$search = "<!--DATE_TAG_{$_SGLOBAL['i']}-->";
	$_SGLOBAL['block_search'][$_SGLOBAL['i']] = $search;
	$_SGLOBAL['block_replace'][$_SGLOBAL['i']] = "<?php echo sgmdate($parameter); ?>";
	return $search;
}

function avatartags($parameter) {
	global $_SGLOBAL;

	$_SGLOBAL['i']++;
	$search = "<!--AVATAR_TAG_{$_SGLOBAL['i']}-->";
	$_SGLOBAL['block_search'][$_SGLOBAL['i']] = $search;
	$_SGLOBAL['block_replace'][$_SGLOBAL['i']] = "<?php echo avatar($parameter); ?>";
	return $search;
}

function stripvtags($expr, $statement='') {
	$expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
	$statement = str_replace("\\\"", "\"", $statement);
	return $expr . $statement;
}

function readtemplate($name) {
	global $_SGLOBAL, $_SCONFIG;
	$tpl = str_exists($name, '/') ? $name : "template/$_SCONFIG[template]/$name";
	$tplfile = S_ROOT . './' . $tpl . '.htm';

	$_SGLOBAL['sub_tpls'][] = $tpl;

	if (!file_exists($tplfile)) {
		$tplfile = str_replace('/' . $_SCONFIG['template'] . '/', '/default/', $tplfile);
	}
	$content = read_over($tplfile);
	return $content;
}

?>