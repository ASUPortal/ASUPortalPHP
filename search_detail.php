<?php

include 'sql_connect.php';

include "header.php";
echo $head;    ?>

<div class="main">Расширенный поиск: портал АСУ</div><br>
  
<br>&nbsp;<br>

<TABLE cellSpacing=0 cellPadding=0 width=95% border=0 align=center>
   <TR><TD>
		<form method="GET" action="" name="s_form">
			<div class=text> <input type="text" size="40" name="q" <?php 
				if (isset($_GET['q'])) {echo 'value="'.$_GET['q'].'"';} else {echo 'value=""';} ?> > текст запроса <input type=submit value="Найти">
				&nbsp;  
				<label><span class=text><input type="checkbox" name="reg" <?php 
					if (isset($_GET['reg']) && $_GET['reg']=='on') {echo 'checked';} else {echo '';} ?> >учитывать регистр </span></label> &nbsp; 
				</div>
				
			<p class=text> &nbsp; 
				<label><span class=text><input type="checkbox" name="r1" <?php 
					if (isset($_GET['r1']) && $_GET['r1']=='on') {echo 'checked';} else {echo '';} ?> >новости, объявления </span></label> &nbsp; 
				<label><span class=text><input type="checkbox" name="r2" <?php 
					if (isset($_GET['r2']) && $_GET['r2']=='on') {echo 'checked';} else {echo '';} ?>>учеба </span> </label>  &nbsp; 
				<label><span class=text><input type="checkbox" name="r3" <?php 
					if (isset($_GET['r3']) && $_GET['r3']=='on') {echo 'checked';} else {echo '';} ?>>документы </span> </label>  &nbsp; 
				<label><span class=text><input type="checkbox" name="r4" <?php 
					if (isset($_GET['r4']) && $_GET['r4']=='on') {echo 'checked';} else {echo '';} ?>>преподаватели </span> </label>  &nbsp; <b>разделы портала для поиска:</b></p><div class=text> макс.число результатов поиска по каждому разделу не более 30</div><br>
		</form>
</td></tr> 
</TABLE>

<!--TABLE cellSpacing=0 cellPadding=0 width=95% border=1 align=center>
   <TR><TD><input type="text" size="40" value="" name="q"></TD><TD><div class=text></div></td></tr> 
   <TR><TD><input type="text" size="40" value="" name="q"></TD><TD><div class=text></div></td></tr> 
</TABLE-->

<?php
$err=false;
$maxRows=30;

if (!isset($_GET['r1']) && !isset($_GET['r2']) && !isset($_GET['r3']) && !isset($_GET['r4'])) 
	{echo '<div class=warning> не выбрано ни одного раздела для поиска </div>';$err=true;}

if (!isset($_GET['q']) || trim($_GET['q'])=='') 
	{echo '<div class=warning> введите текст для поиска </div>';$err=true;}

if (isset($_GET['q']) && strlen(trim($_GET['q']))<3 ) 
	{echo '<div class=warning> текст для поиск должен содержать более 2 символов (исключая пробелы) для поиска </div>';$err=true;}

