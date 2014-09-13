<?
require_once("include/bittorrent.php");

if (!mkglobal("id"))
	die();

$id = intval($id);
if($id<1)
	die();

dbconn();

loggedinorreturn();

$res = sql_query("SELECT * FROM torrents WHERE id = ".$id);
$row = mysql_fetch_array($res);
if (!$row)
	die();

if($row['block_edit']!="false" && get_user_class() < UC_MODERATOR) {
	stderr($tracker_lang['error'], "Администрация, в лице ".$row['block_edit_added']." заблокировала возможность редактирования данного торрента!");
}

$row['name'] = view_saves($row['name']);

stdhead("Редактирование торрента \"" . $row["name"] . "\"");

if(!isset($CURUSER) || ($CURUSER["id"] != $row["owner"] && get_user_class() < UC_MODERATOR)) {
	stdmsg($tracker_lang['error'],"Вы не можете редактировать этот торрент.");
} else {
	print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
	print("<tr><td class=\"colhead\" colspan=\"2\">Редактировать торрент</td></tr>");


	if(get_user_class() >= UC_MODERATOR){
?>
	<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js?ver=1.3.2'></script>
	<script type="text/javascript">
	function FormClick ()  
	{
	jQuery.post("details_cache.php?clear_cache", { id: "<?php echo $id; ?>" }, function(data){
	    jQuery("#result_cache").html(data);
	  });  
	}
	</script>
<?

	tr('Метод "Кешу - Кыш"',"<form id=\"parser_mp3\" method=post action=details_cache.php?please><input type=hidden name=id value=$id><input onclick=\"FormClick(); return false\" type=\"button\" value=\"тИЦ\" /></form><br/><small>Если данные обновлены или информация о раздающих в живую устарела-нажмите на кнопку выше</small><div id=\"result_cache\"></div>",1);
	}

	print("<form name=\"edit\" method=post action=takeedit.php enctype=multipart/form-data>\n");
	print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
	if (isset($_GET["returnto"]))
		print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars_uni($_GET["returnto"]) . "\" />\n");

	tr("Ожидаемый релиз", "<label><input type=\"checkbox\" ".(!empty($name_link)? "checked":"")." name=\"future\" value=\"yes\"" .($row["future"] == "yes" ? " checked=\"checked\"" : "")."><i>Включить / Выключить.</i> </label><br>Если включена эта опция - торрент-файл игнорируется!", 1);
	tr("Мульти-трекер <font color=red>*</font>", "<label><input type=radio name=multitr value=yes" .($row["multitracker"] == "yes" ? " checked=\"checked\"" : "").">Да </label><label><input type=radio name=multitr value=no" .($row["multitracker"] == "no" ? " checked" : "").">Нет </label><i>Разрешить / Запретить подключение внешних сидов и пиров (для обеспечения max скорости скачивания)</i> <br>Работает только с обновлением torrent файла (<b>вкладка ниже</b>)", 1);


	tr($tracker_lang['torrent_file'], "<input type=file name=tfile size=80>\n", 1);
	tr($tracker_lang['torrent_name'], "<input type=\"text\" name=\"name\" value=\"" . $row["name"] . "\" size=\"80\" />", 1);
	tr($tracker_lang['img_poster'], "<input type=radio name=img1action value='keep' checked>Оставить постер&nbsp&nbsp"."<input type=radio name=img1action value='delete'>Удалить постер&nbsp&nbsp"."<input type=radio name=img1action value='update'>Обновить постер<br /><b>Постер:</b>&nbsp&nbsp<input type=file name=image0 size=80><br />".
					"<input type=radio name=img6action value='keep' checked>Оставить постер&nbsp&nbsp"."<input type=radio name=img6action value='delete'>Удалить постер&nbsp&nbsp"."<input type=radio name=img6action value='update'>Обновить постер<br /><b>Постер:</b>&nbsp&nbsp<input type=file name=image5 size=80><br />", 1);
	tr($tracker_lang['images'],
		"<input type=radio name=img2action value='keep' checked>Оставить скриншот №1&nbsp&nbsp"."<input type=radio name=img2action value='delete'>Удалить скриншот №1&nbsp&nbsp"."<input type=radio name=img2action value='update'>Обновить скриншот №1<br /><b>Картинка №2:</b>&nbsp&nbsp<input type=file name=image1 size=80><br /><br />".
		"<input type=radio name=img3action value='keep' checked>Оставить скриншот №2&nbsp&nbsp"."<input type=radio name=img3action value='delete'>Удалить скриншот №2&nbsp&nbsp"."<input type=radio name=img3action value='update'>Обновить скриншот №2<br /><b>Картинка №3:</b>&nbsp&nbsp<input type=file name=image2 size=80><br /><br />".
		"<input type=radio name=img4action value='keep' checked>Оставить скриншот №3&nbsp&nbsp"."<input type=radio name=img4action value='delete'>Удалить скриншот №3&nbsp&nbsp"."<input type=radio name=img4action value='update'>Обновить скриншот №3<br /><b>Картинка №4:</b>&nbsp&nbsp<input type=file name=image3 size=80><br /><br />".
		"<input type=radio name=img5action value='keep' checked>Оставить скриншот №4&nbsp&nbsp"."<input type=radio name=img5action value='delete'>Удалить скриншот №4&nbsp&nbsp"."<input type=radio name=img5action value='update'>Обновить скриншот №4<br /><b>Картинка №5:</b>&nbsp&nbsp<input type=file name=image4 size=80>", 1);
