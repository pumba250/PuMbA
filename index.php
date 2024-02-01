<?php
//    Считываем текущее время с миллисекундами
$start_time=microtime(1);
if (session_id()=='') session_start();
require('incl/db.php');
include('incl/gzdoc.php');
include("incl/config.php");
if ($maintance>0){
	echo '<center><img src="/img/maintance.jpg" height="70%">';
} else {

//gzip();
$user = null;
if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
    $stmt = pdo()->prepare("SELECT * FROM `{$usertab}` WHERE `id` = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
if ($user) {
$userBirthday = $user['birthday']; // День рождение юзера
$birthday = strtotime($userBirthday); // Получаем unix timestamp нашего дня рождения
$years = date('Y') - date('Y',$birthday); // Вычисляем возраст БЕЗ учета текущего месяца и дня
$now = time(); // no comments
$nowBirthday = mktime(0,0,0,date('m',$birthday),date('d',$birthday),date('Y')); // Получаем день рождение пользователя в этом году
if ($nowBirthday > $now) {
    $years --; // Если дня рождения ещё не было то вычитаем один год
}
//echo $years;
$_SESSION['valid_adult']=$years;
}
if ($_GET['mod']=="") {
	$_GET['mod']=$sys_def_mod;
	@include("module/" . $_GET['mod'] . ".php");
	gzdocout(); 
	}
if (!file_exists("module/".@$_GET['mod'].".php")) {
	include('module/404.php');
	}
}

?>
