<?php

require_once("include/bittorrent.php");

dbconn(false);

//loggedinorreturn();
parked();

$cats = genrelist();

$searchstr = '';

if (isset($_GET['search']))
	$searchstr = (string) unesc($_GET["search"]);
$cleansearchstr = htmlspecialchars_uni($searchstr);
if (empty($cleansearchstr))
unset($cleansearchstr);

$letter = trim($_GET["letter"]); 
if (strlen($letter) > 3) die; 
if (empty($letter)) unset($letter);

if (isset($_GET['dsearch'])) 
    $dsearchstr = (string) unesc($_GET["dsearch"]); 
$descrsearchstr = htmlspecialchars_uni($dsearchstr); 
if (empty($descrsearchstr)) 
unset($descrsearchstr); 

// sorting by MarkoStamcar

if (isset($_GET['sort']) && isset($_GET['type'])) {

$column = '';
$ascdesc = '';

switch($_GET['sort']) {
case '1': $column = "name"; break;
case '2': $column = "numfiles"; break;
case '3': $column = "comments"; break;
case '4': $column = "added"; break;
case '5': $column = "size"; break;
case '6': $column = "times_completed"; break;
case '7': $column = "seeders"; break;
case '8': $column = "leechers"; break;
case '9': $column = "owner"; break;
case '10': if (get_user_class() >= UC_MODERATOR) $column = "moderatedby"; break;
default: $column = "id"; break;
}

    switch($_GET['type']) {
  case 'asc': $ascdesc = "ASC"; $linkascdesc = "asc"; break;
  case 'desc': $ascdesc = "DESC"; $linkascdesc = "desc"; break;
  default: $ascdesc = "DESC"; $linkascdesc = "desc"; break;
    }


$orderby = "ORDER BY torrents." . $column . " " . $ascdesc;
$pagerlink = "sort=" . intval($_GET['sort']) . "&type=" . $linkascdesc . "&";

} else {
	$orderby = "ORDER BY torrents.sticky DESC, torrents.id DESC,  torrents.addtime DESC";
	$pagerlink = "";
} 

$on = "name"; 
if(isset($_GET["on"]) && $_GET["on"] == 1) { 
	$on = "name"; 
} elseif(isset($_GET["on"]) && $_GET["on"] == 2) { 
	$on = "descr"; 
} else { 
	$on = "name"; 
}





/*
if (isset($_GET['sort']) && isset($_GET['type'])) {

$column = '';
$ascdesc = '';

switch($_GET['sort']) {
case '1': $column = "name"; break;
case '2': $column = "numfiles"; break;
case '3': $column = "comments"; break;
case '4': $column = "added"; break;
case '5': $column = "size"; break;
case '6': $column = "times_completed"; break;
case '7': $column = "seeders"; break;
case '8': $column = "leechers"; break;
case '9': $column = "owner"; break;
case '10': if (get_user_class() >= UC_MODERATOR) $column = "moderatedby"; break;
default: $column = "id"; break;
}

    switch($_GET['type']) {
  case 'asc': $ascdesc = "ASC"; $linkascdesc = "asc"; break;
  case 'desc': $ascdesc = "DESC"; $linkascdesc = "desc"; break;
  default: $ascdesc = "DESC"; $linkascdesc = "desc"; break;
    }


$newby = "ORDER BY torrents.new " . $ascdesc;
$pagerlink = "sort=" . intval($_GET['sort']) . "&type=" . $linkascdesc . "&";

} else {

$newby = "ORDER BY torrents.new ASC, torrents.id DESC";
$pagerlink = "";

} 
*/










//$addparam = array();
//$addparam = "";
$wherea = array();
$wherecatina = array();
$incldead = 0;

