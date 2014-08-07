<?
require_once("include/bittorrent.php");

dbconn(false);

loggedinorreturn();



loggedinorreturn();

if ($CURUSER['class'] <= UC_SYSOP){

stderr("ОШИБКА!", "Взлом!!!");

}else{


$id = $_GET['id'];

stdhead("Реклама");




if($_GET['edit'] != NULL){

        $res = sql_query("SELECT * FROM reklama_admin WHERE id=".$_GET['edit']) or sqlerr(__FILE__, __LINE__);
        $reb = mysql_fetch_array($res);

    begin_frame("Редактировать рекламу"); 
echo <<<HTML
<form action="reklama.php?done=edit" METHOD="POST">
Редактировать: <input type="RADIO" name="id" value={$reb[id]}  CHECKED><Br>
  ссылка: <input type="text" name="link" value={$reb[link]}><Br>
  Картинка: <input type="text" name="image" value={$reb[image]}><Br>
  Что именно:          <input type=radio name=enum value=http>Ссылка <input type=radio name=enum value=img checked>Картинка<Br>
  <input type="submit">
 </form>
HTML;
 end_frame(); 
}






$done = $_GET['done'];

if($_GET['clear'] == "yes"){

if (unlink('include/cache/reklama/reklama.txt'))
{ echo "Файл удалён"; }
else
{ stderr("ОШИБКА!", "Ошибка при удалении файла"); }

sql_query("TRUNCATE TABLE reklama_admin") or sqlerr(__FILE__, __LINE__);

header("Refresh: 5; url=reklama.php");

}





if($done == "add"){

$link = $_POST['link'];
$image = $_POST['image'];
$enum = $_POST['enum'];

sql_query("INSERT INTO reklama_admin (link,image,whe) VALUES ('{$link}','{$image}','{$enum}')") or sqlerr(__FILE__, __LINE__);

if (unlink('include/cache/reklama/reklama.txt'))
{ echo "DONE"; }
else
{ stderr("ОШИБКА!", "Ошибка при добавлении данных"); }

header("Refresh: 0; url=reklama.php");

}elseif($done == "edit"){

if (unlink('include/cache/reklama/reklama.txt'))
{ echo "Файл обновлён"; }
else
{ stderr("ОШИБКА!", "Ошибка при обновлении файла"); }

$image = $_POST['image'];
$link = $_POST['link'];
$id = $_POST['id'];
$enum = $_POST['enum'];

sql_query("UPDATE reklama_admin SET image='{$image}', link='{$link}, whe='{$enum}' where id=".$id) or sqlerr(__FILE__, __LINE__);

header("Refresh: 5; url=reklama.php");

}elseif($done == "del"){

$id = $_POST['id'];

if (unlink('include/cache/reklama/reklama.txt'))
{ echo "Файл удален"; }
else
{ stderr("ОШИБКА!", "Ошибка при удалении файла"); }

sql_query("DELETE FROM `reklama_admin` WHERE id=".$id) or sqlerr(__FILE__, __LINE__);

header("Refresh: 5; url=reklama.php");

}elseif($_GET['id'] == NULL && $done == NULL){

    begin_frame("Добавить рекламу"); 
echo <<<HTML
<form action="reklama.php?done=add" METHOD="POST">
  Ссылка: http://<input type="text" name="link"><Br>
  Картинка:          <input type="text" name="image"><Br>
  Что именно:          <input type=radio name=enum value=http>Ссылка <input type=radio name=enum value=img checked>Картинка<Br>
  <input type="submit">
 </form>
HTML;
 end_frame(); 


    begin_frame("Удалить рекламу"); 
echo <<<HTML
<form action="reklama.php?done=del" METHOD="POST">
  ИД: <input type="text" name="id"><Br>
  <input type="submit">
 </form>

<br><br>
<a href=reklama.php?clear=yes><< Очистить >></a>
HTML;
 end_frame(); 




    begin_frame("Редактировать рекламу"); 
echo <<<HTML
<form action="reklama.php" METHOD="GET">
  Редактировать(id): <input type="text" name="edit"><Br>
  <input type="submit">
 </form>
HTML;
 end_frame(); 






    begin_frame("Наличие рекламы"); 
echo "ид                      ссылка                      картинка<br>";
        $res = sql_query("SELECT * FROM reklama_admin") or sqlerr(__FILE__, __LINE__);
        while($reb = mysql_fetch_array($res)){

echo $reb[id]."  "."http://".$reb[link]."  ".$reb[image]."           <a href=http://".$reb[link]." target=_blank><img src=".$reb[image]."></a><br>";

}
 end_frame(); 


}elseif($_GET['clear'] != "yes" || $done != "edit" || $done != "add" || $done != "del" || $done != NULL){

if($_GET['id'] == NULL)
stderr("ОШИБКА!", "Взлом!!!");

}

}

stdfoot();

?>