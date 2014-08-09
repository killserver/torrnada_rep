<?php
require_once("include/bittorrent.php");
dbconn();

$type_down = 1; ///просмотр и скачка - 1; сразу скачка - 2

if(!$CURUSER) {
	$ip = getenv("REMOTE_ADDR");
	$rows = sql_query("SELECT COUNT(*) as count FROM tmp_users WHERE ip = \"".$ip."\"");
	$row = mysql_fetch_array($rows);
	if($row['count'] >= 1) {
		stderr($tracker_lang['error'],"Извините... Вы скачали без регистрации 1 или более торрентов.<br />Скачивание без регистрации Вам снова будет доступно через 24 часа.<br /> Если Вы хотите качать без ограничений, то пройдите простую регистрацию и качайте бесплатно сколько угодно!");
	die();
	}
}

$id = intval($_GET['id']);
$res = sql_query("SELECT name, filename, owner, banned, moderated FROM torrents WHERE id = ".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$row = mysql_fetch_assoc($res);
if(!$row) {
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
}

if(!$row || !is_file($torrent_dir."/".$id.".torrent") || !is_readable($torrent_dir."/".$id.".torrent")) {
	stderr($tracker_lang['error'], $tracker_lang['unable_to_read_torrent']);
}

if($row['banned'] == 'yes' && $row['owner'] != $CURUSER['id'] && get_user_class() < UC_MODERATOR) {
	stderr($tracker_lang['error'], 'Упс, а торрентик-то забанен!');
}

/*if ($row['moderated'] == "no" && $row['owner'] != $CURUSER['id'] && get_user_class() < UC_MODERATOR)
	stderr($tracker_lang['error'], "Торрент не прошел модерацию");*/
$s = "";
if($row["status"] == "1" && get_user_class() < UC_MODERATOR){ 
	$s = "Торрент проверяется";
} elseif($row["status"] == "2"){ 
	$s = "Торрент закрыт";
} elseif($row["status"] == "5" && get_user_class() < UC_MODERATOR){ 
	$s = "В описании есть значительные отклонения от правил - загрузка недоступна."; 
} elseif($row["status"] == "6"){ 
	$s = "Торрент закрыт по причине повтора"; 
} elseif($row["status"] == "7"){ 
	$s = "Правообладатель закрыл этот торрент."; 
} elseif($row["status"] == "8"){ 
	$s = "Торрент был поглощён"; 
}
if(!empty($s) && $row['owner'] != $CURUSER['id'] && get_user_class() < UC_MODERATOR) {
	stderr($tracker_lang['error'], "Торрент не прошел модерацию");
}




$name = (strpos($row['filename'], "torrnada.ru")===false ? "[torrnada.ru]_" : "").$row['filename'];
if($type_down == 1) {
stdhead();
echo <<<HTML
Для скачки нажмите: <a href="download.php?id={$id}&amp;name={$name}">ссылку</a>
HTML;
stdfoot();
} else {
header("Location: download.php?id=".$id."&name=".$name);
}

?>