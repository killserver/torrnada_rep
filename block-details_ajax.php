<?
require_once("include/bittorrent.php");

dbconn(false, true);	



require_once("include/benc.php");
require_once('include/scraper/httptscraper.php');
require_once('include/scraper/udptscraper.php');

$timeout = 2;
$udp = new udptscraper($timeout);
$http = new httptscraper($timeout);



header("Content-Type: text/html; charset=" .$tracker_lang['language_charset']);
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
$act_end = array("multion", "multioff","simular"); /// исключения для этих действий

$id = (int) $_POST["id"];
$action = (string) $_POST["action"];


/// показ похожих файлов
if ($action == "simular"){


$sql = sql_query("SELECT name FROM torrents WHERE id = ".sqlesc($id)." LIMIT 5") or sqlerr(__FILE__,__LINE__);
$row = mysql_fetch_array($sql);

$search = view_saves(htmlspecialchars_uni($row["name"]));

$search = preg_replace("/\(((\s|.)+?)\)/is", "", preg_replace("/\[((\s|.)+?)\]/is", "", $search));

$sear = array("'","\"","%","$","/","`","`","<",">");
$search = str_replace($sear, " ", $search);

$list = explode(" ", $search);

$listrow = array();
$listview = array();

foreach ($list AS $lis){

if (strlen($lis)>=3) $listrow[] = "+".$lis;

}

$listrow = array_unique($listrow); /// удаляем дубликаты

if (strlen($search) >= 4){ /// нельзя искать строку меньше 3 символов (см конфиг mysql)

$sql = sql_query("SELECT name, id, size FROM torrents WHERE MATCH (torrents.name) AGAINST ('".trim(implode(" ", $listrow))."' IN BOOLEAN MODE) ORDER BY added DESC LIMIT 15", $cache = array("type" => "disk", "time" => 3*86400)) or sqlerr(__FILE__, __LINE__);

}
else
die("Поиск неудачен, слишком короткая строка для поиска.");

//print_r($listrow);

$num_p = 0;
$nut = 0;

$pogre = array();

while ($t = mysql_fetch_assoc($sql)){

$name1 = $search;
$name2 = preg_replace("/\(((\s|.)+?)\)/is", "", preg_replace("/\[((\s|.)+?)\]/is", "", $t["name"]));

$proc = @similar_text($name1, $name2);

if ($id <> $t["id"] && $proc >= $Torrent_Config["procents"]) {
echo "<li><a href=\"details.php?id=".$t['id']."\">".view_saves(htmlspecialchars_uni($t['name']))."</a> ".($CURUSER ? "<a title=\"Нажмите, чтобы скачать файл\" href=\"download.php?id=".$t['id']."\">[".mksize($t["size"])."]</a>":"[".mksize($t["size"])."]")."</li>";

$pogre[] = $proc;
$num_p = 1;
++$nut;

}


}

if (empty($nut)){

if ($proc > $Torrent_Config["procents"])
die("<i>Данных нет - вывод отключен. </i>");
else
die("<i>Точность данных ".$proc."% против минимума ".$Torrent_Config["procents"]."% - вывод отключен. </i>");

} 

}
/// показ сидов на мульти трекере
if ($action == "multion"){

$sql = sql_query("SELECT seeders, leechers, checkpeers FROM torrents WHERE id = ".sqlesc($id)) or sqlerr(__FILE__,__LINE__);
$row = mysql_fetch_array($sql);



//// пересчет внут пиров скрытая версия
//// перекидываем из cleanup код под каждый торрент
$dt_multi = get_date_time(gmtime() - 600); // каждые 10 минут

if ($row["checkpeers"] < $dt_multi) {

$torrents = array();
$res_cle = sql_query("SELECT seeder, COUNT(*) AS c FROM peers WHERE torrent = ".sqlesc($id)." GROUP BY torrent, seeder") or sqlerr(__FILE__,__LINE__);
while ($row_cle = mysql_fetch_assoc($res_cle)) {

if ($row_cle["seeder"] == "yes")
$key = "seeders";
else
$key = "leechers";

$torrents[$id][$key] = $row_cle["c"];
}


$res_cle = sql_query("SELECT COUNT(*) AS c FROM comments WHERE torrent = ".sqlesc($id)." GROUP BY torrent") or sqlerr(__FILE__,__LINE__);
while ($row_cle = mysql_fetch_assoc($res_cle))
$torrents[$id]["comments"] = $row_cle["c"];


$fields = explode(":", "comments:leechers:seeders");
$res_cle = sql_query("SELECT seeders, leechers, comments FROM torrents WHERE id = ".sqlesc($id)) or sqlerr(__FILE__,__LINE__);

while ($row_cle = mysql_fetch_assoc($res_cle)) {

$torr = (isset($torrents[$id]) ? $torrents[$id]:""); 

foreach ($fields as $field) {
if (!isset($torr[$field]))
$torr[$field] = 0;
}

$update = array();
foreach ($fields as $field) {

$update[] = $field." = " . $torr[$field];
/// вносим сразу изменения в просмотр
if ($field == "leechers")
$row["leechers"] = $torr["leechers"];

elseif ($field = "seeders")
$row["seeders"] = $torr["seeders"];
/// вносим сразу изменения в просмотр
}

if (count($update)){
$update[] = "checkpeers = ".sqlesc(get_date_time());
sql_query("UPDATE torrents SET ".implode(", ", $update)." WHERE id = ".sqlesc($id)) or sqlerr(__FILE__,__LINE__);
@unlink('cache/details/details_id'.$id.'.txt');
@unlink("cache/details/details_thanked_id".$id.".txt");
@unlink("cache/details/details_freeed_id".$id.".txt");
@unlink("cache/details/thanks_comm_id".$id.".txt");
@unlink("cache/details/delthanks_comm_id".$id.".txt");
@unlink("cache/details/details_relizgroup_id".$id.".txt");
@unlink("cache/details/download_id".$id.".txt");
}

}
//// перекидываем из cleanup код под каждый торрент
sql_query("UPDATE torrents SET seeders='0', leechers='0' WHERE checkpeers = '0000-00-00 00:00:00'") or sqlerr(__FILE__,__LINE__);
}
//// проверка пиров скрытая версия

echo "<b><font color=\"".linkcolor($row["seeders"])."\">".($row["seeders"])."</font></b> ".$tracker_lang['seeders_l'].", <b><font color=\"".linkcolor($row["leechers"])."\">".($row["leechers"])."</font></b> ".$tracker_lang['leechers_l']." = <b>" . ($row["seeders"] + $row["leechers"]) . "</b> ".$tracker_lang['peers_l'];


}
/// показ сидов на данном трекере
elseif ($action == "multi_seeding"){

/// мультитрекерная раздача
$sres = sql_query("SELECT info_hash, f_seeders, f_leechers, multi_time, f_trackers, multitracker FROM torrents WHERE id = ".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$row = mysql_fetch_array($sres);

if ($row["multitracker"] == "no")
die("Отключен мультитрекер");


$list = explode("\n", $row["f_trackers"]);

echo "<table width=100% class=main border=1 cellspacing=0 cellpadding=5>\n";

echo "<tr><td class=a align=center colspan=\"5\">Мультитрекер и его внешние аннонсы.</td></tr>";

echo "<tr>
<td class=colhead width=40% align=center>Домен</td>
<td class=colhead width=10% align=center>Раздают</td>
<td class=colhead width=10% align=center>Качают</td>
<td class=colhead width=10% align=center>Завершено</td>
<td class=colhead align=center>Статус</td>
</tr>";

$s4etik = 0;

foreach ($list AS $lisar){

if ($s4etik % 2 == 0) $clas_tdi = "class=\"a\""; else $clas_tdi = "class=\"b\"";

$ex_lode = explode(":", $lisar);

$li_text = array(
"off" => "мультитрекер отключен системно",
"false" => "ответ пуст, торрент не существует",
"timeout" => "время отклика прошло (timeout)"
);

$status = '';
if (in_array($ex_lode[1], array("off","timeout","false")))
$status = $li_text[$ex_lode[1]];

echo "<tr>
<td $clas_tdi align=left>".$ex_lode[0]."</td>
<td $clas_tdi align=center>".(!empty($status) ? "-":"<b><font color=\"".linkcolor($ex_lode[2])."\">".$ex_lode[2]."</font></b>")."</td>
<td $clas_tdi align=center>".(!empty($status) ? "-":"<b><font color=\"".linkcolor($ex_lode[1])."\">".$ex_lode[1]."</font></b>")."</td>
<td $clas_tdi align=center>".(!empty($status) ? "-":"<b><font color=\"".linkcolor($ex_lode[3])."\">".$ex_lode[3]."</font></b>")."</td>
<td $clas_tdi align=center>".(!empty($status) ? $status:"успешно, работает")."</td>
</tr>\n"; 
++$s4etik;
}

