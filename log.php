<?

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

  require_once("include/bittorrent.php");
  dbconn(false);

  loggedinorreturn();

// delete items older than a week
  $secs = 7 * 86400;
  stdhead("Логи");
  $type = htmlspecialchars((string)$_GET["type"]);
   if(!$type || $type == 'simp') $type = "tracker";
 	print("<p align=center>"  .
		($type == tracker || !$type ? "<b>Трекер</b>" : "<a href=log.php?type=tracker>Трекер</a>") . " | " .
 		($type == bans ? "<b>Баны</b>" : "<a href=log.php?type=bans>Баны</a>") . " | " .
 		($type == release ? "<b>Релизы</b>" : "<a href=log.php?type=release>Релизы</a>") . " | " .
 		($type == exchange ? "<b>Обменник</b>" : "<a href=log.php?type=exchange>Обменник</a>") . " | " .
		($type == torrent ? "<b>Торренты</b>" : "<a href=log.php?type=torrent>Торренты</a>") . " | " .
		($type == error ? "<b>Ошибки</b>" : "<a href=log.php?type=error>Ошибки</a>") . "</p>") . " | " .
		($type == bonus ? "<b>Обменник бонусов</b>" : "<a href=log.php?type=bonus>Обменник бонусов</a></p>\n");

   if (($type == 'speed' || $type == 'error') && $CURUSER['class'] < 4) {
	stdmsg("Ошибка","Доступ в этот раздел закрыт.");
	stdfoot();
	die();
}

   if (($type == 'bonus' || $type == 'error') && $CURUSER['class'] < 12) {
	stdmsg("Ошибка","Доступ в этот раздел закрыт.");
	stdfoot();
	die();
}

  sql_query("DELETE FROM sitelog WHERE " . gmtime() . " - UNIX_TIMESTAMP(added) > $secs") or sqlerr(__FILE__, __LINE__);
  $limit = ($type == 'announce' ? "LIMIT 1000" : "");
  $res = sql_query("SELECT txt, added, color FROM `sitelog` WHERE type = ".sqlesc($type)." ORDER BY `added` DESC $limit") or sqlerr(__FILE__, __LINE__);
  print("<h1>Логи</h1>\n");
  if (mysql_num_rows($res) == 0)
    print("<b>Лог файл пустой</b>\n");
  else
  {
    print("<table border=1 cellspacing=0 cellpadding=5>\n");
    print("<tr><td class=colhead align=left>Дата</td><td class=colhead align=left>Время</td><td class=colhead align=left>Событие</td></tr>\n");
    while ($arr = mysql_fetch_assoc($res))
    {
      $date = substr($arr['added'], 0, strpos($arr['added'], " "));
      $time = substr($arr['added'], strpos($arr['added'], " ") + 1);
      print("<tr style=\"background-color: $arr[color]\"><td>$date</td><td>$time</td><td align=left>".view_saves($arr['txt'])."</td></tr>\n");
    }
    print("</table>");
  }
  stdfoot();
?>