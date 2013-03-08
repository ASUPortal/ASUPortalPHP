<?php
	$pg_title="Вопросы преподавателям и другим пользователям портала";
	include "sql_connect.php";
	include "header.php";
	if (!isset($_GET["wap"])) {	echo $head;}
	else { echo $head_wap;}	
?>
<script language=javascript>
  function testForm()
  {

 	a = new Array(
	 	new Array('question_text','')		
	);
requireFieldCheck(a,'form1');

    
  }
  
</script>
<p class="main"><?php echo $pg_title;?></p>

<?php
function sendMail($title,$text,$from_user_id,$to_user_id,$show_msg)	//отправка сообщения пользователю
{
    $msg='';
    if (intval($from_user_id)>0)	//есть отправитель
	{   
	   $query="insert into mails (date_send,mail_title,mail_text,from_user_id,to_user_id,mail_type,read_status,file_name) 
		       values('".f_ri(date("Y-m-d H:i:s"))."','".f_ri($title)."','".f_ri($text)."',".intval($from_user_id).",".echoIf(intval($to_user_id)>0,intval($to_user_id),0).",'out',1,'".$file_name."')";
	    if (mysql_query($query))
		{$msg.='<div class=success>Письмо отправлено, сохранено в папке исходящих отправителя</div>';}
		else {$msg.='<div class=warning>Письмо НЕ отправлено со стороны отправителя</div>'.$query;}
	}	
	
	if (intval($to_user_id)>0)	//есть получатель
	{
		$query="insert into mails (date_send,mail_title,mail_text,from_user_id,to_user_id,mail_type,read_status,file_name) 
			    values('".date("Y-m-d H:i:s")."','".f_ri($title)."','".f_ri($text)."',".echoIf(intval($from_user_id)>0,intval($from_user_id),0).",".intval($to_user_id).",'in',0,'".$file_name."')";
	    if (mysql_query($query))
		{$msg.='<div class=success>Письмо отправлено, сохранено в папке входящих получателя</div>';}
		else {$msg.='<div class=warning>Письмо НЕ отправлено получателю</div>'.$query;}
	}
	if ($show_msg) return $msg;
}

if (isset($_POST['question_text']) && trim($_POST['question_text'])!='')	//добавление вопроса преподавателю/пользователю портала
{
  $question_text=trim($_POST['question_text']);
  $user_id=intVal($_POST['user_id'],10);
  $contact_info=trim($_POST['contact_info']).' ip '.$_SERVER["REMOTE_ADDR"].';';
  if (isset($_SESSION['id']) && intval($_SESSION['id'])>0) {
	$user_send_fio=getScalarVal('select fio_short from users where id='.intval($_SESSION['id']));
	if ($user_send_fio!='') $contact_info.=' пользователь портала: '.$user_send_fio.';';
  }
  
  $query='insert into question2users(user_id,question_text,contact_info) values('.$user_id.',"'.$question_text.'","'.$contact_info.'")';
  mysql_query($query);
  if (mysql_affected_rows()) {
	echo '<span class=success>Вопрос успешно занесен</span>';
	
	//отправка уведомления пользователю
	echo "<div class=success style='font-size:12pt;'>".
	sendMail('вопрос пользователю',
		echoIf(strlen($question_text)>250,substr($question_text,0,250).'...',$question_text),
		echoIf(isset($_SESSION['id']),intval($_SESSION['id']),null),
		$user_id,false).
	"</div>";
	}
  else {echo '<span class=warning>Вопрос НЕ занесен</span>';}
}
	?>
<div class=text> Здесь Вы можете задать интересующие Вас вопросы все преподавателям портала.<br/>
После получения ответа, вопрос и ответ будут опубликованы на страницах портала.
<p>
<?php
$query='SELECT (select count(*) from `question2users` where `status`=3) as withAns, (select count(*) from `question2users`) as allQuests';
$qStat=getRowSqlVar($query);
$qStat=$qStat[0];
?>
Всего задано вопросов: <?php echo $qStat['allQuests']; ?>, из них с ответами: <?php echo $qStat['withAns'];?> &nbsp;
  <?php if ($_SESSION['id']!=null && intval($_SESSION['id'],10)>0) echo '<a class=main href="question_answ.php"> Мои вопросы-ответы</a>'; ?>
</p>
</div>

<form name=form1 id=form1 class=light_color_max action="" method=post>
  <table class="text" border="0" cellpadding=5>
  <tbody>
  <tr align="left" class=light_color_min>
    <td width="150">Преподаватель<br>
				  (кому адресуется вопрос)</td>
    <td><select id=user_id name=user_id title="Преподаватель">
      <?php
      $listQuery='select id,concat(fio," (",status,")") as fio from users order by fio';
      echo getFrom_ListItemValue($listQuery,'id','fio','user_id');      
      ?>
      
    </select></td>
  </tr>
  <tr height="40" align="left" >
    <td >Текст вопроса<span class=warning>*</span></td>
    <td><textarea rows=4 cols=40 id=question_text name=question_text title="Текст вопроса"></textarea></td>
  </tr>
  <tr align="left">
    <td >Контактная информация<br>
					  (по умолчанию публиковаться не будет)</td>
    <td><textarea rows=4 cols=40 id=contact_info name=contact_info title="Контактная информация"></textarea></td>
  </tr>
  <tr align="left">
    <td ><input type=button value="Задать вопрос" onclick=javascript:testForm();></td>
    <td><input type=reset value="Очистить форму"></td>
  </tr>
  </tbody>
  </table>
</form>

<?php
$query='select q2u.user_id,q2u.question_text,q2u.contact_info,q2u.answer_text,q2u.datetime_quest,q2u.datetime_answ,u.fio_short 
	  from question2users q2u left join users u on u.id=q2u.user_id
	  where q2u.status=3 and answer_text is not null and answer_text!="" 
	  order by q2u.datetime_quest';
$res=mysql_query($query);
if (mysql_num_rows($res)>0) {
?>
<div class="text" style="text-align:center;">Перед тем как задать вопрос, рекомендуем ознакомиться со списком уже заданных вопросов и ответов на них.</div>
Список вопросов с ответами: <?php echo mysql_num_rows($res);?>
<table class=text border=1 cellpadding=10 cellspacing=0>
  <tr class=title>
    <td>Преподаватель</td><td>Вопрос</td><td>Ответ</td>
  <tr>
<?php
  while ($a=mysql_fetch_assoc($res))
    echo '<tr>
	    <td><a href="p_lecturers.php?onget=1&idlect='.$a['user_id'].'" title="перейти на страницу преподавателя">'.$a['fio_short'].'</a>&nbsp;</td>
	    <td>'.$a['question_text'].'&nbsp;</td>
	    <td>'.$a['answer_text'].'&nbsp;</td>    
	  </tr>';
	  
  }
else {echo '<div class="text">не найдено вопросов с ответами</div>';}  
?>
</table>  
<?php
	if (!isset($_GET["wap"])) {
	  echo $end1;
	  include "display_voting.php";
	  }
	echo $end2; include('footer.php'); 
	?>