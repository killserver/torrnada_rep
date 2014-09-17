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

require "include/bittorrent.php";
dbconn(false);
loggedinorreturn();

$action = $_GET['action'];
$userid = $CURUSER['id'];

if (!is_valid_id($userid))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

if ($userid != $CURUSER["id"])
	stderr($tracker_lang['error'], $tracker_lang['access_denied']);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['act']))
{
    $id = (int)$_GET['id'];
    $user = (int)$_GET['user'];
    if ($_GET['act'] == "accept")
    {
        sql_query("UPDATE friends SET status = 'yes' WHERE id = $id") or sqlerr(__FILE__, __LINE__);
        sql_query("INSERT IGNORE INTO friends (userid, friendid, status) VALUES (" . sqlesc($CURUSER['id']) . ", $user, 'yes')") or sqlerr(__FILE__, __LINE__);
        $dt = sqlesc(get_date_time());
        $msg = sqlesc("Пользователь [url=userdetails.php?id=" . $CURUSER['id'] . "]" . $CURUSER['username'] . "[/url] согласился на дружбу.");
        $subj = sqlesc("Ответ на предложение дружбы.");
        sql_query("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0, $user, $dt, $msg, $subj)") or sqlerr(__FILE__, __LINE__);
        header("Refresh: 2; url=" . $DEFAULTBASEURL . "/friends.php");
        stderr("Успешно", "Пользователь добавлен в список друзей");
    }
    elseif ($_GET['act'] == "surrender")
    {
        sql_query("UPDATE friends SET status = 'no' WHERE id = $id") or sqlerr(__FILE__, __LINE__);
        sql_query("DELETE FROM friends WHERE id = $id") or sqlerr(__FILE__, __LINE__);
        $dt = sqlesc(get_date_time());
        $msg = sqlesc("Пользователь [url=userdetails.php?id=" . $CURUSER['id'] . "]" . $CURUSER['username'] . "[/url] отказался от дружбы.");
        $subj = sqlesc("Ответ на предложение дружбы.");
        sql_query("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES (0, $user, $dt, $msg, $subj)") or sqlerr(__FILE__, __LINE__);
        header("Refresh: 2; url=" . $DEFAULTBASEURL . "/friends.php");
        stderr("Успешно", "Пользователю отказано в дружбе");
    }
    else
        stderr("Ошибка", "Нет доступа");
}

// main body  -----------------------------------------------------------------

stdhead("Мои списки пользователей");

/*print("<p><table class=main border=0 cellspacing=0 cellpadding=0>".
"<tr><td class=embedded><h1 style='margin:0px'> Personal lists for $user[username]</h1>$donor$warned$country</td></tr></table></p>\n");*/

print("<table class=main width=100% border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>");

print("<table width=100% border=1 cellspacing=0 cellpadding=5>");
print("<tr><td class=\"colhead\"><a name=\"friends\">".$tracker_lang['friends_list']."</a></tr></td>");
print("<tr><td>");
$i = 0;

$res = sql_query("SELECT f.friendid as id, u.username AS name, u.class, u.avatar, u.title, u.donor, u.warned, u.enabled, u.last_access FROM friends AS f LEFT JOIN users as u ON f.friendid = u.id WHERE f.userid=$userid AND f.status = 'yes' ORDER BY name") or sqlerr(__FILE__, __LINE__);
if(mysql_num_rows($res) == 0)
	$friends = "<em>".$tracker_lang['no_friends'].".</em>";
else
	while ($friend = mysql_fetch_array($res))
	{
    $title = $friend["title"];
		if (!$title)
	    $title = get_user_class_name($friend["class"]);
    $body1 = "<a href=userdetails.php?id=" . $friend['id'] . "><b>" . get_user_class_color($friend["class"], $friend['name']) . "</b></a>" .
    	get_user_icons($friend) . " ($title)<br /><br />" . $tracker_lang['last_seen'] . $friend['last_access'] .
    	"<br />(" . get_elapsed_time(sql_timestamp_to_unix_timestamp($friend[last_access])) . " ".$tracker_lang['ago'].")";
		$body2 = "<br /><a href=friends.php?id=$userid&action=delete&type=friend&targetid=" . $friend['id'] . ">".$tracker_lang['delete']."</a>" .
			"<br /><br /><a href=message.php?action=sendmessage&amp;receiver=" . $friend['id'] . ">".$tracker_lang['pm']."</a>";
    $avatar = ($CURUSER["avatars"] == "yes" ? htmlspecialchars($friend["avatar"]) : "");
		if (!$avatar)
			$avatar = "pic/default_avatar.gif";
    if ($i % 2 == 0)
    	print("<table width=100% style='padding: 0px'><tr><td class=bottom style='padding: 5px' width=50% align=center>");
    else
    	print("<td class=bottom style='padding: 5px' width=50% align=center>");
    print("<table class=main width=100% height=100px>");
    print("<tr valign=top><td width=100 align=center style='padding: 0px'>" .
			($avatar ? "<div style='width:100px;height:100px;overflow: hidden'><img width=\"100\" src=\"$avatar\" /></div>" : ""). "</td><td>\n");
    print("<table class=main>");
    print("<tr><td class=embedded style='padding: 5px' width=80%>$body1</td>\n");
    print("<td class=embedded style='padding: 5px' width=20%>$body2</td></tr>\n");
    print("</table>");
		print("</td></tr>");
		print("</td></tr></table>\n");
    if ($i % 2 == 1)
			print("</td></tr></table>\n");
		else
			print("</td>\n");
		$i++;
	}
if ($i % 2 == 1)
	print("<td class=bottom width=50%>&nbsp;</td></tr></table>\n");
print($friends);
print("</td></tr></table>\n");

$res = sql_query("SELECT b.blockid as id, u.username AS name, u.class, u.donor, u.warned, u.enabled, u.last_access FROM blocks AS b LEFT JOIN users as u ON b.blockid = u.id WHERE userid=$userid ORDER BY name") or sqlerr(__FILE__, __LINE__);
if(mysql_num_rows($res) == 0)
	$blocks = "<em>".$tracker_lang['no_blocked'].".</em>";
else
{
	$i = 0;
	$blocks = "<table width=100% cellspacing=0 cellpadding=0>";
	while ($block = mysql_fetch_array($res))
	{
		if ($i % 6 == 0)
			$blocks .= "<tr>";
    	$blocks .= "<td style='border: none; padding: 4px; spacing: 0px;'>[<font class=small><a href=friends.php?id=$userid&action=delete&type=block&targetid=" .
				$block['id'] . ">D</a></font>] <a href=userdetails.php?id=" . $block['id'] . "><b>" . get_user_class_color($block['class'], $block['name']) . "</b></a>" .
				get_user_icons($block) . "</td>";
		if ($i % 6 == 5)
			$blocks .= "</tr>";
		$i++;
	}
	print("</table>\n");
}
print("<br />");
print("<table class=main width=100% border=0 cellspacing=0 cellpadding=5>");
print("<tr><td class=\"colhead\"><a name=\"blocks\">".$tracker_lang['blocked_list']."</a></td></tr>");
print("<tr><td style='padding: 5px;background-color: #ECE9D8'>");
print("$blocks\n");
print("</td></tr></table>\n");
print("</td></tr></table>\n");
print("<p><a href=users.php><b>Найти пользователя/Список пользователей</b></a></p>");
stdfoot();
?>