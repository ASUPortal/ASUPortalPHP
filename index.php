<?php

include 'sql_connect.php';


	//открываем заставку, если требуется
	$hide_logo=true;
	
	// <abarmin date="04.05.2012">
	// а ошибки кто-нибудь показывать пробовал?
	if (!isset($_COOKIE['hide_logo'])) {
		$hide_logo = false;
	} elseif ($_COOKIE['hide_logo']!=1) {
		$hide_logo=false;
	}

	// поставил заглушку ошибок
	if (@$logo_enable && $logo_path!='' && !$hide_logo)
	{
	setcookie('hide_logo',1);	
	header('Location:'.$logo_path);
	}
if ($site_blocked) {
 	header('Location:under_construction.php'); }
	
	// </abarmin>


$pg_title='Новости';

include 'header.php';

$showKadriImg_ifNullBiogImg=true;

if (!isset($_GET['wap'])) { if (!isset($_GET['id']))	{echo $head;} }
else { echo $head_wap;}

if(isset($_GET['id']))
 {


  $query='select news.*,users.fio,users.id as user_id from news left join users on users.id=news.user_id_insert where news.id="'.$_GET['id'].'" ';
  //echo $query;
  $res00=mysql_query ($query);
	echo $head1;
  if(mysql_num_rows($res00)==1)
   {
	$g=mysql_fetch_array($res00);

	$e=$g['file'];
      $e=htmlspecialchars($e,ENT_COMPAT );
      $e=msg_replace($e);

	if (!isset($_GET['save']) && !isset($_GET['print']))
		{
		 echo "<div style='text-align:right;'>
		 	<a class=text href='?".$_SERVER["QUERY_STRING"]."&save&attach=doc' title='Выгрузить'>Передать в MS Word</a>&nbsp;&nbsp;&nbsp;
			<a class=text href='?".$_SERVER["QUERY_STRING"]."&print' title='Распечатать'>Печать</a></div>";
		}
	  $class_css='';
	  if (isset($_GET['save']) || isset($_GET['print']))  {$class_css='_big';}

	   if ($g['news_type']=='notice') {$g['title']=$g['title'].'<div class="author'.$class_css.'">(автор: '.($hide_person_data_rule?$hide_person_data_text:$g['fio']).')</div>' ;}
  
	  echo '<br><div class="middle'.$class_css.'">'.$g['title'].'</div><br><div class="time'.$class_css.'">'.DateTimeCustomConvert($g['date_time'],'dt','mysql2rus').'</div><br><div class="text'.$class_css.'">';
      if ($g['image']!="")
       {
        $notice_img_path='';$notice_img_path_small='';
		if ($g['news_type']=='notice'){$notice_img_path='lects/';		  }
		else {$notice_img_path='news/';}
		$notice_img_path_small='small/';
       }
      else {
           //echo '<img src="images/design/themes/'.$theme_folder.'/no_photo.gif" border="0" align="left" hspace="10" vspace="0">';
      }
      echo $e.'</div>';
		  if (trim($g['file_attach'])!='') 
		  	{	
			   echo '<br> <div class=success>Прикреплен файл: <a href="news/attachement/'.trim($g['file_attach']).'">

			  		<img src="images/design/attachment.gif" border=0><b>'.trim($g['file_attach']).'</b></a></div>';
					  
			}
	if (!isset($_GET['save']) && !isset($_GET['print']))
		{  
		 echo '<div class="middle_library" id="close_link"><br>';
		 if (isset($_GET['wap'])) {echo '<a onclick="javascript:history.back();" href="#">к списку новостей</a>';}
		 else {echo'<a href="javascript:window.close()">Закрыть</a>';}
		 echo '</div>';
			 }	  
      ?>
      <?php include('footer.php'); ?>
      <?php

   }
  else
   {
    echo '<div style="text-align:center;" class=warning>Запись не найдена</div><p align=center><a href=#close onclick="javascript:window.close();">Закрыть</a></p>'; exit();
   }
 }
