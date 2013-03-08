<?php
include "config.php";

include_once $portal_path.'authorisation.php';

//---------------------

if (isset($_GET['id_kadri']) && intval($_GET['id_kadri'])>0)
{	if ($view_all_mode==false && intval($_GET['id_kadri'])!=$_SESSION['kadri_id']) 
		{header('Location:?q=2&id_kadri='.$_SESSION['kadri_id']);}        
}
else {
    if ($view_all_mode==false && $_SESSION['kadri_id']>0) 
		{header('Location:?q=1&id_kadri='.$_SESSION['kadri_id']);}
    
}
//---------------------
include_once $portal_path.'master_page_short.php';

$comma_cnt=1;   // точность до 2-х знаков

$id_kadri=0;
$id_year=0;
if (isset($_GET['id_kadri']) && intval($_GET['id_kadri'])>0 ) {$id_kadri=intval($_GET['id_kadri']);}
if (isset($_GET['id_year']) && intval($_GET['id_year'])>0 ) {$id_year=intval($_GET['id_year']);}

?>

<LINK REL="STYLESHEET" TYPE="text/css" HREF="indplan.css">
<script type="text/javascript"  src="indplan.js"></script>

<script type="text/javascript" src="<?=$portal_path?>scripts/calendar.js"></script>
<script type="text/javascript" src="<?=$portal_path?>scripts/calendar-setup.js"></script>
<script type="text/javascript" src="<?=$portal_path?>scripts/lang/calendar-ru_win_.js"></script>
<link type="text/css" href="<?=$portal_path;?>css/jquery/redmond/jquery-ui-1.8.18.custom.css" rel="stylesheet" />	
<!--script type="text/javascript" src="<?=$portal_path?>scripts/jquery/jquery-1.7.1.min.js"></script-->
<script type="text/javascript" src="<?=$portal_path?>scripts/jquery/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript">
	$(function(){	
		// Tabs
		$('#tabs').tabs();
		
		//hover states on the static widgets
		$('#dialog_link, ul#icons li').hover(
			function() { $(this).addClass('ui-state-hover'); }, 
			function() { $(this).removeClass('ui-state-hover'); }
		);
		
	});
</script>
<script type="text/javascript">
/* преобразовать дробное число с учетом направления отображения (browse, [mysql, php,...]	)*/
function numLocal(number,direction)
{
	localnum='';
	number=String(number);
	//if (parseFloat(number)==0) number='';	// очистить нулевые значения
	if (number!='' && number!=0)
	{
		if (direction=='browse') localnum=number.replace('.',',');
		else localnum=number.replace(',','.');
	}
	if (localnum=='' && direction!='browse') return 0;
	else return localnum;
}

function calc_sum(item_name,sum_with_correct)	// пересчет итоговой суммы
{
	//var kind1=numLocal($("input[name*='"+item_name+"']").val());
	//alert('item_name='+item_name+',kind1='+kind1);
        
        var m=0;    // месяц
        var item_name_pre='';   // имя переменной без месяца
        var sum=0;  // сумма семестра
        var i=0;
        m=item_name.replace(/.*id(\d+)m(\d+)/i, "$2");
        item_name_pre=item_name.replace(/(.*id\d+)m(\d+)/i, "$1");
        m=parseInt(m);
	
        if (m>0 && item_name_pre!='')
        {
            if (m<=5)   // первый семестр
            {
                for (i=1;i<=5;i++) 
                    sum=sum+parseFloat(numLocal($("input[name*='"+item_name_pre+'m'+i+"']").val()));
                    //alert(item_name_pre+'m'+m+'='+numLocal($("input[name*='"+item_name_pre+'m'+i+"']").val()));
                
                var kind_sum=$("span[name*='"+item_name_pre+"_sum1']");
            }
            else        // второй семестр
            {
                for (i=6;i<=11;i++)
                    sum=sum+parseFloat(numLocal($("input[name*='"+item_name_pre+'m'+i+"']").val()));
                var kind_sum=$("span[name*='"+item_name_pre+"_sum2']");
                
            }
    
            kind_sum.text(numLocal(parseFloat(sum),'browse'));
	    // подсветка итоговой суммы с учетом сравнения с факта и плана
	    if (parseFloat(sum)!=parseFloat(sum_with_correct))
		$("#label_rab_"+item_name_pre+"").removeClass("success").addClass("warning");
	    else $("#label_rab_"+item_name_pre+"").removeClass("warning").addClass("success");
        }
        else alert('Ошибка определния семестра! Автопересчет итога недоступен.');
}
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $files_path;?>css/calendar-win2k-asu.css" title="win2k-cold-1" />
              
<p class="main"><?php echo $head_title;?></p>
              
