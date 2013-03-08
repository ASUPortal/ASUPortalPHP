<?php
header('Content-Type: text/html; charset=windows-1251');
$typeQ='';	//тип запроса
$list1=0;	//значение основного списка для фильтрации зависимого
$select_id=0;

if (isset($_GET['typeQ'])) $typeQ=$_GET['typeQ'];
if (isset($_GET['list1'])) $list1=intval($_GET['list1']);
if (isset($_GET['select_id'])) $select_id=intval($_GET['select_id']);

$queryArr=array(
	'StGroup2Students'=>array('select id,fio from students','group_id')
	);

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && array_key_exists($typeQ,$queryArr))  {
    //getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName)
	
$files_path='../../';
//include '../asu/sql_connect.php';
include $files_path.'sql_connect.php';

$query_all='SELECT time_intervals.name as year_name, time_intervals.date_start,time_intervals.date_end,settings.year_id 
	FROM settings inner join time_intervals on time_intervals.id=settings.year_id 
	where 1 limit 0,1';
if ( $res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {$def_settings=mysql_fetch_array($res_all);}

//типы запросов на фильтрацию списков	mainQuery, whereColumnName

	
$query='SELECT s.id, concat(s.fio," (",sg.name,")") as fio
			  FROM    study_groups sg RIGHT OUTER JOIN  students s  ON (sg.id = s.group_id)
			  WHERE (sg.year_id = '.$def_settings['year_id'].') ';

	//$query='select id,fio from students ';
	//if ($list1>0) $query.=' where group_id='.$list1.' ';
	if ($list1>0) $query.=' and s.group_id='.$list1.' ';
	$query.='order by 2';
	
	$res=mysql_query($query);
	//print $query;
	print'[{value:"0",text:"выберите из списка...('.mysql_num_rows($res).')"}';
	while ($a=mysql_fetch_array($res))	
		{
		 $select_val='';

		 if ($select_id>0 && $select_id==$a['id']) $select_val=true;		 
		 print ",{value:\"{$a['id']}\",text:\"{$a['fio']}\",selected:\"$select_val\"}";
		 }
		//print ",{value:\"{$a['id']}\",text:\"{$a['fio']}{$_GET['typeQ']}\"}";
	print ']';
	
	
/*	
	switch($_GET['list1']) {
       	case '0':
       	print '[{value:"",text:"Выбрать автомобиль"},{value:"1",text:"Audi"},{value:"2",text:"BMW"},{value:"3",text:"Opel"}]';
       	break;
       	case '2':
       	print '[{value:"",text:"Выбрать автомобиль"},{value:"1",text:"Daewoo"},{value:"2",text:"Hyundai"},{value:"3",text:"KIA"}]';
       	break;
       	case '3':
       	print '[{value:"",text:"Выбрать автомобиль"},{value:"1",text:"Honda"},{value:"2",text:"Mazda"},{value:"3",text:"Toyota"}]';
       	break;
       	default:
       	print '[{value:"",text:"Выбрать автомобиль"}]';
       	break;
    }
	*/
}
?>