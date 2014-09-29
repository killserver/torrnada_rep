<?

/*
// +--------------------------------------------------------------------------+
// | Project:    TBDevYSE - TBDev Yuna Scatari Edition                        |
// +--------------------------------------------------------------------------+
// | This file is part of TBDevYSE. TBDevYSE is based on TBDev,               |
// | originally by RedBeard of TorrentBits, extensively modified by           |
// | Gartenzwerg.                                                             |
// |                                                                          |
// | TBDevYSE is free software; you can redistribute it and/or modify         |
// | it under the terms of the GNU General Public License as published by     |
// | the Free Software Foundation; either version 2 of the License, or        |
// | (at your option) any later version.                                      |
// |                                                                          |
// | TBDevYSE is distributed in the hope that it will be useful,              |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
// | GNU General Public License for more details.                             |
// |                                                                          |
// | You should have received a copy of the GNU General Public License        |
// | along with TBDevYSE; if not, write to the Free Software Foundation,      |
// | Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA            |
// +--------------------------------------------------------------------------+
// |                                               Do not remove above lines! |
// +--------------------------------------------------------------------------+
*/

require_once("include/bittorrent.php");
dbconn(false);
loggedinorreturn();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	header("Content-Type: text/html; charset=".$tracker_lang['language_charset']);
	if (empty($_POST["id"])) {
		stdmsg($tracker_lang['error'], "Вы не выбрали тип бонуса!");
		die();
	}
	$id = (int) $_POST["id"];
	if (!is_valid_id($id)) {
		stdmsg($tracker_lang['error'], $tracker_lang['access_denied']);
		die();
	}
	$res = sql_query("SELECT * FROM bonus WHERE id = $id ORDER BY  `bonus`.`cat` ASC ") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	$points = $arr["points"];
	$type = $arr["type"];
	if ($CURUSER["bonus"] < $points) {
		stdmsg($tracker_lang['error'], "У вас недостаточно бонусов!");
		die();
	}