<?php
include_once 'sql_individual_plan.inc';
function getFactTable($hours_kind_type, $mode='view') // получаем таблицу в разделе учебная нагрузка
{
    global $spravochnik_uch_rab_sel_fact;
    global $id_kadri,$id_year;
    global $comma_cnt;
    
    $sumCol=array();
    for ($i=1;$i<=12;$i++)
                    $sumCol[$i]=0;
    $sumCol['plan1']=0;	// итого план сем1
    $sumCol['plan2']=0;	// итого план сем2
    $sumCol['fact1']=0;	// итого факт сем1
    $sumCol['fact2']=0;	// итого факт сем2
    $sumFactAll=0;		// итого факт

    ?>
    <form method="POST" name="form_hours_kind_type_<?=$hours_kind_type?>" action="?<?=reset_param_name($_SERVER['QUERY_STRING'],'load1')?>" >		
        <table border=1 class=indplan cellpadding=2 cellspacing=0>
		<tr class=indplan>
		 <th rowspan="3">Название работ</td>
		 <th rowspan="3"><font size=-1 TITLE="значения заносятся из нагрузки автоматически" style=cursor:help>План
		 <br>на се-<br>местр</font></td>
	       
		 <th colspan="6" align="center">Осенний</td>
		 <th rowspan="3"><font size=-1 TITLE="значения заносятся из нагрузки автоматически" style=cursor:help>План
		 <br>на се-<br>местр</font></td>
		 <th colspan="7" align="center">Весенний</td>
                 <th rowspan="3" align="center" width=40>По плану</td>
		 <th rowspan="3" align="center">Выполнено</td>
	       </tr>
	       <tr class=indplan>
		<th colspan="6" align="center">Фактически выполнено</td>
		<th colspan="7" align="center">Фактически выполнено</td>
		</tr>
		<tr class=indplan>
		<th>сент</td>
		<th>окт</td>
		<th>нояб</td>
		<th>дек</td>
		<th>янв</td>
		<th>итого</td>
		<th>февр</td>
		<th>март</td>
		<th>апр</td>
		<th>май</td>
		<th>июнь</td>
		<th>июль</td>
		<th>итого</td>
		</tr>
		<?php
		 $i=1;		

        mysql_data_seek($spravochnik_uch_rab_sel_fact,0);        
        while ($name=mysql_fetch_array($spravochnik_uch_rab_sel_fact)) // строки
		{
		$n=$name[0];   //номер учебной работы и 
		   //--------------------------------------План на семестр
		       $q_val='select sum(IF('.$name['name_hours_kind'].','.$name['name_hours_kind'].',0))+
			       sum(IF('.$name['name_hours_kind'].'_add,'.$name['name_hours_kind'].'_add,0))  
		       from hours_kind 
		       where kadri_id="'.$id_kadri.'" 
			   and year_id="'.$id_year.'" 
               and hours_kind_type="'.$hours_kind_type.'" ';
			       //echo '<div>'.$q_val.'<div/>';
		       
		       $resp1['rab_'.$n.'']=getScalarVal($q_val.'and part_id=1');  
		       $resp2['rab_'.$n.'']=getScalarVal($q_val.'and part_id=2');  
		   //--------------------------------------
	       
		       echo'<tr><td>'.$name['name'].'</td>
		       <td align=right>'.numLocal(number_format($resp1['rab_'.$n.''],$comma_cnt)).'</td>'; // получаем план1 из нагрузки
                       $sumCol['plan1']+=$resp1['rab_'.$n.''];
		$summa=0;$i=1;
	       $sumFactRow=0;		// итого факт по строке
	       $res=array();
		   if (isset($_GET['load1']) && $_GET['load1']==1) // загрузка усредненных данных по плану
		   {
		       // округляем в большую сторону для переноса разницы на последней месяц с учетом погрешности округления
		       $res["rab_{$n}_load1_avg"]=round($resp1['rab_'.$n.'']/5,$comma_cnt);
		       $res["rab_{$n}_load1_end"]=$resp1['rab_'.$n.'']-$res["rab_{$n}_load1_avg"]*4;
		   }                   
	       while (($i)!==6)    // обход месяцы 1-6 (сент-янв) - столбцы
	       {
		   if (isset($_GET['load1']) && $_GET['load1']==1) // загрузка усредненных данных по плану
		   {
		       // округляем в большую сторону для переноса разницы на последней месяц с учетом погрешности округления
		       if ($i==5) $res["rab_{$n}"]=$res["rab_{$n}_load1_end"];
		       else $res["rab_{$n}"]=$res["rab_{$n}_load1_avg"];
		       //$res["rab_{$n}"]=numLocal(number_format(ceil($resp1['rab_'.$n.'']/5,$comma_cnt),$comma_cnt));    
		   }
		   else {
		       $sqlm="SELECT `rab_{$n}` FROM fact
			   WHERE id_month=".$i." and
                id_kadri=".$id_kadri." and
                id_year=".$id_year." and
                hours_kind_type=".$hours_kind_type." ";
		       $mysqlm=mysql_query($sqlm);
		       $row=mysql_fetch_assoc($mysqlm);
		       $res["rab_{$n}"]=$row["rab_{$n}"];
		   }
		   echo '<td>';
		   if ($mode=='edit')
            echo '<input style="text-align:right;" type="text" name="id'.$name[0].'m'.$i.'" id="id'.$name[0].'m'.$i.'" 
            value="'.numLocal(number_format($res['rab_'.$n.''],$comma_cnt)).'" size="4" 
            onChange="javascript:calc_sum(\'id'.$name[0].'m'.$i.'\','.$resp1['rab_'.$n.''].');">';
           else
            numLocal(number_format($res['rab_'.$n.''],$comma_cnt));
           
		   echo '</td>';		
                $sumCol[$i]+=$res['rab_'.$n.'']; 
		$summa=$summa+$res['rab_'.$n.''];
                $i++;
	       }
	       
	       echo '
	       <td style="text-align:right;" class="'.($summa!=$resp1['rab_'.$n.'']?'warning':'success').'" id="label_rab_id'.$n.'" name="label_rab_id'.$n.'">
	       <span name="id'.$name[0].'_sum1">'.numLocal(number_format($summa,$comma_cnt)).'</span></td>';
	       $sumCol['fact1']+=$summa;
               
	       echo'<td align=right>'.numLocal(number_format($resp2['rab_'.$n.''],$comma_cnt)).'</td>';    // получаем план2 из нагрузки
	       $sumFactRow+=$summa;
               $sumCol['plan2']+=$resp2['rab_'.$n.''];
	       $summa=0;$res=array();
		   if (isset($_GET['load1']) && $_GET['load1']==1) // загрузка усредненных данных по плану
		   {
		       // округляем в большую сторону для переноса разницы на последней месяц с учетом погрешности округления
		       $res["rab_{$n}_load1_avg"]=round($resp2['rab_'.$n.'']/6,$comma_cnt);
		       $res["rab_{$n}_load1_end"]=$resp2['rab_'.$n.'']-$res["rab_{$n}_load1_avg"]*5;
		   }                   
	       while (($i)!==12)   // обход месяцы 7-12 (фев-июль)
	       {
		   if (isset($_GET['load1']) && $_GET['load1']==1) // загрузка усредненных данных по плану
		   {
		       if ($i==11) $res["rab_{$n}"]=$res["rab_{$n}_load1_end"];
		       else $res["rab_{$n}"]=$res["rab_{$n}_load1_avg"];
		   }
		   else {
		       $sqlm="SELECT `rab_{$n}` FROM fact
			   WHERE id_month=".$i."
                and id_kadri=".$id_kadri." and
                id_year=".$id_year." and 
                hours_kind_type=".$hours_kind_type." ";
		       $mysqlm=mysql_query($sqlm);
		       $row=mysql_fetch_assoc($mysqlm);
		       $res['rab_'.$n.'']=$row['rab_'.$n.''];
		   }
	       echo '<td>';
           if ($mode=='edit')
            echo '<input type="text" style="text-align:right;" name="id'.$name[0].'m'.$i.'" id="id'.$name[0].'m'.$i.'" 
            value="'.numLocal(number_format($res['rab_'.$n.''],$comma_cnt)).'" size="4"
            onChange="javascript:calc_sum(\'id'.$name[0].'m'.$i.'\');" >';
          else
            numLocal(number_format($res['rab_'.$n.''],$comma_cnt));
           
           echo '</td>';		
		$summa=$summa+$res['rab_'.$n.''];
                $sumCol[$i]+=$res['rab_'.$n.''];
                $i++;
	       }
	       echo '
	       <td style="text-align:right;" class="'.($summa!=$resp2['rab_'.$n.'']?'warning':'success').'"><span name="id'.$name[0].'_sum2">'.numLocal(number_format($summa,$comma_cnt)).'</span></td>';
               $sumCol['fact2']+=$summa;
               $sumFactRow+=$summa;
               // столбец всего
              echo '<td align=right>'.numLocal(number_format($resp1['rab_'.$n.'']+$resp2['rab_'.$n.''],$comma_cnt)).'</td>';
	      echo '<td align=right>'.numLocal(number_format($sumFactRow,$comma_cnt)).'</td>';

              $sumFactAll+=$sumFactRow;
              echo '</tr>';
	       
	       }
	// подсчет итогов
	echo '<tr style="font-weight:bold;">
		<td class="title">Итого</td>
		<td align=right>'.numLocal(number_format($sumCol['plan1'],$comma_cnt)).'</td>
		
		<td align=right>'.numLocal(number_format($sumCol[1],$comma_cnt)).'</td>
		<td align=right>'.numLocal(number_format($sumCol[2],$comma_cnt)).'</td>
		<td align=right>'.numLocal(number_format($sumCol[3],$comma_cnt)).'</td>
		<td align=right>'.numLocal(number_format($sumCol[4],$comma_cnt)).'</td>
		<td align=right>'.numLocal(number_format($sumCol[5],$comma_cnt)).'</td>
		<td align=right>'.numLocal(number_format($sumCol['fact1'],$comma_cnt)).'</td>
		
		<td align=right>'.numLocal(number_format($sumCol['plan2'],$comma_cnt)).'</td>
		
		<td align=right>'.numLocal(number_format($sumCol[6],$comma_cnt)).'</td>
		<td align=right>'.numLocal(number_format($sumCol[7],$comma_cnt)).'</td>
		<td align=right>'.numLocal(number_format($sumCol[8],$comma_cnt)).'</td>
		<td align=right>'.numLocal(number_format($sumCol[9],$comma_cnt)).'</td>
		<td align=right>'.numLocal(number_format($sumCol[10],$comma_cnt)).'</td>
		<td align=right>'.numLocal(number_format($sumCol[11],$comma_cnt)).'</td>
		<td align=right>'.numLocal(number_format($sumCol['fact2'],$comma_cnt)).'</td>
		<td align=right>'.numLocal(number_format($sumCol['plan1']+$sumCol['plan2'],$comma_cnt)).'</td>
		<td align=right>'.numLocal(number_format($sumFactAll,$comma_cnt)).'</td>
		</tr>';               
	       // раздел 1
	       $load1=false;
	       if (isset($_GET['load1']) && $_GET['load1']==1) $load1=true;    // флаг загрузки из "учебной нагрузки" в раздел 1
	       ?>
           </table><br>
		   <div class=text> Примечание: <br>
		   <ul>
            <li><span class=warning>Красным</span> цветом в столбце "итого" отмечены несовпадающие с планом фактические значения, <span class=success>зеленым</span> - совпадающие.</li>
           </ul>		   
		   </div>
            <input type="hidden" name="hours_kind_type" value="<?=$hours_kind_type?>">
          <?if ($mode=='edit'):?>
		   <input type="submit" name="main" value="Сохранить"> &nbsp; 
		   <input type="button" value="Загрузить" title="загрузка из нагрузки за выбранный период"
		       onclick="confirm_url(document.location.href='?<?=reset_param_name($_SERVER['QUERY_STRING'],'load1')?>&load1=1');"> &nbsp;
		   <input type="button" value="Вернуть" title="вернуть данные до сохранения"  
		       onclick="document.location.href='?<?=reset_param_name($_SERVER['QUERY_STRING'],'load1')?>';">
          <?endif;?>
	  </form>
  <?
}


