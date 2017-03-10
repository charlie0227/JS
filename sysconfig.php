<?php 
session_save_path("/**/");
session_start();  
$db_host = "/**/";
$db_name = "/**/";
$db_user = "/**/";
$db_password = "/**/";
$dsn = "mysql:host=$db_host;dbname=$db_name";
$db = new PDO($dsn, $db_user, $db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$root_dir ="/**/";
date_default_timezone_set("Asia/Taipei");
function write_log($status,$data)  //狀態 資料       
{
	$status='<'.$status.'>';
	$day = date("Y-m");
    $URL = $root_dir."log/".$day;
	$time= "[".date("Y-m-d H:i:s")."] ";
	//ip
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
	  $ip=$_SERVER['HTTP_CLIENT_IP'];
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	else
	  $ip=$_SERVER['REMOTE_ADDR'];
    $ip='['.$ip.']';
	//
	$ip=str_pad($ip,17," ",STR_PAD_RIGHT);
	$status=str_pad($status,16," ",STR_PAD_RIGHT);
    $log=$time.$ip.$status.$data."\r\n";
    $fileopen = fopen($URL, "a+");   
    fseek($fileopen, 0);
    fwrite($fileopen,$log);
    fclose($fileopen); 
}
function encrypt( $q ) {
    $cryptKey = '/**/';
    $qEncoded = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    return urlencode($qEncoded);
}

function decrypt( $q ) {
	$q = urldecode($q);
    $cryptKey = '/**/';
    $qDecoded = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
    return( $qDecoded );
}
?>