/*
	switch ($type) {
		case "traffic":
			$traffic = $arr["quanity"];
			if (!sql_query("UPDATE users SET bonus = bonus - $points, uploaded = uploaded + $traffic WHERE id = ".sqlesc($CURUSER["id"]))) {
				stdmsg($tracker_lang['error'], "Не могу обновить бонус!");
				die();
			}
			stdmsg($tracker_lang['success'], "Бонус обменян на траффик!");
			break;
		case "invite":
			$invites = $arr["quanity"];
			if (!sql_query("UPDATE users SET bonus = bonus - $points, invites = invites + $invites WHERE id = ".sqlesc($CURUSER["id"]))) {
				stdmsg($tracker_lang['error'], "Не могу обновить бонус!");
				die();
			}
			stdmsg($tracker_lang['success'], "Бонус обменян на приглашения!");
			break;
		default:
			stdmsg($tracker_lang['error'], "Unknown bonus type!");
	}
*/


	switch ($type) {

		case "traffic":
			$traffic = $arr["quanity"];
			$img = $arr["image"];

			if (!sql_query("UPDATE users SET bonus = bonus - $points, yesuploaded = 'yes', uploadednum = uploadednum + '$traffic', uploadedimg = '$img', rukzak = 'yes' WHERE id = ".sqlesc($CURUSER["id"]))) {
				stdmsg($tracker_lang['error'], "Не могу обновить бонус!");
    header ("Refresh: 5; url=mybonus.php");
				die();
			}
			stdmsg($tracker_lang['success'], "Бонус обменян на траффик!");
			$trafficnum = $arr["quanity"]/1024/1024/1024;
write_log("Бонус обменян на траффик пользователем " . $CURUSER["username"]." в размере " . $trafficnum."GB","5DDB6E","bonus");
@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
    header ("Refresh: 5; url=mybonus.php");
			break;



case "download":
             $download = $arr["quanity"];
			$img = $arr["image"];
          if ($CURUSER["bonus"] < $points) {
              stdmsg($tracker_lang['error'], "У Вас нет столько бонусов");
    header ("Refresh: 5; url=mybonus.php");
              die;
         }

             if (!sql_query("UPDATE users SET bonus = bonus - $points, yesdownload = 'yes', downloadednum = downloadednum + '$download', downloadedimg = '$img', rukzak = 'yes' WHERE id = ".sqlesc($CURUSER["id"]))) {
                stdmsg($tracker_lang['error'], "Не могу обновить бонусы!");
    header ("Refresh: 5; url=mybonus.php");
                die();
             }
             stdmsg($tracker_lang['success'], "Бонус обменян на списание раздачи!");
			$downloadnum = $arr["quanity"]/1024/1024/1024;
write_log("Бонус обменян на списание раздачи пользователем " . $CURUSER["username"]." в размере " . $downloadnum."GB","5DDB6E","bonus");
@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
    header ("Refresh: 5; url=mybonus.php");
             break;



case "download1":
             $download = $arr["quanity"];
			$img = $arr["image"];
          if ($CURUSER["bonus"] < $points) {
              stdmsg($tracker_lang['error'], "У Вас нет столько бонусов");
    header ("Refresh: 5; url=mybonus.php");
              die;
         }

             if (!sql_query("UPDATE users SET bonus = bonus - $points, yesdownload = 'yes', downloadednum = downloadednum + '$download', downloadedimg = '$img', rukzak = 'yes' WHERE id = ".sqlesc($CURUSER["id"]))) {
                stdmsg($tracker_lang['error'], "Не могу обновить бонусы!");
    header ("Refresh: 5; url=mybonus.php");
                die();
             }
             stdmsg($tracker_lang['success'], "Бонус обменян на списание раздачи!");
			$downloadnum = $arr["quanity"]/1024/1024/1024;
write_log("Бонус обменян на списание раздачи пользователем " . $CURUSER["username"]." в размере " . $downloadnum."GB","5DDB6E","bonus");
@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
    header ("Refresh: 5; url=mybonus.php");
             break;












		case "invite":

$invitesimg = $arr["image"];
			$invites = $arr["quanity"];
			if (!sql_query("UPDATE users SET bonus = bonus - $points, invitesyes = 'yes', invitesnum = invitesnum + '$invite', invitesimg = '' WHERE id = ".sqlesc($CURUSER["id"]))) {
				stdmsg($tracker_lang['error'], "Не могу обновить бонус!");
    header ("Refresh: 5; url=mybonus.php");
				die();
			}
			stdmsg($tracker_lang['success'], "Бонус обменян на приглашения!");
			$invitesnum = $arr["quanity"];
write_log("Бонус обменян на приглашения пользователем " . $CURUSER["username"]." в размере " . $invitesnum,"5DDB6E","bonus");
@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
    header ("Refresh: 5; url=mybonus.php");
			break;






case "changtitle": 
            if ($CURUSER['yeschangetitle'] == 'yes') { 
                stdmsg($tracker_lang['error'], "Вам что бонусы некуда девать!? У вас уже есть возможность сменить заголовок <a href=my.php>тут</a>.", 'error'); 
    header ("Refresh: 3; url=mybonus.php");
                die(); 
            } 
            if (!sql_query("UPDATE users SET bonus = bonus - $points, yeschangetitle = 'yes' WHERE id = ".sqlesc($CURUSER["id"]))) { 
                stdmsg($tracker_lang['error'], "Не могу обновить бонус!", 'error'); 
    header ("Refresh: 5; url=mybonus.php");
                die(); 
            } 
            stdmsg($tracker_lang['success'], "Бонус обменян на смену заголовка.<br />Вы можете сменить его <a href=my.php>здесь</a>"); 
write_log("Бонус обменян на смену заголовка пользователем " . $CURUSER["username"],"5DDB6E","bonus");
@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
    header ("Refresh: 5; url=mybonus.php");
            break;







case "channame":  
            if ($CURUSER['channame'] == 'yes') {  
                stdmsg($tracker_lang['error'], "Вам что бонусы некуда девать!? У вас уже есть возможность сменить имя <a href=my.php>тут</a>.", 'error');  
    header ("Refresh: 3; url=mybonus.php");
                die();  
            }  
            if (!sql_query("UPDATE users SET bonus = bonus - $points, channame = 'yes' WHERE id = ".sqlesc($CURUSER["id"]))) {  
                stdmsg($tracker_lang['error'], "Не могу обновить бонус!", 'error');  
    header ("Refresh: 5; url=mybonus.php");
                die();  
            }  
            stdmsg($tracker_lang['success'], "Бонус обменян на смену ника.<br />Вы можете сменить его <a href=my.php>здесь</a>");  
write_log("Бонус обменян на смену ника пользователем " . $CURUSER["username"],"5DDB6E","bonus");
@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
    header ("Refresh: 5; url=mybonus.php");
            break; 








case "killratio": 
if ($CURUSER['killratio'] == 'yes') {   
stdmsg($tracker_lang['error'], "Вам что бонусы некуда девать!? У вас уже есть возможность убить рейтинг.", 'error');
    header ("Refresh: 3; url=mybonus.php");
die();   
}   
			$img = $arr["image"];
if (!sql_query("UPDATE users SET bonus = bonus - $points, killratio = 'yes', killratioimg = '$img', rukzak = 'yes'  WHERE id = ".sqlesc($CURUSER["id"]))) { 
stdmsg($tracker_lang['error'], "Не могу обновить бонус!"); 
    header ("Refresh: 5; url=mybonus.php");
die(); 
} 
stdmsg($tracker_lang['success'], "Бонус обменян на убивания рейтинга!"); 
write_log("Бонус обменян на убивания рейтинга пользователем " . $CURUSER["username"],"5DDB6E","bonus");
@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
    header ("Refresh: 5; url=mybonus.php");
break; 







case "killcomm": 
if ($CURUSER['killcomm'] == 'yes') {   
stdmsg($tracker_lang['error'], "Вам что бонусы некуда девать!? У вас уже есть возможность забить кляп.", 'error');  
    header ("Refresh: 3; url=mybonus.php");
die();   
}   
			$img = $arr["image"];
if (!sql_query("UPDATE users SET bonus = bonus - $points, killcomm = 'yes', killcommimg = '$img', rukzak = 'yes'  WHERE id = ".sqlesc($CURUSER["id"]))) { 
stdmsg($tracker_lang['error'], "Не могу обновить бонус!"); 
    header ("Refresh: 5; url=mybonus.php");
die(); 
} 
stdmsg($tracker_lang['success'], "Бонус обменян на забивание кляпа!");
write_log("Бонус обменян на забивание кляпа пользователем " . $CURUSER["username"],"5DDB6E","bonus");
@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
    header ("Refresh: 5; url=mybonus.php");
break; 







case "oldcomm": 
if ($CURUSER['oldcomm'] == 'yes') {   
stdmsg($tracker_lang['error'], "Вам что бонусы некуда девать!? У вас уже есть Адмирал в запасе.", 'error');  
    header ("Refresh: 3; url=mybonus.php");
die();   
}   
			$img = $arr["image"];
if (!sql_query("UPDATE users SET bonus = bonus - $points, oldcomm = 'yes', oldcommimg = '$img', rukzak = 'yes'  WHERE id = ".sqlesc($CURUSER["id"]))) { 
stdmsg($tracker_lang['error'], "Не могу обновить бонус!"); 
    header ("Refresh: 5; url=mybonus.php");
die(); 
} 
stdmsg($tracker_lang['success'], "Бонус обменян на Адмирала!"); 
write_log("Бонус обменян на Адмирала пользователем " . $CURUSER["username"],"5DDB6E","bonus");
@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
    header ("Refresh: 5; url=mybonus.php");
break; 






case "killname": 
if ($CURUSER['killname'] == 'yes') {   
stdmsg($tracker_lang['error'], "Вам что бонусы некуда девать!? У вас уже есть эфект Нубасика.", 'error');   
    header ("Refresh: 3; url=mybonus.php");
die();   
}   
			$img = $arr["image"];
if (!sql_query("UPDATE users SET bonus = bonus - $points, killname = 'yes', killnameimg = '$img', rukzak = 'yes'  WHERE id = ".sqlesc($CURUSER["id"]))) { 
stdmsg($tracker_lang['error'], "Не могу обновить бонус!"); 
    header ("Refresh: 5; url=mybonus.php");
die(); 
} 
stdmsg($tracker_lang['success'], "Бонус обменян на эффект Нубасика!");
write_log("Бонус обменян на эффект Нубасика пользователем " . $CURUSER["username"],"5DDB6E","bonus");
@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
    header ("Refresh: 5; url=mybonus.php");
break; 









case "oldname":
if ($CURUSER['yesoldname'] == 'yes') {   
stdmsg($tracker_lang['error'], "Вам что бонусы некуда девать!? У вас уже есть эфект Швабры.", 'error');   
    header ("Refresh: 3; url=mybonus.php");
die();   
}   
			$img = $arr["image"];
if (!sql_query("UPDATE users SET bonus = bonus - $points, yesoldname = 'yes', oldnameimg = '$img', rukzak = 'yes'   WHERE id = ".sqlesc($CURUSER["id"]))) { 
stdmsg($tracker_lang['error'], "Не могу обновить бонус!"); 
    header ("Refresh: 5; url=mybonus.php");
die(); 
} 
stdmsg($tracker_lang['success'], "Бонус обменян на Швабру!"); 
write_log("Бонус обменян на Швабру пользователем " . $CURUSER["username"],"5DDB6E","bonus");
@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
    header ("Refresh: 5; url=mybonus.php");
break; 









case "reklyes": 
if ($CURUSER['reklyes'] == 'yes') {   
stdmsg($tracker_lang['error'], "Вам что бонусы некуда девать!? У вас уже скрыта реклама.", 'error');   
    header ("Refresh: 3; url=mybonus.php");
die();   
}   

            $days = $arr["quanity"]; 
$rekltime = get_date_time(TIMENOW + $days * 86400);
			$img = $arr["image"];
if (!sql_query("UPDATE users SET bonus = bonus - $points, reklyes = 'no', rekltime = ".sqlesc($rekltime).", reklyesimg = '$img', rukzak = 'yes'    WHERE id = ".sqlesc($CURUSER['id']))) { 
stdmsg($tracker_lang['error'], "Не могу обновить бонус!"); 
    header ("Refresh: 5; url=mybonus.php");
die(); 
} 
stdmsg($tracker_lang['success'], "Бонус обменян на убийство куска рекламы!"); 
write_log("Бонус обменян на убийство куска рекламы пользователем " . $CURUSER["username"],"5DDB6E","bonus");
@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
    header ("Refresh: 5; url=mybonus.php");
break; 









case "buploader": 
            $buploader = $arr["quanity"]; 
            if (!sql_query("UPDATE users SET bonus = bonus - $points, bonuploadernum = '$buploader' WHERE id = ".sqlesc($CURUSER["id"]))) { 
                stdmsg($tracker_lang['error'], "Не могу обновить бонус!"); 
    header ("Refresh: 5; url=mybonus.php");
                die(); 
            } 
            stdmsg($tracker_lang['success'], "Бонус обменян на возможность загрузки раздачи!"); 
write_log("Бонус обменян на возможность загрузки раздачи пользователем " . $CURUSER["username"],"5DDB6E","bonus");
@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
    header ("Refresh: 5; url=mybonus.php");
            break; 








case "free": 
if (get_user_class() >= UC_MODERATOR){ 
stderr("Ошибка","Вам что бонусы некуда девать?"); 
    header ("Refresh: 3; url=mybonus.php");
}
            if (!sql_query("UPDATE users SET bonus = bonus - $points, possfree = 'yes' WHERE id = ".sqlesc($CURUSER["id"]))) { 
                stdmsg($tracker_lang['error'], "Не могу обновить бонус!", 'error'); 
    header ("Refresh: 5; url=mybonus.php");
                die(); 
            } 
            stdmsg($tracker_lang['success'], "Бонус обменян возможность сделать одну раздачу золотой (Скаченное не учитывается, а раздача учитывается на 100%)"); 
@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
    header ("Refresh: 5; url=mybonus.php");
            break; 




		default:
			stdmsg($tracker_lang['error'], "Unknown bonus type!".(get_user_class() >= UC_SYSTEM ? $type : ""));


	}


} else {
stdhead($tracker_lang['my_bonus']);
?>
<script language="javascript" type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript">
function send(){

    var frm = document.mybonus;
	var bonus_type = '';

    for (var i=0;i < frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=='radio') {
            if(elmnt.checked == true){ bonus_type = elmnt.value; break;}
        }
    }

	var ajax = new tbdev_ajax();
	ajax.onShow ('');
	var varsString = "";
	ajax.requestFile = "mybonus.php";
	ajax.setVar("id", bonus_type);
	ajax.method = 'POST';
	ajax.element = 'ajax';
	ajax.sendAJAX(varsString);
}
</script>
<div id="loading-layer" style="display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000">
	<div style="font-weight:bold" id="loading-layer-text">Загрузка. Пожалуйста, подождите...</div><br />
	<img src="pic/loading.gif" border="0" />
