<?php

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

# IMPORTANT: Do not edit below unless you know what you are doing!
if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

function untier($announces) {
	$list = array();
	foreach((array) $announces as $tier) {
		is_array($tier) ? $list = array_merge($list, untier($tier)) : array_push($list, $tier);
	}
	return $list;
}

function magnet($row, $html = false) {
global $announce_urls, $CURUSER, $DEFAULTBASEURL;

	$announce_urls_list = array();
	if(!empty($CURUSER['passkey'])) {
		$announce_urls_list[] = $DEFAULTBASEURL."/announce.php?passkey=".$CURUSER['passkey'];
	} else {
		$announce_urls_list[] = $DEFAULTBASEURL."/announce.php?passkey=03010d6c4f6a1ae7ffcdf07c587abea3";
	}
	$announce_urls_list[] = "http://corbinaretracker.dyndns.org:80/announce.php";
	$announce_urls_list[] = "http://retracker.slvnet.ru/announce.php";
	$announce_urls_list[] = "http://tracker.prq.to/announce";
	$announce_urls_list[] = "http://tracker.publicbt.com/announce";
	$announce_urls_list[] = "http://bt.rutor.org:2710/announce";
	$announce_urls_list[] = "http://denis.stalker.h3q.com:6969/announce";
	$announce_urls_list[] = "http://tracker2.torrentino.com/announce";
	$announce_urls_list[] = "http://tracker.openbittorrent.com/announce";

	$ampersand = $html ? '&amp;' : '&';
	return sprintf('magnet:?xt=urn:btih:%2$s%1$sdn=%3$s%1$sxl=%4$d%1$str=%5$s', $ampersand, $row['info_hash'], rawurlencode($row['save_as']), $row['size'], implode($ampersand .'tr=', untier(array_merge($announce_urls_list, $announce_urls))));
}

