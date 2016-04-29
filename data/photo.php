<?php
error_reporting(E_ALL ^ E_NOTICE);
$imagekod = '62017624';
$imagekod = $_GET['imagekod'];
if (isset ($imagekod) && (!empty($imagekod)) && $imagekod != 0){
	$url = 'http://172.250.1.206:9876/KO/api/image/'.$imagekod; // 62017624
	header("Content-Type: image/jpeg");
	echo file_get_contents($url);}
?>