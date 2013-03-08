<?php
include ('authorisation.php');
// <abarmin date="09.11.2012">
require_once("core.php");
// </abarmin>

if (isset($_GET['action']) && $_GET['action']=='del' && isset($_GET['id']) && isset($_GET['type']) && isset($_GET['kadri_id']))
{
	if ($_GET['type']=='obrazov') {
		$query='delete from obrazov where id="'.$_GET['id'].'"';
		$res=mysql_query($query);
		//echo 'query='.$query;
		header('Location:spav_other.php?type=obrazov&kadri_id='.$_GET['kadri_id'].'&action=new');}

	if ($_GET['type']=='course') {
		$query='delete from courses where id="'.$_GET['id'].'"';
		$res=mysql_query($query);
		//echo 'query='.$query;
		header('Location:spav_other.php?type=course&kadri_id='.$_GET['kadri_id'].'&action=new');
		}

	if ($_GET['type']=='kandid') {
		$query='delete from disser where id="'.$_GET['id'].'"';
		$res=mysql_query($query);
		//echo 'query='.$query;
		header('Location:spav_other.php?type=kandid&kadri_id='.$_GET['kadri_id'].'&action=new');}

	if ($_GET['type']=='doktor') {
		$query='delete from disser where id="'.$_GET['id'].'"';
		$res=mysql_query($query);
		//echo 'query='.$query;
		header('Location:spav_other.php?type=doktor&kadri_id='.$_GET['kadri_id'].'&action=new');}
} 
$bodyOnLoad=' onLoad="';//update_lect_load();"';
if ($_GET['type']=="obrazov") {$bodyOnLoad.='Click_color(\'c1\',4);"';}
    else if ($_GET['type']=="kandid") {$bodyOnLoad.='Click_color(\'c2\',4);"';}
    else if ($_GET['type']=="doktor") {$bodyOnLoad.='Click_color(\'c3\',4);"';}
	else if ($_GET['type']=="course") {$bodyOnLoad.='Click_color(\'c4\',4);"';}
    else if ($_GET['type']=="degree") {$bodyOnLoad.='Click_color(\'c5\',4);"';}

include ('master_page_short.php');
?>

<script type="text/javascript" src="scripts/calendar_init.js"></script>
<script type="text/javascript" src="scripts/jquery-textarea.js"></script>
<?php
$kadri_id=0;
$kadri_fio='не указан';
$small_img_path='/small';
$small_img_pref='sm_';
$file_type_arr=array('.jpg','.gif','.png','.zip','.pdf');	//разрешенные типы файлов прикрепления

$obrazov_path='library/anketa/obrazov';

if (isset($_GET['kadri_id']) && intval($_GET['kadri_id'])>0) {$kadri_id=$_GET['kadri_id'];}

$kadri_fio=getScalarVal('select fio from kadri where id='.$kadri_id);

echo '<h4>Анкета преподавателя (образование) <u>'.$kadri_fio.'</u></h4>';
echo '<div class=text> Перед добавлением нового значения во вкладку, 
	<b>сначала</b> нажмите кнопку "Добавить" и после ввода всех данных нажмите "Сохранить".<p>
	Для правки значений, кликните по требуему в нижней части окна, внесите требуемые изменения и нажмите "Сохранить".</div><p>';

$tables=array(2);
for ($i=0;$i<3;$i++) {$tables[$i]=array(2);}

$tables[0][0]='obrazov';          $tables[0][1]='образование';
$tables[1][0]='disser' ;          $tables[1][1]='диссертация';
$tables[2][0]='courses' ;         $tables[2][1]='курсы повышения квалификации';
//$tables[2][0]='dolgnost' ;    $tables[2][1]='должность';

