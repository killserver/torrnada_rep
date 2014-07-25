<?php
require_once('include/class_vk.php');



$publicID = 00000; //твой id сообщества куда постить
$accessToken = 'your access token'; //твой токкен
$text = "Привет мир!"; //сообщение которое будет запощенно
$img = "images/torrnada.png"; //картинка которая будет вместе с сообщением, может быть пустым
$tags = array('вконтакте api', 'автопостинг', 'первые шаги');//теги в " и ' кавычках, через запятую


$vkAPI = new \BW\Vkontakte(array('access_token' => $accessToken));
$path = dirname(__FILE__)."/";

if ($vkAPI->postToPublic($publicID, $text, $path.$img, $tags)) {
    echo "Ура! Всё работает, пост добавлен\n";
} else {
    echo "Фейл, пост не добавлен(( ищите ошибку\n";
}

?>