</div>
<div id="ajax">
<table class="embedded" width="100%" border="1" cellspacing="0" cellpadding="16">
<?


$cacheStatFile = "include/cache/mybonus_1.txt";  
$expire = 10*60*60; // 20 минут 
if (file_exists($cacheStatFile) && filesize($cacheStatFile)<>0 && filemtime($cacheStatFile) > (time() - $expire)) {  
   $output.=file_get_contents($cacheStatFile);  
} else  
{ 

	$my_points = $CURUSER["bonus"];
	$res = sql_query("SELECT * FROM bonus where cat=1 ORDER BY  bonus.id ASC") or sqlerr(__FILE__,__LINE__);
	while ($arr = mysql_fetch_assoc($res)) {
		$id = $arr["id"];
		$bonus = $arr["name"];
		$image = $arr["image"];
		$points = $arr["points"];
		$descr = $arr["description"];
		$output .= "<tr><td><img src=$image></td><td><b>$bonus</b><br />$descr</td><td><center>$points&nbsp;/&nbsp;$my_points</center></td><td><center><input type=\"radio\" name=\"bonus_id\" value=\"$id\" /></center></td></tr>\n";
	}
}
$cacheStatFile = "include/cache/mybonus_2.txt";  
$expire = 10*60*60; // 20 минут 
if (file_exists($cacheStatFile) && filesize($cacheStatFile)<>0 && filemtime($cacheStatFile) > (time() - $expire)) {  
   $output2.=file_get_contents($cacheStatFile);  
} else  
{ 
	$res2 = sql_query("SELECT * FROM bonus where cat=6 ORDER BY  bonus.id ASC") or sqlerr(__FILE__,__LINE__);
	while ($arr2 = mysql_fetch_assoc($res2)) {
		$id2 = $arr2["id"];
		$bonus2 = $arr2["name"];
		$image2 = $arr2["image"];
		$points2 = $arr2["points"];
		$descr2 = $arr2["description"];
		$output2 .= "<tr><td><img src=$image2></td><td><b>$bonus2</b><br />$descr2</td><td><center>$points2&nbsp;/&nbsp;$my_points</center></td><td><center><input type=\"radio\" name=\"bonus_id\" value=\"$id2\" /></center></td></tr>\n";
	}
}
$cacheStatFile = "include/cache/mybonus_3.txt";  
$expire = 10*60*60; // 20 минут 
if (file_exists($cacheStatFile) && filesize($cacheStatFile)<>0 && filemtime($cacheStatFile) > (time() - $expire)) {  
   $output3.=file_get_contents($cacheStatFile);  
} else  
{ 
	$res3 = sql_query("SELECT * FROM bonus where cat=2 ORDER BY  bonus.id ASC") or sqlerr(__FILE__,__LINE__);
	while ($arr3 = mysql_fetch_assoc($res3)) {
		$id3 = $arr3["id"];
		$bonus3 = $arr3["name"];
		$image3 = $arr3["image"];
		$points3 = $arr3["points"];
		$descr3 = $arr3["description"];
		$output3 .= "<tr><td><img src=$image3></td><td><b>$bonus3</b><br />$descr3</td><td><center>$points3&nbsp;/&nbsp;$my_points</center></td><td><center><input type=\"radio\" name=\"bonus_id\" value=\"$id3\" /></center></td></tr>\n";
	}
}
$cacheStatFile = "include/cache/mybonus_4.txt";  
$expire = 10*60*60; // 20 минут 
if (file_exists($cacheStatFile) && filesize($cacheStatFile)<>0 && filemtime($cacheStatFile) > (time() - $expire)) {  
   $output4.=file_get_contents($cacheStatFile);  
} else  
{ 
	$res4 = sql_query("SELECT * FROM bonus where cat=3 ORDER BY  bonus.id ASC") or sqlerr(__FILE__,__LINE__);
	while ($arr4 = mysql_fetch_assoc($res4)) {
		$id4 = $arr4["id"];
		$bonus4 = $arr4["name"];
		$image4 = $arr4["image"];
		$points4 = $arr4["points"];
		$descr4 = $arr4["description"];
		$output4 .= "<tr><td><img src=$image4></td><td><b>$bonus4</b><br />$descr4</td><td><center>$points4&nbsp;/&nbsp;$my_points</center></td><td><center><input type=\"radio\" name=\"bonus_id\" value=\"$id4\" /></center></td></tr>\n";
	}
}