if (isset($_GET['incldead'])) {
	if ($_GET["incldead"] == 1) {
	        $addparam[]= "incldead=1&amp;";
	        if (!isset($CURUSER) || get_user_class() < UC_ADMINISTRATOR)
	                $wherea[] = "banned != 'yes'";
	} elseif ($_GET["incldead"] == 2) {
	        $addparam .= "incldead=2&amp;";
	                $wherea[] = "torrents.visible = 'no'";
	} elseif ($_GET["incldead"] == 3) {
	        $addparam .= "incldead=3&amp;";
	                $wherea[] = "free = 'silver'";
	               $wherea[] = "torrents.visible = 'yes'";
	} elseif ($_GET["incldead"] == 4) {
	        $addparam .= "incldead=3&amp;";
	                $wherea[] = "free = 'gold'";
	               $wherea[] = "torrents.visible = 'yes'";
	} elseif ($_GET["incldead"] == 5) {
	        $addparam .= "incldead=4&amp;";
	                $wherea[] = "seeders+f_seeders = 0";
	                $wherea[] = "torrents.visible = 'yes'";
	} elseif ($_GET["incldead"] == 6) {
	        $addparam .= "incldead=5&amp;";
	                $wherea[] = "new = 'yes'";
	                $wherea[] = "torrents.visible = 'yes'";
	} elseif ($_GET["incldead"] == 7) {
	        $addparam .= "incldead=6&amp;";
	                $wherea[] = "seeders+f_seeders > 0";
	                $wherea[] = "torrents.visible = 'yes'";
	}
	$incldead = (int) $_GET['incldead'];
}


if (isset($_GET['cat']))
	$category = (int) $_GET["cat"];
else
	$category = 0;

if (isset($_GET['all']))
	$all = $_GET["all"];
else
	$all = false;

if (!$all)
if (!$_GET && $CURUSER["notifs"]) {
	$all = True;
	foreach ($cats as $cat) {
		$all &= $cat[id];
		if (strpos($CURUSER["notifs"], "[cat" . $cat[id] . "]") !== false) {
			$wherecatina[] = $cat[id];
			$addparam[]= "c$cat[id]=1&amp;";
		}
	}
} elseif ($category) {
	if (!is_valid_id($category))
		stderr($tracker_lang['error'], "Invalid category ID.");
	$wherecatina[] = $category;
	$addparam .= "cat=$category&amp;";
} else {
	$all = true;
	foreach ($cats as $cat) {
		$all = !isset($_GET["c$cat[id]"]);
		if (isset($_GET["c$cat[id]"])) {
			$wherecatina[] = $cat[id];
			$addparam .= "c$cat[id]=1&amp;";
		}
	}
}

if($all) {
	$wherecatina = array();
	$addparam = "";
}

if(count($wherecatina) > 1) {
	$wherecatin = implode(",",$wherecatina);
} elseif(count($wherecatina) == 1) {
	$wherea[] = "category = ".$wherecatina[0];
}

$wherebase = $wherea;

if(!empty($_GET["search"])) {
	if(!empty($_GET["on"])) {
		$oz = "&on=".$_GET["on"]."";
	}
	if(!empty($_GET["incldead"])) {
		$incldead="&incldead=".$_GET["incldead"]."";
	}
	if(!empty($_GET["cat"])) {
		$cat="&cat=".$_GET["cat"]."";
	}
	if(isset($cleansearchstr)) {
		$wherea[] = "torrents.".$on." LIKE '%" . sqlwildcardesc($searchstr) . "%'";
		$addparam .= "search=" . urlencode($searchstr) . $oz.$incldead.$cat."&amp;";
	}
} else {
	$wherea[] = "torrents.name LIKE '%" . sqlwildcardesc($searchstr) . "%'"; 
	//$addparam .= "search=" . urlencode($searchstr) . "&amp;&on=".$_GET["on"]."&incldead=".$_GET["incldead"]."" . urlencode($searchstr) . "&amp;";

	if(!empty($_GET["on"])) {
		$oz = "&on=".$_GET["on"]."";
	} else {
		$oz="";
	}
	if(!empty($_GET["incldead"])) {
		$incldead="&incldead=".$_GET["incldead"]."";
	} else {
		$incldead="";
	}
	if(!empty($_GET["cat"])) {
		$cat="&cat=".$_GET["cat"]."";
	} else {
		$cat="";
	}
	if(!empty($_GET["search"])) {
		$search="search=" . urlencode($searchstr);
	} else {
		$search="";
	}


	$addparam .= $search . $oz.$incldead.$cat."&amp;";
}

