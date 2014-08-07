<?
require_once("include/bittorrent.php");
dbconn(false);
loggedinorreturn();

if($CURUSER['class'] <= UC_SYSOP) {
	stderr("ОШИБКА!", "Взлом!!!");
}


if(isset($_GET['clear']) && $_GET['clear'] == "yes") {
	if(@unlink('include/cache/reklama/reklama.txt')) {
		echo "Файл удалён";
	} else {
		stderr("ОШИБКА!", "Ошибка при удалении файла");
	}
	sql_query("TRUNCATE TABLE reklama_admin") or sqlerr(__FILE__, __LINE__);
	header("Refresh: 5; url=reklama.php");
die();
}

stdhead("Реклама");


if(isset($_GET['edit']) && !empty($_GET['edit'])) {
        $res = sql_query("SELECT * FROM reklama_admin WHERE id=".intval($_GET['edit'])) or sqlerr(__FILE__, __LINE__);
        $reb = mysql_fetch_array($res);
    begin_frame("Редактировать рекламу"); 
echo '<form action="reklama.php?done=edit" METHOD="POST">
Редактировать: <input type="RADIO" name="id" value='.$reb['id'].' CHECKED><Br />
  ссылка: <input type="text" name="link" value='.$reb['link'].'><Br />
  Картинка: <input type="text" name="image" value='.$reb['image'].'><Br />
  Что именно:          <input type="radio" name="enum" value="http">Ссылка <input type="radio" name="enum" value="img" checked>Картинка<Br />
  <input type="submit">
 </form>';
 end_frame(); 
}


if(isset($_GET['done'])) {
	$done = $_GET['done'];
} else {
	$done = "";
}

if($done == "add") {
	$name = saves($_POST['name']);
	$link = saves($_POST['link']);
	$image = saves($_POST['image']);
	$enum = saves($_POST['enum']);
	sql_query("INSERT INTO reklama_admin (link, image, whe, name) VALUES ('".$link."', '".$image."', '".$enum."', '".$name."')") or sqlerr(__FILE__, __LINE__);
	if(@unlink('include/cache/reklama/reklama.txt')) {
		echo "DONE";
	} else {
		stderr("ОШИБКА!", "Ошибка при добавлении данных");
	}
	header("Refresh: 0; url=reklama.php");
} elseif($done == "edit") {
	if(@unlink('include/cache/reklama/reklama.txt')) {
		echo "Файл обновлён";
	} else {
		stderr("ОШИБКА!", "Ошибка при обновлении файла");
	}
	$name = saves($_POST['name']);
	$image = saves($_POST['image']);
	$link = saves($_POST['link']);
	$id = intval($_POST['id']);
	$enum = saves($_POST['enum']);
	sql_query("UPDATE reklama_admin SET image='".$image."', link='".$link."', whe='".$enum."', name = '".$name."' where id=".$id) or sqlerr(__FILE__, __LINE__);
	header("Refresh: 5; url=reklama.php");
} elseif($done == "del") {
	$id = intval($_POST['id']);
	if(@unlink('include/cache/reklama/reklama.txt')) {
		echo "Файл удален";
	} else {
		stderr("ОШИБКА!", "Ошибка при удалении файла");
	}
	sql_query("DELETE FROM `reklama_admin` WHERE id=".$id) or sqlerr(__FILE__, __LINE__);
	header("Refresh: 5; url=reklama.php");
} elseif((!isset($_GET['id']) || empty($_GET['id'])) && empty($done)) {
    begin_frame("Добавить рекламу"); 
echo <<<HTML
<form action="reklama.php?done=add" METHOD="POST">
  Название: <input type="text" name="name" /><Br />
  Ссылка: http://<input type="text" name="link" /><Br />
  Картинка:          <input type="text" name="image" /><Br />
  Что именно:          <input type="radio" name="enum" value="http" />Ссылка <input type="radio" name="enum" value="img" checked="checked" />Картинка<Br />
  <input type="submit">
 </form>
HTML;
 end_frame(); 

    begin_frame("Удалить рекламу"); 
echo <<<HTML
<form action="reklama.php?done=del" METHOD="POST">
  ИД: <input type="text" name="id"><Br />
  <input type="submit">
 </form>

<br /><br />
<a href="reklama.php?clear=yes"><< Очистить >></a>
HTML;
 end_frame(); 

    begin_frame("Редактировать рекламу"); 
echo <<<HTML
<form action="reklama.php" METHOD="GET">
  Редактировать(id): <input type="text" name="edit"><Br />
  <input type="submit">
 </form>
HTML;
 end_frame(); 

	begin_frame("Наличие рекламы"); 
	echo "<table width=\"100%\"><tr><td>ид</td><td>Название</td><td>ссылка</td><td>картинка</td><td>пример</td></tr>";
        $res = sql_query("SELECT * FROM reklama_admin") or sqlerr(__FILE__, __LINE__);
        while($reb = mysql_fetch_array($res)) {
		echo "<tr>".
			"<td>".$reb['id']."</td>".
			"<td>".$reb['name']."</td>".
			"<td>"."http://".$reb['link']."</td>".
			"<td>".$reb['image']."</td>".
			"<td><a href=http://".$reb['link']." target=\"_blank\">".(!empty($reb['image']) ? "<img src=\"".$reb['image']."\">" : $reb['name'])."</a></td>".
			"</tr>";
	}
	echo "</table>";
	end_frame(); 


} elseif($_GET['clear'] != "yes" && $done != "edit" && $done != "add" && $done != "del" && !empty($done)) {
///if($_GET['id'] == NULL) <- WTF?!?!?!
	stderr("ОШИБКА!", "Взлом!!!");
}

stdfoot();

?>