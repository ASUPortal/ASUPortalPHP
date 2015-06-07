<?php
include 'sql_connect.php';

$pg_title="Преподаватели";

$idlect=0;
if (isset($_GET['idlect']) && intval($_GET['idlect'])>0) { $idlect=intval($_GET['idlect']); }

$fiolect='';
if (!$hide_person_data_rule && $idlect>0) {
  $fiolect=getScalarVal('select FIO from users where id="'.$idlect.'"');
  if ($fiolect!='') {
      $head_title=$fiolect.'. ';
      if (isset($head_title)) {
          $head_title .= $head_title;
      }
  }
}



if (!isset($_GET['onget']))  {//идем на список преподавателей сразу
  $wap='';
  if (isset($_GET['wap'])) {$wap='wap&';}
  
  header('Location:?'.$wap.'onget=1&getallsub=1');exit();}

include 'header.php';

if (!isset($_GET['wap'])) { if (!isset($_GET['id']))	{echo $head;} }
else { echo $head_wap;}

echo '<div class="main">'.$pg_title.'</div>';

if ($hide_person_data_rule) die($hide_person_data_task);

$firstLet=array ("А","Б","В","Г","Д","Е","Ё","Ж","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф",
"Х","Ц","Ч","Ш","Щ","Э","Ю","Я");

$lectors4page=20;
$showKadriImg_ifNullBiogImg=true;	//показывать фото из анкеты, если нет в биографии

function letters()
 {global $firstLet;
  $letterId=-1;
  if (isset($_GET['getsub']) && intval($_GET['getsub'])>=0) {$letterId=intval($_GET['getsub']);}
  echo '<div class="middle_library" style="font-size:18pt;">';
  $query_letter_rus='select UPPER(left(u.fio,1)) as name, COUNT(*) AS cnt  
					from users u 
					where u.status="преподаватель"
					group by 1
					order by 1';
  $res_rus=mysql_query($query_letter_rus);
  if (mysql_num_rows($res_rus)>0){
    while ($a_rus=mysql_fetch_assoc($res_rus)) {
        if (array_key_exists($letterId, $firstLet)) {
            if ($firstLet[$letterId]==$a_rus['name']) {
                echo '<font size=+3>'.$a_rus['name'].'<sub class=fLetter_cnt>'.$a_rus['cnt'].'</sub></font>&nbsp;';
            } else {
                echo '<a href="?onget=1&getsub='.array_search($a_rus['name'],$firstLet).'" title="в категории записей: '.$a_rus['cnt'].'">'.$a_rus['name'].'<sub class=fLetter_cnt>'.$a_rus['cnt'].'</sub></a>&nbsp;';
            }
        } else {
            echo '<a href="?onget=1&getsub='.array_search($a_rus['name'],$firstLet).'" title="в категории записей: '.$a_rus['cnt'].'">'.$a_rus['name'].'<sub class=fLetter_cnt>'.$a_rus['cnt'].'</sub></a>&nbsp;';
        }
	}
  }
  else {echo '<div class=text> <b>записей не найдено.</b></div>';}  
  /*for ($i=0;$i<count($firstLet);$i++)
  	{ if ($firstLet[$i]=="A") {echo '<br><BR>';}
	  if ($letterId==$i) {echo '<font size=+2>'.$firstLet[$i].'</font>&nbsp;';}
	  else echo '<a href="?onget=1&getsub='.$i.'">'.$firstLet[$i].'</a>&nbsp;'; 	     }
  */
  echo '<br><br><a href="?onget=1&getallsub=1">все</a><br><BR></div>';
 }


?>
<?php
//-------------------------------------------------------------------
echo '<div class=text style="text-align:center"> выберите первую букву фамилии преподавателя </div>';
if (isset($_SESSION['auth']) && $_SESSION['userType']=='преподаватель') {echo '<a href="_modules/_biography/" class=text title="'.$_SESSION['FIO'].'"> добавить  биографию</a>';}
echo '<br>';
    letters();
/**
 * Переписано для использования новой системы глобальных настроек
 */
