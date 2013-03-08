<?php
//выборка для автозаполнения, осн.задача - передача текста запроса на выборку в этот файл
//используется в "Аспиранты"

$sFromStart=false;  //	поиск с начала фразы, по умолчанию поиск в любом месте фразы

header('Content-Type: text/html; charset=utf-8');
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
  if($_GET['q']){
	require "sql_connect.php";
	//---------------
	
	switch ($_GET['dbqt']) {
	case 'recenz':
		$query='SELECT `recenz` as name,count(*) as name_cnt  FROM `diploms` group by `recenz` order by 1';
		break;
	case 'pract_place':
		$query='SELECT `pract_place` as name,count(*) as name_cnt  FROM `diploms` group by 1 order by 1';
		break;
	case 'kandidWork_name':
		$query='SELECT `tema` as name,count(*) as name_cnt  FROM `disser` group by 1 order by 1';
		break;		
	case 'kadriFio':
		$query='SELECT `fio` as name,count(*) as name_cnt  FROM `kadri` group by 1 order by 1';
		break;
	case 'nation':
		$query='SELECT `nation` as name,count(*) as name_cnt  FROM `kadri` group by 1 order by 1';
		break;
	case 'social':
		$query='SELECT `social` as name,count(*) as name_cnt  FROM `kadri` group by 1 order by 1';
		break;	  
	default:
		$query='';
	}

	$res=mysql_query($query);
	while ($a=mysql_fetch_array($res))
	{
	 	 $a['name'] = mb_convert_encoding($a['name'], "UTF-8","cp1251");	//cp1251 ASCII
		 //print $a['name']."|1"."|2"."|3"."\n";
		 $find=mb_strpos(mb_strtolower($a['name'],"UTF-8"),mb_strtolower($_GET['q'],"UTF-8"));
		  if (($sFromStart && $find!==false&&$find==0) or (!$sFromStart && $find!==false) ) {
		    $a['name'] = trim($a['name']);
		  	print $a['name']."|".$a['name_cnt']."\n";
		  }
	} 
  }
}
?>