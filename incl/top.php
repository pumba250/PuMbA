<!doctype html>
<html lang="ru">
<head>
<title>
<?php
echo $PAGETITLE.$_SERVER['SERVER_NAME']; echo '</title>';
echo '<link rel="canonical" href="https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'"/>';
$logo = '';

echo '
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="Generator" content="'.$version.'" />
<meta name="robots" content="index, follow">
<meta name="language" content="Russian">
<meta name="revisit-after" content="2 days">
';

echo "<meta name='keywords' content='{$keyword}'></head>";
echo '<body>
<div id="mainwrapper">
  <header> 
    <div id="logo">'.$logo.'</div>
    <nav><a href="/" title="Главная страница">Главная</a> </nav>
  </header>
  <div id="content">
    <div class="notOnDesktop">
    </div>';