if (!isset($_GET['kadri_id']) || !isset($_GET['type'])) 
{echo 'Не найден сотрудник или справочник. <a href="lect_anketa_view.php">к списку сотрудников</a>';exit;}			//type=obrazov
	//создание при необходимости папки хранения 
	if (!file_exists($obrazov_path) ) {
		if (mkdir($obrazov_path, 0777,$recursive=true)) echo '<div class=succes>папка создана: '.$obrazov_path.'</div>';
		else  echo '<div class=warning>папка не создана: '.$obrazov_path.'</div>';
		}
	if (!file_exists($obrazov_path.$small_img_path) ) {
		if (mkdir($obrazov_path.$small_img_path, 0777,$recursive=true))	echo '<div class=succes>папка иконок  создана: '.$obrazov_path.$small_img_path.'</div>';
		else echo '<div class=warning>папка не создана: '.$obrazov_path.$small_img_path.'</div>';
		}		
	if ($_GET['type']=='obrazov')  //раздел образование			//
		{
			$file_name='';
			
			// удалять файл если выбран новый (заменой) или указан признак "удалить"
			if ((isset($_FILES['obrazov_file']) && $_FILES['obrazov_file']['name']!='')||(isset($_POST['del_file_attach']) && $_POST['del_file_attach']=='on' ) )	
			{ 
			// дополнительно сужаем до действия "обновление"
			if (isset($_GET['id']) && intval($_GET['id'])>0)			
			{
			$del_f_name=getScalarVal('select file_attach from obrazov where id='.intval($_GET['id']));
			// удалить прикрепленный файл и возможную иконку, при наличии			
			delFile($obrazov_path,$del_f_name,$small_img_path);			
			}
			}
			//сохраняем прикрепленный файл
			if (isset($_FILES['obrazov_file']) && $_FILES['obrazov_file']['name']!='')	
			{ 
			    $file_name=saveFile($obrazov_path.'/',$_FILES['obrazov_file'],null,$file_type_arr,$small_img_path.'/');            
			    if ($file_name!='') echo '<div class=success> Фaйл "'.$file_name.'" успешно прикреплен.</div>';			    
			}

		if (isset($_GET['id']) && intval($_GET['id'])>0)
			{
			if (isset($_POST['elem18']) && $_POST['elem18']!='') 
			{$query='update obrazov set
			zaved_name="'.$_POST['elem18'].'",
			god_okonch="'.$_POST['elem19'].'",
			spec_name="'.$_POST['elem20'].'",
			comment="'.$_POST['elem48'].'",
				  seriya="'.$_POST['elem52'].'",
				  nomer="'.$_POST['elem53'].'",
				  kvalifik="'.$_POST['elem54'].'" 
				  '.($file_name!=""?',file_attach="'.$file_name.'"':($del_f_name!=""?',file_attach=""':'')).' 
				  where id="'.$_GET['id'].'" limit 1;';
			if ($res=mysql_query($query)) {echo '<div class=success>Данные успешно обновлены</div>';}
			}
			
			$query='select * from obrazov 
				where kadri_id="'.$_GET['kadri_id'].'" and id="'.$_GET['id'].'" order by god_okonch desc';
			$res=mysql_query($query);
			$tmpval_obraz=mysql_fetch_array($res);
			}
		
		if (isset($_GET['action']) && $_GET['action']=='new' && isset($_POST['elem18']) && $_POST['elem18']!='')
			{$query='insert into obrazov(kadri_id,obraz_type,zaved_name,god_okonch,spec_name,
			comment,seriya,nomer,kvalifik'.($file_name!=""?',file_attach':'').') 
				values("'.$_GET['kadri_id'].'","высшее","'.$_POST['elem18'].'",
				"'.$_POST['elem19'].'","'.$_POST['elem20'].'","'.$_POST['elem48'].'",
				"'.$_POST['elem52'].'","'.$_POST['elem53'].'","'.$_POST['elem54'].'"
				'.($file_name!=""?',"'.$file_name.'"':'').'
				)';
			if ($res=mysql_query($query)) {echo '<div class=success>Данные успешно добавлены</div>';}
			else {echo '<div class=warning>Данные не добавлены</div>';}
				}
		
	//echo $query;
	} 
	if ($_GET['type']=='course') 			//
		{
			$file_name='';
			$del_f_name='';
			// удалять файл если выбран новый (заменой) или указан признак "удалить"
			if ((isset($_FILES['course_file']) && $_FILES['course_file']['name']!='')||(isset($_POST['del_fa_course']) && $_POST['del_fa_course']=='on' ) )	
			{ 
			// дополнительно сужаем до действия "обновление"
			if (isset($_GET['id']) && intval($_GET['id'])>0)			
			{
			$del_f_name=getScalarVal('select file_attach from courses where id='.intval($_GET['id']));
			// удалить прикрепленный файл и возможную иконку, при наличии			
			delFile($obrazov_path,$del_f_name,$small_img_path);			
			}
			}
			//сохраняем прикрепленный файл
			if (isset($_FILES['course_file']) && $_FILES['course_file']['name']!='')	
			{ 
			    $file_name=saveFile($obrazov_path.'/',$_FILES['course_file'],null,$file_type_arr,$small_img_path.'/');            
			    if ($file_name!='') echo '<div class=success> Фaйл "'.$file_name.'" успешно прикреплен.</div>';			    
			}
		if (isset($_GET['id']) && $_GET['id']!='')
			{
			if (isset($_POST['elem43']) && $_POST['elem43']!='') 
			{
			$query='update courses set 
			name="'.$_POST['elem43'].'", 
			place="'.$_POST['elem44'].'", 
			date_start='.($_POST['elem45']==''?'NULL':'"'.DateTimeCustomConvert($_POST['elem45'],'d','rus2mysql').'"').', 
			date_end='.($_POST['elem46']==''?'NULL':'"'.DateTimeCustomConvert($_POST['elem46'],'d','rus2mysql').'"').', 
			document="'.$_POST['elem47'].'", 
			comment="'.$_POST['elem51'].'" 	
			'.($file_name!=""?',file_attach="'.$file_name.'"':($del_f_name!=""?',file_attach=""':'')).'
			where id="'.$_GET['id'].'" limit 1;';
			if ($res=mysql_query($query)) {echo '<div class=success>Данные успешно обновлены</div>';};
			//echo $query;
			}			
			$query='select * from courses 
				where kadri_id="'.$_GET['kadri_id'].'" and id="'.$_GET['id'].'"';
			$res=mysql_query($query);
			$tmpval_course=mysql_fetch_array($res);
			}
		
		if (isset($_GET['action']) && $_GET['action']=='new' && isset($_POST['elem43']) && $_POST['elem43']!='')
			{$query='insert into courses
			(kadri_id , name , place , date_start , date_end,document,comment,file_attach) 
			values(
				"'.$_GET['kadri_id'].'",
				"'.$_POST['elem43'].'",
				"'.$_POST['elem44'].'",
				'.($_POST['elem45']==''?'NULL':'"'.DateTimeCustomConvert($_POST['elem45'],'d','rus2mysql').'"').',
				'.($_POST['elem46']==''?'NULL':'"'.DateTimeCustomConvert($_POST['elem46'],'d','rus2mysql').'"').',
				"'.$_POST['elem47'].'",
				"'.$_POST['elem51'].'",
				'.($file_name!=""?'"'.$file_name.'"':'NULL').'
			)';
			if ($res=mysql_query($query)) {echo '<div class=success>Данные успешно добавлены</div>';}
			else {echo '<div class=warning>Данные не добавлены</div>';}			
				}
		
	//echo $query;
	} 
	if ($_GET['type']=='kandid') 			//
		{

		$file_name='';			
		//создание при необходимости папки хранения 
		$disser_k_path='library/anketa/kandid';
		$cur_dir_name=$disser_k_path;
		$cur_fvar_name='disser_k_file';
		$cur_fval_del_name='del_disser_k_file';
		
		
		if (!file_exists($cur_dir_name) ) {
			if (mkdir($cur_dir_name, 0777,$recursive=true))	echo '<div class=succes>папка создана: '.$cur_dir_name.'</div>';
			else echo '<div class=warning>папка не создана: '.$cur_dir_name.'</div>';			
			}
		if (!file_exists($cur_dir_name.$small_img_path) ) {
			if (mkdir($cur_dir_name.$small_img_path, 0777,$recursive=true)) echo '<div class=succes>папка иконок создана: '.$obrazov_path.$small_img_path.'</div>';
			else echo '<div class=warning>папка иконок не создана: '.$obrazov_path.$small_img_path.'</div>';
			}
		
		//сохраняем прикрепленный файл
		if ( (isset($_FILES[$cur_fvar_name]) && $cur_dir_name!='')
		    ||(isset($_POST[$cur_fval_del_name]) && $_POST[$cur_fval_del_name]=='on' ) )	
		{ 
		// дополнительно сужаем до действия "обновление"
		if (isset($_GET['id']) && intval($_GET['id'])>0)			
		{
		$del_f_name=getScalarVal('select file_attach from disser where id='.intval($_GET['id']));
		// удалить прикрепленный файл и возможную иконку, при наличии			
		delFile($cur_dir_name,$del_f_name,$small_img_path);			
		}
		}		
		
		if (isset($_FILES[$cur_fvar_name]) && $_FILES[$cur_fvar_name]['name']!='')	
		{ 
		    $file_name=saveFile($cur_dir_name.'/',$_FILES[$cur_fvar_name],null,$file_type_arr,$small_img_path.'/');            
		    if ($file_name!='') echo '<div class=success> Фaйл "'.$file_name.'" успешно прикреплен.</div>';			    
		}

			
		if (isset($_GET['id']) && $_GET['id']!='')
			{
			
			if (isset($_POST['elem21']) && $_POST['elem21']!='') 
			{$query='update disser set
			tema="'.$_POST['elem21'].'",
			spec_nom="'.$_POST['elem22'].'",
			god_zach="'.$_POST['elem23'].'",
		comment="'.$_POST['elem49'].'",
		scinceMan="'.$_POST['scinceMan'].'",
	  study_form_id="'.$_POST['study_form_id'].'",
	  science_spec_id="'.$_POST['science_spec_id'].'",
	  date_begin='.($_POST['elem58']==''?'NULL':'"'.DateTimeCustomConvert($_POST['elem58'],'d','rus2mysql').'"').', 
	  order_num_begin="'.$_POST['elem64'].'",
	  date_out='.($_POST['elem65']==''?'NULL':'"'.DateTimeCustomConvert($_POST['elem65'],'d','rus2mysql').'"').', 
	  order_num_out="'.$_POST['elem55'].'",
	  date_end='.($_POST['elem56']==''?'NULL':'"'.DateTimeCustomConvert($_POST['elem56'],'d','rus2mysql').'"').', 
	  dis_sov_date='.($_POST['elem57']==''?'NULL':'"'.DateTimeCustomConvert($_POST['elem57'],'d','rus2mysql').'"').', 
	  dis_sov_num="'.$_POST['elem59'].'",
	  vak_date='.($_POST['elem60']==''?'NULL':'"'.DateTimeCustomConvert($_POST['elem60'],'d','rus2mysql').'"').', 
	  vak_num="'.$_POST['elem61'].'",
	  doc_seriya="'.$_POST['elem62'].'",
	  doc_num="'.$_POST['elem63'].'"
	'.($file_name!=""?',file_attach="'.$file_name.'"':($del_f_name!=""?',file_attach=""':'')).' 
	   where id="'.$_GET['id'].'" limit 1;';
	   //echo $query;
			if ($res=mysql_query($query)) {echo '<div class=success>Данные кандидатской успешно обновлены</div>';};
			}
				
			$query='select * from disser 
				where kadri_id="'.$_GET['kadri_id'].'" and id="'.$_GET['id'].'"';
			$res=mysql_query($query);
			$res_edit=mysql_fetch_array($res);
			}
		
		if (isset($_GET['action']) && $_GET['action']=='new' && isset($_POST['elem21']) && $_POST['elem21']!='')
			{$query='insert into disser (kadri_id , tema , spec_nom , god_zach , disser_type,comment,scinceMan,study_form_id,science_spec_id,
			date_begin,order_num_begin,
			date_out,order_num_out,date_end,
			dis_sov_date,dis_sov_num,vak_date,vak_num,doc_seriya,doc_num
			'.($file_name!=""?',file_attach':'').')
      			values(
					"'.$_GET['kadri_id'].'",
					"'.$_POST['elem21'].'",
					"'.$_POST['elem22'].'",
				  	"'.$_POST['elem23'].'",
					"кандидат",
					"'.$_POST['elem49'].'",
					"'.$_POST['scinceMan'].'",
					"'.$_POST['study_form_id'].'",
					"'.$_POST['science_spec_id'].'",
					  '.($_POST['elem58']==''?'NULL':'"'.DateTimeCustomConvert($_POST['elem58'],'d','rus2mysql').'"').',
					  "'.$_POST['elem64'].'",
					  '.($_POST['elem65']==''?'NULL':'"'.DateTimeCustomConvert($_POST['elem65'],'d','rus2mysql').'"').',
					  "'.$_POST['elem55'].'",
					  '.($_POST['elem56']==''?'NULL':'"'.DateTimeCustomConvert($_POST['elem56'],'d','rus2mysql').'"').',
					  '.($_POST['elem57']==''?'NULL':'"'.DateTimeCustomConvert($_POST['elem57'],'d','rus2mysql').'"').',
					  "'.$_POST['elem59'].'",
					  '.($_POST['elem60']==''?'NULL':'"'.DateTimeCustomConvert($_POST['elem60'],'d','rus2mysql').'"').',
					  "'.$_POST['elem61'].'",
					  "'.$_POST['elem62'].'",
					  "'.$_POST['elem63'].'"
					  '.($file_name!=""?',"'.$file_name.'"':'').'
					  );';
					  //echo $query;
			if ($res=mysql_query($query)) {echo '<div class=success>Данные кандидатской успешно добавлены</div>';};
				}
		
	//echo $query;
	} 

	if ($_GET['type']=='doktor') 			//
		{
		if (isset($_GET['id']) && $_GET['id']!='')
			{
			if (isset($_POST['elem24']) && $_POST['elem24']!='') 
			{$query='update disser set tema="'.$_POST['elem24'].'",spec_nom="'.$_POST['elem25'].
      '",god_zach="'.$_POST['elem26'].'",comment="'.$_POST['elem50'].'" where id="'.$_GET['id'].'" limit 1;';
			if ($res=mysql_query($query)) {echo '<div class=success>Данные докторской успешно обновлены</div>';};
			}
				
			$query='select * from disser 
				where kadri_id="'.$_GET['kadri_id'].'" and id="'.$_GET['id'].'"';
			$res=mysql_query($query);
			$tmpval_doktor=mysql_fetch_array($res);
			}
		
		if (isset($_GET['action']) && $_GET['action']=='new' && isset($_POST['elem24']) && $_POST['elem24']!='')
			{$query='insert into disser (kadri_id , tema , spec_nom , god_zach , disser_type,comment)
      values("'.$_GET['kadri_id'].'","'.$_POST['elem24'].'","'.$_POST['elem25'].'","'.$_POST['elem26'].'","доктор","'.$_POST['elem50'].'");';
			if ($res=mysql_query($query)) {echo '<div class=success>Данные докторской успешно добавлены</div>';};
				}
		
	//echo $query;
	} 

