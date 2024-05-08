<?php
//    Считываем текущее время с миллисекундами
$start_time=microtime(1);
if (session_id()=='') session_start();
require('incl/db.php');
include('incl/gzdoc.php');
include("incl/config.php");
include("incl/boot.php");
if ($maintance>0){ //set incl/config.php
	echo '<center><img src="/img/maintance.jpg" height="70%">';
} else {
if (!isset($_GET['mod']) || ($_GET['mod']=="")) {
	$_GET['mod'] = $sys_def_mod;
}
if (!file_exists("module/".@$_GET['mod'].".php")) {
	include('module/404.php');
	} else {
@include("module/" . $_GET['mod'] . ".php");
 }
}
?>
