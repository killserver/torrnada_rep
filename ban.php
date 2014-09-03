<?
require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();

if (get_user_class() < UC_SYSTEM)
	stderr($tracker_lang['error'], "Что вы тут забыли?");

if (!mkglobal("id"))
    die();

$id = 0 + $id;
if (!$id)
    die();

$updateset = array();
$updateset[] = "ban = 'yes'";
sql_query("UPDATE torrents SET " . join(",", $updateset) . " WHERE id = $id");
@unlink('cache/details/details_id'.$id.'.txt');
@unlink("cache/details/details_thanked_id".$id.".txt");
@unlink("cache/details/details_freeed_id".$id.".txt");
@unlink("cache/details/details_relizgroup_id".$id.".txt");
@unlink("cache/details/download_id".$id.".txt");

		$resname = sql_query("SELECT name FROM torrents WHERE id = $id") or sqlerr(__FILE__,__LINE__);
		$arrname = mysql_fetch_array($resname);
		$name = $arrname[0];

    $res3 = sql_query("SELECT * FROM checkcomm WHERE checkid = $id AND torrent = 1") or sqlerr(__FILE__,__LINE__);
    $subject = sqlesc("Раздача заблокированна");
    while ($arr3 = mysql_fetch_array($res3)) {
    $msg = sqlesc("Торрент [url=".$DEFAULTBASEURL."/details-$id]".$name."[/url] заблокирован");
    if ($CURUSER[id] != $arr3[userid])
     mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) VALUES (0, $arr3[userid], NOW(), $msg, 0, $subject)");
    }



$returl = $DEFAULTBASEURL."/details-$id";
if (isset($_POST["returnto"]))
    $returl .= "&returnto=" . urlencode($_POST["returnto"]);
header("Refresh: 0; url=$returl");
?>