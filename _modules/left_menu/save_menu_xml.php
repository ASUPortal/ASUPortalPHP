<?php
if (!function_exists('file_put_contents')) {
	function file_put_contents($filename,$data) {
		$f=@fopen($filename,'w');
		if (!$f) {
			return false;
		} else {
			$bytes=fwrite($f,$data);
			fclose($f);
			return $bytes;
			}
		}
	}	

if (isset($_POST['xml']) && $_POST['xml']!='')
{
    //сохранить xml-документ
    $xml_code=$_POST['xml'];
    
    //удалить некорректные теги root, больше 1 пары
    $xml_code='<root>'.preg_replace('/<(|\/)root>/','',$xml_code).'</root>';
    $xml_code='<?xml version="1.0" encoding="UTF-8"?>'.stripslashes($xml_code);    
	
	$writeBytes=file_put_contents('menu.xml',$xml_code);	// число записанных в файл байт
    
	if ($writeBytes==0) echo 'error writing file';
}

?>