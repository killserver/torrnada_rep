<?php
########################################
#         Мод Фильмы онлайн            #
#          Writed by EDX               #
########################################


require "include/bittorrent.php";
dbconn();

$id = intval($_GET["id"]);
if (!isset($id) || !$id)
	die();


$resursy = sql_query("SELECT *, (SELECT id FROM users WHERE id = owner) as id_uploader, (SELECT username FROM users WHERE id = owner) as username, (SELECT class FROM users WHERE id = owner) as class, (SELECT name FROM catvideo WHERE id = category) as cat FROM videoblog WHERE id = $id") or sqlerr();
$row = mysql_fetch_array($resursy);

if($row['category']==18) {
	$row["image1"] = "images/wz1jri7nzosy.png";
}

	if (!isset($_GET["page"])) {


$imya_filima = $row["name"];
$link = $row["video"];

if($row["ispol"] <> NULL){
$ispol = $row["ispol"]. " - ";
} else {
$ispol = "";
}




 stdhead("Смотреть онлайн  " . $ispol.$row["name"] . "");

begin_frame("Смотреть онлайн: " .$ispol.$row["name"]. " [".$row["cat"]."]");

print("<div align=\"center\"><img src=\"" .$row["image1"]. "\" alt=\"".$row["cat"]."\" title=\"".$row["cat"]."\"></div>");

print("<br>");

print("<hr>");




print("<div align=\"center\">".format_comment($row["ori_descr"])."</div>");

print("<br>");
print("<br>");




print("<hr>");

print("<br>");

//$background = "http://localhost/pic/logo/torrents_logo.png"; // Тут ссыль к изображению

//print("<div name=\"film1\" class=\"film1\" id=\"film1\" align=\"center\">");
//if(!empty($row["video"]) || preg_match("/youtu/i", $row['youtube'])) {

if(preg_match("/vk.com/", $row['video']) || preg_match("/(.*).flv/", $row['video']) || preg_match("/(.*).mp4/", $row['video']) || !empty($row['youtube'])){
$delete_array = array(
"video_ext.php?", "http://vk.com/",
);
$row['videos'] = str_replace($delete_array, "", $row['video']);
$row['videos'] = str_replace("&", "&amp;", $row['videos']);
$row['videos'] = (!preg_match("/sd/", $row['videos']) ? (preg_match("/hd=/", $row['videos']) ? $row['videos'] : $row['videos']."&amp;hd=1") : $row['videos']."&amp;sd");
$row['videos'] = "{$DEFAULTBASEURL}/vk_video/".str_replace("amp;", "", str_replace("=", "", str_replace("&", "", str_replace("&amp;", "-", $row['videos'])))).".flv";

if(preg_match("/(.*).flv/", $row['video'])){
unset($row['videos']);
$row['videos'] = preg_replace("/(.*)http:\/\/(.+?).flv(.*)/", "http://$2.flv", $row['video']);
}

$provider = "video";

if(preg_match("/(.*).mp4/", $row['video'])){
unset($row['videos']);
$row['videos'] = preg_replace("/(.*)http:\/\/(.+?).mp4(.*)/", "http://$2.mp4", $row['video']);
}

if(!empty($row['youtube'])){
$provider = "youtube";
$row['videos'] = (!preg_match("/http/", $row['youtube']) ? "http://www.youtube.com/v/".$row['youtube']."?version=3&amp;hl=ru_RU&amp;rel=0" : $row['youtube']);
}

$link = <<<HTML
<script type="text/javascript" src="{$DEFAULTBASEURL}/flash/player.js"></script>
<script type="text/javascript" src="{$DEFAULTBASEURL}/flash/swfobject.js"></script>
<script type="text/javascript">
var killer = new SWFObject("{$DEFAULTBASEURL}/flash/player_killer.swf","mpl","680","560","9");
 killer.addParam('allowscriptaccess','always');
 killer.addParam("allowfullscreen","true");
 killer.addParam("quality", "autolow");
 killer.addVariable("file","{$row['videos']}");
 killer.addVariable("title","{$row["name"]}");
 killer.addVariable("image","{$row['image1']}");
 killer.addVariable("provider","{$provider}");
 killer.addVariable("width","680");
 killer.addVariable("height","560");
 killer.addVariable("volume","100");
 killer.addVariable("smoothing","false");
 killer.addVariable("streamer","http");
 killer.addVariable("bitrate","200");
 killer.addVariable("abouttext","online");
 killer.addVariable("aboutlink","http://online-killer.com/");
 killer.write("mediaplayer");
</script>
HTML;
}

print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\"><br><br>\n");

