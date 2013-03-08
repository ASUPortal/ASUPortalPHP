<?php
$files_path='../../';

include ($files_path.'authorisation.php');

//$head_title='Предзащита дипл.проекта- отчет по рецензентам';

include $files_path."header.php";
echo $head1;

$comm_id=0;	//отбор по комиссии предзаписи	
$sort=4;
$stype='asc';
$q='';
$filt_str_display='';

if (isset($_GET['comm_id'])) 	{$comm_id=intval($_GET['comm_id']);$filt_str_display.=' комиссии;';}
if (isset($_GET['stype']) && ($_GET['stype']=='desc' || $_GET['stype']=='asc')) {$stype=$_GET['stype'];}
if (isset($_GET['sort'])) 	{$sort=intval($_GET['sort']);}
if (isset($_GET['q']) && trim($_GET['q'])!='') {$q=f_ri($_GET['q']);$filt_str_display.=' поиску;';}


?>
<style type="text/css">
    body {background-color:#ffffff;}
</style>
    
<p class=main><?php echo $head_title ?></p>
<?php

$query="SELECT concat(s.fio,' - ',sg.name) as stud_name,
       d.dipl_name,
       dp.diplom_percent,
       dp.another_view,
       concat(IFNULL(k_rez.fio_short,''),IF(IFNULL(d.recenz,'')!='',concat(' - ',substring(d.recenz,1,30),'...'),'')) AS rec_fio,
       dp.date_preview, ".      
       ($comm_id==0?"concat(dpc.name,' (',k_secr.fio_short,')') AS comm_name,":"").
       "dp.comment,
       dp.id,
       dpc.id as comm_id,
       d.id as dipl_id 
  FROM    diplom_previews dp 
          LEFT OUTER JOIN diplom_preview_committees dpc ON (dpc.id = dp.comm_id)
          LEFT OUTER JOIN students s ON (s.id = dp.student_id) 
	  LEFT OUTER JOIN study_groups sg ON (sg.id = s.group_id) 
          LEFT OUTER JOIN kadri k_secr ON (k_secr.id = dpc.secretary_id) 
	  LEFT OUTER JOIN diploms d ON (dp.student_id = d.student_id) 
	  LEFT OUTER JOIN kadri k_rez ON (k_rez.id = d.recenz_id)";		
		
$search_query='';
if ($q!='') {
$search_query=' and (
				        convert(d.dipl_name USING utf8) like "%'.$q.'%" or 
					convert(k_rez.fio_short USING utf8) like "%'.$q.'%" or 
					convert(k_secr.fio_short USING utf8) like "%'.$q.'%" or '.
					($comm_id==0?'convert(dpc.name USING utf8) like "%'.$q.'%"
					 or convert(k_secr.fio_short USING utf8) like "%'.$q.'%" or ':' ').					
				       'convert(s.fio USING utf8) like "%'.$q.'%" or
				        convert(sg.name USING utf8) like "%'.$q.'%" or
					dp.date_preview like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%" or
					convert(dp.comment USING utf8) like "%'.$q.'%"
		    )';}

if ($comm_id>0) 
	{$search_query.=' and dpc.id="'.$comm_id.'"';}

$query=$query." where 1 ".$search_query." order by 5, ".$sort.' '.$stype.'';	//доп.сортировка";
$res=mysql_query($query);

$flag1=false;$flag2=false;
$i=0;$comm_cnt=0;
$elemCnt=mysql_num_rows($res);
$res_pp=mysql_fetch_assoc($res);

if ($elemCnt<=0) die('записей нет');
else {
    
    if ($filt_str_display!='')
        {echo '<p class=text >включена фильтрация по:
            <b style="color:#FF0000;">'.$filt_str_display.'</b> </p>';}

    
    while ($i<$elemCnt)
    {
   	     $paramVal1=$res_pp['rec_fio'];
             
 		 //1-уровня группировки
		 echo '<div><b>Рецензент:</b> '.(trim($res_pp['rec_fio'])==''?'не указан ':$res_pp['rec_fio']).'<div>';
		   
                   echo '<table border=1 class=text cellpadding=2 cellspacing=0>
                    <tr class=title>
                        <td width="37" valign="top">№</td>
                        <td width="250" valign="top">Студент (группа)</td>                                            
                    </tr>                   
                   ';
                   $k=1;
		   while ($paramVal1==$res_pp['rec_fio'] && $i<$elemCnt)
                    {
                    //вывод ФИО студентов
                    
                       echo '
                        <tr>
                            <td >'.($k).'</td>
                            <td >'.$res_pp['stud_name'].'</td>                                                   
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
<div><b>Число рецензентов в отчете:</b> <?php echo $comm_cnt; ?></div>
<div><b>Число студентов в отчете:</b> <?php echo $elemCnt; ?></div>

<?php if (!isset($_GET['save'])) {?>
<p class=notinfo>
<div class=notinfo style="border: dotted green 3px; padding: 2px;">Примечание:
<ul>
    <li>можно использовать <strong>фильтр</strong> по комиссии и\или поисковому выражению, выбрав его в исходной форме, из которой вызывается печать отчета</li>    
    <li><strong>сортировка </strong>наследуется из исходной формы, из которой вызывается печать отчета</li>
</ul>

</div>

<input class=notinfo type=button onclick="window.close();" value="Закрыть окно">
</p>
<?php }?>
</body>
</html>