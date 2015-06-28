<?php
include 'sql_connect.php';

$pg_title='Веб-страницы';

/*  типы страниц type_id
0	-пользовательская страница, созданная веб-редактором портала (добавление, изменение, удаление)
1	-портальная статичная страница (изменение)
2	-пользовательский веб-архив или сайт, загруженный на портал (добавление, удаление) отражается во фрейме, при правке нарушается структура
3	-портальная динамическая страница (-) не отражается, требуется для именования страниц в статистике

*/

//echo phpinfo();
$files_path='';
$folder='user_pages/';
$fileBackup='user_pages/_portal_pages/';
$folder_upload_pages='upload_pages/';	//имя каталога загруженного архива

$bodyOnLoad=' onLoad="showTabLayer(1,3);"';


$pg_id=0;
if (isset($_GET['pg_id']) && intval($_GET['pg_id'])>0) { $pg_id=intval($_GET['pg_id']); }

$pg_name='';
if ($pg_id>0) {
  $pg_name=getScalarVal('select `title` from `pg_uploads` where `id`="'.$pg_id.'"');
  if ($pg_name!='') $head_title=$pg_name.'. '.$head_title;
}

include 'header.php';

//------------------
function rm_recurse($file) {	//удаление папки с файлами
    if (is_dir($file) && !is_link($file)) {
        foreach(glob($file.'/*') as $sf) {
            if ( !rm_recurse($sf) ) {
                //error_log("Failed to remove $sf\n");
                return false;
            }
        }
        return rmdir($file);
    } else {
        return unlink($file);
    }
}
// права доступа на открытую задачу (без прохождения авторизации)
$public_task_rights_id=getTaskRightId($_SESSION['id'],$curpage);
//-------------

if ($pg_id>0 && isset($_GET['type'])&& $_GET['type']=='del') 
{ //удаление веб-страницы из списка
	$id=$pg_id;
	$query='select `name`,`type_id` from `pg_uploads` where `id`='.$id;
//------------------проверка прав пользователя-----------
		if ($public_task_rights_id!=4 && intval($a['user_id_insert'])!=intval($_SESSION['id']))
		{$query.=' and `user_id_insert`='.intval($_SESSION['id']);}
//------------------------------
	$res=getRowSqlVar($query);
	print_r($res);
	$file_name=$res[0]['name'];
	$type_id=$res[0]['type_id'];
	
	if ($file_name!='') {
	 unlink($folder.$file_name); //попытка удаления файла
	 
	 if ($type_id==2)	// удаление папок только для загруженных (а не созданных в редакторе) страниц
	 {
	  $parent_folder=dirname($folder.$file_name); //$pgViewName.'_files';	 
	  // проверка удаления папки
	  if (dirname($parent_folder).'/'==$folder.$folder_upload_pages)
	     {
	     rm_recurse($parent_folder);	     
	     }
	 }
		} 	

	$query='delete from `pg_uploads` where `id`='.$id;
//-----------------проверка прав пользователя------------
		if ($public_task_rights_id!=4 && intval($a['user_id_insert'])!=intval($_SESSION['id']))
		{$query.=' and `user_id_insert`='.intval($_SESSION['id']);}
//------------------------------
	mysql_query($query);
	header('Location:'.'pg_view.php');
}

if (!isset($_GET['wap'])) {	echo $head;}
else { echo $head_wap;}
?>
<script language="JavaScript" src="scripts/tabs.js"></script>
<script language="JavaScript">
function do_restore(pg_name)
{
 if (confirm('Продолжить восстановление веб-страницы'))
  window.location.href='?restoreF='+pg_name;

} 
</script>
<?php

//создание папки для хранения копий портальных стат.веб-страниц 
if (!file_exists($fileBackup)) 
	{if (mkdir($fileBackup,0700,1)) 
		{echo '<div class=success>папка для хранения копий портальных стат.веб-страниц <b>создана </b></div>';}
		else {echo '<div class=warning>папка для хранения копий портальных стат.веб-страниц <b>не создана </b></div>';}
	}


