<?php
require_once "../sysconfig.php";
write_log('Logout','id: '.$_SESSION['id']);
unset($_SESSION['id']);
?>