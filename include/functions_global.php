<?php

# IMPORTANT: Do not edit below unless you know what you are doing!
if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

function saves($text) {
	$text = strip_tags($text);
	$text = htmlspecialchars($text);
return $text;
}

function iconv_charset($string) {
	return mb_detect_encoding($string, array('UTF-8', 'Windows-1251', 'KOI8-R', 'ISO-8859-5'));
}

function get_user_class_color($class, $username)
{
  global $tracker_lang;



if($username=='killer') return "<span style='color:yellow; text-shadow: 0 0 14px #0F6CEE, 0 0 3px #0F6CEE, 1px 1px 3px #0F6CEE; *color:#0F6CEE;' title=kill>Kil</span><span style='color:gold; text-shadow: 0 0 14px #0F6CEE, 0 0 3px #0F6CEE, 1px 1px 3px #0F6CEE; *color:#0F6CEE;' title=ler>leR</span>";

//if($username=='killer') return "<font color=white>KilleR</font>";

if($username=='dark_sky') return "<span style='color:yellow; text-shadow: 0 0 14px #00FF00, 0 0 3px #FFFF00, 1px 1px 3px #0F6CEE; *color:#0F6CEE;' title=dark_sky>dark_sky</span>";

if($username=='paha') return "<span style='color:yellow; text-shadow: 0 0 14px #0000EE, 0 0 3px #ffff00, 1px 1px 3px #0F6CEE; *color:#0F6CEE;' title=paha>paha</span>";




  switch ($class)
  {
    case UC_SYSTEM:
      return "<span style='color:yellow; text-shadow: 0 0 14px #00FF00, 0 0 3px #000000, 1px 1px 3px #0F6CEE; *color:#000000;'  title=\"".$tracker_lang['class_system']."\">" . $username . "</span>";
      break;
    case UC_SYSOP:
      return "<span style=\"color: #fff; text-shadow: 0 0 14px #0F6CEE, 0 0 3px #0F6CEE, 1px 1px 3px #0F6CEE; *color:#0F6CEE\" title=\"".$tracker_lang['class_sysop']."\">" . $username . "</span>";
      break;
    case UC_SYSOPIDIOT:
      return "<span style=\"color: #fff; text-shadow: 0 0 14px #0F6CE0, 0 0 3px #0F6CE0, 1px 1px 3px #0F6CE0; *color:#006CE0\" title=\"".$tracker_lang['class_sysopidiot']."\">" . $username . "</span>";
      break;
    case UC_SYSOPUPLOADER:
      return "<span style=\"color: #fff; text-shadow: 0 0 14px #0F6CE0, 0 0 3px #0F6CE0, 1px 1px 3px #0F6CE0; *color:#0F6CE0\" title=\"".$tracker_lang['class_sysopuploader']."\">" . $username . "</span>";
      break;
    case UC_SPONSOR:
      return "<span style=\"color: #fff; text-shadow: 0 0 14px #660066, 0 0 3px #660066, 1px 1px 3px #660066; *color: #660066\" title=\"".$tracker_lang['class_sponsor']."\">" . $username . "</span>";
      break;
    case UC_ADMINISTRATOR:
      return "<span style=\"color: #fff; text-shadow: 0 0 14px #4169e1, 0 0 3px #4169e1, 1px 1px 3px #4169e1; *color: #4169e1\" title=\"".$tracker_lang['class_administrator']."\">" . $username . "</span>";
      break;
case UC_HELP_ADMIN: 
      return "<span style=\"color: #fff; text-shadow: 0 0 14px #00FF00, 0 0 3px #00FF00, 1px 1px 3px #00FF00; *color: #00FF00\" title=\"".$tracker_lang['class_help_admin']."\">" . $username . "</span>"; 
      break; 
    case UC_MODERATOR:
      return "<span style=\"color: #fff; text-shadow: 0 0 14px #c41e3a, 0 0 3px #c41e3a, 1px 1px 3px #c41e3a; *color: #c41e3a\" title=\"".$tracker_lang['class_moderator']."\">" . $username . "</span>";
      break;

    case UC_MODCHAT:
      return "<span style=\"color: #fff; text-shadow: 0 0 14px #50c878, 0 0 3px #50c878, 1px 1px 3px #50c878; *color: #50c878\" title=\"".$tracker_lang['class_modchat']."\">" . $username . "</span>";
      break;

     case UC_SUPER_UPLOADER:
      return "<span style='color: #fff; text-shadow: 0 0 14px #FF8C00, 0 0 3px #FF8C00, 1px 1px 3px #FF8C00; *color: #FF8C00' title=\"".$tracker_lang['class_super_uploader']."\">" . $username . "</span>";
      break;

     case UC_NEADEKVAT:
      return "<span style=\"color: #fff; text-shadow: 0 0 14px #FFA500, 0 0 3px #FFA500, 1px 1px 3px #FFA500; *color: #FFA500\" title=\"".$tracker_lang['class_neadekvat']."\">" . $username . "</span>";
      break;

     case UC_UPLOADER:
      return "<span style=\"color: #fff; text-shadow: 0 0 14px #ffbf00, 0 0 3px #ffbf00, 1px 1px 3px #ffbf00; *color: #ffbf00\" title=\"".$tracker_lang['class_uploader']."\">" . $username . "</span>";
      break;
     case UC_VIP:
      return "<span style=\"color: #fff; text-shadow: 0 0 14px #725191, 0 0 3px #725191, 1px 1px 3px #725191; *color: #725191\" title=\"".$tracker_lang['class_vip']."\">" . $username . "</span>";
      break;
     case UC_POWER_USER:
      return "<span style='color: #fff; text-shadow: 0 0 14px #01796f, 0 0 3px #01796f, 1px 1px 3px #01796f; *color: #01796f' title=\"".$tracker_lang['class_power_user']."\">" . $username . "</span>";
      break;
     case UC_USER:
      return "<span style='color: #fff; text-shadow: 0 0 14px black, 0 0 3px black, 1px 1px 3px black; *color: black' title=\"".$tracker_lang['class_user']."\">" . $username . "</span>";
      break;
  }
  return "$username";
}




function display_date_time($timestamp = 0 , $tzoffset = 0){
        return date("Y-m-d H:i:s", $timestamp + ($tzoffset * 60));
}

function cut_text ($txt, $car) {
	while(strlen($txt) > $car) {
	      return substr($txt, 0, $car) . "...";
	}
	return $txt;
}