function torrenttable_st($res, $variant = "index") {
		global $pic_base_url, $CURUSER, $use_wait, $use_ttl, $ttl_days, $tracker_lang;


?>
<script type="text/javascript" src="js/wz_tooltip.js"></script>
<?

  if ($use_wait)
  if (($CURUSER["class"] < UC_VIP) && $CURUSER) {
		  $gigs = $CURUSER["uploaded"] / (1024*1024*1024);
		  $ratio = (($CURUSER["downloaded"] > 0) ? ($CURUSER["uploaded"] / $CURUSER["downloaded"]) : 0);
		  if ($ratio < 0.5 || $gigs < 5) $wait = 48;
		  elseif ($ratio < 0.65 || $gigs < 6.5) $wait = 24;
		  elseif ($ratio < 0.8 || $gigs < 8) $wait = 12;
		  elseif ($ratio < 0.95 || $gigs < 9.5) $wait = 6;
		  else $wait = 0;
  }

print("<tr>\n");

// sorting by MarkoStamcar

$count_get = 0;

foreach ($_GET as $get_name => $get_value) {
	$get_name = mysql_escape_string(strip_tags(str_replace(array("\"","'"),array("",""),$get_name)));
	$get_value = mysql_escape_string(strip_tags(str_replace(array("\"","'"),array("",""),$get_value)));
	if ($get_name != "sort" && $get_name != "type") {
		if ($count_get > 0)
			$oldlink = $oldlink . "&" . $get_name . "=" . $get_value;
		else
			$oldlink = $oldlink . $get_name . "=" . $get_value;
		$count_get++;
	}
}

if ($count_get > 0)
	$oldlink = $oldlink . "&";

if ($_GET['sort'] == "1") {
if ($_GET['type'] == "desc") {
$link1 = "asc";
} else {
$link1 = "desc";
}
}

if ($_GET['sort'] == "2") {
if ($_GET['type'] == "desc") {
$link2 = "asc";
} else {
$link2 = "desc";
}
}

if ($_GET['sort'] == "3") {
if ($_GET['type'] == "desc") {
$link3 = "asc";
} else {
$link3 = "desc";
}
}

if ($_GET['sort'] == "4") {
if ($_GET['type'] == "desc") {
$link4 = "asc";
} else {
$link4 = "desc";
}
}

if ($_GET['sort'] == "5") {
if ($_GET['type'] == "desc") {
$link5 = "asc";
} else {
$link5 = "desc";
}
}

if ($_GET['sort'] == "7") {
if ($_GET['type'] == "desc") {
$link7 = "asc";
} else {
$link7 = "desc";
}
}

if ($_GET['sort'] == "8") {
if ($_GET['type'] == "desc") {
$link8 = "asc";
} else {
$link8 = "desc";
}
}

if ($_GET['sort'] == "9") {
if ($_GET['type'] == "desc") {
$link9 = "asc";
} else {
$link9 = "desc";
}
}

if ($_GET['sort'] == "10") {
if ($_GET['type'] == "desc") {
$link10 = "asc";
} else {
$link10 = "desc";
}
}

if ($link1 == "") { $link1 = "asc"; } // for torrent name
if ($link2 == "") { $link2 = "desc"; }
if ($link3 == "") { $link3 = "desc"; }
if ($link4 == "") { $link4 = "desc"; }
if ($link5 == "") { $link5 = "desc"; }
if ($link7 == "") { $link7 = "desc"; }
if ($link8 == "") { $link8 = "desc"; }
if ($link9 == "") { $link9 = "desc"; }
if ($link10 == "") { $link10 = "desc"; }

?>
<td class="colhead" align="center"><?php echo $tracker_lang['image'];?></td>
<td class="colhead" align="left"><a href="browse.php?<?php print $oldlink; ?>sort=1&type=<?php print $link1; ?>" class="altlink_white"><?php echo $tracker_lang['name'];?></a> / <a href="browse.php?<?php print $oldlink; ?>sort=4&type=<?php print $link4; ?>" class="altlink_white"><?php echo $tracker_lang['added'];?></a></td>
<?php
if ($wait)
	print("<td class=\"colhead\" align=\"center\">".$tracker_lang['wait']."</td>\n");

if ($variant == "mytorrents")
	print("<td class=\"colhead\" align=\"center\">".$tracker_lang['visible']."</td>\n");


?>
<td class="colhead" align="center"><a href="browse.php?<?php print $oldlink; ?>sort=2&type=<?php print $link2; ?>" class="altlink_white"><center><?php echo $tracker_lang['files'];?></center></a></td>
<td class="colhead" align="center"><a href="browse.php?<?php print $oldlink; ?>sort=3&type=<?php print $link3; ?>" class="altlink_white"><center><?php echo $tracker_lang['comments'];?></center></a></td>
<?php if ($use_ttl) {
?>
	<td class="colhead" align="center"><?php echo $tracker_lang['ttl'];?></td>
<?php
}
?>
<td class="colhead" align="center"><a href="browse.php?<?php print $oldlink; ?>sort=5&type=<?php print $link5; ?>" class="altlink_white"><?php echo $tracker_lang['size'];?></a></td>
<td class="colhead" align="center"><a href="browse.php?<?php print $oldlink; ?>sort=7&type=<?php print $link7; ?>" class="altlink_white"><?php echo $tracker_lang['seeds'];?></a>|<a href="browse.php?<?php print $oldlink; ?>sort=8&type=<?php print $link8; ?>" class="altlink_white"><?php echo $tracker_lang['leechers'];?></a></td>
<?php

if ($variant == "index" || $variant == "bookmarks")
	print("<td class=\"colhead\" align=\"center\"><a href=\"browse.php?{$oldlink}sort=9&type={$link9}\" class=\"altlink_white\">".$tracker_lang['uploadeder']."</a></td>\n");

if ((get_user_class() >= UC_MODERATOR) && $variant == "index")
	print("<td class=\"colhead\" align=\"center\"><a href=\"browse.php?{$oldlink}sort=10&type={$link10}\" class=\"altlink_white\">Изменен</td>");


if ($variant == "bookmarks")
	print("<td class=\"colhead\" align=\"center\">".$tracker_lang['delete']."</td>\n");

print("</tr>\n");

print("<tbody id=\"highlighted\">");

	if ($variant == "bookmarks")
		print ("<form method=\"post\" action=\"takedelbookmark.php\">");

	while ($row = mysql_fetch_assoc($res)) {
$row['name']=view_saves($row['name']);
	$day_added = $row['addtime'];
$day_show = ($day_added);
$thisdate = date('Y-m-d',$day_show);
 
if($thisdate==$prevdate){
$cleandate = '';
 
}else{
$day_added = '  '.date('l d M', ($row['addtime'])); 
$cleandate = "<tr><td colspan=15 class=colhead>Торренты за $day_added</td></tr>\n";
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
 
foreach($dag as $eng => $rus){
    $cleandate = str_replace($eng.'day', $rus.'',$cleandate);
}
if ($row["sticky"] == "no")
if(!$_GET['sort'] && !$_GET['d']){
   echo $cleandate."\n";
}


		$id = $row["id"];
		print("<tr".($row["sticky"] == "yes" ? " class=\"highlight\"" : "").">\n");

		print("<td align=\"center\" style=\"padding: 0px\">");


if(!empty($row["image1"]) || !empty($row["image6"])) { 
   if($row["image1"]) {
	$p = $row["image1"];
   } else {
	$p = $row["image6"];
   }
   $img_tor = (preg_match("/http/", $p) ? $p : "./torrents/images/".$p); 
}


		if (isset($img_tor)) {
			print("<img border=\"0\" width='100' height='100' src=\"".$img_tor."\" alt=\"" . $row["cat_name"] . "\" />");
		} else {
			print("-");
		}






		print("</td>\n");

		$dispname = $row["name"];

switch ($row['free']) {
	case 'no':
	    	$thisisfree  = "";
   	 	break;
	case 'silver':
	    	$thisisfree  = "<img src=\"pic/silverdownload.gif\" title=\"".$tracker_lang['silver']."\" alt=\"".$tracker_lang['silver']."\">";
 	   	break;
	case 'gold':
 	   	$thisisfree  = "<img src=\"pic/freedownload.gif\" title=\"".$tracker_lang['golden']."\" alt=\"".$tracker_lang['golden']."\">";
 	   	break;
}
		//print("<td align=\"left\">".($row["sticky"] == "yes" ? "Важный: " : "")."<a href=\"details.php?");


print("<td align=\"left\">  
        ".($row["sticky"] == "yes" ? "Важный : " : "")."<a href=\"details.php?");

		if ($variant == "mytorrents")
			print("returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;");
		print("id=".$id."");
		if ($variant == "index" || $variant == "bookmarks")
			print("&amp;hit=1");
		print("\">");


		if($row["status"] == "0"){
$tstatus = "";
}
if($row["status"] == "1"){
$tstatus = "<b title=\"проверяется\"><span style=\"color: #0000FF;\">%</span></b>&nbsp;";
}
if($row["status"] == "2"){
$tstatus = "<b title=\"закрыто\"><span style=\"color: #CC3333;\">x</span></b>&nbsp;";
}
if($row["status"] == "3"){
$tstatus = "<b title=\"проверено\"><span style=\"color: green;\">&radic;</span></b>&nbsp;";
}
if($row["status"] == "4"){
$tstatus = "<b title=\"недооформлено\"><span style=\"color: #CC3333;\">?</span></b>&nbsp;";
}
if($row["status"] == "5"){
$tstatus = "<b title=\"неоформлено\"><span style=\"color: #CC3333;\">!</span></b>&nbsp;";
}
if($row["status"] == "6"){
$tstatus = "<b title=\"повтор\"><span style=\"color: #0000FF;\">D</span></b>&nbsp;";
}
if($row["status"] == "7"){
$tstatus = "<b title=\"закрыто правообладателем\"><span style=\"color: #A52A2A;\">&copy;</span></b>&nbsp;";
}
if($row["status"] == "8"){
$tstatus = "<b title=\"поглощено\"><span style=\"color: #996600;\">?</span></b>&nbsp;";
}

print(($row["new"] == "yes" ? "<img src=\"pic/new.png\" title=\"Новинка\" alt=\"Новое\"> " : "")."");
print("<b>$dispname</b></a> $thisisfree $tstatus\n");

			if ($variant != "bookmarks" && $CURUSER)
				print("<a href=\"bookmark.php?torrent=$row[id]\"><img border=\"0\" src=\"pic/bookmark.gif\" alt=\"".$tracker_lang['bookmark_this']."\" title=\"".$tracker_lang['bookmark_this']."\" /></a>\n");

			/*$s = true;
			if($row["status"] == "2") {
				$s = false;
			} elseif($row["status"] == "5") {
				$s = false;
			} elseif($row["status"] == "6") {
				$s = false;
			} elseif($row["status"] == "7") {
				$s = false;
			} elseif($row["status"] == "8") {
				$s = false;
			}
			if($s) {
				print("<a href=\"download.php?id=".$id."\"><img src=\"pic/download.gif\" border=\"0\" alt=\"".$tracker_lang['download']."\" title=\"".$tracker_lang['download']."\"></a>\n".(!empty($CURUSER['username']) ? "<a href=\"".magnet($row)."\"><img src=\"/pic/magnet.gif\" border=\"0\" alt=\"Примагнититься\" title=\"Примагнититься\"></a>\n" : ""));
			}*/
		if ($CURUSER["id"] == $row["owner"] || get_user_class() >= UC_MODERATOR)
			$owned = 1;
		else
			$owned = 0;

				if ($owned)
			print("<a href=\"edit.php?id=$row[id]\"><img border=\"0\" src=\"pic/pen.gif\" alt=\"".$tracker_lang['edit']."\" title=\"".$tracker_lang['edit']."\" /></a>\n");

			   if ($row["readtorrent"] == 0 && $variant == "index")
				   print ("<b><font color=\"red\" size=\"1\">[новый]</font></b>");

			print("<br /><i>".$row["added"]."</i>");

								if ($wait)
								{
								  $elapsed = floor((gmtime() - strtotime($row["added"])) / 3600);
				if ($elapsed < $wait)
				{
				  $color = dechex(floor(127*($wait - $elapsed)/48 + 128)*65536);
				  print("<td align=\"center\"><nobr><a href=\"faq.php#dl8\"><font color=\"$color\">" . number_format($wait - $elapsed) . " h</font></a></nobr></td>\n");
				}
				else
				  print("<td align=\"center\"><nobr>".$tracker_lang['no']."</nobr></td>\n");
		}

	print("</td>\n");

		if ($variant == "mytorrents") {
			print("<td align=\"right\">");
			if ($row["visible"] == "no")
				print("<font color=\"red\"><b>".$tracker_lang['no']."</b></font>");
			else
				print("<font color=\"green\">".$tracker_lang['yes']."</font>");
			print("</td>\n");
		}

		//if ($row["type"] == "single")
			//print("<td align=\"right\">" . $row["numfiles"] . "</td>\n");
		//else {
			if ($variant == "index")
				print("<td align=\"center\"><b><a href=\"details.php?id=".$id."&amp;hit=1&amp;filelist=1\">" . $row["numfiles"] . "</a></b></td>\n");
			else
				print("<td align=\"center\"><b><a href=\"details.php?id=".$id."&amp;filelist=1#filelist\">" . $row["numfiles"] . "</a></b></td>\n");
		//}

		if (!$row["comments"])
			print("<td align=\"center\">" . $row["comments"] . "</td>\n");
		else {
			if ($variant == "index")
				print("<td align=\"center\"><b><a href=\"details.php?id=".$id."&amp;hit=1&amp;tocomm=1\">" . $row["comments"] . "</a></b></td>\n");
			else
				print("<td align=\"center\"><b><a href=\"details.php?id=".$id."&amp;page=0#startcomments\">" . $row["comments"] . "</a></b></td>\n");
		}

				$ttl = ($ttl_days*24) - floor((gmtime() - sql_timestamp_to_unix_timestamp($row["added"])) / 3600);
				if ($ttl == 1) $ttl .= " час"; else $ttl .= "&nbsp;часов";
		if ($use_ttl)
			print("<td align=\"center\">".$ttl."</td>\n");
		print("<td align=\"center\">" . str_replace(" ", "<br />", mksize($row["size"])) . "</td>\n");

		print("<td align=\"center\">");

		if ($row["seeders"]) {
			if ($variant == "index")
			{
			   if ($row["leechers"]) $ratio = $row["seeders"] / $row["leechers"]; else $ratio = 1;
				print("<b><a href=\"details.php?id=".$id."&amp;hit=1&amp;toseeders=1\"><font color=" .
				  get_slr_color($ratio) . ">" . $row["seeders"] + $row["f_seeders"]."</font></a></b>\n");
			}
			else
				print("<b><a class=\"" . linkcolor($row["seeders"]) . "\" href=\"details.php?id=".$id."&amp;dllist=1#seeders\">" .
				  $row["seeders"] . " + ".$row['f_seeders']."</a></b>\n");
		}elseif ($row["f_seeders"]) {
			print("<span class=\"" . get_slr_color($ratio) . "\">" . $row["seeders"] + $row['f_seeders'] . "</span>");
}
		else
			print("<span class=\"" . linkcolor($row["seeders"]) . "\">" . $row["seeders"] + $row['f_seeders'] . "</span>");

		print(" | ");

		if ($row["leechers"]) {
			if ($variant == "index")
				print("<b><a href=\"details.php?id=".$id."&amp;hit=1&amp;todlers=1\">" .
				   number_format($row["leechers"]) . ($peerlink ? "</a>" : "") .
				   "</b>\n");
			else
				print("<b><a class=\"" . linkcolor($row["leechers"]) . "\" href=\"details.php?id=".$id."&amp;dllist=1#leechers\">" .
				  $row["leechers"] . " + " . $row['f_leechers'] . "</a></b>\n");
		}
		else
			print("0\n");

		print("</td>");

		if ($variant == "index" || $variant == "bookmarks")
			print("<td align=\"center\">" . (isset($row["username"]) ? ("<a href=\"userdetails.php?id=" . $row["owner"] . "\"><b>" . get_user_class_color($row["class"], htmlspecialchars_uni($row["username"])) . "</b></a>") : "<i>(unknown)</i>") . "</td>\n");

		if ($variant == "bookmarks")
			print ("<td align=\"center\"><input type=\"checkbox\" name=\"delbookmark[]\" value=\"" . $row['bookmarkid'] . "\" /></td>");

		if ((get_user_class() >= UC_MODERATOR) && $variant == "index") {
			if (empty($row["status"]) || $row['status'] == "0")
				print("<td align=\"center\"><font color=\"red\"><b>Нет</b></font></td>\n");
			else
				print("<td align=\"center\"><a href=\"userdetails.php?id=".$row['moderatedby']."\"><font color=\"green\"><b>Да</b></font></a></td>\n");
		}

		//if ((get_user_class() >= UC_MODERATOR) && $variant == "index")
			//print("<td align=\"center\"><input type=\"checkbox\" name=\"delete[]\" value=\"" . $id . "\" /></td>\n");

	print("</tr>\n");

	}

	print("</tbody>");

	if ($variant == "index" && $CURUSER)
		print("<tr><td class=\"colhead\" colspan=\"12\" align=\"center\"><a href=\"markread.php\" class=\"altlink_white\">Все торренты прочитаны</a></td></tr>");

	//print("</table>\n");

/*
	if ($variant == "index") {
		if (get_user_class() >= UC_MODERATOR) {
			print("<tr><td align=\"right\" colspan=\"12\"><input type=\"submit\" value=\"Удалить\"></td></tr>\n");
		}
	}
*/

	if ($variant == "bookmarks")
		print("<tr><td colspan=\"12\" align=\"right\"><input type=\"submit\" value=\"".$tracker_lang['delete']."\"></td></tr>\n");

/*
	if ($variant == "index" || $variant == "bookmarks") {
		//if (get_user_class() >= UC_MODERATOR) {
			//print("</form>\n");
		//}
	}
*/

	return $rows;
}

?>