if (isset($letter)) { 
        $wherea[] = "torrents.name LIKE BINARY '$letter%'"; 
        $addparam .= "letter=" . urlencode($letter) . "&amp;"; 
} 

if(isset($descrsearchstr)) { 
	$dsearchstr_sql = $dsearchstr; 
	if ($_GET['stype'] == 'and') { 
		function text_add(&$item, $key) { 
			$item = '+' . $item; 
		} 
		$dsearchstr_sql = explode(' ', $dsearchstr_sql); 
		array_walk($dsearchstr_sql, 'text_add'); 
		$addparam .= "stype=and&amp;"; 
		$dsearchstr_sql = implode(' ', $dsearchstr_sql); 
	} 
	$wherea[] = "MATCH (ti.descr) AGAINST ('" . sqlwildcardesc($dsearchstr_sql) . "' IN BOOLEAN MODE)"; 
	$addparam .= "dsearch=" . urlencode($dsearchstr) . "&amp;"; 
	$conditional_joins .= ' INNER JOIN torrents_index AS ti ON torrents.id = ti.torrent'; 
} 

$where = implode(" AND ", $wherea);
if(isset($wherecatin) && !empty($wherecatin)) {
	$where .= ($where ? " AND " : "") . "category IN (" . $wherecatin . ")";
}

if(!empty($where)) {
	$where = "WHERE ".$where;
}

$res = sql_query("SELECT SQL_CACHE COUNT(*) FROM torrents ".$conditional_joins." ".$where) or die(mysql_error());
$row = mysql_fetch_array($res);
$count = $row[0];
$num_torrents = $count;

if(!$count && isset($cleansearchstr)) {
	$wherea = $wherebase;
	//$orderby = "ORDER BY id DESC";
	$searcha = explode(" ", $cleansearchstr);
	$sc = 0;
	foreach ($searcha as $searchss) {
		if (strlen($searchss) <= 1) {
			continue;
		}
		$sc++;
		if ($sc > 5) {
			break;
		}
		$ssa = array();
		$ssa[] = "torrents.name LIKE '%" . sqlwildcardesc($searchss) . "%'";
	}
	if($sc) {
		$where = implode(" AND ", $wherea);
		if(!empty($where)) {
			$where = "WHERE ".$where;
		}
		$res = sql_query("SELECT COUNT(*) FROM torrents ".$where);
		$row = mysql_fetch_array($res);
		$count = $row[0];
	}
}

$torrentsperpage = $CURUSER["torrentsperpage"];
if (!$torrentsperpage) {
        $torrentsperpage = 25;
}

if($count) {
	if(!empty($addparam)) {
		if(!empty($pagerlink)) {
			if ($addparam{strlen($addparam)-1} != ";") { // & = &amp;
				$addparam = $addparam . "&" . $pagerlink;
			} else {
				$addparam = $addparam . $pagerlink;
			}
		}
	} else {
		$addparam = $pagerlink;
	}


	list($pagertop, $pagerbottom, $limit) = pager($torrentsperpage, $count, "browse.php?".$addparam);
$query = "SELECT SQL_CACHE torrents.image1, torrents.image6, torrents.status, torrents.moderated, torrents.category, torrents.leechers, torrents.seeders, torrents.f_seeders, torrents.f_leechers, torrents.free, torrents.name, torrents.owner, torrents.save_as, torrents.descr, torrents.visible, torrents.size, torrents.info_hash, torrents.added, torrents.times_completed, torrents.id, torrents.type, torrents.numfiles, torrents.name, torrents.times_completed, torrents.size, torrents.added, torrents.comments, torrents.numfiles, torrents.filename, torrents.sticky, torrents.new, torrents.owner, torrents.status, categories.name AS cat_name, categories.image AS cat_pic, categories.name AS cat_name, categories.image AS cat_pic, users.username, users.class FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id ".$conditional_joins." ".$where." ".$orderby." ".$limit;

        $res = sql_query($query) or die(mysql_error());
} else {
        unset($res);
}
if(isset($cleansearchstr)) {
        stdhead($tracker_lang['search_results_for']." \"".$cleansearchstr."\"");
} else {
        stdhead($tracker_lang['browse']);
}

