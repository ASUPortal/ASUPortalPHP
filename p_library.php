<?php
include 'sql_connect.php';

$pg_title="������� ���������";

$getsubj=0;	//��������� ������� ������� ����������
if (isset($_GET['getsubj']) && intval($_GET['getsubj'])>0) { $getsubj=intval($_GET['getsubj']); }

$getdir=0;	// ����� � �������� ����������� ���������� �������������
if (isset($_GET['getdir']) && intval($_GET['getdir'])>0) { $getdir=intval($_GET['getdir']); }

$fiosubj='';

if ($getdir>0)
{
  $vasubj=getRowSqlVar('SELECT subjects.name as subj_name, users.FIO as user_name
      FROM    ( documents documents INNER JOIN subjects subjects ON (documents.subj_id = subjects.id))
	   INNER JOIN users users ON (users.id = documents.user_id)
     WHERE documents.nameFolder = "'.$getdir.'"');  
  
  if (!$hide_person_data_rule && $vasubj[0]['user_name']!='') $head_title=$vasubj[0]['user_name'].'. '.$head_title;
  if ($vasubj[0]['subj_name']!='') $head_title=$vasubj[0]['subj_name'].'. '.$head_title;  
}
else
 if ($getsubj>0) {
   $fiosubj=getScalarVal('select name from subjects where id="'.$getsubj.'"');  
   if ($fiosubj!='') $head_title=$fiosubj.'. '.$head_title;
 }

$firstLet=array ("�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�",
"�","�","�","�","�","�","�","�","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R",
"S","T","U","V","W","X","Y","W","Z");

function letters()
 {global $firstLet;
  
  $letterId=-1;
  if (isset($_GET['getsub']) && intval($_GET['getsub'])>=0) {$letterId=intval($_GET['getsub']);}
  
  echo '<p class=middle_library style="font-size:18pt;"> <span class=text>�������� � �������� ���������� �������: </span>';
  //��� ������� ������ ���� ����� �������� � ����� ������ � ���
  $query_letter_rus='SELECT UPPER(left(s.name,1)) as name, COUNT(f.id_file) AS file_cnt
	  FROM    (   documents d
	           INNER JOIN
	              files f
	           ON (d.nameFolder = f.nameFolder))
	       INNER JOIN
	          subjects s
	       ON (s.id = d.subj_id)
	where UPPER(left(s.name,1))>="�" && UPPER(left(s.name,1))<="�"
	GROUP BY 1
	ORDER BY 1 ASC';
  $res_rus=mysql_query($query_letter_rus);
  if (mysql_num_rows($res_rus)>0)	
  {
   while ($a_rus=mysql_fetch_assoc($res_rus))
   {
	if ($firstLet[$letterId]==$a_rus['name']) {echo '<font size=+3>'.$a_rus['name'].'<sub class=fLetter_cnt>'.$a_rus['file_cnt'].'</sub></font>&nbsp;';}
	else {echo '<a href="?onget=1&getsub='.array_search($a_rus['name'],$firstLet).'" title="� ��������� ������: '.$a_rus['file_cnt'].'">'.$a_rus['name'].'<sub class=fLetter_cnt>'.$a_rus['file_cnt'].'</sub></a>&nbsp;';}
	}
  }
  else {echo '<div class=text> <b>������ �� �������.</b></p>';}
  echo '<p><span class=text>�������� � ������������� ���������� �������: </span> ';
  $query_letter_rus='SELECT UPPER(left(s.name,1)) as name, COUNT(f.id_file) AS file_cnt
	  FROM    (   documents d
	           INNER JOIN
	              files f
	           ON (d.nameFolder = f.nameFolder))
	       INNER JOIN
	          subjects s
	       ON (s.id = d.subj_id)
	where UPPER(left(s.name,1))>="A" && UPPER(left(s.name,1))<="Z"
	GROUP BY 1
	ORDER BY 1 ASC';
  $res_rus=mysql_query($query_letter_rus);
  if (mysql_num_rows($res_rus)>0)	
  {
   while ($a_rus=mysql_fetch_assoc($res_rus))
   {
	if ($firstLet[$letterId]==$a_rus['name']) {echo '<font size=+2>'.$a_rus['name'].'<sub>'.$a_rus['file_cnt'].'</sub></font>&nbsp;';}
	else {echo '<a href="?onget=1&getsub='.array_search($a_rus['name'],$firstLet).'">'.$a_rus['name'].'<sub>'.$a_rus['file_cnt'].'</sub></a>&nbsp;';}
	}
  }
  else {echo '<span class=text> <b>������ �� �������.</b></span></p>';}
  echo '<a class=main href="?onget=1&getallsub=1">���</a>';
  
  ?>
  <div class=text><a href="#show" onclick="hide_show('nFiles');">��������� ����� � ������� "�����" (�� 7 ����)  ��������/������</a></div>
  <?php

$time_in_days=7;

$query='select f.nameFile,f.browserFile,f.entry,f.date_time,subjects.name as subj_name,documents.nameFolder,users.fio,users.id as user_id from files f 
 	inner join documents on documents.nameFolder=f.nameFolder 
 	inner join subjects on documents.subj_id=subjects.id 
 	inner join users on users.id=documents.user_id 
 	where f.`date_time` >= "'.date("Y-m-d",mktime (0,0,0,date("m"),(date("d")-$time_in_days),date("Y"))).'" order by f.date_time desc';
 

  $res=mysql_query ($query);
  if (mysql_num_rows($res)>0) { ?>

	<div id=nFiles class=text style="border-style:solid;border-width:1px;">
  	<table class=text border=0 cellspacing=5>
  <?php
  while ($a=mysql_fetch_array($res))
  {
   	echo '<tr align=left>
	   <td>'.substr(DateTimeCustomConvert($a['date_time'],'dt','mysql2rus'),0,10).'</td>
	   <td><a href="?onget=1&getdirect='.$a['nameFolder'].'&getfile='.$a['nameFile'].'">';
	echo file_type_img('library/'.$a['nameFolder'].'/'.$a['nameFile']);   
	echo '<b>'.$a['browserFile'].'</b> ('.$a['entry'].') </a></td>
	   <td><a href="?onget=1&getdir='.$a['nameFolder'].'">'.$a['subj_name'].'</a></td>
	   <td>'.($hide_person_data_rule?$hide_person_data_text:'<a href="p_lecturers.php?onget=1&idlect='.$a['user_id'].'">'.$a['fio'].'</a>').'</td>
	</tr>';  	
   
   }
   echo '</table></div>';
   }
   else {echo '<div class=warning>����� ������ �� ����������</div>';}  
 }
 
if (!isset($_GET['onget']))
 {
  $wap='';
  if (isset($_GET['wap'])) {$wap='wap&';}
  header("Location:?".$wap."onget=1&getallsub=1");
 
 }

//������ ����� � ��������� ������� �� ������
 if (isset($_GET['getdirect']) )
   {    
      $res04=mysql_query ('update files set entry=entry+1 where nameFolder="'.f_ri($_GET['getdirect']).'"
      and nameFile="'.f_ri($_GET['getfile']).'"');
      //mysql_close();
      header('Location:library/'.($_GET['getdirect']).'/'.($_GET['getfile']) );
     
   }
include 'header.php';

//include 'sql_connect.php';

if (!isset($_GET['wap'])) {	echo $head;}
else { echo $head_wap;}

//----------------------------------------------------------------------------------------------
?>

<?php
    echo '<div class="main">'.$pg_title;
if (isset($_SESSION['auth']) && $_SESSION['userType']=='�������������') {echo '<a href="lect_library.php" class=text title="'.$_SESSION['FIO'].'"> ��������  ���� �������\����</a>';}
echo '</div>
	<div class=text style="text-align:center;"> ����� ������ ����� ����������� WinRar, ��������� ��������� �� ������ <a href="apps/wrar34b5ru.exe"> <u>�����</u> </a><p>';
    letters();
  if ($getdir>0)	//����� ������� ������������� �� ��������
   {   

    $res02=mysql_query ('select * from files where nameFolder="'.$getdir.'" order by browserFile');

    $res02a=mysql_query ('select subjects.name,documents.nameFolder,documents.user_id, subjects.id as subj_id   
		from subjects inner join documents on documents.subj_id=subjects.id
		where documents.nameFolder="'.$getdir.'"');
    $dd=mysql_fetch_array($res02a);
    $res02b=mysql_query ('select id,FIO from users where id="'.$dd['user_id'].'"');
    $ddd=mysql_fetch_array($res02b);
    echo '<div class="middle"><a href=p_library.php?onget=1&getsubj='.$dd['subj_id'].'">'.$dd['name'].'</a></div><div class="text_library" >
		'.($hide_person_data_rule?$hide_person_data_text:'<a href="p_lecturers.php?onget=1&idlect='.$ddd['id'].'" title="��������� � �������������" style="text-decoration:none;">'.$ddd['FIO'].'</a>').'</div>';
    if(mysql_num_rows($res02)==0)
     {
      echo '<br><div class="middle_lite_library">� ������ �������� ���������� ���.</div>';
     }
    else
     {
      while($d=mysql_fetch_array($res02))
       {
        echo '<br><div class="middle_lite_library">
        <a href="?onget=1&getdirect='.$getdir.'&getfile='.$d['nameFile'].'"
        title="��������� '.DateTimeCustomConvert($d['date_time'],'dt','mysql2rus').'">';
		  
		file_type_img($d['nameFile']);
		
		  echo' &nbsp; '.$d['browserFile'].'</a>';
		  print_file_size('library/'.$getdir.'/'.$d['nameFile']);
		  echo '</div>';
        if (trim($d['add_link'])!='') {
		 	$add_link_array = explode("\n", $d['add_link']);
			 //$d['add_link']=str_replace("\n","<br>",$d['add_link']);
			 echo '<div class="text">������������� ������: <img src="images/pc.gif" border=0><br>';
			 for ($i=0;$i<count($add_link_array);$i++) 
			 	{
				  if (strstr($add_link_array[$i],'http://') || strstr($add_link_array[$i],'www.') || strstr($add_link_array[$i],'ftp://'))
				  {if (strstr($add_link_array[$i],'www.') && !strstr($add_link_array[$i],'http://') && !strstr($add_link_array[$i],'ftp://')) 
				  	{$add_link_array[$i]='http://'.$add_link_array[$i];}
				   echo '- &nbsp; <a href="'.$add_link_array[$i].'" title="������� �� ������" target="_blank">'.$add_link_array[$i].'</a><br>';}
				  else {echo '<b>'.$add_link_array[$i].'</b><br>';}
				  }
		echo '</div>';
		}
		echo '<div class="text">�������:&nbsp;'.$d['entry'].'&nbsp;���(�)</div>';
       }
     }
   }

  if (isset($_GET['getallsub']) || $getsubj>0 || isset($_GET['getsub']))
   {
    if(!isset($_GET['number']))
     {
      $number=1; $start=0;
     }
    else
     {
      $number=$_GET['number'];$start=$number*10-10;
     }
	//$query_list=
    $filter='';
    if (isset($_GET['getsub']) && intval($_GET['getsub'])>=0) {	//������ �� ������ ����� ����� ��������
	 $getsub=intval($_GET['getsub']);
	 $filter=' and s.name like "'.$firstLet[$getsub].'%"';}
	else if ($getsubj>0) 	//������ �� ��������
		{
		 $getsubj=$getsubj;
		 $filter=' and s.id='.$getsubj.'';}				
    
	$res05=mysql_query ('select count(distinct s.id) from subjects s 
	 	inner join documents d on d.subj_id=s.id 
		inner join files f on d.nameFolder=f.nameFolder where 1 '.$filter);
    
	$count=mysql_result($res05,0,0);
    $length=10;	//����� ��������� �� ��������
	$pages=$count/$length;
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
      $number=$pages;$start=$number*$length-$length;
     }
    $finish=$start+$length;
    if($finish>$count)
     {
      $length=$count-$start;
     }
    //������ ������ ������ �� ������� ��������-------------------------------------------------------------------------------
	 $res05=mysql_query ('select distinct s.id,s.name from subjects s 
	 	inner join documents d on d.subj_id=s.id 
		inner join files f on d.nameFolder=f.nameFolder where 1 '.$filter.' 
		order by s.name limit '.$start.','.$length);
	
?>
  <div class="pages" style="text-align:center;">
  	<?php 
	  $href="?onget=1&getallsub=1&number=";
	  //printPGnums($pages,$number,$href);
	  echo getPagenumList($pages,$number,3,'onget=1&getallsub=1&number','','');
	  ?> 
  </div>
<?php    
while($p_=mysql_fetch_array($res05))
 {
	echo '<br><div class="middle_lite_library" style="font-weight:bold;"><a href="?onget=1&getsubj='.$p_['id'].'">'.$p_['name'].'</a></div>';
	
	$res06=mysql_query ('select d.nameFolder,d.user_id,u.fio,count(f.id_file) as f_cnt  
		from documents d inner join files f on d.nameFolder=f.nameFolder inner join users u on u.id=d.user_id 		
		where d.subj_id='.$p_['id'].'
		group by d.nameFolder,d.user_id,u.fio order by u.fio');
	while($p=mysql_fetch_array($res06))
     {
		  echo '<div class="text" style="padding-left:40px;">
		  �������������:&nbsp;
		  '.($hide_person_data_rule?$hide_person_data_text:'<a href="p_lecturers.php?onget=1&idlect='.$p['user_id'].'" title="��������� � �������������">'.$p['fio'].'</a>').', &nbsp;
	      <a href="?onget=1&getdir='.$p['nameFolder'].'">������� ���������� ������������� ('.$p['f_cnt'].')</a></div>';
	      echo '';
		
     } 
}   
?>
  <div class="pages" style="text-align:center;">
  	<?php 
	  $href='?onget=1&getallsub=1&number=';
	  //printPGnums($pages,$number,$href);
	  echo getPagenumList($pages,$number,3,'onget=1&getallsub=1&number','','');
	?> 
  </div>
	
	<p class="pages" valign="bottom">�������� ������ �������� � ������������� �����������<br>� ������� ������� ����� ���������� �� ������� �������� 
	<div class=text><b>����������:</b> <br>�� ������� ������� ������� ��� <u>������-������������ ����������</u> �� �������� ��� ��������. <br>
  ��� ������� ���������� � ��� �� ��������� ���� ����� �� ������ <a href="http://10.61.2.63"><u>http://10.61.2.63</u></a>.<br>
  ���� ������� ���������� ������ <a href="mailto:smart_newline@mail.ru"> <u>�������������� �������</u>. </div>     

<?php  
   }
if (!isset($_GET['wap'])) {
  echo $end1;
  include "display_voting.php";		  
}
define("CORRECT_FOOTER", true);
echo $end2; include('footer.php');  
?>