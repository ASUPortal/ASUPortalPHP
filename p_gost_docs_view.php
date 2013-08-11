<?php
$pg_title='Документации нормативно-справочная';

include 'sql_connect.php';
include 'header.php';

if (!isset($_GET['wap'])) {	echo $head;}
else { echo $head_wap;}

echo '<div class="main">'.$pg_title;
if (isset($_SESSION['auth']) && $_SESSION['auth']==1) {echo '<a href="gost_docs.php" class=text title="'.$_SESSION['FIO'].'"> добавить  свой файл</a>';}
echo '</div><br>';

$folders=array();
//путь						//название
$folders[0][0]='';			$folders[0][1]='';
$folders[1][0]='dolg_instr';$folders[1][1]='должностные инструкции';
$folders[2][0]='edu_stand';	$folders[2][1]='образовательные стандарты';
$folders[3][0]='att_spec';	$folders[3][1]='самообследование';
$folders[4][0]='uch_plan';	$folders[4][1]='учебные планы';
$folders[5][0]='diplom';	$folders[5][1]='к диплому';
$folders[6][0]='instr';	$folders[6][1]='Интеллектуальная система самообучения и самоорганизации пользователей веб-портала';
$folders[7][0]='moodle';	$folders[7][1]='СДО MOODLE';
$folders[8][0]='practice';	$folders[8][1]='Практика';
$folders[9][0]='umk';	$folders[9][1]='Материалы для оформления УМК';
//$folders[3][0]='';			$folders[3][1]='';

//если папки не существуют система их создаст в library/gost/
//не забудьте прописать папки в файле gost_docs.php - для просмотра правки файлов

