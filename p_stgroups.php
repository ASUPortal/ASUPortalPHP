<?php
include 'sql_connect.php';

$pg_title="Учебные группы студентов";

$group_id=0;
if (isset($_GET['group_id']) && intval($_GET['group_id'])>0) { $group_id=intval($_GET['group_id']); }

$group_name='';
if (!$hide_person_data_rule && $group_id>0) {
  $group_name=getScalarVal('select name from study_groups where id="'.$group_id.'"');
  if ($group_name!='') $head_title=$group_name.'. '.$head_title;
}

$showGrPhoto=true;	//показать ссылку на фотогалерею группы при наличии
//$topGroup='numeric';	//группировка классов\групп по числовому признаку, например для классов в школе
$topGroup='literal';	//группировка классов\групп по символному признаку, например для групп в ВУЗах			

/**
 * Переписано для использования новой системы глобальных настроек
 */
$def_settings["year_name"] = CUtils::getCurrentYear()->name;
$def_settings["date_start"] = CUtils::getCurrentYear()->date_start;
$def_settings["date_end"] = CUtils::getCurrentYear()->date_end;
$def_settings["year_id"] = CUtils::getCurrentYear()->getId();
$def_settings["part_id"] = CUtils::getCurrentYearPart()->getId();
$def_settings["part_name"] = CUtils::getCurrentYearPart()->name;

//echo $def_settings['year_id'];
if (!isset($_GET['onget']))  {//идем на список преподавателей сразу
  $wap='';
  if (isset($_GET['wap'])) {$wap='wap&';}
  
  header('Location:?'.$wap.'onget=1&getallsub=1');exit();}

include 'header.php';


if ($topGroup=='numeric') {	//для  числового представления
	$firstLet=array(1,2,3,4,5,6,7,8,9,10,11); }
else	{	//для символьного представления
	$firstLet=array ("А","Б","В","Г","Д","Е","Ё","Ж","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х","Ц","Ч","Ш","Щ","Э","Ю","Я"); }


$lectors4page=20;