if ($err==false)   { 
 //начинаем поиск
 $q=trim($_GET['q']);
 
 echo '<div class=text> Вы искали: <span style="font-weight:bold;text-decoration:underline;">'.$_GET['q'].'</span></div>';
//-------------------------------------------------------------
	  if (!isset($_GET['reg']) || $_GET['reg']!='on') {$q=strtolower($q);}

  if ($_GET['r1']=='on') {
	  echo '<br> <div class=text><b>новости:</b></div>';
	  $query='select news.*,users.fio,users.id as user_id from news left join users on users.id=news.user_id_insert ';
	  
	  if (isset($_GET['reg']) && $_GET['reg']=='on') {
		  $query=$query.' where news.title like "%'.$q.'%" or news.file like "%'.$q.'%" or users.fio like "%'.$q.'%" limit 0,'.$maxRows.'';}
	  else {
		  //$q=strtolower($q);
		  $query=$query.' where LOWER(news.title) like "%'.$q.'%" or LOWER(news.file) like "%'.$q.'%" or 
		  	LOWER(users.fio) like "%'.$q.'%" limit 0,'.$maxRows.'';}
		
	 // echo $query;
	  
	  $res=mysql_query ($query);
	  if (mysql_num_rows($res)>0) 
	  	{
		   echo ' найдено совпадений: <b>'.mysql_num_rows($res).' </b>';
	  		$i=0;
			  while ($a=mysql_fetch_array($res))
	  		{	$i++;
	  		 	echo '<p>'.$i.' <a class=text style="color:#2020bd" href="index.php?id='.$a['id'].'" target="_blank">'.$a['title'].', <b>'.$a['fio'].'</b></a></p>';
	  		} 
	  	} 
	  else {echo ' ничего не найдено';} 
  }
//-------------------------------------------------------------
  if ($_GET['r2']=='on') {
	  echo '<br><br> <div class=text><b>учеба:</b></div>';

$query='select distinct files.browserFile,files.DATA as file_date,files.nameFile,files.nameFolder,
		subjects.name as subj_name,subjects.name_short as subj_name_short,
		users.fio,users.fio_short  from files 
	left join documents on documents.nameFolder=files.nameFolder 
	left join subjects on subjects.id=documents.subj_id
	left join users on documents.user_id=users.id ';

	  if (isset($_GET['reg']) && $_GET['reg']=='on') {
	$query=$query.'where files.nameFolder<>"gost" and (files.browserFile like "%'.$q.'%" or subjects.name like "%'.$q.'%" 
		or subjects.name_short like "%'.$q.'%" or users.fio like "%'.$q.'%" or users.fio_short like "%'.$q.'%") limit 0,'.$maxRows.'';}
	else {
	//$q=strtolower($q);
	$query=$query.'where files.nameFolder<>"gost" and (LOWER(files.browserFile) like "%'.$q.'%" or LOWER(subjects.name) like "%'.$q.'%" 
		or LOWER(subjects.name_short) like "%'.$q.'%" or LOWER(users.fio) like "%'.$q.'%" or LOWER(users.fio_short) like "%'.$q.'%") limit 0,'.$maxRows.'';}	
//echo $query;

	  $res=mysql_query ($query);
	  if (mysql_num_rows($res)>0) 
	  	{
		   echo ' найдено совпадений: <b>'.mysql_num_rows($res).' </b>';
	  		$i=0;
			  while ($a=mysql_fetch_array($res))
	  		{	$i++;
	  		 	echo '<p>'.$i.' <a class=text style="color:#2020bd" href="_modules/_library/index.php?action=publicView&id='.$a['nameFolder'].'&getfile='.$a['nameFile'].'" target="_blank">'.$a['browserFile'].', '.$a['subj_name'].', <b>'.$a['fio'].'</b></a></p>';
	  		} 
	  	} 
	  else {echo ' ничего не найдено';} 
  }
//-------------------------------------------------------------  
  if ($_GET['r3']=='on') {
	  echo '<br><br> <div class=text><b>документы:</b></div>';

$query='select distinct files.browserFile,files.DATA as file_date,files.nameFile,files.nameFolder,
		users.fio,users.fio_short  from files 
	left join users on files.id=users.id ';

	  if (isset($_GET['reg']) && $_GET['reg']=='on') {
$query=$query.'	where files.nameFolder="gost" and files.browserFile like "%'.$q.'%" limit 0,'.$maxRows.'';}
	  else {
$query=$query.'	where files.nameFolder="gost" and LOWER(files.browserFile) like "%'.$q.'%" limit 0,'.$maxRows.'';}
		
		
		
	  $res=mysql_query ($query);
	  if (mysql_num_rows($res)>0) 
	  	{
		   echo ' найдено совпадений: <b>'.mysql_num_rows($res).' </b>';
	  		$i=0;
			  while ($a=mysql_fetch_array($res))
	  		{	$i++;
	  		 	echo '<p>'.$i.' <a class=text style="color:#2020bd" href="p_gost_docs_view.php#'.$a['nameFile'].'" target="_blank">'.$a['browserFile'].'</a></p>';
	  		} 
	  	} 
	  else {echo ' ничего не найдено';} 
  }
//-------------------------------------------------------------  
  if ($_GET['r4']=='on') {
	  echo '<br><br> <div class=text><b>преподаватели:</b></div>';

$query='SELECT distinct biography.user_id,biography.main_text,users.fio FROM biography left join users on users.id=biography.user_id ';

	  if (isset($_GET['reg']) && $_GET['reg']=='on') {
$query=$query.'	where users.fio like "%'.$q.'%" or biography.main_text like "%'.$q.'%" LIMIT 0 , '.$maxRows.'';}
else {$query=$query.'	where LOWER(users.fio) like "%'.$q.'%" or LOWER(biography.main_text) like "%'.$q.'%" LIMIT 0 , '.$maxRows.'';}

/*$query='select distinct files.browserFile,files.DATA as file_date,files.nameFile,files.nameFolder,
		users.fio,users.fio_short  from files 
	left join users on files.id=users.id 
	where files.nameFolder="gost" and files.browserFile like "%'.$q.'%" limit 0,30';*/

	  $res=mysql_query ($query);
	  if (mysql_num_rows($res)>0) 
	  	{
		   echo ' найдено совпадений: <b>'.mysql_num_rows($res).' </b>';
	  		$i=0;
			  while ($a=mysql_fetch_array($res))
	  		{	$i++;
	  		 	echo '<p>'.$i.' <a class=text style="color:#2020bd" href="_modules/_lecturers/index.php?action=view&id='.$a['user_id'].'" target="_blank">'.$a['fio'].'</a></p>';
	  		} 
	  	} 
	  else {echo ' ничего не найдено';} 
  }

}
//-----------------------------------------------------------------------
  echo $end1;
  //include "display_voting.php";
  echo $end2;
define("CORRECT_FOOTER", true);
    require("footer.php");

?>