function textbbcode($form, $name, $content = false) {

if (preg_match("/upload/i", $_SERVER["SCRIPT_FILENAME"]))
$col = "18";
elseif (preg_match("/edit/i", $_SERVER["SCRIPT_FILENAME"]))
$col = "38";
else
$col = "11";

?>

<script language="javascript" type="text/javascript" src="js/ajax.js"></script>

<div id="loading-layer" style="display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000">
<div style="font-weight:bold" id="loading-layer-text">Загрузка предварительного просмотра. Пожалуйста, подождите...</div><br />
<img src="pic/loading.gif" border="0" />
</div>

<script language="javascript" type="text/javascript" src="js/bbcode.js"></script>

<style>
.editbutton { cursor: pointer; padding: 2px 1px 0px 5px; }
</style>
<table cellpadding="0" cellspacing="0" align="сenter">

<tr>
<td class="b">

<div>

<div align="center">
<select name="fontFace" class="editbutton">
<option style="font-family: Verdana" value="-1" selected="selected">Шрифт:</option>
<option style="font-family: Courier" value="Courier">&nbsp;Courier</option>
<option style="font-family: Courier New" value="Courier New">&nbsp;Courier New</option>
<option style="font-family: monospace" value="monospace">&nbsp;monospace</option>
<option style="font-family: Fixedsys" value="Fixedsys">&nbsp;Fixedsys</option>
<option style="font-family: Arial" value="Arial">&nbsp;Arial</option>
<option style="font-family: Comic Sans MS" value="Comic Sans MS">&nbsp;Comic Sans</option>
<option style="font-family: Georgia" value="Georgia">&nbsp;Georgia</option>
<option style="font-family: Tahoma" value="Tahoma">&nbsp;Tahoma</option>
<option style="font-family: Times New Roman" value="Times New Roman">&nbsp;Times</option>
<option style="font-family: serif" value="serif">&nbsp;serif</option>
<option style="font-family: sans-serif" value="sans-serif">&nbsp;sans-serif</option>
<option style="font-family: cursive" value="cursive">&nbsp;cursive</option>
<option style="font-family: fantasy" value="fantasy">&nbsp;fantasy</option>
<option style="font-family: Book Antiqua" value="Book Antiqua">&nbsp;Antiqua</option>
<option style="font-family: Century Gothic" value="Century Gothic">&nbsp;Century Gothic</option>
<option style="font-family: Franklin Gothic Medium" value="Franklin Gothic Medium">&nbsp;Franklin</option>
<option style="font-family: Garamond" value="Garamond">&nbsp;Garamond</option>
<option style="font-family: Impact" value="Impact">&nbsp;Impact</option>
<option style="font-family: Lucida Console" value="Lucida Console">&nbsp;Lucida</option>
<option style="font-family: Palatino Linotype" value="Palatino Linotype">&nbsp;Palatino</option>
<option style="font-family: Trebuchet MS" value="Trebuchet MS">&nbsp;Trebuchet</option>
</select>
&nbsp;
<select name="codeColor" class="editbutton">
<option style="color: black; background: #fff;" value="black" selected="selected">Цвет шрифта:</option>
<option style="color: black" value="Black">&nbsp;Черный</option>
<option style="color: sienna" value="Sienna">&nbsp;Охра</option>
<option style="color: Beige" value="Beige">&nbsp;Бежевый</option>
<option style="color: darkolivegreen" value="DarkOliveGreen">&nbsp;Олив. Зеленый</option>
<option style="color: darkgreen" value="DarkGreen">&nbsp;Т. Зеленый</option>
<option style="color: Cornflower" value="Cornflower">&nbsp;Васильковый</option>
<option style="color: darkslateblue" value="DarkSlateBlue">&nbsp;Гриф.-синий</option>
<option style="color: navy" value="Navy">&nbsp;Темно-синий</option>
<option style="color: MidnightBlue" value="MidnightBlue">&nbsp;Полу.-синий</option>
<option style="color: indigo" value="Indigo">&nbsp;Индиго</option>
<option style="color: darkslategray" value="DarkSlateGray">&nbsp;Синевато-серый</option>
<option style="color: darkred" value="DarkRed">&nbsp;Т. Красный</option>
<option style="color: darkorange" value="DarkOrange">&nbsp;Т. Оранжевый</option>
<option style="color: olive" value="Olive">&nbsp;Оливковый</option>
<option style="color: green" value="Green">&nbsp;Зеленый</option>
<option style="color: DarkCyan" value="DarkCyan">&nbsp;Темный циан</option>
<option style="color: CadetBlue" value="CadetBlue">&nbsp;Серо-синий</option>
<option style="color: Aquamarine" value="Aquamarine">&nbsp;Аквамарин</option>
<option style="color: teal" value="Teal">&nbsp;Морской волны</option>
<option style="color: blue" value="Blue">&nbsp;Голубой</option>
<option style="color: slategray" value="SlateGray">&nbsp;Синевато-серый</option>
<option style="color: dimgray" value="DimGray">&nbsp;Тускло-серый</option>
<option style="color: red" value="Red">&nbsp;Красный</option>
<option style="color: Chocolate" value="Chocolate">&nbsp;Шоколадный</option>
<option style="color: Firebrick" value="Firebrick">&nbsp;Кирпичный</option>
<option style="color: Saddlebrown" value="SaddleBrown">&nbsp;Кож.коричневый</option>
<option style="color: yellowgreen" value="YellowGreen">&nbsp;Желт-Зеленый</option>
<option style="color: seagreen" value="SeaGreen">&nbsp;Океан. Зеленый</option>
<option style="color: mediumturquoise" value="MediumTurquoise">&nbsp;Бирюзовый</option>
<option style="color: royalblue" value="RoyalBlue">&nbsp;Голубой Корол.</option>
<option style="color: purple" value="Purple">&nbsp;Липовый</option>
<option style="color: gray" value="Gray">&nbsp;Серый</option>
<option style="color: magenta" value="Magenta">&nbsp;Пурпурный</option>
<option style="color: orange" value="Orange">&nbsp;Оранжевый</option>
<option style="color: yellow" value="Yellow">&nbsp;Желтый</option>
<option style="color: Gold" value="Gold">&nbsp;Золотой</option>
<option style="color: Goldenrod" value="Goldenrod">&nbsp;Золотистый</option>
<option style="color: lime" value="Lime">&nbsp;Лимонный</option>
<option style="color: cyan" value="Cyan">&nbsp;Зел.-голубой</option>
<option style="color: deepskyblue" value="DeepSkyBlue">&nbsp;Т.Неб.-голубой</option>
<option style="color: darkorchid" value="DarkOrchid">&nbsp;Орхидея</option>
<option style="color: silver" value="Silver">&nbsp;Серебристый</option>
<option style="color: pink" value="Pink">&nbsp;Розовый</option>
<option style="color: wheat" value="Wheat">&nbsp;Wheat</option>
<option style="color: lemonchiffon" value="LemonChiffon">&nbsp;Лимонный</option>
<option style="color: palegreen" value="PaleGreen">&nbsp;Бл. Зеленый</option>
<option style="color: paleturquoise" value="PaleTurquoise">&nbsp;Бл. Бирюзовый</option>
<option style="color: lightblue" value="LightBlue">&nbsp;Св. Голубой</option>
<option style="color: plum" value="Plum">&nbsp;Св. Розовый</option>
<option style="color: white" value="White">&nbsp;Белый</option>
</select>
&nbsp;
<select name="codeSize" class="editbutton">
	<option value="12" selected="selected">Размер шрифта:</option>
	<option value="9" class="em">Маленький</option>
	<option value="10">&nbsp;size=10</option>
	<option value="11">&nbsp;size=11</option>
	<option value="12" class="em" disabled="disabled">Обычный</option>
	<option value="14">&nbsp;size=14</option>
	<option value="16">&nbsp;size=16</option>
	<option value="18" class="em">Большой</option>

	<option value="20">&nbsp;size=20</option>
	<option value="22">&nbsp;size=22</option>
	<option value="24" class="em">Огромный</option>
</select>
&nbsp;

<select name="codeAlign" class="editbutton">
   <option value="" selected="selected">Выравнивание:</option>
   <option value="left">&nbsp;По левому краю</option>
   <option value="right">&nbsp;По правому краю</option>
   <option value="center">&nbsp;По центру</option>
   <option value="justify">&nbsp;По ширине</option>
</select>
</div>


<div align="center">

<input class="btn" type="button" value="&#8212;" name="codeHR" title="Горизонтальная линия (Ctrl+8)" style="font-weight: bold; width: 26px;" />
<input class="btn" type="button" value="&para;" name="codeBR" title="Новая строка" style="width: 26px;" />

<input class="btn" type="button" value="Спойлер" name="codeSpoiler" title="Спойлер (Ctrl+S)" style="width: 70px;" />

<input class="btn" type="button" value=" B " name="codeB" title="Жирный текст (Ctrl+B)" style="font-weight: bold; width: 30px;" />
<input class="btn" type="button" value=" i " name="codeI" title="Наклонный текст (Ctrl+I)" style="width: 30px; font-style: italic;" />
<input class="btn" type="button" value=" u " name="codeU" title="Подчеркнутый текст (Ctrl+U)" style="width: 30px; text-decoration: underline;" />
<input class="btn" type="button" value=" s " name="codeS" title="Перечеркнутый текст" style="width: 30px; text-decoration: line-through;" />

<input class="btn" type="button" value=" BB " name="codeBB" title="Чистый bb код (Неотформатированный) (Ctrl+N)" style="font-weight: bold; width: 30px;" />

<input class="btn" type="button" value=" PRE " name="codePRE" title="Преформатный текст (Ctrl+P)" style="width: 40px;" />

<input class="btn" type="button" value=" HTEXT " name="codeHT" title="Скрытие текста при наведение показ (Ctrl+H)" style="width: 60px;" />

<input class="btn" type="button" value=" YOUTUBE " name="codeMYT" title="Вставка плеера с ютуба" style="width: 70px;" />

<input class="btn" type="button" value=" Marquee " name="codeMG" title="Бегающая строка (Ctrl+M)" style="width: 70px;" />

<input class="btn" type="button" value="Цитата" name="codeQuote" title="Цитирование (Ctrl+Q)" style="width: 60px;" />
<input class="btn" type="button" value="Img" name="codeImg" title="Картинка (Ctrl+R)" style="width: 40px;" />

<?  if (basename($_SERVER['SCRIPT_FILENAME']) == 'edit.php' || basename($_SERVER['SCRIPT_FILENAME']) == 'uploadnext.php'){ $disab1="disabled=\"disabled\""; } ?>

<input class="btn" type="button"  <?=$disab1;?>  value="Цитировать выделение" name="quoteselected" title="Цитировать выделенный текст" style="width: 165px;" onmouseout="bbcode.refreshSelection(false);" onmouseover="bbcode.refreshSelection(true);" onclick="bbcode.onclickQuoteSel();" />&nbsp;


<?  if (basename($_SERVER['SCRIPT_FILENAME']) <> 'edit.php' && basename($_SERVER['SCRIPT_FILENAME']) <> 'uploadnext.php'){ 	$disab="disabled=\"disabled\""; } ?>

<input class="btn" type="button" value="Скрытый" <?=$disab;?> name="codeHIDE" title="Скрытый Текст, пока не прокомментируешь раздачу" style="width: 70px;" />


<input class="btn" type="button" value="URL" name="codeUr" title="URL ссылка" style="width: 40px; text-decoration: underline;" />

<input class="btn" type="button" value="PHP" name="codeCode" title="PHP код (Ctrl+K)" style="width: 46px;" />

<input class="btn" type="button" value="Flash" name="codeFlash" title="Flash анимания (Ctrl+F)" style="width: 50px;" />

<input class="btn" type="button" value="&#8226;" name="codeOpt" title="Маркированый список (Ctrl+0)" style="width: 30px;" />

<input class="btn" type="button" value="Рамка I" name="codeLG1" title="Рамка вокруг текста (Ctrl+1)" style="width: 65px;" />

<input class="btn" type="button" value="Рамка II" name="codeLG2" title="Рамка вокруг текста с цитатой (Ctrl+2)" style="width: 65px;" />

<input class="btn" type="button" value="highlight" name="codeHIG" title="Подсветка синтаксиса" style="width: 60px;" />


<? if (basename($_SERVER['SCRIPT_FILENAME']) == 'edit.php' || basename($_SERVER['SCRIPT_FILENAME']) == 'uploadnext.php') { ?>
<input class="btn" type="button" value="addBB" onclick='textbb_udesck("area")' title="Добавить теги (оформить читабельность)" style="width: 60px;" />
<? } ?>


<input class="btn" type="button" value=" Смайлы " name="Smailes" title="Смайлы (окно всех смайлов)" onclick="javascript:winop()"/>&nbsp;

</div>

<?  if (basename($_SERVER['SCRIPT_FILENAME']) <> 'forums.php'){ ?>
<script language="javascript" type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript">
function winop()
{
windop = window.open("moresmiles.php?form=shoutform&text=shout","mywin","height=500,width=600,resizable=no,scrollbars=yes");
}
function RowsTextarea(n, w) {
	var inrows = document.getElementById(n);
	if (w < 1) {
		var rows = -5;
	} else {
		var rows = +5;
	}
	var outrows = inrows.rows + rows;
	if (outrows >= 5 && outrows < 54) {
		inrows.rows = outrows;
	}
	return false;
}

var SelField = document.<?php echo $form;?>.<?php echo $name;?>;
var TxtFeld  = document.<?php echo $form;?>.<?php echo $name;?>;

var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var clientVer = parseInt(navigator.appVersion); // Get browser version

var is_ie = ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1));
var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
                && (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
                && (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));

var is_moz = 0;

var is_win = ((clientPC.indexOf("win")!=-1) || (clientPC.indexOf("16bit") != -1));
var is_mac = (clientPC.indexOf("mac")!=-1);

function StoreCaret(text) {
	if (text.createTextRange) {
		text.caretPos = document.selection.createRange().duplicate();
	}
}
function FieldName(text, which) {
	if (text.createTextRange) {
		text.caretPos = document.selection.createRange().duplicate();
	}
	if (which != "") {
		var Field = eval("document.<?php echo $form;?>."+which);
		SelField = Field;
		TxtFeld  = Field;
	}
}
function AddSmile(SmileCode) {
	var SmileCode;
	var newPost;
	var oldPost = SelField.value;
	newPost = oldPost+SmileCode;
	SelField.value=newPost;
	SelField.focus();
	return;
}
function AddSelectedText(Open, Close) {
	if (SelField.createTextRange && SelField.caretPos && Close == '\n') {
		var caretPos = SelField.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? Open + Close + ' ' : Open + Close;
		SelField.focus();
	} else if (SelField.caretPos) {
		SelField.caretPos.text = Open + SelField.caretPos.text + Close;
	} else {
		SelField.value += Open + Close;
		SelField.focus();
	}
}
function InsertCode(code, info, type, error) {
	if (code == 'name') {
		AddSelectedText('[b]' + info + '[/b]', '\n');
	} else if (code == 'url' || code == 'mail') {
		if (code == 'url') var url = prompt(info, 'http://');
		if (code == 'mail') var url = prompt(info, '');
		if (!url) return alert(error);
		if ((clientVer >= 4) && is_ie && is_win) {
			selection = document.selection.createRange().text;
			if (!selection) {
				var title = prompt(type, type);
				AddSelectedText('[' + code + '=' + url + ']' + title + '[/' + code + ']', '\n');
			} else {
				AddSelectedText('[' + code + '=' + url + ']', '[/' + code + ']');
			}
		} else {
			mozWrap(TxtFeld, '[' + code + '=' + url + ']', '[/' + code + ']');
		}
	} else if (code == 'color' || code == 'family' || code == 'size') {
		if ((clientVer >= 4) && is_ie && is_win) {
			AddSelectedText('[' + code + '=' + info + ']', '[/' + code + ']');
		} else if (TxtFeld.selectionEnd && (TxtFeld.selectionEnd - TxtFeld.selectionStart > 0)) {
			mozWrap(TxtFeld, '[' + code + '=' + info + ']', '[/' + code + ']');
		}
	} else if (code == 'li' || code == 'hr') {
		if ((clientVer >= 4) && is_ie && is_win) {
			AddSelectedText('[' + code + ']', '');
		} else {
			mozWrap(TxtFeld, '[' + code + ']', '');
		}
	} else if (code == 'spoiler') { 
		//var text = prompt(info, ''); 
		var header = prompt(type, ''); 
		if (!header) 
			header = 'Скрытая информация'; 
		mozWrap(TxtFeld, '[' + code + '=' + header + ']', '[/' + code + ']'); 
	} else {
		if ((clientVer >= 4) && is_ie && is_win) {
			var selection = false;
			selection = document.selection.createRange().text;
			if (selection && code == 'quote') {
				AddSelectedText('[' + code + ']' + selection + '[/' + code + ']', '\n');
			} else {
				AddSelectedText('[' + code + ']', '[/' + code + ']');
			}
		} else {
			mozWrap(TxtFeld, '[' + code + ']', '[/' + code + ']');
		}
	}

}