?>
<script language="JavaScript">
<!--
function test_add(value2check)
{
 var valCheck='';
 //alert(value2check);
 try {valCheck=document.getElementById(value2check).value;}
 catch (e) {valCheck.document.all[valCheck].value;}
 //alert('valCheck='+valCheck);
 valCheck=valCheck.replace(/ /,'');
 if (valCheck=='') {alert('Заполните обязательное поле');}
 else {document.anket_form.submit();}
 
} 
function check_val(value_)
         {if (value_=='') {alert('Введите непустое значение...'); }
         }

function show_hide(name) {
	if (name>"") {
        // <abarmin date="09.11.2012">
        // а если двадцать закладок будет?
        for (var i = 1; i < 5; i++) {
            document.getElementById("Layer" + i).style.display = "none";
        }
        document.getElementById(name).style.display = "block";
        if (name=="Layer1") {
            anket_form.obraz_save.click();//автоклик нового элемента при заходе на вкладку
        }
        if (name=="Layer2") {
            anket_form.kandid_save.click();
        }
        if (name=="Layer3") {
            anket_form.doktor_save.click();
        }
        if (name=="Layer4") {
            anket_form.course_save.click();
        }
        // </abarmin>
	}
}
function new_elem(sprav_type,kadri_id)
{
	window.location.href='spav_other.php?type='+sprav_type+'&kadri_id='+kadri_id+'&action=new'; 	//?type=obrazov&kadri_id=29&id=12
 
 
}
function del_elem(sprav_type,id,kadri_id)
{	 
	 if (confirm('Удалить строку ?')) 
	 	{window.location.href='spav_other.php?type='+sprav_type+'&kadri_id='+kadri_id+'&id='+id+'&action=del'; }
} 
 