?>
<STYLE TYPE="text/css" MEDIA="screen">
	a.catlink:link, a.catlink:visited{
		text-decoration: none;
	}
	a.catlink:hover {
		color: #A83838;
	}
</STYLE>
<table class="embedded" cellspacing="0" cellpadding="5" width="100%">
<tr><td class="colhead" align="center" colspan="12">Список торрентов</td></tr>
<tr><td colspan="12">

<form method="get" action="browse.php">
<table class="embedded" align="center">
<tr>




<td class="bottom">
        <table class="bottom">
        <tr>

<?
$i = 0;
	$cates = new MySQLCache("SELECT id, name, image, parent FROM categories WHERE parent = '0' ORDER BY sort ASC", 86400,"categories_browse.txt");
	while ($cat = $cates->fetch_array($res))
//foreach ($cats as $cat)
{
        $catsperrow = 10;
        print(($i && $i % $catsperrow == 0) ? "</tr><tr>" : "");

print("<td class=\"bottom\" style=\"padding-bottom: 2px;padding-left: 7px\"><input name=\"c".$cat['id']."\" type=\"checkbox\" " . (in_array($cat['id'], $wherecatina) ? "checked " : "") . "value=\"".$cat['id']."\"><a class=\"catlink\" href=\"browse.php?cat=".$cat['id']."\"><img src=pic/cats/" . htmlspecialchars_uni($cat['image']) . " alt=" . htmlspecialchars_uni($cat['name']) . "></a></td>\n");
$sp=$sp+1;

        $i++;
}



$alllink = "<div align=\"left\">(<a href=\"browse.php?all=1\"><b>".$tracker_lang['show_all']."</b></a>)</div>";

$ncats = count($cats);
$nrows = ceil($ncats/$catsperrow);
$lastrowcols = $ncats % $catsperrow;

if ($lastrowcols != 0) {
	if($catsperrow - $lastrowcols != 1) {
		print("<td class=\"bottom\" rowspan=\"" . ($catsperrow  - $lastrowcols - 1) . "\">&nbsp;</td>");
	}
}

print("</div></td>\n");
?>

        </tr>
        </table>




</td>
</tr>
<tr><td class="embedded">
<div align="center" style="position:relative;">
<?php echo $tracker_lang['search']; ?>:
<script language="javascript" src="js/suggest.js" type="text/javascript"></script>

<input type="text" id="suggestinput" name="search" size="60" autocomplete="off" ondblclick="suggest(event.keyCode,this.value);" onkeyup="suggest(event.keyCode,this.value);" onkeypress="return noenter(event.keyCode);" value="<?php echo htmlspecialchars_uni($searchstr); ?>" />


<select name="on"> 
<option value="1">По названию</option> 
<option value="2">По описанию</option> 
</select> 



<?php echo $tracker_lang['in']; ?>

<select name="incldead">
<option value="0"><?php echo $tracker_lang['active']; ?></option>
<option value="1"<?php print($_GET["incldead"] == 1 ? " selected" : ""); ?>><?php echo $tracker_lang['including_dead']; ?></option>
<option value="2"<?php print($_GET["incldead"] == 2 ? " selected" : ""); ?>><?php echo $tracker_lang['only_dead']; ?></option>
<option value="7"<?php print($_GET["incldead"] == 7 ? " selected" : ""); ?>>Только раздаваемые</option>
<option value="3"<?php print($_GET["incldead"] == 3 ? " selected" : ""); ?>><?php echo $tracker_lang['silver_torrents']; ?></option>
<option value="4"<?php print($_GET["incldead"] == 4 ? " selected" : ""); ?>><?php echo $tracker_lang['golden_torrents']; ?></option>
<option value="5"<?php print($_GET["incldead"] == 5 ? " selected" : ""); ?>><?php echo $tracker_lang['no_seeds']; ?></option>
<option value="6"<?php print($_GET["incldead"] == 6 ? " selected" : ""); ?>>Новинки</option>
</select>


<style type="text/css">
.bigcat {
color:BLUE;
font-weight:bold;
}
</style>

<select name="cat">
<option value="0">(<?php echo $tracker_lang['all_types']; ?>)</option>
<?

