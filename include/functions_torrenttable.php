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










function torrenttable($res, $variant = "index") {
	global $pic_base_url, $CURUSER, $use_wait, $use_ttl, $ttl_days, $tracker_lang;

?>
<link rel="stylesheet" href="highslide/highslide.css" type="text/css" media="screen" /> 
<script type="text/javascript" src="js/wz_tooltip.js"></script>
<script type="text/javascript" src="highslide/highslide.js"></script> 
<script type="text/javascript" src="js/ajax.js"></script> 
<script type="text/javascript"> 
function getDetails(tid) { 
   var det = document.getElementById('details_'+tid); 
   var td = document.getElementById('td_'+tid); 
   if(!det.innerHTML) 
   { 
     td.style.display=''; 
     var ajax = new tbdev_ajax(); 
     ajax.onShow (''); 
     var varsString = ""; 
     ajax.requestFile = "getTorrentDetails.php"; 
     ajax.setVar("tid", tid); 
     ajax.method = 'POST'; 
     ajax.element = 'details_'+tid; 
     ajax.sendAJAX(varsString); 
   } 
   else { 
     td.style.display='none'; 
     det.innerHTML = ''; 
     } 
} 
</script> 
<div id="loading-layer" style="display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000">
     <div style="font-weight:bold" id="loading-layer-text">Загрузка. Пожалуйста, подождите...</div><br />
     <img src="pic/loading.gif" border="0" />
</div>
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
if ($count_get > 0) {
$oldlink = $oldlink . "&" . $get_name . "=" . $get_value;
} else {
$oldlink = $oldlink . $get_name . "=" . $get_value;
}
$count_get++;
}

}

if ($count_get > 0) {
$oldlink = $oldlink . "&";
}


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
/*
if ($link1 == "") { $link1 = "asc"; } // for torrent name
if ($link2 == "") { $link2 = "desc"; }
if ($link3 == "") { $link3 = "desc"; }
if ($link4 == "") { $link4 = "desc"; }
if ($link5 == "") { $link5 = "desc"; }
if ($link9 == "") { $link9 = "desc"; }
if ($link10 == "") { $link10 = "desc"; }
*/
if ($link1 == "") { $link1 = "asc"; } // for torrent name
if ($link2 == "") { $link2 = "desc"; }
if ($link3 == "") { $link3 = "desc"; }
if ($link4 == "") { $link4 = "desc"; }
if ($link5 == "") { $link5 = "desc"; }
if ($link7 == "") { $link7 = "desc"; }
if ($link8 == "") { $link8 = "desc"; }
if ($link9 == "") { $link9 = "desc"; }
if ($link10 == "") { $link10 = "desc"; }


print("</tr>\n");