</script>
<script language="javaScript" src="scripts/tabs.js"></script>
<NOSCRIPT>
<h3>Для корректной работы форм ввода требуется включение JavaScript ....
<br> Дальнейшая работа невозможна. Обратитесь к администратору проекта ...<p></h3> </NOSCRIPT>

<?php
//выборка стат.информации по число элементов по каждой вкладке
$query_stat='
SELECT count(*) as cnt,"obrazov" as col_name from obrazov where kadri_id="'.$_GET['kadri_id'].'" union
SELECT count(*) as cnt,"courses" as col_name FROM courses where kadri_id="'.$_GET['kadri_id'].'" union
SELECT count(*) as cnt,"disser_k" as col_name FROM disser d where d.kadri_id="'.$_GET['kadri_id'].'" and d.disser_type="кандидат" union
SELECT count(*) as cnt,"disser_d" as col_name FROM disser where kadri_id="'.$_GET['kadri_id'].'" and disser_type="доктор" UNION
select count(*) as cnt,"degree" as col_name from disser where kadri_id='.$_GET['kadri_id'].' and disser_type = "степень"
';
//echo $query_stat;
$res_stat=mysql_query($query_stat);
$a_tmp=array();
$a_stat=array();
while ($a_tmp=mysql_fetch_array($res_stat,MYSQL_ASSOC)) 	{$a_stat[$a_tmp['col_name']]=$a_tmp['cnt'];	}

//print_r($a_stat);	/**/
?>
<table border="1" bordercolor="#FFFFFF" cellpadding="0" cellspacing="0" width=640>
  <tr bgcolor="#FFFFFF" >
    <td width=160 height=40 id="c1" onMouseOver="newColor('c1');" onMouseOut="backColor('c1');" onClick="Click_color('c1',4);">
      <div align="center"><font size="3"><b><a href="javascript:show_hide('Layer1');">Образование (<?php echo $a_stat['obrazov'];?>)</a> </b></font></div>
    </td>
      <td width=160 height=40 id="c4" onMouseOver="newColor('c4');" onMouseOut="backColor('c4');" onClick="Click_color('c4',4);">
      <div align="center"><font size="3"><b><a href="javascript:show_hide('Layer4');">Курсы повышения квалификации (<?php echo $a_stat['courses'];?>)</a> </b></font></div>
    </td>
    <td width=160 height=40 id="c2" onMouseOver="newColor('c2');" onMouseOut="backColor('c2');" onClick="Click_color('c2',4);" width="196">
      <div align="center"><font size="3"><b><a href="javascript:show_hide('Layer2');">Кандидатская диссертация (<?php echo $a_stat['disser_k'];?>)</a></b></font></div>
    </td>
    <td width=160 height=50 id="c3" onMouseOver="newColor('c3');" onMouseOut="backColor('c3');" onClick="Click_color('c3',4);" width="173">
      <div align="center"><font size="3"><b><a href="javascript:show_hide('Layer3');">Докторская диссертация (<?php echo $a_stat['disser_d'];?>)</b></font></div>
    </td>
    <td width=160 height=50 id="c5" onMouseOver="newColor('c5');" onMouseOut="backColor('c5');" onClick="Click_color('c5',4);" width="173">
        <div align="center"><font size="3"><b><a href="javascript:show_hide('Layer5');">Звание (<?php echo $a_stat['degree'];?>)</b></font></div>
    </td>
  </tr>
</table>

