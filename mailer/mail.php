<?php

set_time_limit(0);
ini_set('max_execution_time', '0');

define('IN_TRACKER',1);
define('IN_ANNOUNCE',1);

//Config
$limit = 1500; //Количество писем в час
$delay = 2; //Время задержки между отправками писем (c)
$sub = 'Новые раздачи на ТоррНАДА'; //Тема рассылки
$smtp_s = 'mail.torrnada.ru'; // Почтовый сервер - порт 25
$smtp_l = 'robot@torrnada.ru'; // Логин для почтовика
$smtp_p = 'UF1Omt4SFh'; // Пароль для почтовика

$from_m = 'robot@torrnada.ru'; // Из какого ящика письма
$from_n = 'TorrNada.ru'; // От кого письма
//Config

$hour = date('G')*1;
//$hour = 0;

include "ext/class.db.php";
include "ext/swift/swift_required.php";
include "../include/secrets.php";
include "../include/config.php";

$fs = @filesize('log.txt');
if($fs > 2145728) @unlink('log.txt');

try {
    $db = new Db("mysql:host=" . $mysql_host . ";dbname=" . $mysql_db, $mysql_user, $mysql_pass);
    $db->query("SET NAMES " . $mysql_charset);
} catch (PDOException $e) {
    print "no connection: mysql";
    die();
}

if(!isset($_GET['debug'])) {
$ban = @file_get_contents('ban.txt');
if(time()<($ban+55*60)) die('Не прошло достаточно времени с прошлой рассылки');
file_put_contents('ban.txt', time());
}

$txt = file_get_contents('contents.html');
$torr_list = file_get_contents('http://torrnada.ru/category_view.php');
$torr_list = substr($torr_list, 19);
$torr_list = iconv('CP1251', 'UTF-8', $torr_list);
$txt = str_replace ( '(@date)' , date('d.m.Y'), $txt );
$txt = str_replace ( '(@torr_list)' , $torr_list, $txt );


$sql = "SELECT id,email,username FROM `users` WHERE  status = :status AND email_dist = :email_dist AND email != \"\" ORDER BY `id`".(!isset($_GET['debug'])?" LIMIT ".($limit*$hour).",".$limit : "");
$data = array(':status' => 'confirmed',':email_dist' => 'yes');

$info = $db->sql($sql, $data);

if(isset($_GET['debug'])) {
unset($info);
$info[0]['id'] = 3;
$info[0]['email'] = 'ivan.min-vody@mail.ru';
$info[0]['username'] = 'ivan';
/*$info[0]['id'] = 3;
$info[0]['email'] = 'killer-server@mail.ru';
$info[0]['username'] = 'killer';*/
}


//$transport = Swift_SmtpTransport::newInstance($smtp_s, 25)
//  ->setUsername($smtp_l)
//  ->setPassword($smtp_p);

$transport = Swift_MailTransport::newInstance();
$mailer = Swift_Mailer::newInstance($transport);

file_put_contents('log.txt', 'Start '.date("Y-m-d G:i:s"), FILE_APPEND);
file_put_contents('log.txt', "\n", FILE_APPEND);
$i = 1;
foreach($info as $k=>$v){
    file_put_contents('log.txt', date("G:i:s").' '.$i.':'.$v['id'].':'.$v['email'].':', FILE_APPEND);

	$txt = str_replace('{@md5_email@}', md5($v['email']), $txt);
	$txt = str_replace('{@email@}', urlencode(base64_encode($v['email'])), $txt);

if(isset($_GET['debug'])) {
echo $txt;
if(isset($_GET['send'])) {
	echo "<h1>SEND TO: ".$v['email']."</h1>";
	$message = Swift_Message::newInstance($sub)
	->setFrom(array( $from_m=> $from_n))
	->setTo(array($v['email']))
	->setBody(str_replace ( '(@name)' , $v['username'], $txt ), 'text/html', 'cp1251');
	$mailer->send($message);
}
die();
}

	$message = Swift_Message::newInstance($sub)
	->setFrom(array( $from_m=> $from_n))
	->setTo(array($v['email']))
	->setBody(str_replace ( '(@name)' , $v['username'], $txt ), 'text/html', 'cp1251');

    if(!$mailer->send($message)) {
        file_put_contents('log.txt', "ERROR", FILE_APPEND);
    } else {
        file_put_contents('log.txt', "SEND", FILE_APPEND);
    }
    file_put_contents('log.txt', "\n", FILE_APPEND);
    sleep($delay);
//	if(!($i%10))sleep(10);
	$i++;

}
file_put_contents('log.txt', 'Finish '.date("Y-m-d G:i:s"), FILE_APPEND);
file_put_contents('log.txt', "\n", FILE_APPEND);
file_put_contents('log.txt', "\n", FILE_APPEND);