if (isAuthSess() ) 
{
// отражение ссылок с учетом прав пользователя
echo '<div>'.
 (getTaskAccess($_SESSION['id'],'pg_load.php')?
   '<a href="pg_load.php" class=text title="'.$_SESSION['FIO'].'"> добавить существующую страницу</a><br>':'').
(getTaskAccess($_SESSION['id'],'pg_load.php')?
   '<a href="pg_new.php" class=text title="'.$_SESSION['FIO'].'"> создать страницу в редакторе</a>':'').
 '</div>';
 }

	//определяем список доступных страниц по пользователю и его группе
	$query_access='select pg_id from pg_in_group pig where group_id in (SELECT group_id
				FROM user_in_group
				WHERE user_id ="'.intval($_SESSION['id']).'")
	 union 
	 select pg_id from pg_in_user pig where user_id='.intval($_SESSION['id']).'';

$query='select `pg_uploads`.*,`u1`.`fio_short` as fioInsert,  `u2`.`fio_short` as fioUpdate,t3.pg_id as access  
		from `pg_uploads` 
		left join `users` `u1` on `u1`.`id`=`pg_uploads`.`user_id_insert` 
		left join `users` `u2` on `u2`.`id`=`pg_uploads`.`user_id_update` 
		left join ('.$query_access.')t3 on t3.pg_id=`pg_uploads`.id';

if ($pg_id>0 ) {
	 
	$id=$pg_id; 

	$query.=' where `pg_uploads`.`id`='.$id;
	$res=mysql_query($query);
	if (mysql_num_rows($res)==1) {
	$a=mysql_fetch_array($res);
	
	// автор (добавил\обновил) страницу или с правами глоб-го админа
	$is_pg_author=false;
	if (isAuthSess()) {
	  if ($public_task_rights_id==4) $is_pg_author=true;
	  else
	   if ( intval($a['user_id_insert'])==intval($_SESSION['id']) || intval($a['user_id_update'])==intval($_SESSION['id']))
	    $is_pg_author=true;	 
	}
	echo '<h4>'.$a['title'].' <span class=text>( страницу добавил: '.
	($hide_person_data_rule?$hide_person_data_text:'<a href="'.$files_path.'_modules/_lecturers/index.php?action=view&id='.$a['user_id_insert'].'"><b>'.$a['fioInsert'].'</b></a>').', обновил '.
	($hide_person_data_rule?$hide_person_data_text:'<a href="'.$files_path.'_modules/_lecturers/index.php?action=view&id='.$a['user_id_update'].'"><b>'.$a['fioUpdate'].'</b></a>').') &nbsp;
	 '.($is_pg_author&& $a['type_id']!=2?'<a href="pg_new.php?pg_id='.$pg_id.'&type=edit">изменить </a>':'').' 
	 <a href="?">к списку страниц</a> </span>
	 </h4>';

	if (file_exists($folder.$a['name'])) {
	 
	if ($a['type_id']==2) {	//загрузка страницы во фрейм с учетом ее структуры (возможно для готовых страниц, целых сайтов при загрузке их на портал)
	//проверка наличия страницы на сервере
	?>

       <iframe src="<?php echo $folder.$a['name']; ?>" width="99%" height="800" style="border:none 0px;" border=0 allowtransparency="true">       
       </iframe>
	
	<?php
}
	else {	//пытаемся загрузить страницу в файл без фреймов (для работы поисковиков)
	?>
	<!--div style="OVERFLOW: auto; width:100%; border-style:solid; border-width:1px; height:800px; padding:10;">
	</div-->
	<div class=text style="margin:0; padding:10;">
		<?php
		include $folder.$a['name'];
		?>
	</div>
	<?php	 
	  }	

	}
	else
	 {echo '<div>страница не найдена на сервере. приносим извинения за неудобства</div><a href="?">к списку страниц</a> </span>';}
	}else {echo '<div>страница не найдена в БД. приносим извинения за неудобства</div><a href="?">к списку страниц</a> </span>';}


}
else { //вывод списка загруженных страниц (пользовательские страницы+сайты)
 
 	$res=mysql_query($query.' where (`pg_uploads`.`type_id`=0 or `pg_uploads`.`type_id`=2) and `pg_uploads`.`static`=0');

	$q_val='select sum(act_cnt) act_cnt,sum(stat_cnt) stat_cnt,	sum(user_cnt) user_cnt from (
	SELECT count(*) as act_cnt,0 as stat_cnt,0 as user_cnt
		FROM pg_uploads 
	WHERE `type_id` = 3 
	union
	SELECT 0,count(*),0
		FROM pg_uploads pu
	WHERE `type_id`=1 or static=1
	union 
	SELECT 0,0,count(*)
		FROM pg_uploads 
	WHERE (`type_id` = 0 OR `type_id` = 2) AND `static` = 0
	) t';
	$pgs_stat=getRowSqlVar($q_val);
	$pgs_stat=$pgs_stat[0];
	$pgs_cnt=$pgs_stat['act_cnt']+$pgs_stat['stat_cnt']+$pgs_stat['user_cnt'];
	
