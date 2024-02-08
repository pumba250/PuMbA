<?php
if (!preg_match("/index.php/i", $_SERVER['PHP_SELF'])) { die ("Access denied"); }

$html = NULL;
$user = null;
$datepost = date('Y-m-d');
if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
    $stmt = $pdo->prepare("SELECT * FROM `{$usertab}` WHERE `id` = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (empty($_GET['news']) ) { //news -> id short full cat date
$limit = 6;
 $offset = !empty($_GET['page'])?(($_GET['page']-1)*$limit):0;
 //получаем количество записей
 $queryNum = $db->query("SELECT COUNT(*) as postNum FROM `{$newstab}` WHERE `moderate`=1 AND `date`<='{$datepost}'");
 $resultNum = $queryNum->fetch_assoc();
 $rowCount = $resultNum['postNum'];
// Запрос для выборки целевых элементов:
$sql = "SELECT * FROM `{$newstab}` WHERE `moderate`=1 AND `date`<='{$datepost}' ORDER BY `date` DESC, `id` LIMIT $offset,$limit;";
// Выводим составленный SQL-запрос для отладки
//print_r($sql);
$stmt  = $pdo->query($sql);
//print_r($stmt);
$items = $stmt->fetchAll();
//print_r($items);
if (!empty($_GET['page'])){
	$keyword ='';
	$PAGETITLE = 'Страница '.@$_GET['page'].' << Главная << '; // Заголовок страницы с разделителем " << "
} else {
$keyword ='';
$PAGETITLE = 'Главная << '; // Заголовок страницы с разделителем " << "
}
include("incl/top.php");
	echo '<section id="mainContent">';
if( is_array($items) ) {
    foreach( $items AS $item ) {
        // ... ЗДЕСЬ КОД ФОРМИРУЮЩИЙ ВЫВОД ЭЛЕМЕНТОВ ...
    echo '<h1><b>'.$item['cat'].'</b> > <a href="/main/news/'.$item['altname'].'">'.$item['title'].'</a></h1><p>';
	echo $item['short'];
	echo '</p>
	<div style="clear:both"></div>
      <aside id="authorInfo">
	  <h2>Автор: <a style="text-decoration: none;" href="/profile/username/'.$item['author'].'">'.$item['author'].'</a></h2>';
	  if (!preg_match("/0{4}/" , $item['mdate'])){
		  echo '<div class="date">Изменено:'.$item['mdate'].'</div>';
	  } else {
		  echo '<div class="date">Добавлено:'.$item['date'].'</div>';
	  }
	  echo '<div style="clear:both"></div>
	  </aside>';

    }
}

} else {
	$idnew = $_GET['news'];
// Запрос для выборки целевых элементов:
$sql = "SELECT * FROM `{$newstab}` WHERE `altname`='{$idnew}';";
// Выводим составленный SQL-запрос для отладки
$stmt  = $pdo->query($sql);
$items = $stmt->fetchAll();

if( is_array($items) ) {
    foreach( $items AS $item ) {
$time=time();

$key=$item['title'];
$keyword ='Статьи, блог, полезное, '.implode(', ', explode(' ', $key));
		$PAGETITLE = ''.$key.' << ';// Заголовок страницы с разделителем " << "
include("incl/top.php");
	echo '<section id="mainContent">';
    echo '<h1>'.$key.'</h1>
	<p>'.$item['full'].'</p>
	<aside id="authorInfo">
    <h2>Автор: '.$item['author'].'</h2>';
    	  if (!preg_match("/0{4}/" , $item['mdate'])){
		  echo '<p>Изменено:'.$item['mdate'].'</p>';
	  } else {
		  echo '<p>Добавлено:'.$item['date'].'</p>';
	  }
	echo '</aside>';
include("incl/bottom.php");
