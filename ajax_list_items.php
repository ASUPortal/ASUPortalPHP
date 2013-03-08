<?php
//обновление элементов списка, применяется в задачах: дип.проекты-список_студентов


header('Content-Type: text/html; charset=utf-8');
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
  if(isset($_GET['list_type'])){
	require "sql_connect.php";
	//---------------
	$param1='';
	$param2='';
	$listId='';
	$selectId=0;
	
	if (isset($_GET['param1'])) $param1=htmlspecialchars($_GET['param1']);
	if (isset($_GET['param2'])) $param2=htmlspecialchars($_GET['param2']);
	if (isset($_GET['listId'])) $listId=htmlspecialchars($_GET['listId']);
	if (isset($_GET['selectId'])) $selectId=intval($_GET['selectId']);

    /**
    * $query_all='SELECT time_intervals.name as year_name, time_intervals.date_start,time_intervals.date_end
    *	FROM settings inner join time_intervals on time_intervals.id=settings.year_id
    *	where 1 limit 0,1';
    * if ( $res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {$def_settings=mysql_fetch_array($res_all);}
    *
    * Переписано для использования новой системы глобальных настроек
    */
    $query_all = "
        select
            time_intervals.name as year_name,
            time_intervals.date_start,
            time_intervals.date_end
        from
            time_intervals
        where
            time_intervals.id = ".CUtils::getCurrentYear()->getId();
    if ($res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {
        $def_settings=mysql_fetch_array($res_all);
    }
        
	switch ($_GET['list_type']) {
	case 'stud_list':
		$query='SELECT s.id, concat(s.fio," (",sg.name,")") as name
			  FROM    study_groups sg RIGHT OUTER JOIN  students s  ON (sg.id = s.group_id) ';
		if ($param1!='' && $param1=='true')    {  //текущий год	
		  $query.='WHERE (sg.year_id = '.$def_settings['year_id'].') order by 2';
		  }
		else {	//поиск в архиве	
		  $query.='WHERE (sg.year_id < '.$def_settings['year_id'].' or sg.year_id is NULL) order by 2';
		    }		
		break;
	case 'pract_list':
		$query='select id,concat(name," (",replace((select count(*) from `diploms` where diploms.pract_place_id=pract_places.id),"0","-"),")") as name from pract_places ';
		if ($param2!='' && $param2=='true' && $param1!='')    {  //применить фильтр
		  $query.='WHERE (name like "%'.$param1.'%" or comment like "%'.$param1.'%")';
		  }
		else {	//отменить фильтр		  
		    }
		$query.=' order by 2';
		break;	      
	default:
		$query='';
	}
	
      if ($listId!='' && $selectId>0) $res_edit[$listId]=$selectId;
      $list_str=getFrom_ListItemValue($query,'id','name',$listId);
      //$list_str='<option>'.'$listId='.$listId.',$selectId='.$selectId.'</option>';
      echo mb_convert_encoding($list_str, "UTF-8","cp1251");
   }
}
?>