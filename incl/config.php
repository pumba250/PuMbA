<?php
if (!preg_match("/index.php/i", $_SERVER['PHP_SELF'])) { die ("Access denied"); }

#Модуль по умолчанию
$sys_def_mod="main";
#плохие знаки для url
$badword = array("?", "&", "!", "+", ",", ".", ":", "ʹ", "(", ")");
#Отладочная информация 0 - выкл
$debug = '0';
$maintance = '0';