function mozWrap(txtarea, open, close)
{
        var selLength = txtarea.textLength;
        var selStart = txtarea.selectionStart;
        var selEnd = txtarea.selectionEnd;
        if (selEnd == 1 || selEnd == 2)
                selEnd = selLength;

        var s1 = (txtarea.value).substring(0,selStart);
        var s2 = (txtarea.value).substring(selStart, selEnd)
        var s3 = (txtarea.value).substring(selEnd, selLength);
        txtarea.value = s1 + open + s2 + close + s3;
        txtarea.focus();
        return;
}

language=1;
richtung=1;
var DOM = document.getElementById ? 1 : 0, 
opera = window.opera && DOM ? 1 : 0, 
IE = !opera && document.all ? 1 : 0, 
NN6 = DOM && !IE && !opera ? 1 : 0; 
var ablauf = new Date();
var jahr = ablauf.getTime() + (365 * 24 * 60 * 60 * 1000);
ablauf.setTime(jahr);
var richtung=1;
var isChat=false;
NoHtml=true;
NoScript=true;
NoStyle=true;
NoBBCode=true;
NoBefehl=false;

function setZustand() {
	transHtmlPause=false;
	transScriptPause=false;
	transStylePause=false;
	transBefehlPause=false;
	transBBPause=false;
}
setZustand();
function keks(Name,Wert){
	document.cookie = Name+"="+Wert+"; expires=" + ablauf.toGMTString();
}
function changeNoTranslit(Nr){
	if(document.trans.No_translit_HTML.checked)NoHtml=true;else{NoHtml=false}
	if(document.trans.No_translit_BBCode.checked)NoBBCode=true;else{NoBBCode=false}
	keks("NoHtml",NoHtml);keks("NoScript",NoScript);keks("NoStyle",NoStyle);keks("NoBBCode",NoBBCode);
}
function winop()
{
windop = window.open("moresmiles.php?form=<?php echo $form;?>&text=<?php echo $name; ?>","mywin","height=500,width=600,resizable=no,scrollbars=yes");
}
function changeRichtung(r){
	richtung=r;keks("TransRichtung",richtung);setFocus()
}
function changelanguage(){  
	if (language==1) {language=0;}
	else {language=1;}
	keks("autoTrans",language);
	setFocus();
	setZustand();
}
function setFocus(){
	TxtFeld.focus();
}
function repl(t,a,b){
	var w=t,i=0,n=0;
	while((i=w.indexOf(a,n))>=0){
		t=t.substring(0,i)+b+t.substring(i+a.length,t.length);	
		w=w.substring(0,i)+b+w.substring(i+a.length,w.length);
		n=i+b.length;
		if(n>=w.length){
			break;
		}
	}
	return t;
}
var rus_lr2 = ('Е-е-О-о-Ё-Ё-Ё-Ё-Ж-Ж-Ч-Ч-Ш-Ш-Щ-Щ-Ъ-Ь-Э-Э-Ю-Ю-Я-Я-Я-Я-ё-ё-ж-ч-ш-щ-э-ю-я-я').split('-');
var lat_lr2 = ('/E-/e-/O-/o-ЫO-Ыo-ЙO-Йo-ЗH-Зh-ЦH-Цh-СH-Сh-ШH-Шh-ъ'+String.fromCharCode(35)+'-ь'+String.fromCharCode(39)+'-ЙE-Йe-ЙU-Йu-ЙA-Йa-ЫA-Ыa-ыo-йo-зh-цh-сh-шh-йe-йu-йa-ыa').split('-');
var rus_lr1 = ('А-Б-В-Г-Д-Е-З-И-Й-К-Л-М-Н-О-П-Р-С-Т-У-Ф-Х-Х-Ц-Щ-Ы-Я-а-б-в-г-д-е-з-и-й-к-л-м-н-о-п-р-с-т-у-ф-х-х-ц-щ-ъ-ы-ь-я').split('-');
var lat_lr1 = ('A-B-V-G-D-E-Z-I-J-K-L-M-N-O-P-R-S-T-U-F-H-X-C-W-Y-Q-a-b-v-g-d-e-z-i-j-k-l-m-n-o-p-r-s-t-u-f-h-x-c-w-'+String.fromCharCode(35)+'-y-'+String.fromCharCode(39)+'-q').split('-');
var rus_rl = ('А-Б-В-Г-Д-Е-Ё-Ж-З-И-Й-К-Л-М-Н-О-П-Р-С-Т-У-Ф-Х-Ц-Ч-Ш-Щ-Ъ-Ы-Ь-Э-Ю-Я-а-б-в-г-д-е-ё-ж-з-и-й-к-л-м-н-о-п-р-с-т-у-ф-х-ц-ч-ш-щ-ъ-ы-ь-э-ю-я').split('-');
var lat_rl = ('A-B-V-G-D-E-JO-ZH-Z-I-J-K-L-M-N-O-P-R-S-T-U-F-H-C-CH-SH-SHH-'+String.fromCharCode(35)+String.fromCharCode(35)+'-Y-'+String.fromCharCode(39)+String.fromCharCode(39)+'-JE-JU-JA-a-b-v-g-d-e-jo-zh-z-i-j-k-l-m-n-o-p-r-s-t-u-f-h-c-ch-sh-shh-'+String.fromCharCode(35)+'-y-'+String.fromCharCode(39)+'-je-ju-ja').split('-');
var transAN=true;
function transliteText(txt){
	vorTxt=txt.length>1?txt.substr(txt.length-2,1):"";
	buchstabe=txt.substr(txt.length-1,1);
	txt=txt.substr(0,txt.length-2);
	return txt+translitBuchstabeCyr(vorTxt,buchstabe);
}
function translitBuchstabeCyr(vorTxt,txt){
	var zweiBuchstaben = vorTxt+txt;
	var code = txt.charCodeAt(0);
	
	if (txt=="<")transHtmlPause=true;else if(txt==">")transHtmlPause=false;
	if (txt=="<script")transScriptPause=true;else if(txt=="<"+"/script>")transScriptPause=false;
	if (txt=="<style")transStylePause=true;else if(txt=="<"+"/style>")transStylePause=false;
	if (txt=="[")transBBPause=true;else if(txt=="]")transBBPause=false;
	if (txt=="/")transBefehlPause=true;else if(txt==" ")transBefehlPause=false;
	
	if (
		(transHtmlPause==true &&   NoHtml==true)||
		(transScriptPause==true &&   NoScript==true)||
		(transStylePause==true &&   NoStyle==true)||
		(transBBPause==true &&   NoBBCode==true)||
		(transBefehlPause==true &&   NoBefehl==true)||
		
		!(((code>=65) && (code<=123))||(code==35)||(code==39))) return zweiBuchstaben;
	
	for (x=0; x<lat_lr2.length; x++){
		if (lat_lr2[x]==zweiBuchstaben) return rus_lr2[x];
	}
	for (x=0; x<lat_lr1.length; x++){
		if (lat_lr1[x]==txt) return vorTxt+rus_lr1[x];
	}
	return zweiBuchstaben;
}
function translitBuchstabeLat(buchstabe){
	for (x=0; x<rus_rl.length; x++){
		if (rus_rl[x]==buchstabe)
		return lat_rl[x];
	}
	return buchstabe;
}
function translateAlltoLatin(){
	if (!IE){
		var txt=TxtFeld.value;
		var txtnew = "";
		var symb = "";
		for (y=0;y<txt.length;y++){
			symb = translitBuchstabeLat(txt.substr(y,1));
			txtnew += symb;
		}
		TxtFeld.value = txtnew;
		setFocus()
	} else {
		var is_selection_flag = 1;
		var userselection = document.selection.createRange();
		var txt = userselection.text;

		if (userselection==null || userselection.text==null || userselection.parentElement==null || userselection.parentElement().type!="textarea"){
			is_selection_flag = 0;
			txt = TxtFeld.value;
		}
		txtnew="";
		var symb = "";
		for (y=0;y<txt.length;y++){
			symb = translitBuchstabeLat(txt.substr(y,1));
			txtnew +=  symb;
		}
		if (is_selection_flag){
			userselection.text = txtnew; userselection.collapse(); userselection.select();
		}else{
			TxtFeld.value = txtnew;
			setFocus()
		}
	}
	return;
}
function TransliteFeld(object, evnt){
	if (language==1 || opera) return;
	if (NN6){
		var code=void 0;
		var code =  evnt.charCode; 
		var textareafontsize = 14; 
		var textreafontwidth = 7;
		if(code == 13){
			return;
		}
		if ( code && (!(evnt.ctrlKey || evnt.altKey))){
			pXpix = object.scrollTop;
			pYpix = object.scrollLeft;
        	evnt.preventDefault();
			txt=String.fromCharCode(code);
			pretxt = object.value.substring(0, object.selectionStart);
			result = transliteText(pretxt+txt);
			object.value = result+object.value.substring(object.selectionEnd);
			object.setSelectionRange(result.length,result.length);
			object.scrollTop=100000;
			object.scrollLeft=0;
				
			cXpix = (result.split("\n").length)*(textareafontsize+3);
			cYpix = (result.length-result.lastIndexOf("\n")-1)*(textreafontwidth+1);
			taXpix = (object.rows+1)*(textareafontsize+3);
			taYpix = object.clientWidth;
				
			if ((cXpix>pXpix)&&(cXpix<(pXpix+taXpix))) object.scrollTop=pXpix;
			if (cXpix<=pXpix) object.scrollTop=cXpix-(textareafontsize+3);
			if (cXpix>=(pXpix+taXpix)) object.scrollTop=cXpix-taXpix;
				
			if ((cYpix>=pYpix)&&(cYpix<(pYpix+taYpix))) object.scrollLeft=pYpix;
			if (cYpix<pYpix) object.scrollLeft=cYpix-(textreafontwidth+1);
			if (cYpix>=(pYpix+taYpix)) object.scrollLeft=cYpix-taYpix+1;
		}
		return true;
	} else if (IE){
		if (isChat){
			var code = frames['input'].event.keyCode;
			if(code == 13){
				return;
			}
			txt=String.fromCharCode(code);
			cursor_pos_selection = frames['input'].document.selection.createRange();
			cursor_pos_selection.text="";
			cursor_pos_selection.moveStart("character",-1);
			vorTxt = cursor_pos_selection.text;
			if (vorTxt.length>1){
				vorTxt="";
			}
			frames['input'].event.keyCode = 0;
			if (richtung==2){
				result = vorTxt+translitBuchstabeLat(txt)
			}else{
				result = translitBuchstabeCyr(vorTxt,txt)
			}
			if (vorTxt!=""){
				cursor_pos_selection.select(); cursor_pos_selection.collapse();
			}
			with(frames['input'].document.selection.createRange()){
				text = result; collapse(); select()
			}	
		} else {
			var code = event.keyCode;
			if(code == 13){
				return;
			}
			txt=String.fromCharCode(code);
			cursor_pos_selection = document.selection.createRange();
			cursor_pos_selection.text="";
			cursor_pos_selection.moveStart("character",-1);
			vorTxt = cursor_pos_selection.text;
			if (vorTxt.length>1){
				vorTxt="";
			}
			event.keyCode = 0;
			if (richtung==2){
				result = vorTxt+translitBuchstabeLat(txt)
			}else{
				result = translitBuchstabeCyr(vorTxt,txt)
			}
			if (vorTxt!=""){
				cursor_pos_selection.select(); cursor_pos_selection.collapse();
			}
			with(document.selection.createRange()){
				text = result; collapse(); select()
			}	
		}
		return;
   }
}


