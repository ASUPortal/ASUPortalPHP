<?php
// дни рождения преподавателей и УВП

$pol_imgM='images/pol_imgM.gif';	//пиктограмма МУЖ
$pol_imgW='images/pol_imgW.gif';	//пиктограмма ЖЕН
$pol_imgN='images/pol_imgN.gif';	//пиктограмма НЕ ИЗВ

$week_days=array('Вс','Пн','Вт','Ср','Чт','Пт','Сб');

$hideRezen=true;	//скрывать рецензента в  ДР
//----------------------ДР за неделю----------------

$dayWeek='';
$dayWeek=date("d",mktime (0,0,0,date("m"),date("d")+7,date("Y")));
$monWeek=date("m",mktime (0,0,0,date("m"),date("d")+7,date("Y")));

$mon_next=date("m",mktime (0,0,0,date("m"),date("d")+1,date("Y")));


$whereWeek='';
//--------------------------+++++++++++++++++++++++++++++++++++
if ($monWeek!=$mon_next) {
	
	//$monTmp=date(m);
	//if ($day_next==1) {$monTmp=date(m)+1;}
	
	$whereWeek="(bDay>$day_next and bMonth=".$mon_next.") or (bDay<=$dayWeek and bMonth=$monWeek)" ;
	}
else {$whereWeek="(bDay>$day_next and bDay<=$dayWeek and bMonth=".$mon_next.")" ;}

$queryWeek="select t.id,t.fio,t.fio_short,t.bDay,t.bMonth,t.pol,t.pol_name, t.kadri_id,t2.k_role  from
	(SELECT u.id,k.id as kadri_id,k.fio,k.fio_short,day(date(concat(substring(k.`date_rogd`,7,4), '-', substring(k.`date_rogd`,4,2),'-', substring(k.`date_rogd`,1,2)))) as bDay,
	month(date(concat(substring(k.`date_rogd`,7,4), '-', substring(k.`date_rogd`,4,2),'-', substring(k.`date_rogd`,1,2)))) as bMonth,k.pol, pol.name as pol_name  
	FROM `kadri` k
		left join `users` u on  u.`kadri_id`=k.`id`
		left join pol on k.pol=pol.id) t
		left join (select k.id, kadri_role(k.id,',') as k_role from kadri k )t2 on t2.id=t.kadri_id		
	where $whereWeek";
//echo $queryWeek.'<hr>';
//--------------------------------------
$queryAdd='';
if ($hideRezen) {$queryAdd=' and (k_role like "%ППС%" or k_role like "%УВП%" or k_role like "%асп%")  ';}

$query='select `users`.`id` , `kadri`.`photo` , `kadri`.`fio` , `kadri`.`fio_short` , `kadri`.`pol` , substring( `kadri`.`date_rogd` , 1, 5 ) AS "date_rogd", pol.name as pol_name,`kadri`.id as kadri_id, t2.k_role  
	from `kadri`
		left join `users` on  `users`.`kadri_id`=`kadri`.`id`
		left join pol on kadri.pol=pol.id
		left join (select k.id, kadri_role(k.id,",") as k_role from kadri k )t2 on t2.id=kadri.id
	where `kadri`.`date_rogd` like "'.$q_date_now.'%" or  
		`kadri`.`date_rogd` like "'.$q_date_soon.'%" '.$queryAdd.' 
	order by CONCAT( substring( `kadri`.`date_rogd` , 4, 2 ) , substring( `kadri`.`date_rogd` , 1, 2 ) )';
//echo ' query='.$query;	
$res=mysql_query($query);
$resWeek=mysql_query($queryWeek);

if (mysql_num_rows($res)>0 || mysql_num_rows($resWeek)>0)
	{echo '
	<a href="#show" onClick=hide_show("personBdays") title="подробнее..." style="TEXT-DECORATION: none;"><div class=warning style="text-align:center;">
		<img src="images/gift.jpg" border=0 width=32 alt="подарок">
		дни рождения ППС и УВП <u>сегодня-завтра:</u> <font size=+1>'.mysql_num_rows($res).'</font>, 
		<u>неделю: </u><font size=+1>'.mysql_num_rows($resWeek).'</font>
		<img src="images/design/left-arrow.gif" border=0 width=15></div></a>
	<div class=text id=personBdays style="display:none;text-align:center;
		border-style:solid;border-width:1px;border-color:#999999;">';
	while ($a=mysql_fetch_array($res))
	{
		if ($a['date_rogd']==$q_date_now) {$bDay_text='сегодня';}
		else {$bDay_text='завтра';}
		if ($a['pol']==1) {$pol_img=$pol_imgM;}
		else {
			if ($a['pol']==2) {$pol_img=$pol_imgW;}
			else {$pol_img=$pol_imgN;}
			}
		
		echo ''.$bDay_text.'-<a href="_modules/_lecturers/index.php?action=view&id='.$a['id'].'" title="'.$a['fio'].'-'.$a['k_role'].'">
			<img src="'.$pol_img.'" border=0 height=20 alt="'.$a['pol_name'].'">'.$a['fio_short'].'</a>; ';
	}
	if (mysql_num_rows($res)>0) {echo '<br>';}
	
	echo 'за неделю: ';
	while ($a=mysql_fetch_array($resWeek))
	{
		if ($a['pol']==1) {$pol_img=$pol_imgM;}
		else {
			if ($a['pol']==2) {$pol_img=$pol_imgW;}
			else {$pol_img=$pol_imgN;} 
			}
		
		$bDay=$week_days[date('w',mktime (0,0,0,$a['bMonth'],$a['bDay'],date("Y")))];
		echo '<b>'.$a['bDay'].'.'.$a['bMonth'].' ('.$bDay.')</b> -<a href="_modules/_lecturers/index.php?action=view&id='.$a['id'].'" title="'.$a['fio'].'-'.$a['k_role'].'">
			<img src="'.$pol_img.'" border=0 height=20 alt="'.$a['pol_name'].'">'.$a['fio_short'].'</a>; ';
	}
	
	echo '</div><br/>';}
else {echo '<p class=text style="text-align:center;">дней рождения сегодня-завтра,неделю нет</p>';}

//echo $queryWeek;


?>