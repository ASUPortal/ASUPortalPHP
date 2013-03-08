<?php
$pg_title='Объявления';

include 'sql_connect.php';
include 'header.php';

if (!isset($_GET['wap'])) {	echo $head;}
else { echo $head_wap;}

$lectors4page=20;
$href=$curpage;

echo '<div class="main">'.$pg_title.'</div>';

if ($hide_person_data_rule) die($hide_person_data_task);

if (!isset($_GET['onget']))
 {
  if(!isset($_GET['number']))
     {
      $number=1; $start=0;
     }
    else
     {
      $number=$_GET['number'];$start=($number-1)*$lectors4page;
     }
    
if (isset($_SESSION['auth']) && $_SESSION['userType']=='преподаватель') {echo '<a href="admin_news.php?go=1" class=button title="'.$_SESSION['FIO'].'"> добавить  объявление</a>';}
echo '';

    $res05=mysql_query ('select count(if(status!="администратор",1,NULL)) from users');
    $count=mysql_result($res05,0,0);
    $pages=$count/$lectors4page;
    $pages_and=$pages;
    $pages_and=intval($pages_and);
    if ($pages_and==$pages)
     {
      $pages=$pages_and;
     }
    else
     {
      $pages=$pages_and+1;
     }
    if(($number>$pages) || ($number<1))
     {
      $number=$pages;$start=$number*($lectors4page-1);
     }
    $finish=$start+$lectors4page;
    if($finish>$count)
     {
      $length=$count-$start;
     }
    else
     {
      $length=$lectors4page;
     }
    $query='select * from users where status!="администратор" order by FIO asc limit '.$start.','.$length;
	$res06=mysql_query ($query);
//    echo $query;
    echo '<p align=center>';
	//$href='?number=';
	//printPGnums($pages,$number,$href);
	echo getPagenumList($pages,$number,3,'number','','');
	echo '</p>';    
    
	while($p=mysql_fetch_array($res06))
     {
      $res06aaa=mysql_query ('select * from news where user_id_insert="'.$p['id'].'" and news_type="notice" order by date_time desc');
      $p_=mysql_fetch_array($res06aaa);
	  $p111=mysql_num_rows($res06aaa);
      echo '<p class="lecturers">
      <a href="?onget=1&idlect='.$p['id'].'"';
      
      if ($p111>=1)
       {
		echo ' >'.$p['FIO'].' &nbsp; ('.$p111.')'.
		echoIf(isNewItem($p_['date_time']),'<font class=warning> new </font> от '.DateTimeCustomConvert($p_['date_time'],'dt','mysql2rus'),'').'</a>';
       }
      else
       {
        echo ' style="color:#8D8D8D;">'.$p['FIO'].' &nbsp; (-)</a>';
       }
     }
    echo '<p align=center>';
	echo getPagenumList($pages,$number,3,'number','','');
	echo '</p>';    
	echo '<p class="text" valign="bottom">в скобках указано число объявлений данного преподавателя <br>
	<font color=red> new </font> отмечает объявления за последние 7 дней';
 }
else
 {
  if (isset($_GET['idlect']))
   {
    
if (isset($_SESSION['auth']) && $_SESSION['userType']=='преподаватель') {echo '<a href="lect_notice.php?go=1" class=button title="'.$_SESSION['FIO'].'"> добавить  объявление</a>';}
echo '<br>';

    $res00=mysql_query ('select * from news where user_id_insert="'.$_GET['idlect'].'" and news_type="notice" order by date_time desc');
    $res00aaa=mysql_query ('select FIO from users where id="'.$_GET['idlect'].'"');
    $g1=mysql_fetch_array($res00aaa);
    if(mysql_num_rows($res00)>0)
     {
      echo '<div class="middle">'.$g1['FIO'].'&nbsp;&nbsp;<a href="'.$href.'" class=text>к общему списку</a></div><br><br>';
      while($g=mysql_fetch_array($res00))
       {
        
	echo '<p class="text" align="left">'.DateTimeCustomConvert($g['date_time'],'dt','mysql2rus').'
        <span class="title" align="left"><a href="#view" onclick=javascript:win_open("index.php?id='.$g['id'].'",400,600);>'.$g['title'].'</a></span>'.
	echoIf(isNewItem($g['date_time']),'<font class=warning> new </font>','').
	'</p>';
       }
     }
    else
     {
      echo '<div class="middle">'.$g1['FIO'].'</div><br><br>';
      echo '<p class="lecturers">Объявлений нет.';
     }
   }

 }
	if (!isset($_GET['wap'])) {
	  echo $end1;
	  include "display_voting.php";
	  }
	echo $end2; include('footer.php'); 
 
?>