<form name="anket_form" action="" method="post" enctype="multipart/form-data">
    <div id="Layer1" style="display:
  <?php if (!isset($_GET['type']) or $_GET['type']=='' or $_GET['type']=='obrazov') {echo "";} else {echo "none";};?>">
    <table name=anketa cellpadding="0" cellspacing="10" bgcolor="#E6E6FF" width=640>
		<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Высшее образование:</b>&nbsp; &nbsp;</td></tr>
	
		<tr><td>ВУЗ: *  </td> <td><textarea name="elem18" cols=50 rows=3 id="elem18"><?php if (isset($tmpval_obraz)) {echo $tmpval_obraz['zaved_name'];}?></textarea></td> </tr>
		<tr><td width=200>Серия, номер диплома:  </td> <td>
			<input name="elem52" id="elem52" type=text value="<?php if (isset($tmpval_obraz)) {echo $tmpval_obraz['seriya'];}?>" size=12 maxlength="10">
			<input name="elem53" type=text value="<?php if (isset($tmpval_obraz)) {echo $tmpval_obraz['nomer'];}?>" size=12 maxlength="10">  </td> </tr>
		
		<tr><td>Квалификация:  </td> <td><textarea name="elem54" cols=50 rows=3><?php if (isset($tmpval_obraz)) {echo $tmpval_obraz['kvalifik'];}?></textarea></td> </tr>
		
		<tr><td width=200>Год окончания:  </td> <td><input name="elem19" type=text value="<?php if (isset($tmpval_obraz)) {echo $tmpval_obraz['god_okonch'];}?>" size=12 maxlength="10">  </td> </tr>
		<tr><td>Специальность в дипломе: </td><td><textarea name="elem20" cols=50 rows=3><?php if (isset($tmpval_obraz)) {echo $tmpval_obraz['spec_name'];}?></textarea>  </td> </tr>
		<tr>
			<td>прикрепленный файл:<div class=text>(<?php echo implode(', ',$file_type_arr);?>)</div></td>
			<td><input type=file name="obrazov_file" id="obrazov_file" size=60>
			 <?php			 
			if (isset($tmpval_obraz) && $tmpval_obraz['file_attach']!='')	//прикреплено фото к записи
		       {
			 echo '<div class=text>прикреплен файл <a href="'.$obrazov_path.'/'.$tmpval_obraz['file_attach'].'" target="_blank"> 
			     '.$tmpval_obraz['file_attach'].' </a>';
			 echo '<label><input type=checkbox id=del_file_attach name=del_file_attach> удалить </label></div>';
		       }
		      ?>								 
	
			</td>
		</tr>
		<tr><td>Доп.информация: </td><td><textarea name="elem48" cols=50 rows=5><?php if (isset($tmpval_obraz)) {echo $tmpval_obraz['comment'];}?></textarea>  </td> </tr>
		<tr><td><input type=button name=obraz_save value="Новая запись" onclick="javascript:new_elem('obrazov','<?php echo $_GET['kadri_id'];?>');"></td>
			<td><input type=button name=obraz_new value="Сохранить" onclick="javascript:test_add('elem18');">&nbsp;&nbsp;
				<input type=button name=obraz_new value="Удалить" <?php echo ($_GET['type']=='obrazov' && intval($_GET['id'])>0?"":"disabled title='необходимо выбрать запись для удаления'") ?>
					onclick="javascript:del_elem('obrazov','<?php echo $_GET['id'];?>','<?php echo $_GET['kadri_id'];?>');"></td></tr>	
	<?php
		 	$query='select * from obrazov where kadri_id="'.$_GET['kadri_id'].'" order by god_okonch desc' ;
		 	$res=mysql_query($query);$i=1;
		 	while ($tmpval_obraz=mysql_fetch_array($res))
		 	{
			echo '<tr><td colspan=2>
			<table><tr>
			<td><a href="spav_other.php?type=obrazov&kadri_id='.$_GET['kadri_id'].'&id='.$tmpval_obraz['id'].'" 
				style="color:grey;text-decoration:none; font-size:12pt;" title="кликните для редактирования">'.$i.
			'<br><small>ВУЗ:</small><b>'.$tmpval_obraz['zaved_name']. '</b>, 
			<small>год окончания:</small><b>'.$tmpval_obraz['god_okonch']. '</b>,
			<br><small>специальность в дипломе:</small><b>'.$tmpval_obraz['spec_name'].'</b>.
			<br><small>Доп.информация:</small><b>'.$tmpval_obraz['comment']. '</b></a></td>
			<td width="*">'.
			($tmpval_obraz['file_attach']!=''?printThrumb($tmpval_obraz['file_attach'],$obrazov_path,$small_img_path):'')
			.'</td>
			</tr></table>
			</td></tr>';
		 	$i++;
			echo '<tr><td colspan=2><hr></td></tr>';
		 	
			}				
	 ?>
</table>
</div>
    <div id="Layer2" style="display:<?php if (isset($_GET['type']) and $_GET['type']=='kandid') {echo "";} else {echo "none";}?>">
    <table name=anketa cellpadding="0" cellspacing="10" bgcolor="#E6E6FF" width=640>
		<tr><td colspan=2 align="center" valign="bottom" height="27">
			<b>Кандидатская диссертация:</b>&nbsp; &nbsp;</td></tr>
		<tr><td width=200>
			Тема: * </td> <td><textarea name="elem21" cols=50 rows=5 id="elem21"><?php if (isset($res_edit)) {echo $res_edit['tema'];}?></textarea>  </td> </tr>
		<tr><td>
			Номер специальности<br><b>(с кафедры)</b>:  </td> <td><select name="science_spec_id" id="science_spec_id" style="width:400;">
		<?php
		$listQuery="select ss.`id`,concat(ss.`name_short`,' (',IFNULL(d.`cnt`,'-'),')')as `name` from specialities_science ss left join
(select `science_spec_id` as id,count(*) as `cnt` 
from disser d where `disser_type`='кандидат' and `god_zach`>='2008' group by `science_spec_id`)d 
on ss.id=d.`id` order by 2";
		echo getFrom_ListItemValue($listQuery,'id','name','science_spec_id');
		?>
		</select>
		  </td> </tr>
		<tr><td>Форма обучения:  </td> <td><select name="study_form_id" id="study_form_id" style="width:400;">
		<?php
		$listQuery="select sf.`id`,concat(sf.`name`,' (',IFNULL(d.`cnt`,'-'),')')as `name` from study_forms sf left join
(select `study_form_id` as id,count(*) as `cnt` 
from disser d where `disser_type`='кандидат' and `god_zach`>='2008' group by `study_form_id`)d 
on sf.id=d.`id` order by 2";
		echo getFrom_ListItemValue($listQuery,'id','name','study_form_id');
		?>
		</select>
		  </td> </tr>		
		  <tr><td>Номер и наименование спец-ти по ВАК <br> <b>(вне кафедры)</b>:  </td> <td><textarea name="elem22" cols=50 rows=3><?php if (isset($res_edit)) {echo $res_edit['spec_nom'];}?></textarea>  </td> </tr>
<tr><td colspan=2 align=center><b>Обучение</b><hr></td></tr>
		  <tr><td>Приказ о зачислении:  </td> <td class=text>
		  дата <input name="elem58" id="elem58" type=text value="<?php if (isset($res_edit)) {echo f_ro(DateTimeCustomConvert(substr($res_edit['date_begin'],0,10),'d','mysql2rus'));}?>" size=12 maxlength="10">	<button type="reset" id="f_trigger_elem58">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "elem58",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_elem58",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
	номер <input name="elem64" type=text value="<?php if (isset($res_edit)) {echo $res_edit['order_num_begin'];}?>" size=12 maxlength="10">   </td> </tr>
		  <tr><td>Приказ об отчислении:  </td> <td class=text>
		  дата <input name="elem65" id="elem65" type=text value="<?php if (isset($res_edit)) {echo f_ro(DateTimeCustomConvert(substr($res_edit['date_out'],0,10),'d','mysql2rus'));}?>" size=12 maxlength="10">	
		  <button type="reset" id="f_trigger_elem65">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "elem65",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_elem65",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
	номер <input name="elem55" type=text value="<?php if (isset($res_edit)) {echo $res_edit['order_num_out'];}?>" size=12 maxlength="10">
	</td> </tr>
		  <tr><td>Окончание:  </td>
			<td class=text>дата <input name="elem56" id="elem56" type=text value="<?php if (isset($res_edit)) {echo f_ro(DateTimeCustomConvert(substr($res_edit['date_end'],0,10),'d','mysql2rus'));}?>" size=12 maxlength="10">	<button type="reset" id="f_trigger_elem56">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "elem56",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_elem56",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script></td> </tr>
	
