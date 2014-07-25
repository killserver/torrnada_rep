<?php

$token="your_access_token";//твой токкен
$group_id="12345678";//id группы куда будет отправленно сообщение
$tags = array('tags');//теги...просто теги:)
$text = "Мой супер-пупер текст";//текст постинга на стену
$PATH="";//путь картинки, может быть пустым
$path_bool=true;//имеет 2 значения true и false; предназначенна для "выхода вне локальной группы пользования", либо на русском - файлы относительно того места, где лежит сайт файл-скрипт: true-да, false-нет

class vk {

	private $group_id = 0;
	private $token = "";

	function __construct($token=null, $group=null) {
		if(!empty($token)) {
			$this->group_id = $group;
		}
		if(!empty($token)) {
			$this->token = $token;
		}
	}

	protected function curl($url) {
	        // create curl resource
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	        $result = curl_exec($ch);
	        if (!$result) {
	            $errno = curl_errno($ch);
	            $error = curl_error($ch);
	        }
	        curl_close($ch);
	        if (isset($errno) && isset($error)) {
	            throw new \Exception($error, $errno);
		}
		return $result;
	}

	public function upload_file($url, $PATH, $cols="file1") {
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array($cols => "@".$PATH));
		$Request_step2 = curl_exec($ch);
		curl_close($ch);
		$Responce_step2 = json_decode($Request_step2);
	return $Responce_step2;
	}

	public function post($text, $PATH, $path_bool=true, $tags=null) {
		$api="";
		if(!empty($PATH)) {
			if($path_bool) {
				$PATH=dirname(__FILE__)."/".$PATH;
			}
			$sRequest_step1 = $this->curl("https://api.vkontakte.ru/method/photos.getWallUploadServer?gid=" . $this->group_id . "&access_token=" . $this->token);
			$oResponce_step1 = json_decode($sRequest_step1);
			$Responce_step2 = $this->upload_file($oResponce_step1->response->upload_url, $PATH);
			$imgdata = $this->curl("https://api.vkontakte.ru/method/photos.saveWallPhoto?gid=" . $this->group_id . "&photo=" . $Responce_step2->photo . "&server=" . $Responce_step2->server . "&hash=" . $Responce_step2->hash . "&access_token=" . $this->token);
			$step3 = json_decode($imgdata);
			$media = $step3->response[0]->id;
			$api .= "&attachments=".$media;
		}
	        if(!empty($tags)) {
			$text .= "\n\n";
		        foreach($tags as $tag) {
				$text .= ' #' . str_replace(' ', '_', $tag);
		        }
	        }
		$text = html_entity_decode($text);
		$text = urlencode(iconv("cp1251", "utf-8", $text));
		$fin = $this->curl("https://api.vkontakte.ru/method/wall.post?owner_id=-" . $this->group_id . "&message=" . $text . "&access_token=" . $this->token . $api);
	return $fin;
	}

}
$vk = new VK($token, $group_id);
var_dump($vk->post($text, $PATH, $path_bool, $tags));

?>