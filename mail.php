<?php
include ('authorisation.php');

//ini_set();
$main_page=$curpage;
$main_spaces='&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; ';

if ($_SERVER["QUERY_STRING"]=='') {header('Location:'.$main_page.'?folder=in');}
$pg_title='Письма участников портала';

include ('master_page_short.php');

$page=1;
$kadri_id='';		//отбор по руководителю диплома
$q='';			//строка поиска
$pgVals=20;	//число тем на странице по умолчанию
$mail_id=0;
$compose=0;	//написать письмо

if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];$filt_str_display=$filt_str_display.'  странице;';}
if (isset($_GET['mail_id']) && $_GET['mail_id']>0) {$mail_id=$_GET['mail_id'];}
if (isset($_GET['pgVals']) && $_GET['pgVals']<=99 && $_GET['pgVals']>=1) {$pgVals=$_GET['pgVals'];$filt_str_display=$filt_str_display.' числу записей;';}

if (isset($_GET['q'])) {$q=$_GET['q'];$filt_str_display=$filt_str_display.'  поиску;';}
$query_string=$_SERVER['QUERY_STRING'];


if (isset($_GET['compose'])) {$compose=intval($_GET['compose']);}

?>

<style>
tr.main {font-size:11px; font-family:Arial; text-align:left; font-weight:normal;}
.err {color:red;font-family:Arial;}
</style>

<script language="JavaScript">
var main_page='<?php echo $main_page;?>';	//for redirect & links
function del_mail_cofirm()
{
	if (confirm('Удалить выбранные письма')) document.mail_list_form.submit();
}
function go2search(query_string)
{
 	var search_query=document.getElementById('q').value;
 	var href_addr='';
 	
 	if (search_query!='') {
		href_addr='q='+search_query+'&'+query_string;
	 	window.location.href=main_page+'?'+href_addr;
	 }
 	else {alert('Введите строку поиска');}
} 

function mark_all(click_name)
{//
	
	var mail_cnt=0;
	
	//try {mail_cnt=document.getElementById('mail_cnt').value;}
	//catch (e) {mail_cnt=document.mail_list_form.elements.length;}
	mail_cnt=document.mail_list_form.elements.length;
	
	//alert(mail_cnt);
	
	var chech_val='';
	var mark_val;
	try {mark_val=document.getElementById(click_name).checked;}
	catch (e) {
		if (click_name=='checkbox_del_all1') {mark_val=document.mail_list_form.checkbox_del_all1.checked;}
		else {mark_val=document.mail_list_form.checkbox_del_all2.checked;}
		  }
	
	//alert(mark_val);
	//document.mail_list_form.checkbox_del_all1.checked=mark_val;
	//document.mail_list_form.checkbox_del_all2.checked=mark_val;
	//alert(mail_cnt);alert(mark_val);
	 for (i=0;i<mail_cnt;i++) {  document.mail_list_form.elements[i].checked=mark_val; }  
} 
function del_mail()
{
	var mail_cnt=document.getElementById('mail_cnt').value;
	var subForm=false;

   for (var i in document.mail_list_form.elements) {
    	var mail_id;
    	if (document.mail_list_form[i].name!=null) {
		 	 mail_id=document.mail_list_form[i].name;	//;alertdocument.getElementById(mail_id).checkedd);
			if(document.getElementById(mail_id).checked) {subForm=true; alert(document.getElementById(mail_id).checked);} 
			}  
		}
		 	 
			 if (subForm) {document.mail_list_form.submit();}
		 	 else {alert('Нет ни одного письма для удаления');}	
	
	}
   
function check_mail()
{
	//alert(document.compose_form.mail_title);
 if (document.compose_form.mail_title.value=='' || document.compose_form.mail_text.value=='' || document.compose_form.to_user_id.value=='0') 
 		{alert ('Не заполнены обязательные поля');}
 else 	{document.compose_form.submit();}
  
 
} 
function response_mail()
{
 document.compose_form.mail_title.value='ответ на: '+document.compose_form.mail_title.value;
 document.compose_form.mail_text.value='Здравствуйте, Вы писали: "'+document.compose_form.mail_text.value+'"'+"\n";
 document.compose_form.resp_button.style.display="none";
 
 
} 
</script>


<?php 

echo '<h4>'.$pg_title.'</h4>';
echo '<div class=text>Теперь Вы можете обмениваться сообщениями с участниками портала. Это ускорит получение ответов на интересующие Вас вопросы.</div><p>';
//include ('sql_connect.php');