function translateAlltoCyrillic(){
	if (!IE){
		txt = TxtFeld.value;
		var txtnew = translitBuchstabeCyr("",txt.substr(0,1));
		var symb = "";
		for (kk=1;kk<txt.length;kk++){
			symb = translitBuchstabeCyr(txtnew.substr(txtnew.length-1,1),txt.substr(kk,1));
			txtnew = txtnew.substr(0,txtnew.length-1) + symb;
		}
		TxtFeld.value = txtnew;
		setFocus()
	}else{
		var is_selection_flag = 1;
		var userselection = document.selection.createRange();
		var txt = userselection.text;
		if (userselection==null || userselection.text==null || userselection.parentElement==null || userselection.parentElement().type!="textarea"){
			is_selection_flag = 0;
			txt = TxtFeld.value;
		}
		var txtnew = translitBuchstabeCyr("",txt.substr(0,1));
		var symb = "";
		for (kk=1;kk<txt.length;kk++){
			symb = translitBuchstabeCyr(txtnew.substr(txtnew.length-1,1),txt.substr(kk,1));
			txtnew = txtnew.substr(0,txtnew.length-1) + symb;
		}
		if (is_selection_flag){
			userselection.text = txtnew; userselection.collapse(); userselection.select();
		}else{
			TxtFeld.value = txtnew;
			setFocus()
		}
	}
	return;
}
(function($) {
var textarea, staticOffset; 
var iLastMousePos = 0;
var iMin = 32;
var grip;
$.fn.TextAreaResizer = function() {
return this.each(function() {
textarea = $(this).addClass('processed'), staticOffset = null;
$(this).wrap('<div class="resizable-textarea"><span></span></div>')
.parent().append($('<div class="grippie"></div>').bind("mousedown",{el: this} , startDrag));
var grippie = $('div.grippie', $(this).parent())[0];
grippie.style.marginRight = (grippie.offsetWidth - $(this)[0].offsetWidth) +'px';
});	};

function startDrag(e) {
textarea = $(e.data.el);
textarea.blur();
iLastMousePos = mousePosition(e).y;
staticOffset = textarea.height() - iLastMousePos;
textarea.css('opacity', 0.25);
$(document).mousemove(performDrag).mouseup(endDrag);
return false;
}

function performDrag(e) {
var iThisMousePos = mousePosition(e).y;
var iMousePos = staticOffset + iThisMousePos;
if (iLastMousePos >= (iThisMousePos)) {
iMousePos -= 5;
}
iLastMousePos = iThisMousePos;
iMousePos = Math.max(iMin, iMousePos);
textarea.height(iMousePos + 'px');
if (iMousePos < iMin) {
endDrag(e);
}
return false;
}

function endDrag(e) {
$(document).unbind('mousemove', performDrag).unbind('mouseup', endDrag);
textarea.css('opacity', 1);
textarea.focus();
textarea = null;
staticOffset = null;
iLastMousePos = 0;
}
function mousePosition(e) {
return { x: e.clientX + document.documentElement.scrollLeft, y: e.clientY + document.documentElement.scrollTop };
};
})(jQuery);

$(document).ready(function() {
$('textarea.resizable:not(.processed)').TextAreaResizer();
});
</script>

<style>
div.grippie {
	background:#EEEEEE url("/pic/grippie.png") no-repeat scroll center 2px;
	border-color:#DDDDDD;
	border-style:solid;
	border-width:0pt 1px 1px;
	cursor:s-resize;
	height:9px;
	overflow:hidden;
}
</style>
<? } ?>
<textarea class = "editorinput" id = "<?=$name;?>" name = "<?=$name;?>" style = "width:100%;" rows = "<?=$col;?>" onfocus = "storeCaret(this);" onselect = "storeCaret(this);" onclick = "storeCaret(this);" onkeyup = "storeCaret(this);"><?=$content;?></textarea>


<script type="text/javascript">
var bbcode = new BBCode(document.<?=$form;?>.<?=$name;?>);
var ctrl = "ctrl";
bbcode.addTag("codeB", "b", null, "B", ctrl);
bbcode.addTag("codeBB", "bb", null, "N", ctrl);
bbcode.addTag("codePRE", "pre", null, "P", ctrl);
bbcode.addTag("codeHT", "hideback", null, "H", ctrl);
bbcode.addTag("codeMG", "marquee", null, "M", ctrl);
bbcode.addTag("codeLG1", "legend", null, "1", ctrl);
bbcode.addTag("codeMYT", "youtube", null, "1", ctrl);
bbcode.addTag("codeLG2", function(e) { var v=e.value; e.selectedIndex=0; return "legend=Заголовок" }, "/legend", "2", ctrl);
bbcode.addTag("codeHIDE", "hide", null, "", ctrl);
bbcode.addTag("codeHIG", "highlight", null, "", ctrl);
bbcode.addTag("codeI", "i", null, "I", ctrl);
bbcode.addTag("codeU", "u", null, "U", ctrl);
bbcode.addTag("codeS", "s", null, "", ctrl);
bbcode.addTag("codeQuote", "quote", null, "Q", ctrl);
bbcode.addTag("codeImg", "img", null, "R", ctrl);
bbcode.addTag("codeUr", "url=введите ссылку", "/url", "", ctrl);
bbcode.addTag("codeCode", "php", null, "K", ctrl);
bbcode.addTag("codeFlash", "flash", null, "F", ctrl);
bbcode.addTag("codeOpt", "li", "", "0", ctrl);
bbcode.addTag("codeHR","hr", "", "8", ctrl);
bbcode.addTag("codeBR","br", "", "", ctrl);
bbcode.addTag("codeSpoiler", "spoiler", null, "S",  ctrl);
bbcode.addTag("fontFace", function(e) { var v=e.value; e.selectedIndex=0; return "font="+v+"" }, "/font");
bbcode.addTag("codeColor", function(e) { var v=e.value; e.selectedIndex=0; return "color="+v }, "/color");
bbcode.addTag("codeSize", function(e) { var v=e.value; e.selectedIndex=0; return "size="+v }, "/size");
bbcode.addTag("codeAlign", function(e) { var v=e.value; e.selectedIndex=0; return "align="+v }, "/align");
</script>


</div>

</td>
</tr>

<tr><td style="margin: 0px; padding: 0px;" align="center" class="b"><input type="button" name="preview" class="btn" title="ALT+ENTER Предпросмотр просмотр" value="Предпросмотр" onclick="javascript:ajaxpreview('<?=$name;?>');"/> <input type="reset" class="btn" value="Обратить изменения"/></td></tr>
<tr><td id="preview" style="margin: 0px; padding: 0px;" class="a"></td></tr>

</table>

<?
}


function get_row_count($table, $suffix = "")
{
  if ($suffix)
    $suffix = " $suffix";
  ($r = sql_query("SELECT COUNT(*) FROM $table$suffix")) or die(mysql_error());
  ($a = mysql_fetch_row($r)) or die(mysql_error());
  return $a[0];
}

function stdmsg($heading = '', $text = '', $div = 'success', $htmlstrip = false) {
    if ($htmlstrip) {
        $heading = htmlspecialchars_uni(trim($heading));
        $text = htmlspecialchars_uni(trim($text));
    }
    print("<table class=\"main\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"embedded\">\n");
    print("<div class=\"$div\">".($heading ? "<b>$heading</b><br />" : "")."$text</div></td></tr></table>\n");
}

function stderr($heading = '', $text = '') {
	stdhead();
	stdmsg($heading, $text, 'error');
	stdfoot();
	die;
}

function newerr($heading = '', $text = '', $head = true, $foot = true, $die = true, $div = 'error', $htmlstrip = true) {
	if ($head)
		stdhead($heading);

	newmsg($heading, $text, $div, $htmlstrip);

	if ($foot)
		stdfoot();

	if ($die)
		die;
}

function sqlerr($file = '', $line = '') {
	global $queries;
	print("<table border=\"0\" bgcolor=\"blue\" align=\"left\" cellspacing=\"0\" cellpadding=\"10\" style=\"background: blue\">" .
	"<tr><td class=\"embedded\"><font color=\"white\"><h1>Ошибка в SQL</h1>\n" .
	"<b>Ответ от сервера MySQL: " . htmlspecialchars_uni(mysql_error()) . ($file != '' && $line != '' ? "<p>в $file, линия $line</p>" : "") . "<p>Запрос номер $queries.</p></b></font></td></tr></table>");


mysql_errors($file,$line,$queries,htmlspecialchars_uni(mysql_error()));

	die;
}

function mysql_errors($file,$line,$queries,$mysqlerror){ 
 global $queries,$CURUSER; 
$error = $mysqlerror. ($file != '' && $line != '' ? " в $file, линия $line" : "") . "Запрос номер $queries."; 
$time = sqlesc(get_date_time()); 
$page = sqlesc($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); 
$ip = sqlesc($_SERVER['REMOTE_ADDR']); 
 if($CURUSER){ 
$id = sqlesc($CURUSER['id']); 
$name = sqlesc($CURUSER['username']); 
 }else{ 
$id = 0; 
$name = 0; 
 } 
$sql = sql_query("Insert into mysql_error (error, time, page, ip, userid, username) value (".sqlesc($error)."," .$time. ",".$page.",".$ip.",".$id.",".$name.")"); 
}

// Returns the current time in GMT in MySQL compatible format.
function get_date_time($timestamp = 0) {
	if ($timestamp)
		return date("Y-m-d H:i:s", $timestamp);
	else
		return date("Y-m-d H:i:s");
}

function encodehtml($s, $linebreaks = true) {
	$s = str_replace("<", "&lt;", str_replace("&", "&amp;", $s));
	if ($linebreaks)
		$s = nl2br($s);
	return $s;
}

function get_dt_num() {
	return date("YmdHis");
}

function format_urls($s)
{
	return preg_replace(
    	"/(\A|[^=\]'\"a-zA-Z0-9])((http|ftp|https|ftps|irc):\/\/[^()<>\s]+)/i",
	    "\\1<a href=\"away.php?to=\\2\" target='_blank'>\\2</a>", $s);
}

//Finds last occurrence of needle in haystack
//in PHP5 use strripos() instead of this
function _strlastpos ($haystack, $needle, $offset = 0)
{
	$addLen = strlen ($needle);
	$endPos = $offset - $addLen;
	while (true)
	{
		if (($newPos = strpos ($haystack, $needle, $endPos + $addLen)) === false) break;
		$endPos = $newPos;
	}
	return ($endPos >= 0) ? $endPos : false;
}

