﻿<?
require_once("include/bittorrent.php");

$action = (isset($_GET["action"]) ? $_GET["action"] : "");

/// Предварительный просмотр ///
if (isset($_POST['area']) && $action == 'preview') {
//header('Content-type: text/html; charset=windows-1251');
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
if (!empty($_POST['area'])) {
	header('Content-type: text/html; charset=utf-8');
	$_POST['area'] = urldecode($_POST['area']);
	$charset = mb_detect_encoding($_POST['area'], array('UTF-8', 'Windows-1251', 'CP1251', 'KOI8-R', 'ISO-8859-5'));
	//$_POST['area'] = iconv('UTF-8','CP1251',$_POST['area']);
	$_POST['area'] = iconv($charset, 'cp1251', $_POST['area']);
	echo "<table cellpadding='2' width='100%' border='0' cellspacing='0'>";
	echo "<tr><td class=\"colhead\"> </td></tr>";
	echo "<tr><td class=\"b\">".iconv('cp1251', 'UTF-8', format_comment(htmlspecialchars_uni($_POST['area']), true))."</td></tr>";
	echo "<tr><td class=\"colhead\"> </td></tr>";
	echo "</table>";
}
die();
}
/// Предварительный просмотр ///

dbconn(false);

loggedinorreturn();
parked();

if ($action == "add")
{
  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
    $torrentid = intval($_POST["tid"]);
	  if (!is_valid_id($torrentid))
			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
		$res = sql_query("SELECT name FROM torrents WHERE id = ".$torrentid) or sqlerr(__FILE__,__LINE__);
		$arr = mysql_fetch_array($res);
		if (!$arr)
		  stderr($tracker_lang['error'], $tracker_lang['no_torrent_with_such_id']);
		$name = view_saves($arr[0]);
	  $text = trim($_POST["text"]);
	  if (!$text)
			stderr($tracker_lang['error'], $tracker_lang['comment_cant_be_empty']);

	  sql_query("INSERT INTO comments (user, torrent, added, text, ori_text, ip) VALUES (" .
	      $CURUSER["id"] . ", ".$torrentid.", '" . get_date_time() . "', " . sqlesc($text) .
	       "," . sqlesc($text) . "," . sqlesc(getip()) . ")");

	  $newid = mysql_insert_id();
	  sql_query('INSERT INTO comments_parsed (cid, text_hash, text_parsed) VALUES ('.implode(', ', array_map('sqlesc', array($newid, md5($text), format_comment($text)))).')') or sqlerr(__FILE__,__LINE__);
	  sql_query("UPDATE torrents SET comments = comments + 1 WHERE id = ".$torrentid);

	/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ///////////////// 
      $subject = sqlesc ("Новый комментарий");
      $msg = sqlesc("Для торрента [url=details.php?id=".$torrentid."&viewcomm=".$newid."#comm".$newid."]".$name."[/url] добавился новый комментарий.");
      sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, userid, NOW(), ".$msg.", 0, ".$subject." FROM checkcomm WHERE checkid = ".$torrentid." AND torrent = 1 AND userid != ".$CURUSER['id']) or sqlerr(__FILE__, __LINE__);

	if(isset($_POST['quote'])) {
		$subject = sqlesc ("Новый комментарий");
		$msg = sqlesc("Вам ответили в торренте [url=details.php?id=".$torrentid."&viewcomm=".$newid."#comm".$newid."]".$name."[/url]");
		sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, id, NOW(), ".$msg.", 0, ".$subject." FROM users WHERE id = ".intval($_POST['quote'])) or sqlerr(__FILE__, __LINE__);
	}
    /////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////
	  @unlink("cache/details/details_comments_id".$torrentid."_limitLIMIT 0,10.txt");
	  @unlink("cache/functions/users_id".$CURUSER['id'].".txt");
	  header("Refresh: 0; url=details.php?id=".$torrentid."&viewcomm=".$newid."#comm".$newid);
	  die();
	}

  $torrentid = intval($_GET["tid"]);
  if (!is_valid_id($torrentid))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	$res = sql_query("SELECT name FROM torrents WHERE id = ".$torrentid) or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr)
	  stderr($tracker_lang['error'], $tracker_lang['no_torrent_with_such_id']);

	stdhead("Добление комментария к \"" . $arr["name"] . "\"");

	print("<p><form name=\"comment\" method=\"post\" action=\"comment.php?action=add\">\n");
	print("<input type=\"hidden\" name=\"tid\" value=\"".$torrentid."\"/>\n");
?>
	<table class=main border=0 cellspacing=0 cellpadding=3>
	<tr>
	<td class="colhead">
<?
	print("".$tracker_lang['add_comment']." к \"" . htmlspecialchars_uni($arr["name"]) . "\"");
?>
	</td>
	</tr>
	<tr>
	<td>
<?
	textbbcode("comment","text","");
?>
	</td></tr></table>