echo '<span class=text> Непрочитанных писем: ';
$query="select count(*) as new_mails_cnt from mails where to_user_id='".f_ri($_SESSION['id'])."' and mail_type='in' and read_status=0";
$res=mysql_query($query);
$a=mysql_fetch_array($res);
if ($a['new_mails_cnt']>0) {echo '<font style="font-weight:bold; font-size:12;">'.$a['new_mails_cnt'].'</font>'; } else {echo 'нет';}

echo '</span><p>';

//--------------------------------------------------------
//--------------------------------------------------------
if (isset($_GET['del'])&& isset($_GET['folder'])) 
	{
		//echo 'Удаление писем.<p>';

while (list($key, $value) = each ($_POST)) {
 	if 	  (strpos($key,'all')<1) {
		$mail_id=substr($key,strpos($key,'del_')+4);	//выдираем ID из названий чекбоксов
	  //echo "Key: $key; Value: $value<br>\n"; 
	  if (intval($mail_id)>0) {
		$mail_stat=getRowSqlVar('select date_send,mail_title from mails where id='.$mail_id);
		
		if ($_GET['folder']=='in') {$query="delete from mails where id='".f_ri($mail_id)."' and to_user_id='".f_ri($_SESSION['id'])."'"; }
		if ($_GET['folder']=='out') {$query="delete from mails where id='".f_ri($mail_id)."' and from_user_id='".f_ri($_SESSION['id'])."'"; }	  
      
		//echo $query.'<p>'; 
		if (mysql_query($query) && mysql_affected_rows()>0)
		      echo '<div class=success> Письмо "'.$mail_stat[0]['mail_title'].'"
		      от '.DateTimeCustomConvert(substr($mail_stat[0]['date_send'],0,10),'d','mysql2rus').' <font size=+2>удалено</font></div>'; 
	  }
	}	   }


}
$in_cnt='0'; $out_cnt='0';
$query="select count(*) as in_cnt from mails where to_user_id='".intval($_SESSION['id'])."' and mail_type='in' limit 0,1";
$res=mysql_query($query);
$tmpval=mysql_fetch_array($res);
$in_cnt=$tmpval['in_cnt'];

$query="select count(*) as out_cnt from mails where from_user_id='".intval($_SESSION['id'])."' and mail_type='out' limit 0,1";
$res=mysql_query($query);
$tmpval=mysql_fetch_array($res);
$out_cnt=$tmpval['out_cnt'];

	if (isset($_GET['compose'])) {echo '<b> Написать</b>';} else {echo'<a href="?compose=1">Написать</a> ';} echo $main_spaces;
	if (isset($_GET['folder']) && $_GET['folder']=='in') {echo '<b> Входящие ('.$in_cnt.')</b>';} else {echo '<a href="?folder=in">Входящие ('.$in_cnt.')</a>';}echo $main_spaces;
	if (isset($_GET['folder']) && $_GET['folder']=='out') {echo '<b> Исходящие ('.$out_cnt.')</b>';} else {echo'<a href="?folder=out">Исходящие ('.$out_cnt.')</a>';}	echo $main_spaces;
	echo '	<input type=text name="q" id="q" width=50 value="" '.echoIf($compose==1,'disabled title="выберите раздел для поиска"','').'> &nbsp;
		<input type=button value="Найти" title="" OnClick="javascript:go2search(\''.reset_param_name_ARR($query_string, array('page','q')).'\');" '.echoIf($compose==1,'disabled title="выберите раздел для поиска"','').'>';
	
	$query='select `e_mail` from kadri where id='.intval($_SESSION['kadri_id']).' limit 0,1';
	$outEmail=getScalarVal($query);
	
	if (isset($_POST['flag_copy2outEmail_hidden']))
	{
	if ($_POST['flag_copy2outEmail']=='on')
		{
		// сохраняем подписку в БД
		$queryUpdateS='insert into subscriptions(type_id,user_id) 
			select id,'.intval($_SESSION['id']).' from subscription_types where name="mail"';			
		}
		else
		{
		$queryUpdateS='delete from subscriptions 
			where user_id='.intval($_SESSION['id']).' and type_id in (select st.id from subscription_types st where st.name="mail")';			
		}		
	if (mysql_query($queryUpdateS) && mysql_affected_rows()>0)
		      echo '<div class=success> подписка обновлена</div>';
	}
	$query='select s.id from subscriptions s 
		left join subscription_types st on st.id=s.type_id 
		where user_id='.intval($_SESSION['id']).' and st.name="mail" limit 0,1';
	
	$flag_copy2outEmail=intval(getScalarVal($query));
	//if (trim($outEmail)=='') $outEmail='укажите в анкете сотрудника';
	echo '<form name="outEmailStatus" action="" method="post">
	<div class="text">';
	mark_new('11.04.2012');
	echo 'дублировать входящие сообщения на внеш.эл.почту 
	('.(trim($outEmail)==''?'не указан в <a href="lect_anketa.php?kadri_id='.intval($_SESSION['kadri_id']).'&action=update">анкете преподавателя</a>':'<a href=mailto:'.$outEmail.'>'.$outEmail.'</a>').')	
	<input type="checkbox" name="flag_copy2outEmail" '.(trim($outEmail)==''?'disabled':'').' '.($flag_copy2outEmail>0?'checked':'').'>
	<input type="hidden" name="flag_copy2outEmail_hidden" value="1">	
	<input type="submit" value="Сохранить">
	</div>
	</form>
	';