function format_quotes($s) {
	while ($old_s != $s) {
		$old_s = $s;

		//find first occurrence of [/quote]
		$close = strpos($s, "[/quote]");
		if ($close === false)
			return $s;

		//find last [quote] before first [/quote]
		//note that there is no check for correct syntax
		$open = _strlastpos(substr($s, 0, $close), "[quote");
		if ($open === false)
			return $s;

		$quote = substr($s, $open, $close - $open + 8);

		//[quote]Text[/quote]
		$quote = preg_replace(
			"/\[quote\]\s*((\s|.)+?)\s*\[\/quote\]\s*/i",
			"<p class=sub><b>Quote:</b></p><table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\"><tr><td style=\"border: 1px black dotted\">\\1</td></tr></table><br />",
			$quote);

		//[quote=Author]Text[/quote]
		$quote = preg_replace(
			"/\[quote=(.+?)\]\s*((\s|.)+?)\s*\[\/quote\]\s*/i",
			"<p class=sub><b>\\1 wrote:</b></p><table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\"><tr><td style=\"border: 1px black dotted\">\\2</td></tr></table><br />",
			$quote);

		$s = substr($s, 0, $open) . $quote . substr($s, $close + 8);
	}

	return $s;
}

// Format quote
function encode_quote($text) {
	$start_html = "<div align=\"center\"><div style=\"width: 85%; overflow: auto\">"
		. "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\" class=\"bgcolor4\">"
		. "<tr bgcolor=\"FFE5E0\"><td><font class=\"block-title\">Цитата</font></td></tr><tr class=\"bgcolor1\"><td>";
	$end_html = "</td></tr></table></div></div>";
	$text = preg_replace("#\[quote\](.*?)\[/quote\]#si", "" . $start_html . "\\1" . $end_html . "", $text);
	return $text;
}

// Format quote from
function encode_quote_from($text) {
	$start_html = "<div align=\"center\"><div style=\"width: 85%; overflow: auto\">"
		. "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\" class=\"bgcolor4\">"
		. "<tr bgcolor=\"FFE5E0\"><td><font class=\"block-title\">\\1 писал</font></td></tr><tr class=\"bgcolor1\"><td>";
	$end_html = "</td></tr></table></div></div>";
	$text = preg_replace("#\[quote=(.+?)\](.*?)\[/quote\]#si", "" . $start_html . "\\2" . $end_html . "", $text);
	return $text;
}

// Format spoiler from
function encode_spoiler_from($text) {
        $q = time().mt_rand(1, 1024);

$replace = "<script language=\"javascript\" type=\"text/javascript\" src=\"js/spoiler.js\"></script><div class=\"spoiler_head\" onclick=\"javascript:showspoiler('".$q."')\"><img border=\"0\" src=\"pic/plus.gif\" id=\"pic".$q."\" title=\"Показать\">&nbsp;&nbsp;\\1</div><div class=\"spoiler_body\" style=\"display:none;\" id=".$q." name=".$q.">\\2</div>";

	$text = preg_replace("#\[spoiler=((\s|.)+?)\]((\s|.)+?)\[\/spoiler\]#si", $replace, $text);
	return $text;
}


function encode_spoiler_hide($text) {
        $q = time().mt_rand(1, 1024);
	$replace = "<script language=\"javascript\" type=\"text/javascript\" src=\"js/spoiler.js\"></script><div class=\"spoiler_head\" onclick=\"javascript:showspoiler('".$q."')\"><img border=\"0\" src=\"pic/plus.gif\" id=\"pic".$q."\" title=\"Показать\">&nbsp;&nbsp;Скрытый текст</div><div class=\"spoiler_body\" style=\"display:none;\" id=".$q." name=".$q.">\\1</div>";
	$text = preg_replace("#\[hide\]((\s|.)+?)\[\/hide\]#si", $replace, $text);
	return $text;
}


function encode_spoiler_from_hide($text) {
        $q = time().mt_rand(1, 1024);
	$replace = "<script language=\"javascript\" type=\"text/javascript\" src=\"js/spoiler.js\"></script><div class=\"spoiler_head\" onclick=\"javascript:showspoiler('".$q."')\"><img border=\"0\" src=\"pic/plus.gif\" id=\"pic".$q."\" title=\"Показать\">&nbsp;&nbsp;\\1</div><div class=\"spoiler_body\" style=\"display:none;\" id=".$q." name=".$q.">\\2</div>";
	$text = preg_replace("#\[hide=((\s|.)+?)\]((\s|.)+?)\[\/hide\]#si", $replace, $text);
	return $text;
}



// Format code
function encode_code($text) {
	$start_html = "<div align=\"center\"><div style=\"width: 85%; overflow: auto\">"
	."<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\" class=\"bgcolor4\">"
	."<tr bgcolor=\"E5EFFF\"><td colspan=\"2\"><font class=\"block-title\">Код</font></td></tr>"
	."<tr class=\"bgcolor1\"><td align=\"right\" class=\"code\" style=\"width: 5px; border-right: none\">{ZEILEN}</td><td class=\"code\">";
	$end_html = "</td></tr></table></div></div>";
	$match_count = preg_match_all("#\[code\](.*?)\[/code\]#si", $text, $matches);
    for ($mout = 0; $mout < $match_count; ++$mout) {
      $before_replace = $matches[1][$mout];
      $after_replace = $matches[1][$mout];
      $after_replace = trim ($after_replace);
      $zeilen_array = explode ("<br />", $after_replace);
      $j = 1;
      $zeilen = "";
      foreach ($zeilen_array as $str) {
        $zeilen .= "".$j."<br />";
        ++$j;
      }
      $after_replace = str_replace ("", "", $after_replace);
      $after_replace = str_replace ("&amp;", "&", $after_replace);
      $after_replace = str_replace ("", "&nbsp; ", $after_replace);
      $after_replace = str_replace ("", " &nbsp;", $after_replace);
      $after_replace = str_replace ("", "&nbsp; &nbsp;", $after_replace);
      $after_replace = preg_replace ("/^ {1}/m", "&nbsp;", $after_replace);
      $str_to_match = "[code]".$before_replace."[/code]";
      $replace = str_replace ("{ZEILEN}", $zeilen, $start_html);
      $replace .= $after_replace;
      $replace .= $end_html;
      $text = str_replace ($str_to_match, $replace, $text);
    }

    $text = str_replace ("[code]", $start_html, $text);
    $text = str_replace ("[/code]", $end_html, $text);
    return $text;
}