<?
	//print("<textarea name=\"text\" rows=\"10\" cols=\"60\"></textarea></p>\n");
	print("<p><input type=\"submit\" value=\"Добавить\" /></p></form>\n");

	$res = sql_query("SELECT comments.id, text, comments.ip, comments.added, username, title, class, users.id as user, users.avatar, users.donor, users.enabled, users.warned, users.parked FROM comments LEFT JOIN users ON comments.user = users.id WHERE torrent = ".$torrentid." ORDER BY comments.id DESC LIMIT 5");

	$allrows = array();
	while ($row = mysql_fetch_array($res))
	  $allrows[] = $row;

	if (count($allrows)) {
	  print("<h2>Последние комментарии, в обратном порядке</h2>\n");
	  commenttable($allrows);
	}

  stdfoot();
	die();
}
elseif ($action == "quote")
{
  $commentid = intval($_GET["cid"]);
  if (!is_valid_id($commentid))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

  $res = sql_query("SELECT c.*, t.name, t.id AS tid, u.id as uid, u.username FROM comments AS c LEFT JOIN torrents AS t ON c.torrent = t.id JOIN users AS u ON c.user = u.id WHERE c.id=".$commentid) or sqlerr(__FILE__,__LINE__);
  $arr = mysql_fetch_array($res);
  if (!$arr)
  	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

 	stdhead("Добавления комментария к \"" . $arr["name"] . "\"");

	$text = "[quote=".$arr['username']."]" . $arr["text"] . "[/quote]\n";

	print("<form method=\"post\" name=\"comment\" action=\"comment.php?action=add\">\n");
	print("<input type=\"hidden\" name=\"quote\" value=\"".$arr['uid']."\" />\n");
	print("<input type=\"hidden\" name=\"tid\" value=\"".$arr['tid']."\" />\n");
?>

	<table class=main border=0 cellspacing=0 cellpadding=3>
	<tr>
	<td class="colhead">
<?
	print("Добавления комментария к \"" . htmlspecialchars_uni($arr["name"]) . "\"");
?>
	</td>
	</tr>
	<tr>
	<td>
<?
	textbbcode("comment","text",htmlspecialchars_uni($text));
?>
	</td></tr></table>

<?

	print("<p><input type=\"submit\" value=\"Добавить\" /></p></form>\n");

	stdfoot();

}
elseif ($action == "edit")
{
  $commentid = intval($_GET["cid"]);
  if (!is_valid_id($commentid))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

  $res = sql_query("SELECT c.*, t.name, t.id AS tid FROM comments AS c LEFT JOIN torrents AS t ON c.torrent = t.id WHERE c.id=".$commentid) or sqlerr(__FILE__,__LINE__);
  $arr = mysql_fetch_array($res);
  if (!$arr)
  	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	if ($arr["user"] != $CURUSER["id"] && get_user_class() < UC_MODERATOR)
		stderr($tracker_lang['error'], $tracker_lang['access_denied']);

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
	  $text = $_POST["text"];
    $returnto = $_POST["returnto"];

	  if ($text == "")
	  	stderr($tracker_lang['error'], $tracker_lang['comment_cant_be_empty']);

	  $orig_text = $text;
	  $text = sqlesc($text);

	  $editedat = sqlesc(get_date_time());

	  sql_query("UPDATE comments SET text=".$text.", editedat=".$editedat.", editedby=".$CURUSER['id']." WHERE id=".$commentid) or sqlerr(__FILE__, __LINE__);
	  sql_query('REPLACE INTO comments_parsed (cid, text_hash, text_parsed) VALUES ('.implode(', ', array_map('sqlesc', array($commentid, md5($orig_text), format_comment($orig_text)))).')') or sqlerr(__FILE__,__LINE__);

		if($arr['konkurs']>0) {
			$returnto = "konkursdetails.php?id=".$arr['konkurs']; 
		          header("Location: ".$returnto); 
		        die(); 
		} else {
				if($returnto) {
			  	  header("Location: ".$returnto);
				} else {
				  header("Location: ".$DEFAULTBASEURL."/");      // change later ----------------------
				}
				die();
		}
        }

 	stdhead("Редактирование комментария к \"" . $arr["name"] . "\"");

	print("<form method=\"post\" name=\"comment\" action=\"comment.php?action=edit&amp;cid=".$commentid."\">\n");
	print("<input type=\"hidden\" name=\"returnto\" value=\"details.php?id=".$arr["tid"]."&amp;viewcomm=".$commentid."#comm".$commentid."\" />\n");
	print("<input type=\"hidden\" name=\"cid\" value=\"".$commentid."\" />\n");
?>

	<table class=main border=0 cellspacing=0 cellpadding=3>
	<tr>
	<td class="colhead">
<?
	print("Редактирование комментария к \"" . htmlspecialchars_uni($arr["name"]) . "\"");
?>
	</td>
	</tr>
	<tr>
	<td>
<?
	textbbcode("comment","text",htmlspecialchars_uni($arr["text"]));