$def_settings["year_name"] = CUtils::getCurrentYear()->name;
$def_settings["date_start"] = CUtils::getCurrentYear()->date_start;
$def_settings["date_end"] = CUtils::getCurrentYear()->date_end;
$def_settings["year_id"] = CUtils::getCurrentYear()->getId();
$def_settings["part_id"] = CUtils::getCurrentYearPart()->getId();
$def_settings["part_name"] = CUtils::getCurrentYearPart()->name;
//-----------------------------------------------------				
				
  if (isset($_GET['getsub']))	//вывод с фильтром по начальной букве в Фамилии
   {
    
    $letter=$firstLet[$_GET['getsub']];
    $res0=mysql_query ('select * from users where status!="администратор" order by FIO');
    $b2=0;
    while($a=mysql_fetch_array($res0))
     {
      $b=substr($a['FIO'],0,1);
      if ($b==$letter)
       {
        $res0aaa=mysql_query ('select * from biography where user_id="'.$a['id'].'"');
        $p111c=mysql_num_rows($res0aaa);
        $b2++;
        echo '<p class="lecturers"><a href="?onget=1&idlect='.$a['id'].'">'.$a['FIO'];
        if ($p111c==1)
         {
          echo '(+)</a>';
         }
        else
         {
          echo '(-)</a>';
         }
       }
     }
    if ($b2==0)
     {
      echo '<p class="lecturers">В данном разделе преподавателей нет.';
     }
    echo '<p class="text" valign="bottom">(+) биография есть(-) биографии нет';
   }
  
  if (isset($_GET['getallsub']))	//вывод общего списка без фильтра
   {
    if(!isset($_GET['number']))
     {
      $number=1; $start=0;
     }
    else
     {
      $number=$_GET['number'];$start=$number*$lectors4page-$lectors4page;
     }    

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
      $number=$pages;$start=$number*$lectors4page-$lectors4page;
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
    $res06=mysql_query ('select * from users where status!="администратор" order by FIO asc limit '.$start.','.$length);
	echo '<div align=center>';
	//$href='?onget=1&getallsub=1&number=';
	//printPGnums($pages,$number,$href);
	echo getPagenumList($pages,$number,3,'onget=1&getallsub=1&number','','');
    echo '</div>';
	
	while($p=mysql_fetch_array($res06))
     {
      $res06aaa=mysql_query ('select * from biography where user_id="'.$p['id'].'"');
      $p111=mysql_num_rows($res06aaa);
//-------------------------------------------------
      $add_msg='';
	  $query='SELECT COUNT(*) from diploms 
	  		left join kadri on diploms.kadri_id=kadri.id 
			left join users on users.kadri_id=kadri.id where users.id="'.$p['id'].'" and users.kadri_id>0';
	  $res06aaa=mysql_query ($query);
      $dipl_cnt=mysql_result($res06aaa,0); 
      if ($dipl_cnt>0) {$add_msg='дипломников('.$dipl_cnt.')';}
      
      $query='select count(*) from documents where documents.user_id="'.$p['id'].'"';
	  $res06aaa=mysql_query ($query);
      $dipl_cnt=mysql_result($res06aaa,0); 
      if ($dipl_cnt>0) {$add_msg=$add_msg.' предметов('.$dipl_cnt.')';}

      $query='select count(*) from news where user_id_insert="'.$p['id'].'"';
	  $res06aaa=mysql_query ($query);
      $dipl_cnt=mysql_result($res06aaa,0); 
      if ($dipl_cnt>0) {$add_msg=$add_msg.' объявлений('.$dipl_cnt.')';}

      $query='select count(id) from time where id="'.$p['id'].'" and  
				time.year="'.$def_settings['year_id'].'" and time.month="'.$def_settings['part_id'].'"';
	  $res06aaa=mysql_query ($query);
      $dipl_cnt=mysql_result($res06aaa,0); 
      if ($dipl_cnt>0) {$add_msg=$add_msg.' расписание';}

//-------------------------------------------------      
      
	  echo '<p class="lecturers">
      <a href="?onget=1&idlect='.$p['id'].'">'.$p['FIO'].' ';
      if ($p111==1)
       {
        echo '(+)</a>';
       }
      else
       {
        echo '(-)</a>';
       }
       echo ' <span class=text style="color:#CCCCCC;">'.$add_msg.'</span>';
     }
    echo '<p class="text" valign="bottom">(+) биография есть(-) биографии нет';
    echo '<p align=center>';
    
	//$href='?onget=1&getallsub=1&number=';
	//printPGnums($pages,$number,$href);
	echo getPagenumList($pages,$number,3,'onget=1&getallsub=1&number','','');
	
   }
  if ($idlect>0)	//вывод конкретной единицы по идентификатору
   {
    $res00=mysql_query ('select * from biography where user_id="'.$idlect.'"');
    $res00aaa=mysql_query('select FIO from users where id="'.$idlect.'"');
    $g1=mysql_fetch_array($res00aaa);
	
	//$query="select photo from kadri where id in(select kadri_id as id from users where id='".$idlect."') limit 0,1";
    //$res_photo=mysql_query($query);
    //$photo=mysql_fetch_array($res_photo);
    $photo_biog=getScalarVal("select image from biography where user_id='".$idlect."' limit 0,1 ");
    $photo_kadri=getScalarVal("select photo from kadri where id in(select kadri_id as id from users where id='".$idlect."') limit 0,1");
    //echo 'test';
	echo '<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF"><tr><td>';
	echo '<div class="middle">'.$g1['FIO'].'</div><br><br>';
	if(mysql_num_rows($res00)==1)
     {
      $g=mysql_fetch_array($res00);
        echo '<div class="text">';
        if ($photo_biog!="")
         {
         	$filename = 'images/lects/small/sm_'.urlencode($photo_biog).'';
         	if (file_exists($filename)) {
		  		echo '<img src="images/lects/small/sm_'.urlencode($photo_biog).'" border="0" align="left" hspace="10" vspace="0" title="фото из биографии">';
		  	} else {
		  		echo '<img src="_modules/_thumbnails/?src=images/lects/'.urlencode($photo_biog).'" border="0" align="left" hspace="10" vspace="0" title="фото из биографии">';
		  	}
         }
	 else if ($showKadriImg_ifNullBiogImg && $photo_kadri!="")
         {
         	$filename = 'images/lects/small/sm_'.urlencode($photo_kadri).'';
         	if (file_exists($filename)) {
		  		echo '<img src="images/lects/small/sm_'.urlencode($photo_kadri).'" border="0" align="left" hspace="10" vspace="0" title="фото из анкеты">';
		  	} else {
		  		echo '<img src="_modules/_thumbnails/?src=images/lects/'.urlencode($photo_kadri).'" border="0" align="left" hspace="10" vspace="0" title="фото из анкеты">';
		  	}
         }

//----------------------------
	$b=$g['main_text'];
	 
	 $chars4read=600;
	  
	 $details='';$b_='';
	 if (strlen($b)>$chars4read)
	 {
		  
		  //if (ereg ("\.|\!", $b)) {echo 'найшли знаки!!!!!!!';}
		  //echo '<hr>strpos= '.strpos($b," ",$chars4read-20).'<hr>';
		  $firstPos=strpos($b,"\n",$chars4read-100);	//первая  часть биографии
		  if ($firstPos<=0) {$firstPos=strpos($b," ",$chars4read-20);}
		  
		  $b=substr($b,0,$firstPos);

      //$b_=$b.' ...';
      $b_=substr($g['main_text'],$firstPos);	//остаток текста биографии
      $b_= '<div class=text style="display:none" name="end_biog" id="end_biog">'.$b_.'</div>';
	  $details='<div class="menu" align="right" name="hide_show_biog" id="hide_show_biog"><a href=#view 
	  		onclick=\'javascript:hide_show("end_biog");changeTextHide("hide_show_msg");\'><span id="hide_show_msg">подробнее...</span></a></div>';
	  } 
      //$b=htmlspecialchars($b,ENT_COMPAT );
      $str_biog=$b.'</div>'.$b_;
	  
	  $str_biog=msg_replace($str_biog);
	  $str_biog=str_replace("\n","<br>",$str_biog);
	  
      echo $str_biog.$details;

//---------------------------
     }
    else
     {
      //echo '<div class="middle">'.$g1['FIO'].'</div><br><br>';
      echo '<p class="lecturers">Биография не выложена.';
     }
		$res=mysql_query ('select `id`,`title` from `pg_uploads` where `user_id_insert`='.$idlect.' and type_id<>1');
		if (mysql_num_rows($res)<1) {
			//echo '<div class=text>&nbsp;&nbsp;-веб-страниц на портале нет</div>';
			}
		else {
			echo "<p><div class=text style='font-weight:bold; text-decoration:underline;'> Веб-страницы на портале: (".mysql_num_rows($res).")</div>\n<ul class=text>\n";
			while ($a=mysql_fetch_array($res)) 
				{echo "<li><a  href='pg_view.php?pg_id=".$a['id']."'>".$a['title']."</a></li>";}
		}
		echo "</ul>\n";
	        $res=mysql_query ('SELECT d.nameFolder, s.name AS nameSubject, (select count(*) from files f where f.nameFolder= d.nameFolder) as f_cnt
				  FROM    subjects s
				       LEFT OUTER JOIN
				          documents d
				       ON (s.id = d.subj_id)
				 WHERE d.user_id ="'.$idlect.'"');

	 echo "<div class=title> Список пособий на портале: (".mysql_num_rows($res).")</div>\n";
	        if (mysql_num_rows($res)<1) {echo '<div class=text>&nbsp;&nbsp;-пособий на портале нет</div>'."\n";}
	  echo "<ul class=text>";
			while ($a=mysql_fetch_array($res)) 
				{echo "<li><a href='p_library.php?onget=1&getdir=".$a['nameFolder']."'>".
					$a['nameSubject']." (".$a['f_cnt'].")</a></li>\n";}
	  echo "</ul>\n";
//--------------
	        $res=mysql_query ('select id,title,date_time,file from news 
				where user_id_insert="'.$idlect.'" and date_time>="'.$def_settings['date_start'].'"
				order by date_time DESC');
		echo "<p><div class=title> Объявления текущего учебного года: (".mysql_num_rows($res).")</div>";
	        if (mysql_num_rows($res)<1) {echo '<div class=text>&nbsp;&nbsp;-объявлений на портале нет</div>';}

			  $url='';
			  if (isset($_GET['wap']))
		      {$url='wap&';}
			echo "<ul class=text>";
			while ($a=mysql_fetch_array($res)) 
				{echo "<li><a href='#newsOpen' onclick=javascript:win_open('index.php?".$url."&id=".$a['id']."',400,600);>".
				$a['title']." от ".DateTimeCustomConvert($a['date_time'],'dt','mysql2rus')."</a></li>";}
			echo "</ul>";
		//-------------------
	        $res=mysql_query ('select id,title,date_time,file from news 
				where user_id_insert="'.$idlect.'" and date_time<"'.$def_settings['date_start'].'"
				order by date_time DESC');
		echo "<p><div class=title> <a href=javascript:hide_show('news_old');>Объявления прошлых учебных лет: (".mysql_num_rows($res).")</a></div>";
	        if (mysql_num_rows($res)<1) {echo '<div class=text>&nbsp;&nbsp;-объявлений на портале нет</div>';}

			  $url='';
			  if (isset($_GET['wap']))
		      {$url='wap&';}
			echo '<div style="display:none;" name="dipl_old" id="news_old">';
			echo '<ul class=text>';
			while ($a=mysql_fetch_array($res)) 
				{echo "<li><a href='#newsOpen' onclick=javascript:win_open('index.php?".$url."&id=".$a['id']."',400,600);>".$a['title']." от ".DateTimeCustomConvert($a['date_time'],'dt','mysql2rus')."</a></li>";}
			echo '</ul>';
echo '</div>';				
		
		//-------------------------------------------------------------------------
	        $query='SELECT diploms.id,diploms.dipl_name,pp.name as pract_place,kadri.id as kadri_id,kadri.fio as kadri_fio,students.fio as student_fio,study_groups.name as group_name,diploms.comment
		FROM diploms
		left join students on diploms.student_id=students.id
		left join pract_places pp on pp.id=diploms.pract_place_id
		left join kadri on diploms.kadri_id=kadri.id
		left join study_groups on study_groups.id=students.group_id
		inner join users on users.kadri_id=kadri.id  
			where users.kadri_id>0 and users.id="'.$idlect.'" and (diploms.date_act>="'.$def_settings['date_start'].'" or date_act is NULL)	order by students.fio';
			/*
$archiv_query=' and date_act_sort>"'.$def_settings['date_start'].'"';
if (isset($_GET['archiv'])) {$archiv_query=' and (date_act_sort<"'.$def_settings['date_start'].'" or date_act_sort is NULL)';}
			*/
			//'select id,title,DATA,file from news where user_id_insert="'.$idlect.'" order by DATA_sort DESC'
			$res=mysql_query($query);
		echo "<p><div class=title> Дипломники текущего учебного года: (".mysql_num_rows($res).") </div>";
	        if (mysql_num_rows($res)<1) {echo '<div class=text>&nbsp;&nbsp;-дипломников на портале нет</div>';}
			echo '<ul class=text>';
			while ($a=mysql_fetch_array($res)) 
				/*{echo "-<a class=text style='color:#000099' href='#' title='".$a['pract_place']."'>".$a['student_fio']." (".$a['group_name'].")<br><b>".$a['dipl_name']."</b></a><p>";}*/
				{
				echo "<li>".$a['student_fio']." (".$a['group_name']."), ";
				if (strlen($a['pract_place'])>3) echo ", место практики:<u>".$a['pract_place']."</u><br>"; else echo '<br>';
				echo "<u>".$a['dipl_name']."</u></li>";
				}
			echo '</ul>';
		//-----------------------------------------------------
	        $query='SELECT diploms.id,diploms.dipl_name,pp.name as pract_place,kadri.id as kadri_id,kadri.fio as kadri_fio,students.fio as student_fio,study_groups.name as group_name,diploms.comment
		FROM diploms
		left join students on diploms.student_id=students.id
		left join kadri on diploms.kadri_id=kadri.id
		left join study_groups on study_groups.id=students.group_id
		left join pract_places pp on pp.id=diploms.pract_place_id
		left join users on users.kadri_id=kadri.id  
			where users.kadri_id>0 and users.id="'.$idlect.'" and (diploms.date_act<"'.$def_settings['date_start'].'" )  
			order by students.fio';
			//echo 'query='.$query;
			$res=mysql_query($query);
		echo "<p>
		<div class=title> 
		<a href=javascript:hide_show('dipl_old');>Дипломники предыдущих учебных лет: (".mysql_num_rows($res).") </a></div>";
		echo '<div style="display:none;" name="dipl_old" id="dipl_old">';
	        if (mysql_num_rows($res)<1) {echo '<div class=text>&nbsp;&nbsp;-дипломников на портале нет</div>';}
			echo '<ul class=text>';
			while ($a=mysql_fetch_array($res)) 
				/*{echo "-<a class=text style='color:#000099' href='#' title='".$a['pract_place']."'>".$a['student_fio']." (".$a['group_name'].")<br><b>".$a['dipl_name']."</b></a><p>";}*/
				{
				echo "<li>".$a['student_fio']." (".$a['group_name'].")";
				if (strlen($a['pract_place'])>3) echo ", место практики:<u>".$a['pract_place']."</u><br>";
				echo "<i>".$a['dipl_name']."</i></li>";
				}
			echo '</ul>';
		echo '</div>';

		//-------------------------------------------------------------------------

	        $query='SELECT k.fio,d.`tema` FROM `disser` d inner join kadri k on k.id=d.`kadri_id` 
					WHERE d.`kadri_id`>0 and `scinceMan`=(select kadri_id from users where id='.$idlect.') and `god_zach`>="'.date("Y").'" order by k.fio';
			//echo 'query='.$query;
			$res=mysql_query($query);
		echo "<p>
		<div class=title> 
		<a href=javascript:hide_show('aspits'); title='сроком обучения не истек'>Подготовка аспирантов, текущие: (".mysql_num_rows($res).") </a></div>";
		echo '<div style="display:none;" name="aspits" id="aspits">';
	        if (mysql_num_rows($res)<1) {echo '<div class=text>&nbsp;&nbsp;-аспирантов на портале нет</div>';}
			echo '<ul class=text>';
			while ($a=mysql_fetch_array($res)) 
				/*{echo "-<a class=text style='color:#000099' href='#' title='".$a['pract_place']."'>".$a['student_fio']." (".$a['group_name'].")<br><b>".$a['dipl_name']."</b></a><p>";}*/
				{echo "<li>".$a['fio']." <u>".$a['tema']."</u></li>";}
			echo '</ul>';
		echo '</div>';

		//-------------------------------------------------------------------------

	        $query='SELECT k.fio,d.`tema` FROM `disser` d inner join kadri k on k.id=d.`kadri_id` 
					WHERE d.`kadri_id`>0 and `scinceMan`=(select kadri_id from users where id='.$idlect.') and `scinceMan`>0 
						and (`god_zach`<"'.date("Y").'" or `god_zach` is null) order by k.fio';
			//echo 'query='.$query;
			$res=mysql_query($query);
		echo "<p>
		<div class=title> 
		<a href=javascript:hide_show('aspits_old'); title='с истекшим сроком обучения'>Подготовка аспирантов, архив: (".mysql_num_rows($res).") </a></div>";
		echo '<div style="display:none;" name="aspits_old" id="aspits_old">';
	        if (mysql_num_rows($res)<1) {echo '<div class=text>&nbsp;&nbsp;-аспирантов на портале нет</div>';}
			echo '<ul class=text>';
			while ($a=mysql_fetch_array($res)) 
				/*{echo "-<a class=text style='color:#000099' href='#' title='".$a['pract_place']."'>".$a['student_fio']." (".$a['group_name'].")<br><b>".$a['dipl_name']."</b></a><p>";}*/
				{echo "<li>".$a['fio']." <u>".$a['tema']."</u></li>";}
			echo '</ul>';
		echo '</div>';

		//-------------------------------------------------------------------------
		echo "<p><div class=title> Расписание занятий: </div>";
	
	        $res=mysql_query ('select id from time where id="'.$idlect.'" and  
				time.year="'.$def_settings['year_id'].'" and time.month="'.$def_settings['part_id'].'" limit 0,1');
	        if (mysql_num_rows($res)<1) {echo '<div class=text>&nbsp;&nbsp;-расписания на портале нет</div>';}
			echo '<ul class=text>';
			while ($a=mysql_fetch_array($res)) 
				{echo "<li><a  href='p_time_table.php?onget=1&idlect=".$a['id']."'> расписание занятий </a></li>";}
			echo '</ul>';
		
		
	
	        $res=mysql_query ('select q2u.user_id,q2u.question_text,q2u.contact_info,q2u.answer_text,q2u.datetime_quest,q2u.datetime_answ 
	  from question2users q2u 
	  where q2u.status=3 and answer_text is not null and answer_text!="" and user_id='.$idlect.' 
	  order by q2u.datetime_quest');
        echo "<p><div class=title> Вопросы и ответы на них преподавателя: (".mysql_num_rows($res).") &nbsp; <a href=\"_modules\_question_add\index.php?action=index&user_id=$idlect\">Задать вопрос</a></div>";
        if (mysql_num_rows($res)<1) {echo '<div class=text>&nbsp;&nbsp;-вопросов с ответами на портале нет</div>';}
			echo '<ul class=text>';
			while ($a=mysql_fetch_array($res)) 
				{echo "<li>вопрос: <font color=grey>".$a['question_text']."</font>, ответ: <b>".$a['question_text']."</b></li>";}
			echo '</ul>';




	        $res=mysql_query ('select sg.id,sg.name
                from study_groups sg
                    left join users u on u.kadri_id=sg.curator_id
                    where u.kadri_id>0 and u.id='.$idlect);
		
            echo "<p><div class=title> Кураторство учебных групп: (".mysql_num_rows($res).")</div>";
        if (mysql_num_rows($res)<1) {echo '<div class=text>&nbsp;&nbsp;-записей на портале нет</div>';}
			echo '<ul class=text>';
			while ($a=mysql_fetch_array($res))
				{echo "<li><a href='p_stgroups.php?onget=1&group_id=".$a['id']."'>".$a['name']."</a></li>";}
			echo '</ul>';

     echo '</td></tr></table>';

   }
   
 if (!isset($_GET['wap'])) {
  echo $end1;
  include "display_voting.php";
  
}
define("CORRECT_FOOTER", true);
echo $end2; include('footer.php'); 

 //mysql_close();
?>