<?php
ini_set("max_execution_time", 0);
set_time_limit(0);
require_once("include/BDecode.php");
require_once("include/BEncode.php");
require_once("include/bittorrent.php");

ini_set("upload_max_filesize",$max_torrent_size);

function bark($msg) {
	genbark($msg, $tracker_lang['error']);
}


function validfilenamee($name) {
$name = trim(preg_replace("/\[((\s|.)+?)\]/is", "", preg_replace("/\(((\s|.)+?)\)/is", "", $name))); /// чистм от мусора [] и тд
$name = str_replace(array("@","#","%",";","'","\"","/",":","&","~", "|"), "", $name);
return preg_match('/^[^\0-\x1f:;\\\\\/?*#<>|]+$/si', $name); 
}



$namet="[torrnada.ru]";

dbconn(); 
loggedinorreturn();
parked();

if(get_user_class() < UC_USER AND $CURUSER["bonuploader"] < 1)
bark("Доступ закрыт");


if($CURUSER["ban_upload"] > 1) {
bark("Доступ закрыт");
}


foreach(explode(":", "descr:type:name") as $v) {
	if (!isset($_POST[$v]))
		bark("missing form data<br />error in: ".$v);
}


//if (!isset($_FILES["tfile"]))
//bark("missing form data");


//$f = $_FILES["tfile"];

//$tmpname = $f["tmp_name"];

$f = $_FILES["tfile"];
$fname = trim($f["name"]);



if($_POST['reliz'] != 0) { 
$reliz = intval($_POST['reliz']);
} else { 
$reliz = 0; 
}


if ($_POST['free'] == 'yes' AND get_user_class() >= UC_VIP) {
	$free = htmlspecialchars_uni($_POST['free']);
} else {
	$free = "no";
};

if ($_POST['sticky'] == 'yes' AND get_user_class() >= UC_ADMINISTRATOR)
    $sticky = "yes";
else
    $sticky = "no";



if (!isset($f) || empty($fname) || !validfilenamee($fname) || !preg_match('/^(.+)\.torrent$/si', $fname, $matches))
stderr("Ошибка", "Не верное имя файла");


$descr = unesc($_POST["descr"]);
if (!$descr)
	bark("Вы должны ввести описание!");

$youtube = unesc($_POST["youtube"]);

$catid = (int)$_POST["type"];
if (!is_valid_id($catid))
bark("Вы должны выбрать категорию, в которую поместить торрент!");
	

$shortfname = $torrent = $matches[1];


$torrent = htmlspecialchars_uni($_POST["name"]);
$tmpname = $f["tmp_name"];
if (empty($torrent) && !empty($matches[0]))
$torrent = htmlspecialchars_uni($matches[0]);
if (empty($torrent) && !empty($matches[0]))
$torrent = $tmpname;


$dict = bdecode(file_get_contents($tmpname));
if (!isset($dict))
bark("Что за хрень ты загружаешь? Это не бинарно-кодированый файл!");


//function dict_check($d, $s) {
$info = $dict['info'];
list($dname, $plen, $pieces, $totallen) = array($info['name'], $info['piece length'], $info['pieces'], $info['length']);


$ret = sql_query("SHOW TABLE STATUS LIKE 'torrents'");
$row = mysql_fetch_array($ret);
$next_id = $row['Auto_increment'];

if (strlen($pieces) % 20 != 0)
	bark("invalid pieces");

$filelist = array();
if (isset($totallen)) {
	$filelist[] = array($dname, $totallen);
	$type = "single";
} else {
	$flist = $info['files'];
	if (!isset($flist))
		bark("missing both length and files");
	if (!count($flist))
		bark("no files");
	$totallen = 0;
	foreach ($flist as $fn) {
		list($ll, $ff) = array($fn['length'], $fn['path']);
		$totallen += $ll;
		$ffa = array();
		foreach ($ff as $ffe) {
			$ffa[] = $ffe;
		}
		if (!count($ffa))
			bark("filename error");
		$ffe = implode("/", $ffa);
		$filelist[] = array($ffe, $ll);
	if ($ffe == 'Thumbs.db')
        {
            stderr("Ошибка", "В торрентах запрещено держать файлы Thumbs.db!");
            die;
        }
	}
	$type = "multi";
}





