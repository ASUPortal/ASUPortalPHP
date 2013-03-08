<?php
include ('authorisation.php');

$item_id=0;
$query_string=$_SERVER['QUERY_STRING'];
$sql_lect_filtr='';
$img_path='images/news/';   //путь к прикрепленным фото
$file_path='news/attachement/';  //путь к прикрепленным файлам

if ($view_all_mode!==true) 
	{$sql_lect_filtr=' and user_id_insert="'.$_SESSION['id'].'" ';}

if (isset($_GET['item_id']) && $_GET['item_id']>0) {$item_id=intval($_GET['item_id']);}

if (isset($_GET['type']) && $_GET['type']=='del' && $item_id>0)
	{
	$filesArr=getRowSqlVar('select image,file_attach from news where id="'.$item_id.'"'.$sql_lect_filtr);
        if ($filesArr[0]['image']!='')  //удаляем файл фото
            unlink($img_path.$filesArr[0]['image']);
        
        if ($filesArr[0]['file_attach']!='')  //удаляем прикрепленный файл
            unlink($file_path.$filesArr[0]['file_attach']);
        
        $query='delete from news where id="'.$item_id.'"'.$sql_lect_filtr;	
	
	//echo $query;
	if ($write_mode===true) {
		$res=mysql_query($query);	
		header('Location:?'.reset_param_name_ARR($query_string,array(type,item_id)));
		}
	}
//------------------------------------------------------
 include ('master_page_short.php');

//$head_title='Новости';
?>
<script type="text/javascript" src="scripts/calendar_init.js"></script>
<script language="Javascript">
var main_page='<?php echo $main_page;?>';	//for redirect & links
function del_confirm(id,num)
{
	 if (confirm('Удалить запись: '+num+' ?')) 
	 	{window.location.href=main_page+'?item_id='+id+'&type=del&'+'<?php echo $_SERVER["QUERY_STRING"];?>';} 
}
function check_form()	//проверка обязательных полей при отправке формы
{
var err=false;
var date_act=document.getElementById('date_time');

if (date_check(date_act.value)) 
	{
	 err=true;
	 alert('Дата не существует. воспользуйтесь календарем;');
	}
else {
 	a = new Array(
	 	new Array('title',''),
	 	new Array('file','')
	);
	requireFieldCheck(a,'form1');
	
	}

}

function go2search(search_path)
{
 	var search_query=document.getElementById('q').value;
 	var href_addr='';
 	
 	if (search_query!='') {href_addr='q='+search_query;
		if (search_path=='archiv') {href_addr='archiv&'+href_addr+'';}
	 	window.location.href=main_page+'?'+href_addr;
	 }
 	else {alert('Введите строку поиска');}
} 
</script>
<?php

include 'editor-var.php';	      //панель инструментов для форматирования текста Объявления

$item4pg=15;	//число записей на странице

$main_page=$curpage;//'diploms_view.php';
$page=1;
$kadri_id='';		//отбор по руководителю диплома
$q='';			//строка поиска
$pgVals=20;	//число тем на странице по умолчанию
$sort=4;
$filt_str_display='';	//отражение по чему идет отбор
$err=false;		//нет ошибок при изменении БД
$stype='desc';		//тип сортировки столбца
$item_id=0;
$img_path='images/news/';   //путь к прикрепленным фото
$file_path='news/attachement/';  //путь к прикрепленным файлам

$query_string=$_SERVER['QUERY_STRING'];

if (isset($_GET['sort'])) {$sort=intval($_GET['sort']);}

if (isset($_GET['q'])) {$q=$_GET['q'];$filt_str_display=$filt_str_display.'  поиску;';}

if (isset($_GET['page']) && $_GET['page']>1) {$page=intval($_GET['page']);$filt_str_display=$filt_str_display.'  странице;';}
if (isset($_GET['item_id']) && $_GET['item_id']>1) {$item_id=intval($_GET['item_id']);}

