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

require "include/bittorrent.php";

dbconn();

//loggedinorreturn();

stdhead("Коментарии");

begin_main_frame();

if (get_user_class() < UC_MODERATOR) 
stderr("Error", "Ну и куда нас занесло?"); 

$limit = 30;

$res = mysql_query("SELECT * FROM comments ORDER BY id DESC LIMIT $limit") or sqlerr(__FILE__, __LINE__); 
print("<h1>Просмотр сообщений</h1>\n"); 
  print("<table border=1 cellspacing=0 cellpadding=5>\n"); 
  print("<tr><td class=colhead align=left>От кого</td><td class=colhead align=left>Торрент</td><td class=colhead>ID торрента</td><td class=colhead align=left>Комментарий</td></tr>\n"); 
  while ($arr = mysql_fetch_assoc($res)) 
  { 
    //$res1 = mysql_query("SELECT name FROM torrents WHERE id=" . $arr["torrent"]) or sqlerr(); 
    //$arr1 = mysql_fetch_assoc($res1); 

    $torr1 = mysql_query("SELECT name FROM torrents WHERE id=" . $arr["torrent"]) or sqlerr(); 
    $torr1 = mysql_fetch_assoc($torr1); 
if ($arr['torrent'] <> 0) {
    $receiver = "<a href=details.php?id=" . $arr["torrent"] . "><b>" .$torr1[name]. "</b></a>"; 
} else {
    $receiver = "<a href=futurerldetails.php?id=" . $arr["trailer"] . "><b>" .$torr1[name]. "</b></a>"; 
}

 $res4 = mysql_query("SELECT torrent FROM comments WHERE id=" . $arr["id"]) or sqlerr(); 
    $arr4 = mysql_fetch_assoc($res4); 
if ($arr['torrent'] > 0) {
    $idreceiver = "<a href=details.php?id=" . $arr4["torrent"] . "><b>" . $arr4["torrent"] . "</b></a>"; 
} elseif ($arr['video'] > 0) {
    $idreceiver = "<a href=details_of_movie.php?id=" . $arr["video"] . "><b>Видео блог №" . $arr["video"] . "</b></a>"; 
} else {
    $idreceiver = "<a href=futurerldetails.php?id=" . $arr["video"] . "><b>Трейлер №" . $arr["video"] . "</b></a>"; 
}

    $res2 = mysql_query("SELECT torrent FROM comments WHERE id=" . $arr["torrent"]) or sqlerr(); 
    $arr2 = mysql_fetch_assoc($res2); 
    $res3 = mysql_query("SELECT username FROM users WHERE id=" . $arr["user"]) or sqlerr(); 
    $arr3 = mysql_fetch_assoc($res3); 
    $sender = "<a href=userdetails.php?id=" . $arr["user"] . "><b>" . $arr3["username"] . "</b></a>"; 
    $msg = format_comment($arr["text"]); 
  print("<tr><td>$sender</td><td>$receiver</td><td>$idreceiver</td><td align=left>$msg</td></tr>\n"); 
  } 
  print("</table>"); 

 end_frame(); 

stdfoot();
?>