echo "<center>".
(preg_match("/vk.com/", $row['video']) || preg_match("/(.*).flv/", $row['video']) || preg_match("/(.*).mp4/", $row['video']) || !empty($row['youtube']) ? "\n<div id=\"mediaplayer\"></div>\n$link\n" : "<object data=\"$link\" frameborder=\"0\" height=\"500\" width=\"700\"></object>").
"</center>";
//} elseif(!empty($row['youtube'])) {
		//tr("Видео", '<object width="460" height="750"><param name="movie" value="http://www.youtube.com/v/'.$row['youtube'].'?version=3&amp;hl=ru_RU&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$row['youtube'].'?version=3&amp;hl=ru_RU&amp;rel=0" type="application/x-shockwave-flash" width="660" height="480" allowscriptaccess="always" allowfullscreen="true"></embed></object>', 1);
//}
		
		
		
		//print("\n        </center>");





}





if(preg_match("/Серия/i", $row['name']) || preg_match("/серия/i", $row['name']) || preg_match("/сезон/i", $row['name'])){
echo "<center>\n";
$others_video = preg_replace("/(.*) ([0-9]+) Серия(.*)/i", "$1", $row['name']);
$others_video = preg_replace("/(.*) ([0-9]+) серия(.*)/i", "$1", $others_video);
$others_video = preg_replace("/(.*) ((.*))/i", "$1", $others_video);
$row['names'] = addslashes($row['name']);
$others_video = addslashes($others_video);
$others_row = sql_query("SELECT id, name FROM videoblog WHERE name LIKE \"%".$others_video."%\" AND name <> \"".$row['names']."\" ORDER BY added ASC") or sqlerr(__FILE__, __LINE__);
if(@mysql_num_rows($others_row) > 0){
echo "<br /><center><strong>Остальные серии</strong></center>";
//Начало вывода
while($others = mysql_fetch_array($others_row)){
echo "<a href=\"{$DEFAULTBASEURL}/details_of_movie.php?id=".$others['id']."\">".$others['name']."</a><br />";
}
//Конец вывода
}
echo "</center>\n";
}



print("<h3 align=center>Информация</h2>");
print("<table width=100% border=1 cellspacing=0 cellpadding=10 align=center><tr><td>");

print('<table  valign="top" cellpadding=\"5\" align="center">');

print("<tr><td width=\"15%\">&nbsp;<b>Название:</b></td><td align=\"right\" width=\"70%\" >".$ispol.$row[name]."&nbsp;</td></tr>");

print("<tr><td width=\"15%\" >&nbsp;<b>Дата публикации:</b></td><td align=\"right\" width=\"70%\" >".$row[added]."&nbsp;</td></tr>");

print("<tr><td width=\"15%\">&nbsp;<b>Добавил:</b></td><td align=\"right\" width=\"70%\" ><a href=\"/id".$row["id"]."\">".$row["username"]."</a>&nbsp;</td></tr>");

print("<tr><td width=\"15%\">&nbsp;<b>Просмотров:</b></td><td align=\"right\" width=\"70%\" >".$row[views]."&nbsp;</td></tr>");

