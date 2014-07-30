<?


require_once("include/bittorrent.php");

dbconn(false);

loggedinorreturn();
parked();

stdhead($tracker_lang['upload_torrent']);

if (get_user_class() < UC_USER AND $CURUSER["bonuploader"] < 1)
{
  stdmsg($tracker_lang['error'], $tracker_lang['access_denied']);
  stdfoot();
  exit;
}


if($CURUSER["ban_upload"] > 1) {
  stdmsg($tracker_lang['error'], $tracker_lang['access_denied']);
  stdfoot();
  exit;
}


if (strlen($CURUSER['passkey']) != 32) {
$CURUSER['passkey'] = md5($CURUSER['username'].get_date_time().$CURUSER['passhash']);
sql_query("UPDATE users SET passkey='$CURUSER[passkey]' WHERE id=$CURUSER[id]");
}

$reliz = new MySQLCache("SELECT * FROM relizgroup", 86400, "upload/reliz.txt");

?>
Прежде чем залить релиз <a href=browse.php><h1><b><font color=red>ПРОВЕРЬ НЕТ-ЛИ ДАННОГО РЕЛИЗА У НАС НА ТРЕКЕРЕ</font></b></h1></a> ( <- кликабельно)
<br />
<a href=forum.php?action=viewforum&forumid=8>Как создать свою раздачу</a>
<br>
<h3>Аннонсер: <font color=blue>http://torrnada.ru/announce.php</font></h3>
<div align=center>
<p><span style="color: green; font-weight: bold;">После загрузки торрента, вам нужно будет скачать торрент и поставить качаться в папку где лежат оригиналы файлов.</span></p>
<form name="upload" enctype="multipart/form-data" action="takeupload.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_torrent_size?>" />
<table border="1" cellspacing="0" cellpadding="5">
<tr><td class="colhead" colspan="2"><?=$tracker_lang['upload_torrent'];?></td></tr>
<?




print"<tr><td align=\"center\" width=\"99%\" colspan=\"2\" style=\"padding: 15px;\"><b>Внимание</b>: Если вы скачали торрент файл с другого трекера, и решили залить такой же файл сюда, вы можете напрямую указать <b>тотже</b> торрент файл, который вы <b>уже скачали</b> (не обязательно создавать новый, с оригинального файла будут удалены лишь ваши идентификаторы - пасскеи, чтобы вы не испортили рейтинг на другом трекере).</td></tr>"; 


tr("Мульти-трекер", "<label><input type=\"checkbox\" ".(!empty($name_link)? "checked":"")." name=\"multitr\" value=\"1\" checked><i>Разрешить / Запретить подключение внешних сидов и пиров.</i> </label><br>Работает только с обновлением torrent файла (<b>вкладка ниже</b>)", 1);

$added_data = <<<HTML
<font color="red"><strong>Допустимо использование сторонних фотохостигов. Рекомендуем <a href=http://imgmoney.ru/>imgmoney.ru</strong></font></a>
HTML;


tr($tracker_lang['torrent_file'], "<input type=file name=tfile size=80>\n", 1);
tr($tracker_lang['torrent_name'], "<input type=\"text\" name=\"name\" size=\"80\" /><br />(".$tracker_lang['taken_from_torrent'].")\n", 1);
tr($tracker_lang['img_poster'], $tracker_lang['max_file_size'].": 5MB<br />".$tracker_lang['avialable_formats'].": .gif .jpg .png<br /><input type=file name=image0 size=80><br />".
"<input type=file name=image5 size=80>\n", 1);


tr($tracker_lang['images'], $tracker_lang['max_file_size'].": 5MB<br />".$tracker_lang['avialable_formats'].": .gif .jpg .png<br />".$added_data."<br />".
		"<b>".$tracker_lang['image']." №1:</b>&nbsp&nbsp<input type=file name=image1 size=80><br />".
		"<b>".$tracker_lang['image']." №2:</b>&nbsp&nbsp<input type=file name=image2 size=80><br />".
		"<b>".$tracker_lang['image']." №3:</b>&nbsp&nbsp<input type=file name=image3 size=80><br />".
		"<b>".$tracker_lang['image']." №4:</b>&nbsp&nbsp<input type=file name=image4 size=80>", 1);

