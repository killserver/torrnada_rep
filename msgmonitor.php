<?
require_once("include/bittorrent.php");
dbconn(false);

	header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);
	
$tname = convert_text(urldecode(decode_unicode_url($_POST["tname"])));
$userid = (int)$_POST["userid"];

if(!$tname || !$userid)
die("Доступ запрещен");	

$subject = "Торрент ".$tname." Добавлен";
$msg = "Ваша искомая раздача [b] $tname [/b] была добавлена! Пожалуйста воспользуйтесь поиском и вы ее найдете ";
sql_query("INSERT INTO messages(sender, receiver, added, subject, msg) VALUES (0, ".sqlesc($userid).", NOW(), ".sqlesc($subject).", ".sqlesc($msg).")");
sql_query("DELETE from monitor WHERE torrentname=".sqlesc($tname)."");
print ("Пользователю успешно отправлено сообщение о добавлении его искомого торрента");
?>