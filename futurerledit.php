<?
require "include/bittorrent.php";

dbconn();
loggedinorreturn();

if (!mkglobal("id"))
stderr("Ошибка", "Введите ID");
$id = (int) $_GET["id"];
if (!$id){
stderr("Ошибка", "Введите ID");
die();
}

$act = $_GET['act'];

if($act == 'delete'){

if (get_user_class() <  UC_MODERATOR || $CURUSER['id']!=8505)
stderr("Woot!", "Fuck off.........");

$id = (int) $_POST['id'];

mysql_query("DELETE FROM futurerls WHERE id=$id");
mysql_query("DELETE FROM comments WHERE trailers=$id");
header("Refresh: 0; url=futurerls.php");
die();
}

if($act == 'takerelease'){


$downid = $_POST["download"];

mysql_query("UPDATE futurerls SET download=$downid WHERE id=$id");

header("Refresh: 0; url=futurerls.php");

die();
}

if($act == 'take'){
stdhead();
begin_frame("Выполнение ожидаемого релиза");

print("<center><form action=\"futurerledit.php?act=takerelease&id=$id\" name=\"takerelease\" method=post>\n");
print("<b>Введите ID релиза:</b> <input type='text' name='download'  size='30' /> ");
print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n"); 
print(" <input type=submit class=btn value='Выполнить!'>\n");
print("</form>\n");
end_frame();
stdfoot();
die();
}

$res = mysql_query("SELECT futurerls.id, futurerls.cat, futurerls.name, futurerls.userid, futurerls.trailer, futurerls.comments, futurerls.added, futurerls.realeasedate, futurerls.descr, users.username FROM futurerls LEFT JOIN users ON users.id=futurerls.userid WHERE futurerls.id = $id")or sqlerr();
$row = mysql_fetch_array($res);
if (!$row)	
stderr("Ошибка", "Неверный релиз");
$id = (int) $_GET["id"];
if (!$id)
die();
if ($CURUSER["id"] != $row["userid"] && get_user_class() < UC_POWER_USER){
stderr("Ошибка", "Вы не можете редактировать этот ожидаемый релиз.");
}
$where = "WHERE userid = " . $CURUSER["id"] . "";
$res2 = mysql_query("SELECT * FROM futurerls $where") or sqlerr();
$num2 = mysql_num_rows($res2);
stdhead("Редактирование ожилаемого релиза");
begin_frame("Редактирование ожилаемого релиза");
print("<center><form action=\"takefuturerledit.php\" name=\"nrg\" method=\"post\" enctype=\"multipart/form-data\">\n");
print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
?>
<center>
<b>Постер:</b> <input type=file name=userfile>&nbsp;<input type="checkbox" name="poster_old" value="1" />Оставить старый постер?<br />
<b>Название:</b> <input type='text' name='name' value='<?=$row['name']?>' size='80' /><br />
<b>Дата выхода:</b> <input type='text' name='realeasedate' value='<?=$row['realeasedate']?>' size='40' /> (оставьте пустым если не знаете)<br />
<b>Код YouTube ролика:</b> <input type='text' name='text' value='<?=$row['trailer']?>' size='30' /></br>
<b>Описание:</b></br><?php textbbcode("nrg","descr",htmlspecialchars_uni($row["descr"])); ?><br />
<style type="text/css">
#bigcat {
color:blue;
font-weight:bold;
}
</style>
<?
	//$s = "<select name=\"type\">\n";

$s = "<select name=\"type\">\n<option value=\"0\">(".$tracker_lang['choose'].")</option>\n";

$cats = genrelist();
foreach($cats as $subrow) {  
	if($subrow["parent"] == 0) {  
		$s .= "<option class='bigcat' label=\"\">".$subrow["name"]."";  
		foreach($cats as $grp) {  
			if($grp["parent"] == $subrow["id"]) {  
				$s .= "<option value=\"" . $grp["id"]. "\"";  
				if($grp["id"] == $row["cat"])  
					$s .= " selected=\"selected\"";  
				$s .= ">" . htmlspecialchars_uni($grp["name"]) . "</option>\n";  
			}  
		}  
		$s .= "</option>";  
	} elseif ($subrow["parent"] == -1){  
		$s .= "<option value=\"" . $subrow["id"] . "\"";  
		if($subrow["id"] == $row["cat"])  
			$s .= " selected=\"selected\"";  
		$s .= ">" . htmlspecialchars_uni($subrow["name"]) . "</option>\n";  
	}  
}
$s .= "</select>\n";

print("<b>Категория:</b> ".$s."<br />");
print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
print("<input type=submit class=btn value='Изменить!'>\n");
print("</form>\n");

print("<form method=\"post\" action=\"futurerledit.php?act=delete&id=".$id."\">\n");
print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n"); 
print("<input type=submit value='Удалить!' style='height: 20px'>\n");

end_frame();
stdfoot();
?>
