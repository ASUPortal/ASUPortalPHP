<?php

if (!isset($files_path)) {
    $files_path='';
}

include ($files_path.'sql_connect.php');

if (!isset($_SESSION['auth']) || $_SESSION['auth']!=1)  {

//все символы 2-значные
$url='';
$url=$_SERVER["REQUEST_URI"];

	 header('Location: '.$files_path.'p_denied_access.php?type=not_auth&url='.$url.'');
} else {
	if ($_SESSION['group_blocked']==1) {
        header('Location: p_denied_access.php?type=user_lock' );
    } else {
        $query="SELECT distinct tig.task_rights_id,t.url,t.name as pg_name
			FROM task_in_group tig inner join tasks t on t.id=tig.task_id 
			WHERE tig.user_group_id in (
			  SELECT group_id
				FROM user_in_group
				WHERE user_id ='".$_SESSION['id']."') and t.url like '".$curpage."%'";

		//введение персональных задач пользователя
		$query.="union 
			SELECT distinct tiu.task_rights_id,t.url,t.name as pg_name 
			FROM task_in_user tiu inner join tasks t on t.id=tiu.task_id 
			WHERE user_id ='".$_SESSION['id']."' and t.url like '".$curpage."%'
			order by 1 desc limit 0,1";

		$res=mysql_query($query);
		$a=mysql_fetch_array($res);

		if (mysql_num_rows($res)<=0)  header('Location: '.$files_path.'p_denied_access.php?type=not_task_auth' );
		else {
			$_SESSION['task_rights_id']=$a['task_rights_id'];	//права пользователя на задачу			
			
			$pg_title=$a['pg_name'];
			//обновляем статистику активности пользователей
			
			mysql_select_db($sql_stats_base);
 
			$query="UPDATE `".$sql_stats_base."`.`user_activity` SET `last_datetime` = '".date("Y-m-d H:i:s")."',
				`last_page` = '".$curpage."' WHERE `user_id` ='".$_SESSION['id']."' LIMIT 1 ";
			
			//mysql_query("use asu;");
			mysql_query($query);
			//если не смогли обновить, пробуем добавить
			if (mysql_affected_rows()==0) {
				$query='insert into `'.$sql_stats_base.'`.`user_activity`(`user_id`,`last_datetime`,`last_page`)
					values("'.$_SESSION['id'].'","'.date("Y-m-d H:i:s").'","'.$curpage.'")';
				mysql_query($query);
				}
			
			//преобразование метки прав в текстовые эквивалентные переменные
			$view_all_mode=false;
			$write_mode=false;
			if (isset($_SESSION['task_rights_id'])) {	//определение видимости записей с учетом прав
					//обзор всех записей, а не тольео своих
				if ($_SESSION['task_rights_id']==2 || $_SESSION['task_rights_id']==4) $view_all_mode=true;
				
					//возможность записи, а не только просмотра
				if ($_SESSION['task_rights_id']==3 || $_SESSION['task_rights_id']==4) $write_mode=true;
			}
			
		}
		mysql_select_db($sql_base);
	}
}
?>