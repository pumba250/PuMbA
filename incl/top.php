<!doctype html>
<html lang="ru">
<head>
<title>
<?php
echo $PAGETITLE.$_SERVER['SERVER_NAME']; echo '</title>';
echo '<link rel="canonical" href="https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'"/>';
$logo = '<img src="/img/logo.png" alt="logo" width="55" height="55" />';
?>

<script type="text/javascript">
function clock() {
var d = new Date();
var day = d.getDate();
var hrs = d.getHours();
var min = d.getMinutes();
var sec = d.getSeconds();

var mnt = new Array("января", "февраля", "марта", "апреля", "мая",
"июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");

if (day <= 9) day="0" + day;
if (hrs <= 9) hrs="0" + hrs;
if (min <=9 ) min="0" + min;
if (sec <= 9) sec="0" + sec;

$("#time").html("" + hrs + ":" + min + ":" + sec + "&nbsp;" +
day + "-го " + mnt[d.getMonth()] + " " + d.getFullYear() + "<br>");
}
setInterval("clock()",1000);
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="Generator" content="PuMbA 0.6" />
<meta name="robots" content="index, follow">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<meta name="language" content="Russian">
<meta name="revisit-after" content="2 days">
<meta name="author" content="pumba">
<script type="text/javascript">
$(document).ready(function() {
$().UItoTop({ easingType: 'easeOutQuart' });
});
</script>
<?php
echo"<meta name='keywords' content='{$keyword}'></head>";
echo '<body>
<div id="mainwrapper">
  <header> 
    <div id="logo">'.$logo.'</div>
    <nav><a href="/" title="Главная страница">Главная</a> </nav>
  </header>
  <div id="content">
    <div class="notOnDesktop">
    </div>';