else
 {
    if(!isset($_GET['number'])) {
        $number=1; $start=0;
    } else {
        $number=$_GET['number'];$start=$number*5-5;
    }
    $res01=mysql_query ('select count(*) from news');
    $count=mysql_result($res01,0,0);
    $pages=$count/5;
    $pages_and=$pages;
    $pages_and=intval($pages_and);
    if ($pages_and==$pages) {
        $pages=$pages_and;
    } else {
        $pages=$pages_and+1;
    }
    if(($number>$pages) || ($number<1)) {
        $number=$pages;
        if ($number == 0) {
            $start = 0;
        } else {
            $start=$number*5-5;
        }
    }
    $finish=$start+5;
    if($finish>$count) {
        $length=$count-$start;
    } else {
        $length=5;
    }
    $query='select n.*,u.fio,u.id as user_id,k.photo as kadri_photo,b.image as user_photo
  	from news n
	left join users u on u.id=n.user_id_insert 
	left join kadri k on k.id=u.kadri_id
	left join biography b on b.user_id=u.id
	order by n.date_time desc 
	  limit '.$start.','.$length;

  $res02=mysql_query ($query) or die(mysql_error());
  echo '<div class="main">'.$pg_title;
if (isset($_SESSION['auth']) && $_SESSION['auth']==1 /*&& $_SESSION['userType']!='преподаватель'*/) 
{echo '<a href="admin_news.php?go=1" class=text title="'.$_SESSION['FIO'].'"> добавить  новость</a>';}
echo '</div>';
  ?>  
  <p style="text-align:center;">	
  	<?php 
	  if ($pages>1)
	  {
		$q_str=reset_param_name($_SERVER["QUERY_STRING"],'number');
		if ($q_str!="") {$q_str=$q_str.'&';}
	  } else {
		// <abarmin date="04.05.2012">
		$q_str = "";
		// </abarmin>
	  }
	  //$href="index.php?".$q_str."&number=";
	  //printPGnums($pages,$number,$href);
	  echo getPagenumList($pages,$number,3,'number',$q_str,'');
	  ?> 
  </p>
  <table border="1" width="95%" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
  <?php
  while($a=mysql_fetch_array($res02))
   {
    echo '<tr><td colspan=2 background="'.$files_path.'_themes/'.$theme_folder.'/images/news_03.gif" height="10" bgcolor="#2020bd"></td></tr>';
    echo '<tr>';
	
	//вывод фото новости (кроме ?wap)
	
if (!isset($_GET['wap'])) {
 	
	echo '<td width="130" align="center" valign="middle"><a href=#view 
	  		onclick=javascript:win_open("index.php?id='.$a['id'].'",400,600); title="подробнее...">';
    if ($a['image']!="")
     {
      if ($a['news_type']=='notice') 
	  {
	   //echo '<img src="images/lects/small/sm_'.urlencode($a['image']).'" align="center" valign="middle"  border=0>';
	   echo '<img src="images/news/small/sm_'.urlencode($a['image']).'" align="center" valign="middle"  border=0>';
	   }
	  else {echo '<img src="images/news/small/sm_'.urlencode($a['image']).'" align="center" valign="middle" alt="Фото новости" border=0>';}
     }
      else {
           //сначала пытаемся получить фото из анкета, если не находим из биографии
	   
	   if ($a['user_photo']!="") 
		   	{echo '<img src="images/lects/small/sm_'.$a['user_photo'].'" 
			   border="0" align="center" valign="middle" alt="Фото преподавателя из биографии" border=0>';}
           else if ($showKadriImg_ifNullBiogImg && $a['kadri_photo']!="") 
		{echo '<img src="images/lects/small/sm_'.$a['kadri_photo'].'" 
			   border="0" align="center" valign="middle" alt="Фото преподавателя из анкеты" border=0>';}
		else {echo '<img src="images/design/notice.gif" border="0" align="center" valign="middle" alt="Фото новости" border=0>';}
      }

    echo '</a></td>';
}else {echo '<td width=5></td>';}

    echo '<td width="100%" valign="top">';
    $file_path='news/';
	if ($a['news_type']=='notice') {$file_path='notice/';}

	  $b=$a['file'];$chars4read=200;
	  
	 if ($a['news_type']=='notice') {$a['title']=$a['title'].'<br><font color=#8D8D8D> 
	 (автор: ';
	 
	 if ($hide_person_data_rule)
		$a['title'].=$hide_person_data_text;
	else {
		$a['title'].='<a href="p_lecturers.php?';
		if (isset($_GET['wap']) ) { $a['title'].='wap&';}	 
		$a['title'].='onget=1&idlect='.$a['user_id'].'" title="об авторе...">'.$a['fio'].'</a>' ;
	}
	$a['title'].=')</font>';
	}
	
     echo '<p class="title">'.$a['title'].'</p><p class="text">'.DateTimeCustomConvert($a['date_time'],'dt','mysql2rus').'</p>';
	 if (trim($a['file_attach'])!='') {echo '<a href="news/attachement/'.trim($a['file_attach']).'" 
	 	title="Скачать прикрепленный файл: '.trim($a['file_attach']).'"><img src="images/design/attachment.gif" border=0></a>';
		 echo '<span class=success>';
		 print_file_size('news/attachement/'.trim($a['file_attach']));
		 echo '</span>';
		 }

	 echo'<div class="news" valign="middle">';
     //echo ' strlen='.strlen($b);
	 $details='';
	 if (strlen($b)>$chars4read)
	 {
		$posCnt=strpos($b," ",$chars4read-20);
		if ($posCnt>0) $b=substr($b,0,$posCnt);				
      $b=$b.' ...';
      $url='index.php?';
	  if (isset($_GET['wap']))
      {$url.='wap&';}
      
	  $details='<div class="menu" align="right"><a href=#view 
	  		onclick=javascript:win_open("'.$url.'id='.$a['id'].'",400,600);>подробнее...</a></div>';
	  } 
      $b=htmlspecialchars($b,ENT_COMPAT );
      $b=msg_replace($b);

      echo $b.'</div>'.$details;
	  
      echo '</td></tr>';
     //}
   }
  ?>
  </table>
  <p style="text-align:center;">
  	<?php 
	  //$href="index.php?number=";
	  //printPGnums($pages,$number,$href);
	  echo getPagenumList($pages,$number,3,'number','','');
	  
	  ?> 
  </p>
  <?php

if (!isset($_GET['wap'])) {
    echo $end1;
        include "display_voting.php";
}

echo $end2; 
include 'footer.php';

 }

?>