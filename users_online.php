<?php
//include 'sql_connect.php';

$showLastPageVisit=true;	//указывать ли страницу, где последний раз был пользователь
$timeInterval=20;	// в минутах от тек.времени

//$curTime=mktime(date("H"), date("i"), date("s"), date("m")  , date("d"), date("Y"));
$prevTime=mktime(date("H"), date("i")-$timeInterval, date("s"), date("m"), date("d"), date("Y"));
$date_now=date("Y-m-d H:i:s",$prevTime);
//$date_now=date("Y-m-d h:i:s");
//echo ' date_now='.$date_now.' <br>date_now='.date("Y-m-d H:i:s");

$query='select users.fio,users.id from users inner join user_in_group on user_in_group.user_id=users.id 
			where 1 order by users.fio';

$query='select users.id as user_id,users.login,users.FIO_short,users.FIO,users.status,ua.last_datetime,ua.last_page,
		tasks.name as task_name,(select count(*) from user_in_group where user_id=users.id) as gr_cnt
from users 
	left join `'.$sql_stats_base.'`.`user_activity` ua on ua.user_id=users.id
	left join tasks on tasks.url=ua.last_page 
	left join  user_in_group on user_in_group.user_id=users.id	
where ua.last_datetime>="'.$date_now.'" 
group by users.id,users.login,users.FIO_short,users.FIO,users.status,ua.last_datetime,ua.last_page,tasks.name
order by last_datetime desc';

//echo '$query'.$query;
$res=mysql_query($query);

echo '<div class=text style="width:220;border-style:solid;border-width: thin;background-color:#ffffff;">';
echo '<table class=text border=0 cellspacing=0 cellpadding=4>
		<tr><td>За последние '.$timeInterval.' мин на сайте: ('.mysql_num_rows($res).')</td></tr>';
if (mysql_num_rows($res)>0)
{
 	while ($a=mysql_fetch_array($res))
	{
	
	$minCnt=intval((time()-strtotime($a['last_datetime']))/60);
	//echo ' userTime='.$userTime;
	//echo ' Time='.time();
	
	$name=$a['FIO_short'];
	if ($minCnt==0) {$minText='менее минуты';$minCnt='';} else 
	if ($minCnt==1) {$minText='минуту';}
	else
		if ($minCnt>1 && $minCnt <=4) {$minText='минуты';}
		else {$minText='минут';}
	
	$titleText=$minCnt.' '.$minText.' назад';
	
	if ($showLastPageVisit==true && trim($a['last_page'])!='') 
		{
		 //$titleText=$titleText.', <a href="'.$a['last_page'].'">'.$a['task_name'].'</a>';
		 $titleText=$titleText.', <b>'.$a['task_name'].'</b>';
		 } 

	if (trim($name)=='') {$name=$a['login'];}
	$href='#';
	if ($a['status']=='преподаватель') {$href='_modules/_lecturers/index.php?action=view&id='.$a['user_id'].'';}
	
	echo '<tr><td><a href="'.$href.'" title="'.$a['status'].'"><font size="+1">'.$name.'</font>, группы ('.$a['gr_cnt'].'): ';
	
	//печать списка групп пользователя
	$query_gr='select user_groups.comment as gr_name,user_groups.name as gr_name_eng, user_groups.color_mark  as gr_color 
from users 
	left join  user_in_group on user_in_group.user_id=users.id
	left join user_groups on user_groups.id=user_in_group.group_id
where users.id="'.$a['user_id'].'"';
		$res_gr=mysql_query($query_gr);
		while ($a_gr=mysql_fetch_array($res_gr))
		{
		 	echo '<span style="color:'.$a_gr['gr_color'].';">'.$a_gr['gr_name'].';</span> ';
		 }
	
	
	
	echo '</a> '.$titleText.'</td></tr>';	 
	$titleText=''; 
	$href='#';
	} 
}
else {echo '<tr><td>пользователей нет</td></tr>';;}
echo '</table></div>';
?>