print("</table></table>");
print("<div class='pluso pluso-theme-color pluso-round' style='background:#eaeaea;'><div class='pluso-more-container'><a class='pluso-more' href=''></a><ul class='pluso-counter-container'><li></li><li class='pluso-counter'></li><li></li></ul></div><a class='pluso-facebook'></a><a class='pluso-twitter'></a><a class='pluso-vkontakte'></a><a class='pluso-odnoklassniki'></a><a class='pluso-google'></a><a class='pluso-livejournal'></a><a class='pluso-moimir'></a><a class='pluso-liveinternet'></a></div>
<script type='text/javascript'>if(!window.pluso){pluso={version:'0.9.2',url:'http://share.pluso.ru/'};h=document.getElementsByTagName('head')[0];l=document.createElement('link');l.href=pluso.url+'pluso.css';l.type='text/css';l.rel='stylesheet';s=document.createElement('script');s.charset='UTF-8';s.src=pluso.url+'pluso.js';h.appendChild(l);h.appendChild(s)}</script>");

end_frame();


if(get_user_class() > UC_VIP){

print("<br><center><a href=\"edit_movie.php?m=edit&id=$id\" id=\"edit_movie\"><img src=\"pic/edit.gif\" border=\"0\"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"delete_movie.php?m=delete&id=$id\"><img src=\"pic/remove.png\" border=\"0\"></a></center><br>");

}


if(!empty($CURUSER['username'])) {
   $commentbar = "<p align=center><a href=commentsf.php?action=add&amp;tid=$id>Добавить комментарий</a>&nbsp;&nbsp;&nbsp;&nbsp;$check</p>\n";
 	$subres = mysql_query("SELECT COUNT(*) FROM comments WHERE video = $id");

	$subrow = mysql_fetch_array($subres);

	$count = $subrow[0];

    if (!$count) {

		print("<center><h3>Нет коментариев</h2></center>\n");
 	}

	else {

		list($pagertop, $pagerbottom, $limit) = pager(20, $count, "details_of_movie.php?id=$id&", array(lastpagedefault => 1));

 		$subres = mysql_query("SELECT comments.id, text, user, comments.added, editedby, editedat, avatar, warned, ".
                  "username, title, class, info, donor, last_access FROM comments LEFT JOIN users ON comments.user = users.id WHERE video = " .
                  "$id ORDER BY comments.id $limit") or sqlerr(__FILE__, __LINE__);

		$allrows = array();
		while ($subrow = mysql_fetch_array($subres))
			$allrows[] = $subrow;
         print("<table class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >");
         print("<tr><td class=\"colhead\" align=\"center\" >");
         print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
         print("<div align=\"right\"><a href=#comments class=altlink_white>Добавить комментарий</a></div>");
         print("</td></tr>");

         print("<tr><td>");
         print($pagertop);
         print("</td></tr>");
         print("<tr><td>");
                 commenttable($allrows, "add_movie_comment");
         print("</td></tr>");
         print("<tr><td>");
         print($pagerbottom);
         print("</td></tr>");
         print("</table>");

	}

  print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
  print("<tr><td class=colhead align=\"left\" colspan=\"2\">  <a name=comments>&nbsp;</a><b>:: Добавить комментарий к фильму</b></td></tr>");
  print("<tr><td width=\"100%\" align=\"center\" >");
  //print("Ваше имя: ");
  //print("".$CURUSER['username']."<p>");
  print("<form name=comment method=\"post\" action=\"add_movie_comment.php?action=add\">");
  print("<center><table border=\"0\"><tr><td class=\"clear\">");
  print("<div align=\"center\">". textbbcode("comment","text","" ) ."</div>");
  print("</td></tr><tr><td  align=\"center\" colspan=\"2\">");
  print("<input type=\"hidden\" name=\"tid\" value=\"$id\"/>");
  print("<input type=\"submit\" class=btn value=\"Разместить комментарий\" />");
  print("</td></tr></form>");
print("</table></table>");
} else {
echo <<<HTML
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
HTML;
}


print("<center><h3><a href=\"movies_online.php\"><u>Назад к фильмам</u></a></h1></center>\n");


mysql_query("UPDATE videoblog SET views=views+1 WHERE id=$id") or sqlerr(__FILE__, __LINE__);


?>


<?

stdfoot();

?>