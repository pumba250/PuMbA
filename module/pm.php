<?
//$start_time=microtime(1);
if (!preg_match("/index.php/i", $_SERVER['PHP_SELF'])) { die ("Access denied"); }
$PAGETITLE = 'Личные сообщения << ';// Заголовок страницы
include("incl/top.php");
echo '<section id="mainContent">
	      <h1></h1>';
if ($user){
	if ($_GET['act']=='send'){
		if (!empty($_POST)){
		/**
		 * Принимаем постовые данные. Очистим сообщение от html тэгов
		 * и приведем id получателя к типу integer
		 */
		$message= htmlspecialchars($_POST['message']);
		$to=(int)$_POST['to'];
		$from = $_SESSION['user_id'];
		/**
		 * Я использую библиотеку PDO
		 */
		$sql="insert into `{$pmtab}` (`from`,`to`,`message`,`flag`) values (:u_from,:u_to,:message,:flag)";
		$sth=$pdo->prepare($sql);
		$sth->bindValue(':u_from', $from);// 1 - ID отправителя
		$sth->bindValue(':u_to', $to);
		$sth->bindValue(':message', $message);
		$sth->bindValue(':flag', 0);
		$sth->execute();
		$error=$sth->errorInfo();
		/**
		 * Проверка результата запроса
		 */
		 //echo $error[0];
		if($error[0]==0){
			//echo 'Сообщение успешно отправлено';
			flash('Сообщение успешно отправлено');
			header('Location: /pm/'); // Возврат на форму регистрации
			die; // Остановка выполнения скрипта
		}else{
			//echo 'Ошибка отправки сообщения';
			flash('Ошибка отправки сообщения');
			header('Location: '.$_SERVER['HTTP_REFERER'].''); // Возврат на форму регистрации
			die; // Остановка выполнения скрипта
		}
		} else {
			/* начало вывода написаных */
			$u_id=$_SESSION['user_id'];
			/**
			 * Достаем сообщения
			 */
			$sql="select * from `{$pmtab}` where `from` = :u_to order by `id` desc";
			$sth=$pdo->prepare($sql);
			$sth->bindValue(':u_to', $u_id);
			$sth->execute();
			$res=$sth->fetchAll(PDO::FETCH_ASSOC);
			flash();
			//print_r($res);
			echo '<div><a href="/pm/act/new">Написать</a></div><br>';
			foreach ($res as $row){
				$sql="select `username` from `{$usertab}` where `id` = :u_from";
				$pmuser = $pdo->prepare($sql);
				$pmuser->bindValue(':u_from', $row['to']);
				$pmuser->execute();
				$pm=$pmuser->fetchAll(PDO::FETCH_ASSOC);
				if ($row['flag']==0){
					echo '<div>'.$row['data'].' <br><b>Сообщение для '.$pm[0]['username'].'</b><br>'.$row['message'].'</div><br>';
				} else {
					echo '<div>'.$row['data'].' <br>Сообщение для '.$pm[0]['username'].'<br>'.$row['message'].'</div><br>';
				}
			}
			/* конец вывода написаных */
		}
	} elseif ($_GET['act']=='new') {
		flash();
		echo '<form action="/pm/act/send" method="post" enctype="multipart/form-data"><table>
			<tr><td><div>Адресат: </div></td><td><select name="to">
			';
		$sql="select `id`, `username` from `{$usertab}` order by `id` asc";
		$sth=$pdo->prepare($sql);
		$sth->execute();
		$list=$sth->fetchAll(PDO::FETCH_ASSOC);
		foreach ($list as $opt){
			echo '<option value="'.$opt['id'].'">  '.$opt['username'];
		}
		
		echo '</select></td></tr>
			<tr><td><div>Текст сообщения: </div></td><td><textarea style="width: 260px; height: 100px;" name="message"></textarea></td></tr>
			<tr><td></td><td><input type="submit"  value="Отправить" /></td></tr></table>
		</form>';
		
	} elseif (isset($_GET['read_id'])) {
		/**
		 * Номер пользователя
		 */
		$u_id=$_SESSION['user_id'];

		/**
		 * Получаем номер сообщения. Приводим его типу Integer
		 */
		$id_mess=(int)$_GET['read_id'];
		/**
		 * Достаем сообщение. Помимо номера сообщения ориентируемся и на id пользователя
		 * Это исключит возможность чтения чужого сообщения, методом подбора id сообщения
		 */
		$sql="select * from `{$pmtab}` where `to` = :u_to and `id` = :id_mess";
		$sth=$pdo->prepare($sql);
		$sth->bindParam(':u_to',$u_id,PDO::PARAM_INT);
		$sth->bindParam(':id_mess',$id_mess,PDO::PARAM_INT);
		$sth->execute();
		$res=$sth->fetch(PDO::FETCH_ASSOC);

		/**
		 * Установим флаг о прочтении сообщения если прочитал тот кому написано
		 */
		if ($_SESSION['user_id']==$res['to']){
		$sql="update `{$pmtab}` set `flag` = 1 where  `to` = :u_to and `id` = :id_mess";
		$sth=$pdo->prepare($sql);
		$sth->bindParam(':u_to',$u_id,PDO::PARAM_INT);
		$sth->bindParam(':id_mess',$id_mess,PDO::PARAM_INT);
		$sth->execute();
		}
		/**
		 * Выводим сообщение с датой отправки
		 */
		if($res['id']<>''){
			echo '<div><a href='.$_SERVER['HTTP_REFERER'].'> Назад</a></div>';
			echo '<div>'.$res['message'].'</div><div>Дата отправки: '.$res['data'].'</div>';
			$sql="select `username` from `{$usertab}` where `id` = :u_from";
			$pmuser = $pdo->prepare($sql);
			$pmuser->bindValue(':u_from', $res['from']);
			$pmuser->execute();
			$pm=$pmuser->fetchAll(PDO::FETCH_ASSOC);
			echo '<a href="/pm/answer_id/'.$res['id'].'">Ответить '.$pm[0]['username'].'</a>';
		}else{
			echo 'Данного сообщения не существует или оно предназначено не вам.';
		}
	} elseif (isset($_GET['answer_id'])) {
		/**
		 * Номер пользователя
		 */
		$u_id=$_SESSION['user_id'];
		//$u_id=2;

		/**
		 * Получаем номер сообщения. Приводим его типу Integer
		 */
		$id_mess=(int)$_GET['answer_id'];
		/**
		 * Достаем сообщение. Помимо номера сообщения ориентируемся и на id пользователя
		 * Это исключит возможность чтения чужого сообщения, методом подбора id сообщения
		 */
		$sql="select * from `{$pmtab}` where `id` = :id_mess";
		$sth=$pdo->prepare($sql);
		//$sth->bindParam(':u_to',$u_id,PDO::PARAM_INT);
		$sth->bindParam(':id_mess',$id_mess,PDO::PARAM_INT);
		$sth->execute();
		$res=$sth->fetch(PDO::FETCH_ASSOC);

		echo' <form action="/pm/act/send" method="post" enctype="multipart/form-data"><table>
		<tr><td><div>Текст сообщения: </div></td><td><textarea style="width: 260px; height: 100px;" name="message"></textarea></td></tr>
			<tr><td></td><td><input type="hidden" name="to" value="'.$res['from'].'"><input type="submit"  value="Отправить" /></td></tr></table>
		</form>';
	} else {
		/**
		 * Номер пользователя,для которого отображать сообщения
		 */
		$u_id=$_SESSION['user_id'];
		/**
		 * Достаем сообщения
		 */
		$sql="select * from `{$pmtab}` where `to` = :u_to order by `id` desc";
		$sth=$pdo->prepare($sql);
		$sth->bindValue(':u_to', $u_id);
		$sth->execute();
		$res=$sth->fetchAll(PDO::FETCH_ASSOC);
		flash();
		echo '<div><a href="/pm/act/new">Написать</a> | <a href="/pm/act/send">Отправленые</a></div><br>';
		foreach ($res as $row){
			$sql="select `username` from `{$usertab}` where `id` = :u_from";
			$pmuser = $pdo->prepare($sql);
			$pmuser->bindValue(':u_from', $row['from']);
			$pmuser->execute();
			$pm=$pmuser->fetchAll(PDO::FETCH_ASSOC);
			if ($row['flag']==0){
				echo '<div><b>Сообщение от '.$pm[0]['username'].'  <a href="/pm/read_id/'.$row['id'].'">Открыть</a></b></div>';
			} else {
			echo '<div>Сообщение от '.$pm[0]['username'].'  <a href="/pm/read_id/'.$row['id'].'">Открыть</a></div>';
			}
		}
	}
}
	echo '<div style="clear:both"></div>
<aside id="authorInfo"> 
  </aside>
</section>';

include("incl/bottom.php");
