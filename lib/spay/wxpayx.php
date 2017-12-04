<?php
ini_set("display_errors", "Off");
header("content-Type: text/html; charset=utf-8");

$key = '123456789';//通信秘钥，跟easyPay.exe上填写的接口秘钥保持一致

$sig = $_POST['sig'];//签名
$time = $_POST['time'];//付款时间
$amount = $_POST['amount'];//交易额

//验证签名
if(strtoupper(md5("$time|$amount|$key")) == $sig){
$conn = mysql_connect($dbhost,"root","a123457");
if (!$conn)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("spay",$conn);
mysql_query("SET NAMES UTF8");


$amt=$amount;
$time=time()-200;
		
$result = mysql_query("select count(*) from pay where amt=$amt and time>$time");
$rechargeorder = mysql_fetch_array($result);
if($r6_Order)
{
$s="update pay set sate=1 where  id={$rechargeorder['id']}";
if (mysql_query($s)){echo "";}
else{echo "Error creating database: " . mysql_error();}  

}






mysql_close($conn);		

echo "ok";
	
}
else
	echo "签名错误";

?>