<tr><td colspan=2 align=center><b>Защита</b><hr></td></tr>
		<tr><td>Год защиты:  </td> <td><input name="elem23" type=text value="<?php if (isset($res_edit)) {echo $res_edit['god_zach'];}?>" size=10 maxlength="4">  </td> </tr>
		<tr><td>Решение диссертационного совета:  </td> <td class=text>
		  дата <input name="elem57" id="elem57" type=text value="<?php if (isset($res_edit)) {echo f_ro(DateTimeCustomConvert(substr($res_edit['dis_sov_date'],0,10),'d','mysql2rus'));}?>" size=12 maxlength="10">	<button type="reset" id="f_trigger_elem57">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "elem57",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_elem57",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
	номер <input name="elem59" type=text value="<?php if (isset($res_edit)) {echo $res_edit['dis_sov_num'];}?>" size=12 maxlength="10">  </td> </tr>
	<tr><td>Решение ВАК:  </td> <td class=text>
		  дата <input name="elem60" id="elem60" type=text value="<?php if (isset($res_edit)) {echo f_ro(DateTimeCustomConvert(substr($res_edit['vak_date'],0,10),'d','mysql2rus'));}?>" size=12 maxlength="10">	<button type="reset" id="f_trigger_elem60">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "elem60",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_elem60",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
	номер <input name="elem61" type=text value="<?php if (isset($res_edit)) {echo $res_edit['vak_num'];}?>" size=12 maxlength="10">  </td> </tr>			
<tr><td>Свидетельство:  </td> <td class=text>
	серия <input name="elem62" type=text value="<?php if (isset($res_edit)) {echo $res_edit['doc_seriya'];}?>" size=12 maxlength="10">
	номер <input name="elem63" type=text value="<?php if (isset($res_edit)) {echo $res_edit['doc_num'];}?>" size=12 maxlength="10">  </td> </tr>			
<tr><td colspan=2 align=center><hr></td></tr>
		<tr><td>Научный руководитель <br><b>(с кафедры)</b>:  </td> <td><select name="scinceMan" id="scinceMan" style="width:400;">
		<?php
		$listQuery='select k.id,concat(k.fio," (",kadri_role(k.id,","),")") as caption 
			from kadri k order by k.fio';
		echo getFrom_ListItemValue($listQuery,'id','caption','scinceMan');
		?>
		</select>
		  </td> </tr>
		<tr>
			<td>прикрепленный файл:<div class=text>(<?php echo implode(', ',$file_type_arr);?>)</div></td>
			<td><input type=file name="disser_k_file" id="disser_k_file" size=60>
			 <?php			 
			if (isset($disser_k_path) && $res_edit['file_attach']!='')	//прикреплено фото к записи
		       {
			 echo '<div class=text>прикреплен файл <a href="'.$disser_k_path.'/'.$res_edit['file_attach'].'" target="_blank"> 
			     '.$res_edit['file_attach'].' </a>';
			 echo '<label><input type=checkbox id=del_disser_k_file name=del_disser_k_file> удалить </label></div>';
		       }
		      ?>								 
	
			</td>
		</tr>
		<tr><td>Доп.информация: </td><td><textarea name="elem49" cols=50 rows=5><?php if (isset($res_edit)) {echo $res_edit['comment'];}?></textarea>  </td> </tr>
		<tr><td><input type=button name=kandid_save value="Новая запись" onClick="javascript:new_elem('kandid','<?php echo $_GET['kadri_id'];?>');"></td>
			<td><input type=button name=obraz_new value="Сохранить" onClick="javascript:test_add('elem21');">&nbsp;&nbsp;
				<input type=button name=obraz_new value="Удалить" <?php echo ($_GET['type']=='kandid' && intval($_GET['id'])>0?"":"disabled title='необходимо выбрать запись для удаления'") ?>
					onclick="javascript:del_elem('kandid','<?php echo $_GET['id'];?>','<?php echo $_GET['kadri_id'];?>');"></td></tr>	
	<?php
		 	$query='SELECT d.*,k.fio as scinceManFio FROM disser d left join kadri k on d.scinceMan=k.id where kadri_id="'.$_GET['kadri_id'].'" and disser_type="кандидат"' ;
		 	$res=mysql_query($query);$i=1;
		 	while ($res_edit=mysql_fetch_array($res))
		 	{
			echo '<tr><td colspan=2>
			<table border=0 width=100%><tr>
			<td><a href="spav_other.php?type=kandid&kadri_id='.$_GET['kadri_id'].'&id='.$res_edit['id'].'" 
				style="color:grey;text-decoration:none; font-family:Arial; font-size:12pt;" title="кликните для редактирования">'.$i;
			echo '<br><small>Тема: </small><b>'.$res_edit['tema'].'</b>, ';
		 	echo '<br><small>Номер спец-ти по ВАК: </small><b>'.$res_edit['spec_nom']. '</b>, ';
		 	echo '<br><small>Год защиты: </small><b>'.$res_edit['god_zach'].'</b>';
		 	echo '<br><small>Научный руководитель c каф.: </small><b>'.$res_edit['scinceManFio'].'</b>';
		 	echo '<br><small>Доп.информация: </small><b>'.$res_edit['comment']. '</b></a></td>
			<td width="*" align=right>'.
			($res_edit['file_attach']!=''?printThrumb($res_edit['file_attach'],$disser_k_path,$small_img_path):'')
			.'</td>
			</tr></table>
			</td></tr>';
		 	$i++;
			echo '<tr><td colspan=2><hr></td></tr>';
		 	
			}				
	 ?>

	</table>