function encode_php($text) {
	$start_html = "<div align=\"center\"><div style=\"width: 85%; overflow: auto\">"
	."<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\" class=\"bgcolor4\">"
	."<tr bgcolor=\"F3E8FF\"><td colspan=\"2\"><font class=\"block-title\">PHP - Код</font></td></tr>"
	."<tr class=\"bgcolor1\"><td align=\"right\" class=\"code\" style=\"width: 5px; border-right: none\">{ZEILEN}</td><td>";
	$end_html = "</td></tr></table></div></div>";
	$match_count = preg_match_all("#\[php\](.*?)\[/php\]#si", $text, $matches);
    for ($mout = 0; $mout < $match_count; ++$mout) {
        $before_replace = $matches[1][$mout];
        $after_replace = $matches[1][$mout];
        $after_replace = trim ($after_replace);
		$after_replace = str_replace("&lt;", "<", $after_replace);
		$after_replace = str_replace("&gt;", ">", $after_replace);
		$after_replace = str_replace("&quot;", '"', $after_replace);
		$after_replace = preg_replace("/<br.*/i", "", $after_replace);
		$after_replace = (substr($after_replace, 0, 5 ) != "<?php") ? "<?php\n".$after_replace."" : "".$after_replace."";
		$after_replace = (substr($after_replace, -2 ) != "?>") ? "".$after_replace."\n?>" : "".$after_replace."";
        ob_start ();
        highlight_string ($after_replace);
        $after_replace = ob_get_contents ();
        ob_end_clean ();
		$zeilen_array = explode("<br />", $after_replace);
        $j = 1;
        $zeilen = "";
      foreach ($zeilen_array as $str) {
        $zeilen .= "".$j."<br />";
        ++$j;
      }
		$after_replace = str_replace("\n", "", $after_replace);
		$after_replace = str_replace("&amp;", "&", $after_replace);
		$after_replace = str_replace("  ", "&nbsp; ", $after_replace);
		$after_replace = str_replace("  ", " &nbsp;", $after_replace);
		$after_replace = str_replace("\t", "&nbsp; &nbsp;", $after_replace);
		$after_replace = preg_replace("/^ {1}/m", "&nbsp;", $after_replace);
		$str_to_match = "[php]".$before_replace."[/php]";
		$replace = str_replace("{ZEILEN}", $zeilen, $start_html);
      $replace .= $after_replace;
      $replace .= $end_html;
      $text = str_replace ($str_to_match, $replace, $text);
    }
	$text = str_replace("[php]", $start_html, $text);
	$text = str_replace("[/php]", $end_html, $text);
    return $text;
}
function format_comment($s, $connect_smilies = false) {

	global $smilies, $privatesmilies, $CURUSER, $DEFAULTBASEURL, $tracker_lang;
	$smiliese = $smilies;
//	$s = $text;

  // This fixes the extraneous ;) smilies problem. When there was an html escaped
  // char before a closing bracket - like >), "), ... - this would be encoded
  // to &xxx;), hence all the extra smilies. I created a new :wink: label, removed
  // the ;) one, and replace all genuine ;) by :wink: before escaping the body.
  // (What took us so long? :blush:)- wyz

	//$s = str_replace(";)", ":wink:", $s);

$host = basename($_SERVER['SCRIPT_FILENAME']);
$site = parse_url($DEFAULTBASEURL, PHP_URL_HOST);

$s = str_replace(";)", ";-)", $s);

$counter = 0;
$match_count = preg_match_all("#\[bb\](.*?)\[/bb\]#si", $s, $matches);

if ($match_count) {

for ($mout = 0; $mout < $match_count; ++$mout){

$s_html = "<div style=\"width: 100%; overflow: auto\" align=\"center\"><table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">
<tr><td colspan=\"2\" class=\"a\"><font class=\"block-title\">".$tracker_lang['bb_sourseb']."</font></td></tr>
<tr><td class=\"b\">";
$e_html = "</td></tr></table></div>";

$t_b = str_replace("\n", "<br />", $matches[1][$mout]);
$add_text[] = $s_html.$t_b.$e_html;
++$counter;
}
}

if (in_array($host, array("message.php", "details.php", "comment.php")) && preg_match("#\[php\](.*?)\[/php\]#si", $s))
$s = encode_php($s);

$s = preg_replace("/\[b\](.*?)\[\/b\]/is", "<strong>\\1</strong>", $s); /// [b]жирный[/b]
$s = preg_replace("/\[i\](.*?)\[\/i\]/is", "<i>\\1</i>", $s); /// [i]курсив[/i]
$s = preg_replace("/\[h\](.*?)\[\/h\]/is", "<h3>\\1</h3>", $s); /// [h]крупный[/h]
$s = preg_replace("/\[u\](.*?)\[\/u\]/is", "<u>\\1</u>", $s); /// [u]подчеркнутый[/u]
$s = preg_replace("#\[s\](.*?)\[/s\]#si", "<s>\\1</s>", $s); /// [s]зачеркнутый[/s]
$s = preg_replace("#\[li\]#si", "<li>", $s); /// [li]
$s = preg_replace("#\[hr\]#si", "<hr>", $s); /// [hr]
$s = preg_replace("#\[br\]#si", "<br />", $s); /// [br]
$s = preg_replace("#\[(left|right)\](.*?)\[/\\1\]#is", "<div style=\"float:\\1;\">\\2</div>", $s);
$s = preg_replace("#\[(center|justify)\](.*?)\[/\\1\]#is", "<div align=\"\\1\">\\2</div>", $s);

$s = preg_replace("#\[align=(left|right)\](.*?)\[/align\]#is", "<div style=\"float:\\1;\">\\2</div>", $s);
$s = preg_replace("#\[align=(center|justify)\](.*?)\[/align\]#is", "<div align=\"\\1\">\\2</div>", $s);
$s = preg_replace("#\[size=([0-9]+)\](.*?)\[/size\]#si", "<span style=\"font-size: \\1\">\\2</span>", $s); /// [size=4]Text[/size]
$s = preg_replace("/\[font=([a-zA-Z ,]+)\](.*?)\[\/font\]/is","<font face=\"\\1\">\\2</font>", $s); /// [font=Arial]Text[/font]

$s = str_replace ("[pi]", "<div align=\"center\" style=\"font-size: 25px; width:auto; position:relative; float:center;\">&#8604; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &#9986; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &ndash; &#8605;</div>", $s); /// [pi]
$s = str_replace ("[me]", "<script type=\"text/javascript\" src=\"/js/blink.js\"></script><blink><font color=\"red\">IMHO</font></blink>&nbsp;", $s); /// [me]

$s = preg_replace("/\[audio\]([^()<>\s]+?)\[\/audio\]/is", "<embed autostart=\"false\" loop=\"false\" controller=\"true\" width=\"220\" height=\"42\" src=\"\\1\"></embed>", $s); /// [audio]http://allsiemens.com/mp3/files/1.mp3[/audio]

$s = preg_replace("#\[img\](?!javascript:)([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#i", "<img class=\"linked-image\" src=\"\\1\" border=\"0\" alt=\"\\1\" title=\"\\1\" />", $s); /// [img]http://www/image.gif[/img]

$s = preg_replace("/\[img=(http:\/\/[^\s'\"<>]+(\.(gif|jpeg|jpg|png)))\]/is", "<img border=\"0\" src=\"\\1\" alt=\"\\1\" />", $s); /// [img=http://www/image.gif]

$s = preg_replace("/\[color=([a-zA-Z]+)\](.*?)\[\/color\]/is", "<font color=\"\\1\">\\2</font>", $s); /// [color=blue]Текст[/color]
/////////////////////////////////Tag [youtube][/youtube] writed by RoBoT 
while (preg_match("/\[youtube\]((\s|.)+?)\[\/youtube\]/i", $s)) { 
$s = str_replace("watch?v=","v/", $s); 
$s = preg_replace ("/\[youtube\]((\s|.)+?)\[\/youtube\]/i", "<object width='640' height='505'><param name=movie value='\\1&hl=ru&fs=1&'></param><param name='allowFullScreen' value='true'></param><param name='allowscriptaccess' value='always'></param><embed width='500' height='300' src='\\1&hl=ru&fs=1&' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='640' height='505'></embed></object>", $s); 
} 
///////////////////////////////////end tag youtube    
$s = preg_replace("/\[color=(#[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9])\](.*?)\[\/color\]/is", "<font color=\"\\1\">\\2</font>", $s); /// [color=#ffcc99]Текст[/color]


$lnk = array_map('trim', explode(',', $tracker_lang['lnk_array']));

$s = preg_replace("/\[url=javascript:(.+?)\](.*?)\[\/url\]/is", "\\2", $s);

if ($host == "shoutbox.php"){

$s = preg_replace("/\[url=((http|https):\/\/[^()<>\s]+?)\](.*?)\[\/url\]/is", "<a href=\"\\1\" target=\"_blank\" rel=\"nofollow\">\\3</a>", $s); /// [url=http://www.example.com]Пример[/url]
$s = preg_replace("/\[url\]((http|https):\/\/[^()<>\s]+?)\[\/url\]/is", "<a href=\"\\1\" rel=\"nofollow\" target=\"_blank\">\\1</a>", $s); /// [url]http://www.example.com[/url]

} else {


$s = preg_replace("/\[url=((http|https):\/\/".$site."+(\/|.*?))\](.*?)\[\/url\]/is", "<a title=\"".$site."\" href=\"\\1\" rel=\"nofollow\">\\4</a>", $s); /// для ссылок вида: http://localhost
$s = preg_replace("/\[url\]((http|https):\/\/".$site."+(\/|.*?))\[\/url\]/is", "<a href=\"\\1\" title=\"".$site."\" rel=\"nofollow\">\\1</a>", $s); /// для ссылок вида: http://localhost

$s = preg_replace("/\[url\]((http|https):\/\/[^()<>\s]+?)\[\/url\]/is", "<a target=\"blank\" href=\"\\1\" rel=\"nofollow\">\\1</a>", $s);
$s = preg_replace("/\[url=((http|https):\/\/[^()<>\s]+?)\](.*?)\[\/url\]/is", "<a target=\"blank\" href=\"\\1\" rel=\"nofollow\">\\3</a>", $s);

}

$s = preg_replace("/\[url=([a-zA-Z]+.php(.*?))\](.*?)\[\/url\]/is", "<a target=\"blank\" href=\"\\1\" rel=\"nofollow\">\\3</a>", $s); /// [url=browse.php?search=Хорошее]Хорошее[/url]
//$s = preg_replace("/(\\n|\\s|^)((http|ftp|https|ftps):\/\/[^()<>\s]+)/is", "\\1<a target=\"blank\" href=\"\\2\" rel=\"nofollow\">\\2</a>", $s);




	foreach ($smiliese as $code => $url)
		$s = str_replace($code, "<img border=\"0\" src=\"pic/smilies/$url\" alt=\"" . htmlspecialchars_uni($code) . "\">", $s);

	foreach ($privatesmilies as $code => $url)
		$s = str_replace($code, "<img border=\"0\" src=\"pic/smilies/$url\">", $s);

if ($host == 'details.php' && preg_match("#\[hide\](.*?)\[/hide\]#si", $s))
$s = comment_hide($s);

$s = nl2br($s);

$s = preg_replace("/\[pre\](.*?)\[\/pre\]/is", "<pre>".htmlspecialchars('\\1')."</pre>", $s); /// [pre]Preformatted[/pre]

$s = preg_replace("/\[highlight\](.*?)\[\/highlight\]/is", "<table border=\"0\" cellspacing=\"0\" cellpadding=\"1\"><tr><td bgcolor=\"white\"><b>\\1</b></td></tr></table>", $s); /// [highlight]Highlight text[/highlight]

$s = preg_replace("/\[hideback\](.*?)\[\/hideback\]/is", "<span onmouseout=\"this.style.color='#DDDDDD';\" onmouseover=\"this.style.color='#002AFF';\" style=\"background: #DDDDDD none repeat scroll 0% 0%;font-weight: bold; font-size: small;  color: #DDDDDD; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; cursor: help;\">\\1</span>", $s);

$s = preg_replace("/\[legend=(.*?)\](.*?)\[\/legend\]/is", "<fieldset><legend>\\1</legend>\\2</fieldset>", $s);
$s = preg_replace("/\[legend\](.*?)\[\/legend\]/is", "<fieldset>\\1</fieldset>", $s);  //<legend></legend>
$s = preg_replace("/\[marquee\](.*?)\[\/marquee\]/is", "<marquee behavior=\"alternate\">\\1</marquee>", $s); /// [marquee]Marquee[/marquee]

$s = preg_replace("/\[flash=(\d{1,3}):(\d{1,3})\]((www.|http:\/\/|https:\/\/)[^\s]+(\.swf))\[\/flash\]/is", "<param name=\"movie\" value=\\3/><embed width=\"\\1\" height=\"\\2\" src=\"\\3\"></embed>", $s, 3); ///[flash=320x240]http://somesite.com/test.swf[/flash]

$s = preg_replace("/\[flash]((www.|http:\/\/|https:\/\/)[^\s'\"<>&]+(\.swf))\[\/flash\]/is", "<param name=\"movie\" value=\"\\1\" /><embed width=\"470\" height=\"310\" src=\\1></embed>", $s, 3); ///[flash]http://somesite.com/test.swf[/flash]

$s = preg_replace("/\[mcom=(#[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9]):(#[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9])\](.*?)\[\/mcom\]/is", "<div style=\"background-color: \\1; color: \\2; font-weight: bold; font-size: small;\">\\3</div>", $s); /// [mcom=#FFD42A:#002AFF]Text[/mcom]

$s = wordwrap($s, 150, "\n", 1); //linebreak - по умолчанию 75 - было 100

$s = preg_replace("/\[video=(http:\/\/www.(youtube-nocookie.com|youtube.com).*?(\/v\/|watch\?v=))(.*?)\]/is", "<object width='640' height='505'><param name=movie value='http://www.youtube-nocookie.com/v/\\4&hl=ru&hd=1'></param><param name='allowFullScreen' value='true'></param><param name='allowscriptaccess' value='always'></param><embed src='http://www.youtube-nocookie.com/v/\\4&hl=ru&hd=1' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='640' height='505'></embed></object>", $s); /// youtube тег video, длинные ссылки вида (http://www.youtube.com/watch?v=xPzblzRq6kM или http://www.youtube-nocookie.com/v/SWnmDRM0e18)

$s = preg_replace("/\[video=(http:\/\/youtu.be\/(.*?))\]/is", "<object width='640' height='505'><param name=movie value='http://www.youtube-nocookie.com/v/\\2&hl=ru&hd=1'></param><param name='allowFullScreen' value='true'></param><param name='allowscriptaccess' value='always'></param><embed src='http://www.youtube-nocookie.com/v/\\2&hl=ru&hd=1' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='640' height='505'></embed></object>", $s); /// youtube тег video, короткая ссылка вида (http://youtu.be/xPzblzRq6kM?hd=1)


//[spoiler][/spoiler] 
while (preg_match("#\[spoiler]((\s|.)+?)\[/spoiler\]#is", $s)) 
{ 
        $q = time().mt_rand(1, 1024); 
        $s = preg_replace("/\[spoiler]((\s|.)+?)\[\/spoiler\]/i", "<div class=\"spoiler_head\" onclick=\"javascript:showspoiler('".$q."')\"><img border=\"0\" src=\"pic/plus.gif\" id=\"pic".$q."\" title=\"Показать\">&nbsp;&nbsp;Скрытый текст</div><div class=\"spoiler_body\" style=\"display:none;\" id=".$q." name=".$q.">\\1</div>", $s, 1); 
}  


	while (preg_match("#\[quote\](.*?)\[/quote\]#si", $s)) {
		$s = encode_quote($s);
	}
	while (preg_match("#\[quote=(.+?)\](.*?)\[/quote\]#si", $s)) {
		$s = encode_quote_from($s);
	}
while (preg_match("#\[spoiler=((\s|.)+?)\]((\s|.)+?)\[/spoiler\]#is", $s)) 
{ 
        $q = time().mt_rand(1, 1024); 
        $s = preg_replace("/\[spoiler=((\s|.)+?)\]((\s|.)+?)\[\/spoiler\]/i", "<div class=\"spoiler_head\" onclick=\"javascript:showspoiler('".$q."')\"><img border=\"0\" src=\"pic/plus.gif\" id=\"pic".$q."\" title=\"Показать\">&nbsp;&nbsp;\\1</div><div class=\"spoiler_body\" style=\"display:none;\" id=".$q." name=".$q.">\\3</div>", $s, 1); 
}   


		/*if ($connect_smilies == true) {

global $smilies;
$num = 0;

reset($smilies);
while (list($code, $url) = each($smilies))
if ($host == "shoutbox.php")
$s = str_replace($code, "<img title=\"Click me!\" border=\"0\" onclick=\"parent.document.shoutform.shout.focus();parent.document.shoutform.shout.value=parent.document.shoutform.shout.value+'".htmlspecialchars_uni($code)."';return false;\" style=\"font-weight: italic;\" src=\"/pic/smilies/".$url."\" alt=\"".htmlspecialchars_uni($code)."\">", $s);
else
$s = str_replace($code, "<img border=\"0\" src=\"/pic/smilies/".$url."\">", $s);
unset($smilies); }*/



if (!empty($counter)) {
$is = 0;
while ($is < $counter) {
$s = preg_match("#\[bb\](.*?)\[/bb\]#si", $add_text[$is], $s, 1);
++$is;
}
}

return $s;

}
/*
function format_comment($text, $strip_html = true) {
/*
//Цензура мата by Kreker666 
    $s = preg_replace('/хуй/i', 'где моя циркулярная пила?мне надо кое-что в туалете себе отрезать', $s); 
    $s = preg_replace('/хуйня/i', 'бред', $s); 
    $s = preg_replace('/жжош/i', 'круто', $s); 
    $s = preg_replace('/админ/i', 'царь во дворце', $s); 

$badwords = array();
 $badwords[] = "бля";
 $badwords[] = "блябля";
 $badwords[] = "хуй";

 $s = str_replace($badwords,"---",$s);
*/ /*
	global $smilies, $privatesmilies, $me, $movie, $mat, $zamena;
	$smiliese = $smilies;
	$s = $text;

  // This fixes the extraneous ;) smilies problem. When there was an html escaped
  // char before a closing bracket - like >), "), ... - this would be encoded
  // to &xxx;), hence all the extra smilies. I created a new :wink: label, removed
  // the ;) one, and replace all genuine ;) by :wink: before escaping the body.
  // (What took us so long? :blush:)- wyz

	$s = str_replace(";)", ":wink:", $s);

	if ($strip_html)
		$s = htmlspecialchars_uni($s);

	$bb[] = "#\[img\](?!javascript:)([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#i";
	$html[] = "<img class=\"linked-image\" src=\"\\1\" border=\"0\" alt=\"\\1\" title=\"\\1\" />";
	$bb[] = "#\[img=([a-zA-Z]+)\](?!javascript:)([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
	$html[] = "<img class=\"linked-image\" src=\"\\2\" align=\"\\1\" border=\"0\" alt=\"\\2\" title=\"\\2\" />";
	$bb[] = "#\[img\ alt=([a-zA-Zа-яА-Я0-9\_\-\. ]+)\](?!javascript:)([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
	$html[] = "<img class=\"linked-image\" src=\"\\2\" align=\"\\1\" border=\"0\" alt=\"\\1\" title=\"\\1\" />";
	$bb[] = "#\[img=([a-zA-Z]+) alt=([a-zA-Zа-яА-Я0-9\_\-\. ]+)\](?!javascript:)([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
	$html[] = "<img class=\"linked-image\" src=\"\\3\" align=\"\\1\" border=\"0\" alt=\"\\2\" title=\"\\2\" />";
	$bb[] = "#\[kp=([0-9]+)\]#is";
	$html[] = "<a href=\"http://www.kinopoisk.ru/level/1/film/\\1/\" rel=\"nofollow\"><img src=\"http://www.kinopoisk.ru/rating/\\1.gif/\" alt=\"Кинопоиск\" title=\"Кинопоиск\" border=\"0\" /></a>";

	$bb[] = "#\[imdb\]((\s|.)+?)\[\/imdb\]#i";
	$html[] = "<a href=\"http://www.imdb.com/title/\\1/\" rel=\"nofollow\"><img src=\"imdb.php?\\1\" alt=\"Кинопоиск\" title=\"Кинопоиск\" border=\"0\" /></a>";

	$bb[] = "#\[url\]([\w]+?://([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
	$html[] = "<a href=\"away.php?to=\\1\" target='_blank' title=\"\\1\">\\1</a>";
	$bb[] = "#\[url\]((www|ftp)\.([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
	$html[] = "<a href=\"\\1\" title=\"\\1\">\\1</a>";
	$bb[] = "#\[url=([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
	$html[] = "<a href=\"away.php?to=\\1\"  target='_blank' title=\"\\1\">\\2</a>";
	$bb[] = "#\[url=((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
	$html[] = "<a href=\"away.php?to=\\1\" target='_blank' title=\"\\1\">\\3</a>";
	$bb[] = "/\[url=([^()<>\s]+?)\]((\s|.)+?)\[\/url\]/i";
	$html[] = "<a href=\"away.php?to=\\1\"  target='_blank'>\\2</a>";
	$bb[] = "/\[url\]([^()<>\s]+?)\[\/url\]/i";
	$html[] = "<a href=\"\\1\">\\1</a>";
	$bb[] = "#\[mail\](\S+?)\[/mail\]#i";
	$html[] = "<a href=\"mailto:\\1\">\\1</a>";
	$bb[] = "#\[mail\s*=\s*([\.\w\-]+\@[\.\w\-]+\.[\w\-]+)\s*\](.*?)\[\/mail\]#i";
	$html[] = "<a href=\"mailto:\\1\">\\2</a>";
	$bb[] = "#\[color=(\#[0-9A-F]{6}|[a-z]+)\](.*?)\[/color\]#si";
	$html[] = "<span style=\"color: \\1\">\\2</span>";
	$bb[] = "#\[(font|family)=([A-Za-z ]+)\](.*?)\[/\\1\]#si";
	$html[] = "<span style=\"font-family: \\2\">\\3</span>";
	$bb[] = "#\[size=([0-9]+)\](.*?)\[/size\]#si";
	$html[] = "<span id=\"r\\1\">\\2</span>";
	$bb[] = "#\[(left|right|center|justify)\](.*?)\[/\\1\]#is";
	$html[] = "<div align=\"\\1\">\\2</div>";
	$bb[] = "#\[b\](.*?)\[/b\]#si";
	$html[] = "<b>\\1</b>";
	$bb[] = "#\[i\](.*?)\[/i\]#si";
	$html[] = "<i>\\1</i>";
	$bb[] = "#\[u\](.*?)\[/u\]#si";
	$html[] = "<u>\\1</u>";
	$bb[] = "#\[s\](.*?)\[/s\]#si";
	$html[] = "<s>\\1</s>";
	$bb[] = "#\[li\]#si";
	$html[] = "<li>";
	$bb[] = "#\[hr\]#si";
	$html[] = "<hr>";
	// Dirty YouTube hack...
	// Yeah, unsecure! Hello noobies! :D
	#$bb[] = "#\[youtube=([a-zA-Z0-9]+)\]#si";
	#$html = "<object width=\"480\" height=\"385\"><param name=\"movie\" value=\"http://www.youtube.com/v/\\1?fs=1&amp;rel=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/\\1?fs=1&amp;rel=0\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"480\" height=\"385\"></embed></object>";
	//$html[] = "<div class=\"spoiler-wrap\"><div class=\"spoiler-head folded clickable\">Скрытый текст</div><div class=\"spoiler-body\"><textarea>\\1</textarea></div></div>";
	//$bb[] = "/\[hide=\s*((\s|.)+?)\s*\]((\s|.)+?)\[\/hide\]/i";
	//$html[] = "<div class=\"spoiler-wrap\"><div class=\"spoiler-head folded clickable\">\\1</div><div class=\"spoiler-body\"><textarea>\\3</textarea></div></div>";

	$s = preg_replace($bb, $html, $s);

	// Linebreaks
	$s = nl2br($s);

/////////////////////////////////Tag [youtube][/youtube] writed by RoBoT 
while (preg_match("/\[youtube\]((\s|.)+?)\[\/youtube\]/i", $s)) { 
$s = str_replace("watch?v=","v/", $s); 
$s = preg_replace ("/\[youtube\]((\s|.)+?)\[\/youtube\]/i", "<object width='540' height='405'><param name=movie value='\\1?version=3&fs=1&hd=1&amp;hl=ru_RU&amp;rel=0'></param><param name='allowFullScreen' value='true'></param><param name='allowscriptaccess' value='always'></param><embed src='\\1?version=3&fs=1&hd=1&amp;hl=ru_RU&amp;rel=0' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='540' height='405'></embed></object>", $s); 
} 
///////////////////////////////////end tag youtube 

$s = str_replace(array("javascript", "alert", "<body", "<html"), "", $s);

// [movie].movie[/movie]   
// [movie]сюда вставляем прямую ссылку на FLY файл[/movie]   
// [movie]например http://intv.ru/uplay/dNqg6vD4LI[/movie]   
$s = preg_replace( "/\[video=([^()<>\s]+?)\]/i", "<object width=\"560\" height=\"349\"><param name=\"movie\" value=\"http://www.youtube.com/v/\\1?version=3&amp;hl=ru_RU&hd=1\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/\\1?version=3&amp;hl=ru_RU&hd=1\" type=\"application/x-shockwave-flash\" width=\"560\" height=\"349\" allowscriptaccess=\"always\" allowfullscreen=\"true\"></embed></object>", $s); 

$s = preg_replace( "/\[vk=([^()<>\s]+?)\]/i", "<iframe src=\"\\1\" width=\"607\" height=\"360\" frameborder=\"0\"></iframe>", $s); 


	while (preg_match("#\[quote\](.*?)\[/quote\]#si", $s)) $s = encode_quote($s);
	while (preg_match("#\[quote=(.+?)\](.*?)\[/quote\]#si", $s)) $s = encode_quote_from($s);
	while (preg_match("#\[hide\](.*?)\[/hide\]#si", $s)) $s = encode_spoiler_hide($s);
	while (preg_match("#\[hide=(.+?)\](.*?)\[/hide\]#si", $s)) $s = encode_spoiler_from_hide($s);
	if (preg_match("#\[code\](.*?)\[/code\]#si", $s)) $s = encode_code($s);
	if (preg_match("#\[php\](.*?)\[/php\]#si", $s)) $s = encode_php($s);

while (preg_match("#\[spoiler=((\s|.)+?)\]((\s|.)+?)\[/spoiler\]#is", $s)) 
{ 
        $q = time().mt_rand(1, 1024); 
        $s = preg_replace("/\[spoiler=((\s|.)+?)\]((\s|.)+?)\[\/spoiler\]/i", 
     "<script language=\"javascript\" type=\"text/javascript\" src=\"js/spoiler.js\"></script><div class=\"spoiler_head\" onclick=\"javascript:showspoiler('".$q."')\"><img border=\"0\" src=\"pic/plus.gif\" id=\"pic".$q."\" title=\"Показать\">&nbsp;&nbsp;\\1</div><div class=\"spoiler_body\" style=\"display:none;\" id=".$q." name=".$q.">\\3</div>", $s, 1); 
} 


while (preg_match("#\[hide=((\s|.)+?)\]((\s|.)+?)\[/hide\]#is", $s))
{
        $q = time().mt_rand(1, 1024);
        $s = preg_replace("/\[hide=((\s|.)+?)\]((\s|.)+?)\[\/hide\]/i", 
     "<script language=\"javascript\" type=\"text/javascript\" src=\"js/spoiler.js\"></script><div class=\"spoiler_head\" onclick=\"javascript:showspoiler('".$q."')\"><img border=\"0\" src=\"pic/plus.gif\" id=\"pic".$q."\" title=\"Показать\">&nbsp;&nbsp;\\1</div><div class=\"spoiler_body\" style=\"display:none;\" id=".$q." name=".$q.">\\3</div>", $s, 1);
}



/*
while (preg_match("#\[hide=((\s|.)+?)\]((\s|.)+?)\[/hide\]#is", $s)) 
{ 
        $q = time().mt_rand(1, 1024); 
        $s = preg_replace("/\[hide=((\s|.)+?)\]((\s|.)+?)\[\/hide\]/i", 
     "<script language=\"javascript\" type=\"text/javascript\" src=\"js/spoiler.js\"></script><div class=\"spoiler_head\" onclick=\"javascript:showspoiler('".$q."')\"><img border=\"0\" src=\"pic/plus.gif\" id=\"pic".$q."\" title=\"Показать\">&nbsp;&nbsp;\\1</div><div class=\"spoiler_body\" style=\"display:none;\" id=".$q." name=".$q.">\\3</div>", $s, 1); 
} 
*//*





//[spoiler]Text[/spoiler] or [spoiler=Name]Text[/spoiler] 
     //global $nummatch; 
        $q = time().mt_rand(1, 1024);    
$nummatch = $q;
     while(preg_match("/\[spoiler=\s*((\s|.)+?)\s*\]((\s|.)+?)\[\/spoiler\]/i",$s)) {
     $s = preg_replace("/\[spoiler=\s*((\s|.)+?)\s*\]((\s|.)+?)\[\/spoiler\]/i",    
     "<br><script language='javascript' type='text/javascript' src='js/show_hide.js'></script>  
     <div style='border: 1px solid E0E0E0; padding: 3px'>    
     <div style='padding-bottom: 3px' class='clickable' onclick=\"javascript: show_hide('s$nummatch')\" title='Показать/Скрыть спойлер' tooltip='Показать'>    
     <img id='pics$nummatch' src='pic/plus.gif' border='0' title='Показать/Скрыть спойлер' tooltip='Показать'> \\1</div>    
     <div id='ss$nummatch' style='DISPLAY: none; border: 1px dashed #E0E0E0; padding: 2px'>\\3</div>    
     </div>", $s,1);      

     $nummatch=$nummatch+1;    
     if($nummatch>100) break;    
     }    
*/

     //while(preg_match("/\[spoiler\]\s*((\s|.)+?)\s*\[\/spoiler\]\s*/i",$s)) { 
	 
     //$s = preg_replace("/\[spoiler\]\s*((\s|.)+?)\s*\[\/spoiler\]\s*/i",    
	 /*
     "<script language='javascript' type='text/javascript' src='js/show_hide.js'></script>  
     <div style='border: 1px solid #E0E0E0; padding: 3px'>    
     <div style='padding-bottom: 3px' class='clickable' onclick=\"javascript: show_hide('s$nummatch')\" title='Показать/Скрыть спойлер'>    
     <img id='pics$nummatch'src='pic/plus.gif' border='0' title='Показать/Скрыть спойлер' tooltip='Показать'> Скрытый текст    
     </div>    
     <div id='ss$nummatch' style='DISPLAY: none; border: 1px dashed #E0E0E0; padding: 2px'>\\1</div>    
     </div>", $s,1);      
     $nummatch=$nummatch+1;    
     if($nummatch>100) break;    
     }

	// URLs
	$s = format_urls($s);
	//$s = format_local_urls($s);

	// Maintain spacing
	//$s = str_replace("  ", " &nbsp;", $s);

	foreach ($smiliese as $code => $url)
		$s = str_replace($code, "<img border=\"0\" src=\"pic/smilies/$url\" alt=\"" . htmlspecialchars_uni($code) . "\">", $s);

	foreach ($zamena as $code => $url)
		$s = str_replace($code, htmlspecialchars_uni($url) , $s);


	foreach ($me as $code => $url)
		$s = str_replace($code, "Я ".$url."", $s);

	foreach ($movie as $code => $url)
		$s = str_replace($code, "Я ".$url."", $s);

	foreach ($mat as $code)
		$s = str_replace($code, "А я матюкнулся...", $s);

		$s = str_replace("!рейтинг", "<b>ГОЛОСУЕМ ЗА НАШ ТРЕКЕР => <a href=http://www.tematop.com/details.php?id=514><img src=http://www.tematop.com/buttons/baner2.gif></a></b>", $s);

		$s = str_replace("!статрейт", "<b>Голосуем за наш трекер:</b> <center><a href=http://www.uptracker.ru/tracker/1400><img src=http://www.uptracker.ru/buttons/b_vote_silver.png></a><br><a href=http://torrent-trackers.ru/tracker/killserver.superhub.ru><img src=http://torrent-trackers.ru/static/widget/button.gif></a><br><a href=http://www.tematop.com/details.php?id=514><img src=http://www.tematop.com/buttons/toptema.gif></a></center>", $s);

	foreach ($privatesmilies as $code => $url)
		$s = str_replace($code, "<img border=\"0\" src=\"pic/smilies/$url\">", $s);

	return $s;
}
*/
function get_user_class() {
  global $CURUSER;
  return $CURUSER["class"];
}