if (isset($_GET['pgVals']) && $_GET['pgVals']<=99 && $_GET['pgVals']>=1) {$pgVals=intval($_GET['pgVals']);$filt_str_display=$filt_str_display.' числу записей;';}

if (isset($_GET['stype']) && ($_GET['stype']=='desc' || $_GET['stype']=='asc')) {$stype=$_GET['stype'];}

/**
 * $query_all='SELECT time_intervals.name as year_name, time_intervals.date_start,time_intervals.date_end
 *	FROM settings inner join time_intervals on time_intervals.id=settings.year_id
 *	where 1 limit 0,1';
 * if ( $res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {$def_settings=mysql_fetch_array($res_all);}
 *
 * Переписано для использования новой системы глобальных настроек
 */
$query_all = "
    select
        time_intervals.name as year_name,
        time_intervals.date_start,
        time_intervals.date_end,
        time_intervals.id as year_id
    from
        time_intervals
    where
        time_intervals.id = ".CUtils::getCurrentYear()->getId();
if ($res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {
    $def_settings=mysql_fetch_array($res_all);
}

$sql_lect_filtr='';
if ($view_all_mode!==true) 
	{$sql_lect_filtr=' and n.user_id_insert="'.$_SESSION['id'].'" ';}
//-----------------------------------------------
            
//-----------------------------------------------
  echo '<span class="main">'.$pg_title.'</span> ';

if (isset($_POST['title']))
{
	if (trim($_POST['title'])!='' && trim($_POST['file'])!='' && trim($_POST['date_time'])!="") 
	{
            $image_name='';
            $file_name='';
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}

        if (isset($_GET['type']) && ($_GET['type']=='edit' || $_GET['type']=='add')
                && (isset($_FILES['loadimage']) || isset($_FILES['loadfile'])))
        {   //сохраняем файлы новости
///////////////////////////////////
            //сохраняем прикрепленное фото
            $image_name=saveFile($img_path,$_FILES['loadimage'],null,array('.jpg','.gif'),"small/sm_");
            if ($image_name!='') echo '<div class=success> Фото "'.$image_name.'" успешно прикреплено.</div>';
            
            //сохраняем прикрепленный файл
            $file_name=saveFile($file_path,$_FILES['loadfile'],null,null,null);            
            if ($file_name!='') echo '<div class=success> Фaйл "'.$file_name.'" успешно прикреплен.</div>';
/////////////////////////////////////////
        }
	 //обновление записи
	 if (isset($_GET['type']) && $_GET['type']=='edit' && $item_id>0) {
		  
		 if (isset($_POST['delfile']) && $_POST['delfile']=='on')   //удаляем файл
                 {
                    $file_name='';
                    $prev_file_name=getScalarVal('select file_attach from news where id='.$item_id);
                    if ($prev_file_name!='' && file_exists($file_path.$prev_file_name)) unlink($file_path.$prev_file_name);
                 }
		 if (isset($_POST['delimage']) && $_POST['delimage']=='on')   //удаляем фото
                 {
                    $image_name='';
                    $prev_image_name=getScalarVal('select image from news where id='.$item_id);
                    if ($prev_file_name!='' && file_exists($img_path.$prev_image_name)) unlink($img_path.$prev_image_name);
                 }
                 
                 $query="update news set title='".f_ri($_POST["title"])."',file='".f_ri($_POST["file"])."',
                    user_id_update='".$_SESSION["id"]."'".
                    echoIf($_FILES['loadimage']['name']!='' || $_POST['delimage']=='on',", image='".f_ri($image_name)."'","").
                    echoIf($_FILES['loadfile']['name']!='' || $_POST['delfile']=='on',", file_attach='".f_ri($file_name)."'","").
		    ", date_time='".DateTimeCustomConvert($_POST["date_time"].' '.date("H:i:s"),'dt','rus2mysql')."'".
                    " where id='".$item_id."'";
                 //echo $query;
		 if ($res=mysql_query($query)) {
		  	echo '<div class=success> Запись обновлена  успешно.'.$onEditRemain_text.'</div><br>';
		 				}
		 else {echo '<div class="warning">Запись не обновлена .<p>&nbsp;</div>';$err=true;}
		 //echo $query;
	 }
	 else
	 if (isset($_GET['type']) && $_GET['type']=='add' && intval($_SESSION["id"])>0) {	
	 //добавление новой записи
		  	  $news_type='';
			  if ($_SESSION['userType']=='преподаватель') $news_type='notice';
			  
			  $query="insert into news (title,file,image,file_attach,date_time,user_id_insert,news_type) 
	  	values ('".f_ri($_POST["title"])."','".f_ri($_POST["file"])."','".f_ri($image_name)."','".f_ri($file_name)."',
		  '".DateTimeCustomConvert($_POST["date_time"].' '.date("H:i:s"),'dt','rus2mysql')."','".intval($_SESSION["id"])."','".f_ri($news_type)."')";
		  	//echo $query;

                        if (mysql_query($query))  {
		  	echo '<div class=success> Запись "'.$_POST['title'].'" добавлена успешно.'.$onEditRemain_text.'</div><br>'; 
			}
		 else {echo '<div class="warning">Запись не добавлена. </div><br>';$err=true;}
	 }
	 
	}
	else {echo '<div class="warning">Часть обязательных данных не заполнено .<br>&nbsp;</div>';$err=true;}
if (!$err && !$onEditRemain) {
 	$query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
 	echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.$query_string.'\"",2000);</script>';}	
}
if (isset($_GET['type']) && $_GET['type']=='edit')
{
	if ($item_id>0)
	{//echo '<h4>Правка темы</h4>';
	$query="select * from news where id='".$item_id."'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_assoc($res);
	}
	else {echo '<h4 class="warning">не выбрана Запись для правки</h4>';}	
}


  if (isset($_GET['type']) && ($_GET['type']=='add' || $_GET['type']=='edit'))
   {
    ?>
	<span class="text"><?php if(isset($_GET['type']) && $_GET['type']=='edit') {echo 'Правка';} else {echo 'Ввод';} ?> записи</span> 
	<div class="middle_lite" > <a href="admin_news.php?<?php echo reset_param_name_ARR($query_string,array('type','item_id')); ?>">К списку </a></div>
	
    <form action="" method="post" enctype="multipart/form-data" name="form1" id=form1>
	<table class="text" border=0>
		<tr>
			<td>Дата новости <span class=warning>*</span> (в формате дд.мм.гггг)</td>
			<td>
			<input maxlength="10" type="text" style="width:100;" name="date_time" id="date_time" value="<?php echo echoIf(getFormItemValue('date_time')!='',DateTimeCustomConvert(substr(getFormItemValue('date_time'),0,10),'d','mysql2rus'),date("d.m.Y"));?>" > 
			<button type="reset" id="f_trigger_date_time">...</button>
			<script type="text/javascript">
		    Calendar.setup({
		        inputField     :    "date_time",      // id of the input field
		        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
		        showsTime      :    false,            // will display a time selector
		        button         :    "f_trigger_date_time",   // trigger for the calendar (button ID)
		        singleClick    :    true,           // double-click mode false
		        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
		    });
			</script>
			</td>
		</tr>
		<tr>
			<td>Введите заголовок для новости <span class=warning>*</span></td>
			<td><input type="text" name="title" id=title class="text2" size="50" title="заголовок для новости" value="<?php echo getFormItemValue('title');?>"></td>
		</tr>
	</table>	
    <div class=text>
	Введите текст новости <span class=warning>*</span><br><?php echo $edit_table;?>
    <textarea name="file" id=file title="текст новости" rows="10" cols="60" class="text2"><?php echo getFormItemValue('file'); ?></textarea><br><br>
	
	Прикрепить фото к новости (только *.jpg или *.gif)<br>
    <input type="file" name="loadimage" id=loadimage class="text2" size="50">
 <?php
   if ($res_edit['image']!='')	//прикреплено фото к записи
  {
    echo 'прикреплено фото <a href="'.$img_path.$res_edit['image'].'" target="_blank">
        <img height=60 src="'.$img_path.$res_edit['image'].'" border=0></a>';
    echo '<label><input type=checkbox id=delimage name=delimage> удалить </label><br>';
  }
 ?>								 
	
	<div>Прикрепить файл к новости ( не более <b><?php echo $upload_max_filesize;?></b> МБ)</div>
    <input type="file" name="loadfile" id=loadfile class="text2" size="50">
 <?php
   if ($res_edit['file_attach']!='')	//прикреплено фото к записи
  {
    echo 'прикреплен файл <a href="'.$file_path.$res_edit['file_attach'].'" target="_blank">';
        file_type_img($res_edit['file_attach']);
        echo '</a>';
    echo '<label><input type=checkbox id=delfile name=delfile> удалить </label><br>';
   
  }
 ?>								 
    <br><br>
    <input type="reset" value="Очистить" class="button">&nbsp;&nbsp;&nbsp;
    <input type="button" name="gotoinsert" value="Сохранить" class="button" onclick="javascript:check_form();">
    </div>
	</form>
	
	<?php
    
    }
    else //списочная таблица
    {
		if (!isset($_GET['archiv'])) {
			$query_='select count(*) from `news` n where (n.date_time<"'.$def_settings['date_start'].'" or n.date_time is NULL) ';
			$query_.=$sql_lect_filtr;	//ограничения на число записей преподавателя, видит только персональный новости
			$archiv_cnt=intval(getScalarVal($query_),10);
			
			echo '<a href="?'.$query_string.'&archiv" title="записи учебных лет">архив записей: '.$archiv_cnt.'</a><br>';
		}
		else {
			$query_='select count(*) from `news` n where n.date_time>="'.$def_settings['date_start'].'" ';
			$query_.=$sql_lect_filtr;
			$cur_cnt=intval(getScalarVal($query_),10);
	 		echo '<a href="?'.reset_param_name(reset_param_name($query_string,'archiv'),'page').'" 
			 title="записи прошлых учебных лет">записи текущего учебного года: '.$cur_cnt.'</a><br>';}
		
		$archiv_query=' and n.date_time>"'.$def_settings['date_start'].'"';
		if (isset($_GET['archiv'])) {$archiv_query=' and (n.date_time<"'.$def_settings['date_start'].'" or n.date_time is NULL)';}
		
		$search_query='';
		if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b></div><br>';
		$search_query=' and (convert(n.title USING utf8) like "%'.$q.'%" or 
							convert(n.file USING utf8) like "%'.$q.'%" or 
							convert(u.fio_short USING utf8) like "%'.$q.'%" or 
							n.date_time like "%'.$q.'%" or n.date_time like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%" or
							convert(n.comment USING utf8) like "%'.$q.'%")';}
		
	$table_headers=array(
		1=>array('тема','200'),
		2=>array('фото','10'),
		3=>array('файл','100'),
		4=>array('дата','100')
		);
	if ($view_all_mode===true) {array_push($table_headers,array('автор','100'));}
	
	$def_sort=1;
	if ($sort<1 && $sort>=cont($table_headers))  {$sort=$def_sort;}
	     
	//выборка для показа списочной таблицы записей
	
	$query='SELECT n.title,n.image,n.file_attach,n.date_time,u.fio_short,n.comment,SUBSTR(n.`file`,1,100) as `file`, n.id,n.user_id_insert
	FROM news n
			left join users u on u.id=n.user_id_insert	';
	
	$search_query.=$sql_lect_filtr;
	
	$query=$query." where 1 ".$archiv_query."".$search_query." order by ".$sort." ".$stype." ";
		
	$res=mysql_query($query.'limit '.(($page-1)*$pgVals).','.$pgVals);
	//echo $query;
	
	if (!isset($_GET['save']) && !isset($_GET['print'])) {
		echo '<p class="notinfo"><a href="?type=add&'.$_SERVER["QUERY_STRING"].'"> Добавить</a><p>';
	if ($filt_str_display!='') {echo '<div class=text>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; <a href="?'.reset_param_name_ARR($query_string,array('kadri_id','page','q')).'"> сбросить фильтр </a></div>';}
	}
	if (mysql_num_rows($res)==0) {
		 /*
		 echo '<p class=warning style="width:80%;text-align:center;">В текущем учебном году дипломных проектов не обнаружено. Возможно не указана предполагаемая дата защиты дипломного проекта, либо она раньше <u>'.DateTimeCustomConvert(substr($def_settings['date_start'],0,10),'d','mysql2rus').'</u>. Вы можете ознакомиться с архивом из <u>'.$archiv_cnt.'</u> проектов</p>';*/
		if (!isset($_GET['archiv'])) echo '<p class=warning style="font-size:12pt; text-align:center;">в текущем году записей не найдено, попробуйте поискать в  <a href="?'.reset_param_name($query_string,'archiv').'&archiv">архиве</a> ';
		else echo '<p class=warning style="font-size:12pt; text-align:center;">в архиве записей не найдено, попробуйте поискать в  
			<a href="?'.reset_param_name($query_string,'archiv').'">текущем году</a> ';
		if ($search_query!='') echo ', либо <a href="?">сбросить фильтр</a>';
		echo '</p>';
		 
	 }
	else
		{
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		//-------------------------------------  списочная таблица -----------------------------------------------------	
			echo '<table width=99% class="notinfo" border=0><tr>';	
			echo '<td align=left colspan=2>';

            $add_string = "";
			$add_string=reset_param_name($add_string,'page');	//для перехода к первой странице сортировки по преп-лю
			
				echo showPrintSaveOpt('print&doc',$_SERVER['QUERY_STRING'],'');
				echo ' </td> 
			<td align=right><input type=text name="q" id="q" width=50 value=""> &nbsp; 
				<input type=button value="Найти" title="поиск проводится в текущем разделе (архив или тек.уч.год) выбором соот.ссылки" OnClick=javascript:go2search(\'';
					if (isset($_GET['archiv'])) {echo 'archiv';}
				echo '\');>
				<div class=text style="text-align:right"> кроме полей: файл, фото. <br>
				Поиск по дате в формате дд.мм.гггг или гггг-мм-дд</div></td>
			</tr></table>';}	
//-----------------------------------------------------
			$itemCnt=getScalarVal('select count(*) from ('.$query.')t');
			
			if (floor($itemCnt/$pgVals)==$itemCnt/$pgVals) {$pages_cnt=floor($itemCnt/$pgVals);}
			else {$pages_cnt=($itemCnt/$pgVals)+1;}
			
			$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;
			
			echo '<div align=center>страницы '.getPagenumList($pages_cnt,$page,6,'page',$add_string,'').'</div>';
			//-----------------------------------------------------
			echo '<form name=order_list>
			<table name=tab1 border=1 cellpadding="0" cellspacing="0" width="99%" class=text>
			<tr align="center" class="title" height="30">';
				if (!isset($_GET['save']) && !isset($_GET['print'])) {
					echo '<td width="50" class="notinfo"><img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';}
			
			//echo $query;
			
			$add_string=reset_param_name($query_string,'sort');
				
			//------------------------------------------- шапка списочной таблицы -начало-----------------------------------------------------
				echo '<td width="50">№</td>';
			
				for ($i=1;$i<=count($table_headers);$i++)
				{
					echo '<td width="'.$table_headers[$i][1].'">'.print_col($i,$table_headers[$i][0]).'</td>';
				}
				if (!isset($_GET['save']) && !isset($_GET['print'])) 
					{echo '<td width="100" class="notinfo">комментарий</td>';}
			//------------------------------------------- шапка списочной таблицы -конец-----------------------------------------------------
				$bgcolor='';	
				if (!isset($_GET['save']) && !isset($_GET['print'])) {$bgcolor=' bgcolor="#DFEFFF"';};
				$i=0;
				while ($tmpval=mysql_fetch_array($res))	//вывод показателей
				{
					$sum=0;
					echo '<tr align="left" class="text" '.$bgcolor.' valign="top">';
					if (!isset($_GET['save']) && !isset($_GET['print'])) {
					  echo '<td align="center"> '.
					  	($write_mode && ($view_all_mode || intval($tmpval['user_id_insert'])== intval($_SESSION["id"]))?
						'<a href="javascript:del_confirm(\''.f_ro($tmpval['id']).'\',\''.str_replace(" ","_",f_ro($tmpval['title'])).'\');" title="Удалить">
						<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
						<a href="?item_id='.$tmpval['id'].'&type=edit&'.$query_string.'" title="Правка">
						<img src="images/toupdate.png" alt="Правка" border="0"></a>':'').
						'</td>';}
					$i++;
					echo '<td>&nbsp;'.($i+($page-1)*$pgVals).'</td>';
					echo '<td>&nbsp;<a href="" title="'.htmlspecialchars(substr($tmpval['file'],0,100)).'...">'.color_mark($q,$tmpval['title']).'</a></td>';
					echo '<td>&nbsp;'.echoIf($tmpval['image']!='','<a href="'.$img_path.$tmpval['image'].'" title="открыть файл" target=_blank>
                                                                 <img src="'.$img_path.'small/sm_'.$tmpval['image'].'" border=0 height=20></a>','').'</td>';
					echo '<td>&nbsp;';
                                        if ($tmpval['file_attach']!='') {
                                            echo '<a href="'.$file_path.$tmpval['file_attach'].'" title="открыть файл">';
                                            file_type_img($tmpval['file_attach']);
                                            echo '</a>'; }
                                        echo '</td>';//
					
					$date_act=$tmpval['date_time'];
					//$date_act=date("d.m.Y H:i:s",strtotime($tmpval['date_act']));
					$date_act=substr($date_act,0,10);
					$date_act=DateTimeCustomConvert($date_act,'d','mysql2rus');
					echo '<td>&nbsp;'.color_mark($q,$date_act).'</td>';

					if ($view_all_mode===true) echo '<td>&nbsp;<a href="p_lecturers.php?onget=1&idlect='.$tmpval['user_id_insert'].'" title="страница пользователя">'.color_mark($q,$tmpval['fio_short']).'</a></td>';
			
					if (!isset($_GET['save']) && !isset($_GET['print'])) {
					 	echo '<td class="notinfo">&nbsp;'.color_mark($q,$tmpval['comment']).'</td>';}
				}
			echo '</table></form>';
				}
			//-------------------------------------списочная таблица -конец----------------------------------------------------
			
			//постраничный вывод списка тем (по 10 тем)
			echo '<div align="center"> страницы ';
			$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;
			
			echo getPagenumList($pages_cnt,$page,6,'page',$add_string,'');
			echo '</div>';
			//--------------------------------------------------------
			
			$add_string=reset_param_name($add_string,'pgVals');// preg_replace("/(&pgVals=)(\d+)/x","",$add_string);		//убрать число страниц через RegExp
			echo '<br>макс.число записей на странице:  <input type=text value="'.$pgVals.'" name="pgVals" id="pgVals" size=10 title="число с 1-99"> &nbsp;
				<input type=button onclick="javascript:pgVals(\''.$add_string.'\');" value=Ok>
				<p> Всего записей: '.$itemCnt.'</div>';      	
		 
		 }


 if (!isset($_GET['save']) && !isset($_GET['print'])) 
{
        echo $end1;
        include "display_voting.php";
        echo $end2;
	include('footer.php'); 
 }
?>