//-----------------------------------------------------
$select=mysql_query('SELECT id, fio FROM kadri ORDER BY fio') or die ("Возникла ошибка : ".mysql_error());
$select1=mysql_query('SELECT id, name FROM time_intervals  ORDER BY name desc') or die ("Возникла ошибка : ".mysql_error());
echo '<table><tr><td valign=top><b>Преподаватель: </b>';
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		if ($view_all_mode) {
                    echo '<Select   name="id_kadri" ONCHANGE="top.location.href=this.options[this.selectedIndex].value">
		    <option value="?id_kadri=0&id_year='.$id_year.'">выберите из списка...('.mysql_num_rows($select).')</option>';                
                    while ($fio=mysql_fetch_array($select))
                    {echo'<Option Value="?id_kadri='.$fio['id'].'&id_year='.$id_year.'"  ';
		    if ($id_kadri==$fio['id'])echo'selected'; echo'>'.$fio['fio'].'</Option>';}
                    echo'</Select>';}
                else echo getScalarVal('select FIO from kadri where id='.$id_kadri);
                }
    else {while ($fio=mysql_fetch_array($select)){if ($id_kadri==$fio['id'])echo $fio['fio'];}}
    echo '      </td>
    	   		<td>
                <b>&nbsp;&nbsp;&nbsp; учебный год: </b>';
                if (!isset($_GET['save']) && !isset($_GET['print']))
                {echo '<select name="id_year" ONCHANGE="top.location.href=this.options[this.selectedIndex].value">
                <option value="?id_kadri='.$id_kadri.'&id_year=0">выберите из списка...('.mysql_num_rows($select1).')</option>';
    while ($year=mysql_fetch_array($select1))
    {
        $check_sel=false;
        $fact1=mysql_query('SELECT id_kadri FROM fact
			   WHERE id_year='.$year['id'].''.($id_kadri>0?' and id_kadri='.$id_kadri:''));
        if(mysql_num_rows($fact1)) $check_sel=true;
        else
        {$izmen1=mysql_query('SELECT id_kadri FROM izmen
			     WHERE id_year='.$year['id'].''.($id_kadri>0?' and id_kadri='.$id_kadri:''));
        if(mysql_num_rows($izmen1)) $check_sel=true;
        else
         {$nauch_met_rab1=mysql_query('SELECT id_kadri FROM nauch_met_rab
				      WHERE id_year='.$year['id'].''.($id_kadri>0?' and id_kadri='.$id_kadri:''));
        if(mysql_num_rows($nauch_met_rab1)) $check_sel=true;
        else
          {$perechen_nauch_rab1=mysql_query('SELECT id_kadri FROM perechen_nauch_rab
					    WHERE id_year='.$year['id'].''.($id_kadri>0?' and id_kadri='.$id_kadri:''));
        if(mysql_num_rows($perechen_nauch_rab1)) $check_sel=true;
        else
          {$uch_org_rab1=mysql_query('SELECT id_kadri FROM uch_org_rab
				     WHERE id_year='.$year['id'].''.($id_kadri>0?' and id_kadri='.$id_kadri:''));
        if(mysql_num_rows($uch_org_rab1)) $check_sel=true;
        else
            {$uch_vosp_rab1=mysql_query('SELECT id_kadri FROM uch_vosp_rab
					WHERE id_year='.$year['id'].''.($id_kadri>0?' and id_kadri='.$id_kadri:''));
        if(mysql_num_rows($uch_vosp_rab1)) $check_sel=true;
        else
            {
                $zakl1=mysql_query('SELECT id_kadri FROM zakl
				   WHERE id_year='.$year['id'].''.($id_kadri>0?' and id_kadri='.$id_kadri:''));
                if(mysql_num_rows($zakl1)) $check_sel=true;  
            }
        }
        }
        }
        }
        }
    
        echo'<option Value="?id_kadri='.$id_kadri.'&id_year='.$year['id'].'"  ';
        if ($id_year==$year['id'])echo'selected'; echo'>'.$year['name'].'';
        if($check_sel) echo'&nbsp;(+)'; echo'</Option>';
    }
    	   		echo'</select>';} else {
                            while ($year=mysql_fetch_array($select1))
                            {if ($id_year==$year['id'])echo $year['name'];}
                        }
                        echo '</td>
    	    </tr>
     </table><br>';
if ($id_kadri>0 && $id_year>0)
{
if (!isset($_GET['save']) && !isset($_GET['print']))
{echo '<a class=text href="?'.$_SERVER['QUERY_STRING'].'&save&attach=doc">Выгрузить в MS Word</a><br>';
echo '<a class=text href="?'.$_SERVER['QUERY_STRING'].'&print">Распечатать</a><br><br>';}

//---------------------------------------------Раздел 1--------------------------------------
if (!isset($_GET['save']) && !isset($_GET['print']))
{echo '<a class="notinfo" href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id_razdel1">1. Учебная работа (по типу нагрузки)</a>';}

//------------------Редактирование и просмотр "1. Учебная работа"-------------------------


if (isset($_GET['id_razdel_red']) || isset($_GET['id_razdel1']))
{
    $mode='edit';
    if  (isset($_GET['id_razdel1']))
    {
    // раздел 1. таблица при просмотре
    $mode='view';
	}
    
 //--------------Вставка данных --------------------------
 if (isset($_POST['main']))
 {
 echo "<h3>Данные успешно сохранены.</h3>";

   }
?>
<style>
  div#tabs div  {padding:0; margin: 0;}
</style>
<table>
  <tr><td>
<!-- Tabs start-->

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Основная</a></li>
		<li><a href="#tabs-2">Дополнительная</a></li>		
	</ul>	
    <div id="tabs-1">
    <?
    $hours_kind_type=1; // основная нагрузка
    getFactTable($hours_kind_type, $mode); // формируем таблицу в уч.нагрузке
    ?>
	</div>
	<div id="tabs-2">
    <? $hours_kind_type=2; // дополнительная нагрузка
    getFactTable($hours_kind_type, $mode); // формируем таблицу в уч.нагрузке
    ?>    
    </div>	
</div>
<?
if (!isset($_GET['save']) && !isset($_GET['print']) && $mode=='view')
{echo '<a class="notinfo" href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id_razdel_red">Редактировать учебную работу</a>';}
?>
</td></tr>
</table>
<!-- end tabs -->        
           <?
	       }


 //---------------------------------------------Раздел 2-------------------------
if (!isset($_GET['save']) && !isset($_GET['print']))
{echo '<p><a class="notinfo" href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id_razdel2">2. Учебно - и организационно-методическая работа</a>';}
if  (isset($_GET['id_razdel2']))
{

//---------------------------------------вывод таблицы----------------------------------------
if (!$check)
echo '<h3>Нет данных</h3>';
else
{
echo '<table border=1 class=indplan>
<tr class=indplan><th>№</td><th>Наименование работы</td><th>Планируемое количество</td><th>Срок выполнения</td>
<th>Вид отчётности</td><th>Отметка о выполнении</td><th>Примечание</td><th></td></tr>';
$i=1;
while ($line=mysql_fetch_array($uch_org_rab))
{
$sql2="SELECT name FROM spravochnik_vidov_rabot WHERE id=".$line['id_vidov_rabot']." ";
$mysql2=mysql_query($sql2);
$res1=mysql_fetch_array($mysql2);
if ($line['otm']=='нет')
{
if($line['srok_vipolneniya']<date('Y-m-d'))
echo '<tr bgcolor=red onmouseover=this.style.background="tomato" onmouseout=this.style.background="red" title="Данная работа была просрочена">';
else
echo '<tr>';
}
else
echo '<tr onmouseover=this.style.background="#fffafa" onmouseout=this.style.background="#DFEFFF">';
echo '<td>'.$i.'</td><td>'.$res1[0].'</td><td>'.$line['kol_vo_plan'].'</td><td>'.f_ri(DateTimeCustomConvert($line['srok_vipolneniya'],'d','mysql2rus')).'</td>
<td>'.$line['vid_otch'].'</td><td>'.$line['otm'].'</td><td>'.$line['prim'].'</td>'; if (!isset($_GET['save']) && !isset($_GET['print'])) {echo '<td><a href="javascript:del_confirm_act(\''.f_ro($line['id']).'\',\''.str_replace(" ","_",f_ro($res1[0])).'\');"><img src="'.$files_path.'images/todelete.png" alt="Удалить" title="Удалить"></a><p><a href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id='.$line['id'].'&update"><img src="'.$files_path.'images/toupdate.png" alt="Редактировать" title="Редактировать"></a></td></tr>';}
	$i++;
}
echo '</table>';
}
//------------------------------Проверка на пустые поля при редактировании----------------
if ((isset($_POST['update'])) and !($empt))
 {
 echo'<h3>Данные успешно изменены.</h3>';
  }

    if ($empt)
    {
    echo '<h3>Вы заполнили не все поля</h3>';
     }

//------------------------------Проверка на пустые поля при Добавлении----------------
if ((isset($_POST['add'])) and !($empt_add))
 {
 echo'<h3>Данные успешно добавлены.</h3>';
  }

    if ($empt_add)
    {
    echo '<h3>Вы заполнили не все поля</h3>';
     }
     if($duplicat)
     {
     echo '<h3>Учебно- и организационно-методическая работа с таким названием уже существует,
     проверьте целесообразность введения данных.</h3>';
     }
     if (!isset($_GET['save']) && !isset($_GET['print']))
{echo '<a  class="notinfo" href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&add">Добавить работу</a>';}

if(isset($_GET['delete']))
    	{
    	echo'<h3>Запись успешно удалена.</h3>';
    	}

}
//---------------------------------------------Редактирование ------------------------



if (isset($_GET['update']))
{

echo '<form method=POST action="?id_razdel2&id_kadri='.$id_kadri.'&id_year='.$id_year.'">
<table border=1 class="cent">';
$otmetka="SELECT id,name FROM otmetka";
$otm_sel=mysql_query($otmetka);
$spravochnik="SELECT id,name,time_norm FROM spravochnik_vidov_rabot WHERE id_razdel=2 ORDER BY name ";
$spr_vid=mysql_query($spravochnik);
echo '<input type="hidden" name="a" value="'.$line['id'].'"><tr><td colspan=2><table border=1 class=indplan><caption><h4>Выберите работу</h4></caption><tr><th>Список учебно- и организационно-методических работ</td><th>Нормы времени в часах </td><th></td></tr>';
 while($name=mysql_fetch_array($spr_vid))
 {
 echo '<tr><td>'.$name['name'].'</td><td>'.$name['time_norm'].'</td><td><input type=radio name="delete_rab" value="'.$name['id'].'" ';
  if ($name['id']==$line['id_vidov_rabot']) echo 'checked';
  echo'></td></tr>';
 }
 echo '</table></td></tr>';
 echo '
<tr><td>Планируемое количество</td></tr><tr><td><input type="text" name="plan" value="'.$line['kol_vo_plan'].'" size="15"></td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td>Введите срок выполнения</td></tr><tr title="Введите дату в формате дд.мм.гггг"><td><input type="text" id=date_act value="'.f_ri(DateTimeCustomConvert($line['srok_vipolneniya'],'d','mysql2rus')).'" name="srok">
<button  type="reset" id="f_trigger_date_act">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act",
        ifFormat       :    "%d.%m.%Y",
        showsTime      :    false,
        button         :    "f_trigger_date_act",
        singleClick    :    true,
        step           :    1
    });
	</script>
</td></tr>
<tr><td>Введите вид отчётности</td></tr><tr><td><input type="text" name="otch" value="'.$line['vid_otch'].'" size="30"></td></tr>
<tr><td>Введите отметку о выполнении работы</td></tr><tr><td><select name="danet">';
while($otm_selt=mysql_fetch_array($otm_sel))
{
echo '<option value="'.$otm_selt['id'].'" ';
if ($line['id_otmetka']==$otm_selt['id']){echo'selected';}
echo'>'.$otm_selt['name'].'</option>';
}
echo '</select></td></tr>
<tr><td>Введите примечание</td></tr><tr><td><input type="text" name="pri" value="'.$line['prim'].'" size="40"</td></tr>';

echo '</table><input type=submit name="update" value="Изменить"></form>';
}

//---------------------------------------------Ввод данных--------------------------------------
if (isset($_GET['add']))
{
$otmetka="SELECT id,name FROM otmetka";
$otm_sel=mysql_query($otmetka);
$sql="SELECT * FROM uch_org_rab WHERE id_kadri=".$id_kadri." and id_year=".$id_year."";
$uch_org_rab=mysql_query($sql);
echo '<table border=1 class="cent">';
$line=mysql_fetch_row($uch_org_rab);
echo '<form method=POST action="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id_razdel2">
<tr><td colspan=2><table border=1 class=indplan><caption>
<h4>Выберите работу <span class=warning>*</span></h4></caption><tr><th>Список учебно- и организационно-методических работ</td>
<th>Нормы времени в часах </td><th></td></tr>';
while($name=mysql_fetch_array($spr_vid))
 {
 echo '<tr><td>'.$name['name'].'</td><td>'.$name['time_norm'].'</td>
 <td><input type=radio name="delete_rab" id="delete_rab" value="'.$name['id'].'" title="Нормы времени в часах"></td></tr>';
 }
 echo '</table></td></tr>';
 echo '
<tr><td>Планируемое количество</td></tr>
<tr><td><input type="text" name="plan" id="plan" title="Планируемое количество" size="15">
<span class=warning>*</span></td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td>Введите срок выполнения</td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td><input type="text" id="srok" name="srok" title="срок выполнения">
<span class=warning>*</span>
<button  type="reset" id="f_trigger_date_act">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "srok",
        ifFormat       :    "%d.%m.%Y",
        showsTime      :    false,
        button         :    "f_trigger_date_act",
        singleClick    :    true,
        step           :    1
    });
	</script>
</td></tr>
<tr><td>Введите вид отчётности</td></tr>
<tr><td><input type="text" name="otch" id="otch" size="30" title="вид отчётности">
<span class=warning>*</span></td></tr>
<tr><td>Введите отметку о выполнении работы</td></tr>
<tr><td><select name="danet" id="danet">
<span class=warning>*</span>';
while($otm_selt=mysql_fetch_array($otm_sel))
{
echo '<option value="'.$otm_selt['id'].'">'.$otm_selt['name'].'</option>';
}
echo '</select></td></tr>
<tr><td>Введите примечание</td></tr>
<tr><td><input type="text" name="pri" id="pri" size="40" title="примечание"></td></tr>';

echo '</table>';?>
<input type="submit" name="add" value="Сохранить"
onclick="return requireFieldCheck(new Array(new Array('delete_rab',''),new Array('plan',''),new Array('srok',''),
		new Array('otch',''),new Array('danet','')));"></form>
<?php


}


//---------------------------------------------Раздел 3-----------------------------------
if (!isset($_GET['save']) && !isset($_GET['print']))
{echo '<p><a class="notinfo" href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id_razdel3=3">3. Научно-методическая и госбюджетная научно-исследовательская работа</a>';}
if  (isset($_GET['id_razdel3']))
{
if (!$check)
echo '<h3>Нет данных</h3>';
else
//---------------------------------------вывод таблицы----------------------------------------
 {
echo '<table border=1 class=indplan>
<tr class=indplan><th>№</td><th>Тема, наименование работы</td><th>Планируемое количество</td><th>Планируемое количество часов</td>
<th>Срок выполнения</td><th>Вид отчётности</td><th>Примечание</td><th></td></tr>';
$i=1;
while ($line=mysql_fetch_row($nauch_met_rab))
{
$sql2="SELECT name FROM spravochnik_vidov_rabot WHERE id=".$line[3]."";
$mysql2=mysql_query($sql2);
$res1=mysql_fetch_row($mysql2);
echo '<tr onmouseover=this.style.background="#fffafa" onmouseout=this.style.background="#DFEFFF"><td>'.$i.'</td><td>'.$res1[0].'</td><td>'.$line[8].'</td><td>'.$line[6].'</td>
<td>'.f_ri(DateTimeCustomConvert($line[5],'d','mysql2rus')).'</td><td>'.$line[7].'</td><td>'.$line[4].'</td><td>'; if (!isset($_GET['save']) && !isset($_GET['print'])) {echo'<a href="javascript:del_confirm_act(\''.f_ro($line[0]).'\',\''.str_replace(" ","_",f_ro($res1[0])).'\');"><img src="'.$files_path.'images/todelete.png" alt="Удалить" title="Удалить"></a><p><a href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id='.$line[0].'&update3"><img src="'.$files_path.'images/toupdate.png" alt="Редактирвать" title="Редактировать"></a></td></tr>';}
	$i++;
	}
echo '</table>';
   }
  //------------------------------Проверка на пустые поля при редактировании----------------
if ((isset($_POST['update3'])) and !($empt))
 {
 echo'<h3>Данные успешно изменены.</h3>';
  }

    if ($empt)
    {
    echo '<h3>Вы заполнили не все поля</h3>';
     }

//------------------------------Проверка на пустые поля при Добавлении----------------
if ((isset($_POST['add3'])) and !($empt_add))
 {
 echo'<h3>Данные успешно добавлены.</h3>';
  }

    if ($empt_add)
    {
    echo '<h3>Вы заполнили не все поля</h3>';
     }
     if ($duplicat)
    {
    echo '<h3>Научно-методическая и госбюджетая научно-исследовательская работа с таким названием уже существует,
     проверьте целесообразность введения данных.</h3>';
     }
     if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<a class="notinfo" href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&add3">Добавить работу</a>';}

   	if(isset($_GET['delete']))
    	{
    	echo'<h3>Запись успешно удалена.</h3>';
    	}
}
 //---------------------------------------------Редактирование и удаление------------------------


if (isset($_GET['update3']))
{

echo '<form method=POST action="?id_razdel3&id_kadri='.$id_kadri.'&id_year='.$id_year.'">
<table border=1 class="cent">';

$spravochnik="SELECT id,name,time_norm FROM spravochnik_vidov_rabot WHERE id_razdel=3 ORDER BY name ";
$spr_vid=mysql_query($spravochnik);
echo '<input type="hidden" name="a" value="'.$line[0].'"><tr><td colspan=2><table border=1 class=indplan><caption><h4>Выберите работу</h4></caption><tr><th>Список научно-методической и госбюджетно научно-исследовательской работы</td><th>Нормы времени в часах </td><th></td></tr>';
 while($name=mysql_fetch_array($spr_vid))
 {
 echo '<tr><td>'.$name['name'].'</td><td>'.$name['time_norm'].'</td><td><input type=radio name="delete_rab" value="'.$name['id'].'" ';
  if ($name['id']==$line['id_vidov_rabot']) echo 'checked';
  echo'></td></tr>';
 }
 echo '</table></td></tr>';
 echo '
<tr><td>Введите планируемое количество</td></tr><tr><td><input type="text" name="plan" value="'.$line[8].'" size="15"></td></tr>
<tr><td>Введите планируемое количество часов</td></tr><tr><td><input type="text" value="'.$line[6].'" name="timeplan" size="15"></td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td>Введите срок выполнения</td></tr><tr title="Введите дату в формате дд.мм.гггг"><td><input type="text" id=date_act name="srok" value="'.f_ri(DateTimeCustomConvert($line[5],'d','mysql2rus')).'" size="30">
<button  type="reset" id="f_trigger_date_act">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
</td></tr>
<tr><td>Введите вид отчётности</td></tr><tr><td><input type="text" name="otch" value="'.$line[7].'" size="30"></td></tr>
<tr><td>Введите примечание</td></tr><tr><td><input type="text" name="pri" value="'.$line[4].'" size="40"</td></tr>';

echo '</table><input type=submit name="update3" value="Изменить"></form>';


   }
//---------------------------------------------Ввод данных--------------------------------------
if (isset($_GET['add3']))
{

$sql="SELECT * FROM nauch_met_rab WHERE id_kadri=".$id_kadri." and id_year=".$id_year."";
$nauch_met_rab=mysql_query($sql);
echo '<table border=1 class="cent">';
$line=mysql_fetch_row($nauch_met_rab);
$sql4="SELECT id,name FROM spravochnik_vidov_rabot WHERE id_razdel=3 order by name";
$mysql4=mysql_query($sql4);
echo '<form method=POST action="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id_razdel3">
<tr><td colspan=2><table border=1 class=indplan><caption><h4>Выберите работу<span class=warning>*</span></h4></caption>
<tr><th>Список учебно- и организационно-методических работ</td><th>Нормы времени в часах </td><th></td></tr>';
while($name=mysql_fetch_array($spr_vid))
 {
 echo '<tr><td>'.$name['name'].'</td><td>'.$name['time_norm'].'</td><td><input type=radio name="delete_rab" value="'.$name['id'].'"></td></tr>';
 }
 echo '</table></td></tr>';
 echo '
<tr><td>Введите планируемое количество</td></tr><tr><td><input type="text" title="планируемое количество" name="plan"  size="15"><span class=warning>*</span></td></tr>
<tr><td>Введите планируемое количество часов</td></tr><tr><td><input type="text" title="планируемое количество часов"  name="timeplan" size="15"><span class=warning>*</span></td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td>Введите срок выполнения</td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td><input type="text" name="srok" title="срок выполнения" id=srok size="30"><span class=warning>*</span>
<button  type="reset" id="f_trigger_date_act">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "srok",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
</td></tr>
<tr><td>Введите вид отчётности</td></tr><tr><td><input type="text" name="otch" title="вид отчётности"  size="30"><span class=warning>*</span></td></tr>
<tr><td>Введите примечание</td></tr><tr><td><input type="text" name="pri" title="примечание"  size="40"></td></tr>';

echo '</table>';
?>
<input type="submit" name="add3" value="Сохранить"
onclick="return requireFieldCheck(new Array(new Array('delete_rab',''),new Array('plan',''),new Array('timeplan',''),
		new Array('srok',''),new Array('otch','')));"></form>
<?php


}
//---------------------------------------------Раздел 4--------------------------
if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<p><a class="notinfo" href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id_razdel4=4">4. Учебно-воспитательная работа</a>';}
if  (isset($_GET['id_razdel4']))
{
//---------------------------------------вывод таблицы----------------------------------------
if (!$check)
echo '<h3>Нет данных</h3>';
else
 {
echo '<table border=1 class=indplan>
<tr class=indplan><th>№</td><th>Виды работ</td><th>Планируемое количество часов</td><th>Срок выполнения</td>
<th>Отметка о выполнении</td><th>Примечание</td><th>Номер группы</td><th></td></tr>';
$i=1;
while ($line=mysql_fetch_array($uch_vosp_rab))
{
$sql2="SELECT name FROM spravochnik_vidov_rabot WHERE id=".$line['id_vidov_rabot']."";
$mysql2=mysql_query($sql2);
$res1=mysql_fetch_array($mysql2);
if ($line['otm']=='нет')
{
if($line['srok_vipolneniya']<date('Y-m-d'))
echo '<tr onmouseover=this.style.background="tomato" onmouseout=this.style.background="red" bgcolor=red title="Данная работа была просрочена">';
else
echo '<tr>';
}
else
echo '<tr onmouseover=this.style.background="#fffafa" onmouseout=this.style.background="#DFEFFF">';
echo '<td>'.$i.'</td><td>'.$res1['name'].'</td><td>'.$line['kol_vo_plan'].'</td><td>'.f_ri(DateTimeCustomConvert($line['srok_vipolneniya'],'d','mysql2rus')).'</td>
<td>'.$line['otm'].'</td><td>'.$line['prim'].'</td><td>'.$line['st_group'].'</td><td>'; if (!isset($_GET['save']) && !isset($_GET['print'])) {echo'<a href="javascript:del_confirm_act(\''.f_ro($line['id']).'\',\''.str_replace(" ","_",f_ro($res1['name'])).'\');"><img src="'.$files_path.'images/todelete.png" alt="Удалить" title="Удалить"></a><p><a href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id='.$line['id'].'&update4"><img src="'.$files_path.'images/toupdate.png" alt="Редактировать" title="Редактировать"></a></td></tr>';}
	$i++;
	}
echo '</table>';
   }
//------------------------------Проверка на пустые поля при редактировании----------------
if ((isset($_POST['update4'])) and !($empt))
 {
 echo'<h3>Данные успешно изменены.</h3>';
  }

    if ($empt)
    {
    echo '<h3>Вы заполнили не все поля</h3>';
     }

//------------------------------Проверка на пустые поля при Добавлении----------------
if ((isset($_POST['add4'])) and !($empt_add))
 {
 echo'<h3>Данные успешно добавлены.</h3>';
  }

    if ($empt_add)
    {
    echo '<h3>Вы заполнили не все поля</h3>';
     }
       if ($duplicat)
    {
    echo '<h3>Учебно-воспитательная работа с таким названием уже существует,
     проверьте целесообразность введения данных.</h3>';
     }
     if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<a class="notinfo" href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&add4">Добавить работу</a>';}
   	if(isset($_GET['delete']))
    	{
    	echo'<h3>Запись успешно удалена.</h3>';
    	}
   }