?>
<div>Всего зарегистрировано страниц: <?php echo $pgs_cnt;?></div>
<table border="1" cellpadding="0" cellspacing="0" width=99%>
      <tr>
        <td height=40 id="c1" onMouseOver="newColor(this.id);" onMouseOut="backColor(this.id);" onClick="showTabLayer(1,3);" width="33%">
          <div align="center"><font size="3"><b>Динамические <?php echo $pgs_stat['act_cnt'];?></b></font></div>
        </td>
        <td height=40 id="c2" onMouseOver="newColor(this.id);" onMouseOut="backColor(this.id);" onClick="showTabLayer(2,3);" width="33%">
          <div align="center"><font size="3"><b>Статические <?php echo $pgs_stat['stat_cnt'];?></b></font></div>
        </td>
        <td  height=40 id="c3" onMouseOver="newColor(this.id);" onMouseOut="backColor(this.id);" onClick="showTabLayer(3,3);;" width="*">
          <div align="center"><font size="3"><b>Пользовательские <?php echo $pgs_stat['user_cnt'];?></b></font></div>
        </td>
      </tr>
    </table>
<?php
 
 echo '<div id=tab3><table border=0 class="text" cellspasing=2 cellpadding=2 align=center width=99%> ';
	if (mysql_num_rows($res)>0) {
	while ($a=mysql_fetch_array($res))
	{
	 echo '<tr align=left bgcolor=#E6E6FF>';
	 if (!isset($_GET['save']) && !isset($_GET['print']) && isAuthSess() ) {
		
		//не админам - доступ только к своим страницам		
		if (intval($a['user_id_insert'])!=intval($_SESSION['id']) &&
		    intval($a['access'])<=0 && !($public_task_rights_id==4) ) {
		 	echo '<td align="center" width=40>&nbsp;</td>';			
		}		
		else {		
		echo '<td align="center" width=40> ';
		  	
			 echo '<a href="javascript:del_confirm_act(\''.str_replace(" ","_",f_ro($a['title'])).'\',\'pg_view.php?pg_id='.$a['id'].'&type=del\');" title="Удалить">
			<img src="'.$files_path.'images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;';
			
			if (intval($a['type_id'])==2 ||
			    (intval($a['static'])==0) &&  intval($a['user_id_insert'])!=intval($_SESSION['id']) ) 
			echo '<img src="'.$files_path.'images/toupdateD.png" alt="Правка невозможна" border="0">';
			else
			 echo '<a href="pg_new.php?pg_id='.$a['id'].'&type=edit" title="Правка">
					<img src="'.$files_path.'images/toupdate.png" alt="Правка" border="0"></a>'; 

			
			echo'</td>';
			}
		}
	 echo'
	 	<td><a href="?pg_id='.$a['id'].'">'.$a['title'].'</a></td> 
	 	<td width=200> '.
		($hide_person_data_rule?$hide_person_data_text:'<a href="'.$files_path.'_modules/_lecturers/index.php?action=view&id='.$a['user_id_insert'].'">'.$a['fioInsert'].'</a> / 
		 <a href="'.$files_path.'_modules/_lecturers/index.php?action=view&id='.$a['user_id_update'].'">'.$a['fioUpdate'].'</a>').'</td></tr>';
	}
	
	}else {echo '<tr height=40><td>список пуст</td></tr>';}
	echo '</table></div>';
	
	if (isAuthSess())	{//портальные страницы 
	//------------------вывод системных страниц только для правки, удаление невозможно
	// 
 	$query.=' where (`pg_uploads`.`type_id`=1 or static=1) ';

	//определяем список доступных страниц по пользователю и его группе
	$query_access='select pg_id from pg_in_group pig where group_id in (SELECT group_id
				FROM user_in_group
				WHERE user_id ="'.intval($_SESSION['id']).'")
	 union 
	 select pg_id from pg_in_user pig where user_id='.intval($_SESSION['id']).'';
	 
	if (isAuthSess() && $public_task_rights_id==4)
 	{
 	} 
	else
	{
 	 $query.=' and `pg_uploads`.id in('.$query_access.')';		
	}
	
	//подзапрос для расчет числа связанных со страницей пользовательских групп/пользователей	
	$query_gr_cnt='select t.pg_id as id,sum(t.gr_cnt) as gr_cnt,sum(t.pers_cnt) as pers_cnt from (
			     select pg_id,count(*) as gr_cnt,0 as pers_cnt from pg_in_group pig group by pg_id
			     union 
			     select pg_id,0,count(*) as pers_cnt from pg_in_user piu group by pg_id)t
		     group by t.pg_id';	

	$query='select t1.*,t2.gr_cnt,t2.pers_cnt
	     from ('.$query.')t1
		  left join ('.$query_gr_cnt.')t2 on t2.id=t1.id';
	
	$res=mysql_query($query);
	
	if (isset($_GET['restoreF']) && $_GET['restoreF']!='')  {
		echo '<div>Восстановление файла из копии: <b>'.$_GET['restoreF'].'</b></div>';
		if (!copy($fileBackup.$_GET['restoreF'], $_GET['restoreF'])) {    echo "<div>не удалось скопировать <b>".$_GET['restoreF']."</b></div>"; }
		else {echo "<div>файл <b>".$_GET['restoreF']."</b> успешно скопирован...</div>";}

	}	
