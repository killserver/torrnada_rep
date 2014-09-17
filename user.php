<?php

require_once("include/bittorrent.php");
dbconn();
header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && $_SERVER["REQUEST_METHOD"] == 'POST')
{
    $id = (int)$_POST["user"];
    $act = (string)$_POST["act"];

    if (!is_valid_id($id) || empty($act))
    	die("Ошибка");

    print("<link rel=\"stylesheet\" href=\"css/user.css\" type=\"text/css\">\n");

    function maketable($res)
    {
        global $tracker_lang;
        $ret = "<table class=\"tt\">\n
            <tr><td class=\"tt\" style=\"padding:0px;margin:0px;width:45px;\" align=\"center\"><img src=\"pic/genre.gif\" title=\"Категория\" alt=\"\" /></td><td class=\"tt\"><img src=\"pic/release.gif\" title=\"Название\" alt=\"\" /></td><td class=\"tt\" align=\"center\"><img src=\"pic/mb.gif\" title=\"Размер\" alt=\"\" /></td><td class=\"tt\" width=\"30\" align=\"center\"><img src=\"pic/seeders.gif\" title=\"Раздают\" alt=\"\" /></td><td class=\"tt\" width=\"30\" align=\"center\"><img src=\"pic/leechers.gif\" title=\"Качают\" alt=\"\" /></td><td class=\"tt\" align=\"center\"><img src=\"pic/uploaded.gif\" title=\"Раздал\" alt=\"\" /></td>\n
            <td class=\"tt\" align=\"center\"><img src=\"pic/downloaded.gif\" title=\"Скачал\" alt=\"\" /></td><td class=\"tt\" align=\"center\"><img src=\"pic/ratio.gif\" title=\"Рейтинг\" alt=\"\" /></td></tr>\n";
        while ($arr = mysql_fetch_assoc($res))
        {
            if ($arr["downloaded"] > 0)
            {
                $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
                $ratio = "<font color=" . get_ratio_color($ratio) . ">".$ratio."</font>";
            }
            else
            {
                if ($arr["uploaded"] > 0)
                    $ratio = "Inf.";
                else
                    $ratio = "---";
            }
            $catid = $arr["catid"];
        	$catimage = htmlspecialchars($arr["image"]);
        	$catname = htmlspecialchars($arr["catname"]);
        	$size = str_replace(" ", "&nbsp;", mksize($arr["size"]));
        	$uploaded = str_replace(" ", "&nbsp;", mksize($arr["uploaded"]));
        	$downloaded = str_replace(" ", "&nbsp;", mksize($arr["downloaded"]));
        	$seeders = number_format($arr["seeders"]);
        	$leechers = number_format($arr["leechers"]);
            $ret .= "
                <tr>\n
                <td rowspan=\"2\" style=\"padding:0;margin:0;\"><a href=\"browse.php?cat=".$catid."\"><img src=\"pic/cats/".$catimage."\" title=\"".$catname."\" alt=\"\" border=\"0\"/></a></td>\n
                <td colspan=\"7\"><a href=details.php?id=".$arr['torrent']."&amp;hit=1><b>" . view_saves($arr["torrentname"]) ."</b></a></td>\n
                </tr>\n
                <tr>\n
                <td align=\"left\"><font color=\"#808080\" size=\"1\">" . $arr["added"] . "</font></td>\n
                <td align=\"center\">".$size."</td>\n
                <td align=\"center\">".$seeders."</td>\n
                <td align=\"center\">".$leechers."</td>\n
                <td align=\"center\">".$uploaded."</td>\n
        	<td align=\"center\">".$downloaded."</td>\n
                <td align=\"center\">".$ratio."</td>\n
                </tr>\n";
        }
        $ret .= "</table>\n";
        return $ret;
    }

    $res = @sql_query("SELECT * FROM users WHERE id = ".$id) or sqlerr(__FILE__, __LINE__);
    $user = mysql_fetch_array($res) or die("Неверный идентификатор");

    print("<style>\n");
    print("table.main td {border:1px solid #cecece;margin:0;}\n");
    print("table.main a {color:#266C8A;font-family:tahoma;}\n");
    print("</style>\n");

    if ($act == "info")
    {
        if (empty($user['info']))
            print("<div class=\"tab_error\">Пользователь не сообщил эту информацию.</div>\n");
        else
            print(format_comment($user['info']));
        die();
    }
    elseif ($act == "presents")
    {
echo "<table width=\"100%\">";
///////////////////////////////////////////////////////////////
  //[jQuery]Подарки by Женадий
  ///////////////////////////////////////////////////////////////
  ?>
  <link rel="stylesheet" href="presents/system/css/style.css" type="text/css">
  <link rel="stylesheet" href="presents/system/css/box.css" type="text/css">
  <script src="presents/system/js/main.js"> </script>
  <div class="popup_box_container message_box" id="box_present" style="width: 600px; height: auto;top: 250px; margin-left: -310px;display:none">
	<div class="box_layout" >
	<div class="box_title_wrap">
	<div class="box_title">Просмотр подарка</div>

	</div>
	<div class="box_body" style="padding: 0pt;">
	<div class="simplePage" style="line-height: 170%; padding-bottom: 20px;">
	<a name="Подарить подарок"/>
	<div style="" align=left>
	<div id="res_present"></div>


	</div>
	</div>

	</div>
	</div>
	<div class="box_controls_wrap">
	<div class="box_controls">


	<div class="button_no">
	<div id="check_exit">Закрыть</div>
	</div>

	</div>
	</div>
	</div>
	</div>
	</div>
	<script>

	//Отмена
	$("#check_exit").click(function() {
		$("#box_present").fadeOut("slow"); //Выводим в эффектом
	});
 
	</script>
  <?
  $present = sql_query("SELECT p.* ,p.id AS p_id, u.id AS user_id, u.username, u.avatar, u .class,
						pl.pic AS p_pic
						FROM presents AS p
						LEFT JOIN users AS u ON u.id=p.id_user_to
						LEFT JOIN presents_list  AS pl ON pl.id=p.id_present
						WHERE p.id_user=".sqlesc($id)." 
						ORDER BY p.added DESC LIMIT 4") or die(mysql_error());
		

  

  echo '<tr><td colspan="2"> ';
  echo '<span class=argmore1><font class=small> <a href="presents.add.php?id='.$id.'" >Подарить</a></font></span>';
  echo '<span class=argmore><font class=small> <a href="presents.my.php" onClick="all_present('.$id.')">Все</a></font></span>';
  if(!mysql_num_rows($present))
	stdmsg("Подарков нет");
  else {
		$kolonok = 4;
		echo '<table cellspacing="0" border="0" width="100%" id="all_present">';
		while($row = mysql_fetch_object($present)) {
			print(($c && $c % $kolonok == 0) ? "</td><tr>" : "");
			echo '<td id="present_'.$row->id.'" width="1%" style="border:none" onClick="info_present('.$row->p_id.'); "><center><img src="presents/'.$row->p_pic.'" border="0" width="200"><br>'.htmlspecialchars($row->text).'</center></td>';
			$c++;
		}
		echo '</table>';
  }	
  echo '</td></tr>';
echo "</table>";
    }
    elseif ($act == "friends")
    {
        $res = sql_query("SELECT f.friendid as id, u.username AS name, u.class, u.avatar, u.gender, u.title, u.donor, u.warned, u.enabled, u.last_access FROM friends AS f LEFT JOIN users as u ON f.friendid = u.id WHERE f.userid=$id AND f.status = 'yes' ORDER BY name") or sqlerr(__FILE__, __LINE__);
        if (mysql_num_rows($res) > 0)
        {
            print("<div id=\"friends\">\n");
            while ($row = mysql_fetch_array($res))
            {
                if (empty($row['avatar']))
                    $avatar = "pic/default_avatar.gif";
                else
                    $avatar = $row['avatar'];
                $dt = get_date_time(gmtime() - 300);
                if ($row['last_access'] > $dt)
                    $status = "<font color=\"#008000\">Онлайн</font>";
                else
                    $status = "<font color=\"#FF0000\">Оффлайн</font>";
                if ($row["gender"] == "1")
                    $gender = "<img src=\"pic/male.gif\" alt=\"Парень\" title=\"Парень\" />";
                else
                    $gender = "<img src=\"pic/female.gif\" alt=\"Девушка\" title=\"Девушка\" />";
                print("<div class=\"friend\">\n");
                print("<div class=\"avatar\"><a href=\"userdetails.php?id=" . $row['id'] . "\"><img src=\"".$avatar."\" alt=\"\" /></a></div>\n");
                print("<div class=\"finfo\">\n");
                print("<p><b>Имя:</b>&nbsp;<a href=\"userdetails.php?id=" . $row['id'] . "\">" . get_user_class_color($row['class'], $row['name']) . "</a></p>\n");
                print("<p><b>Пол:</b>&nbsp;$gender</p>\n");
                print("<p><b>Класс:</b>&nbsp;" . get_user_class_name($row['class']) . "</p>\n");
                print("<p><b>Статус:</b>&nbsp;".$status."</p>\n");
                print("</div>\n");
                print("<div class=\"actions\">\n");
                print("<p><a href=\"message.php?action=sendmessage&receiver=" . $row['id'] . "\">Отправить сообщение</a></p>\n");
                print("<p><a href=\"friends.php?id=" . $row['id'] . "\">Друзья " . get_user_class_color($row['class'], $row['name']) . "</a></p>\n");
                if ($CURUSER['id'] == $id)
                    print("<p><a href=\"friends.php?action=delete&type=friend&targetid=" . $row['id'] . "\">Убрать из друзей</a></p>\n");
                print("</div>\n");
                print("<div style=\"clear:both;\"></div>\n");
                print("</div>\n");
            }
            print("</div>\n");
        }
        else
            print("<div class=\"tab_error\">У пользователя нет друзей.</div>\n");
        die();
    }
    elseif ($act == "downloaded")
    {
        $res = sql_query("SELECT snatched.torrent AS id, snatched.uploaded, snatched.seeder, snatched.downloaded, snatched.startdat, snatched.completedat, snatched.last_action, categories.name AS catname, categories.image AS catimage, categories.id AS catid, torrents.name, (torrents.f_seeders + torrents.seeders) as seeders, (torrents.f_leechers + torrents.leechers) as leechers FROM snatched JOIN torrents ON torrents.id = snatched.torrent JOIN categories ON torrents.category = categories.id WHERE snatched.finished='yes' AND userid = ".$id." ORDER BY torrent") or sqlerr(__FILE__,__LINE__);
        if (mysql_num_rows($res) > 0)
        {
            print "<table class=\"tt\" width=\"100%\">\n
            <tr>
            <td class=\"tt\" style=\"padding:0;margin:0;width:45px;\" align=\"center\"><img src=\"pic/genre.gif\" title=\"Категория\" alt=\"\" /></td>
            <td class=\"tt\"><img src=\"pic/release.gif\" title=\"Название\" alt=\"\" /></td>
            <td class=\"tt\" width=\"30\" align=\"center\"><img src=\"pic/seeders.gif\" title=\"Раздают\" alt=\"\" /></td>
            <td class=\"tt\" width=\"30\" align=\"center\"><img src=\"pic/leechers.gif\" title=\"Качают\" alt=\"\" /></td>
            <td class=\"tt\" width=\"30\" align=\"center\"><img src=\"pic/uploaded.gif\" title=\"Раздал\" alt=\"\" /></td>
            <td class=\"tt\" width=\"30\" align=\"center\"><img src=\"pic/downloaded.gif\" title=\"Скачал\" alt=\"\" /></td>
            <td class=\"tt\" width=\"30\" align=\"center\"><img src=\"pic/ratio.gif\" title=\"Скачал\" alt=\"\" /></td>
            <td class=\"tt\" width=\"30\" align=\"center\"><img src=\"pic/start.gif\" title=\"Начал\" alt=\"\" /></td>
            <td class=\"tt\" width=\"30\" align=\"center\"><img src=\"pic/end.gif\" title=\"Закончил\" alt=\"\" /></td>
            <td class=\"tt\" width=\"30\" align=\"center\"><img src=\"pic/seeded.gif\" title=\"Сид?\" alt=\"\" /></td>";
            while ($row = mysql_fetch_array($res))
            {
                if ($row["downloaded"] > 0)
                {
                    $ratio = number_format($row["uploaded"] / $row["downloaded"], 3);
                    $ratio = "<font color=\"" . get_ratio_color($ratio) . "\">".$ratio."</font>";
                }
                else
                {
            	    if ($row["uploaded"] > 0)
                        $ratio = "Inf.";
            	    else
            		    $ratio = "---";
                }
                $uploaded = mksize($row["uploaded"]);
                $downloaded = mksize($row["downloaded"]);
                if ($row["seeder"] == 'yes')
            	    $seeder = "<font color=\"green\">Да</font>";
                else
            	    $seeder = "<font color=\"red\">Нет</font>";
            	$cat = "<a href=\"browse.php?cat=".$row['catid']."\"><img src=\"pic/cats/".$row['catimage']."\" alt=\"".$row['catname']."\" border=\"0\" /></a>";
                print "<tr><td style=\"padding:0;margin:0;\" rowspan=\"2\">".$cat."</td><td colspan=\"9\"><a href=\"details.php?id=" . $row["id"] . "&amp;hit=1\"><b>" . view_saves($row["name"]) . "</b></a></td></tr>" .
                  "<tr><td align=\"left\" width=500></td><td align=\"center\">".$row['seeders']."</td><td align=\"center\">".$row['leechers']."</td><td align=\"center\"><nobr>".$uploaded."</nobr></td><td align=\"center\"><nobr>".$downloaded."</nobr></td><td align=\"center\">".$ratio."</td><td align=\"center\"><nobr style=\"font-size:10px;\">".$row['startdat']."</nobr></td><td align=\"center\"><nobr style=\"font-size:10px;\">".$row['completedat']."</nobr></td><td align=\"center\">".$seeder."</td>\n";
            }
            print "</table>";
        }
        else
            print("<div class=\"tab_error\">Пользователь не скачивал торрентов.</div>");
        die();
    }
    elseif ($act == "uploaded")
    {
        $res = sql_query("SELECT t.addtime, t.id, t.name, t.added, (t.f_seeders + t.seeders) as seeders, (t.f_leechers + t.leechers) as leechers, t.category, c.name AS catname, c.image AS catimage, c.id AS catid FROM torrents AS t LEFT JOIN categories AS c ON t.category = c.id WHERE t.owner = ".$id." ORDER BY t.addtime DESC") or sqlerr(__FILE__, __LINE__);
        if (mysql_num_rows($res) > 0)
        {
            print("<table class=\"tt\">\n" .
            "<tr><td class=\"tt\" style=\"padding:0;margin:0;width:45px;\" align=\"center\"><img src=\"pic/genre.gif\" title=\"Категория\" alt=\"\" /></td><td class=\"tt\"><img src=\"pic/release.gif\" title=\"Название\" alt=\"\" /></td><td class=\"tt\" width=\"30\" align=\"center\"><img src=\"pic/seeders.gif\" title=\"Раздают\" alt=\"\" /></td><td class=\"tt\" width=\"30\" align=\"center\"><img src=\"pic/leechers.gif\" title=\"Качают\" alt=\"\" /></td></tr>\n");
            $num_torr = array();
            $roll = sql_query("SELECT addtime FROM torrents WHERE owner = ".$id." ORDER BY addtime DESC") or sqlerr(__FILE__, __LINE__);
            while ($rows = mysql_fetch_assoc($roll))
            {
                   $nam = date('jmY', $rows['addtime']);
                   if(isset($num_torr[$nam])) {
                        $num_torr[$nam] += 1;
                   } else {
                        $num_torr[$nam] = 1;
                   }
            }
            while ($row = mysql_fetch_assoc($res))
            {
		$day_added = $row['addtime'];
		$thisdate = date('Y-m-d',$day_added);
		if($thisdate==$prevdate) {
			$cleandate = '';
		} else {
			$day_added = '  '.date('l d M Y', $row['addtime']);
			$dat = date('jmY', $row['addtime']);
			$cleandate = "<tr><td colspan=\"15\" class=\"colhead\">Торренты за ".$day_added."(".$num_torr[$dat].")</td></tr>\n";
		}
		$prevdate = $thisdate;
		$man = array(
			'Jan' => 'Января',
			'Feb' => 'Февраля',
			'Mar' => 'Марта',
			'Apr' => 'Апреля',
			'May' => 'Мая',
			'Jun' => 'Июня',
			'Jul' => 'Июля',
			'Aug' => 'Августа',
			'Sep' => 'Сентября',
			'Oct' => 'Октября',
			'Nov' => 'Ноября',
			'Dec' => 'Декабря'
		);
		foreach($man as $eng => $rus){
			$cleandate = str_replace($eng, $rus,$cleandate);
		}
		$dag = array(
			'Mon' => 'Понедельник',
			'Tues' => 'Вторник',
			'Wednes' => 'Среда',
			'Thurs' => 'Четверг',
			'Fri' => 'Пятница',
			'Satur' => 'Суббота',
			'Sun' => 'Воскресенье'
		);
		foreach($dag as $eng => $rus) {
			$cleandate = str_replace($eng.'day', $rus.'',$cleandate);
		}
		echo $cleandate."\n";
		$cat = "<a href=\"browse.php?cat=".$row['catid']."\"><img src=\"pic/cats/".$row['catimage']."\" alt=\"".$row['catname']."\" border=\"0\" /></a>";
                print("<tr><td rowspan=\"2\" style=\"padding:0;margin:0;\">".$cat."</td><td colspan=\"3\"><a href=\"details.php?id=" . $row["id"] . "&hit=1\"><b>" . view_saves($row["name"]) . "</b></a></td></tr>\n");
                print("<tr><td><font color=\"#808080\" size=\"1\">" . $row["added"] . "</font></td><td align=\"center\">".$row['seeders']."</td><td align=\"center\">".$row['leechers']."</td></tr>\n");
            }
            print("</table>");
        }
        else
            print("<div class=\"tab_error\">Пользователь не загружал торрентов.</div>");
        die();
    }
    elseif ($act == "downloading")
    {
        $res = sql_query("SELECT torrent, added, uploaded, downloaded, torrents.name AS torrentname, categories.name AS catname, categories.id AS catid, size, image, category, seeders, leechers FROM peers LEFT JOIN torrents ON peers.torrent = torrents.id LEFT JOIN categories ON torrents.category = categories.id WHERE userid = ".$id." AND seeder='no'") or sqlerr(__FILE__, __LINE__);
        if (mysql_num_rows($res) > 0)
            print(maketable($res));
        else
            print("<div class=\"tab_error\">Пользователь ничего не качает сейчас.</div>");
        die();
    }
    elseif ($act == "uploading")
    {
        $res = sql_query("SELECT torrent, added, uploaded, downloaded, torrents.name AS torrentname, categories.name AS catname, categories.id AS catid, size, image, category, seeders, leechers FROM peers LEFT JOIN torrents ON peers.torrent = torrents.id LEFT JOIN categories ON torrents.category = categories.id WHERE userid = ".$id." AND seeder='yes'") or sqlerr(__FILE__, __LINE__);
        if (mysql_num_rows($res) > 0)
            print(maketable($res));
        else
            print("<div class=\"tab_error\">Пользователь ничего не раздает сейчас.</div>");
        die();
    }
    elseif ($act == "moderate")
    {
        if (get_user_class() >= UC_MODERATOR && $user["class"] < get_user_class())
        {
		$enabled = $user["enabled"] == 'yes';
            print("<h2>Модерирование</h2>\n");
            print("<table width=\"100%\" cellpadding=\"5\">\n");
            print("<tr><td>\n");
            print("<form method=\"post\" action=\"modtask.php\">\n");
            print("<input type=\"hidden\" name=\"action\" value=\"edituser\">\n");
            print("<input type=\"hidden\" name=\"userid\" value=\"".$id."\">\n");
            print("<input type=\"hidden\" name=\"returnto\" value=\"userdetails.php?id=".$id."\">\n");
            print("<table width=\"100%\" cellpadding=\"5\" align=\"left\">\n");
            print("<tr><td class=\"rowhead\">Заголовок</td><td colspan=\"2\" align=\"left\"><input type=\"text\" size=\"60\" name=\"title\" value=\"" . htmlspecialchars($user['title']) . "\"></tr>\n");
          	$avatar = htmlspecialchars($user["avatar"]);
            print("<tr><td class=\"rowhead\">Аватар</td><td colspan=\"2\" align=\"left\"><input type=\"text\" size=\"60\" name=\"avatar\" value=\"".$avatar."\"></tr>\n");
   if ($CURUSER["class"] < UC_ADMINISTRATOR)
	  print("<input type=\"hidden\" name=\"donor\" value=\"".$user['donor']."\">\n");
	else {
		print("<tr><td class=\"rowhead\">Донор</td><td colspan=\"2\" align=\"left\"><input type=\"radio\" name=\"donor\" value=\"yes\"" .($user["donor"] == "yes" ? " checked" : "").">Да <input type=\"radio\" name=\"donor\" value=\"no\"" .($user["donor"] == "no" ? " checked" : "").">Нет</td></tr>\n");

		echo Awards($user['awards'],0); 
	}
          	if (get_user_class() == UC_MODERATOR && $user["class"] > UC_VIP)
          	    print("<input type=\"hidden\" name=\"class\" value=\"".$user['class']."\">\n");
          	else
          	{
                print("<tr><td class=\"rowhead\">Класс</td><td colspan=\"2\" align=\"left\"><select name=\"class\">\n");
                if (get_user_class() == UC_SYSOP)
                    $maxclass = UC_SYSOP;
                elseif (get_user_class() == UC_MODERATOR)
                    $maxclass = UC_VIP;
                else
                    $maxclass = get_user_class() - 1;
                for ($i = 0; $i <= $maxclass; ++$i)
                    print("<option value=\"$i\"" . ($user["class"] == $i ? " selected" : "") . ">".$prefix . get_user_class_name($i) . "\n");
                print("</select></td></tr>\n");

                print("<tr><td class=\"rowhead\">Причина изменения класса</td><td colspan=\"2\" align=\"left\"><input type=\"text\" name=\"classreason\"></td></tr>");
          	}
          	print("<tr><td class=\"rowhead\">Сбросить день рождения</td><td colspan=\"2\" align=\"left\"><input type=\"radio\" name=\"resetb\" value=\"yes\">Да<input type=\"radio\" name=\"resetb\" value=\"no\" checked>Нет</td></tr>\n");
          	$modcomment = htmlspecialchars($user["modcomment"]);
          	$supportfor = htmlspecialchars($user["supportfor"]);
          	print("<tr><td class=rowhead>Поддержка</td><td colspan=2 align=left><input type=radio name=support value=yes" .($user["support"] == "yes" ? " checked" : "").">Да <input type=radio name=support value=no" .($user["support"] == "no" ? " checked" : "").">Нет</td></tr>\n");
          	print("<tr><td class=rowhead>Поддержка для:</td><td colspan=2 align=left><textarea cols=60 rows=6 name=supportfor>$supportfor</textarea></td></tr>\n");
          	print("<tr><td class=rowhead>История пользователя</td><td colspan=2 align=left><textarea cols=60 rows=6".(get_user_class() < UC_SYSOP ? " readonly" : " name=modcomment").">".$modcomment."</textarea></td></tr>\n");
          	print("<tr><td class=rowhead>Добавить заметку</td><td colspan=2 align=left><textarea cols=60 rows=3 name=modcomm></textarea></td></tr>\n");
          	$warned = $user["warned"] == "yes";

           	print("<tr><td class=\"rowhead\"" . (!$warned ? " rowspan=\"2\"": "") . ">Предупреждение</td>
           	<td align=\"left\" width=\"20%\">" . ($warned ? "<input name=\"warned\" value=\"yes\" type=\"radio\" checked>Да<input name=\"warned\" value=\"no\" type=\"radio\">Нет" : "Нет" ) ."</td>");

          	if ($warned)
            {
          		$warneduntil = $user['warneduntil'];
          		if ($warneduntil == '0000-00-00 00:00:00')
              		print("<td align=\"center\">На неограниченый срок</td></tr>\n");
          		else
                {
              		print("<td align=\"center\">До $warneduntil");
          	    	print(" (" . mkprettytime(strtotime($warneduntil) - gmtime()) . " осталось)</td></tr>\n");
           	    }
            }
            else
            {
                print("<td>Предупредить на <select name=\"warnlength\">\n");
                print("<option value=\"0\">------</option>\n");
                print("<option value=\"1\">1 неделю</option>\n");
                print("<option value=\"2\">2 недели</option>\n");
                print("<option value=\"4\">4 недели</option>\n");
                print("<option value=\"8\">8 недель</option>\n");
                print("<option value=\"255\">Неограничено</option>\n");
                print("</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Комментарий в ЛС:</td></tr>\n");
                print("<tr><td colspan=\"2\" align=\"left\"><input type=\"text\" size=\"60\" name=\"warnpm\"></td></tr>");
            }
 
 print("<tr><td class=\"rowhead\" rowspan=\"2\">Включен</td><td align=\"center\" colspan=\"2\">".($enabled ? "<font color=\"green\">Пользователь включен</font>" : "<font color=\"red\">Пользователь отключен</font>")."</td></tr>");

$disabler = <<<DIS
<select name="dislength">
	<option value="0">------</option>
	<option value="1">1 неделю</option>
	<option value="2">2 недели</option>
	<option value="4">4 недели</option>
	<option value="8">8 недель</option>
	<option value="255">Неограничено</option>
</select>
DIS;

	if ($enabled)
		print("<tr><td>Отключить на:<br />".$disabler."</td><td>Причина отключения:<br /><input type=\"text\" name=\"disreason\" size=\"60\" /></td></td></tr>");
	else
		print("<tr><td>Включить?<br /><input name=\"enabled\" value=\"yes\" type=\"radio\">Да <input name=\"enabled\" value=\"no\" type=\"radio\" checked>Нет<br /></td><td>Причина включения:<br /><input type=\"text\" name=\"enareason\" size=\"60\" /></td></tr>");

		
 print("<tr><td class=\"rowhead\" rowspan=\"2\">Запрет заливки</td><td align=\"center\" colspan=\"2\">".($enabled ? "<font color=\"green\">Пользователь включен</font>" : "<font color=\"red\">Пользователь отключен</font>")."</td></tr>");
		
		$banup = <<<DIS
<select name="banuplength">
	<option value="0">------</option>
	<option value="1">1 неделю</option>
	<option value="2">2 недели</option>
	<option value="4">4 недели</option>
	<option value="8">8 недель</option>
	<option value="255">Неограничено</option>
</select>
DIS;

			if ($user['ban_upload'] == 0)
		print("<tr><td>Запретить заливку на:<br />".$banup."</td><td>Причина запрета:<br /><input type=\"text\" name=\"banupreason\" size=\"60\" /></td></td></tr>");
	else
		print("<tr><td>Разрешить заливку?<br /><input name=\"unbanupenabled\" value=\"yes\" type=\"radio\">Да <input name=\"unbanupenabled\" value=\"no\" type=\"radio\" checked>Нет<br /></td><td>Причина разрешения:<br /><input type=\"text\" name=\"banupreason\" size=\"60\" /></td></tr>");

		  ?>

<tr></td></tr>
<script type="text/javascript">

function togglepic(bu, picid, formid)
{
    var pic = document.getElementById(picid);
    var form = document.getElementById(formid);
    
    if(pic.src == bu + "/pic/plus.gif")
    {
        pic.src = bu + "/pic/minus.gif";
        form.value = "minus";
    }else{
        pic.src = bu + "/pic/plus.gif";
        form.value = "plus";
    }
}

</script>
<?
			print("<tr><td class=\"rowhead\">Изменить раздачу</td><td align=\"left\"><img src=\"pic/plus.gif\" id=\"uppic\" onClick=\"togglepic('".$DEFAULTBASEURL."','uppic','upchange')\" style=\"cursor: pointer;\">&nbsp;<input type=\"text\" name=\"amountup\" size=\"10\" /><td>\n<select name=\"formatup\">\n<option value=\"mb\">MB</option>\n<option value=\"gb\">GB</option></select></td></tr>");
            print("<tr><td class=\"rowhead\">Изменить скачку</td><td align=\"left\"><img src=\"pic/plus.gif\" id=\"downpic\" onClick=\"togglepic('".$DEFAULTBASEURL."','downpic','downchange')\" style=\"cursor: pointer;\">&nbsp;<input type=\"text\" name=\"amountdown\" size=\"10\" /><td>\n<select name=\"formatdown\">\n<option value=\"mb\">MB</option>\n<option value=\"gb\">GB</option></select></td></tr>");
            print("<tr><td class=\"rowhead\">Сбросить passkey</td><td colspan=\"2\" align=\"left\"><input name=\"resetkey\" value=\"1\" type=\"checkbox\"></td></tr>\n");
   if (5-$user["num_warned"]>0) 
  $mes_warn = "Добавив ".($Num_warn_disable-$user["num_warned"])." звезд пользователь заблокируется в ближайшие ".$autoclean_interval." сек."; 
      
   print("<tr><td class=\"rowhead\">Изменить<br>предупреждения</td><td colspan=\"2\" align=\"left\"><img src=\"pic/plus.gif\" id=\"warnpic\" onClick=\"togglepic('".$DEFAULTBASEURL."','warnpic','warnchange')\" style=\"cursor: pointer;\">&nbsp;<input type=\"text\" name=\"amountwarn\" size=\"3\" />&nbsp;".$mes_warn."</td></tr>");

print("<tr><td class=rowhead>Написание Лс</td><td colspan=2 align=left><input type=radio name=messagblock value=yes" .($user["messagblock"]=="yes" ? " checked" : "") . ">Да <input type=radio name=messagblock value=no" .($user["messagblock"]=="no" ? " checked" : "") . ">Нет</td></tr>\n");

print("<tr><td class=rowhead>Использовать Чат</td><td colspan=2 align=left><input type=radio name=schoutboxpos value=yes" .($user["schoutboxpos"]=="yes" ? " checked" : "") . ">Да <input type=radio name=schoutboxpos value=no" .($user["schoutboxpos"]=="no" ? " checked" : "") . ">Нет</td></tr>\n");

print ( "<tr><td class=\"rowhead\">Изменить бонус</td><td align=\"left\" colspan=\"2\"><img src=\"" . $pic_base_url . "plus.gif\" id=\"bpic\" onClick=\"togglepic('".$DEFAULTBASEURL."', 'bpic', 'bchange');\" style=\"cursor: pointer;\">&nbsp;<input type=\"text\" name=\"bonusup\" size=\"10\" /></td></tr>" );

   if ($CURUSER["class"] == UC_SYSTEM) {
  print("<tr><td class=\"rowhead\">Изменить кредиты</td><td colspan=\"2\" align=\"left\"><img src=\"pic/plus.gif\" id=\"moneypic\" onClick=\"togglepic('".$DEFAULTBASEURL."','moneypic','moneychange')\" style=\"cursor: pointer;\">&nbsp;<input type=\"text\" name=\"amountmoney\" size=\"3\" /></td></tr>");
}
 if ($CURUSER["class"] > UC_SYSTEM)
  	print("<input type=\"hidden\" name=\"deluser\">");
  else
  	print("<tr><td class=\"rowhead\">Удалить</td><td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"deluser\"></td></tr>");
  
			print("</td></tr>");
            print("<tr><td colspan=\"3\" align=\"center\"><input type=\"submit\" class=\"btn\" value=\"ОК\"></td></tr>\n");
            print("</table>\n");
            print("<input type=\"hidden\" id=\"upchange\" name=\"upchange\" value=\"plus\"><input type=\"hidden\" id=\"downchange\" name=\"downchange\" value=\"plus\"><input type=\"hidden\" id=\"warnchange\" name=\"warnchange\" value=\"plus\"><input type=\"hidden\" id=\"moneychange\" name=\"moneychange\" value=\"plus\"><input type=\"hidden\" id=\"bchange\" name=\"bchange\" value=\"plus\">\n");
            print("</form>\n");
            print("</td>\n</tr>\n</table>\n\n");
            die();
        }
        else
            die("У вас нет прав");
    }
    elseif ($act == "pm")
    {
        ?>
        <script language="JavaScript" type="text/javascript">
        function send_message(to, msg, subject)
        {
            if (msg == '' || subject == '')
            {
                alert('Вы не указали сообщение или тему.');
                return;
            }
            jQuery.post("user.php",{"user":to,"msg":msg,"subject":subject,"act":"sendmessage"},function (response) {
                jQuery("#operation").empty();
                jQuery("#operation").append(response);
            });
            document.pm.msg.value = '';
            document.pm.subject.value = '';
        };
        </script>
        <?
        print("<form name=\"pm\">\n");
        print("<h2>Личное сообщение</h2>\n");
        print("<table width=\"100%\" cellpadding=\"5\">\n");
        print("<tr><td>\n");
        print("<div id=\"operation\"></div>\n");
        print("<p>Тема: <input name=\"subject\" type=\"text\" style=\"width:370px;\" /></p>");
        textbbcode("pm", "msg", htmlspecialchars($text), $long);
        print("<p><input type=\"button\" value=\"Отправить\" onclick=\"javascript:send_message('".$id."', document.pm.msg.value, document.pm.subject.value);\"/>&nbsp;&nbsp;<input type=\"reset\" value=\"Отменить\" /></p>\n");
        print("</td></tr>\n");
        print("</table>\n");
        print("</form>\n");
        die();
    }

    elseif ($act == "sendmessage")
    {
        if (!empty($_POST['subject']) && !empty($_POST['msg']))
        {
            $dt = get_date_time();
            $textch = iconv(iconv_charset($_POST['msg']), "cp1251", $_POST['msg']);
            $subjectch = iconv(iconv_charset($_POST['subject']), "cp1251", $_POST['subject']);
            $text = sqlesc(saves($textch));
            $subject = sqlesc(saves($subjectch));
            sql_query("INSERT INTO messages (sender, receiver, subject, msg, added) VALUES (".sqlesc($CURUSER['id']).", ".$id.", ".$subject.", ".$text.", ".sqlesc($dt).")") or sqlerr(__FILE__,__LINE__);
            die("<div class=\"success\">Ваше сообщение отправлено.</div>");
        }
        else
            die("Вы не ввели тему или сообщение");
    }

    elseif ($act == "statistics")
    {
        $comments = get_row_count("comments", "WHERE user = $id");
        $seeder = get_row_count("peers", "WHERE userid = $id AND seeder = 'yes'");
        $leecher = get_row_count("peers", "WHERE userid = $id AND seeder = 'no'");
        $torrents = get_row_count("torrents", "WHERE owner = $id");
        $snatched = get_row_count("snatched", "WHERE userid = $id");
        $thanks = get_row_count("thanks", "WHERE userid = $id");
        $bookmarks = get_row_count("bookmarks", "WHERE userid = $id");
        $friends = get_row_count("friends", "WHERE userid = $id");
        $invites = get_row_count("invites", "WHERE inviter = $id");
        print("<h2>Статистика</h2>\n");
        print("<table width=\"100%\" cellpadding=\"5\">\n");
        print("<tr>\n");
        print("<td><b>Комментариев:</b> $comments</td>\n");
        print("<td><b>Качает:</b> $leecher</td>\n");
        print("<td><b>Раздает:</b> $seeder</td>\n");
        print("<td><b>Загрузил:</b> $torrents</td>\n");
        print("<td><b>Скачал:</b> $snatched</td>\n");
        print("</tr>\n");
        print("<tr>\n");
        print("<td><b>Спасибо:</b> $thanks</td>\n");
        print("<td><b>Закладок:</b> $bookmarks</td>\n");
        print("<td><b>Пригласил:</b> $bookmarks</td>\n");
        print("<td><b>Друзей:</b> $friends</td>\n");
        print("</tr>\n");
        print("</table>\n");
        die();
    }

    elseif ($act == "addtofriends")
    {
        $type = $_POST['type'];
        if (empty($type) || empty($CURUSER['id']))
            die("Прямой доступ закрыт");
        if ($type == "add")
        {
            $res = sql_query("SELECT id, status FROM friends WHERE userid=" . sqlesc($CURUSER['id']) . " AND friendid = ".$id) or sqlerr(__FILE__, __LINE__);
            $row = mysql_fetch_array($res);
            if ($row['status'] == 'yes')
                die("<div class=\"error\">Пользователь уже ваш друг.</div>");
            elseif ($row['status'] == 'pending')
                die("<div class=\"error\">Вы уже отправляли запрос. Дождитеcь решения пользователя.</div>");
            elseif ($row['status'] == 'no')
                die("<div class=\"error\">Пользователь отказал Вам в дружбе.</div>");
            else
            {
                sql_query("INSERT IGNORE INTO friends (userid, friendid) VALUES (" . sqlesc($CURUSER['id']) . ", ".$id.")") or sqlerr(__FILE__, __LINE__);
                $newid = mysql_insert_id();
                $dt = sqlesc(get_date_time());
                $msg = sqlesc("Пользователь [url=userdetails.php?id=" . $CURUSER['id'] . "]" . $CURUSER['username'] . "[/url] желает добавить Вас в список друзей. [[url=friends.php?id=".$newid."&act=accept&user=" . $CURUSER['id'] . "]Принять[/url]] [[url=friends.php?id=".$newid."&act=surrender&user=" . $CURUSER['id'] . "]Отказать[/url]]");
                $subj = sqlesc("Предложение дружбы.");
                sql_query("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0, ".$id.", ".$dt.", ".$msg.", ".$subj.")") or sqlerr(__FILE__, __LINE__);
                die("<div class=\"success\">Запрос на дружбу отправлен. Дождитесь ответа пользователя.</div>");
            }
        }
        if ($type = "delete")
        {
            sql_query("DELETE FROM friends WHERE userid = ".$id." AND friendid = " . sqlesc($CURUSER['id']));
            sql_query("DELETE FROM friends WHERE friendid = ".$id." AND userid = " . sqlesc($CURUSER['id']));
            $dt = sqlesc(get_date_time());
            $msg = sqlesc("Пользователь [url=userdetails.php?id=" . $CURUSER['id'] . "]" . $CURUSER['username'] . "[/url] удалил Вас из друзей.");
            $subj = sqlesc("Отмена дружбы.");
            sql_query("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0, ".$id.", ".$dt.", ".$msg.", ".$subj.")") or sqlerr(__FILE__, __LINE__);
            die("<div class=\"success\">Пользователь удален из друзей.</div>");
        }
        die();
    }
    else
        die("Прямой доступ запрещен");
}
else
    die("Прямой доступ запрещен");
?>