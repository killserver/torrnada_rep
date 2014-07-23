<?php
require_once("include/bittorrent.php");

ini_set("max_execution_time", 0);
set_time_limit(0);

gzip();

dbconn(false);
//loggedinorreturn();


function getagent($httpagent, $peer_id = "") {
        if (preg_match("/^Azureus ([0-9]+\.[0-9]+\.[0-9]+\.[0-9]\_B([0-9][0-9|*])(.+)$)/", $httpagent, $matches))
        return "Azureus/$matches[1]";
        elseif (preg_match("/^Azureus ([0-9]+\.[0-9]+\.[0-9]+\.[0-9]\_CVS)/", $httpagent, $matches))
        return "Azureus/$matches[1]";
        elseif (preg_match("/^Java\/([0-9]+\.[0-9]+\.[0-9]+)/", $httpagent, $matches))
        return "Azureus/<2.0.7.0";
        elseif (preg_match("/^Azureus ([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $httpagent, $matches))
        return "Azureus/$matches[1]";
        elseif (preg_match("/BitTorrent\/S-([0-9]+\.[0-9]+(\.[0-9]+)*)/", $httpagent, $matches))
        return "Shadow's/$matches[1]";
        elseif (preg_match("/BitTorrent\/U-([0-9]+\.[0-9]+\.[0-9]+)/", $httpagent, $matches))
        return "UPnP/$matches[1]";
        elseif (preg_match("/^BitTor(rent|nado)\\/T-(.+)$/", $httpagent, $matches))
        return "BitTornado/$matches[2]";
        elseif (preg_match("/^BitTornado\\/T-(.+)$/", $httpagent, $matches))
        return "BitTornado/$matches[1]";
        elseif (preg_match("/^BitTorrent\/ABC-([0-9]+\.[0-9]+(\.[0-9]+)*)/", $httpagent, $matches))
        return "ABC/$matches[1]";
        elseif (preg_match("/^ABC ([0-9]+\.[0-9]+(\.[0-9]+)*)\/ABC-([0-9]+\.[0-9]+(\.[0-9]+)*)/", $httpagent, $matches))
        return "ABC/$matches[1]";
        elseif (preg_match("/^Python-urllib\/.+?, BitTorrent\/([0-9]+\.[0-9]+(\.[0-9]+)*)/", $httpagent, $matches))
        return "BitTorrent/$matches[1]";
        elseif (preg_match("/^BitTorrent\/brst(.+)/", $httpagent, $matches))
        return "Burst";
        elseif (preg_match("/^RAZA (.+)$/", $httpagent, $matches))
        return "Shareaza/$matches[1]";
        elseif (preg_match("/Rufus\/([0-9]+\.[0-9]+\.[0-9]+)/", $httpagent, $matches))
        return "Rufus/$matches[1]";
        elseif (preg_match("/^Python-urllib\\/([0-9]+\\.[0-9]+(\\.[0-9]+)*)/", $httpagent, $matches))
        return "G3 Torrent";
        elseif (preg_match("/MLDonkey\/([0-9]+).([0-9]+).([0-9]+)*/", $httpagent, $matches))
        return "MLDonkey/$matches[1].$matches[2].$matches[3]";
        elseif (preg_match("/ed2k_plugin v([0-9]+\\.[0-9]+).*/", $httpagent, $matches))
        return "eDonkey/$matches[1]";
        elseif (preg_match("/uTorrent\/([0-9]+)([0-9]+)([0-9]+)([0-9A-Z]+)/", $httpagent, $matches))
        return "µTorrent/$matches[1].$matches[2].$matches[3].$matches[4]";
        elseif (preg_match("/CT([0-9]+)([0-9]+)([0-9]+)([0-9]+)/", $peer_id, $matches))
        return "cTorrent/$matches[1].$matches[2].$matches[3].$matches[4]";
        elseif (preg_match("/Transmission\/([0-9]+).([0-9]+)/", $httpagent, $matches))
        return "Transmission/$matches[1].$matches[2]";
        elseif (preg_match("/KT([0-9]+)([0-9]+)([0-9]+)([0-9]+)/", $peer_id, $matches))
        return "KTorrent/$matches[1].$matches[2].$matches[3].$matches[4]";
        elseif (preg_match("/rtorrent\/([0-9]+\\.[0-9]+(\\.[0-9]+)*)/", $httpagent, $matches))
        return "rTorrent/$matches[1]";
        elseif (preg_match("/^ABC\/Tribler_ABC-([0-9]+\.[0-9]+(\.[0-9]+)*)/", $httpagent, $matches))
        return "Tribler/$matches[1]";
        elseif (preg_match("/^BitsOnWheels( |\/)([0-9]+\\.[0-9]+).*/", $httpagent, $matches))
        return "BitsOnWheels/$matches[2]";
        elseif (preg_match("/BitTorrentPlus\/(.+)$/", $httpagent, $matches))
        return "BitTorrent Plus!/$matches[1]";
        elseif (preg_match("/^Deadman Walking/", $httpagent))
        return "Deadman Walking";
        elseif (preg_match("/^eXeem( |\/)([0-9]+\\.[0-9]+).*/", $httpagent, $matches))
        return "eXeem$matches[1]$matches[2]";
        elseif (preg_match("/^libtorrent\/(.+)$/", $httpagent, $matches))
        return "libtorrent/$matches[1]";
        elseif (substr($peer_id, 0, 12) == "d0c")
        return "Mainline";
        elseif (substr($peer_id, 0, 1) == "M")
        return "Mainline/Decoded";
        elseif (substr($peer_id, 0, 3) == "-BB")
        return "BitBuddy";
        elseif (substr($peer_id, 0, 8) == "-AR1001-")
        return "Arctic Torrent/1.2.3";
        elseif (substr($peer_id, 0, 6) == "exbc\08")
        return "BitComet/0.56";
        elseif (substr($peer_id, 0, 6) == "exbc\09")
        return "BitComet/0.57";
        elseif (substr($peer_id, 0, 6) == "exbc\0:")
        return "BitComet/0.58";
        elseif (substr($peer_id, 0, 4) == "-BC0")
        return "BitComet/0.".substr($peer_id, 5, 2);
        elseif (substr($peer_id, 0, 7) == "exbc\0L")
        return "BitLord/1.0";
        elseif (substr($peer_id, 0, 7) == "exbcL")
        return "BitLord/1.1";
        elseif (substr($peer_id, 0, 3) == "346")
        return "TorrenTopia";
        elseif (substr($peer_id, 0, 8) == "-MP130n-")
        return "MooPolice";
        elseif (substr($peer_id, 0, 8) == "-SZ2210-")
        return "Shareaza/2.2.1.0";
        elseif (preg_match("/^0P3R4H/", $httpagent))
        return "Opera BT Client";
        elseif (substr($peer_id, 0, 6) == "A310--")
        return "ABC/3.1";
        elseif (preg_match("/^XBT Client/", $httpagent))
        return "XBT Client";
        elseif (preg_match("/^BitTorrent\/BitSpirit$/", $httpagent))
        return "BitSpirit";
        elseif (preg_match("/^DansClient/", $httpagent))
        return "XanTorrent";
        else
        return "Unknown";
}

function dltable($name, $arr, $torrent)
{

        global $CURUSER, $tracker_lang;
        $s = "<b>" . count($arr) . " $name</b>\n";
        if (!count($arr))
                return $s;
        $s .= "\n";
        $s .= "<table width=100% class=main border=1 cellspacing=0 cellpadding=5>\n";
        $s .= "<tr><td class=colhead>".$tracker_lang['user']."</td>" .
          "<td class=colhead align=center>".$tracker_lang['port_open']."</td>".
          "<td class=colhead align=right>".$tracker_lang['uploaded']."</td>".
          "<td class=colhead align=right>".$tracker_lang['ul_speed']."</td>".
          "<td class=colhead align=right>".$tracker_lang['downloaded']."</td>" .
          "<td class=colhead align=right>".$tracker_lang['dl_speed']."</td>" .
          "<td class=colhead align=right>".$tracker_lang['ratio']."</td>" .
          "<td class=colhead align=right>".$tracker_lang['completed']."</td>" .
          "<td class=colhead align=right>".$tracker_lang['connected']."</td>" .
          "<td class=colhead align=right>".$tracker_lang['idle']."</td>" .
          "<td class=colhead align=left>".$tracker_lang['client']."</td></tr>\n";
        $now = time();
        $moderator = (isset($CURUSER) && get_user_class() >= UC_MODERATOR);
		$mod = get_user_class() >= UC_MODERATOR;
        foreach ($arr as $e) {
                // user/ip/port
                // check if anyone has this ip
                $s .= "<tr>\n";
                if ($e["username"])
                  $s .= "<td><a href=\"userdetails.php?id=$e[userid]\"><b>".get_user_class_color($e["class"], $e["username"])."</b></a>".($mod ? "&nbsp;[<span title=\"{$e["ip"]}\" style=\"cursor: pointer\">IP</span>]" : "")."</td>\n";
                else
                  $s .= "<td>" . ($mod ? $e["ip"] : preg_replace('/\.\d+$/', ".xxx", $e["ip"])) . "</td>\n";
                $secs = max(10, ($e["la"]) - $e["pa"]);
                $revived = $e["revived"] == "yes";
        		$s .= "<td align=\"center\">" . ($e[connectable] == "yes" ? "<span style=\"color: green; cursor: help;\" title=\"Порт открыт. Этот пир может подключатся к любому пиру.\">".$tracker_lang['yes']."</span>" : "<span style=\"color: red; cursor: help;\" title=\"Порт закрыт. Рекомендовано проверить настройки Firwewall'а.\">".$tracker_lang['no']."</span>") . "</td>\n";
                $s .= "<td align=\"right\"><nobr>" . mksize($e["uploaded"]) . "</nobr></td>\n";
                $s .= "<td align=\"right\"><nobr>" . mksize($e["uploadoffset"] / $secs) . "/s</nobr></td>\n";
                $s .= "<td align=\"right\"><nobr>" . mksize($e["downloaded"]) . "</nobr></td>\n";
                //if ($e["seeder"] == "no")
                        $s .= "<td align=\"right\"><nobr>" . mksize($e["downloadoffset"] / $secs) . "/s</nobr></td>\n";
                /*else
                        $s .= "<td align=\"right\"><nobr>" . mksize($e["downloadoffset"] / max(1, $e["finishedat"] - $e["st"])) . "/s</nobr></td>\n";*/
                if ($e["downloaded"]) {
                  $ratio = floor(($e["uploaded"] / $e["downloaded"]) * 1000) / 1000;
                    $s .= "<td align=\"right\"><font color=" . get_ratio_color($ratio) . ">" . number_format($ratio, 3) . "</font></td>\n";
                } else
					if ($e["uploaded"])
	                  	$s .= "<td align=\"right\">Inf.</td>\n";
					else
	                  	$s .= "<td align=\"right\">---</td>\n";
                $s .= "<td align=\"right\">" . sprintf("%.2f%%", 100 * (1 - ($e["to_go"] / $torrent["size"]))) . "</td>\n";
                $s .= "<td align=\"right\">" . mkprettytime($now - $e["st"]) . "</td>\n";
                $s .= "<td align=\"right\">" . mkprettytime($now - $e["la"]) . "</td>\n";
                $s .= "<td align=\"left\">" . htmlspecialchars(getagent($e["agent"], $e["peer_id"])) . "</td>\n";
                $s .= "</tr>\n";
        }
        $s .= "</table>\n";
        return $s;
}




$id = (isset($_GET["id"]) ? intval($_GET["id"]):0);


if (!is_valid_id($id) || empty($id))
header("Refresh: 0; url=browse.php");


$res = sql_query("SELECT td.descr_hash, td.descr_parsed, torrents.block_edit, torrents.karmushka, torrents.kp, torrents.multitracker, torrents.ban, torrents.category, torrents.youtube, torrents.relizgroup, torrents.new, torrents.comment_lock, (SELECT username FROM users WHERE id = torrents.modby) as modby, (SELECT class FROM users WHERE id = torrents.modby) as modbyclass, torrents.banned, torrents.info_hash, torrents.filename, UNIX_TIMESTAMP() - UNIX_TIMESTAMP(torrents.last_action) AS lastseed, torrents.numratings, torrents.name, torrents.owner, torrents.save_as, torrents.descr, torrents.visible, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.times_completed, torrents.id, torrents.type, torrents.numfiles, torrents.image1, torrents.image2, torrents.image3, torrents.image4, torrents.image5, torrents.image6, torrents.status, categories.name AS cat_name, users.username " . ($CURUSER ? ", (SELECT COUNT(*) FROM karma WHERE cat='torrents' AND uid = $CURUSER[id] AND pid = $id) AS ktotal" : "") . " FROM torrents LEFT JOIN categories ON torrents.category = categories.id LEFT JOIN users ON torrents.owner = users.id LEFT JOIN torrents_descr AS td ON td.tid = $id WHERE torrents.id = $id", 86400,"details/details_id".$id.".txt")
        or sqlerr(__FILE__, __LINE__);

//$row = mysql_fetch_array($res);
$row = mysql_fetch_array($res);


sql_query("INSERT INTO readtorrents (userid, torrentid) VALUES (".sqlesc($CURUSER["id"]).", ".sqlesc($id).")")/* or sqlerr(__FILE__,__LINE__)*/;

$owned = $moderator = 0;
        if (get_user_class() >= UC_MODERATOR)
                $owned = $moderator = 1;
        elseif ($CURUSER["id"] == $row["owner"])
                $owned = 1;
//}

if($row["ban"] == "yes" && !$moderator)
        stderr($tracker_lang['error'], "Торрент забанен");

if (!$row || ($row["banned"] == "yes" && !$moderator))
        stderr($tracker_lang['error'], $tracker_lang['no_torrent_with_such_id']);
else {
        if (isset($_GET["hit"])) {
                sql_query("UPDATE torrents SET views = views + 1 WHERE id = $id");
                if (isset($_GET["tocomm"]))
                        header("Location: $DEFAULTBASEURL/details.php?id=$id&page=0#startcomments");
                elseif (isset($_GET["filelist"]))
                        header("Location: $DEFAULTBASEURL/details.php?id=$id&filelist=1#filelist");
                elseif (isset($_GET["toseeders"]))
                        header("Location: $DEFAULTBASEURL/details.php?id=$id&dllist=1#seeders");
                elseif (isset($_GET["todlers"]))
                        header("Location: $DEFAULTBASEURL/details.php?id=$id&dllist=1#leechers");
                else
                        header("Location: $DEFAULTBASEURL/details.php?id=$id");
                exit();
        }

$row["name"]=view_saves($row['name']);

        if (!isset($_GET["page"])) {
                stdhead($row["name"]);

?> 
<script type="text/javascript" src="lightbox/jquery.js"></script> 
<link rel="stylesheet" href="lightbox/lightbox.css" type="text/css" media="screen" /> 
<script type="text/javascript" src="lightbox/lightbox.js"></script> 
<script type="text/javascript"> 
$(function() {$('a[@rel*=lightbox]').lightBox();}); 
</script> 
<script language="javascript" type="text/javascript" src="js/crossxhr.js"></script>
<link rel="stylesheet" href="css/kinopoisk.css" type="text/css" media="screen" /> 
<?
                $owned = 0;
                if($CURUSER["id"] == $row["owner"] || get_user_class() >= UC_MODERATOR)
                        $owned = 1;
		if($row['block_edit']=="true" && get_user_class() < UC_MODERATOR)
                        $owned = 0;
                if(get_user_class() >= UC_MODERATOR)
                        $owned = 1;

                $spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$guest = true;
		if(empty($CURUSER['username'])) {
			$ip = getenv("REMOTE_ADDR");
			$rows = sql_query("SELECT COUNT(*) as count FROM tmp_users WHERE ip = \"".$ip."\"");
			$gueste = mysql_fetch_array($rows);
			if($gueste['count'] >= 3) {
				$guest = false;
			}
		}
                //$s=$row["name"];

                print("<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
if($CURUSER){
                print("<tr><td class=\"colhead\" colspan=\"2\"><div style=\"float: left; width: auto;\">:: ".$tracker_lang['torrent_details']."</div><div align=\"right\"><a href=\"bookmark.php?torrent=$row[id]\"><b>Добавить в избранное</b></a></div></td></tr>");
}else{
                print("<tr><td class=\"colhead\" colspan=\"2\"><div style=\"float: left; width: auto;\">:: ".$tracker_lang['torrent_details']."</div></td></tr>");
}
                $url = "edit.php?id=" . $row["id"];
                if (isset($_GET["returnto"])) {
                        $addthis = "&amp;returnto=" . urlencode($_GET["returnto"]);
                        $url .= $addthis;
                        $keepget .= $addthis;
                }

$ld = $ldadd = "";
if($CURUSER) {
	if($CURUSER["id"] == $row["owner"]) {
		$ld = "download.php?id=$id&amp;name=[torrnada.ru]_".rawurlencode($row["filename"]);
	} else {
		$ld = "download.php?id=$id&amp;name=[torrnada.ru]_".rawurlencode($row["filename"]);
	}
	switch($row['free']) {
		case 'no':
			$ldadd .= "";
		break;
		case 'silver':
			$ldadd .= "<img src=\"pic/silverdownload.gif\" title=\"".$tracker_lang['silver']."\" alt=\"".$tracker_lang['silver']."\">";
		break;
		case 'gold':
			$ldadd .= "<img src=\"pic/freedownload.gif\" title=\"".$tracker_lang['golden']."\" alt=\"".$tracker_lang['golden']."\">";
		break;
	}
	if($owned && ($row["status"] != "0" && $row["status"] != "1" && $row["status"] != "2" && $row["status"] != "5" && $row["status"] != "6" && $row["status"] != "7" && $row["status"] != "8")) {
		$ldadd .= $spacer."<a href=\"".$url."\" class=\"sublink\">[".$tracker_lang['edit']."]</a>";
	}
} else {
	//if ($row["moderated"] == "yes") {
		$ld = "download_file.php?id=$id&amp;name=[torrnada.ru]_" . rawurlencode($row["filename"]);
	//}
}



$s = "<a class=\"index\" href=\"".$ld."\">" . ($row["new"] == "yes" ? "<center><b><img src=\"pic/new.gif\">":"</b></center>") ."<b>" . $row["name"] . "</b></a>".$ldadd;
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
if($row["status"] == "0" && get_user_class() < UC_MODERATOR) {
	$s = "<b>".$row['name']."</b><br /><h2><font color=\"red\">Данный релиз находится на проверке. После проверки - релиз будет доступен к скачиванию. Благодарим за понимание!</font></h2>"; 
	if(isset($CURUSER['id']) && $CURUSER["id"] == $row["owner"]) {
		$s = "<a href=\"download.php?id=$id&amp;name=[torrnada.ru]_".rawurlencode($row["filename"])."\"><b>".$row['name']."</b></a>";
	}
}
	if($owned && ($row["status"] == "0" || $row["status"] == "1" || $row["status"] == "2" || $row["status"] == "5" || $row["status"] == "6" || $row["status"] == "7" || $row["status"] == "8")) {
		$s .= $spacer."<a href=\"".$url."\" class=\"sublink\">[".$tracker_lang['edit']."]</a>";
	}
$s .= "<br><div class='pluso pluso-theme-color pluso-round' style='background:#eaeaea;'><div class='pluso-more-container'><a class='pluso-more' href=''></a><ul class='pluso-counter-container'><li></li><li class='pluso-counter'></li><li></li></ul></div><a class='pluso-facebook'></a><a class='pluso-twitter'></a><a class='pluso-vkontakte'></a><a class='pluso-odnoklassniki'></a><a class='pluso-google'></a><a class='pluso-livejournal'></a><a class='pluso-moimir'></a><a class='pluso-liveinternet'></a></div><script type='text/javascript'>if(!window.pluso){pluso={version:'0.9.2',url:'http://share.pluso.ru/'};h=document.getElementsByTagName('head')[0];l=document.createElement('link');l.href=pluso.url+'pluso.css';l.type='text/css';l.rel='stylesheet';s=document.createElement('script');s.charset='UTF-8';s.src=pluso.url+'pluso.js';h.appendChild(l);h.appendChild(s)}</script>";

/*
//Мультитрекер
if ($row["multitracker"] == "yes" &&  ($row["f_seeders"] + $row["f_leechers"])<=3) {
echo "<tr><td align=\"center\" width=\"99%\" colspan=\"2\" style=\"font-weight: bold;\" class=\"b\">Данная раздача является мультитрекерной (<a title=\"Прочитать статью о мультитрекерности\" href=\"http://ru.wikipedia.org/wiki/%D0%9C%D1%83%D0%BB%D1%8C%D1%82%D0%B8%D1%82%D1%80%D0%B5%D0%BA%D0%B5%D1%80\">?</a>) — учтите, количество раздающих может не совпадать с количеством пиров на сайте (также зависит от настроек клиента uTorrent, настройки -> Включить DHT, Обмен пирами и Поиск лок-х пиров). <br>Пример: 0 раздающих, N качающих = N пиров - где N колеблится от 1 до бесконечности, при  0 раздающих и 1 качающих - правильно считать раздачу <a title=\"(не работающая ссылка или раздача которая скачивается не полностью и программа не работает на компьютере скачавшего)\">мертвой</a>.</td></tr>"; 
}
*/

                tr ("<nobr>{$row["cat_name"]}</nobr>", $s, 1, 1, "10%");

                function hex_esc($matches) {
                        return sprintf("%02x", ord($matches[0]));
                }

/*
	print("<tr><td align=\"right\"><b>Проверен<b></td>");  

        if ($row["moderated"] == "no") {
		print("<td><table><tr>");
		if ($CURUSER["id"] == $row["owner"]) {
			print("<td align=\"left\"><b>Это твой торрент</b></td>\n");
		} else {
			if (get_user_class() >= UC_MODERATOR)
				$checks = "<a href=\"check.php?id=$id\"><b><img src=\"pic/red.gif\" alt=\"Не проверен администрацией\" ></b></a>";
		}

                print("<td align=\"left\">".$checks."<font color=red> Этот торрент ещё не проверен Администрацией.</font></td>\n");
		print("</tr></table></td>");
	} else {
            if ($row['moderatedby'] > 0)
                list($moderatorname) = mysql_fetch_row(sql_query('SELECT username FROM users WHERE id = '.$row['moderatedby']));
            elseif (get_user_class() >= UC_MODERATOR)
                $moderatorname = 'Автоматически по классу';

                print("<td align=\"left\"><a href=\"userdetails.php?id=$row[moderatedby]\"><img src=\"pic/green.gif\" alt= $moderatorname></a><font color=green> Этот торрент проверен Администрацией.</font><br><b>Проверил:</b> $moderatorname</td></tr>\n");
        }*/


                if($row["status"] == "2") {
                $tstatus = "<b><span style=\"color: #CC3333;\">x</span></b>  &nbsp;<b>Закрыто</b>";
                } elseif($row["status"] == "3") {
                $tstatus = "<b><span style=\"color: green;\">&radic;</span></b>  &nbsp;<b>Проверил</b>";
                } elseif($row["status"] == "4") {
                $tstatus = "<b><span style=\"color: #CC3333;\">?</span></b>  &nbsp;<b>Недооформлено</b>";
                } elseif($row["status"] == "5") {
                $tstatus = "<b><span style=\"color: #CC3333;\">!</span></b>  &nbsp;<b>Неоформлено</b>";
                } elseif($row["status"] == "6") {
                $tstatus = "<b><span style=\"color: #0000FF;\">D</span></b>  &nbsp;<b>Повтор</b>";
                } elseif($row["status"] == "7") {
                $tstatus = "<b><span style=\"color: #A52A2A;\">&copy</span></b>  &nbsp;<b>Раздача закрыта правообладателем</b>";
                } elseif($row["status"] == "8") {
                $tstatus = "<b><span style=\"color: #996600;\">&sum;</span></b>  &nbsp;<b>Поглощено</b>";
                }
		if(isset($tstatus)) {
                	tr("Статус раздачи", $tstatus." ".get_user_class_color($row['modbyclass'], $row['modby']), 1);
		}
		if(get_user_class() >= UC_MODERATOR) {// && $CURUSER["id"] != $row["owner"]) {
$cstatus = <<<HTML
<form action="takettstatus.php?id={$id}" method="post">
<select name="status">
	<option value="0">(выберите статус)</option>
	<option value="1">Проверяется</option>
	<option value="2">Закрыто</option>
	<option value="3">Проверено</option>
	<!--option value="4">Недооформлено</option-->
	<option value="5">Неоформлено</option>
	<option value="6">Повтор</option>
	<option value="7">Раздача закрыта правообладателями</option>
	<!--option value="8">Поглощено</option-->
</select>&nbsp;<input type="submit" value="Проверить">
</form>
HTML;
                tr("Статус", $cstatus, 1);
                }

if($row['ban'] == "yes"){
if($CURUSER['class'] == UC_SYSTEM){
print("<tr><td></td><td>Ваш торрент забанен,<a href=unban.php?id=$id>Разбанить?</a></td></tr>");
}else{
print("<tr><td></td><td>Ваш торрент забанен</td></tr>");
}
} else {
if($CURUSER['class'] == UC_SYSTEM){
print("<tr><td></td><td>Торрент не забанен,<a href=ban.php?id=$id>Забанить?</a></td></tr>");
}
}


                tr($tracker_lang['info_hash'], $row["info_hash"]);
		if(!empty($row["kp"])) {
			$kp ="".$row['kp'].""; 
		} 



if($HOLIDAYS['eggs']) {
// eggs mod <<<<<<<<<<< 
                $egg=""; 
                //if (get_user_class() > UC_MODERATOR) { 
                    $bonus=rand(1,400); 
                    If ($bonus % 10 == 0) {
                        sql_query("INSERT INTO summer (torrentid, userid, bonus) VALUES (".$id.", ".$CURUSER['id'].", ".$bonus.")") or sqlerr(__FILE__,__LINE__); 
			$poster= "torrents/images/".$row['image1'];
                        $egg="<div style=\"position: relative;\"><div id=\"kinipoisk\"><span class=\"kinoid\" style=\"display: none;\">".$kp."</span><div class=\"kinorating\"></div></div></div><a href='get_summer.php?id=$id'><img src=\"./pic/summer/".rand(1, 7).".png\" alt=\"Открой солнечный бонус! Кликни здесь!\" /></a>";
			$im1 = '<div style="position:relative;width:650px;margin-bottom:0px"><a href='.$poster.' rel="lightbox"><img border=0 width=250 src='.$poster.'></a><div style="position: absolute; left: 160px;top: 8px; font-size: 16px" >'.$egg.'</div></div>';
                    } 
                //} 
                // >>>>>>>>>>>>> eggs mod END 
} elseif($HOLIDAYS['winter']) {
		$winter=""; 
		$bonus=rand(1,400); 
		$rand=rand(0,10); 
                //If ($bonus % 10 == 0) {
		If(!empty($CURUSER['id']) && $rand == 0) {
			sql_query("INSERT INTO winter (torrentid, userid, bonus) VALUES (".$id.", ".$CURUSER['id'].", ".$bonus.")") or sqlerr(__FILE__,__LINE__); 
			$poster= "torrents/images/".$row['image1'];
			$winter="<div style=\"position: relative;\"><div id=\"kinipoisk\"><span class=\"kinoid\" style=\"display: none;\">".$kp."</span><div class=\"kinorating\"></div></div></div><a href='get_winter.php?id=".$id."'><img src=\"./pic/winter/Newyear".rand(0, 5).".png\" width=70 height=65 alt=\"Открой новогодний бонус! Кликни здесь!\" /></a>";
			$im1 = '<div style="position:relative;width:650px;margin-bottom:0px"><a href='.$poster.' rel="lightbox"><img border=0 width=250 src='.$poster.'></a><div style="position: absolute; left: 160px;top: 8px; font-size: 16px" >'.$winter.'</div></div>';
		} elseIf(!empty($CURUSER['id']) && $rand != 0) { 
			$im1 = "<div style=\"position: relative;\"><div id=\"kinipoisk\"><span class=\"kinoid\" style=\"display: none;\">".$kp."</span><div class=\"kinorating\"></div></div></div><a href=\"torrents/images/".$row['image1']."\" rel=\"lightbox\"><img border='0' width=250 src='torrents/images/".$row['image1']."' /></a>";
		}
} else {
		if($row["image1"] != "") {
			$kp = (!empty($row['kp']) ? "<div style=\"position: relative;\"><div id=\"kinipoisk\"><span class=\"kinoid\" style=\"display: none;\">".$kp."</span><div class=\"kinorating\"></div></div></div>" : "");
			$im1= $kp."<a href=\"torrents/images/".$row['image1']."\" rel=\"lightbox\"><img border='0' width=250 src='torrents/images/".$row['image1']."' /></a>";
		}
}

if(!empty($row["image6"])) {
	$img6 = "<br /><br /><a href=\"torrents/images/".$row['image6']."\" rel=\"lightbox\"><img border='0' width=250 src='torrents/images/".$row['image6']."' /></a>";
}
tr('Постер', $im1.$img6, 1);



///////// Поиск по актерам by Йожжж START/////////
if(isset($_GET['unset_descr'])) {
sql_query("DELETE FROM torrents_descr WHERE id = ".$id);
@unlink("cache/details/details_id".$id.".txt");
}
	if(!isset($_GET['unset_descr']) && md5($row['descr']) == $row['descr_hash']) {
		$descr = $row['descr_parsed'];
	} else {
		if(!empty($row["descr"])) {//empty($row['descr_parsed']) && 
			$find = "[b]В ролях:[/b]";
			if(strpos($row['descr'], $find)!==false) {
				$total_length = strlen($row['descr']);
				$length_before = strlen($row['descr']) - strlen(strstr($row['descr'], $find));
				$length_after = strlen(strstr(strstr($row['descr'], $find), "\n"));
				$before = substr($row['descr'], 0, $length_before);
				$after = strstr(strstr($row['descr'], $find), "\n");
				$match = substr($row['descr'], $length_before + strlen($find), $total_length - $length_before - $length_after - strlen($find));
				$matches = explode(",", $match);
				$descr = format_comment($before, true)."<br>";
				$descr .= "<b>В ролях</b>: ";
		
				for($i=0;$i<sizeof($matches);$i++) {
					$actors[] = ($i ? ' ' : '') . '<b><a href="browse.php?search=' . trim($matches[$i]) . '&on=2" title="Поиск фильмов с участием этого актера">' . trim($matches[$i]) . '</a></b>';
				}

				$descr .= implode(",", $actors);
				$descr .= "<br>". format_comment($after, true);
		         } else {
				$descr = format_comment($row['descr']);
		         }
		}
		sql_query('REPLACE INTO torrents_descr (tid, descr_hash, descr_parsed) VALUES ('.implode(', ', array_map('sqlesc', array($id, md5($row['descr']), $descr))).')') or sqlerr(__FILE__,__LINE__);
	}
	tr($tracker_lang['description'], $descr. "<br>", 1, 1);
///////// Поиск по актерам by Йожжж END/////////



	$qr = "<br /><img src=\"/qr/".$id.".jpg\" alt=\"Для скачки на мобильном устройстве - просто просканируйте QR-код.\" title=\"Для скачки на мобильном устройстве - просто просканируйте QR-код.\">";
	$guest = true;
	if(empty($CURUSER['username'])) {
		$ip = getenv("REMOTE_ADDR");
		$rows = sql_query("SELECT COUNT(*) as count FROM tmp_users WHERE ip = \"".$ip."\"");
		$gueste = mysql_fetch_array($rows);
		if($gueste['count'] >= 3) {
			$guest = false;
		}
	}
	$magnet = ($guest ? "&nbsp;<a href=\"".magnet($row)."\"><img src=\"/pic/button_magnite.gif\" alt=\"Примагнититься\" title=\"Примагнититься\"></a>" : "");
	$ld = $ldadd = "";
if($CURUSER["id"] == $row["owner"]) {
	$ld = "download.php?id=".$id."&amp;name=[torrnada.ru]_".rawurlencode($row["filename"]);
} elseif($CURUSER["username"] != "Нуб") {
	$ld = "download.php?id=".$row['id']."&name=".$row['filename'];
} else {
	$ld = "download_file.php?id=".$row['id']."&name=".$row['filename'];
	$ldadd .= "<br><small>Вы не можите скачивать не проверенные торренты</small>";
}

if(isset($CURUSER['id']) && in_array($row["category"], array(46,91,92,93,94,95,96,97,98,99,100,101,12,24,162,11,169,45,103,35,14,182,41,144,160,40,140,166,184,183,102))) {
	$vd = "\n&nbsp;<a rel=\"nofollow\" href=\"download.php?id=".$id."&view_online_ts\"><img src=\"pic/torrentstream.png\" alt=\"".$tracker_lang['view_online_ts']."\" title=\"".$tracker_lang['view_online_ts']."\"></a>&nbsp;<a href=\"forum.php?action=viewtopic&topicid=98\" target=\"_blank\"><sup style=\"vertical-align: super; font-size: 20pt;\"><b>?</b></sub></a><br />\n";
} else {
	$vd = "";
}

$link = "<a class=\"index\" href=\"".$ld."\">" . ($row["new"] == "yes" ? "<center><b><img src=pic/new.gif>":"</b></center>") ."<b><img src=pic/button2.png></b></a>".($guest ? "&nbsp;".$magnet.$vd.$ldadd.$qr : "");
if($row["status"] == "1" && get_user_class() < UC_MODERATOR){ 
	$link = "Торрент проверяется";
} elseif($row["status"] == "2"){ 
	$link = "Торрент закрыт";
} elseif($row["status"] == "5" && get_user_class() < UC_MODERATOR){ 
	$link = "В описании есть значительные отклонения от правил - загрузка недоступна."; 
} elseif($row["status"] == "6"){ 
	$link = "Торрент закрыт по причине повтора"; 
} elseif($row["status"] == "7"){ 
	$link = "Правообладатель закрыл этот торрент."; 
} elseif($row["status"] == "8"){ 
	$link = "Торрент был поглощён"; 
}
if($row["status"] == "0" && get_user_class() < UC_MODERATOR){
	$link = "<b>".$row['name']."</b>"; 
	if(isset($CURUSER['id']) && $CURUSER["id"] == $row["owner"]) {
		$link = "<a href=\"download.php?id=$id&amp;name=[torrnada.ru]_".rawurlencode($row["filename"])."\"><b><img src=pic/button2.png></b></a>";
	}
}
tr ("", $link."<br><left><div class='pluso pluso-theme-color pluso-round' style='background:#eaeaea;'><div class='pluso-more-container'><a class='pluso-more' href=''></a><ul class='pluso-counter-container'><li></li><li class='pluso-counter'></li><li></li></ul></div><a class='pluso-facebook'></a><a class='pluso-twitter'></a><a class='pluso-vkontakte'></a><a class='pluso-odnoklassniki'></a><a class='pluso-google'></a><a class='pluso-livejournal'></a><a class='pluso-moimir'></a><a class='pluso-liveinternet'></a></div><script type='text/javascript'>if(!window.pluso){pluso={version:'0.9.2',url:'http://share.pluso.ru/'};h=document.getElementsByTagName('head')[0];l=document.createElement('link');l.href=pluso.url+'pluso.css';l.type='text/css';l.rel='stylesheet';s=document.createElement('script');s.charset='UTF-8';s.src=pluso.url+'pluso.js';h.appendChild(l);h.appendChild(s)}</script></left>", 1);

                 if ($row["youtube"] <> NULL) {
tr("Видео", '<a href="javascript: show_hide(\'s2\')"> 
         <marquee onmouseover=this.stop() onmouseout=this.start() scrollAmount=5 scrollDelay=1 direction=right  border=1> 
            <i><font color=red size=2> Нажми на меня</font></i> 
                     </marquee> 
                  </a><div id="ss2" style="display: none;">'.format_comment($row["youtube"]).'</div>', 1);
}



				$images = array();
				for ($i = 2; $i <= 5; $i++) {
					if(!empty($row['image'.$i])) {
					if(file_exists(dirname(__FILE__)."/torrents/images/thumbnails/".$row['image'.$i])) {
						$thumb = "torrents/images/thumbnails/".$row['image'.$i];
					} else {
						$thumb = "preview-".$row['image'.$i];
					}
					$images[] = '<a href="torrents/images/' . $row['image'.$i] . '" rel="lightbox" title="Скриншот №'.($i - 1).'"><img title="Скриншот №'.($i - 1).'" border="0" src="'.$thumb.'" /></a>';
					}
				}
				if(sizeof($images))
					tr($tracker_lang['images'], implode('&nbsp; ', $images), 1);



if ($row["relizgroup"] != 0){
$reliz = new MySQLCache("SELECT img, link FROM relizgroup WHERE id='".$row['relizgroup']."'", 86400,"details/details_relizgroup_id".$id.".txt"); 

if (! is_array ( $rel )) {
	$rel = $reliz->fetch_array ( $reliz );
	WriteArrayStr ( $rel, $details_cache );
} else
	$rel = $reliz;

$reliz_group = "<img src=\"pic/reliz-grup/$rel[img]\" title=\"$rel[link]\">"; 
} 

                 if ($row["relizgroup"] <> 0){
					tr("Релиз-группа", $reliz_group, 1, 1);
}



                if ($row["visible"] == "no")
                        tr($tracker_lang['visible'], "<b>".$tracker_lang['no']."</b> (".$tracker_lang['dead'].")", 1);
                if ($moderator)
                        tr($tracker_lang['banned'], ($row["banned"] == 'no' ? $tracker_lang['no'] : $tracker_lang['yes']) );

                if (isset($row["cat_name"]))
                        tr($tracker_lang['type'], $row["cat_name"]);
                else
                        tr($tracker_lang['type'], "(".$tracker_lang['no_choose'].")");

                tr($tracker_lang['seeder'], $tracker_lang['seeder_last_seen']." ".mkprettytime($row["lastseed"]) . " ".$tracker_lang['ago']);
                tr($tracker_lang['size'],mksize($row["size"]) . " (" . number_format($row["size"]) . " ".$tracker_lang['bytes'].")");



                tr($tracker_lang['added'], $row["added"]);
                tr($tracker_lang['views'], $row["views"]);
                //tr($tracker_lang['hits'], $row["hits"]);
                tr($tracker_lang['snatched'], $row["times_completed"] . " ".$tracker_lang['times']);
                $keepget = "";
                $uprow = (isset($row["username"]) ? ("<a href=userdetails.php?id=" . $row["owner"] . ">" . htmlspecialchars($row["username"]) . "</a>") : "<i>Ушедший пользователь</i>");

                tr($tracker_lang['uploaded'], $uprow.'&nbsp;<a href="simpaty.php?action=add&amp;good&amp;targetid=' . $row["owner"] . '&amp;type=torrent' . $id . '&amp;returnto=' . urlencode($_SERVER["REQUEST_URI"]) . '" title="'.$tracker_lang['respect'].'"><img src="pic/thum_good.gif" border="0" alt="'.$tracker_lang['respect'].'" title="'.$tracker_lang['respect'].'" /></a>&nbsp;&nbsp;<a href="simpaty.php?action=add&amp;bad&amp;targetid='.$row["owner"].'&amp;type=torrent' . $id . '&amp;returnto=' . urlencode($_SERVER["REQUEST_URI"]) . '" title="'.$tracker_lang['antirespect'].'"><img src="pic/thum_bad.gif" border="0" alt="'.$tracker_lang['antirespect'].'" title="'.$tracker_lang['antirespect'].'" /></a>', 1);





if(!empty($CURUSER['id'])) {
				$FileList="<div id=\"GetFileList\"><a href=\"#\">Показать список(Кол-во файлов: ".$row["numfiles"].")</a></div><div></div>
					<script language=javascript>
						jQuery('#GetFileList > a').click(function() {
							var FilesContainer = $(this).parent().next();
							var obj = $(this);
							if (!FilesContainer.html().length) {
								obj.text('Loading...');
								jQuery.get(
									'filelist.php',
									{'id':".$id."},
									function (responce) {
										FilesContainer.html(responce);
										obj.text('Скрыть список');
									},
									'html');
							} else {
								obj.text('Показать список');
								FilesContainer.html('');
							}
							return false;
						});
					</script>";
				tr($tracker_lang['files'].': ',$FileList, 1);
}


              



if ($CURUSER && $Functs_Patch["multitracker"] == true){
?>
<script type="text/javascript">
function getmt(tid) {
var det = document.getElementById('mt_'+tid);
if(!det.innerHTML) {
var ajax = new tbdev_ajax();
ajax.onShow ('');
var varsString = "";
ajax.requestFile = "block-details_ajax.php";
ajax.setVar("id", tid);
ajax.setVar("action", "newmulti");
ajax.method = 'POST';
ajax.element = 'mt_'+tid;
ajax.sendAJAX(varsString); 
} else  det.innerHTML = '';
}
</script>
<?
}

if (!isset($_GET["dllist"])) {
echo '<script>
function multion() {
jQuery.post("block-details_ajax.php" , {action:"multion", id:"'.$id.'"}, function(response) {
jQuery("#multion").html(response);
}, "html");
setTimeout("multion();", 120000);
}
setTimeout("multion();", 1000);
</script>';

tr($tracker_lang['thistracker'], "<div id=\"multion\">".$tracker_lang['thistracker']." - ".$tracker_lang['load_and_update']."</div>",1);
} else {
	$downloaders = array();
	$seeders = array();
	$subres = sql_query("SELECT seeder, finishedat, downloadoffset, uploadoffset, peers.ip, port, peers.uploaded, peers.downloaded, to_go, UNIX_TIMESTAMP(started) AS st, connectable, agent, peer_id, UNIX_TIMESTAMP(last_action) AS la, UNIX_TIMESTAMP(prev_action) AS pa, userid, users.username, users.class FROM peers INNER JOIN users ON peers.userid = users.id WHERE torrent = $id") or sqlerr(__FILE__, __LINE__);
	while ($subrow = mysql_fetch_array($subres)) {
		if ($subrow["seeder"] == "yes")
			$seeders[] = $subrow;
		else
			$downloaders[] = $subrow;
	}

	function leech_sort($a, $b) {
		if (isset($_GET["usort"]))
			return seed_sort($a, $b);
		$x = $a["to_go"];
		$y = $b["to_go"];
		if ($x == $y)
			return 0;
		if ($x < $y)
			return -1;
		return 1;
	}

	function seed_sort($a, $b) {
		$x = $a["uploaded"];
		$y = $b["uploaded"];
		if ($x == $y)
			return 0;
		if ($x < $y)
			return 1;
		return -1;
	}

	usort($seeders, "seed_sort");
	usort($downloaders, "leech_sort");

	tr("<a name=\"seeders\">{$tracker_lang['details_seeding']}</a><br /><a href=\"details.php?id=$id$keepget\" class=\"sublink\">[{$tracker_lang['close_list']}]</a>", dltable($tracker_lang['details_seeding'], $seeders, $row), 1);
	tr("<a name=\"leechers\">{$tracker_lang['details_leeching']}</a><br /><a href=\"details.php?id=$id$keepget\" class=\"sublink\">[{$tracker_lang['close_list']}]</a>", dltable($tracker_lang['details_leeching'], $downloaders, $row), 1);
}

if (!empty($CURUSER['id']) && $Functs_Patch["multitracker"] == true && $row["multitracker"] == "yes"){
echo '<script>
function one_mult() {
	jQuery.post("block-details_ajax.php", {action:"multioff", id:"'.$id.'"}, function(data) {
		jQuery("#multioff").html(response);
	}, "html");
}
setTimeout(one_mult, 1000);
function multioff() {
jQuery.post("block-details_ajax.php" , {action:"multioff", id:"'.$id.'"}, function(response) {
jQuery("#multioff").html(response);
}, "html");
setTimeout("multioff();", 180000);
}
setTimeout("multioff();", 1000);
</script>';

tr($tracker_lang['multitracker'], "<div id=\"multioff\">".$tracker_lang['multitracker']." - ".$tracker_lang['load_and_update']."</div>
<a style=\"cursor: pointer;\" onclick=\"getmt('" .$id. "');\">[Обновить список / Закрыть]</a><span id=\"mt_" .$id. "\"></span>
",1);
} else {
echo '<script>
function one_mult() {
	jQuery.post("block-details_ajax.php", {action:"multioff", id:"'.$id.'"}, function(response) {
		jQuery("#multioff").html(response);
	});
}
setTimeout(one_mult, 1000);
function multioff() {
jQuery.post("block-details_ajax.php" , {action:"multioff", id:"'.$id.'"}, function(response) {
jQuery("#multioff").html(response);
}, "html");
setTimeout("multioff();", 180000);
}
setTimeout("multioff();", 1000);
</script>';

tr($tracker_lang['multitracker'], "<div id=\"multioff\">".$tracker_lang['multitracker']." - ".$tracker_lang['load_and_update']."</div>
<span id=\"mt_" .$id. "\"></span>
",1);
}





				if ($row["times_completed"] > 0) {
                    $res = sql_query("SELECT users.id, users.username, users.title, users.uploaded, users.downloaded, users.donor, users.enabled, users.warned, users.last_access, users.class, snatched.startdat, snatched.last_action, snatched.completedat, snatched.seeder, snatched.userid, snatched.uploaded AS sn_up, snatched.downloaded AS sn_dn FROM snatched INNER JOIN users ON snatched.userid = users.id WHERE snatched.finished='yes' AND snatched.torrent =" . sqlesc($id) . " ORDER BY users.class DESC $limit") or sqlerr(__FILE__,__LINE__);
					$snatched_full = "<table width=100% class=main border=1 cellspacing=0 cellpadding=5>\n";
					$snatched_full .= "<tr><td class=colhead>Юзер</td><td class=colhead>Раздал</td><td class=colhead>Скачал</td><td class=colhead>Рейтинг</td><td class=colhead align=center>Начал / Закончил</td><td class=colhead align=center>Действие</td><td class=colhead align=center>Сидирует</td><td class=colhead align=center>ЛС</td></tr>";

					while ($arr = mysql_fetch_assoc($res)) {
						//start Global
						if ($arr["downloaded"] > 0) {
						        $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 2);
								//  $ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
						}
						else if ($arr["uploaded"] > 0)
						$ratio = "Inf.";
						else
						$ratio = "---";
						$uploaded = mksize($arr["uploaded"]);
						$downloaded = mksize($arr["downloaded"]);
						//start torrent
						if ($arr["sn_dn"] > 0) {
								$ratio2 = number_format($arr["sn_up"] / $arr["sn_dn"], 2);
								$ratio2 = "<font color=" . get_ratio_color($ratio2) . ">$ratio2</font>";
						}
						else
							if ($arr["sn_up"] > 0)
								$ratio2 = "Inf.";
							else
								$ratio2 = "---";
						$uploaded2 = mksize($arr["sn_up"]);
						$downloaded2 = mksize($arr["sn_dn"]);
						//end
						//$highlight = $CURUSER["id"] == $arr["id"] ? " bgcolor=#00A527" : "";;
						$snatched_small[] = "<a href=userdetails.php?id=$arr[userid]>".get_user_class_color($arr["class"], $arr["username"])." (<font color=" . get_ratio_color($ratio) . ">$ratio</font>)</a>";
						$snatched_full .= "<tr$highlight><td><a href=userdetails.php?id=$arr[userid]>".get_user_class_color($arr["class"], $arr["username"])."</a>".get_user_icons($arr)."</td><td><nobr>$uploaded&nbsp;Общего<br>$uploaded2&nbsp;Торрент</nobr></td><td><nobr>$downloaded&nbsp;Общего<br>$downloaded2&nbsp;Торрент</nobr></td><td><nobr>$ratio&nbsp;Общего<br>$ratio2&nbsp;Торрент</nobr></td><td align=center><nobr>" . $arr["startdat"] . "<br />" . $arr["completedat"] . "</nobr></td><td align=center><nobr>" . $arr["last_action"] . "</nobr></td><td align=center>" . ($arr["seeder"] == "yes" ? "<b><font color=green>Да</font>" : "<font color=red>Нет</font></b>") .
							"</td><td align=center><a href=message.php?action=sendmessage&amp;receiver=$arr[userid]><img src=$pic_base_url/button_pm.gif border=\"0\"></a></td></tr>\n";
                    }
		            $snatched_full .= "</table>\n";
					?><script language="javascript" type="text/javascript" src="js/show_hide.js"></script><?
					if ($row["seeders"] == 0 || ($row["leechers"] / $row["seeders"] >= 2))
						$reseed_button = "<form action=\"takereseed.php\"><input type=\"hidden\" name=\"torrent\" value=\"$id\" /><input type=\"submit\" value=\"Позвать скачавших\" /></form>";
					if (!$_GET["snatched"]==1)
						tr("Скачавшие<br /><a href=\"details.php?id=$id&amp;snatched=1#snatched\" class=\"sublink\">[Посмотреть список]</a>", '<a href="javascript: show_hide(\'s1\')"><img border="0" src="pic/plus.gif" id="pics1"><div id="ss1" style="display: none;">'.@implode(", ", $snatched_small).$reseed_button.'</div>', 1);
					else
						tr("Скачавшие<br /><a href=\"details.php?id=$id\" class=\"sublink\" name=\"snatched\">[Cпрятать список]</a>", $snatched_full,1);
				}




                        tr($tracker_lang['torrent_info'], "<a href=\"torrent_info.php?id=$id\">".$tracker_lang['show_data']."</a>", 1);


if($CURUSER) {

$torrentid = (int) $_GET["id"];
/*$count_sql = sql_query("SELECT COUNT(*) FROM thanks WHERE torrentid = $torrentid");
$count_row = mysql_fetch_array($count_sql);
$count = $count_row[0];*/

//$thanked_sql = sql_query("SELECT thanks.userid, users.username, users.class FROM thanks INNER JOIN users ON thanks.userid = users.id WHERE thanks.torrentid = $torrentid");
$thanked_sql = sql_query("SELECT thanks.userid, users.username, users.class FROM thanks INNER JOIN users ON thanks.userid = users.id WHERE thanks.torrentid = $torrentid", 86400,"details/details_thanked_id".$id.".txt");
$count = mysql_num_rows($thanked_sql);



if ($count == 0) {
     $thanksby = $tracker_lang['none_yet'];
} else {
     //$thanked_sql = sql_query("SELECT thanks.userid, users.username FROM thanks INNER JOIN users ON thanks.userid = users.id WHERE thanks.torrentid = $torrentid");
     $thanksby = array();
    while ($thanked_row = mysql_fetch_assoc($thanked_sql)) {
          if ($thanked_row["userid"] == $CURUSER["id"])
               $can_not_thanks = true;
          $userid = $thanked_row["userid"];
          $username = $thanked_row["username"];
          $class = $thanked_row["class"];
          $thanksby[] = "<a href=\"userdetails.php?id=$userid\">".get_user_class_color($class, $username)."</a>";
     }
     if ($thanksby)
          $thanksby = implode(', ', $thanksby);
}
if ($row["owner"] == $CURUSER["id"])
     $can_not_thanks = true;
$thanksby = "<div id=\"ajax\"><form action=\"thanks.php\" method=\"post\">
<input type=\"submit\" name=\"submit\" onclick=\"send(); return false;\" value=\"".$tracker_lang['thanks']."\"".($can_not_thanks == true ? " disabled" : "").">
<input type=\"hidden\" name=\"torrentid\" value=\"$torrentid\">".$thanksby."
</form></div>";
?>
<script language="javascript" type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript">
function send() {
     var ajax = new tbdev_ajax();
     ajax.onShow ('');
     var varsString = "";
     ajax.requestFile = "thanks.php";
     ajax.setVar("torrentid", <?=$torrentid;?>);
     ajax.setVar("form_user", <?=$row["owner"];?>);
     ajax.setVar("ajax", "yes");
     ajax.method = 'POST';
     ajax.element = 'ajax';
     ajax.sendAJAX(varsString);
}
</script>
<div id="loading-layer" style="display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000">
     <div style="font-weight:bold" id="loading-layer-text">Загрузка. Пожалуйста, подождите...</div><br />
     <img src="pic/loading.gif" border="0" />
</div>
<?

       tr($tracker_lang['said_thanks'],$thanksby,1);

echo <<<HTML
<style type="text/css">
/*  РЕЙТИНГ (оценка раздач)*/
.ratingblock {
	display:block;
	border-bottom:1px solid #999;
	padding-bottom:8px;
	margin-bottom:8px;
	}
.loading {
	height: 30px;
	background: url(images/working.gif) 50% 50% no-repeat;
	}
.unit-rating { 
	list-style:none;
	margin: 0px;
	padding:0px;
	height: 30px;
	position: relative;
	background: url(pic/starrating.png) top left repeat-x;		
	}
.unit-rating li{
    text-indent: -90000px;
	padding:0px;
	margin:0px;
	float: left;
	}
.unit-rating li a {
	outline: none;
	display:block;
	width:30px;
	height: 30px;
	text-decoration: none;
	text-indent: -9000px;
	z-index: 20;
	position: absolute;
	padding: 0px;
	}
.unit-rating li a:hover{
	background: url(pic/starrating.png) left center;
	z-index: 2;
	left: 0px;
	}
.unit-rating a.r1-unit{left: 0px;}
.unit-rating a.r1-unit:hover{width:30px;}
.unit-rating a.r2-unit{left:30px;}
.unit-rating a.r2-unit:hover{width: 60px;}
.unit-rating a.r3-unit{left: 60px;}
.unit-rating a.r3-unit:hover{width: 90px;}
.unit-rating a.r4-unit{left: 90px;}	
.unit-rating a.r4-unit:hover{width: 120px;}
.unit-rating a.r5-unit{left: 120px;}
.unit-rating a.r5-unit:hover{width: 150px;}
.unit-rating a.r6-unit{left: 150px;}
.unit-rating a.r6-unit:hover{width: 180px;}
.unit-rating a.r7-unit{left: 180px;}
.unit-rating a.r7-unit:hover{width: 210px;}
.unit-rating a.r8-unit{left: 210px;}
.unit-rating a.r8-unit:hover{width: 240px;}
.unit-rating a.r9-unit{left: 240px;}
.unit-rating a.r9-unit:hover{width: 270px;}
.unit-rating a.r10-unit{left: 270px;}
.unit-rating a.r10-unit:hover{width: 300px;}

.unit-rating li.current-rating {
	background: url(images/starrating.gif) left bottom;
	position: absolute;
	height: 30px;
	display: block;
	text-indent: -9000px;
	z-index: 1;
	}
.voted {color:#999;}
.thanks {color:#36AA3D;}
.static {color:#5D3126;}

</style>
HTML;


tr("Личная оценка",rating_bar("".$row['id']."",10),1);








if($CURUSER["class"] < UC_MODERATOR) {
echo <<<HTML
	<script type="text/javascript">
	function FormClick$id()  
	{
	var str = $("#parser_cache").serialize(); 
	$.post("details_cache.php?please", str, function(data)  {
	    $("#result_cache").html(data);
	  });  
	}
	</script>
HTML;
	tr('Метод "Кешу - Кыш"',"<form id=\"parser_cache\" method=\"post\" action=\"details_cache.php?please\"><input type=\"hidden\" name=\"id\" value=\"$id\"><input onclick=\"FormClick".$id."(); return false\" type=\"submit\" value=\"тИЦ\" /></form><br/><small>Если данные обновлены или информация о раздающих в живую устарела-нажмите на кнопку выше</small><div id=\"result_cache\"></div>", 1);
}






$torrentid1 = (int) $_GET["id"];

$freeed_sql = new MySQLCache("SELECT bonustorrent.userid, users.username, users.class FROM bonustorrent INNER JOIN users ON bonustorrent.userid = users.id WHERE bonustorrent.torrentid = $torrentid1", 86400,"details/details_freeed_id".$id.".txt");

if($freeed_sql->num_rows($freeed_sql) < 1){

if (! is_array ( $freeed_sql )) {
	$freecount = $freeed_sql->num_rows ( $freeed_sql );
	WriteArrayStr ( $freecount, $details_cache );
} else
	$freecount = $freeed_sql;


if ($freecount == 0) {
     $freeby1 = $tracker_lang['none_yet'];
} else {
          $freeed_row = $freeed_sql->fetch_assoc($freeed_sql);
          $useridtorr = $freeed_row["userid"];
          $usernametorr = $freeed_row["username"];
          $classtorr = $freeed_row["class"];
          $freeby1 = "<a href=\"id$useridtorr\"><font color=\"Goldenrod1\">".get_user_class_color($classtorr, $usernametorr)."</font></a>";
}
if ($row["owner"] == $CURUSER["id"])
     $can_not_frees = true;


    if($CURUSER["bonus"] >= 5000) {
$freeby = "<font color=\"Goldenrod1\"><div id=\"ajaxtor\"><form action=\"takebonustorr.php\" method=\"post\"><input type=\"hidden\" name=\"torrentid\" value=\"$torrentid1\"><input type=\"submit\" name=\"submit\" onclick=\"sendtor(); return false;\" value=\"".$tracker_lang['bonusadd']."\"".($can_not_frees == true ? " disabled" : "")."></form></div><small>СТОИМОСТЬ УСЛУГИ 5000 БОНУСОВ!!!</small></font></font>";
} else {
$freeby = "<font color=\"Goldenrod1\"><div id=\"ajaxtor\"><input type=\"submit\" name=\"submit\" onclick=\"sendtor(); return false;\" value=\"".$tracker_lang['bonusadd']."\" disabled></div><small>СТОИМОСТЬ УСЛУГИ 5000 БОНУСОВ!!!</small></font></font>";
}

?>
<script language="javascript" type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript">
function sendtor() {
     var ajaxtor = new tbdev_ajax();
     ajaxtor.onShow ('');
     var varsStringtor = "";
     ajaxtor.requestFile = "takebonustorr.php";
     ajaxtor.setVar("torrentid", <?=$torrentid1;?>);
     ajaxtor.setVar("ajax", "yes");
     ajaxtor.method = 'POST';
     ajaxtor.element = 'ajaxtor';
     ajaxtor.sendAJAX(varsStringtor);
}
</script>
<div id="loading-layer" style="display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000">
     <div style="font-weight:bold" id="loading-layer-text">Загрузка. Пожалуйста, подождите...</div><br />
     <img src="pic/loading.gif" border="0" />
</div>
<?
} else {

          $freeed_row = $freeed_sql->fetch_assoc($freeed_sql);
          $useridtorr = $freeed_row["userid"];
          $usernametorr = $freeed_row["username"];
          $classtorr = $freeed_row["class"];
          $freeby1 = "<a href=\"id$useridtorr\"><font color=\"Goldenrod1\">".get_user_class_color($classtorr, $usernametorr)."</font></a>";

$freeby = "<font color=\"Goldenrod1\"><div id=\"ajaxtor\"><input type=\"submit\" name=\"submit\" value=\"".$tracker_lang['bonusadd']."\" disabled>".$freeby." ".$freeby1."</div><small>СТОИМОСТЬ УСЛУГИ 5000 БОНУСОВ!!!</small></font>";

}


       tr($tracker_lang['said_bonusadd'],$freeby,$freeby1,1);


?>
<script language="JavaScript" type="text/javascript">
function karmushka(pid, type, cat, uid) {
var loading = "<img src=\"pic/load.gif\" alt=\"Добавляем..\"  border=0/>";
				  var pid = pid;
					var type = type;
					var cat = cat;
					var uid = uid;
					jQuery("#loading").html(loading);
					$.post('karmushka.php',{'pid':pid , 'type':type , 'cat':cat, 'uid':uid},
					function(response) {
						jQuery("#karmushka").html(response);
						jQuery("#loading").empty();
					}, 'html');
			}		

</script>
<?


##### Настройка #############
$karmushka="".$row['karmushka']."";
$kcat="torrents";// Таблица
$kuid="".(int)$CURUSER['id']."";
#############################

#########Выводим#######################
/*
if ($row["ktotal"] > 0 || $CURUSER['id'] == $row['owner']){
print("<tr><td class=\"rowhead\">Карма</td><td><input type=button $nostyle value=\"+\"> <b><font color=#6f6f6f>$karmushka</font></b> <img src=pic/minus.gif>");
}else{
print("<tr><td class=\"rowhead\">Карма</td><td><div id=\"karmushka\"><load id=loading><input type=button $plustyle  onclick=\"karmushka('$id', '+' , '$kcat', '$kuid');\" value=\"+\"> <b><font color=#6f6f6f>$karmushka</font></b> <img src=pic/minus.gif onclick=\"karmushka('$id', '-' , '$kcat', '$kuid');\" value=\"-\" ></load></div>");
}
print("</td></tr>");
*/

if ($row["ktotal"] > 0 || $CURUSER['id'] == $row['owner']){
print("<tr><td class=\"rowhead\">Карма</td><td><img src=pic/plus.gif> <b><font color=#6f6f6f>$karmushka</font></b> <img src=pic/minus.gif></td></tr>");
}else{
print("<tr><td class=\"rowhead\">Карма</td><td><div id=\"karmushka\"><load id=loading><img onclick=\"karmushka('$id', '+' , '$kcat', '$kuid');\" src=pic/plus.gif> <b><font color=#6f6f6f>$karmushka</font></b> <img src=pic/minus.gif onclick=\"karmushka('$id', '-' , '$kcat', '$kuid');\"></load></div></td></tr>");
}

echo '<script>
function adjective_ax() {
jQuery.post("block-details_ajax.php" , {action:"simular", id:"'.$id.'"}, function(response) {
jQuery("#adjective_ax").html(response);
}, "html");
setTimeout("adjective_ax();", 180000);
}
adjective_ax();
</script>';


tr($tracker_lang['match_files'], "<div id=\"adjective_ax\">".$tracker_lang['load_and_update']."</div>",1);



                print("</table></p>\n");

}










        } else {
                stdhead($tracker_lang['comments_for']." \"" . $row["name"] . "\"");
                print("<h1>".$tracker_lang['comments_for']." <a href=details.php?id=$id>" . $row["name"] . "</a></h1>\n");
        }

}

if(!empty($CURUSER['id'])) {
	if($CURUSER['id'] != $row['owner']) {
		$docheck = mysql_fetch_array(sql_query("SELECT COUNT(*) FROM checkcomm WHERE checkid = " . $id . " AND userid = " . $CURUSER["id"] . " AND torrent = 1"));
		if($docheck[0] <= 0) {
			$check = "check";
			$check_name = "Подписаться на комментарии";
		} else {
			$check = "checkoff";
			$check_name = "Отписаться от комментарий";
		}
	}
		$link_comment = ($CURUSER['id'] != $row['owner']) ?"<div align=right><a href=comment.php?action=$check&tid=$id>$check_name</a></div>" : "";
}

if(!empty($CURUSER['username'])) {
        print("<p><a name=\"startcomments\"></a></p>\n");
        $subres = sql_query("SELECT COUNT(*) FROM comments WHERE torrent = $id");
        $subrow = mysql_fetch_array($subres);
        $count = $subrow[0];
        $limited = 10;
if(!$count) {
  print("<img src='images/5e4fa2944c.gif'>".$link_comment."<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
  print("<tr><td class=colhead align=\"left\" colspan=\"2\">");
  print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
  print("<div align=\"right\"><a href=#comments class=altlink_white>Добавить комментарий</a></div>");
  print("</td></tr><tr><td align=\"center\">");
  print("Комментариев нет. <a href=#comments>Желаете добавить?</a>");
  print("</td></tr></table><br>");

  print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
  print("<tr><td class=colhead align=\"left\" colspan=\"2\"> <a name=comments>&nbsp;</a><b>:: Без комментариев</b></td></tr>");
  print("<tr><td align=\"center\" >");
  print("<form name=comment method=\"post\" action=\"comment.php?action=add\">");
  print("<div>");
  textbbcode("comment","text","");
  print("</div>");


  print("<input type=\"hidden\" name=\"tid\" value=\"$id\"/>");
  print("<input type=\"submit\" class=btn value=\"Разместить комментарий\" />");
  print("</td></tr></form></table>");

        } else {
                list($pagertop, $pagerbottom, $limit) = pager($limited, $count, "details.php?id=$id&", array("lastpagedefault" => 1));
                $subres = sql_query("SELECT c.id, c.ip, c.text, c.user, c.added, c.editedby, c.editedat, u.avatar, u.warned, ".
                  "u.username, u.title, u.class, u.donor, u.downloaded, u.uploaded, u.gender, u.last_access, u.ustatus, e.username AS editedbyname, u.awards FROM comments AS c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN users AS e ON c.editedby = e.id LEFT JOIN comments_parsed AS cp ON cp.cid = c.id WHERE torrent = " .
                  "$id ORDER BY c.id $limit") or sqlerr(__FILE__, __LINE__);
                $allrows = array();
                while ($subrow = mysql_fetch_array($subres))
                        $allrows[] = $subrow;

if (get_user_class() >= UC_SYSTEM) { 
	if($row['comment_lock'] == 'no') { 
		$zii = "<a class=altlink href=?id=$id&lock_comments=".($row['comment_lock'] == 'no' ? "yes>" : "no>")."<input type=button value='Блокировать комментарии'/></a> <input type=button value='Разблокировать комментарии' disabled/>"; 
	} else {
		$zii = "<input type=button value='Блокировать комментарии' disabled/> <a class=altlink href=?id=$id&lock_comments=".($row['comment_lock'] == 'no' ? "yes>" : "no>")."<input type=button value='Разблокировать комментарии'/></a>"; 
	} 
	print("<br/><table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">"); 
	print("<tr><td class=colhead align=\"left\" colspan=\"2\">"); 
	print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Работа с комментарием </div>"); 
	print("</td></tr><tr><td align=\"center\">");
	print $zii; 
	print("</td></tr></table><br/>"); 
} 

if ($row['comment_lock'] == 'no') {
         print("<div align=right>".$link_comment."<table class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >");
         print("<tr><td class=\"colhead\" align=\"center\" >");
         print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
         print(isset($CURUSER['username']) ? "<div align=\"right\"><a href=#comments class=altlink_white>Добавить комментарий</a></div>" : "");
         print("</td></tr>");
         print("<tr><td colspan=\"2\">");
         print($pagertop);
         print("</td></tr>");
         print("<tr><td>");
                 commenttable($allrows);
         print("</td></tr>");
         print("<tr><td colspan=\"2\">");
         print($pagerbottom);
         print("</td></tr>");
         print("</table></div>");

} 

	if($row['comment_lock'] == 'no') {
		if(isset($CURUSER['username']) || $CURUSER['comm'] == 'yes') {
			print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
			print("<form name=comment method=\"post\" action=\"comment.php?action=add\">");
			print("<tr><td class=colhead align=\"left\" colspan=\"2\">  <a name=comments>&nbsp;</a><b>:: Добавить комментарий к торренту</b></td></tr>");
			print("<tr><td width=\"100%\" align=\"center\">");
			print("<center><table border=\"0\"><tr><td class=\"clear\">");
			print("<div align=\"center\">". textbbcode("comment","text","", 1) ."</div>");
			print("</td></tr></table></center>");
			print("<input type=\"hidden\" name=\"tid\" value=\"$id\"/>");
			print("<input type=\"submit\" class=btn value=\"Разместить комментарий\" />");
			print("</td></tr></form></table>");
		} else {
			print("У вас нет возможности комментировать");
		}

	} else {
		stdmsg("", "<center><font color=red><b>Комментарии заблокированы администрацией сайта!</b></font></center>"); 
	}
}

} else {
echo <<<HTML
<tr><td colspan="2">
<!-- Put this script tag to the <head> of your page -->
<script type="text/javascript" src="//vk.com/js/api/openapi.js?111"></script>

<script type="text/javascript">
  VK.init({apiId: 4307454, onlyWidgets: true});
</script>

<!-- Put this div tag to the place, where the Comments block will be -->
<center><div id="vk_comments"></div></center>
<script type="text/javascript">
VK.Widgets.Comments("vk_comments", {limit: 20, width: "800px", attach: "*"});
</script>
</td></tr>
HTML;
}
        //} else {
        //        print("</table></p>\n");
	//}



stdfoot();

?>