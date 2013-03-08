<?php
$files_path='../../';

include ($files_path.'authorisation.php');

//$head_title='Предзащита дипл.проекта- отчет';

include $files_path."header.php";
echo $head1;

$comm_id=0;	//отбор по комиссии предзаписи	
$sort=4;
$stype='asc';

if (isset($_GET['comm_id'])) 	{$comm_id=intval($_GET['comm_id']);}
if (isset($_GET['stype']) && ($_GET['stype']=='desc' || $_GET['stype']=='asc')) {$stype=$_GET['stype'];}
if (isset($_GET['sort'])) 	{$sort=intval($_GET['sort']);}

switch ($sort) {
case 1:
    $sort=1;	//студент
    break;
case 6:
    $sort=3;	//дата предзащиты
    break;
default:
    $sort=1;	//студент
    break;
}
?>
<style type="text/css">
    body {background-color:#ffffff;}
</style>
    
<p class=main><?php echo $head_title ?></p>
<?php

$query="SELECT s.fio as stud_fio,
       sg.name as stud_group,
       dp.date_preview, 
       k_secr.fio_short as comm_secr,
       dpc.name as comm_name,
       kadri_fio_list(dpc.id,',') as comm_kadri,
       dp.id,
       dpc.id as comm_id       
  FROM    diplom_previews dp 
          LEFT OUTER JOIN diplom_preview_committees dpc ON (dpc.id = dp.comm_id)
          LEFT OUTER JOIN students s ON (s.id = dp.student_id) 
	  LEFT OUTER JOIN study_groups sg ON (sg.id = s.group_id) 
          LEFT OUTER JOIN kadri k_secr ON (k_secr.id = dpc.secretary_id) ";		
		
$search_query='';
if ($comm_id>0) 
	{$search_query.=' and dpc.id="'.$comm_id.'"';}

$query=$query." where 1 ".$search_query." order by dpc.id, ".$sort.' '.$stype.'';	//доп.сортировка";
$res=mysql_query($query);

$flag1=false;$flag2=false;
$i=0;$comm_cnt=0;
$elemCnt=mysql_num_rows($res);
$res_pp=mysql_fetch_assoc($res);

if ($elemCnt<=0) die('записей нет');
else {
    while ($i<$elemCnt)
    {
   	     $paramVal1=$res_pp['comm_id'];
             
 		 //1-уровня группировки
		 echo '<div><b>Комиссия по предзащите:</b> '.$res_pp['comm_kadri'].'<div>';
                 echo '<div><b>Аудитория:</b> '.$res_pp['comm_name'].'<div>';
                 echo '<div><b>Секретарь:</b> '.$res_pp['comm_secr'].'<div>';
		   
                   echo '<table border=1 class=text cellpadding=2 cellspacing=0>
                    <tr class=title>
                        <td width="37" valign="top">№</td>
                        <td width="250" valign="top">Студент</td>
                        <td width="100" valign="top">Группа</td>
                        <td width="100" valign="top">Дата</td>
                    </tr>                   
                   ';
                   $k=1;
		   while ($paramVal1==$res_pp['comm_id'] && $i<$elemCnt)
                    {
                    //вывод ФИО студентов
                    
                       echo '
                        <tr>
                            <td >'.($k).'</td>
                            <td >'.$res_pp['stud_fio'].'</td>
                            <td >'.$res_pp['stud_group'].'</td>
                            <td >'.DateTimeCustomConvert(substr($res_pp['date_preview'],0,10),'d','mysql2rus').'</td>
                        </tr>
                        '."\n";
                    $res_pp=mysql_fetch_assoc($res);
                    $i++;$k++;
                    }
		   echo '</table><p>&nbsp;</p>'."\n";
                   $comm_cnt++;
    }
}
?>
<div><b>Число комиссий по предзащите в отчете:</b> <?php echo $comm_cnt; ?></div>
<div><b>Число студентов по предзащите в отчете:</b> <?php echo $elemCnt; ?></div>

<?php if (!isset($_GET['save'])) {?>
<p class=notinfo>
<div class=notinfo style="border: dotted green 3px; padding: 2px;">Примечание:
<ul>
    <li>можно использовать <strong>фильтр</strong> по комиссии, выбрав его в исходной форме, из которой вызывается печать отчета</li>
    <li><strong>сортировка </strong>наследуется из исходной формы, из которой вызывается печать отчета</li>
</ul>

</div>

<input class=notinfo type=button onclick="window.close();" value="Закрыть окно">
</p>
<?php }?>
</body>
</html>