if (empty($_POST["multitr"])) {
	$dict['value']['announce'] = $announce_urls[0];  // change announce url to local
	$dict['info']['private'] = 1;  // add private tracker flag
	$dict['info']['source'] = "[$DEFAULTBASEURL] $SITENAME"; // add link for bitcomet users
	unset($dict['announce-list']); // remove multi-tracker capability
	unset($dict['nodes']); // remove cached peers (Bitcomet & Azareus)
	unset($dict['info']['crc32']); // remove crc32
	unset($dict['info']['ed2k']); // remove ed2k
	unset($dict['info']['md5sum']); // remove md5sum
	unset($dict['info']['sha1']); // remove sha1
	unset($dict['info']['tiger']); // remove tiger
	unset($dict['azureus_properties']); // remove azureus properties
}

$dict = BDecode(BEncode($dict)); // double up on the becoding solves the occassional misgenerated infohash
$dict['comment'] = "Торрент создан для '$SITENAME'"; // change torrent comment
$dict['created by'] = "$CURUSER[username]"; // change created by
$dict['publisher'] = "$CURUSER[username]"; // change publisher
$dict['publisher.utf-8'] = "$CURUSER[username]"; // change publisher.utf-8
$dict['publisher-url'] = "$DEFAULTBASEURL/id$CURUSER[id]"; // change publisher-url
$dict['publisher-url.utf-8'] = "$DEFAULTBASEURL/id$CURUSER[id]"; // change publisher-url.utf-8

$infohash = sha1(BEncode($dict['info']));

if (empty($_POST["multitr"])) {
	if (!empty($dict['announce-list'])) {
		$parsed_urls = array();
		foreach ($dict['announce-list'] as $al_url) {
			$al_url[0] = trim($al_url[0]); // Trim url for match below and prevent "Invalid tracker url." error message if URL contains " " before proto://
			if ($al_url[0] == 'http://retracker.local/announce')
				continue;
			if (!preg_match('#^(udp|http)://#si', $al_url[0]))
				continue; // Skip not http:// or udp:// urls
			if (in_array($al_url[0], $parsed_urls))
				continue; // To skip doubled announce urls
			$url_array = parse_url($al_url[0]);
			if (substr($url_array['host'], -6) == '.local')
				continue; // Skip any .local domains
			$parsed_urls[] = $al_url[0];
			// А вдруг в торренте два одинаковых аннонсера? Потому REPLACE INTO
			sql_query('REPLACE INTO torrents_scrape (tid, info_hash, url) VALUES ('.implode(', ', array_map('sqlesc', array($next_id, $infohash, $al_url[0]))).')') or sqlerr(__FILE__,__LINE__);
		}
	}
}


//////////////////////////////////////////////
//////////////Take Image Uploads//////////////

$maxfilesize = 5*1024*1024; // 5mb

$allowed_types = array(
"image/gif" => "gif",
"image/pjpeg" => "jpg",
"image/jpeg" => "jpg",
"image/jpg" => "jpg",
"image/png" => "png"
// Add more types here if you like
);