echo '<hr>';


if (isset($_POST['to_user_id'])) {
 echo 'отправка письма...  ';
 if ($_POST['to_user_id']!=0 && $_POST['mail_title']!='' && $_POST['mail_text']!='')
 	{
	$file_name='';$err_send=false;
	if (is_uploaded_file($_FILES['lfile']['tmp_name']))
		{
		//echo ' пробуем загрузить  ';
		$file_name=Trans_file_word($_FILES['lfile']['name']);
		$i=1;
		//проверяем имя файла на существование и правим его при необходимости
		$file_name_='';
		while (file_exists('f_mails/'.$file_name_)) {
		 	$file_name_=substr($file_name,0,strlen($file_name)-4).'_'.$i.substr($file_name,strlen($file_name)-4);$i++;
			} 
		$file_name=$file_name_;
		if (test_file($file_name,$_FILES['lfile']['size'])) 
		 {
		 move_uploaded_file($_FILES['lfile']['tmp_name'], 'f_mails/'.$file_name);
		 	echo ' файл загружен: '; file_type_img($file_name); echo '<b>'.$file_name.'</b>'; }
		 else {$err_send=true;echo ' файл не загружен ';}
		
		}
	//else {echo ' файл не загрузить ';}
if ($err_send==false) {
    $query1="insert into mails (date_send,mail_title,mail_text,from_user_id,to_user_id,mail_type,read_status,file_name) 
		values('".date("Y-m-d H:i:s")."','".f_ri($_POST['mail_title'])."','".f_ri($_POST['mail_text'])."','".f_ri($_SESSION['id'])."','".f_ri($_POST['to_user_id'])."','in','0','".$file_name."')";
           
    $query2="insert into mails (date_send,mail_title,mail_text,from_user_id,to_user_id,mail_type,read_status,file_name) 
		values('".f_ri(date("Y-m-d H:i:s"))."','".f_ri($_POST['mail_title'])."','".f_ri($_POST['mail_text'])."','".f_ri($_SESSION['id'])."','".f_ri($_POST['to_user_id'])."','out','1','".$file_name."')";
           //echo "<hr>".$query;
           if (mysql_query($query1) && mysql_query($query2)) {
			echo "<div class=success style='font-size:12pt;'>Письмо отправлено</div>";
			// отправка уведомления на внеш.почту, если подписан пользователь-адресат
			$queryS='select s.id,u.fio, k.e_mail from subscriptions s 
				left join subscription_types st on st.id=s.type_id 
				left join users u on u.id=s.user_id 
				left join kadri k on k.id=u.kadri_id  
				where user_id='.intval($_POST['to_user_id']).' and st.name="mail" limit 0,1';
			$arrCopy2outEmail=getRowSqlVar($queryS);
			
			if (is_array($arrCopy2outEmail) && trim($arrCopy2outEmail[0]['e_mail'])!='') 
				{
				$to      = $arrCopy2outEmail[0]['e_mail'];
				$subject = 'Портал АСУ. Вам отправлено сообщение от пользователя '.f_ri($_SESSION['FIO']);
				$message = 'Тема сообщения: '.f_ri($_POST['mail_title']).'. Для прочтения сообщения перейдите на Портал АСУ.';
				$message = wordwrap($message, 70);
				$headers = 'From: asu_portal@mail.rb.ru' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				if (mail($to, $subject, $message, $headers)) echo '';
				else echo '';
				
				}
			}
           else {$err_send=true; }
}			
	}
 else {echo '<div class=err> часть данных не заполнена </div>';$err_send=true;}
if ($err_send==true)	 {echo "<div class=err color=red>Письмо не отправлено!</div>";}
	
 
}
if (isset($_GET['folder']) && ($_GET['folder']=='in' || $_GET['folder']=='out')  || isset($_GET['compose']))
{
 	
	if ($mail_id>0)
	{		
		$query='';
		if ($_GET['folder']=='in') {
			$query="select date_send,mail_title,from_user_id as mail_user_id,read_status,mail_text,file_name from mails 
				where to_user_id='".f_ri($_SESSION['id'])."' and mail_type='in' and id='".f_ri($_GET['mail_id'])."' limit 0,1";}
		if ($_GET['folder']=='out') {
			$query="select id,date_send,mail_title,to_user_id as mail_user_id,read_status,mail_text,file_name from mails 
				where from_user_id='".f_ri($_SESSION['id'])."' and mail_type='out' and id='".f_ri($_GET['mail_id'])."' limit 0,1";}
		//echo $query;
		$res_mail=mysql_query($query);
		$tmpval=mysql_fetch_array($res_mail); ?>
<?php }
if ($compose==1 || $mail_id>0) {    
?>
<form name=compose_form action="?folder=out" method="post" enctype="multipart/form-data">
	<table border=0 width=600 cellpadding="0" cellspacing="10" class=forms_under_border>
		<tr><td>Кому*</td><td>
			<select name="to_user_id">
				<option value=0>выберите адресата</option>
				<?php
					$query="select id,fio,status from users order by status,fio ";
					$res=mysql_query($query);
					while ($a=mysql_fetch_array($res)) 	{
					 	$select_val='';
						 if ($res_mail) { if ($tmpval['mail_user_id']==$a['id']) {$select_val=' selected';}			  } 
						 if ($a['status']=='преподаватель') {$a['status']='';} 
						 else { $a['status']='  ('.$a['status'].')';}
						
						$option_class='';
						if  ($a['id']==$_SESSION['id']) $option_class='class=forms_under_border';
						echo '<option '.$option_class.' value="'.$a['id'].'"'.$select_val.'>'.$a['fio'].'  '.$a['status'].'</option>';
						}
				?>
			</select></td></tr>
		<tr><td>Заголовок*</td><td> <input type=text size=65 name=mail_title value="<?php if ($res_mail) {echo $tmpval['mail_title'];} ?>"> </td></tr>
		<tr><td>Текст*</td><td> <textarea name=mail_text rows=10 cols=50><?php if ($res_mail) {echo $tmpval['mail_text'];} ?></textarea> </td></tr>
		<tr><td>Прикрепленный файл <span class=text> (не более <span class=err><?php echo $upload_max_filesize;?> </span>МБ) </span></td>
				<td><?php if (($res_mail)) {echo '<a href="f_mails/'.urlencode($tmpval['file_name']).'" title="Сохранить">'.$tmpval['file_name'].'</a>';} else { ?>
				    <input type="file" name="lfile" class="text2" size="70"><?php }?> </td></tr>
		<tr><td colspan=2 class=text style="text-align:center;"> поля, отмеченные *, обязательны для заполнения </td></tr>
	</table>
	<?php if ($_GET['folder']=='in') {echo '<input type=button name="resp_button" value="Ответить" onclick="javascript:response_mail();" >&nbsp;&nbsp;&nbsp;';} ?>
	<input type=button value="Отправить" onclick="javascript:check_mail();">&nbsp;&nbsp;&nbsp; 
</form>
<?php
}

if (isset($tmpval) && $compose!=1) {?>
<form name=mail_list_form action="?folder=<?php echo $_GET['folder'].'&'.reset_param_name_ARR($query_string,array('folder','del'));?>&del=1" method="post">
<div class=text>в папке "Входящие" жирным текстом выделены непрочитанные письма </div>
<table name=tab1 border=0 cellpadding="2" cellspacing="2" width="800">
	<tr align="center" class="table_title" height="30">
		<td width="40"><input type=checkbox name="checkbox_del_all1" onClick="javascript:mark_all(this.name);"></td>
		<td width="200"><?php if ($_GET['folder']=='in') {echo 'от кого';} else {echo 'кому';}?></td>
		<td width="">заголовок </td>
		<td width="100">дата</td>
		<td width="100">размер</td>
	 </tr> 
	 <?php
	$i=0;
	
	//помечаем писмьмо прочитанным в папке исходящие
	if (isset($_GET['mail_id']) && $_GET['folder']=='in') {
		$query="update mails set read_status=1 where to_user_id='".f_ri($_SESSION['id'])."' and mail_type='in' and id='".f_ri($_GET['mail_id'])."'";
		mysql_query($query);
		//echo $query;
		}

//---------------------------
$search_query='';
if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b></div><br>';
$search_query=' and (users.fio like "%'.$q.'%" or 
					mails.mail_title like "%'.$q.'%" or 
					mails.mail_text like "%'.$q.'%" or
					mails.date_send like "%'.$q.'%" or mails.date_send like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%")';}
if (!isset($_GET['save']) && !isset($_GET['print'])) {
	
if ($filt_str_display!='') {echo '<div class=text>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; <a href="?'.reset_param_name_ARR($query_string,array('page','q')).'"> сбросить фильтр </a></div>';}

}
//---------------------------
//запрос списочной таблицы
	if ($_GET['folder']=='in') {
		$query="select mails.id,mails.date_send,mails.mail_title,mails.mail_text,mails.from_user_id,mails.read_status,users.fio from mails left join users on users.id=mails.from_user_id 
			where mails.to_user_id='".f_ri($_SESSION['id'])."' and mails.mail_type='in' ";}
	if ($_GET['folder']=='out') {
		$query="select mails.id,mails.date_send,mails.mail_title,mails.mail_text,mails.from_user_id,mails.read_status,users.fio from mails left join users on users.id=mails.to_user_id 
			where mails.from_user_id='".f_ri($_SESSION['id'])."' and mails.mail_type='out' ";}
	if ($query!="") {$query.=$search_query.' order by 1 desc ';}
	
//    echo "<hr>".$query;
	//$res=mysql_query($query);
	$res=mysql_query($query.'limit '.(($page-1)*$pgVals).','.$pgVals);

	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		$i++;
		echo '<tr align="left" class="table_row" valign="top">';
	  	echo '<td align="center"> <input type=checkbox id="checkbox_del_'.$tmpval['id'].'" name="checkbox_del_'.$tmpval['id'].'"></td>';
		echo '<td> '.color_mark($q,f_ro($tmpval['fio'])).'</td>';
		
		$textBold1='';$textBold2='';
		if ($tmpval['read_status']==0) {$textBold1='<b>';$textBold2='</b>';}	//выделение непрочитанных жирным
		if ($tmpval['id']==$mail_id) {$textBold1='<font size=+1>';$textBold2='</font>';}	//выделение непрочитанных жирным
		
		echo '<td> <a href="?mail_id='.f_ro($tmpval['id']).'&'.reset_param_name($query_string,'mail_id').'">'.
			color_mark($q,$textBold1.f_ro($tmpval['mail_title'])).$textBold2.'</a></td>';
		
		$mail_date=color_mark($q,DateTimeCustomConvert($tmpval['date_send'],'dt','mysql2rus'));
		
		//$mail_date=substr($tmpval['date_send'],6,2).'.'.substr($tmpval['date_send'],4,2).'.'.substr($tmpval['date_send'],0,4).' '.substr($tmpval['date_send'],8);
		
		echo '<td> '.$mail_date.'</td>';
		echo '<td> '.strlen($tmpval['mail_text']).' Б</td></tr>';
	}
echo '<tr align="center" class="title" height="30">
		<td align="center"> <input type=checkbox name="checkbox_del_all2" onClick="javascript:mark_all(this.name);"></td>
		<td colspan=4 align=left><a href="javascript:del_mail_cofirm();" title="Удаление отмеченных писем">Удалить отмеченные письма</a></td>
	</tr>';	
echo '</table>';


$itemCnt=getScalarVal('select count(*) from ('.$query.')t');

if (floor($itemCnt/$pgVals)==$itemCnt/$pgVals) {$pages_cnt=floor($itemCnt/$pgVals);}
 else {$pages_cnt=($itemCnt/$pgVals)+1;}

echo '<div align="left"> страницы ';
	$add_string=reset_param_name($query_string,'page');
	echo getPagenumList($pages_cnt,$page,3,$add_string.'&page','','');
echo '</div>';
//--------------------------------------------------------

$add_string=reset_param_name($add_string,'pgVals');// preg_replace("/(&pgVals=)(\d+)/x","",$add_string);		//убрать число страниц через RegExp
echo '<br>макс.число записей на странице:  <input type=text value="'.$pgVals.'" name="pgVals" id="pgVals" size=10 title="число с 1-999"> &nbsp;
	<input type=button onclick="javascript:pgValsCh(\''.$add_string.'\');" value=Ok>
	<p> Всего записей: '.$itemCnt.'</div>'; 	
	

echo '</form><input type=hidden name=mail_cnt value="'.mysql_num_rows($res).'"><p>';
 	
} 
} 


?>
<div class=text> <b>Примечание</b><br>
	Вы можете отправить сообщение самому себе для проверки или загрузки своего файла на портал.
</div>	
<p><a href="p_administration.php">К списку задач.</a><p>

<?php  
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{
        echo $end1;
        include "display_voting.php";
        echo $end2;
    define("CORRECT_FOOTER", true);
	include('footer.php'); 
 } ?>