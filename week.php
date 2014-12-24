<?php
//последние изменения
$time_in_days=7;

$count_news=0;
$count_files=0;
$count_notice=0;

$query='';
 

 $res=mysql_query ('select count(*)as new_cnt from news 
 	where `date_time` >= "'.date("Y-m-d",mktime (0,0,0,date("m"),(date("d")-$time_in_days),date("Y"))).'"');
  //echo $query;
 $count_news=mysql_result($res,0,0);
 
 $res=mysql_query ('select count(*)as notice_cnt from news 
 	where news_type="notice" and `date_time` >= "'.date("Y-m-d",mktime (0,0,0,date("m"),(date("d")-$time_in_days),date("Y"))).'"');
  $count_notice=mysql_result($res,0,0);

 $res=mysql_query ('select count(*)as file_cnt from files 
 	where `date_time` >= "'.date("Y-m-d",mktime (0,0,0,date("m"),(date("d")-$time_in_days),date("Y"))).'"');
  $count_files=mysql_result($res,0,0);
  
?>
