<?php
$host='localhost'; //this is the database hostname, Do not change this.
$user='charlie27'; //please set your mysql user name
$pass='12345678'; // please set your mysql user password
$dbname='jangsc27_cs_js'; //please set your Database name
$charset='utf8'; // specify the character set
$collation='utf8_general_ci'; //specify what collation you wish to use

$db = mysql_connect("$host","$user","$pass") or die("mysql could not CONNECT to the database, in correct user or password " . mysql_error());
mysql_select_db("$dbname") or die("Mysql could not SELECT to the database, Please check your database name " . mysql_error());
$result=mysql_query('show tables') or die("Mysql could not execute the command 'show tables' " . mysql_error());
while($tables = mysql_fetch_array($result)) {
foreach ($tables as $key => $value) {
mysql_query("ALTER TABLE $value CONVERT TO CHARACTER SET $charset COLLATE $collation") or die("Could not convert the table " . mysql_error());
}}
mysql_query("ALTER DATABASE $dbname DEFAULT CHARACTER SET $charset COLLATE $collation") or die("could not alter the collation of the databse " . mysql_error());
echo "The collation of your database has been successfully changed!";
?>