echo '<div class=text style="text-align:center;"> для увеличения скорости загрузки файлов 
большинство файлов сжаты архиватором WinRar,<br>загрузить архиватор Вы можете <a href="apps/wrar34b5ru.exe"> <u>здесь</u> </a></div>';

      $res6=mysql_query ('select * from files where nameFolder like "'.'gost%'.'"');
      if(mysql_num_rows($res6)==0)
       {echo '<p><div class=text> сейчас не загружено ни одного нормативно-справочного документа <p>
	   Возможно интересующую Вас документацию Вы сможете найти в разделе <a href="p_library.php"> <u>Учеба </u></a></div>';}
      else 
	  {
      echo '<p><table border="0" cellspacing="5" cellpadding="5" align="center" bgcolor="#FFFFFF" width=99%>';
      $i=0;
      /*for ($i=count($folders)-1;$i>0;$i--)
      {	
		  echo "<tr><td colspan=4><a href=# onclick=javascript:hide_show('gost".$i."');>".$folders[$i][1].'</a></td></tr>';
		  $query='select * from files where nameFolder="'.'gost'.$i.'" order by browserFile_trans';
		  $res=mysql_query ($query);
		  echo '<tr><td colspan=4><div style="display:none;" id="gost'.$i.'" name="gost'.$i.'"><table class="middle_lite_other" width="100%">';

		  while($g1=mysql_fetch_array($res6))
	       {  $i++;
	          echo '<tr align="left" height="20"><td width=10>'.$i.'</td>
			  <td><div class="middle_lite_library"><a href="library/gost/'.$g1['nameFile'].'" title="Скачать" >';
	
				file_type_img($g1['nameFile']);
				
			  echo' &nbsp '.$g1['browserFile'].'</a></div></td></tr>';
	         }
      }*/
      for ($i=count($folders)-1;$i>0;$i--)
      	{
		  
		  $query='select files.*,users.FIO_short as FIO_short_ins,users.login as user_login  
			  from files left join users on users.id=files.id 
			  where nameFolder="'.'gost'.$i.'" order by browserFile_trans';
		  $res=mysql_query ($query);
		  echo "<tr><td colspan=2><a class=middle_lite_library href=# onclick=javascript:hide_show('gost".$i."');><img src='images/design/folder.gif' border=0>".$folders[$i][1].
		  	' ('.mysql_num_rows($res).')</a></td></tr>';
		  echo '<tr><td colspan=2><div style="display:none;" id="gost'.$i.'" name="gost'.$i.'"><table class="middle_lite_other" width="100%">';
		  $j=0;
		  while($g1=mysql_fetch_array($res))
	       {
			$query_='select users.FIO_short as user_id_update,users.login as user_login from files left join users on files.user_id_update=users.id 
				where files.id_file='.$g1['id_file'].' limit 0,1';
			$res_=mysql_query($query_);$g1_=mysql_fetch_array($res_);

  			  $j++;
		$dir_name='';
		$id= substr($g1['nameFolder'],4,1);
		
		$dir_name=$folders[$id][0];
		//echo 'id='.$id.', dir_name='.$dir_name;
		
		if ($dir_name!='') {$dir_name=$dir_name.'/';}
	          
			  echo '<tr align="left" height="20"><td width=10><!--'.$j.'--></td>
			  <td><div class="middle_lite_library"><a href="library/gost/'.$dir_name.$g1['nameFile'].'" title="Скачать" >';
				file_type_img($g1['nameFile']);
			  echo' &nbsp '.$g1['browserFile'].'</a>';
			  	if(trim($g1['FIO_short_ins'])=="") {$g1['FIO_short_ins']=$g1['user_login'];}
			  	if(trim($g1_['user_id_update'])=="") {$g1_['user_id_update']=$g1_['user_login'];}
			 echo ' <small style="color:#666666;">( '.($hide_person_data_rule?$hide_person_data_text:$g1['FIO_short_ins'].' / <u>'.$g1_['user_id_update'].'</u>').' )</small>'; 	
			 if (file_exists('library/gost/'.$dir_name.$g1['nameFile'])) {
			 	echo '<font size=-2>, размер файла: <b>'.round(filesize('library/gost/'.$dir_name.$g1['nameFile'])/1024/1024,3).'</b> МБ </font>';}
			 else {echo '<font size=-2 color="#FF0000"> файл не найден </font>';}
			 echo '</div></td></tr>';

	         }
	    	echo '</table></div><hr></td></tr>';
         }
//для доков вне папки $i=0
		  //echo "<tr><td colspan=2><a href=# onclick=javascript:hide_show('gost".$i."');>".$folders[$i][1]."</a></td></tr>";
		  $query='select files.*,users.FIO_short as FIO_short_ins,users.login as user_login 
		  	from files left join users on users.id=files.id 
			where nameFolder="'.'gost'.'" order by browserFile_trans';
		  //echo '<tr><td colspan=2>&nbsp;</td></tr>';
		  $res=mysql_query ($query);
		  echo '<tr><td colspan=2><div style="display:;" id="gost'.$i.'" name="gost'.$i.'"><table class="middle_lite_other" width="100%">';
		  while($g1=mysql_fetch_array($res))
	       {
			$query_='select users.FIO_short as user_id_update,users.login as user_login from files left join users on files.user_id_update=users.id 
				where files.id_file='.$g1['id_file'].' limit 0,1';
			$res_=mysql_query($query_);$g1_=mysql_fetch_array($res_);

 			  $j++;
	          echo '<tr align="left" height="20"><td width=10><!--'.$j.'--></td>
			  <td><div class="middle_lite_library"><a href="library/gost/'.$g1['nameFile'].'" title="Скачать" >';
				file_type_img($g1['nameFile']);
			  echo' &nbsp '.$g1['browserFile'].'</a>';

			  	if(trim($g1['FIO_short_ins'])=="") {$g1['FIO_short_ins']=$g1['user_login'];}
			  	if(trim($g1_['user_id_update'])=="") {$g1_['user_id_update']=$g1_['user_login'];}
			 echo ' <small style="color:#666666;">( '.($hide_person_data_rule?$hide_person_data_text:$g1['FIO_short_ins'].' / <u>'.$g1_['user_id_update'].'</u>').' )</small>'; 	
			 if (file_exists('library/gost/'.$g1['nameFile'])) 
			 	{echo '<font size=-2>, размер файла: <b>'.round(filesize('library/gost/'.$g1['nameFile'])/1024/1024,3).'</b> МБ </font>';}
			 else {echo '<font size=-2 color="#FF0000"> файл не найден </font>';}
			 echo '</div></td></tr>';
	         }
	    	echo '</table></div></td></tr>';         
      echo '</table><br>';	   
         
       }
  echo '<div class=text><b>Примечание:</b>
  <div class=text>в скобках <font style="color:grey;">серым цветом</font> указаны авторы создания/<u>обновления</u> документа
  <br>если имя пользователя на латинском в скобках "создал/<u>обновил</u>", ему необходиом присвоить <u>краткое имя</u> администратором портала </div>
  <br>на внешнем зеркале портала ряд <u>Документов</u> не доступны для загрузки. <br>
  Вам следует обратиться к ним из локальной сети УГАТУ по адресу <a href="http://10.61.2.63"><u>http://10.61.2.63</u></a>.<br>
  Либо послать письменный запрос <a href="mailto:smart_newline@mail.ru"> <u>Администратору портала</u>. </div>';     
	if (!isset($_GET['wap'])) {
	  echo $end1;
	  include "display_voting.php";
	  }
	echo $end2;
define("CORRECT_FOOTER", true);
include('footer.php'); 

?>