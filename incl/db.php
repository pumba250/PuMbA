<?php
// указываем параметры для подключения к MySQL
$host='localhost'; // имя хоста
$database='db_name'; // имя базы данных
$db_user='root'; // имя пользователя
$db_pass=''; // пароль пользователя
/* 
** name for MySQL tables defaults
*/
$usertab = 'users'; //table users
$newstab = 'news'; // table news
$commtab = 'comment'; // table comments
$starcom = 'ocenka_comment'; // table rating comments
$pmtab = 'messages'; // PM table
$galtab = 'image'; // Gallery table
/*
**
*/
$db = mysqli_connect($host, $db_user, $db_pass, $database) or die('error! Нет соединения с сервером mysql!');
mysqli_query($db, "SET NAMES utf8");
// подключаемся к MySQL
$dsn = 'mysql:dbname=db_name;host=localhost';
$pdo      = new PDO($dsn, $db_user, $db_pass);
$pdo->exec("set names utf8");
$connection = mysqli_connect($host, $db_user, $db_pass, $database) or die('error! Нет соединения с сервером mysql!');