function get_user_class_name($class) {
  global $tracker_lang;


if($usercolor !="" ) { 
    return "<span style=\"color:".$usercolor."\" title=\"".get_user_class_name($class)."\">" . $username . "</span>";  
}

  switch ($class) {
    case UC_USER: return $tracker_lang['class_user'];

    case UC_POWER_USER: return $tracker_lang['class_power_user'];

    case UC_VIP: return $tracker_lang['class_vip'];

    case UC_UPLOADER: return $tracker_lang['class_uploader'];

    case UC_SUPER_UPLOADER: return $tracker_lang['class_super_uploader'];

    case UC_MODERATOR: return $tracker_lang['class_moderator'];

    case UC_MODCHAT: return $tracker_lang['class_modchat'];

    case UC_NEADEKVAT: return $tracker_lang['class_neadekvat'];

    case UC_HELP_ADMIN: return $tracker_lang['class_help_admin'];

    case UC_ADMINISTRATOR: return $tracker_lang['class_administrator'];

    case UC_SPONSOR: return $tracker_lang['class_sponsor'];

    case UC_SYSOP: return $tracker_lang['class_sysop'];

    case UC_SYSOPUPLOADER: return $tracker_lang['class_sysopuploader'];

    case UC_SYSOPIDIOT: return $tracker_lang['class_sysopidiot'];

    case UC_SYSTEM: return $tracker_lang['class_system'];
  }
  return "";
}