//$cats = genrelist();
foreach($cats as $row) {
	if($row["parent"] == 0) {
		$catdropdown .= "<option class='bigcat' label=\"\">".$row["name"]."";
		foreach($cats as $grp) {
			if($grp["parent"] == $row["id"])  {
				$catdropdown .= "<option value=\"" . $grp["id"] . "\"";
				if(isset($_GET["cat"]) && $grp["id"] == $_GET["cat"]) {
					$catdropdown .= " selected=\"selected\"";
				}
				$catdropdown .= ">" . htmlspecialchars_uni($grp["name"]) . "</option>\n";
			}
		}
		$catdropdown .= "</option>";
	} elseif($row["parent"] == -1) {
		$catdropdown .= "<option value=\"" . $row["id"] . "\"";
		if(isset($_GET["cat"]) && $row["id"] == $_GET["cat"]) {
			$catdropdown .= " selected=\"selected\"";
		}
		$catdropdown .= ">" . htmlspecialchars_uni($row["name"]) . "</option>\n";
	}  
}

?>
<?php echo $catdropdown; ?>
</select>


<input class="btn" type="submit" value="<?php echo $tracker_lang['search']; ?>!" />
<div id="suggest"></div>
</div>
</form>
</div>
</br> 
</br> 
<? 
if (isset($letter))  
$addparam = str_replace("letter=".$letter,'',$addparam); 
//цифры начало 
echo "<tr><div class=\"com11\" align=\"center\">";
for($i=1;$i<11;++$i) { 
	if($i == $letter) {
		print("<b>".$i."</b>\n");
	} elseif ($i!=10) {
		print("<a href=\"browse.php?".$addparam."letter=".$i."\"><b>".$i."</b></a> \n");
	} else {
		print("<a href=\"browse.php?".$addparam."letter=0\"><b>0</b></a>\n");
	}
} 
//цифры конец 
for($i=65;$i<91;++$i) {
	$l = chr($i);
	if ($l == $letter) {
		print("<b>".$l."</b>\n");
	} else {
		print("<a href=\"browse.php?".$addparam."letter=".$l."\"><b>".$l."</b></a>\n");
	}
} 
print("</br>"); 
for($i=960;$i<992;++$i) {
	$l = chr($i); 
	if ($l == $letter) {
		print("<b>".$l."</b>\n"); 
	} else {
		print("<a href=\"browse.php?".$addparam."letter=".$l."\"><b>".$l."</b></a>\n");
	}
} 
echo <<<HTML
<style type="text/css">
.com11 { 
background: #EBFCE8;
border: 1px solid #B4E5AC;
margin: 10px 0px;
overflow: hidden !important;
padding: 10px;
}
</style>
HTML;
echo "</div></tr>";
?>
</td></tr></table>

<?

if (isset($cleansearchstr)) {
print("<tr><td class=\"index\" colspan=\"12\">".$tracker_lang['search_results_for']." \"" . htmlspecialchars_uni($searchstr) . "\"</td></tr>\n");
}

print("</td></tr>");

if ($num_torrents) {
        print("<tr><td class=\"index\" colspan=\"12\">");
        print($pagertop);
        print("</td></tr>");
	if(!empty($category)){
	        torrenttable($res, "index");
	} else {
	        torrenttable_st($res, "index");
	}
        print("<tr><td class=\"index\" colspan=\"12\">");
        print($pagerbottom);
        print("</td></tr>");

} else {
        if (isset($cleansearchstr)) {
                print("<tr><td class=\"index\" colspan=\"12\">".$tracker_lang['nothing_found']."</td></tr>\n");
if(strlen($_GET["search"]) >= 4) {
	sql_query("INSERT INTO monitor (userid, username, userclass, torrentname, date) VALUES (".sqlesc($CURUSER["id"]).", ".sqlesc($CURUSER["username"]).", ".sqlesc($CURUSER["class"]).", ".sqlesc(saves($_GET["search"])).", '".get_date_time()."')");
}

                //print("<p>Попробуйте изменить запрос поиска.</p>\n");
        }
        else {
                print("<tr><td class=\"index\" colspan=\"12\">".$tracker_lang['nothing_found']."</td></tr>\n");
                //print("<p>Извините, данная категория пустая.</p>\n");
        }
}

print("</table>");

stdfoot();

?>