</div>
    <div id="Layer3" style="display:
  <?php if (isset($_GET['type']) and $_GET['type']=='doktor') {echo "";} else {echo "none";}?>">
    <table name=anketa cellpadding="0" cellspacing="10" bgcolor="#E6E6FF" width=640>
			<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Докторская диссертация:</b>&nbsp; &nbsp;</td></tr>
			<tr><td width=200>Тема: * </td> <td><textarea name="elem24" cols=50 rows=5 id="elem24"><?php if (isset($tmpval_doktor)) {echo $tmpval_doktor['tema'];}?></textarea>  </td> </tr>
			<tr><td>Номер и наименование спец-ти по ВАК:  </td> <td><textarea name="elem25" cols=50 rows=3><?php if (isset($tmpval_doktor)) {echo $tmpval_doktor['spec_nom'];}?></textarea>  </td> </tr>
			<tr><td height="27">Год защиты: </td><td height="27"><input name="elem26" type=text value="<?php if (isset($tmpval_doktor)) {echo $tmpval_doktor['god_zach'];}?>" size=10 maxlength="4">  </td> </tr>
			<tr><td>Доп.информация: </td><td><textarea name="elem50" cols=50 rows=5><?php if (isset($tmpval_doktor)) {echo $tmpval_doktor['comment'];}?></textarea>  </td> </tr>
			<tr><td><input type=button name=doktor_save value="Новая запись" onclick="javascript:new_elem('doktor','<?php echo $_GET['kadri_id'];?>');"></td>
				<td><input type=button name=course_new value="Сохранить" onClick="javascript:test_add('elem24');">&nbsp;&nbsp;
					<input type=button name=course_del value="Удалить" <?php echo ($_GET['type']=='doktor' && intval($_GET['id'])>0?"":"disabled title='необходимо выбрать запись для удаления'") ?> 
						onclick="javascript:del_elem('doktor','<?php echo $_GET['id'];?>','<?php echo $_GET['kadri_id'];?>');"></td></tr>	
	<?php
		 	$query='SELECT * FROM disser where kadri_id="'.$_GET['kadri_id'].'" and disser_type="доктор"' ;
		 	$res=mysql_query($query);$i=1;
		 	while ($tmpval_doktor=mysql_fetch_array($res))
		 	{
			echo '<tr><td colspan=2><a href="spav_other.php?type=doktor&kadri_id='.$_GET['kadri_id'].'&id='.$tmpval_doktor['id'].'" 
				style="color:grey;text-decoration:none; font-family:Arial; font-size:12pt;" title="кликните для редактирования">'.$i;
			echo '<br><small>Тема: </small><b>'.$tmpval_doktor['tema']. '</b>, ';
		 	echo '<br><small>Номер спец-ти по ВАК: </small><b>'.$tmpval_doktor['spec_nom']. '</b>, ';
		 	echo '<br><small>Год защиты: </small><b>'.$tmpval_doktor['god_zach'].'</b>';
		 	echo '<br><small>Доп.информация: </small><b>'.$tmpval_doktor['comment']. '</b></a></td></tr>';
		 	$i++;
			echo '<tr><td colspan=2><hr></td></tr>';
		 	
			}				
	 ?>
	</table>
</div>
    <div id="Layer4" style="display:
  <?php if (isset($_GET['type']) and $_GET['type']=='course') {echo "";} else {echo "none";}?>">

    <table name=anketa cellpadding="0" cellspacing="10" bgcolor="#E6E6FF" width=640>
			<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Курсы повышения квалификации:</b>&nbsp; &nbsp;</td></tr>
			<tr><td width=200>Название курсов *  </td> <td><textarea name="elem43" id="elem43" cols=50 rows=3><?php if (isset($tmpval_course)) {echo $tmpval_course['name'];}?></textarea>  </td> </tr>
			<tr><td>Место проведения  </td> <td><textarea name="elem44" id=elem44 cols=50 rows=3><?php if (isset($tmpval_course)) {echo $tmpval_course['place'];}?></textarea>  </td> </tr>
			<tr><td>Дата проведения </td><td class=text>начало <input name="elem45" id="elem45" type=text value="<?php if (isset($tmpval_course)) {echo DateTimeCustomConvert($tmpval_course['date_start'],'d','mysql2rus');}?>" size=12 maxlength="10">	<button type="reset" id="f_trigger_elem45">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "elem45",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_elem45",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
,
					окончание <input name="elem46" id="elem46" size=12 maxlength="10" type=text value="<?php if (isset($tmpval_course)) {echo DateTimeCustomConvert($tmpval_course['date_end'],'d','mysql2rus');}?>" size=60> <button type="reset" id="f_trigger_elem46">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "elem46",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_elem46",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
	  </td> </tr>
			<tr><td>Документ по завершении:  </td> <td><textarea name="elem47" id=elem47 cols=50 rows=3><?php if (isset($tmpval_course)) {echo $tmpval_course['document'];}?></textarea>
			
			</td> </tr>
			<tr>
				<td>прикрепленный файл:<div class=text>(<?php echo implode(', ',$file_type_arr);?>)</div></td>
				<td><input type=file name="course_file" id="course_file" size=60>
				 <?php			 
				if (isset($tmpval_course) && $tmpval_course['file_attach']!='')	//прикреплено фото к записи
			       {
				 echo '<div class=text>прикреплен файл <a href="'.$obrazov_path.'/'.$tmpval_course['file_attach'].'" target="_blank"> 
				     '.file_type_img($tmpval_course['file_attach'],$strMode=true) .' </a>';
				 echo '<label><input type=checkbox id=del_fa_course name=del_fa_course> удалить </label></div>';
			       }
			      ?>								 
		
				</td>
			</tr>			
			<tr><td>Доп.информация: </td><td><textarea name="elem51" cols=50 rows=5><?php if (isset($tmpval_course)) {echo $tmpval_course['comment'];}?></textarea>  </td> </tr>
			<tr><td><input type=button name=course_save value="Новая запись" onclick="javascript:new_elem('course','<?php echo $_GET['kadri_id'];?>');"></td>
				<td><input type=button name=course_new value="Сохранить" onClick="javascript:test_add('elem43');">&nbsp;&nbsp;
					<input type=button name=course_del value="Удалить"  <?php echo ($_GET['type']=='course' && intval($_GET['id'])>0?"":"disabled title='необходимо выбрать запись для удаления'") ?> 
						onclick="javascript:del_elem('course','<?php echo $_GET['id'];?>','<?php echo $_GET['kadri_id'];?>');"></td></tr>	

	<?php
		 	$query='SELECT id, name , place , date_start , date_end , document , comment,file_attach FROM courses where kadri_id="'.$_GET['kadri_id'].'" order by date_end desc' ;
		 	$res=mysql_query($query);$i=1;
		 	while ($tmpval_obraz=mysql_fetch_array($res))
		 	{
			echo '<tr><td colspan=2><a href="spav_other.php?type=course&kadri_id='.$_GET['kadri_id'].'&id='.$tmpval_obraz['id'].'" 
				style="color:grey;text-decoration:none; font-family:Arial; font-size:12pt;" title="кликните для редактирования">'.$i;
			if ($tmpval_obraz['name']!='') echo '<br><small>Название курсов: </small><b>'.$tmpval_obraz['name']. '</b>, ';
		 	if ($tmpval_obraz['place']!='') echo '<br><small>Место проведения: </small><b>'.$tmpval_obraz['place']. '</b>, ';
		 	if ($tmpval_obraz['date_start']!='') echo '<br>Время проведения: <small>начало <b>'.DateTimeCustomConvert($tmpval_obraz['date_start'],'d','mysql2rus').'</b>, окончание <b>'.DateTimeCustomConvert($tmpval_obraz['date_end'],'d','mysql2rus').'</b></small>.';
		 	if ($tmpval_obraz['document']!='') echo '<br><small>Документ по завершении: </small><b>'.$tmpval_obraz['document'].'</b>';
			if ($tmpval_obraz['file_attach']!='') echo ' (<a href="'.$obrazov_path.'/'.$tmpval_obraz['file_attach'].'" target="_blank" title="прикрепленный файл"> 
				     '.file_type_img($tmpval_obraz['file_attach'],$strMode=true).'</a>)';
			
		 	if ($tmpval_obraz['comment']!='') echo '<br><small>Доп.информация: </small><b>'.$tmpval_obraz['comment']. '</b></a></td></tr>';
		 	$i++;
			echo '<tr><td colspan=2><hr></td></tr>';
		 	
			}				
	 ?>

	</table>
	<script type="text/javascript">		
		$("#elem43").maxlength({ maxChars: 500 });
		$("#elem44").maxlength({ maxChars: 500 });
		$("#elem47").maxlength({ maxChars: 500 });
	</script>