print("<tr><td class=rowhead style='padding: 3px'>".$tracker_lang['description']."</td><td>");
textbbcode("upload","descr");
print("</td></tr>\n");
/*
print("<tr><td class=rowhead style='padding: 2px'>Видео</td><td>");
textbbcode("upload","youtube");
print("<small style='padding: 2px'><b>данная форма предназначенна для видео-роликов</b></small>");
print("</td></tr>\n");
*/
tr("Кинопоиск:", "<input type=\"text\" name=\"kp\" size=\"80\"/>\n", 1);// value=\"Система временно приостановлена\" disabled


?>
<style type="text/css">
#bigcat {
color:blue;
font-weight:bold;
}
</style>
<?

$s = "<select name=\"type\">\n<option value=\"0\">(".$tracker_lang['choose'].")</option>\n";

$cats = genrelist();
foreach ($cats as $row)  
{  
    if ($row["parent"] == 0)  
    {  
        $s .= "<option id='bigcat' label=\"\">".$row["name"]."";  
        foreach ($cats as $grp)  
        {  
            if ($grp["parent"] == $row["id"]) 
            {  
                $s .= "<option value=\"" . $grp["id"] . "\"";  
                if ($grp["id"] == $_GET["cat"])  
                    $s .= " selected=\"selected\"";  
                $s .= ">" . htmlspecialchars_uni($grp["name"]) . "</option>\n";  
            }  
        }  
        $s .= "</option>";  
    }  
    elseif ($row["parent"] == -1)  
    {  
            $s .= "<option value=\"" . $row["id"] . "\"";  
            if ($row["id"] == $_GET["cat"])  
                $s .= " selected=\"selected\"";  
            $s .= ">" . htmlspecialchars_uni($row["name"]) . "</option>\n";  
    }  
}

tr($tracker_lang['type'], $s, 1);

if (get_user_class() >= UC_VIP)
	tr("Бонус раздачи", "<input name=\"free\" type=\"radio\" value=\"no\" ".(($row["free"] == "no") ? "checked=\"checked\"" : "" ) . " checked>&nbsp;Стандартная (трафик учитывается в полном объёме)<br><input name=\"free\" type=\"radio\" value=\"silver\" ".(($row["free"] == "silver") ? "checked=\"checked\"" : "" ) . ">&nbsp;Серебряная раздача (Учитыватся только половина скачанного трафика)<br><input name=\"free\" type=\"radio\" value=\"gold\" ".(($row["free"] == "gold") ? "checked=\"checked\"" : "" ) . ">&nbsp;Золотая раздача(Скачанный трафик не учитыватся)", 1);
elseif ($CURUSER["goldtorrent"]>0)
	tr("Золотая раздача", "<input name=\"free\" type=\"radio\" value=\"no\" ".(($row["free"] == "no") ? "checked=\"checked\"" : "" ) . " checked>&nbsp;Стандартная (трафик учитывается в полном объёме)<br><input name=\"free\" type=\"radio\" value=\"silver\" ".(($row["free"] == "silver") ? "checked=\"checked\"" : "" ) . ">&nbsp;Серебряная раздача (Учитыватся только половина скачанного трафика)<br><input name=\"free\" type=\"radio\" value=\"gold\" ".(($row["free"] == "gold") ? "checked=\"checked\"" : "" ) . ">&nbsp;Золотая раздача(Скачанный трафик не учитыватся) <br>У вас есть право сделать ". $CURUSER["goldtorrent"]." торрентов золотыми", 1);
else
	tr($tracker_lang['golden'], "Вы не можете сделать торрент золотым. Вы можете обменять эту возможность на бонусы", 1);

if (get_user_class() >= UC_ADMINISTRATOR)
    tr("Важный", "<input type=\"checkbox\" name=\"sticky\" value=\"yes\">Прикрепить этот торрент (всегда наверху)", 1);

?>


<tr><td align="right" valign="middle"><strong>Релиз-группа</strong></td><td><select name="reliz"><option value="0">Нет релиз-группы</option> 
<?php while($rel = $reliz->fetch_array()){?> 
<option value="<?php echo $rel["id"]; ?>"><?php echo $rel["link"]; ?></option> 
<?php }?> 

<?
/*
<option style="background-image:url(pic/reliz-grup/<?php echo $rel["img"]; ?>); font-size:16px;" value="<?php echo $rel["id"]; ?>"><?php echo $rel["link"]; ?></option>
*/
?>
</select> 
</td></tr>


<tr><td align="center" colspan="2"><input type="submit" class=btn value="<?=$tracker_lang['upload'];?>" /></td></tr>
</table>
</form>
<?

stdfoot();

?>