//---------------------------------------------Редактирование ------------------------



if (isset($_GET['update4']))
{

echo '<form method=POST action="?id_razdel4&id_kadri='.$id_kadri.'&id_year='.$id_year.'">
<table border=1 class="cent">';
$otmetka="SELECT id,name FROM otmetka";
$otm_sel=mysql_query($otmetka);
$study="SELECT id,name FROM study_groups";
$study_groups=mysql_query($study);
$spravochnik="SELECT id,name,time_norm FROM spravochnik_vidov_rabot WHERE id_razdel=4 ORDER BY name";
$spr_vid=mysql_query($spravochnik);
echo '<input type="hidden" name="a" value="'.$line['id'].'"><tr><td colspan=2><table border=1 class=indplan><caption><h4>Выберите работу</h4></caption><tr><th>Список учебно- и организационно-методических работ</td><th>Нормы времени в часах </td><th></td></tr>';
 while($name=mysql_fetch_array($spr_vid))
 {
 echo '<tr><td>'.$name['name'].'</td><td>'.$name['time_norm'].'</td><td><input type=radio name="delete_rab" value="'.$name['id'].'" ';
  if ($name['id']==$line['id_vidov_rabot']) echo 'checked';
  echo'></td></tr>';
 }
 echo '</table></td></tr>';
 echo '
<tr><td>Планируемое количество часов</td></tr><tr><td><input type="text" name="plan" value="'.$line['kol_vo_plan'].'" size="15"></td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td>Введите срок выполнения</td></tr><tr title="Введите дату в формате дд.мм.гггг"><td><input type="text" value="'.f_ri(DateTimeCustomConvert($line['srok_vipolneniya'],'d','mysql2rus')).'" id=date_act name="srok">
<button  type="reset" id="f_trigger_date_act">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
</td></tr>
<tr><td>Введите примечание</td></tr><tr><td><input type="text" name="prim" value="'.$line['prim'].'" size="30"></td></tr>
<tr><td>Введите отметку о выполнении работы</td></tr><tr><td><select name="danet">';
while($otm_selt=mysql_fetch_array($otm_sel))
{
echo '<option value="'.$otm_selt['id'].'" ';
if ($line['id_otmetka']==$otm_selt['id']){echo'selected';}
echo'>'.$otm_selt['name'].'</option>';
}
echo'</select></td></tr>
<tr><td>Введите номер группы</td></tr><tr><td>
<select name="group">
<option value="0">выберите из списка ('.mysql_numrows($study_groups).')</option>';
while($st_gr=mysql_fetch_array($study_groups))
{
echo'
<option value="'.$st_gr['id'].'"';
if($line['id_study_groups']==$st_gr['id']){echo' selected';}
echo '>'.$st_gr['name'].'</option>';
}
echo '</select></td></tr>';

echo '</table><input type=submit name="update4" value="Изменить"></form>';


   }