function letters()
 {global $firstLet,$def_settings,$topGroup;
  $letterId=-1;
  if (isset($_GET['getsub']) && intval($_GET['getsub'])>=0) {$letterId=intval($_GET['getsub']);}
  echo '<div class="middle_library" style="font-size:18pt;">';


if ($topGroup=='numeric') {//для  числового представления
   $query_letter_rus='select cast(UPPER(left(sg.name,2))as signed) as name, COUNT(*) AS cnt
					from study_groups sg
					where year_id='.$def_settings['year_id'].'
					group by 1
					order by 1';
} 
 else   {// для символьного представления
  $query_letter_rus='select UPPER(left(sg.name,1))as name, COUNT(*) AS cnt
					from study_groups sg
					where year_id='.$def_settings['year_id'].'
					group by 1
					order by 1';}
							 
  //echo $query_letter_rus;
  $res_rus=mysql_query($query_letter_rus);
  if (mysql_num_rows($res_rus)>0) {
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


if (!isset($_GET['wap'])) { if (!isset($_GET['id']))	{echo $head;} }
else { echo $head_wap;}
?>
<?php
//-------------------------------------------------------------------
echo '<div class="main">'.$pg_title.'<span class=text> выберите первую букву названия </span>';
if (isset($_SESSION['auth']) && $_SESSION['userType']=='администратор') {echo '<a href="studygr_view.php" class=text title="'.$_SESSION['name'].'"> добавить  элемент</a>';}
echo '</div><br>';
    letters();


//include "header.php";
//include "sql_connect.php";


//-----------------------------------------------------значения по умолчанию для опр. года и семестра в расписании
			
//-----------------------------------------------------				
				
  if (isset($_GET['getsub']))	//вывод с фильтром по начальной букве в Фамилии
   {
    
    $letter=$firstLet[$_GET['getsub']];
    $query='select id,name,comment,head_student_id from study_groups where year_id='.$def_settings['year_id'].' order by name';
    
//    echo $query;
    $res0=mysql_query ($query);
    
    
    $b2=0;
    while($a=mysql_fetch_array($res0))
     {
	if ($topGroup=='numeric') $b=intval($a['name']);
	else $b=substr($a['name'],0,1);
      if ($b==$letter)
       {
           $comment=getScalarVal('select comment from study_groups where id="'.$a['id'].'"');
        $p111c=mysql_num_rows($res0aaa);
        $b2++;
        echo '<p class="lecturers"><a href="?onget=1&group_id='.$a['id'].'">'.$a['name'];
        if (trim($comment)!='')
         {
          echo '(+)</a>';
         }
        else
         {
          echo '(-)</a>';
         }
	  $query='SELECT COUNT(*) from students where group_id="'.$a['id'].'"';
	  $res06aaa=mysql_query ($query);
      $dipl_cnt=mysql_result($res06aaa,0);
      if ($dipl_cnt>0) {echo ' &nbsp; <span class=text style="color:#CCCCCC;">студентов('.($hide_person_data_rule?$hide_person_data_text:$dipl_cnt).')</span>';}

      $query='select count(id) from time where grup="'.$a['id'].'" and
				time.year="'.$def_settings['year_id'].'" and time.month="'.$def_settings['part_id'].'"';
	  $res06aaa=mysql_query ($query);
      $dipl_cnt=mysql_result($res06aaa,0);
      if ($dipl_cnt>0) {echo ' <a class=text href="p_time_table.php?onget=1&idlect='.$a['id'].'&gr_mode=1">расписание</a>';}

       }

     }
    if ($b2==0)
     {
      echo '<p class="lecturers">В данном разделе записей нет.';
     }
    echo '<p class="text" valign="bottom">описание:(+) есть, (-) нет';
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

    $res05=mysql_query ('select count(if(year_id='.$def_settings['year_id'].',1,NULL)) from study_groups');
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
    
    $query='select * from study_groups where year_id='.$def_settings['year_id'].' ';
    if ($topGroup=='numeric') {$query.= ' order by cast(UPPER(left(name,2))as signed) asc';}
    else $query.=' order by name asc';
    $res06=mysql_query ($query.' limit '.$start.','.$length);
	
	
	echo '<div align=center>';
	echo getPagenumList($pages,$number,3,'onget=1&getallsub=1&number','','');
	echo '</div>';
	
	//детализация по записи
    while($p=mysql_fetch_array($res06))
     {
//-------------------------------------------------
      $add_msg='';
	  $query='SELECT COUNT(*) from students where group_id="'.$p['id'].'"';
	  $res06aaa=mysql_query ($query);
      $dipl_cnt=mysql_result($res06aaa,0); 
      if ($dipl_cnt>0) {$add_msg='студентов('.($hide_person_data_rule?$hide_person_data_text:$dipl_cnt).')';}
      
      $query='select count(id) from time where grup="'.$p['id'].'" and
				time.year="'.$def_settings['year_id'].'" and time.month="'.$def_settings['part_id'].'"';
	  $res06aaa=mysql_query ($query);
      $dipl_cnt=mysql_result($res06aaa,0); 
      if ($dipl_cnt>0) {$add_msg=$add_msg.' <a href="p_time_table.php?onget=1&idlect='.$p['id'].'&gr_mode=1">расписание</a>';}

//-------------------------------------------------      
      
	  echo '<p class="lecturers">
      <a href="?onget=1&group_id='.$p['id'].'">'.$p['name'].' ';
      if ($p['comment']!='')
       {
        echo '(+)</a>';
       }
      else
       {
        echo '(-)</a>';
       }
       echo ' <span class=text style="color:#CCCCCC;">'.$add_msg.'</span>';
     }
    echo '<p class="text" valign="bottom">описание: (+) есть, (-) нет';
    echo '<p align=center>';
	echo getPagenumList($pages,$number,3,'onget=1&getallsub=1&number','','');
	
   }
  if ($group_id>0)	//вывод конкретной единицы по идентификатору
   {
    $res00aaa=mysql_query ('select comment,name,photogallery_link from study_groups where id="'.$group_id.'"');
    $g1=mysql_fetch_array($res00aaa);

    $photo=$g1['photogallery_link'];
	$comment=$g1['comment'];
	echo '<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF"><tr><td>';
	echo '<div class="middle">'.$g1['name'].'</div><br><br>';

	if(trim($comment)!='')
     {
      
        echo '<div class="text">';
//----------------------------
	$b=$comment;
	
	 $chars4read=600;
	  
	 $details='';$b_='';
	 if (strlen($b)>$chars4read)
	 {
		  $firstPos=strpos($b,"\n",$chars4read-100);	//первая  часть биографии
		  if ($firstPos<=0) {$firstPos=strpos($b," ",$chars4read-20);}
		  
		  $b=substr($b,0,$firstPos);

      $b_=substr($comment,$firstPos);	//остаток текста биографии
      $b_= '<div class=text style="display:none" name="end_biog" id="end_biog">'.$b_.'</div>';
	  $details='<div class="menu" align="right" name="hide_show_biog" id="hide_show_biog"><a href=#view 
	  		onclick=\'javascript:hide_show("end_biog");changeTextHide("hide_show_msg");\'><span id="hide_show_msg">подробнее...</span></a></div>';
	  } 
       $str_biog=$b.'</div>'.$b_;
	  
	  $str_biog=msg_replace($str_biog);
	  $str_biog=str_replace("\n","<br>",$str_biog);
	  
      echo $str_biog.$details;

//---------------------------
     }
    else
     {
      echo '<p class="lecturers">Описание не указано.';
     }
     
     $res=mysql_query ('SELECT id,fio FROM    students
				 WHERE group_id ="'.$group_id.'" order by fio');
     echo "<p><div class=title> Студенты: (".($hide_person_data_rule?$hide_person_data_text:mysql_num_rows($res)).")</div>\n";
	        if (mysql_num_rows($res)<1) {echo '<div class=text>&nbsp;&nbsp;-записей нет</div>'."\n";}
	  echo "<ul class=text>";
			if (!$hide_person_data_rule)
			while ($a=mysql_fetch_array($res))
				{echo "<li>".$a['fio']."</li>\n";}
	  echo "</ul>\n";
		//-------------------------------------------------------------------------
		echo "<p><div class=title> Расписание занятий: </div>";
	
	        $res=mysql_query ('select id from time where grup="'.$group_id.'" and
				time.year="'.$def_settings['year_id'].'" and time.month="'.$def_settings['part_id'].'" limit 0,1');
	        if (mysql_num_rows($res)<1) {echo '<div class=text>&nbsp;&nbsp;-расписания на портале нет</div>';}
			echo '<ul class=text>';
			while ($a=mysql_fetch_array($res)) 
				{echo "<li><a  href='p_time_table.php?onget=1&idlect=$group_id&gr_mode=1'> расписание занятий </a></li>";}
			echo '</ul>';		
		//-------------------------------------------------------------------------
		echo "<p><div class=title> Куратор: ".($hide_person_data_rule?$hide_person_data_text:"")."</div>";
		if (!$hide_person_data_rule) {
	        $res=mysql_query ('select k.fio_short,u.id as user_id
        from study_groups sg
            left join kadri k on sg.curator_id=k.id
            left join users u on u.kadri_id=k.id
            where sg.id="'.$group_id.'" limit 0,1');
	       
            if (mysql_num_rows($res)<1) {echo '<div class=text>&nbsp;&nbsp;-записей на портале нет</div>';}
			echo '<ul class=text>';
			while ($a=mysql_fetch_array($res))
				{
                    echo "<li>".
                    echoIf($a['user_id']!='', '<a href="_modules/_lecturers/index.php?action=view&id='.$a['user_id'].'">'.$a['fio_short'].'</a>', $a['fio_short'])."</li>";}
			echo '</ul>';
		}
	 if ($photo!="" && $showGrPhoto)
         {
          echo "<p><div class=title> Фотогалерея группы: </div>
		<ul class=text>
		<li> <a href=\"$photo\" title=\"перейти к просмотру фотографий\">просмотреть</a></li>
		</ul>";
        }
     echo '</td></tr></table>';

   }
   
 if (!isset($_GET['wap'])) {
  echo $end1;
  include "display_voting.php";
  
}
define("CORRECT_FOOTER", true);
echo $end2; include('footer.php'); 
?>