<?php
if (!preg_match("/index.php/i", $_SERVER['PHP_SELF'])) { die ("Access denied"); }

$html = NULL;
	$usercomm = null;
$datepost = date('Y-m-d');
if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
    $stmt = $pdo->prepare("SELECT * FROM `{$usertab}` WHERE `id` = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $usercomm = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (empty($_GET['news']) ) { //news -> id short full cat date
$limit = 6;
 $offset = !empty($_GET['page'])?(($_GET['page']-1)*$limit):0;
 //получаем количество записей
 $queryNum = $db->query("SELECT COUNT(*) as postNum FROM `{$newstab}` WHERE `cat` IN ('Главная','Статьи','Новости','Софт') AND `moderate`=1 AND `date`<='{$datepost}'");
 $resultNum = $queryNum->fetch_assoc();
 $rowCount = $resultNum['postNum'];
// Запрос для выборки целевых элементов:
$sql = "SELECT * FROM `{$newstab}` WHERE `cat` IN ('Главная','Статьи','Новости','Софт') AND `moderate`=1 AND `date`<='{$datepost}' ORDER BY `date` DESC, `id` LIMIT $offset,$limit;";
// Выводим составленный SQL-запрос для отладки
//print_r($sql);
$stmt  = $pdo->query($sql);
//print_r($stmt);
$items = $stmt->fetchAll();
//print_r($items);
if (!empty($_GET['page'])){
	$keyword ='Статьи, блог, полезное';
	$PAGETITLE = 'Страница '.@$_GET['page'].' << Главная << '; // Заголовок страницы с разделителем " << "
} else {
$keyword ='Статьи, блог, полезное';
$PAGETITLE = 'Главная << '; // Заголовок страницы с разделителем " << "
}
include("incl/top.php");
	echo '<section id="mainContent">';
if( is_array($items) ) {
    foreach( $items AS $item ) {
        // ... ЗДЕСЬ КОД ФОРМИРУЮЩИЙ ВЫВОД ЭЛЕМЕНТОВ ...
$theme_id=$item['id'];
$res=mysqli_query($db,"SELECT * FROM `{$commtab}` WHERE theme_id='".$theme_id."' and moderation=1 ORDER BY id");
$number=mysqli_num_rows($res);
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
	  echo '<div class="comm">Комментариев: '.$number.'</div><div style="clear:both"></div>
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
$mess_url='/main/news/'.$item['altname'].'';
//получаем id текущей темы
$res=mysqli_query($db,"SELECT `id` FROM `{$newstab}` WHERE id='".$item['id']."'");
$res=mysqli_fetch_array($res);
$theme_id=$res["id"];

if (isset($_POST["contr_cod"])){    //отправлен комментарий
 $mess_login=htmlspecialchars($_POST["mess_login"]);
 $user_text=htmlspecialchars($_POST["user_text"]);
 if (md5($_POST["contr_cod"])==$_POST["prov_summa"]){    //код правильный
  if ($mess_login!='' and $user_text!=''){
   if (is_numeric($_POST["parent_id"]) and is_numeric($_POST["f_parent"]))
    $res=mysqli_query($db,"insert into {$commtab}
    (parent_id, first_parent, date, theme_id, login, message)
    values ('".$_POST["parent_id"]."','".$_POST["f_parent"]."',
    '".$time."','".$theme_id."','".$mess_login."','".$user_text."')");
   else $res=mysqli_query($db,"insert into {$commtab} (date, theme_id, login, message)
   values ('".$time."','".$theme_id."','".$mess_login."','".$user_text."')");
    $_SESSION["send"]='<font color="#008">Комментарий принят и ждет модерации!</font>';
    header("Location: $mess_url#last"); exit;
  }
  else {
   $_SESSION["send"]='<font color="#C00">Не все поля заполнены!</font>';
   header("Location: $mess_url#last"); exit;
  }
 }
 else {
  $_SESSION["send"]='<font color="#C00">Неверный проверочный код!</font>';
  header("Location: $mess_url#last"); exit;
 }
}

if (isset($_SESSION["send"]) and $_SESSION["send"]!="") {    //вывод сообщения
    $commalert .= ''.$_SESSION["send"].'';
    $_SESSION["send"]="";
}
$key=$item['title'];
$keyword ='Статьи, блог, полезное, '.implode(', ', explode(' ', $key));
		$PAGETITLE = ''.$item['title'].' << ';// Заголовок страницы с разделителем " << "
include("incl/top.php");
	echo '<section id="mainContent">';
        // ... ЗДЕСЬ КОД ФОРМИРУЮЩИЙ ВЫВОД ЭЛЕМЕНТОВ ...

    echo '<h1>'.$item['title'].'</h1>
	<p>'.$item['full'].'</p>
	<aside id="authorInfo">
    <h2>Автор: '.$item['author'].'</h2>';
    	  if (!preg_match("/0{4}/" , $item['mdate'])){
		  echo '<p>Изменено:'.$item['mdate'].'</p>';
	  } else {
		  echo '<p>Добавлено:'.$item['date'].'</p>';
	  }
	echo '</aside>';
		function parents($up=0, $left=0) {    //Строим иерархическое дерево комментариев
global $tag,$mess_url;

for ($i=0; $i<=count($tag[$up])-1; $i++) {
 //Можно выделять цветом указанные логины
 if ($tag[$up][$i][2]=='pumba250') $tag[$up][$i][2]='<font color="#C00">Admin</font>';
 if ($tag[$up][$i][6]==0) $tag[$up][$i][6]=$tag[$up][$i][0];
 //Высчитываем рейтинг комментария
 $sum=$tag[$up][$i][4]-$tag[$up][$i][5];
 if ($up==0) echo '<div style="padding:5px 0 0 0;">';
 else {
    if (count($tag[$up])-1!=$i)
        echo '<div class="strelka" style="padding:5px 0 0 '.($left-2).'px;">';
    else echo '<div class="strelka_2" style="padding:5px 0 0 '.$left.'px;">';
 }
 echo '<div class="comm_head" id="m'.$tag[$up][$i][0].'">';
 echo '<div style="float:left;"><b>'.$tag[$up][$i][2].'</b></div>';
 echo '<div class="comm_minus"></div>';
 echo '<div style="float:right; width:30px;" id="rating_comm'.$tag[$up][$i][0].'">';
 echo '<b>'.$sum.'</b></div><div class="comm_plus"></div>';
 echo '<a style="float:right; width:70px;" href="'.$mess_url.'#m';
 echo $tag[$up][$i][0].'"># '.$tag[$up][$i][0].'</a>';
 echo '<div style="float:right; width:170px;">';
 echo '('.date("H:i:s d.m.Y", $tag[$up][$i][3]).'г.)</div>';
 echo '<div style="clear:both;"></div></div>';
 echo '<div class="comm_body">';
 if ($sum<0) echo '<u class="sp_link">Показать/скрыть</u><div class="comm_text">';
 else echo '<div style="word-wrap:break-word;">';
 echo str_replace("<br />","<br>",nl2br($tag[$up][$i][1])).'</div>';
 echo '<div class="open_hint" onClick="comm_on('.$tag[$up][$i][0].',
     '.$tag[$up][$i][6].')">Ответить</div><div style="clear:both;"></div></div>';

 if (isset($tag[ $tag[$up][$i][0] ])) parents($tag[$up][$i][0],20);
 echo '</div>';
}
	}
 $res=mysqli_query($db,"SELECT * FROM {$commtab} WHERE theme_id='".$theme_id."' and moderation=1 ORDER BY id");
$number=mysqli_num_rows($res);
if ($number>0) {
 echo '<div style="padding:5px;text-align:center;">';
 echo 'Все комментарии проходят обязательную модерацию!<br> ';
 echo '<b>Последние комментарии:</b><br>';
 while ($com=mysqli_fetch_assoc($res))
    $tag[(int)$com["parent_id"]][] = array((int)$com["id"], $com["message"],
    $com["login"], $com["date"], $com["plus"], $com["minus"], $com["first_parent"]);
 echo parents().'</div><br>';
}
$cod=rand(100,900); $cod2=rand(1,99);
echo '<div id="last" align="center">';
echo '<form method="POST" action="'.$mess_url.'#last" class="add_comment"';
echo 'name="add_comment" id="hint"><div class="close_hint">Закрыть</div>';
echo '<textarea cols="68" rows="5" name="user_text"></textarea>';
echo '<div style="margin:5px; float:left;">';
echo 'Имя: <input type="text" name="mess_login" maxlength="20" value="'.htmlspecialchars(@$usercomm['username']).'"></div>';
echo '<div style="margin:5px; float:right;">'.$cod.' + '.$cod2.' = ';
echo '<input type="hidden" name="prov_summa" value="'.md5($cod+$cod2).'">';
echo '<input type="hidden" name="parent_id" value="0">';
echo '<input type="hidden" name="f_parent" value="0">';
echo '<input type="text" name="contr_cod" maxlength="4" size="4">&nbsp;';
echo '<input type="submit" value="Отправить"></div>';
echo '</form>';
echo '<div style="text-align: center;">'.$commalert.'</div>';
echo '<form method="POST" action="'.$mess_url.'#last" class="add_comment">';
echo 'Добавить комментарий:';
echo '<textarea cols="68" rows="5" name="user_text"></textarea>';
echo '<div style="margin:5px; float:left;">';
echo 'Имя: <input type="text" name="mess_login" maxlength="20" value="'.htmlspecialchars(@$usercomm['username']).'"></div>';
echo '<div style="margin:5px; float:right;">'.$cod.' + '.$cod2.' = ';
echo '<input type="hidden" name="prov_summa" value="'.md5($cod+$cod2).'">';
echo '<input type="text" name="contr_cod" maxlength="4" size="4">&nbsp;';
echo '<input type="submit" value="Отправить"></div>';
echo '</form></div>';
}
}
}

?>
<script type="text/javascript">
function comm_on(p_id,first_p){
    document.add_comment.parent_id.value=p_id;
    document.add_comment.f_parent.value=first_p;
}

$(document).ready(function(){
$(".sp_link").click(function(){
    $(this).parent().children(".comm_text").toggle("normal");
});

$(".open_hint").click(function(){
    $("#hint").animate({
        top: $(this).offset().top + 25, left: $(document).width()/2 -
        $("#hint").width()/2
    }, 400).fadeIn(800);
});

$(".close_hint").click(function(){ $("#hint").fadeOut(1200); });

$(".comm_plus,.comm_minus").click(function(){
    id_comm=$(this).parents(".comm_head").attr("id").substr(1);
});

$(".comm_plus").click(function(){
    jQuery.post("/rating_comm.php",{comm_id:id_comm,ocenka:1},rating_comm);
});
$(".comm_minus").click(function(){
    jQuery.post("/rating_comm.php",{comm_id:id_comm,ocenka:0},rating_comm);
});

function rating_comm(data){
    $("#rating_comm"+id_comm).fadeOut(800,function(){
        $(this).html(data).fadeIn(800);
    });
}
});
</script>
<?php
include("incl/bottom.php");
