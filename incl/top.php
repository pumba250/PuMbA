<!doctype html>
<html lang="ru">
<head>
<title>
<?php
echo $PAGETITLE.$_SERVER['SERVER_NAME']; echo '</title>';
echo '<link rel="canonical" href="https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'"/>';
$var1 = strtotime('2024-12-15 12:59');
$var2 = strtotime(date('Y-m-d H:i'));
$var3 = strtotime('2025-01-08 13:00');
$happynew = null;
if ($var2 > $var1 and $var2 < $var3){
	$logo = '<img src="/img/logonew.png" alt="logo" width="55" height="55" />';
	$happynew = '<nolayer><div style="position:absolute; top:0; left:0;">  </nolayer>  
	<img border="0" src="/img/07.png" width="150" align="left" />  
	<nolayer>  </div>  <div class="garland_image"></div></nolayer>';
} else {
	$logo = '<img src="/img/logo.png" alt="logo" width="55" height="55" />';
}

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
<meta name="title" content="yunisov.tech">
<meta name="Generator" content="PuMbA 0.6" />
<meta name="robots" content="index, follow">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<meta name="language" content="Russian">
<meta name="revisit-after" content="2 days">
<meta name="author" content="pumba">
<link href="/css/bstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" media="screen,projection" href="/css/ui.totop.css" />
<script src="/js/jquery-3.4.1.min.js"></script>
<link rel="stylesheet" href="/css/jquery.thumbs.css">
<script src="/js/jquery.thumbs.js"></script>
<script src="/js/easing.js" type="text/javascript"></script>
<script src="/js/jquery.ui.totop.js" type="text/javascript"></script>
<link href="/css/lightbox.css" rel="stylesheet" />
<link href="/css/style.css" rel="stylesheet" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/css/adaptive.css" rel="stylesheet" />
<script type="text/javascript">
$(document).ready(function() {
 
$().UItoTop({ easingType: 'easeOutQuart' });
 
});
</script>

<?php
echo"<meta name='keywords' content='{$keyword}'></head>";

echo $happynew;
echo '<body><!--<div class="indicator"><img src="/img/sload.gif" alt="">
</div>-->
<div id="mainwrapper">
  <header> 
    <div id="logo">'.$logo.'</div>
    <nav><a href="/" title="Главная страница">Главная</a> </nav>
  </header>
  <div id="content">
    <div class="notOnDesktop">
    </div>';
