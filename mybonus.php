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
	if (empty($_POST["bonus_id"])) {
		stdmsg($tracker_lang['error'], "Вы не выбрали тип бонуса!");
		die();
	}
	$id = (int) $_POST["bonus_id"];
	if (!is_valid_id($id)) {
		stdmsg($tracker_lang['error'], $tracker_lang['access_denied']);
		die();
	}
	$res = sql_query("SELECT * FROM bonus WHERE id = $id") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	$points = $arr["points"];
	$type = $arr["type"];
	if($CURUSER["bonus"] < $points) {
		stdmsg($tracker_lang['error'], "У вас недостаточно бонусов!");
		die();
	}
	switch ($type) {
		case "traffic":
			$traffic = $arr["quanity"];
			if (!sql_query("UPDATE users SET bonus = bonus - $points, uploaded = uploaded + $traffic WHERE id = ".sqlesc($CURUSER["id"]))) {
				header("Refresh: 5; url=mybonus.php");
				stdmsg($tracker_lang['error'], "Не могу обновить бонус!");
				die();
			}
			$trafficnum = $arr["quanity"]/1024/1024/1024;
			write_log("Бонус обменян на траффик пользователем " . $CURUSER["username"]." в размере " . $trafficnum."GB","5DDB6E","bonus");
			@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
			header("Refresh: 5; url=mybonus.php");
			stdmsg($tracker_lang['success'], "Бонус обменян на траффик!");
			break;

		case "download":
			$download = $arr["quanity"];
			$img = $arr["image"];
			if($CURUSER["bonus"] < $points) {
				header("Refresh: 5; url=mybonus.php");
				stdmsg($tracker_lang['error'], "У Вас нет столько бонусов");
				die();
			}
			if(!sql_query("UPDATE users SET bonus = bonus - $points, downloaded = downloaded - '$download' WHERE id = ".sqlesc($CURUSER["id"]))) {
				stdmsg($tracker_lang['error'], "Не могу обновить бонусы!");
				header("Refresh: 5; url=mybonus.php");
				die();
			}
			$downloadnum = $arr["quanity"]/1024/1024/1024;
			write_log("Бонус обменян на списание раздачи пользователем " . $CURUSER["username"]." в размере " . $downloadnum."GB","5DDB6E","bonus");
			@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
			header("Refresh: 5; url=mybonus.php");
			stdmsg($tracker_lang['success'], "Бонус обменян на списание раздачи!");
			break;
		case "download1":
			$download = $arr["quanity"];
			$img = $arr["image"];
			if($CURUSER["bonus"] < $points) {
				header("Refresh: 5; url=mybonus.php");
				stdmsg($tracker_lang['error'], "У Вас нет столько бонусов");
				die();
			}
			if (!sql_query("UPDATE users SET bonus = bonus - $points, downloaded = downloaded - '$download' WHERE id = ".sqlesc($CURUSER["id"]))) {
				header("Refresh: 5; url=mybonus.php");
				stdmsg($tracker_lang['error'], "Не могу обновить бонусы!");
				die();
			}
			$downloadnum = $arr["quanity"]/1024/1024/1024;
			write_log("Бонус обменян на списание раздачи пользователем " . $CURUSER["username"]." в размере " . $downloadnum."GB","5DDB6E","bonus");
			@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
			header("Refresh: 5; url=mybonus.php");
			stdmsg($tracker_lang['success'], "Бонус обменян на списание раздачи!");
			break;


		case "invite":
			$invites = $arr["quanity"];
			if (!sql_query("UPDATE users SET bonus = bonus - $points, invites = invites + $invites WHERE id = ".sqlesc($CURUSER["id"]))) {
				header("Refresh: 5; url=mybonus.php");
				stdmsg($tracker_lang['error'], "Не могу обновить бонус!");
				die();
			}
			write_log("Бонус обменян на приглашения пользователем " . $CURUSER["username"]." в размере " . $invitesnum,"5DDB6E","bonus");
			@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
			header("Refresh: 5; url=mybonus.php");
			stdmsg($tracker_lang['success'], "Бонус обменян на приглашения!");
			break;

		case "changtitle": 
			if($CURUSER['yeschangetitle'] == 'yes') { 
				stdmsg($tracker_lang['error'], "Вам что бонусы некуда девать!? У вас уже есть возможность сменить заголовок <a href=my.php>тут</a>.", 'error'); 
				header("Refresh: 3; url=mybonus.php");
				die(); 
			} 
			if(!sql_query("UPDATE users SET bonus = bonus - $points, yeschangetitle = 'yes' WHERE id = ".sqlesc($CURUSER["id"]))) { 
				stdmsg($tracker_lang['error'], "Не могу обновить бонус!", 'error'); 
				header("Refresh: 5; url=mybonus.php");
				die(); 
			} 
			write_log("Бонус обменян на смену заголовка пользователем " . $CURUSER["username"],"5DDB6E","bonus");
			@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
			header("Refresh: 5; url=mybonus.php");
			stdmsg($tracker_lang['success'], "Бонус обменян на смену заголовка.<br />Вы можете сменить его <a href=my.php>здесь</a>"); 
			break;

		case "channame":  
			if($CURUSER['channame'] == 'yes') {
				header("Refresh: 3; url=mybonus.php");
				stdmsg($tracker_lang['error'], "Вам что бонусы некуда девать!? У вас уже есть возможность сменить имя <a href=my.php>тут</a>.", 'error');
		                die();
			}
			if(!sql_query("UPDATE users SET bonus = bonus - $points, channame = 'yes' WHERE id = ".sqlesc($CURUSER["id"]))) {
				stdmsg($tracker_lang['error'], "Не могу обновить бонус!", 'error');
				header("Refresh: 5; url=mybonus.php");
				die();
			}  
			write_log("Бонус обменян на смену ника пользователем " . $CURUSER["username"],"5DDB6E","bonus");
			@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
			header("Refresh: 5; url=mybonus.php");
			stdmsg($tracker_lang['success'], "Бонус обменян на смену ника.<br />Вы можете сменить его <a href=my.php>здесь</a>");
			break;

		case "reklyes":
			if($CURUSER['reklyes'] == 'yes') {
				stdmsg($tracker_lang['error'], "Вам что бонусы некуда девать!? У вас уже скрыта реклама.", 'error');
				header("Refresh: 3; url=mybonus.php");
				die();
			}
			$days = $arr["quanity"];
			$rekltime = get_date_time(TIMENOW + $days * 86400);
			$img = $arr["image"];
			if(!sql_query("UPDATE users SET bonus = bonus - $points, reklyes = 'no', rekltime = ".sqlesc($rekltime)." WHERE id = ".sqlesc($CURUSER['id']))) {
				header("Refresh: 5; url=mybonus.php");
				stdmsg($tracker_lang['error'], "Не могу обновить бонус!");
				die();
			}
			write_log("Бонус обменян на убийство куска рекламы пользователем " . $CURUSER["username"],"5DDB6E","bonus");
			@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
			header("Refresh: 5; url=mybonus.php");
			stdmsg($tracker_lang['success'], "Бонус обменян на убийство куска рекламы!");
			break;

		case "buploader": 
			$buploader = $arr["quanity"]; 
			if(!sql_query("UPDATE users SET bonus = bonus - $points, bonuploadernum = '$buploader' WHERE id = ".sqlesc($CURUSER["id"]))) { 
				stdmsg($tracker_lang['error'], "Не могу обновить бонус!"); 
				header("Refresh: 5; url=mybonus.php");
				die(); 
			} 
			stdmsg($tracker_lang['success'], "Бонус обменян на возможность загрузки раздачи!"); 
			write_log("Бонус обменян на возможность загрузки раздачи пользователем " . $CURUSER["username"],"5DDB6E","bonus");
			@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
			header("Refresh: 5; url=mybonus.php");
			break; 

		case "free":
			if(get_user_class() >= UC_MODERATOR) {
				header("Refresh: 3; url=mybonus.php");
				stderr("Ошибка","Вам что бонусы некуда девать?");
			}
			if(!sql_query("UPDATE users SET bonus = bonus - $points, goldtorrent = goldtorrent+1 WHERE id = ".sqlesc($CURUSER["id"]))) {
				header("Refresh: 5; url=mybonus.php");
				stdmsg($tracker_lang['error'], "Не могу обновить бонус!", 'error');
				die();
			}
			@unlink("cache/functions/users_id".$CURUSER['id'].".txt");
			header("Refresh: 5; url=mybonus.php");
			stdmsg($tracker_lang['success'], "Бонус обменян возможность сделать одну раздачу золотой (Скаченное не учитывается, а раздача учитывается на 100%)");
			break;

		default:
			stdmsg($tracker_lang['error'], "Unknown bonus type!".(get_user_class() >= UC_SYSTEM ? $type : ""));

			//stdmsg($tracker_lang['error'], "Unknown bonus type!");
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
	ajax.setVar("bonus_id", bonus_type);
	ajax.method = 'POST';
	ajax.element = 'ajax';
	ajax.sendAJAX(varsString);
}
</script>
<div id="loading-layer" style="display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000">
	<div style="font-weight:bold" id="loading-layer-text"><?=$tracker_lang['ajax_loading'];?></div><br />
	<img src="pic/loading.gif" border="0" />