function is_valid_user_class($class) {
  return is_numeric($class) && floor($class) == $class && $class >= UC_USER && $class <= UC_SYSOP;
}

//----------------------------------
//---- Security function v0.1 by xam
//----------------------------------
function int_check($value,$stdhead = false, $stdfood = true, $die = true, $log = true) {
	global $CURUSER;
	$msg = "Invalid ID Attempt: Username: ".$CURUSER["username"]." - UserID: ".$CURUSER["id"]." - UserIP : ".getip();
	if ( is_array($value) ) {
        foreach ($value as $val) int_check ($val);
    } else {
	    if (!is_valid_id($value)) {
		    if ($stdhead) {
			    if ($log)
		    		write_log($msg);
		    	stderr("ERROR","Invalid ID! For security reason, we have been logged this action.");
	    }else {
			    Print ("<h2>Error</h2><table width=100% border=1 cellspacing=0 cellpadding=10><tr><td class=text>");
				Print ("Invalid ID! For security reason, we have been logged this action.</td></tr></table>");
				if ($log)
					write_log($msg);
	    }
			
		    if ($stdfood)
		    	stdfoot();
		    if ($die)
		    	die;
	    }
	    else
	    	return true;
    }
}
//----------------------------------
//---- Security function v0.1 by xam
//----------------------------------

function is_valid_id($id) {
  return is_numeric($id) && ($id > 0) && (floor($id) == $id);
}

function sql_timestamp_to_unix_timestamp($s) {
  return mktime(substr($s, 11, 2), substr($s, 14, 2), substr($s, 17, 2), substr($s, 5, 2), substr($s, 8, 2), substr($s, 0, 4));
}

  function get_ratio_color($ratio) {
    if ($ratio < 0.1) return "#ff0000";
    if ($ratio < 0.2) return "#ee0000";
    if ($ratio < 0.3) return "#dd0000";
    if ($ratio < 0.4) return "#cc0000";
    if ($ratio < 0.5) return "#bb0000";
    if ($ratio < 0.6) return "#aa0000";
    if ($ratio < 0.7) return "#990000";
    if ($ratio < 0.8) return "#880000";
    if ($ratio < 0.9) return "#770000";
    if ($ratio < 1) return "#660000";
    return "#000000";
  }

  function get_slr_color($ratio) {
    if ($ratio < 0.025) return "#ff0000";
    if ($ratio < 0.05) return "#ee0000";
    if ($ratio < 0.075) return "#dd0000";
    if ($ratio < 0.1) return "#cc0000";
    if ($ratio < 0.125) return "#bb0000";
    if ($ratio < 0.15) return "#aa0000";
    if ($ratio < 0.175) return "#990000";
    if ($ratio < 0.2) return "#880000";
    if ($ratio < 0.225) return "#770000";
    if ($ratio < 0.25) return "#660000";
    if ($ratio < 0.275) return "#550000";
    if ($ratio < 0.3) return "#440000";
    if ($ratio < 0.325) return "#330000";
    if ($ratio < 0.35) return "#220000";
    if ($ratio < 0.375) return "#110000";
    return "#000000";
  }

function write_log($text, $color = "transparent", $type = "tracker") {
  $type = sqlesc($type);
  $color = sqlesc($color);
  $text = sqlesc($text);
  $added = sqlesc(get_date_time());
  sql_query("INSERT INTO sitelog (added, color, txt, type) VALUES($added, $color, $text, $type)");
}

function get_elapsed_time($ts) {
  $mins = floor((time() - $ts) / 60);
  $hours = floor($mins / 60);
  $mins -= $hours * 60;
  $days = floor($hours / 24);
  $hours -= $days * 24;
  $weeks = floor($days / 7);
  $days -= $weeks * 7;
  $t = "";
  if ($weeks > 0)
    return "$weeks недел" . ($weeks > 1 ? "и" : "я");
  if ($days > 0)
    return "$days д" . ($days > 1 ? "ней" : "ень");
  if ($hours > 0)
    return "$hours час" . ($hours > 1 ? "ов" : "");
  if ($mins > 0)
    return "$mins минут" . ($mins > 1 ? "" : "а");
  return "< 1 минуты";
}



function get_user_class_group($class) {
    if     ($class == 1) return 'Участник'; 
    elseif ($class == 2) return 'VIP';
    elseif ($class == 3) return 'Модератор';
    elseif ($class == 4) return 'Администратор';
    elseif ($class == 5) return 'Создатель';
    elseif ($class == 6) return 'Руководитель';
    else return 'n\a';
}

function get_classes_group() {
    $classes = array();
    $classes['user']    = 1;
    $classes['vip']     = 2;
    $classes['moder']   = 3;
    $classes['admin']   = 4;
    $classes['creator'] = 5;
    $classes['rukovod'] = 6;
    
    return $classes;
}

?>