<?php
ini_set("display_errors", "Off");
header("content-Type: text/html; charset=utf-8");
$mkey="zxc123456789";//本地密钥
$ok="ok";//成功标识
$key = Trim($_POST['key']);  //软件密钥
$order_no = Trim($_POST['order_no']);//订单号
$zftime = Trim($_POST['zftime']);//交易时间
$order_amount = Trim($_POST['addmoney']);//交易金额
$U_id =Trim( $_POST['U_id']);//附言
$M_name =Trim( $_POST['M_name']);//付款人
$shuoming=$M_name."  支付宝充值";
$mkey=md5($mkey.$order_no.$order_amount);

if($order_no=="")
{
exit;
}

if($zftime=="")
{
exit;
}

if($order_amount=="")
{
exit;
}

if($U_id=="")
{
exit;
}

if($M_name=="")
{
exit;
}


if($key==$mkey)//验证成功
{



include '../config.php'; 
$conn = mysql_connect($dbhost,$conf['db']['user'],$conf['db']['password']);
if (!$conn)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db($dbname,$conn);
mysql_query("SET NAMES UTF8");


$r3_Amt=$order_amount;
$time=time()-240;
		
$actionTime=time();
$result = mysql_query("select count(*),rechargeId from ssc_member_recharge where amount=$r3_Amt and actionTime>$time");
$rechargeorder = mysql_fetch_array($result);
$r6_Order=$rechargeorder['rechargeId'];
if(!$r6_Order){echo "<tr align=center bgcolor=#FFFFFF><td colspan=16>暂无充值数据</td></tr>";}
else{
$result2 = mysql_query("select * from ssc_member_recharge where rechargeId='{$r6_Order}'");
$row = mysql_fetch_array($result2);
if($row['state']=='0')
{            
//-----------------帐变明细-----------------------------

$actionIP=$_SERVER["REMOTE_ADDR"];
 $chaxun5 = mysql_query("select coin from ssc_members where uid= '".$row['uid']."'");
$coin = mysql_result($chaxun5,0);
 $inserts = "insert into ssc_coin_log (uid,type,playedId,coin,userCoin,fcoin,liqType,actionUID,actionTime,actionIP,info,extfield0,extfield1) values ('".$row['uid']."',0,0,'".$r3_Amt."','".$coin."'+'".$r3_Amt."',0,1,0,UNIX_TIMESTAMP(),'".$actionIP."','微信扫码','".$row['id']."','".$r6_Order."')";
 
if (mysql_query($inserts)){echo "";}
else{echo "Error creating database: " . mysql_error();}    
 
 
 //-----------------帐变明细-----------------------------
 
 
 
$s="update ssc_members set coin=coin+{$r3_Amt} where  uid={$row['uid']}";
if (mysql_query($s)){echo "";}
else{echo "Error creating database: " . mysql_error();}  
 
 

$ss="update ssc_member_recharge set state='1',rechargeAmount={$r3_Amt},actionTime={$actionTime},info='支付宝扫码' where  rechargeId='".$r6_Order."'";
if (mysql_query($ss)){}
else{echo "Error creating database: " . mysql_error();}    
	
}
else
{	echo "";}
}





mysql_close($conn);		
echo "ok";
}
	
?>