</div>
    <div id="Layer5" style="display: <?php if(array_key_exists("type", $_GET)) { if ($_GET['type'] == "degree") { echo "block"; } else { echo "none"; }} else { echo "none"; } ?>">
        <?php
            // если передан id просматриваемой ученой степени, то грузим ее в модель
            $degree = new CDegree();
            $person = new CPerson();
            if (CRequest::getInt("id") !== 0) {
                if (!is_null(CStaffManager::getDegree(CRequest::getInt("id")))) {
                    $degree = CStaffManager::getDegree(CRequest::getInt("id"));
                }
            }
            if (CRequest::getInt("kadri_id") !== 0) {
                $person = CStaffManager::getPerson(CRequest::getInt("kadri_id"));
                $degree->person_id = $person->getId();
            }
            // обработка пользовательских действий
            if (array_key_exists("action", $_POST)) {
                if (array_key_exists("save", $_POST['action'])) {
                    $degree->disser_type = "степень";
                    $degree->setAttributes(CRequest::getArray(CDegree::getClassName()));
                    if ($degree->subject != "") {
                        $degree->save();
                    }
                } elseif (array_key_exists("remove", $_POST['action'])) {
                    $degree->remove();
                }
            }
        ?>
        <table name=anketa cellpadding="0" cellspacing="10" bgcolor="#E6E6FF" width=640>
            <tr>
                <td>Звание:</td>
                <td>
                    <?php CHtml::activeDropDownList("degree_id", $degree, CTaxonomyManager::getCacheTitles()->getItems()); ?>
                </td>
            </tr>
            <tr>
                <td>Область знания:</td>
                <td>
                    <?php CHtml::activeTextBox("subject", $degree, "", "", 'rows="3" cols="50"'); ?>
                </td>
            </tr>
            <tr>
                <td valign="top">Год присвоения:</td>
                <td>
                    <?php CHtml::activeTextField("year", $degree, "degree_year"); ?>
                    <button type="reset" id="degree_year_selector">...</button>
                    <script type="text/javascript">
                        Calendar.setup({
                            inputField     :    "degree_year",      // id of the input field
                            ifFormat       :    "%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
                            showsTime      :    false,            // will display a time selector
                            button         :    "degree_year_selector",   // trigger for the calendar (button ID)
                            singleClick    :    true,           // double-click mode false
                            step           :    1                // show all years in drop-down boxes (instead of every other year as default)
                        });
                    </script>
                </td>
            </tr>
            <tr>
                <td valign="top">Решение совета:</td>
                <td>
                    <p>
                        Дата: <?php CHtml::activeTextField("decision_date", $degree, "decision_date"); ?>
                        <button type="reset" id="decision_date_selector">...</button>
                        <script type="text/javascript">
                            Calendar.setup({
                                inputField     :    "decision_date",      // id of the input field
                                ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
                                showsTime      :    false,            // will display a time selector
                                button         :    "decision_date_selector",   // trigger for the calendar (button ID)
                                singleClick    :    true,           // double-click mode false
                                step           :    1                // show all years in drop-down boxes (instead of every other year as default)
                            });
                        </script>
                    </p>
                    <p>Номер: <?php CHtml::activeTextField("decision_num", $degree); ?></p>
                </td>
            </tr>
            <tr>
                <td valign="top">Свидетельство:</td>
                <td>
                    <p>Серия: <?php CHtml::activeTextField("doc_series", $degree); ?></p>
                    <p>Номер: <?php CHtml::activeTextField("doc_num", $degree); ?></p>
                </td>
            </tr>
            <tr>
                <td>Примечание:</td>
                <td>
                    <?php CHtml::activeTextBox("comment", $degree, "", "", 'rows="3" cols="50"'); ?>
                </td>
            </tr>
            <tr>
                <td>
                    Прикрепленный файл:
                    <div class=text>(<?php echo implode(', ',$file_type_arr);?>)</div>
                </td>
                <td>
                    <?php CHtml::activeUpload("file", $degree); ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php CHtml::activeHiddenField("id", $degree); ?>
                    <?php CHtml::activeHiddenField("person_id", $degree); ?>
                    <input type="hidden" name="type" action="degree">
                    <input type="submit" name="action[save]" value="Сохранить">
                    <input type="submit" name="action[remove]" value="Удалить">
                </td>
            </tr>
            <?php if (!is_null($person)) : ?>
            <tr>
                <td colspan="2"><hr></td>
            </tr>
                <?php foreach ($person->degrees->getItems() as $degree) : ?>
                <tr>
                    <td>Ученая степень</td>
                    <td><?php echo $degree->degree->getValue(); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td><a href="spav_other.php?kadri_id=<?php echo $person->getId(); ?>&id=<?php echo $degree->getId(); ?>&type=degree"><?php echo $degree->subject; ?></a></td>
                </tr>
                <tr>
                    <td>Год присвоения</td>
                    <td><?php echo $degree->year; ?></td>
                </tr>
                <?php if ($degree->file != "") : ?>
                    <tr>
                        <td>Вложение</td>
                        <td><a href="<?php echo WEB_ROOT."library/anketa/kandid/".$degree->file; ?>"><img src="<?php
                            echo CUtils::getFileMimeIcon(CORE_CWD."/library/anketa/kandid/".$degree->file);
                            ?>"></a></td>
                    </tr>
                <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
<div class=text>
<strong>Примечание</strong>
<ul>
	<li>для редактирования записи Выберите ее в нижней части страницы, кликнув по текстовой части</li>
	<li>удаление записи осуществляется через выборр аналогично редактированию и нажатию кнопки "Удалить"</li>
</ul>			
</div>
			

<p>&nbsp;</p><a href="lect_anketa.php?kadri_id=<?php echo $_GET['kadri_id']; ?>&action=update">Вернуться к всей анкете...</a>
</form>


<?php include('footer.php'); ?>