<?php 

if (!defined('BLOCK_FILE')) { 
header("Location: ../index.php"); 
exit; 
} 



$cacheStatFile = "include/cache/realises/block-realises_" . intval($_GET['page']).".txt";
$expire = 10*60; // 20 минут
if (file_exists($cacheStatFile) && filesize($cacheStatFile)<>0 && filemtime($cacheStatFile) > (time() - $expire)) {
   $content.=file_get_contents($cacheStatFile);
} else {


$where = "category <> 46 and f_seeders+seeders <> 0  and banned = 'no' and torrents.visible = 'yes' and torrents.ban = 'no'";

$res1 = sql_query("SELECT COUNT(*) FROM torrents WHERE ".$where);
$row1 = mysql_fetch_array($res1);
$content .= "<table cellspacing=\"0\" cellpadding=\"5\" width=\"100%\"><tr><td id=\"centerCcolumn\">";
    include "include/codecs.php";
    $perpage = 10;
    list($pagertop, $pagerbottom, $limit) = pager($perpage, $row1[0], $_SERVER["PHP_SELF"] . "?" );
    $res = sql_query("SELECT torrents.id, torrents.name, torrents.descr, torrents.free, torrents.image1, torrents.image6, torrents.size, torrents.times_completed, torrents.comments, torrents.owner, categories.id AS catid, categories.name AS catname, categories.image AS catimage, users.username, torrents.new, (torrents.f_seeders + torrents.seeders) as seeders, (torrents.f_leechers + torrents.leechers) as leechers, users.class as ownerclass FROM torrents LEFT JOIN users ON torrents.owner = users.id LEFT JOIN categories ON torrents.category = categories.id  WHERE ".$where." ORDER BY torrents.added DESC ".$limit) or sqlerr(__FILE__, __LINE__);
//and category <> 19
    $content .= $pagertop;
    $content .= "</td></tr>";
    while ($release = mysql_fetch_array($res)) {
        $catid = $release["catid"];
        $catname = $release["catname"];
        $catimage = $release["catimage"]; 
                $torname = $release["name"];
		$torname=view_saves($torname);

                $tornew = $release["new"] == "yes" ? "<img src=pic/new.png>" : "";

        $descr=$release["descr"];

$uprow = (isset($release["username"]) ? ("<a href=id" . $release["owner"] . ">" . get_user_class_color($release['ownerclass'], $release["username"]) . "</a>") : "<i>Этого 'кто-то' уже нет с нами<img src='pic/smilies/sad.gif'></i>");

        if (strlen($descr) > 300)
            $descr = substr($descr, 0, 300) . "...";
        $sss = '';

if(!empty($release['image1']) || !empty($release['image6']))
$img1 = "<a href=details-$release[id]><img width=\"160\" border='0'  class=\"glossy\" src=\"torrents/images/".(!empty($release['image1']) ? $release['image1'] : $release['image6'])."\" /></a>";
else
$img1 = "<a href=details-$release[id]><img width=\"160\" border='0'  class=\"glossy\" src=\"pic/no_poster.png\" /></a>";
        $content .= "<tr><td>";
        $content .= "<table width=\"100%\" class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">";
        $content .= "<tr>";
        $content .= "<td class=\"colhead\" colspan=\"2\" align=center><p align=left><a href=\"details-$release[id]\" style=\"color: white;\">";
                 if ($release["free"] == 'yes')
                    $content .= "<img src=pic/freedownload.gif border=0 /> - ";


        $content .= "".$tornew." ".htmlspecialchars($torname)."</a>";
        $content .= "</td>";
        $content .= "</tr>";

                  //if ($release["image1"] != "")  
//$img1 = "<div class=\"glossy\" style=\"border: 3px dashed #CCC;  background: #f7f7f7; margin: 0 0 1px 0; padding: 3px; class=more-cont\"><a href=\"details-$release[id]\" alt=\"$release[name]\" title=\"$release[name]\"><img src='thumb2.php?w=160&q=80&file=$release[image1]' class=\"glossy\"  alt=\"$release[name]\" title=\"$release[name]\" /></a>";  

        $content .= "<tr valign=\"top\"><td align=\"center\" width=\"160\">";
            $content .= "$img1";
//$content .= "</div>";
        $content .= "</td>";


        $content .= "<td><div align=\"left\">".(!empty($catname) ? "<a href=\"browse.php?cat=$catid\">
            <img src=\"pic/cats/$catimage\" alt=\"$catname\" title=\"$catname\" align=\"right\" border=\"0\" /></a>" : "")."
            ".format_comment($descr)." <br>
            <br><div style=\"border: 3px dashed #ddd;  background: #f7f7f7; margin: 0 0 5px 0; padding: 3px; class=more-cont\">
            <b style='color: #00b;'>Раздал: </b>$uprow<br>
            <b style='color: #0b9;'>Размер: </b>".mksize($release["size"])."<br>
            <b style='color: #0a0;'>Раздают: </b>".$release["seeders"]." <br>
            <b style='color: #f00;'>Качают: </b>".$release["leechers"]." <br>
            <b style='color: #C6B;'>Скачиваний: </b>$release[times_completed]  <br>
 <b style='color: #b00;'>Комментарии: </b>$release[comments]</b><br>
            </div>
            <br>
                     
            </div>

<div align=\"right\"> [<a href=\"details-$release[id]\" alt=\"$release[name]\" title=\"$release[name]\"><b>Подробнее...<img src=pic/next.gif></b></a>]</div></td>";
        $content .= "</tr>";
        $content .= "</table>";
        $content .= "</td></tr>";
    }
    $content .= "<tr><td>";
    $content .= $pagerbottom;
    $content .= "</td></tr>";
$content .= "</table>";


$fp = fopen($cacheStatFile,"w");
   if($fp)
   {
    fputs($fp, $content);
    fclose($fp);
   }
 }



?>