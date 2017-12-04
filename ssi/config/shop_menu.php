<?php
!defined('LEM') && exit('Forbidden');

$_SGLOBAL['shop_menu'] = array(
		'1'=>array('mid'=>'1','name'=>'任务发布','level'=>'1','pid'=>'0','url'=>'/shop.php?mod=item&extra=add','sort'=>1), 
		'2'=>array('mid'=>'2','name'=>'任务管理','level'=>'1','pid'=>'0','url'=>'/shop.php?mod=item','sort'=>2), 
		'3'=>array('mid'=>'3','name'=>'订单管理','level'=>'1','pid'=>'0','url'=>'/shop.php?mod=order','sort'=>3), 
		'4'=>array('mid'=>'4','name'=>'账号设置','level'=>'1','pid'=>'0','url'=>'/shop.php?mod=shop','sort'=>4), 
		'5'=>array('mid'=>'5','name'=>'任务发布','level'=>'2','pid'=>'1','url'=>'/shop.php?mod=item&extra=add','sort'=>100), 
		'6'=>array('mid'=>'6','name'=>'任务列表','level'=>'2','pid'=>'2','url'=>'/shop.php?mod=item','sort'=>100), 
		'7'=>array('mid'=>'7','name'=>'订单列表','level'=>'2','pid'=>'3','url'=>'/shop.php?mod=order','sort'=>100), 
		'8'=>array('mid'=>'8','name'=>'店铺列表','level'=>'2','pid'=>'4','url'=>'/shop.php?mod=shop','sort'=>100), 
		'9'=>array('mid'=>'9','name'=>'账号列表','level'=>'2','pid'=>'4','url'=>'/shop.php?mod=member','sort'=>100), 
		'10'=>array('mid'=>'10','name'=>'管理员组','level'=>'2','pid'=>'4','url'=>'/shop.php?mod=user_group&extra=index','sort'=>100), 
		'11'=>array('mid'=>'11','name'=>'财务管理','level'=>'1','pid'=>'0','url'=>'/shop.php?mod=money','sort'=>100), 
		'12'=>array('mid'=>'12','name'=>'充值列表','level'=>'2','pid'=>'11','url'=>'/shop.php?mod=money','sort'=>100), 
		'13'=>array('mid'=>'13','name'=>'提现列表','level'=>'2','pid'=>'11','url'=>'/shop.php?mod=money&extra=withdrawal','sort'=>100), 
		'14'=>array('mid'=>'14','name'=>'银行卡管理','level'=>'2','pid'=>'11','url'=>'/shop.php?mod=money&extra=bank_card','sort'=>100), 
		'15'=>array('mid'=>'15','name'=>'模板管理','level'=>'2','pid'=>'1','url'=>'/shop.php?mod=item_temp&extra=item_temp','sort'=>100), 
	);
?>