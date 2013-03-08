<?php
//система вывода отчетности на основе текста SQL-запроса и полей группировки (от 1-3 полей)

$page=1;
$pageVals=20;	//число записей на странице по умолчанию
$query_string=$_SERVER['QUERY_STRING'];
$sort=0;
$stype='asc';		//тип сортировки столбца

if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];}
if (isset($_GET['pageVals']) && $_GET['pageVals']<=99 && $_GET['pageVals']>=1) {$pageVals=$_GET['pageVals'];}
if (isset($_GET['sort']) && intval($_GET['sort'])>0) {$sort=intval($_GET['sort']);}
if (isset($_GET['stype']) && $_GET['stype']=='desc') {$stype=$_GET['stype'];}

function setColWidth($fieldLen)
{
 if ($fieldLen>100) return 100;
 else {if ($fieldLen<=0) return 10;
 		else return $fieldLen*3;}
} 
function report_build($inGrFilendNum,$query) {
//
global $page,$pageVals,$query_string,$sort,$stype;
//получаем имена столбцов по запросу, в запросе намерено не запрашиваем данные

$res_col_names=mysql_query($query.' limit 0');
//echo $query.' limit 0';
//print_r($res_col_names);
//echo ' mysql_field_name($res_col_names,0)='.mysql_field_name($res_col_names,2);

$groupArrGlobal=array();
$inGrFilendNum_cnt=count($inGrFilendNum);
if ($inGrFilendNum_cnt>3) $inGrFilendNum_cnt=3;
else if ($inGrFilendNum_cnt<=0) {$inGrFilendNum_cnt=1;$inGrFilendNum=array(1);}

for ($i=0;$i<$inGrFilendNum_cnt;$i++) 
	array_push($groupArrGlobal, array('grN'.($i+1),mysql_field_name($res_col_names,$inGrFilendNum[$i]-1),($i+1),1));

if ($inGrFilendNum_cnt==1) {
	array_push($groupArrGlobal, array('grN2',mysql_field_name($res_col_names,1),2,0));
	array_push($groupArrGlobal, array('grN3',mysql_field_name($res_col_names,2),3,0));
	}
if ($inGrFilendNum_cnt==2) 
	array_push($groupArrGlobal, array('grN3',mysql_field_name($res_col_names,2),3,0));

//print_r($groupArrGlobal);
/*
$groupArrGlobal=array(
	array('grN1','дисциплина',1,1),
	array('grN2','преподаватель',2,1),
	array('grN3','вид контроля',3,1)
	);	//массив уровней группировки
*/
	//смотрим какой параметр не используется при группировке
	$ignCol=count($groupArrGlobal);	//число игнорируемых колонок при выводе таблицы результатов
	
	for ($i=0;$i<$ignCol;$i++)
		if (strpos($query_string,'grN'.($i+1))=== false && isset($_GET['gr'.$i])) $groupArrGlobal[$i][3]=0; 

$getArrGr=array('','','');	//массив входных параметров сортировки из Get

$groupArr=array();
$groupArr=$groupArrGlobal;	

if (isset($_GET['gr0']) && isset($_GET['gr1']) && isset($_GET['gr2']))
{
	for ($i=0;$i<count($getArrGr);$i++)	{$getArrGr[$i]=$_GET['gr'.$i];}

	$groupArrTmp=array($groupArrGlobal[0][0]=>0,$groupArrGlobal[1][0]=>1,$groupArrGlobal[2][0]=>2);
	
		for ($i=0;$i<count($getArrGr);$i++)	//формируем массив группировочных данных
		{ 	$groupArr[$i]=$groupArrGlobal[$groupArrTmp[$getArrGr[$i]]];	 	}
}
?>

<script language="javascript">
function test_liter_order(){//проверка порядка сортировки для вывода литературы
	var val_tmp_i='';
	var val_tmp_j='';
	
	var cur_val='';
	var err=false;
	
	var sp_name=document.getElementById('gr0');
	var pp_name=document.getElementById('gr1');
	var kd_fio=document.getElementById('gr2');
	
	if (sp_name.value==pp_name.value || sp_name.value==kd_fio.value) {err=true;}
	if (sp_name.value==pp_name.value || pp_name.value==kd_fio.value) {err=true;}
	if (sp_name.value==kd_fio.value || pp_name.value==kd_fio.value) {err=true;}

	if (err==false) {document.forms['gr_list'].submit(); }
	else {if (!confirm('Обнаружено совпадение в порядке группировки.\n\n Вы можете исправить порядок группировки или использовать указанный Вами порядок. \n\nИсправить ?')) document.forms['gr_list'].submit();}
} 
</script>


<?php

//указываем сортировку для используемых столбцов в группировки, а потом по сортируемому столбцу
//print_r($groupArrGlobal);
$query.=' order by ';
for ($i=0;$i<count($groupArr);$i++) if ($groupArrGlobal[$i][3]==1) $query.=$groupArrGlobal[$i][2].', ';
if ($sort>0) $query.=''.$sort.' '.$stype.' ';
else $query=preg_replace('/, $/','',$query);

//echo $query;
$res_PP=mysql_query($query.' limit '.(($page-1)*$pageVals).','.$pageVals);

if (!isset($_GET['save']) && !isset($_GET['print'])) {
?>
<form name="gr_list" id="gr_list" action="" method="get"> порядок группировки:
<?php	
	$query_str_hiddenFields=$query_string; //храним параметры GET, кроме полей группировки


	for ($j=0;$j<count($groupArr);$j++) 
	{
	echo ($j+1).' <select name="gr'.$j.'" id="gr'.$j.'">';
	for ($i=0;$i<count($groupArrGlobal);$i++) 
		{
		 $selected='';
		 if (isset($_GET['gr'.$j]))
		 	{
			  if ($groupArrGlobal[$i][0]==$_GET['gr'.$j]){$selected=' selected';}
			  $query_str_hiddenFields=reset_param_name ($query_str_hiddenFields,'gr'.$j);
			 }
		 else 
		 	if ($groupArrGlobal[$i][0]==$groupArrGlobal[$j][0] && $groupArrGlobal[$i][3]==1) {$selected=' selected';}
		echo '<option value='.$groupArrGlobal[$i][0].''.$selected.'>'.$groupArrGlobal[$i][1].'</option>';
		}
	echo '</select> &nbsp;'; 	
	}
while (list($key, $value) = each($_GET)) 
{
    //не храним группы, т.к. они уже есть
    if (!preg_match('/^gr\d+$/', $key))
        echo '<input type=hidden id="'.$key.'" name="'.$key.'" value="'.$value.'">';
}
?>

<input type=button value=Ok onClick=javascript:test_liter_order();> &nbsp; 	
	<input type=button value="по умолчанию" title="восстановить параметры по умолчанию" 
	onClick=window.location.href="?<?php echo $query_str_hiddenFields;?>">
<!-- input type=hidden id="student_id" name="student_id" value="<?php echo $_GET['student_id'];?>" -->
</form>
<?php		
	}
$flag1=false;$flag2=false;
$i=0;
$elemCnt=mysql_num_rows($res_PP);
//echo ' elemCnt='.$elemCnt;
$res_pp=mysql_fetch_array($res_PP,MYSQL_NUM);
//echo '$groupArr[0][2]-1='.($groupArr[0][2]-1).'';
//print_r($groupArr);
//echo ' sort='.$sort;
	while ($i<$elemCnt)	
	{
   	     $paramVal1=$res_pp[$groupArr[0][2]-1];
 		 //1-уровня группировки
		 echo '<br><b>'.$res_pp[$groupArr[0][2]-1].'</b><br>';
		 
		 while ($paramVal1==$res_pp[$groupArr[0][2]-1] && $i<$elemCnt) 		 
		 {		  
   	       
		   $paramVal2=$res_pp[$groupArr[1][2]-1];
 		   //2-уровня группировки
		   if ($groupArr[0][0]!=$groupArr[1][0] && $groupArr[1][3]==1) 
		   echo ' <span class="grN2" style="padding-left:20;">'.$groupArr[1][1].': '.color_mark($q,$res_pp[$groupArr[1][2]-1]).color_mark($q,$tw_name_add).'</span><br>'."\n";		   

   	       while ($paramVal2==$res_pp[$groupArr[1][2]-1] && $paramVal1==$res_pp[$groupArr[0][2]-1] && $i<$elemCnt)
		   {
			  $paramVal3=$res_pp[$groupArr[2][2]-1];

		   //3-уровня группировки
		   if ($groupArr[1][0]!=$groupArr[2][0] && $groupArr[2][3]==1) echo '<span class="grN3" style="padding-left:40;">'.$groupArr[2][1].': '.color_mark($q,$res_pp[$groupArr[2][2]-1]).'</span><br>'."\n";

			//печатаем заголоски столбцов таблицы
		   echo '<table border=1 cellspacing=0 cellpadding=5><tr>';
		   for ($m=0;$m<$ignCol;$m++) {if ($groupArrGlobal[$m][3]==0) echo '<td class=main width="'.setColWidth(mysql_field_len($res_PP,$m)).'">'.print_col($m+1,mysql_field_name($res_PP, $m)).'</td>'; }
		   for ($j=3;$j<mysql_num_fields($res_PP);$j++) echo '<td class=main width="'.setColWidth(mysql_field_len($res_PP,$j)).'">'.print_col($j+1,mysql_field_name($res_PP, $j)).'</td>';
		   echo '</tr>';
		   while ($paramVal3==$res_pp[$groupArr[2][2]-1] && $paramVal2==$res_pp[$groupArr[1][2]-1] && 
		   			$paramVal1==$res_pp[$groupArr[0][2]-1] && $i<$elemCnt)
		   		{
				//вывод ФИО студентов
		   		
				   echo '<tr>';
				   		//вывод неиспользуемых полей группировки в основную таблицу
						for ($m=0;$m<$ignCol;$m++) {if ($groupArrGlobal[$m][3]==0) echo '<td>&nbsp;'.$res_pp[$m].'</td>'; }
						//вывод остальных полей, кроме полей группировки
						for ($k=$ignCol;$k<mysql_num_fields($res_PP);$k++) echo '<td>&nbsp;'.$res_pp[$k].'</td>';
				   echo '</tr>'."\n";
		   		$res_pp=mysql_fetch_array($res_PP,MYSQL_NUM);
		   		$i++;
		   		}
		   	echo '</table>';		   	
		  	}		  	
		 }
	}	

//$res=mysql_query($query);
$itemCnt=getScalarVal('select count(*) from ('.$query.')t');
if (floor($itemCnt/$pageVals)==$itemCnt/$pageVals) {$pages_cnt=floor($itemCnt/$pageVals);}
 else {$pages_cnt=floor($itemCnt/$pageVals)+1;}

echo '<div align="left"> страницы ';

$add_string=reset_param_name($query_string,'page');

for ($i=1;$i<=$pages_cnt;$i++) {if ($i!=$page) {echo '<a href="?page='.$i.'&'.$add_string.'"> '.$i.' </a>';} else {echo ' <b>'.$i.'</b> ';}}
//--------------------------------------------------------
echo '<br>макс.число строк на странице:  <input type=text value="'.$pageVals.'" name="pageVals" id="pageVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pgVals(\''.reset_param_name($add_string,'pageVals').'\');" value=Ok>
	<p> Всего строк: '.$itemCnt.'</div>'; 
}
?>