// статические и портальные(не динамические) страницы
 echo '<div id=tab2>
  <table border=0 class="text" align=center width=99% cellspasing=2 cellpadding=2> ';
	if (mysql_num_rows($res)>0) {
	echo '<tr align=center style="font-weight:bold;text-alig:center;">
		<td width=40>&nbsp;</td>
		<td width="*">наименование страницы <i><u>(ключевые слова, описание)</u></i></td>
		<td width=40>группы/ пользователи</td>
		<td width=150>копия</td>
		<td width=150>авторы</td>
	</tr>';
	while ($a=mysql_fetch_array($res))
	{
	 echo '<tr align=left bgcolor=#E6E6FF>';
	 if (!isset($_GET['save']) && !isset($_GET['print']) && isAuthSess()) {
		echo '<td align="center" width=40>';
		
		// нельзя удалить портальную страницу
		if (intval($a['type_id'])==1)
		 echo '<img src="'.$files_path.'images/todeleteD.png" alt="Удалить невозможно" border="0" title="Удалить невозможно">';
		else echo '<a href="javascript:del_confirm_act(\''.str_replace(" ","_",f_ro($a['title'])).'\',\'pg_view.php?pg_id='.$a['id'].'&type=del\');" title="Удалить">
			<img src="'.$files_path.'images/todelete.png" alt="Удалить невозможно" border="0" title="Удалить невозможно">
			</a>';
		echo '&nbsp;';
		// правка невозможна для портальных страниц, страниц без прав доступа		
		if (intval($a['type_id'])==2)
			echo '<img src="'.$files_path.'images/toupdateD.png" alt="Правка невозможна" border="0">';
		else 
			echo '<a href="pg_new.php?pg_id='.$a['id'].'&type=edit" title="Правка">
				<img src="'.$files_path.'images/toupdate.png" alt="Правка" border="0"></a>';
		
		echo '</td>';
	    }
	 
	 //при наличии копии портальной страницы
	 $fileB=$fileBackup.$a['name'];
	 $dateB='-';
	 if (file_exists($fileB)) {$dateB= '<a href="#restore" title="вернуть из копии" onclick="javascript:do_restore(\''.$a['name'].'\');"><-</a> от '.date("d.m.Y H:i:s", filemtime($fileB));}

	 echo'
	 	<td><a href="'.$files_path.echoIf($a['static']!=1,$a['name'],'pg_new.php?pg_id='.$a['id']).'" '.
		 	echoIf($a['static']!=1,'','style="font-weight:bold;" title="из пользовательских"').'>'.$a['title'].'</a><i><u>'.
		(trim($a['meta_keywords'])!=''?''.substr($a['meta_keywords'],0,20):'').
		(trim($a['description'])!=''?''.substr($a['description'],0,20):'').
		'</u></i></td> 
	 	<td align=center>&nbsp;'.$a['gr_cnt'].echoIf(intval($a['gr_cnt'])>0 || intval($a['pers_cnt'])>0,' / ','').$a['pers_cnt'].'</td>
		<td align=center>'.$dateB.'</td>
		<td> '.($hide_person_data_rule?$hide_person_data_text:'<a href="'.$files_path.'_modules/_lecturers/index.php?action=view&id='.$a['user_id_insert'].'">'.$a['fioInsert'].'</a> / 
		 <a href="'.$files_path.'_modules/_lecturers/index.php?action=view&id='.$a['user_id_update'].'">'.$a['fioUpdate'].'</a>').'</td>
		</tr>';
	}
	
			
	//		------------------
	}else {echo '<tr height=40><td>список страниц (портальные, статические) пуст</td></tr>';}
	echo '</table></div>';
	
	?><br/>			
	<div class=text><b>Примечание</b>:
	<ul>
		<li>автор: добавил / изменил </li>
		<li>портальные страницы можно только изменить (нельзя удалить или добавить)</li>
		<li>в страницах допускается только html-код (без серверных/клиентких скриптов) </li>
		<li>страницы для правки\удаления доступны только зарегистрированным пользователям при наличии прав</li>
		<li>для просмотра доступны только пользовательские страницы</li>
		<li>при загрузке страницы через архив, ее невозможно изменить, только удалить и загрузить повторно</li>
		<li>для "связок" между страницами разместите на них гиперссылки через редактор страницы в режиме правки </li>
		<li>для статических портальных страниц при сохранении автоматически создается копия</li>

		<!--li>при отражение загруженных страниц (тип=2) используются фреймы, для созданных через редактор - слои (лучше для поисковиков)</li-->
		<li>число групп отражает число связанных пользовательских групп</li>
		<li>пользовательская страница доступна как в списке веб-страниц, так и на странице ее автора</li>
		<li>пользовательская страница может редактироваться только автором, если он хочет ее передать в общий доступ для правки, такую страницу необходимо пометить, как статичная</li>
		<li>число групп/пользователей отражает количество "привязанных" элементов в задачах прав пользователей</li>
		
	</div><?php

}
}
if (!isset($_GET['wap'])) {
  echo $end1;
  include "display_voting.php";
  }
define("CORRECT_FOOTER", true);
echo $end2; include('footer.php'); 
?>