echo "<tr><td class=a align=center colspan=\"5\">Последняя проверка: ".$row["multi_time"]." (".get_elapsed_time(sql_timestamp_to_unix_timestamp($row["multi_time"])) . " назад)</td></tr>";




echo "<tr><td class=b align=center colspan=\"5\"><a style=\"cursor: pointer;\" onclick=\"getmt('" .$id. "');\">Обновить список пиров и сидов / Закрыть список</a><div id=\"mt_" .$id. "\"></div></td></tr>";


echo "</table>\n";

}
/// показ похожих файлов
/// показ сидов на этом трекере
if ($action == "multioff"){

/// мультитрекерная раздача
$sres = sql_query("SELECT info_hash, multi_infohash, f_seeders, f_leechers, multi_time, f_trackers, multitracker FROM torrents WHERE id = ".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$row = mysql_fetch_array($sres);

$dt_multi = get_date_time(gmtime() - $Torrent_Config["multihours"]*3600); // умножаем количество часов на секунды

if ($row["multi_time"] < $dt_multi && $row["multitracker"] == "yes" && (!empty($Torrent_Config["multihours"]) || $row["multi_time"] == "0000-00-00 00:00:00")) {

$sql_ann = $tracker_cache = array();
$f_leechers = 0;
$f_seeders = 0;


$sres = sql_query("SELECT url FROM torrents_scrape WHERE tid = ".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
while($scrape = mysql_fetch_array($sres)) {
	$sql_ann[] = $scrape['url'];
}


$rows = $row['info_hash'];

$announce_urls[] = $DEFAULTBASEURL."/announce.php?passkey=".$CURUSER['passkey'];
$announce_urls[] = "http://corbinaretracker.dyndns.org:80/announce.php";
$announce_urls[] = "udp://tracker.prq.to/announce";
$announce_urls[] = "udp://tracker.publicbt.com:80/announce";
$announce_urls[] = "udp://bt.rutor.org:2710";
$announce_urls[] = "http://tracker2.torrentino.com/announce";
$announce_urls[] = "udp://tracker.openbittorrent.com:80/announce";
//$announce_urls[] = "http://bt.nnm-club.info:2710/003c0589569f75ccc933a385abd268bb/announce";

$nannounce_urls = array_merge($announce_urls, $sql_ann);
$nannounce_urls = array_unique($nannounce_urls);


foreach($nannounce_urls as $announce) {
	$parse = parse_url($announce, PHP_URL_HOST);
	if(substr($announce, 0, 6) == 'udp://') {
		try {
			$data = $udp->scrape($announce, $rows);
			$data = $data[$rows];
			$f_seeders += $data['seeders'];
			$f_leechers += $data['leechers'];
			sql_query('UPDATE torrents_scrape SET state = "ok", error = "", seeders = '.sqlesc($data['seeders']).', leechers = '.sqlesc($data['leechers']).' WHERE tid = '.sqlesc($tid).' AND url = '.sqlesc($announce)) or sqlerr(__FILE__,__LINE__);
			$tracker_cache[] = $parse.':'.($data['leechers'] ? $data['leechers'] : 0).':'.($data['seeders'] ? $data['seeders'] : 0).':0';
		} catch(ScraperException $e){
			sql_query('UPDATE torrents_scrape SET state = "error", error = '.sqlesc($e->getMessage()).', seeders = 0, leechers = 0 WHERE tid = '.$id.' AND url = '.sqlesc($announce)) or sqlerr(__FILE__,__LINE__);
			$tracker_cache[] = $parse.':'.$e->getMessage();
		}
	} else {
		try {
			$data = $http->scrape($announce, $rows);
			$data = $data[$rows];
			$f_seeders += $data['seeders'];
			$f_leechers += $data['leechers'];
			sql_query('UPDATE torrents_scrape SET state = "ok", error = "", seeders = '.sqlesc($data['seeders']).', leechers = '.sqlesc($data['leechers']).' WHERE tid = '.sqlesc($tid).' AND url = '.sqlesc($announce)) or sqlerr(__FILE__,__LINE__);
			$tracker_cache[] = $parse.':'.($data['leechers'] ? $data['leechers'] : 0).':'.($data['seeders'] ? $data['seeders'] : 0).':0';
		} catch(ScraperException $e){
			sql_query('UPDATE torrents_scrape SET state = "error", error = '.sqlesc($e->getMessage()).', seeders = 0, leechers = 0 WHERE tid = '.$id.' AND url = '.sqlesc($announce)) or sqlerr(__FILE__,__LINE__);
			$tracker_cache[] = $parse.':'.$e->getMessage();
		}
	}
}


$fpeers = $f_seeders + $f_leechers;
$tracker_cache = implode("\n",$tracker_cache);
$updatef = array();
$updatef[] = "f_trackers = ".sqlesc($tracker_cache);
$updatef[] = "f_leechers = ".sqlesc($f_leechers);
$updatef[] = "f_seeders = ".sqlesc($f_seeders);
$updatef[] = "multi_time = ".sqlesc(get_date_time());
$updatef[] = "visible = ".sqlesc(!empty($fpeers) ? 'yes':'no');
sql_query("UPDATE torrents SET " . implode(",", $updatef) . " WHERE id = ".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
@unlink('cache/details/details_id'.$id.'.txt');
@unlink("cache/details/details_thanked_id".$id.".txt");
@unlink("cache/details/details_freeed_id".$id.".txt");
@unlink("cache/details/thanks_comm_id".$id.".txt");
@unlink("cache/details/delthanks_comm_id".$id.".txt");
@unlink("cache/details/details_relizgroup_id".$id.".txt");
@unlink("cache/details/download_id".$id.".txt");

$row["f_seeders"] = $f_seeders;
$row["f_leechers"] = $f_leechers;
$row["multi_time"] = get_date_time();
$row["f_trackers"] = $tracker_cache;
}


echo ($row["multitracker"]=="yes" ? "<b><font color=\"".linkcolor($row["f_seeders"])."\">".$row["f_seeders"]."</font></b> ".$tracker_lang['seeders_l'].", <b><font color=\"".linkcolor($row["f_leechers"])."\">".$row["f_leechers"]."</font></b> ".$tracker_lang['leechers_l']." = <b>" . ($row["f_seeders"] + $row["f_leechers"]) . "</b> ".$tracker_lang['peers_l']: "".$tracker_lang['disabled']."");

}
/// показ сидов на этом трекере
elseif ($action == "newmulti") {
global $announce_urls;
$sql = sql_query("SELECT id, info_hash, multi_infohash FROM torrents WHERE id = ".sqlesc($id)) or sqlerr(__FILE__,__LINE__);
$torrent = mysql_fetch_array($sql);

    $sql_ann = $tracker_cache = array();
    $f_leechers = 0;
    $f_seeders = 0;


$sres = sql_query("SELECT url FROM torrents_scrape WHERE tid = ".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
while($scrape = mysql_fetch_array($sres)) {
	$sql_ann[] = $scrape['url'];
}


	$rows = $torrent['info_hash'];

$announce_urls[] = $DEFAULTBASEURL."/announce.php?passkey=".$CURUSER['passkey'];
$announce_urls[] = "http://corbinaretracker.dyndns.org:80/announce.php";
$announce_urls[] = "udp://tracker.prq.to/announce";
$announce_urls[] = "udp://tracker.publicbt.com:80/announce";
$announce_urls[] = "udp://bt.rutor.org:2710";
$announce_urls[] = "http://tracker2.torrentino.com/announce";
$announce_urls[] = "udp://tracker.openbittorrent.com:80/announce";
//$announce_urls[] = "http://bt.nnm-club.info:2710/003c0589569f75ccc933a385abd268bb/announce";
$nannounce_urls = array_merge($announce_urls, $sql_ann);
$nannounce_urls = array_unique($nannounce_urls);
    
foreach($nannounce_urls as $announce) {
	$parse = parse_url($announce, PHP_URL_HOST);
	if(substr($announce, 0, 6) == 'udp://') {
		try {
			$data = $udp->scrape($announce, $rows);
			$data = $data[$rows];
			$f_seeders += $data['seeders'];
			$f_leechers += $data['leechers'];
			sql_query('UPDATE torrents_scrape SET state = "ok", error = "", seeders = '.sqlesc($data['seeders']).', leechers = '.sqlesc($data['leechers']).' WHERE tid = '.sqlesc($tid).' AND url = '.sqlesc($announce)) or sqlerr(__FILE__,__LINE__);
			$tracker_cache[] = $parse.':'.($data['leechers'] ? $data['leechers'] : 0).':'.($data['seeders'] ? $data['seeders'] : 0).':0';
		} catch(ScraperException $e){
			sql_query('UPDATE torrents_scrape SET state = "error", error = '.sqlesc($e->getMessage()).', seeders = 0, leechers = 0 WHERE tid = '.$id.' AND url = '.sqlesc($announce)) or sqlerr(__FILE__,__LINE__);
			$tracker_cache[] = $parse.':'.$e->getMessage();
		}
	} else {
		try {
			$data = $http->scrape($announce, $rows);
			$data = $data[$rows];
			$f_seeders += $data['seeders'];
			$f_leechers += $data['leechers'];
			sql_query('UPDATE torrents_scrape SET state = "ok", error = "", seeders = '.sqlesc($data['seeders']).', leechers = '.sqlesc($data['leechers']).' WHERE tid = '.sqlesc($tid).' AND url = '.sqlesc($announce)) or sqlerr(__FILE__,__LINE__);
			$tracker_cache[] = $parse.':'.($data['leechers'] ? $data['leechers'] : 0).':'.($data['seeders'] ? $data['seeders'] : 0).':0';
		} catch(ScraperException $e){
			sql_query('UPDATE torrents_scrape SET state = "error", error = '.sqlesc($e->getMessage()).', seeders = 0, leechers = 0 WHERE tid = '.$id.' AND url = '.sqlesc($announce)) or sqlerr(__FILE__,__LINE__);
			$tracker_cache[] = $parse.':'.$e->getMessage();
		}
	}
}

    $fpeers = $f_seeders + $f_leechers;
    $list = $tracker_cache;
    $tracker_cache = implode("\n", $tracker_cache);
    $updatef = array();
    $updatef[] = "f_trackers = ".sqlesc($tracker_cache);
    $updatef[] = "f_leechers = ".sqlesc($f_leechers);
    $updatef[] = "f_seeders = ".sqlesc($f_seeders);
    $updatef[] = "multi_time = ".sqlesc(get_date_time());
    $updatef[] = "visible = ".sqlesc(!empty($fpeers) ? 'yes':'no');
    sql_query("UPDATE torrents SET " . implode(",", $updatef) . " WHERE id = ".sqlesc($torrent['id']));

@unlink('cache/details/details_id'.$torrent['id'].'.txt');
@unlink("cache/details/details_thanked_id".$torrent['id'].".txt");
@unlink("cache/details/details_freeed_id".$torrent['id'].".txt");
@unlink("cache/details/thanks_comm_id".$torrent['id'].".txt");
@unlink("cache/details/delthanks_comm_id".$torrent['id'].".txt");
@unlink("cache/details/details_relizgroup_id".$torrent['id'].".txt");
@unlink("cache/details/download_id".$torrent['id'].".txt");


echo "<table width=100% class=main border=1 cellspacing=0 cellpadding=5>\n";

echo "<tr><td class=a align=center colspan=\"5\">Перепроверка началась</td></tr>";

$s4etik = 0;

foreach ($list AS $lisar){

if ($s4etik % 2 == 0) $clas_tdi = "class=\"a\""; else $clas_tdi = "class=\"b\"";

$ex_lode = explode(":", $lisar);

$li_text = array(
"off" => "мультитрекер отключен системно",
"false" => "ответ пуст, торрент не существует",
"timeout" => "время отклика прошло (timeout)"
);

$status = '';
if (in_array($ex_lode[1], array("off","timeout","false")))
$status = $li_text[$ex_lode[1]];

echo "<tr>
<td $clas_tdi align=left>".$ex_lode[0]."</td>
<td $clas_tdi align=center>".(!empty($status) ? "-":"<b><font color=\"".linkcolor($ex_lode[2])."\">".$ex_lode[2]."</font></b>")."</td>
<td $clas_tdi align=center>".(!empty($status) ? "-":"<b><font color=\"".linkcolor($ex_lode[1])."\">".$ex_lode[1]."</font></b>")."</td>
<td $clas_tdi align=center>".(!empty($status) ? "-":"<b><font color=\"".linkcolor($ex_lode[3])."\">".$ex_lode[3]."</font></b>")."</td>
<td $clas_tdi align=center>".(!empty($status) ? $status:"успешно, работает")."</td>
</tr>\n"; 
++$s4etik;
}

echo "<tr><td class=a align=center colspan=\"5\">Последняя проверка: сейчас</td></tr>";

echo "</table>\n";

}




?>