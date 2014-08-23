<?php

require_once("include/bittorrent.php");
dbconn(false);
header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && $_SERVER["REQUEST_METHOD"] == 'GET') {
    $q = trim(strip_tags(iconv('utf-8', $tracker_lang['language_charset'], base64_decode($_GET["q"]))));
    if (empty($q) || strlen($q) < 3) {
      die();
    }
    $res = sql_query("SELECT t.id, t.name, t.added, t.free, t.category, c.name AS cat_name, c.image AS cat_pic FROM torrents t LEFT JOIN categories c ON t.category = c.id WHERE t.name LIKE " . sqlesc("%$q%") . " ORDER BY id DESC LIMIT 0,10;") or sqlerr(__FILE__, __LINE__);
    print("<div style=\"position:absolute;width:100%;\">\n");
    print("<table width=\"100%\">");
    print("<tr><td class=\"colhead\" colspan=\"2\" style=\"padding:5px;\">Результаты быстрого поиска</td></tr>\n");
    if(mysql_num_rows($res) < 1) {
       print("<tr><td style=\"padding:5px;\">Поиск не дал результатов</td></tr>\n");
       die();
    }
    else {
        $i = 1;
        while ($row = mysql_fetch_array($res)) {
          if($row['free'] == 'yes')
            $color = "#FFFFCC";
          else
            $color = "#FAFAFA";
          print("<tr style=\"background-color:$color\"><td width=\"1%\"><a href=\"browse.php?cat=" . $row["category"] . "\"><img src=\"pic/cats/" . $row["cat_pic"] . "\" title=\"" . $row["cat_name"] . "\" style=\"height:40px;border:none;\" /></a></td><td style=\"padding:0 10px;\"><a href=\"details.php?id=".$row['id']."\">".str_ireplace($q, "<strong style=\"color:red;\">$q</strong>", $row['name'])."</a><br /><small>Добавлен: " . $row['added'] . "</small></td></tr>\n");
          $i++;
        }
    }
    print("</table>\n");
    print("</div>\n");
    die();
}
else
    die("Прямой доступ закрыт.");
?>