if ((strpos($row["ori_descr"], "<") === false) || (strpos($row["ori_descr"], "&lt;") !== false))
  $c = "";
else
  $c = " checked";
	//tr("Описание", "<textarea name=\"descr\" rows=\"10\" cols=\"80\">" . htmlspecialchars($row["ori_descr"]) . "</textarea><br />(HTML <b>не</b> разрешен. Нажмите <a href=tags.php>сюда</a> для получения информации о тегах.)", 1);
	print("<tr><td class=rowhead style='padding: 3px'>".$tracker_lang['description']."</td><td>");
	textbbcode("edit","descr",htmlspecialchars_uni($row["ori_descr"]));
	print("</td></tr>\n");
/*
	print("<tr><td class=rowhead style='padding: 3px'>Видео</td><td>");
	textbbcode("edit","youtube",htmlspecialchars($row["youtube"]));
	print("</td></tr>\n");
*/
tr("Кинопоиск:", "<input type=\"text\" name=\"kp\" value=\"" . htmlspecialchars_uni($row["kp"]) ."\" size=\"55\" />\n", 1);

?>
<style type="text/css">
.bigcat {
color:BLUE;
font-weight:bold;
}
</style>
<?

	$s = "<select name=\"type\">\n";
	$cats = genrelist();
	foreach ($cats as $subrow) {  
                if ($subrow["parent"] == 0) {  
                    $s .= "<option class='bigcat' label=\"\">".$subrow["name"]."";  
                    foreach ($cats as $grp) {  
                        if ($grp["parent"] == $subrow["id"]){  
                            $s .= "<option value=\"" . $grp["id"]. "\"";  
                            if ($grp["id"] == $row["category"])  
                                $s .= " selected=\"selected\"";  
                            $s .= ">" . htmlspecialchars_uni($grp["name"]) . "</option>\n";  
                        }  
                    }  
                    $s .= "</option>";  
                } elseif ($subrow["parent"] == -1){  
                    $s .= "<option value=\"" . $subrow["id"] . "\"";  
                    if ($subrow["id"] == $row["category"])  
                        $s .= " selected=\"selected\"";  
                    $s .= ">" . htmlspecialchars_uni($subrow["name"]) . "</option>\n";  
                }
	}
	$s .= "</select>\n";
	tr("Тип", $s, 1);

	if(get_user_class() >= UC_MODERATOR)
		tr("Запрет редактирования", "<input type=\"checkbox\" name=\"block_edit\"" . (($row["block_edit"] == "true") ? " checked=\"checked\"" : "" ) . " value=\"1\" />", 1);

	tr("Видимый", "<input type=\"checkbox\" name=\"visible\"" . (($row["visible"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"1\" />
					Видимый в торрентах<br /><table border=0 cellspacing=0 cellpadding=0 width=420><tr><td class=embedded>Обратите внимание, что торрент автоматически станет видимым когда появится раздающий и автоматически перестанет быть видимым (станет мертвяком) когда не будет раздающего некоторое время.
					Используйте этот переключатель для ускорения процеса. Также учтите что невидимые торренты (мертвяки) все-равно могут быть просмотрены и найдены, это просто не по-умолчанию.</td></tr></table>", 1);
	if(get_user_class() >= UC_ADMINISTRATOR)
		tr("Забанен", "<input type=\"checkbox\" name=\"banned\"" . (($row["banned"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"1\" />", 1);
    if(get_user_class() >= UC_ADMINISTRATOR)
        //tr("Золотая раздача", "<input type=\"checkbox\" name=\"free\"" . (($row["free"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"1\" /> Золотая раздача (считаеться только раздача, скачка не учитиваеться)", 1);
tr("Золотая раздача", "<input name=\"free\" type=\"radio\" value=\"no\" ".(($row["free"] == "no") ? "checked=\"checked\"" : "" ) . " ".(($row["free"] == "") ? "checked=\"checked\"" : "" ) . ">&nbsp;Стандартная (трафик учитывается в полном объёме)<br><input name=\"free\" type=\"radio\" value=\"silver\" ".(($row["free"] == "silver") ? "checked=\"checked\"" : "" ) . ">&nbsp;Серебряная раздача (Учитыватся только половина скачанного трафика)<br /><input name=\"free\" type=\"radio\" value=\"gold\" ".(($row["free"] == "gold") ? "checked=\"checked\"" : "" ) . ">&nbsp;Золотая раздача(Скачанный трафик не учитыватся)", 1);

    if(get_user_class() >= UC_SYSOP) {
        tr("Новинка", "<input type=\"checkbox\" name=\"new\"" . (($row["new"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"yes\" /> Прикрепляет пометку о том что торрент только-только вышел на экраны", 1);
}

    if(get_user_class() >= UC_ADMINISTRATOR)
        tr("Важный", "<input type=\"checkbox\" name=\"sticky\"" . (($row["sticky"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"yes\" /> Прикрепить этот торрент (всегда наверху)", 1);


?> 
<tr><td align="right" valign="middle"><strong>Релиз-группа</strong></td><td><select name="reliz"> 
<?php
if($row["relizgroup"] != 0) {
$reliz = mysql_query("SELECT * FROM relizgroup WHERE id='".$row['relizgroup']."'"); 
$rel = mysql_fetch_array($reliz);
?> 
<option value="<?php echo $rel["id"]; ?>"><?php echo $rel["link"]; ?></option> 
<option value="0">Нет релиз-группы</option> 
<?php $reliz = mysql_query("SELECT * FROM relizgroup"); 
 while($rel = mysql_fetch_array($reliz)) { ?> 
<option style="background-image:url(pic/reliz-grup/<?php echo $rel["img"]; ?>); font-size:16px;" value="<?php echo $rel["id"]; ?>"><?php echo $rel["link"]; ?></option> <?php
 } 
} else {
?> 
<option value="0">Нет релиз-группы</option>
<?php 
$reliz = mysql_query("SELECT * FROM relizgroup"); 
while($rel = mysql_fetch_array($reliz)){
?> 
<option style="background-image:url(pic/reliz-grup/<?php echo $rel["img"]; ?>); font-size:16px;" value="<?php echo $rel["id"]; ?>"><?php echo $rel["link"]; ?></option>
<?php
}
}
?> 
</select></td></tr> 
<?php

	print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"Отредактировать\" style=\"height: 25px; width: 100px\"> <!--input type=reset value=\"Обратить изменения\" style=\"height: 25px; width: 100px\"--></td></tr>\n");


	print("</table>\n");
	print("</form>\n");
	print("<p>\n");
	print("<form method=\"post\" action=\"delete.php\">\n");
  print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
  print("<tr><td class=embedded style='background-color: #F5F4EA;padding-bottom: 5px' colspan=\"2\"><b>Удалить торрент</b> Причина:</td></tr>");
  print("<td><input name=\"reasontype\" type=\"radio\" value=\"1\">&nbsp;Мертвяк </td><td> 0 раздающих, 0 качающих = 0 соединений</td></tr>\n");
  print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"2\">&nbsp;Дупликат</td><td><input type=\"text\" size=\"40\" name=\"reason[]\"></td></tr>\n");
  print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"3\">&nbsp;Nuked</td><td><input type=\"text\" size=\"40\" name=\"reason[]\"></td></tr>\n");
  print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"4\">&nbsp;Правила</td><td><input type=\"text\" size=\"40\" name=\"reason[]\">(Обязательно)</td></tr>");
  print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"5\" checked>&nbsp;Другое:</td><td><input type=\"text\" size=\"40\" name=\"reason[]\">(Обязательно)</td></tr>\n");
	print("<input type=\"hidden\" name=\"id\" value=\"".$id."\">\n");
	if (isset($_GET["returnto"]))
		print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars_uni($_GET["returnto"]) . "\" />\n");
  print("<td colspan=\"2\" align=\"center\"><input type=submit value='Удалить' style='height: 25px'></td></tr>\n");
  print("</table>");
	print("</form>\n");
	print("</p>\n");
}

stdfoot();

?>