//---------------------------------------------Ввод данных--------------------------------------
if (isset($_GET['add4']))
{
$otmetka="SELECT id,name FROM otmetka order by name";
$otm_sel=mysql_query($otmetka);
$study="SELECT id,name FROM study_groups order by name";
$study_groups=mysql_query($study);
echo '<table border=1 class="cent">';
$line=mysql_fetch_row($uch_vosp_rab);
$sql4="SELECT id,name FROM spravochnik_vidov_rabot WHERE id_razdel=4";
$mysql4=mysql_query($sql4);
echo '<form method=POST action="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id_razdel4">
<tr><td colspan=2><table border=1 class=indplan><caption>
<h4>Выберите работу<span class=warning>*</span></h4></caption><tr><th>Список учебно-воспитательных работ</td>
<th>Нормы времени в часах </td><th></td></tr>';
while($name=mysql_fetch_array($spr_vid))
 {
 echo '<tr><td>'.$name['name'].'</td><td>'.$name['time_norm'].'</td>
 <td><input type=radio name="delete_rab" value="'.$name['id'].'"></td></tr>';
 }
 echo '</table></td></tr>';
 echo '
<tr><td>Планируемое количество часов</td></tr>
<tr><td><input type="text" name="plan"  size="15" title="планируемое количество часов"><span class=warning>*</span></td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td>Введите срок выполнения</td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td><input type="text" id=srok name="srok" title="срок выполнения"><span class=warning>*</span>
<button  type="reset" id="f_trigger_date_act">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "srok",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
</td></tr>
<tr><td>Введите примечание</td></tr>
<tr><td><input type="text" name="prim" size="30" title="примечание"></td></tr>
<tr><td>Введите отметку о выполнении работы</td></tr>
<tr><td><select name="danet">';
while($otm_selt=mysql_fetch_array($otm_sel))
{
echo '<option value="'.$otm_selt['id'].'">'.$otm_selt['name'].'</option>';
}
echo'</select></td></tr>
<tr><td>Введите номер группы</td></tr>
<tr><td><select name="group" title="номер группы">
<option value="0">выберите из списка ('.mysql_numrows($study_groups).')</option>';
while($st_gr=mysql_fetch_array($study_groups))
{
echo'
<option value="'.$st_gr['id'].'">'.$st_gr['name'].'</option>';
}
echo '</select><span class=warning>*</span>
</td></tr>';

echo '</table>';
?>
<input type="submit" name="add4" value="Сохранить" 
onclick="return requireFieldCheck(new Array(new Array('plan',''),
		new Array('srok',''),new Array('group','')));">

