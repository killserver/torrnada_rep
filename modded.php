<?php

require "include/bittorrent.php";
dbconn(false);
loggedinorreturn();

if (get_user_class() < UC_MODERATOR)
  stderr($tracker_lang['error'], "Нет доступа.");

stdhead("Обзор проверки торрентов");

print "<div align=\"center\" style=\"padding: 10px;\"><a href=\"modded.php\">Непроверенные</a> | <a href=\"modded.php?modded\">Проверенные</a></div>";

if (isset($_GET["modded"])) {




$resi = mysql_query("SELECT count(moderated) FROM torrents WHERE status='3'") or sqlerr(__FILE__, __LINE__); 
$arra = mysql_fetch_row($resi); 
$count = $arra[0];

$limit = "30";

  //$counts = get_row_count("torrents", "WHERE moderated='yes'");
  list($pagertop, $pagerbottom, $limit) = pager($limit, $count, "modded.php?modded&");

  if ($count)
  {
    echo $pagertop;
  }

  begin_frame("Проверенные торренты [".$count."]");
  echo '<table width="100%" cellpadding="5"><tr><td class="colhead">Торрент</td><td class="colhead">Загрузил</td><td class="colhead">Проверил</td></tr>';
  $res = sql_query("SELECT torrents.*, users.username, users.class FROM torrents LEFT JOIN users ON torrents.owner = users.id WHERE torrents.status = '3'  ORDER BY torrents.added DESC ".$limit)  or sqlerr(__FILE__,__LINE__);
  if (!mysql_num_rows($res))
      echo ("<tr><td colspan=\"4\">Нет проверенных торрентов</td></tr>");
  else
  {
    while ($row = mysql_fetch_array($res)){

      echo '<tr><td><a href="details.php?id='.$row["id"].'">'.view_saves($row["name"]).'</a></td><td><a href="userdetails.php?id='.$row["owner"].'">'.get_user_class_color($row["class"], $row["username"]).'</a></td><td><a href="userdetails.php?id='.$row["moderatedby"].'">'.$row["moderatedby"].'</a></td></tr>';
}

/*
$moderatedby = mysql_query("SELECT users.username, users.class FROM torrents LEFT JOIN users ON torrents.moderatedby = users.id WHERE torrents.moderated = 'yes'  ORDER BY torrents.added DESC $limit");
while ($moderatedbys = mysql_fetch_array($moderatedby)){

echo '<td><a href="userdetails.php?id='.$row["moderatedby"].'">'.get_user_class_color($moderatedbys["class"], $moderatedbys["username"]).'</a></td></tr>';

}
*/

//echo '</tr>';

  }

  if ($count)
  {
    echo '<tr><td colspan="4">';
    echo $pagerbottom;
    echo '</td></tr>';
  }
  echo '</tr></table>';
  end_frame();
} elseif (isset($_GET["moderator"])) {
  $moderaror = unesc($_GET["moderator"]);
  //$count = number_format(get_row_count("torrents", "WHERE moderatedby = ".sqlesc($moderaror)));

$resi = mysql_query("SELECT SUM(moderated) FROM torrents WHERE moderatedby = ".sqlesc($moderaror)) or sqlerr(__FILE__, __LINE__); 
$arra = mysql_fetch_row($resi); 
$count = $arra[0];

  list($pagertop, $pagerbottom, $limit) = pager(15, $count, "modded.php?moderator=".unesc($_GET["moderator"])."&");
  //$res = sql_query("SELECT users.id, users.username, users.class, torrents.moderatedby FROM users LEFT JOIN torrents ON users.id = torrents.moderatedby  WHERE torrents.moderatedby = ".sqlesc($moderaror))  or sqlerr(__FILE__,__LINE__);
  $res = sql_query("SELECT id, username, class, (SELECT moderatedby FROM torrents WHERE moderatedby=id) as moderatedby FROM users WHERE moderatedby = ".sqlesc($moderaror))  or sqlerr(__FILE__,__LINE__);
  $row = mysql_fetch_array($res);
  begin_frame("Торренты, проверенные <a href=\"userdetails.php?id=".$row["id"]."\">".get_user_class_color($row["class"], $row["username"])."</a> [".$count."]");
  echo '<table width="100%" cellpadding="5"><tr><td class="colhead">Торрент</td><td class="colhead">Загрузил</td><td class="colhead">Проверен</td></tr>';
  //$res = sql_query("SELECT torrents.*, users.username, users.class FROM torrents LEFT JOIN users ON torrents.owner = users.id  WHERE moderatedby = ".sqlesc($moderaror)." ORDER BY torrents.modtime DESC")  or sqlerr(__FILE__,__LINE__);
  $res = sql_query("SELECT id, name, owner, modtime, (SELECT username FROM users WHERE id=owner) AS username, (SELECT class FROM users WHERE id=owner) AS class FROM torrents WHERE moderatedby = ".sqlesc($moderaror)." ORDER BY modtime DESC")  or sqlerr(__FILE__,__LINE__);
  if (!mysql_num_rows($res) || empty($moderaror))
      echo ("<tr><td colspan=\"4\">Не проверено ни одного торрента этим модератором</td></tr>");
  else
  {
    while ($row = mysql_fetch_array($res))
      echo '<tr><td><a href="details.php?id='.$row["id"].'">'.view_saves($row["name"]).'</a></td><td><a href="userdetails.php?id='.$row["owner"].'">'.get_user_class_color($row["class"], $row["username"]).'</a></td><td>'.$row["modtime"].'</td></tr>';
  }

  if ($count)
  {
    echo '<tr><td colspan="4">';
    echo $pagerbottom;
    echo '</td></tr>';
  }
  echo '</tr></table>';
  end_frame();
} else {
  //$count = number_format(get_row_count("torrents", "WHERE moderated='no'"));

$limit = "30";

$resi = mysql_query("SELECT count(moderated) FROM torrents WHERE status=''") or sqlerr(__FILE__, __LINE__); 
$arra = mysql_fetch_row($resi); 
$count = $arra[0];
  list($pagertop, $pagerbottom, $limit) = pager($limit, $count, "modded.php?");
  begin_frame("Непроверенные торренты [".$count."]");
  echo '<table width="100%" cellpadding="5"><tr><td class="colhead">Торрент</td><td class="colhead">Загрузил</td><td class="colhead">Когда?</td></tr>';



  if ($count)
  {
    echo '<tr><td colspan="3">';
print($pagertop);
    echo '</td></tr>';
  }



  $res = sql_query("SELECT owner, id, name, added, (SELECT username FROM users WHERE id=owner) AS username, (SELECT class FROM users WHERE id=owner) AS class FROM torrents WHERE status = '' ORDER BY id ".$limit)  or sqlerr(__FILE__,__LINE__);
  if (!mysql_num_rows($res))
      echo ("<tr><td colspan=\"3\">Все торренты проверены</td></tr>");
  else
  {
    while ($row = mysql_fetch_array($res))
      echo '<tr><td><a href="details.php?id='.$row["id"].'">'.view_saves($row["name"]).'</a></td><td><a href="userdetails.php?id='.$row["owner"].'">'.get_user_class_color($row["class"], $row["username"]).'</a></td><td>'.$row["added"].'</td></tr>';
  }

  if ($count)
  {
    echo '<tr><td colspan="3">';
print($pagerbottom);
    echo '</td></tr>';
  }
  echo '</tr></table>';
  end_frame();
}

stdfoot();

?>
