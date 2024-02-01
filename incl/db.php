<?php
// указываем параметры для подключения к MySQL
$host='localhost'; // имя хоста
$database='db_name'; // имя базы данных
$db_user='root'; // имя пользователя
$db_pass=''; // пароль пользователя
/* 
** name for MySQL tables defaults
*/
$usertab = 'users'; // default table users
$newstab = 'news'; // default table news
$commtab = 'comment'; // default table comments
$starcom = 'ocenka_comment'; // default table rating comments
$pmtab = 'messages'; // default PM table
$galtab = 'image'; // default Gallery table
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