</form>
<?php

}
//---------------------------------------------Раздел 5--------------------------
if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<p><a class="notinfo" href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id_razdel5">5. Перечень научных и научно-методических работ, выполненных преподавателем</a>';
}
if  (isset($_GET['id_razdel5']))
{
if (!$check1 and !$check2)
echo '<h3>Нет данных</h3>';
else
 {
 echo '<table border=1 class=indplan>
<tr class=indplan><th>Наименование работ</td><th>Объём печатных листов и издательство</td><th></td></tr><tr><th>А) Печатных</td><th>&nbsp;</td><th>&nbsp;</td></tr>';
while ($line=mysql_fetch_array($perechen_nauch_rab_p))
{
echo "<tr onmouseover=this.style.background='#fffafa' onmouseout=this.style.background='#DFEFFF'><td>".$line['name']."</td><td>".$line['volume']."</td><td>"; if (!isset($_GET['save']) && !isset($_GET['print'])) {echo "<a href='javascript:del_confirm_act(\"".f_ro($line['id'])."\",\"".str_replace(' ','_',f_ro($line[3]))."\");'><img src='".$files_path."images/todelete.png' alt='Удалить' title='Удалить'></a><p><a href='?id_kadri=".$id_kadri."&id_year=".$id_year."&id=".$line[0]."&update5'><img src='".$files_path."images/toupdate.png' alt='Редактировать' title='Редактировать'></a></td></tr>";}
	}
echo '<tr><th>Б) Рукописных</td><th>&nbsp;</td><th>&nbsp;</td></tr>';
while ($line=mysql_fetch_array($perechen_nauch_rab_r))
{
echo "<tr onmouseover=this.style.background='#fffafa' onmouseout=this.style.background='#DFEFFF'><td>".$line['name']."</td><td>".$line['volume']."</td><td>"; if (!isset($_GET['save']) && !isset($_GET['print'])) {echo "<a href='javascript:del_confirm_act(\"".f_ro($line['id'])."\",\"".str_replace(' ','_',f_ro($line[3]))."\");'><img src='".$files_path."images/todelete.png' alt='Удалить' title='Удалить'></a><p><a href='?id_kadri=".$id_kadri."&id_year=".$id_year."&id=".$line[0]."&update5'><img src='".$files_path."images/toupdate.png' alt='Редактировать' title='Редактировать'></a></td></tr>";}
	}
echo '</table>';
}
 //------------------------------Проверка на пустые поля при редактировании----------------
