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

if (get_user_class() < UC_MODERATOR) 
stderr("Error", "Ну и куда нас занесло?"); 

//loggedinorreturn();

stdhead("Коментарии");

begin_main_frame();

$limit = 30;

$res = mysql_query("SELECT *, (SELECT name FROM torrents WHERE id=comments.torrent) AS torrname, (SELECT username FROM users WHERE id = comments.user) AS username_torr FROM comments ORDER BY id DESC LIMIT ".$limit) or sqlerr(__FILE__, __LINE__); 
print("<h1>Просмотр сообщений</h1>\n"); 
  print("<table border=1 cellspacing=0 cellpadding=5>\n"); 
  print("<tr><td class=colhead align=left>От кого</td><td class=colhead align=left>Торрент</td><td class=colhead>ID торрента</td><td class=colhead align=left>Комментарий</td></tr>\n"); 
  while($arr = mysql_fetch_assoc($res)) 
  {
    if($arr['torrent']<>0) {
        $receiver = "<a href=details.php?id=" . $arr["torrent"] . "><b>" .view_saves($arr['torrname']). "</b></a>"; 
    } else {
        $receiver = "<a href=futurerldetails.php?id=" . $arr["trailer"] . "><b>" .view_saves($arr['torrname']). "</b></a>"; 
    } 
    if($arr['torrent']>0) {
        $idreceiver = "<a href=details.php?id=" . $arr["torrent"] . "><b>" . $arr["torrent"] . "</b></a>"; 
    } elseif($arr['video']>0) {
        $idreceiver = "<a href=details_of_movie.php?id=" . $arr["video"] . "><b>Видео блог №" . $arr["video"] . "</b></a>"; 
    } else {
        $idreceiver = "<a href=futurerldetails.php?id=" . $arr["video"] . "><b>Трейлер №" . $arr["video"] . "</b></a>"; 
    }

    $sender = "<a href=userdetails.php?id=" . $arr["user"] . "><b>" . $arr["username_torr"] . "</b></a>"; 
    $msg = format_comment($arr["text"]); 
    print("<tr><td>".$sender."</td><td>".$receiver."</td><td>".$idreceiver."</td><td align=\"left\">".$msg."</td></tr>\n"); 
  } 
  print("</table>"); 
 end_frame(); 

stdfoot();
?>