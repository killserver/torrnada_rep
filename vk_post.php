<?php
require_once('include/class_vk.php');



$publicID = 00000; //���� id ���������� ���� �������
$accessToken = 'your access token'; //���� ������
$text = "������ ���!"; //��������� ������� ����� ���������
$img = "images/torrnada.png"; //�������� ������� ����� ������ � ����������, ����� ���� ������
$tags = array('��������� api', '�����������', '������ ����');//���� � " � ' ��������, ����� �������


$vkAPI = new \BW\Vkontakte(array('access_token' => $accessToken));
$path = dirname(__FILE__)."/";

if ($vkAPI->postToPublic($publicID, $text, $path.$img, $tags)) {
    echo "���! �� ��������, ���� ��������\n";
} else {
    echo "����, ���� �� ��������(( ����� ������\n";
}

?>