for ($x=0; $x < 6; $x++) {


if (!($_FILES[image.$x]['name'] == "")) {

	if (!array_key_exists($_FILES[image.$x]['type'], $allowed_types))
		bark("Неправильный тип картинки! [Разрешенны расширения jpg, png, gif]".(get_user_class() >= UC_SYSOP ? "У Вас формат файла: ".$_FILES[image.$x]['type'].".Для картинки №".$x : ""));

	if (!preg_match('/^(.+)\.(jpg|jpeg|png|gif)$/si', $_FILES[image.$x]['name']))
		bark("Неверное имя или формат файла (не картинка) [Разрешенны расширения jpg, jpeg, png, gif]");

	if ($_FILES[image.$x]['size'] > $maxfilesize)
		bark("Неправильный размер файла! Картинка не может быть больше 5MB");
     
	 	// проверка мал размера, как будто шелл
		if ($_FILES[image.$x]['size'] < "3024")
			bark("Ошибка размера изображения! Картинка - слишком мала для постера");
			
       
	// Update for your own server. Make sure the folder has chmod write permissions. Remember this director
	$uploaddir = ROOT_PATH."torrents/images/";

	// What is the temporary file name?
	$ifile = $_FILES[image.$x]['tmp_name'];

$image=file_get_contents($_FILES[image.$x]['tmp_name']);
if (!$image)
bark("Не изображение");
 
validimage($_FILES[image.$x]["tmp_name"],"takeupload");

  	$ifilename = $next_id . $x . '.' . end(explode('.', $_FILES[image.$x]['name']));

	// Upload the file
	$copy = copy($ifile, $uploaddir.$ifilename);

	if (!$copy)
	bark("Ошибка копировании изображения на сервер $y");

//водяной знак

$margin = 7; 

$ifn=$uploaddir.$ifilename; 

//две картинки которые накладываем одна для темного фона другая для светлого 
$watermark_image_light = ROOT_PATH.'pic/watermark_light.png'; 
$watermark_image_dark =  ROOT_PATH.'pic/watermark_dark.png'; 


list($image_width, $image_height) = getimagesize($ifn); 

list($watermark_width, $watermark_height) = getimagesize($watermark_image_light); 

$watermark_x = $image_width - $margin - $watermark_width; 
$watermark_y = $image_height - $margin - $watermark_height; 

$watermark_x2 = $watermark_x + $watermark_width; 
$watermark_y2 = $watermark_y + $watermark_height; 

if ($watermark_x < 0 OR $watermark_y < 0 OR 
    $watermark_x2 > $image_width OR $watermark_y2 > $image_height OR 
    $image_width < $min_image OR $image_height < $min_image) 
    { 
       return; 
    }

$test123 = imagecreatetruecolor(1, 1); 
if ($_FILES[image.$x]['type']=="image/gif") 
    $creimg=imagecreatefromgif($ifn); 
elseif ($_FILES[image.$x]['type']=="image/png") 
    $creimg=imagecreatefrompng($ifn); 
elseif ($_FILES[image.$x]['type']=="image/jpg" or $_FILES[image.$x]['type']=="image/jpeg" or $_FILES[image.$x]['type']=="image/pjpeg") 
    $creimg=imagecreatefromjpeg($ifn); 

imagecopyresampled($test123, $creimg, 0, 0, $watermark_x, $watermark_y, 1, 1, $watermark_width, $watermark_height); 
$rgb = imagecolorat($test123, 0, 0); 

$r = ($rgb >> 16) & 0xFF; 
$g = ($rgb >> 8) & 0xFF; 
$b = $rgb & 0xFF; 
     
$max = min($r, $g, $b); 
$min = max($r, $g, $b); 
$lightness = (double)(($max + $min) / 510.0); 
imagedestroy($test123); 

$watermark_image = ($lightness < 0.5) ? $watermark_image_light : $watermark_image_dark; 
$watermark = imagecreatefrompng($watermark_image); 
imagealphablending($creimg, TRUE); 
imagealphablending($watermark, TRUE); 
imagecopy($creimg, $watermark, $watermark_x, $watermark_y, 0, 0,$watermark_width, $watermark_height); 

imagedestroy($watermark); 

if ($_FILES[image.$x]['type']=="image/jpg" or $_FILES[image.$x]['type']=="image/jpeg" or $_FILES[image.$x]['type']=="image/pjpeg") 
    imagejpeg($creimg,$ifn);
elseif ($_FILES[image.$x]['type']=="image/png") 
    imagepng($creimg,$ifn); 
elseif ($_FILES[image.$x]['type']=="image/jpg" or $_FILES[image.$x]['type']=="image/jpeg" or $_FILES[image.$x]['type']=="image/pjpeg") 
    imagejpeg($creimg,$ifn); 
elseif ($_FILES[image.$x]['type']=="image/png") 
    imagepng($creimg,$ifn);

    $inames[$x] = $ifilename;  

//водяной знак

}







}