?>
	</td></tr></table>

<?

	print("<p><input type=\"submit\" value=\"Отредактировать\" /></p></form>\n");

	stdfoot();
	die();
}
/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ///////////////// 
elseif ($action == "check" || $action == "checkoff")
{
        $tid = intval($_GET["tid"]);
        if (!is_valid_id($tid))
                stderr($tracker_lang['error'], "Неверный идентификатор ".$tid.".");
        $docheck = mysql_fetch_array(sql_query("SELECT COUNT(*) FROM checkcomm WHERE checkid = " . $tid . " AND userid = " . $CURUSER["id"] . " AND torrent = 1"));
        if ($docheck[0] > 0 && $action=="check")
                stderr($tracker_lang['error'], "<p>Вы уже подписаны на этот торрент.</p><a href=details.php?id=".$tid."#startcomments>Назад</a>");
        if ($action == "check") {
                sql_query("INSERT INTO checkcomm (checkid, userid, torrent) VALUES (".$tid.", ".$CURUSER['id'].", 1)") or sqlerr(__FILE__,__LINE__);
                stderr($tracker_lang['success'], "<p>Теперь вы следите за комментариями к этому торренту.</p><a href=details.php?id=".$tid."#startcomments>Назад</a>");
        } else {
                sql_query("DELETE FROM checkcomm WHERE checkid = ".$tid." AND userid = ".$CURUSER['id']." AND torrent = 1") or sqlerr(__FILE__,__LINE__);
                stderr($tracker_lang['success'], "<p>Теперь вы не следите за комментариями к этому торренту.</p><a href=details.php?id=".$tid."#startcomments>Назад</a>");
        }
	@unlink("cache/details/details_comments_id".$tid."_limitLIMIT 0,10.txt");
	@unlink("cache/functions/users_id".$CURUSER['id'].".txt");

}
/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////
elseif ($action == "delete")
{
	if (get_user_class() < UC_MODERATOR)
		stderr($tracker_lang['error'], $tracker_lang['access_denied']);

  $commentid = intval($_GET["cid"]);

  if (!is_valid_id($commentid))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

  $sure = $_GET["sure"];

  if (!$sure)
  {
		stderr($tracker_lang['delete']." ".$tracker_lang['comment'], sprintf($tracker_lang['you_want_to_delete_x_click_here'],$tracker_lang['comment'],"?action=delete&cid=".$commentid."&sure=1"));
  }


	$res = sql_query("SELECT torrent FROM comments WHERE id=".$commentid)  or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if ($arr)
		$torrentid = $arr["torrent"];

	sql_query("DELETE FROM comments WHERE id=".$commentid) or sqlerr(__FILE__,__LINE__);
	if ($torrentid && mysql_affected_rows() > 0)
		sql_query("UPDATE torrents SET comments = comments - 1 WHERE id = ".$torrentid);

	list($commentid) = mysql_fetch_row(sql_query("SELECT id FROM comments WHERE torrent = ".$torrentid" ORDER BY added DESC LIMIT 1"));
	$returnto = "details.php?id=".$torrentid."&viewcomm=".$commentid."#comm".$commentid;
	@unlink("cache/details/details_comments_id".$torrentid."_limitLIMIT 0,10.txt");

	if ($returnto)
	  header("Location: ".$returnto);
	else
	  header("Location: ".$DEFAULTBASEURL."/");      // change later ----------------------
	die();
}
elseif ($action == "vieworiginal")
{
	if (get_user_class() < UC_MODERATOR)
		stderr($tracker_lang['error'], $tracker_lang['access_denied']);

  $commentid = intval($_GET["cid"]);

  if (!is_valid_id($commentid))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

  $res = sql_query("SELECT c.*, t.name, t.id AS tid FROM comments AS c LEFT JOIN torrents AS t ON c.torrent = t.id WHERE c.id=".$commentid) or sqlerr(__FILE__,__LINE__);
  $arr = mysql_fetch_array($res);
  if (!$arr)
  	stderr($tracker_lang['error'], "Неверный идентификатор $commentid.");

  stdhead("Просмотр оригинала");
  print("<h1>Оригинальное содержание комментария №".$commentid."</h1><p>\n");
	print("<table width=500 border=1 cellspacing=0 cellpadding=5>");
  print("<tr><td class=comment>\n");
	//echo htmlspecialchars($arr["ori_text"]);
	echo format_comment($arr["ori_text"]);
  print("</td></tr></table>\n");

  $returnto = "details.php?id=".$arr["tid"]."&amp;viewcomm=".$commentid."#comm".$commentid;
//	$returnto = "details.php?id=$torrentid&amp;viewcomm=$commentid#$commentid";
	if ($returnto)
 		print("<p><font size=small><a href=$returnto>Назад</a></font></p>\n");

	stdfoot();
	die();
}
else
	stderr($tracker_lang['error'], "Unknown action");

die();
?>