while ($row = mysql_fetch_assoc($res)) {
$row['name']=view_saves($row['name']);
	$id = $row["id"];
	$details_link .= "id=".$id;
        $descr=$row["descr"];
        if (strlen($descr) > 420)
        $descr = substr($descr, 0, 420) . "...";

             
print("<tr><td colspan='2' class=\"colhead\" align=\"center\">"); 

if ($variant != "bookmarks" && $CURUSER){
    ?>
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script language="JavaScript" type="text/javascript">
jQuery.noConflict();
    (function($){
        bookmark = function(id){
            $.post("bookmark.php",{"id":id},function(response){
                alert(response);
            });
        }
    })(jQuery);
    </script>
<!--
    <a href="javascript:bookmark('<?=$row['id']?>')">
        <img border="0" src="pic/bookmark.gif" align="right" alt="<?=$tracker_lang['bookmark_this']?>" title="<?=$tracker_lang['bookmark_this']?>" />
    </a>
-->
    <?
}

if ($row["free"] == 'yes') {
echo "<img src=pic/freedownload.gif border=0 title=\"".$tracker_lang['golden']."\" alt=\"".$tracker_lang['golden']."\"> - ";				
	}

		print(($row["sticky"] == "yes" ? "Важный: " : "")."");

		print(($row["new"] == "yes" ? "<img src=\"pic/new.png\" title=\"Новинка\" alt=\"Новое\"> " : "")."");
echo "<a href=\"details.php?id=".$id."\"><b><font size=\"2\" color=\"black\">$row[name]</font></b></a>";
$torentdate = get_date_time(($row["addtime"])); 
$torentdate = date("d-m-Y", strtotime($torentdate)); 
	if ($row["free"] > '0')
		//$thisisfree = ($row[free]=="yes" ? "<img src=\"pic/freedownload.gif\" title=\"".$tracker_lang['golden']."\" alt=\"".$tracker_lang['golden']."\">" : "");

//$thisisfree = ($row[free]=="yes" ? "<img src=\"pic/freedownload.gif\" title=\"".$tracker_lang['golden']."\" alt=\"".$tracker_lang['golden']."\">" : "");

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

	//print("<img src=\"pic/percent/".$row[free]."small.png\" width=\"50\" title=\"Download на ".$row['free']."% не учитывается!\" align=\"left\" border=\"0\">");
	print("&nbsp;&nbsp;&nbsp;Добавлен: <i>".$torentdate."</i>");
// 	print("Добавлен: <i>".nicetime($row["added"])."</i></br></br> "); // вывод даты в правильном формате


			if ($variant != "bookmarks" && $CURUSER)
				print("<a href=\"bookmark.php?torrent=$row[id]\"><img border=\"0\" src=\"pic/bookmark.gif\" /></a>\n");
print("</tr>"); 

/*
if ((get_user_class() >= UC_MODERATOR) && $variant == "index"){
	echo "&nbsp; <a href=\"edit.php?id=$row[id]\" target=\"_blank\"><img border=\"0\" src=\"pic/pen.gif\" alt=\"".$tracker_lang['edit']."\" title=\"".$tracker_lang['edit']."\" /></a>";
	}
*/

if ($row["banned"] == "yes"){
	echo "&nbsp; <a href=\"details.php?id=$row[id]\"><img border=\"0\" src=\"pic/warned16.gif\" title=\"Раздача заблокирована\" alt=\"Заблокирован\"></a>";
}
/*
if ($row[free]=='100'){
echo "&nbsp; <img src=\"pic/freedownload.gif\" title=\"".$tracker_lang['golden']."\" alt=\"".$tracker_lang['golden']."\">";			
	}
if ($row["moderated"] == "no"){
	echo "&nbsp; <font color=\"orange\"><b>[Не проверен]</b></font>";
}
*/

// Выделение новых торрентов в browse.php
/*
if (checkNewTorrent($row["id"], $row["added"]) && $CURUSER[id] && $variant == "index") 
	print ("&nbsp; <img src=\"pic/new.png\" title=\"новая раздача\" alt=\"новая раздача\">". $diff); 
print("</td>"); 
*/
/*
if ($variant == "bookmarks")
	print("<td width=\"50\" align=\"center\" class=\"colhead\">".$tracker_lang['delete']."</td>\n");
print("</tr>"); 
*/



        $img_tor = (preg_match("/http:/",$row["image1"]) ? $row["image1"] : "./torrents/images/" .$row["image1"]); 
if ($row["image1"])
																					     	   
	//print("<tr><td valign=\"top\" align=\"center\" width=\"135\" style=\"padding: 0px\"><a href=\"torrents/images/$row[image1]\" class=\"highslide\" onclick=\"return hs.expand(this)\"><img  border=\"0\" title=\"Увеличить картинку\" src=\"torrents/images/".$row["image1"]."\" width=\"132\" height=\"176\"></a> ");		// Скриншоты и постеры в Highslide
print("<tr><td valign=\"top\" align=\"center\" width=\"135\" style=\"padding: 0px\"><a href=\"details.php?id=".$row['id']."\" class=\"highslide\" onclick=\"return hs.expand(this)\"><img  border=\"0\" class=\"glossy\" title=\"".$row['name']."\" src=\"torrents/images/".$row["image1"]."\" width=\"132\" height=\"176\"></a> ");

     else
	print("<tr><td valign=\"top\" align=\"center\" width=\"135\" style=\"padding: 0px\"><img src='pic/noimage.gif'  border='0' class=\"glossy\" title='Постер не загружен' width=\"132\" height=\"176\"> ");



print("</td>");

echo "<td><a href=\"browse.php?cat=" . $row["category"] . "\"><img src=\"pic/cats/".$row["cat_pic"]."\" class='glossy'  title=\"Категория: ".$row['cat_name']."\" align=\"right\" border=\"0\" ></a>";

print(" ".format_comment($descr)." <p>");

   	print("--------------------------<br>");


	//print("<div style=\"float: left; width: auto;\"><a href=\"browse.php?".$oldlink."sort=7&type=".$link7."\"><font color=black>Раздают:</font></a> <a href=details.php?id=".$id."&amp;dllist=1#seeders><font color=green><b>" . $row["seeders"] . "+".$row[f_seeders]."</b></font></a> &nbsp;/&nbsp; <a href=\"browse.php?".$oldlink."sort=8&type=".$link8."\"><font color=black>Качают:</font></a> <b><a class=\"" . linkcolor($row["leechers"]) . "\" href=\"details.php?id=".$id."&amp;dllist=1#leechers\"><font color=red>$row[leechers] + ".$row[f_leechers]."</font></a></b></div>");

$seeders = $row['seeders'] + $row['f_seeders'];
$leechers = $row['leechers'] + $row['f_leechers'];

	print("<div style=\"float: left; width: auto;\"><a href=\"browse.php?".$oldlink."sort=7&type=".$link7."\"><font color=black>Раздают:</font></a> <a href=details.php?id=".$id."&amp;dllist=1#seeders><font color=green><b>" . $seeders ."</b></font></a> &nbsp;/&nbsp; <a href=\"browse.php?".$oldlink."sort=8&type=".$link8."\"><font color=black>Качают:</font></a> <b><a class=\"" . linkcolor($row["leechers"]) . "\" href=\"details.php?id=".$id."&amp;dllist=1#leechers\"><font color=red>".$leechers."</font></a></b></div>");

?>

<?

		if ($row["moderated"] == "no"){
			$downl = "";
		}
	//print("<div align=\"right\">[<a href=\"details.php?id=".$id."\" title=\"$row[name]\" target=\"_blank\">Подробнее</a> $downl]&nbsp;</div> ");

print("</td>\n");




if ($variant == "bookmarks")
	print ("<td align=\"center\"><form method=\"post\" action=\"takedelbookmark.php\"><input type=\"checkbox\" name=\"delbookmark[]\" value=\"" . $row['bookmarkid'] . "\" /></td>");
//print ("<form method=\"post\" action=\"takedelbookmark.php\">");

     	print("</tr> <tr><td id=\"td_".$id."\" colspan=\"3\" style=\"display:none;\"><span id=\"details_" .$id. "\"></span></td></tr>");
}

	if ($variant == "bookmarks")
		print("<tr><td colspan=\"3\" style=\"border: 0px;\" align=\"right\"><input type=\"submit\" value=\"".$tracker_lang['delete']." выбранные\"></td></tr>\n");

	if ($variant == "index" || $variant == "bookmarks") {
		if (get_user_class() >= UC_MODERATOR) {
			print("</form>\n");
		}
	}










	return $rows;









/*
	if ($variant == "index" || $variant == "bookmarks") {
		if (get_user_class() >= UC_MODERATOR) {
			print("</form>\n");
		}
	}

	return $rows;
*/
}