if ((isset($_POST['update5'])) and !($empt))
 {
 echo'<h3>Данные успешно изменены.</h3>';
  }

    if ($empt)
    {
    echo '<h3>Вы заполнили не все поля</h3>';
     }
     if ($duplicat_apd)
    {
    echo '<h3>Проверьте корректность введённых данных, возможно работа с таким названием уже существует.</h3>';
     }
//------------------------------Проверка на пустые поля при Добавлении----------------
if ((isset($_POST['add5'])) and !($empt_add) and !($duplicat))
 {
 echo'<h3>Данные успешно добавлены.</h3>';
  }

    if ($empt_add)
    {
    echo '<h3>Вы заполнили не все поля</h3>';
     }
     if ($duplicat)
    {
    echo '<h3>Данные не добавлены, научная работа уже существует.</h3>';
     }
if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<a href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&add5">Добавить работу</a>';
}

     	if(isset($_GET['delete']))
    	{
    	echo'<h3>Запись успешно удалена.</h3>';
    	}
    }
 //---------------------------------------------Редактирование ------------------------



if (isset($_GET['update5']))
{
$type_nauch_rab="SELECT id,name FROM type_nauch_rab";
$type_rab=mysql_query($type_nauch_rab);
echo '<form method=POST action="?id_razdel5&id_kadri='.$id_kadri.'&id_year='.$id_year.'">
<table border=1 class="cent">';
echo '<input type="hidden" name="a" value="'.$line['id'].'">
<tr><td>Введите название работы</td></tr><tr><td><input type="text" name="rab_name" value="'.$line['name'].'" size="40"></td></tr>
<tr><td>Введите количество печатных листов и издательство</td></tr><tr><td><input type="text" value="'.$line['volume'].'" name="kol"></td></tr>
<tr><td>Введите вид работы</td></tr><tr><td><select name="rab_vid">';
while($type=mysql_fetch_array($type_rab))
{
echo '<option value="'.$type['id'].'" ';
if ($line['id_type_nauch_rab']==$type['id']){echo'selected';}
echo'>'.$type['name'].'</option>';
}
echo '</select></td></tr>
</table><input type=submit name="update5" value="Изменить"></form>';


   }
