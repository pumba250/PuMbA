    </section>
    <section id="sidebar">
	<div>
<?php

		$u_id=$_SESSION['user_id'];
		$id_mess=0;
		$sql="select count(*) as pmnum from `{$pmtab}` where `to` = :u_to and `flag` = :id_mess";
		$sth=$pdo->prepare($sql);
		$sth->bindParam(':u_to',$u_id,PDO::PARAM_INT);
		$sth->bindParam(':id_mess',$id_mess,PDO::PARAM_INT);
		$sth->execute();
		$res=$sth->fetch(PDO::FETCH_ASSOC);
		if ($res['pmnum']>0){
			$pmlink = '<b>'.$res['pmnum'].'</b>';
		} else {
			$pmlink = ''.$res['pmnum'].'';
		}
if ($user & @$user['isadmin'] == 9) { //admin section
	echo'<form class="mt-5" method="post" action="/do_logout.php"><nav><ul>';
	echo '<li>Привет, <b>'.htmlspecialchars($user['username']).'</b>!<button type="submit" class="btn btn-primary">Выйти</button></li>';
	echo '</form><li><a href="/adm/admin.php" rel="nofollow">Админка</a></li>';
	echo '<li><a href="/addnews" title="Добавить новость">Добавить новость</a></li>';
	echo '<li><a href="/profile" title="Профиль">Профиль</a></li>';
	echo '<li><a href="/pm/" title="Личные сообщения">Личные сообщения['.$pmlink.']</a></li>';
	echo '<li><a href="/photo/" title="Загрузи свое">Загрузи свое</a></li>';
	echo '</ul></nav></div>';

} elseif ($user & @$user['isadmin'] == 8) { //moderator section
	echo'<form class="mt-5" method="post" action="/do_logout.php"><nav><ul>';
	echo '<li>Привет, <b>'.htmlspecialchars($user['username']).'</b>!<button type="submit" class="btn btn-primary">Выйти</button></li>';
	echo '</form><li><a href="/adm/admin.php" rel="nofollow">Админка</a></li>';
	echo '<li><a href="/addnews" title="Добавить новость">Добавить новость</a></li>';
	echo '<li><a href="/profile" title="Профиль">Профиль</a></li>';
	echo '<li><a href="/pm/" title="Личные сообщения">Личные сообщения['.$pmlink.']</a></li>';
	echo '<li><a href="/photo/" title="Загрузи свое">Загрузи свое</a></li>';
	echo '</ul></nav></div>';

} elseif ($user & @$user['isadmin'] < 8) { //user section
	echo'<form class="mt-5" method="post" action="/do_logout.php"><nav><ul>';
	echo '<li>Привет, <b>'.htmlspecialchars($user['username']).'</b>!<button type="submit" class="btn btn-primary">Выйти</button></li>';
	echo '</form><li><a href="/addnews" title="Добавить новость">Добавить новость</a></li>';
	echo '<li><a href="/profile" title="Профиль">Профиль</a>!</li>';
	echo '<li><a href="/pm/" title="Личные сообщения">Личные сообщения['.$pmlink.']</a></li>';
	echo '<li><a href="/photo/" title="Загрузи свое">Загрузи свое</a></li>';
	echo '</ul></nav></div>';

} else {
	flash(); 
echo '<form method="post" action="/do_login.php">
    <nav><ul>
        <li><label for="username" class="form-label">Имя</label>
        <input type="text" class="form-control" id="username" name="username" required></li>
    <li>
        <label for="password" class="form-label">Пароль</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </li>
    <li>
        <button type="submit" class="btn btn-primary">Войти</button> </li><li><a href="/registration">Регистрация</a>
    </li>
	</ul></nav>
</form>';
	
	echo '</div>';
} 
echo '<div class="d1">
	<form name="search" method="post" action="/search">
		<input type="search" name="query" placeholder="Поиск...">
		<button type="submit">>></button>	 
	</form>
	</div>';

$ddm = date('m');
$ddd = date('d');
    // Получим данные пользователя по сохранённому идентификатору
    $sql = "SELECT `username`,`birthday` FROM `{$usertab}` WHERE month(birthday) = {$ddm} AND day(birthday)={$ddd}";
    $stmt = $pdo->query($sql);
	//print_r($stmt);
	//$stmt->store_result();
if ($stmt->rowCount() == 1){
		//echo $stmt->num_rows();
		$busers = $stmt->fetchAll();
		foreach($busers as $bday);
		$bdata = ' Поздравляем именинника!!!<br> Сегодня это:<br>';
		$bdate .= $bdata.'<b>'.$bday['username'].'</b><br>';
//print_r($busers);
	} elseif ($stmt->rowCount() > 1){
		$busers = $stmt->fetchAll();
		$bdata = ' Поздравляем именинников!!!<br> Сегодня это:<br>';
		foreach($busers as $bday){
		$bdate .= $bdata.'<b>'.$bday['username'].'</b><br>';
	}
	} else {
		$bdate = null;
	}
echo $bdate;


    echo '<div id="adimage">';
	  //echo '<div>Случайное фото</div>';

if (!$user){
	$clss = '<div class="bw">';
	$clse = '</div>';
} else {
	$clss = null;
	$clse = null;
}

echo '</div>';
echo '<nav>
    <ul>
		<li><a href="/" title="Главная">Главная</a></li>

    </ul>
</nav>';
    echo '</section>';
	echo '<div style="clear:both"></div>';
    echo '<footer> 
      <article>
		<h3>Реклама</h3>
        <p>Место для вашей рекламы</p>
		<p></p>
      </article>
      <article>
		<h3>Реклама</h3>
        <p>Место для вашей рекламы</p>
		<p></p>
      </article>
    <article>
		<h3>Реклама</h3>
        <p>Место для вашей рекламы</p>
	</article>
    </footer>';
  echo '</div>';?>
  <div id="footerbar"><p>Copyright &copy; 2022 - <?php echo date("Y");?> by <b>pumba250</b></p></div>
<script src="/js/lightbox.js"></script>
<?php
//    Считываем текущее время с миллисекундами
$current_time=microtime(1);
//    Вычисляем разницу во времени
$result_time=round($current_time - $start_time, 5);
//    Выводим результат
echo '<div class="comm2">Время генерации - '.$result_time.' сек.<br></div>';
?>
</div>
</body>
</html>