</div>
<div id="ajax">
<table class="embedded" width="550" border="1" cellspacing="0" cellpadding="5">
<?
	$my_points = $CURUSER["bonus"];
	$res = sql_query("SELECT * FROM bonus") or sqlerr(__FILE__,__LINE__);
	while ($arr = mysql_fetch_assoc($res)) {
		$id = $arr["id"];
		$bonus = $arr["name"];
		$points = $arr["points"];
		$descr = $arr["description"];
		$color = 'green';
		if ($CURUSER['bonus'] < $points)
			$color = 'red';
		$output .= "<tr><td><b>$bonus</b><br />$descr</td><td><center><font style=\"color: $color\">$points&nbsp;/&nbsp;$my_points</font></center></td><td><center><input type=\"radio\" name=\"bonus_id\" value=\"$id\"".($color == 'red' ? ' disabled' : '')." /></center></td></tr>\n";
	}
?>
	<tr><td class="colhead" colspan="3">Мой бонус (<?=$CURUSER["bonus"];?> бонусов в наличии / <?=$points_per_hour;?> бонусов в час)</td></tr>
	<tr><td class="colhead">Тип бонуса</td><td class="colhead">Очки</td><td class="colhead">Выбор</td></tr>
	<form action="mybonus.php" name="mybonus" method="post">
<?=$output;?>
		<tr><td colspan="3"><input type="submit" onClick="send(); return false;" value="Обменять" /></td></tr>
	</form>
</table>
</div>
<?
stdfoot();
}
?>