//---------------------------------------------Ввод данных--------------------------------------
if (isset($_GET['add5']))
{
$type_nauch_rab="SELECT id,name FROM type_nauch_rab";
$type_rab=mysql_query($type_nauch_rab);
echo '<table border=1 class="cent"><form method=POST action="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id_razdel5">
<tr><td>Введите название работы</td></tr>
<tr><td><input type="text" name="rab_name"  size="40" title="название работы"><span class=warning>*</span></td></tr>
<tr><td>Введите количество печатных листов и издательство</td></tr>
<tr><td><input type="text"  name="kol" title="количество печатных листов и издательство"><span class=warning>*</span></td></tr>
<tr><td>Введите вид работы</td></tr><tr><td><select name="rab_vid">';
while($type=mysql_fetch_array($type_rab))
{
echo '<option value="'.$type['id'].'">'.$type['name'].'</option>';
}
echo '</select></td></tr>';

echo '</table>';
?>
<input type="submit" name="add5" value="Сохранить" 
onclick="return requireFieldCheck(new Array(new Array('rab_name',''),new Array('kol','')));">

</form>
<?php
}
//---------------------------------------------Раздел 6-------------------------
//---------------------------------------------Вывод таблицы-------------------------
if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<p><a class="notinfo" href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id_razdel6=6">6. Записи об изменениях в Годовом индивидуальном плане</a>';
}
if  (isset($_GET['id_razdel6']))
{
$i=1;
if (!$check)
echo '<h3>Нет данных</h3>';
else {
	echo '<table border=1 class=indplan>
<tr class=indplan><th>№</td><th>Раздел и пункт</td><th>Изменения(Причины)</td><th>Дата (зав.каф.)</td>
<th>Дата (преподаватель)</td><th>Отметка о выполнении</td><th></td></tr>';
while ($line=mysql_fetch_array($izmen))
{
echo '<tr onmouseover=this.style.background="#fffafa" onmouseout=this.style.background="#DFEFFF"><td>'.$i.'</td><td>'.$line['razdel'].'</td><td>'.$line['izmenenie'].'</td><td>'.f_ri(DateTimeCustomConvert($line['zav'],'d','mysql2rus')).'</td>
<td>'.f_ri(DateTimeCustomConvert($line['prep'],'d','mysql2rus')).'</td><td>'.$line['otm'].'</td><td>'; if (!isset($_GET['save']) && !isset($_GET['print'])) {echo '<a href="javascript:del_confirm_act(\''.f_ro($line['id']).'\',\''.str_replace(" ","_",f_ro($line['izmenenie'])).'\');"><img src="'.$files_path.'images/todelete.png" alt="Удалить" title="Удалить"></a><p><a href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id='.$line[0].'&update6"><img src="'.$files_path.'images/toupdate.png" alt="Редактировать" title="Редактировать"></a></td></tr>';}
	$i++;
	}

echo '</table>';
}
//------------------------------Проверка на пустые поля при редактировании----------------
if ((isset($_POST['update6'])) and !($empt))
 {
 echo'<h3>Данные успешно изменены.</h3>';
  }

    if ($empt)
    {
    echo '<h3>Вы заполнили не все поля</h3>';
     }
//------------------------------Проверка на пустые поля при Добавлении----------------
if ((isset($_POST['add6'])) and !($empt_add) and !($duplicat))
 {
 echo'<h3>Данные успешно добавлены.</h3>';
  }

    if ($empt_add)
    {
    echo '<h3>Вы заполнили не все поля</h3>';
     }
      if ($duplicat)
    {
    echo '<h3>Данные не добавлены, запись уже существует.</h3>';
     }
     if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<a class="notinfo" href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&add6">Добавить запись</a>';
 }
      	if(isset($_GET['delete']))
    	{
    	echo'<h3>Запись успешно удалена.</h3>';
    	}
   }
//---------------------------------------------Редактирование------------------------



if (isset($_GET['update6']))
{

echo '<form method=POST action="?id_razdel6&id_kadri='.$id_kadri.'&id_year='.$id_year.'">
<table border=1 class="cent">';
$otmetka="SELECT id,name FROM otmetka";
$otm_sel=mysql_query($otmetka);
echo '<input type="hidden" name="a" value="'.$line['id'].'">
<tr><td>Введите раздел и пункт</td></tr><tr><td><input type="text" name="razdel" value="'.$line['razdel'].'" size="15"></td></tr>
<tr><td>Введите описание и причины изменения </td></tr><tr><td><textarea rows=5 cols=30 name="izmenenie">'.$line['izmenenie'].'</textarea></td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td>Введите дату (зав.каф.)</td></tr><tr title="Введите дату в формате дд.мм.гггг"><td><input type="text" id=date_act name="zav" value="'.f_ri(DateTimeCustomConvert($line['zav'],'d','mysql2rus')).'" size="15">
<button  type="reset" id="f_trigger_date_act">...</button>
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
</td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td>Введите дату (преподаватель)</td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td><input type="text" id=date_act1 name="prep" value="'.f_ri(DateTimeCustomConvert($line['prep'],'d','mysql2rus')).'" size="15">
<button  type="reset" id="f_trigger_date_act1">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act1",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act1",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
</td></tr>
<tr><td>Введите отметку о выполнении</td></tr><tr><td><select name="danet">';
while($otm_selt=mysql_fetch_array($otm_sel))
{
echo '<option value="'.$otm_selt['id'].'" ';
if ($line['id_otmetka']==$otm_selt['id']){echo'selected';}
echo'>'.$otm_selt['name'].'</option>';
}
echo '</select></td></tr>';

echo '</table><input type=submit name="update6" value="Изменить"></form>';


   }
//---------------------------------------------Ввод данных--------------------------------------
if (isset($_GET['add6']))
{
$otmetka="SELECT id,name FROM otmetka";
$otm_sel=mysql_query($otmetka);
$sql="SELECT * FROM izmen WHERE id_kadri=".$id_kadri." and id_year=".$id_year."";
$izmen=mysql_query($sql);
echo '<table border=1 class="cent">';
$line=mysql_fetch_row($izmen);
echo '<form method=POST action="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id_razdel6">
<tr><td>Введите раздел и пункт</td></tr>
<tr><td><input type="text" name="razdel" title="раздел и пункт" size="15"><span class=warning>*</span></td></tr>
<tr><td>Введите описание и причины изменения </td></tr>
<tr><td><textarea rows=5 cols=30 name="izmenenie" title="описание и причины изменения"></textarea><span class=warning>*</span></td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td>Введите дату (зав.каф.)</td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td><input type="text" name="zav" id=zav size="15" title="дату (зав.каф.)"><span class=warning>*</span>
<button  type="reset" id="f_trigger_date_act">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "zav",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
</td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td>Введите дату (преподаватель)</td></tr>
<tr title="Введите дату в формате дд.мм.гггг"><td><input type="text" name="prep" id=prep size="15" title="дату (преподаватель)"><span class=warning>*</span>
<button  type="reset" id="f_trigger_date_act1">...</button>
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "prep",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act1",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script></td></tr>
<tr><td>Введите отметку о выполнении</td></tr><tr><td><select name="danet">';
while($otm_selt=mysql_fetch_array($otm_sel))
{
echo '<option value="'.$otm_selt['id'].'">'.$otm_selt['name'].'</option>';
}
echo '</select></td></tr>';

echo '</table>';
?>
<input type="submit" name="add6" value="Сохранить" 
onclick="return requireFieldCheck(new Array(new Array('razdel',''),new Array('izmenenie',''),
		new Array('zav',''),new Array('prep',''),new Array('danet','')));">

</form>
<?php


}
//---------------------------------------------Раздел 7-------------------------
if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<p><a class="notinfo" href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id_razdel7">7. Заключение и предложения заведующего кафедрой</a>';
}
if  (isset($_GET['id_razdel7']))
{

echo '<br><textarea name="msg" rows=5 cols=30 readonly>'.$msg[1].'</textarea>';
echo '<br>'; if (!isset($_GET['save']) && !isset($_GET['print'])) {echo '<a class="notinfo" href="?id_kadri='.$id_kadri.'&id_year='.$id_year.'&izmen">Изменить запись</a>';}
if (isset($_POST['msg']))
     {
     	echo '<H3>Данные успешно добавлены</H3>';
     }

}
if  (isset($_GET['izmen']))
{
echo'
<form name="" method="POST" action=?id_kadri='.$id_kadri.'&id_year='.$id_year.'&id_razdel7>
<textarea cols=30 rows=5 name="msg" title="Заключение и предложения заведующего кафедрой">'.$msg[1].'</textarea>
<span class=warning>*</span>
<br><input type="submit" value="Ввод" onclick="return requireFieldCheck(new Array(new Array(\'msg\',\'\')));">
<input type="hidden" name="id" value="'.$msg[0].'">
</form>';
}
if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<p><a class="notinfo" href="ind_index.php">Назад</a>';}
mysql_close();


}

else
{
if ($id_kadri==0 && $id_year==0)echo '<p align=center><h3>Выберите преподавателя и учебный год.</h3><br><a href="ind_index.php" class="notinfo">Назад</a></p>';
else
	{
	if ($id_year==0)echo '<p align=center><h3>Выберите учебный год.</h3><br><a href="ind_index.php" class="notinfo">Назад</a></p>';
	if ($id_kadri==0)echo '<p align=center><h3>Выберите преподавателя.</h3><br><a href="ind_index.php" class="notinfo">Назад</a></p>';
    }

}
show_footer();
include($portal_path.'footer.php'); 
?>