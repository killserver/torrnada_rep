<?php
require_once("include/bittorrent.php");
dbconn();

$hours = 24; //интервал в часах

if(isset($_GET['update']) && get_user_class() >= UC_ADMINISTRATOR){
@unlink("cache/categories_viewth.txt");
@unlink("cache/categories_video.txt");
echo "<center>Кеш успешно обновили</center>";
}

$temp_unixdate = time() - (3600 * $hours);
$temp_date = get_date_time($temp_unixdate);

//view torrents
$view_cat = array();
$view_cat[] = $temp_date."<br /><br /><br /><br />";
$view_cat[] = "<center><h2><b>Торренты</b></h2></center><br /><br /><br /><br />";

$cates = new MySQLCache("SELECT id, name, parent FROM categories WHERE parent != \"0\" ORDER BY parent ASC", 86400, "categories_viewth.txt");
while($cat = $cates->fetch_array()) {
	$query = sql_query("SELECT id, name, (SELECT name FROM categories WHERE id = category) as cat_name FROM torrents WHERE addtime >= \"".$temp_unixdate."\" AND category = ".$cat['id']." ORDER BY category ASC") or sqlerr(__FILE__,__LINE__);
	if(@mysql_num_rows($query) >= 1) {
		$view_cat[] = "<b>".$cat['name']."</b><br />";
		while($view = mysql_fetch_array($query)) {
			if(!empty($view['id'])) {
				$view_cat[] = "<a href=\"".$DEFAULTBASEURL."/details-".$view['id']."\">".view_saves($view['name'])."</a><br />";
			}
		}
	}
}

$view = @implode("\n", $view_cat);
//view torrents//

//view video
$view_cat_video = array();
$view_cat_video[] = "<br /><br /><br /><br /><center><h2><b>Видео онлайн</b></h2></center><br /><br />";

$cates_video = new MySQLCache("SELECT id, name FROM catvideo ORDER BY id ASC", 86400, "categories_video.txt");
while ($cat_video = $cates_video->fetch_array()) {
$query_video = sql_query("SELECT id, name, (SELECT name FROM catvideo WHERE id = category) as cat_name FROM videoblog WHERE added >= \"".$temp_date."\" AND category = ".$cat_video['id']." ORDER BY category ASC") or sqlerr(__FILE__,__LINE__);
	if(@mysql_num_rows($query_video) >= 1) {
		$view_cat_video[] = "<b>".$cat_video['name']."</b><br />";
		while($view_video = mysql_fetch_array($query_video)) {
			if(!empty($view_video['id'])) {
				$view_cat_video[] = "<a href=\"".$DEFAULTBASEURL."/details_of_movie.php?id=".$view_video['id']."\">".$view_video['name']."</a><br />";
			}
		}
	}
}

$view_remove = "<br /><a href=\"".$DEFAULTBASEURL."/remove_email.php?email={@email@}&key={@md5_email@}\">Отписаться от рассылки</a>";

$view_video = @implode("\n", $view_cat_video);
//view video//


@unlink("time.html");
$fopen = @fopen("time.html", "a+");
@fwrite($fopen, $view.$view_video.$view_remove);
@fclose($fopen);

echo $view.$view_video.$view_remove;
?>