$cacheStatFile = "include/cache/mybonus_5.txt";  
$expire = 10*60*60; // 20 минут 
if (file_exists($cacheStatFile) && filesize($cacheStatFile)<>0 && filemtime($cacheStatFile) > (time() - $expire)) {  
   $output5.=file_get_contents($cacheStatFile);  
} else  
{ 
	$res5 = sql_query("SELECT * FROM bonus where cat=4 ORDER BY  bonus.id ASC") or sqlerr(__FILE__,__LINE__);
	while ($arr5 = mysql_fetch_assoc($res5)) {
		$id5 = $arr5["id"];
		$bonus5 = $arr5["name"];
		$image5 = $arr5["image"];
		$points5 = $arr5["points"];
		$descr5 = $arr5["description"];
		$output5 .= "<tr><td><img src=$image5></td><td><b>$bonus5</b><br />$descr5</td><td><center>$points5&nbsp;/&nbsp;$my_points</center></td><td><center><input type=\"radio\" name=\"bonus_id\" value=\"$id5\" /></center></td></tr>\n";
	}
}


$mybonus = '<tr><td class="colhead" colspan="4">Мой бонус ('.$CURUSER["bonus"].' бонусов в наличии / '.$points_per_hour.' бонусов в час)</td></tr><tr><td class="colhead">Картинка</td><td class="colhead">Тип бонуса</td><td class="colhead">Очки</td><td class="colhead">Выбор</td></tr><form action="mybonus.php" name="mybonus" method="post">'.$output.$output2.$output3.$output4.$output5.'<tr><td colspan="3"><input type="submit" onClick="send(); return false;" value="Обменять" /></td></tr></form>';

echo $mybonus;
?>


</table>
</div>
<?
stdfoot();
}
?>