//////////////////////////////////////////////

// Replace punctuation characters with spaces

$multut=(!empty($_POST["multitr"])? "yes":"no");

$torrent = htmlspecialchars(str_replace("_", " ", $torrent));

$filename = $namet."_".$fname;


$kp = intval($_POST["kp"]);


$visible = (get_user_class() >= UC_MODERATOR ? "yes" : "no");
$stats = (get_user_class() >= UC_MODERATOR ? "3" : "0");
$statu = (get_user_class() >= UC_MODERATOR ? sqlesc($CURUSER["id"]) : "0");


$ret = sql_query("INSERT INTO torrents (search_text, filename, owner, visible, sticky, info_hash, multi_infohash, name, size, numfiles, type, descr, ori_descr, youtube, multitracker, free, image1, image2, image3, image4, image5, image6, category, save_as, added, last_action, kp, relizgroup, status, modby, addtime) VALUES (" . implode(",", array_map("sqlesc", array(searchfield("$shortfname $dname $torrent"), $filename, $CURUSER["id"], $visible, $sticky, $infohash, $multi_infohash, $torrent, $totallen, count($filelist), $type, $descr, $descr, $youtube, $multut, $free, $inames[0], $inames[1], $inames[2], $inames[3], $inames[4],$inames[5], intval($_POST["type"]), $namet.$dname))) . ", '" . get_date_time() . "', '" . get_date_time() . "', ".sqlesc($kp).", '$reliz', ".$stats.", ".$statu.", UNIX_TIMESTAMP());");

if (!$ret) {
if (mysql_errno() == 1062)
bark("torrent already uploaded!");
bark("mysql puked: ".mysql_error());
}

$id = mysql_insert_id();

@unlink("include/cache/realises/block-realises_0.txt");

//if (get_user_class() <= UC_UPLOADER) 
//sql_query("UPDATE users SET bonuploader = bonuploader-1 WHERE id = $CURUSER[id]") or sqlerr(__FILE__,__LINE__); 


sql_query("INSERT INTO checkcomm (checkid, userid, torrent) VALUES ($id, $CURUSER[id], 1)") or sqlerr(__FILE__,__LINE__);


@sql_query("DELETE FROM files WHERE torrent = $id");
foreach ($filelist as $file) {
@sql_query("INSERT INTO files (torrent, filename, size) VALUES ($id, ".sqlesc($namet."_".$file[0]).",".$file[1].")");
}

sql_query('REPLACE INTO torrents_index (torrent, descr) VALUES ('.implode(', ', array_map('sqlesc', array($id, strip_bbcode($descr)))).')') or sqlerr();


move_uploaded_file($tmpname, $torrent_dir."/".$id.".torrent");

$fp = fopen($torrent_dir."/".$id.".torrent", "w");
if ($fp) {
	$dict_str = BEncode($dict);
    @fwrite($fp, $dict_str, strlen($dict_str));
    fclose($fp);
}

write_log("Торрент номер $id ($torrent) был залит пользователем " . $CURUSER["username"],"5DDB6E","torrent");

clear_cache();

///////////////////ОПОВЕЩЕНИЕ В ЧАТЕ/////////////////////////////////// 

$bot_text = "Новый торрент [url=details-".$id."]".$torrent.".[/url] Залит пользователем [url=id" .$CURUSER["id"]. "][b][color=red]".$CURUSER["username"]."[/color][/b][/url]"; 

bot_msg($bot_text);     

///////////////////ОПОВЕЩЕНИЕ В ЧАТЕ/////////////////////////////////// 

@unlink('cache/details/details_id'.$id.'.txt');
@unlink("cache/details/details_thanked_id".$id.".txt");
@unlink("cache/details/details_freeed_id".$id.".txt");
@unlink("cache/details/thanks_comm_id".$id.".txt");
@unlink("cache/details/delthanks_comm_id".$id.".txt");
@unlink("cache/details/details_relizgroup_id".$id.".txt");
@unlink("cache/details/download_id".$id.".txt");

header("Location: ".$DEFAULTBASEURL."/details-".$id);


?>