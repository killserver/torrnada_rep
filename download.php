<?
require_once("include/bittorrent.php");
dbconn();

function benc_str($s) {
	return strlen($s) . ":$s";
}


if(!$CURUSER) {

if(!isset($_GET['api']) || md5(base64_encode($_GET['id'])) != $_GET['api']) {
$ip = getenv(REMOTE_ADDR);
$rows = sql_query("SELECT COUNT(*) as count FROM tmp_users WHERE ip = \"".$ip."\"");
$row = mysql_fetch_array($rows);
if($row['count'] >= 3) {
stderr($tracker_lang['error'],"Извините... Вы скачали без регистрации 3 или более торрентов.<br />Скачивание без регистрации Вам снова будет доступно через 24 часа.<br />Если Вы хотите качать без ограничений, то пройдите простую регистрацию и качайте бесплатно сколько угодно!");
}

sql_query("INSERT INTO tmp_users SET ip = \"".$ip."\", time = \"".time()."\"");
}


$CURUSER = array(
"id" => 0,
"passkey" => "03010d6c4f6a1ae7ffcdf07c587abea3",
"parked" => "no",
);
}

#loggedinorreturn();
parked();

if (@ini_get('output_handler') == 'ob_gzhandler' AND @ob_get_length() !== false)
{	// if output_handler = ob_gzhandler, turn it off and remove the header sent by PHP
	@ob_end_clean();
	header('Content-Encoding:');
}

/*if (!preg_match(':^/(\d{1,10})/(.+)\.torrent$:', $_SERVER["PATH_INFO"], $matches))
	httperr();*/

$id = (int) $_GET["id"];
if (!is_numeric($id))
	stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

/*$id = 0 + $matches[1];
if (!$id)
	httperr();*/

$res = sql_query("SELECT name, filename, owner, banned, status FROM torrents WHERE id = ".sqlesc($id)) or sqlerr(__FILE__, __LINE__); //moderated, 
$row = mysql_fetch_assoc($res);
if (!$row)
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
/*if ($row['moderated'] == "no" && $row['owner'] != $CURUSER['id'] && get_user_class() < UC_MODERATOR)
	stderr($tracker_lang['error'], "Торрент не прошел модерацию");*/
if ($row['banned'] == 'yes' && $row['owner'] != $CURUSER['id'] && get_user_class() < UC_MODERATOR)
	stderr($tracker_lang['error'], 'Упс, а торрентик-то забанен!');
$s = "";
if($row["status"] == "2"){ 
	$s = "Торрент закрыт"; 
} elseif($row["status"] == "5"){ 
	$s = "В описании есть значительные отклонения от правил - загрузка недоступна."; 
} elseif($row["status"] == "6"){ 
	$s = "Торрент закрыт по причине повтора"; 
} elseif($row["status"] == "7"){ 
	$s = "Правообладатель закрыл этот торрент."; 
} elseif($row["status"] == "8"){ 
	$s = "Торрент был поглощён"; 
}
if(!empty($s)) {
	stderr($tracker_lang['error'], 'Упс, а у торрента ограничение: '.$s);
}

$name = $row['filename'];

$fn = "$torrent_dir/$id.torrent";

if (!$row || !is_file($fn) || !is_readable($fn))
	stderr($tracker_lang['error'], $tracker_lang['unable_to_read_torrent']);

sql_query("UPDATE torrents SET hits = hits + 1 WHERE id = ".sqlesc($id));
@unlink("cache/details/details_id".$id.".txt");
@unlink("cache/details/details_id".$id.".txt");
@unlink("cache/userdetails/userdetails_leeching_id".$CURUSER['id'].".txt");
@unlink("cache/userdetails/userdetails_seeding_id".$CURUSER['id'].".txt");

$name = str_replace(array(',', ';'), '', $name);

//require_once "include/benc.php";
require_once "include/BDecode.php";
require_once "include/BEncode.php";

if (strlen($CURUSER['passkey']) != 32) {
	$CURUSER['passkey'] = md5($CURUSER['username'].get_date_time().$CURUSER['passhash']);
	sql_query("UPDATE users SET passkey=".sqlesc($CURUSER[passkey])." WHERE id=".sqlesc($CURUSER[id]));
}

$dict = bdecode(file_get_contents($fn));

//$dict['announce'] = $announce_urls[0]."?passkey=$CURUSER[passkey]";//"$DEFAULTBASEURL/announce.php?passkey=$CURUSER[passkey]";
if (!empty($dict['announce-list'])) {
	$dict['announce-list'][][0] = $announce_urls[0]."?passkey=$CURUSER[passkey]"; // Just add one tracker for multitrackers, we are the last
} else {
	$dict['announce'] = $announce_urls[0]."?passkey=$CURUSER[passkey]";//"$DEFAULTBASEURL/announce.php?passkey=$CURUSER[passkey]";
}





$announce_urls_list = array();
if(!empty($CURUSER['annonce'])) {
$announce_urls_list[] = $CURUSER['annonce'];
}
$announce_urls_list[] = $DEFAULTBASEURL."/announce.php?passkey=".$CURUSER[passkey];
$announce_urls_list[] = "http://corbinaretracker.dyndns.org:80/announce.php";
$announce_urls_list[] = "udp://tracker.prq.to/announce";
$announce_urls_list[] = "udp://tracker.publicbt.com:80/announce";
$announce_urls_list[] = "udp://bt.rutor.org:2710";
$announce_urls_list[] = "http://tracker2.torrentino.com/announce";
$announce_urls_list[] = "udp://tracker.openbittorrent.com:80/announce";
$announce_urls_list[] = "http://bt.nnm-club.info:2710/003c0589569f75ccc933a385abd268bb/announce";



put_announce_urls($dict,$announce_urls_list);
//if($CURUSER['id']==8505) { var_dump(bdecode(BEncode($dict)));die(); }

if (!isset($_GET['view_online_ts'])) {

header ("Expires: Tue, 1 Jan 1980 00:00:00 GMT");
header ("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header ("Cache-Control: no-store, no-cache, must-revalidate");
header ("Cache-Control: post-check=0, pre-check=0", false);
header ("Pragma: no-cache");
header ("Accept-Ranges: bytes");
header ("Connection: close");
header ("Content-Transfer-Encoding: binary");
header ("Content-Disposition: attachment; filename=\"[torrnada.ru]_".$name."\"");
header ("Content-Type: application/x-bittorrent");
ob_implicit_flush(true);

print(BEncode($dict));
} else {

ob_implicit_flush(true);
require_once('include/functions_ts_client.php');

define('API_KEY', '5b1829dacb2b9df4988780a2f3f8bc1f'); // ваш код партнера, находится в разделе "Мой профиль"
define('ZONE_ID', '3272'); // идентификатор площадки, находится в разделе "Площадки", колонка "ID" 

$client = new TS_Client(API_KEY); /// создание клиента
$content_uid = $client->add_content(ZONE_ID, base64_encode(BEncode($dict)), 'tracker', 5580);

if (!empty($content_uid)) {
	header('Location: box.php?code='.$content_uid.'&id='.$id);
} else {
	stderr($tracker_lang['view_online_ts'], "Плеер ещё не готов");
}

}


?>