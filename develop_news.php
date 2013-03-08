<?php
include ('authorisation.php');
//include ('sql_connect_empty.php');

$main_page='develop_news.php';
$page=1;
if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];}

if (isset($_GET['type']) & $_GET['type']=='del' && isset($_GET['news_id']))
	{
	//echo '!!!!!!!del!!!!!!';
	$res=mysql_query("select file from news where news_type='develop' and master_id='".f_ri($_GET['news_id'])."'");
 	$a=mysql_fetch_array($res);
 	if (unlink ('./news/'.$a['file'])) {/*echo 'Не могу удалить файл: '.$a['file'];*/ };
	$res=mysql_query ("delete from news where news_type='develop' and master_id='".f_ri($_GET['news_id'])."'");	
	//echo $query;
	$query="delete from develop_news where id='".f_ri($_GET['news_id'])."'";
	$res=mysql_query($query);
	//exit();
	header('Location:'.$main_page.'?page='.$page.'&filtr='.$_GET['filtr']);
		
	}
include ('master_page_short.php');

?>
<script type="text/javascript" src="scripts/calendar_init.js"></script>

<style>
tr.title {font-size:13px; font-family:Arial; background-color:#E6E6FF; }
tr.main {font-size:11px; font-family:Arial; text-align:left; font-weight:normal;}
.err {color:red;font-family:Arial;}
</style>

<script language="JavaScript">
var main_page='develop_news.php';	//for redirect & links

function del_confirm(id,title,page,filtr)
{
	 if (confirm('Удалить строку: "'+title+'"?')) 
	 	{window.location.href=main_page+'?news_id='+id+'&type=del'+'&page='+page+'&filtr='+filtr;} 
} 
function filter()
{
 if (document.getElementById('news_type').value!=0)
	{ window.location.href=main_page+'?filtr='+document.getElementById('news_type').value;}
 else {window.location.href=main_page;}
} 
function go2search(filtr)
{
 	var search_query=document.getElementById('search_query').value;
 	if (search_query!='') {window.location.href=main_page+'?q='+search_query+'&filtr='+filtr;}
 	else {alert('Введите строку поиска');}
} 
function day_now()
{
 //alert('сегодня');
 var today_date=new Date();
 var date=today_date.getDate();
 var month=today_date.getMonth()+1;
 var year=today_date.getFullYear();
 var date_string='';
 //alert(day);
 if (date<10) {date_string='0'+date;} else {date_string=date;}
 if (month<10) {date_string+='.'+'0'+month;} else {date_string+='.'+month;}
 date_string+='.'+year;
 try {document.getElementById('date_new').value=date_string;}
 catch (e) {document.new_form.date_new.value=date_string;}
 
} 
function test_submit()
{
var msg='Требуется заполнить: ';
var err=false;

with (document.new_form) {
	if (news_type.value==0) {msg=msg+'\n тип обновления;';err=true;}
	if (date_new.value.replace(/ /gi,'')=='') {msg=msg+'\n дата обновления;';err=true;}
	if (title_new.value.replace(/ /gi,'')=='') {msg=msg+'\n заголовок обновления;';err=true;}
	if (text_new.value.replace(/ /gi,'')=='') {msg=msg+'\n текст обновления;';err=true;}
					} 
if (err==true)  {alert(msg);}
else {document.new_form.submit();}
} 
</script>

<?php



//include ('menu.htm');
//include ('sql_connect.php');


//удаление обновления

//добавление обновления
echo '<a name="top"></a>';
if (isset($_POST['date_new']))
{
	if ($_POST['date_new']!="" & $_POST['title_new']!="" & $_POST['text_new']!="" & $_POST['news_type']!=0) 
	{
	 //обновление обновления
	 if (isset($_GET['type']) & $_GET['type']=='edit' & isset($_GET['new_id']) & $_GET['new_id']!='') {
		 echo 'Правка обновления.';
		 $query="update develop_news set news_type='".$_POST["news_type"]."',
		 	date_time='".DateTimeCustomConvert($_POST["date_new"],'d','rus2mysql').date(" H:i:s")."',
		 	title='".f_ri($_POST["title_new"])."',text='".f_ri($_POST["text_new"])."',comment='".f_ri($_POST["comment_new"])."',in_news='".$_POST["for_news"]."' 
			 where id='".$_GET["new_id"]."'";

		 if ($res=mysql_query($query)) {echo 'Обновление обновлено  успешно.<p>&nbsp;';}
		 else {echo '<div class="err">Обновление не обновлено .<p>&nbsp;</div>';}
	 }
	 
	 //новое обновление
	 if (isset($_GET['type']) & $_GET['type']=='add') {
		 echo 'Добавление обновления.';
		 $query="insert into develop_news(news_type,date_time,title,text,comment,in_news) 
		 	values('".$_POST["news_type"]."','".DateTimeCustomConvert($_POST["date_new"],'d','rus2mysql').date(" H:i:s")."','".f_ri($_POST["title_new"])."','".f_ri($_POST["text_new"])."','".f_ri($_POST["comment_new"])."','".$_POST["for_news"]."')";
		 if ($res=mysql_query($query)) {echo 'Обновление "'.$_POST['title_new'].'" добавлено успешно.<p>&nbsp;';}
		 else {echo '<div class="err">Обновление не добавлено.<p>&nbsp;</div>';}
		 //echo $query;
	 }
	 //добавление новости на Главную, если ее раньше не было
	 	if ($_POST['for_news']=='on' & $_POST['for_news_base']!='on') {
		$res_news=true;
		//заносим в новости кафедры
	    $number_rand="N";$i=0;
	    while($number_rand=="N")
	     {
	      //mt_srand(time());
	      $i++;
		  $rand=date('d_m_Y').'_'.$i.'.txt';//mt_rand(0,100000);
	      $res01=mysql_query ('select * from news where file="'.$rand.'"');
	      if (mysql_num_rows($res01)==0)
	       {
	        $number_rand="Y";
	       }
	      else
	       {
	        $number_rand="N";
	       }
	     }
		$notice=fopen("./news/".$rand,"w");
	    flock($notice, LOCK_EX);
	    fwrite($notice,$_POST['text_new']);
	    fflush($notice);
	    flock($notice, LOCK_UN);
	    fclose($notice);	 
		 $res_news=true;
		 $title='Обновление сайта от '.$_POST['date_new'].'('.$_POST['title_new'].')';
		 
		 if (!isset($_GET['new_id'])) 
		 	{$res=mysql_query('select max(id) as max_id from develop_news');
			 $a=mysql_fetch_array($res);$news_id=$a['max_id'];}
		else {$news_id=$_GET['new_id'];}
		 
		 $res_news=mysql_query ('insert into news (news_type,master_id,file,title,date_time,image_small,image) 
			values ("develop","'.$news_id.'","'.$rand.'","'.$title.'","'.date("Y-m-d H:i:s").'","N","N")');
			}
		
		//удаление новости на Главной, если ранее она там была
		
		if ($_POST['for_news']!='on' & $_POST['for_news_base']=='on') 
			{
			 $res=mysql_query('select file from news where news_type="develop" and master_id="'.$_GET['news_id'].'"');
			 $a=mysql_fetch_array($res);
			 if (unlink ('./news/'.$a['file'])) {echo 'Не могу далить файл: '.$a['file'];}
			 else {echo ' файл удален из внешних новостей.';}
			 $res=mysql_query ('delete from news where news_type="develop" and master_id="'.$_GET['new_id'].'"');
			 //echo 'delete from news where news_type="develop" and master_id="'.$_GET['new_id'].'"';
			}
	

	 }
	else {echo '<div class="err">Часть обязательных данных не заполнено .<br>&nbsp;</div>';}
	
} 

if (isset($_GET['type']) & $_GET['type']=='edit')
{
	if (isset($_GET['new_id']) & $_GET['new_id']!="")
	{echo '<h4>Правка обновления</h4>';
	$query="select * from develop_news where id='".$_GET['new_id']."'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	}
	else {echo '<h4 class="err">не выбрано обновление для правки</h4>';}	
}

//добавление обновлениев
if (isset($_GET['type']) & $_GET['type']=='add' || $_GET['type']=='edit')
{
?>
<p><a href="develop_news.php" title="Просмотр обновлений портала"> Просмотр обновлений портала </a><p>
<h4> Ввод нового обновления портала</h4>
<div class=text> по умолчанию носит закрытый характер в служебных целях, но при необходимости может публиковаться в Новостях кафедры</div><p> 
<form name="new_form" method="post" action="">
Тип обновления* &nbsp; <select name="news_type" style="width:500;"> 
		<option value="0">...выберите тип обновления...</option>';
		<?php
		$query='select id,name from develop_news_type';
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
			 if ($res_edit) { if ($res_edit['news_type']==$a['id']) {$select_val=' selected';} } 
			echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
			}
		?>
</select><p>
	Дата* &nbsp; <input type=text size=20 name=date_new value="<?php if (isset($res_edit)) {echo substr(DateTimeCustomConvert($res_edit['date_time'],'dt','mysql2rus'),0,10);}else {echo date("d.m.Y");} ?>">
	<button type="reset" id="f_trigger_b">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_new",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_b",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
 
	<a href="javascript:day_now();">Сегодня</a>&nbsp;&nbsp;&nbsp;
	Заголовок* &nbsp; <input type=text size=50 name=title_new value='<?php if (isset($res_edit)) {echo f_ro($res_edit['title']);} ?>'> <p>
	Текст обновления*<br><textarea name=text_new cols=75 rows=6><?php if (isset($res_edit)) {echo f_ro($res_edit['text']);} ?></textarea> <p>
	Комментарий<br><input type=text size=100 name=comment_new value='<?php if (isset($res_edit)) {echo f_ro($res_edit['comment']);} ?>'> <p>
	
	<label title="Информация по обновлению попадет в Новости портала не Главной странице">
	<?php if ($write_mode===true) { ?>
	<input type=checkbox name=for_news <?php if (isset($res_edit) && $res_edit['in_news']=='on' ) {echo 'checked'; } ?> >Разместить в новостях кафедры</label>  
	<?php } ?>
	<div class=text>при необходимости правки Обновления, размещенного в Новостях, Вы можете либо снять и поставить отметку 
	<br> в пунте "Разместить в новостях кафедры", либо попросить Администратора скорректировать саму Новость.</div>	 <p>
	
	<input type=hidden name=for_news_base value="<?php if (isset($res_edit)) {echo $res_edit['in_news'];} ?>">
	<input type=button value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>" onClick=test_submit();> &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить> 
</form>

<?php
}

else 
	{
	echo '<h4> Обновления портала.</h4>';
	echo '<div class=text>по умолчанию носит закрытый характер в служебных целях, но при необходимости может публиковаться в Новостях кафедры </div> ';
	echo '<p><a href="'.$main_page.'?type=add"> Добавить обновление </a><p>';

echo '<table width=99%><tr>';
echo '<td align=left width=150>Тип обновления*</td> 
	<td align=left><select name="news_type" id="news_type" style="width:200;"> 
		<option value="0">...выберите тип обновления...</option>';
		$query='select id,name from develop_news_type';
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
			 if (isset($_GET['filtr'])) { if ($_GET['filtr']==$a['id']) {$select_val=' selected';} } 
			echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
			}
echo '</select>  ';
echo ' <input type=button value=Отобрать onclick="javascript:filter();">&nbsp;';
echo ' <input type=button value=Все onclick=javascript:window.location.href="'.$main_page.'";> </td>
	 <td align=right><input type=text name="search_query" width=50 value=""> &nbsp; <input type=button value="Найти" OnClick=javascript:go2search('.$_GET['filtr'].');></td>
</tr></table><p>';

echo '	<table><tr height="20" class=text>
			<td width=30 bgcolor="#FFFFCC"></td>	<td> планы, </td>
			<td width=30 bgcolor="#FFCC33"></td>	<td> модификация БД, </td>
			<td width=30 bgcolor="#DFEFFF"></td>	<td> реализовано </td>
		</tr></table>

<p>';

$page=1;$pageVals=10;$search_query="";
if (isset($_GET['page'])) {$page=$_GET['page'];}
if (isset($_GET['q'])) {$search_val=$_GET['q']; echo '<div>Поиск: <b><u>'.$search_val.'</u></b></div><br>';
$search_query=' and (develop_news.date like "%'.$search_val.'%" or 
					develop_news.title like "%'.$search_val.'%" or 
					develop_news.text like "%'.$search_val.'%" or 
					develop_news.comment like "%'.$search_val.'%")';}

if (isset($_GET['filtr']) & $_GET['filtr']!=0) 
	{$query='SELECT develop_news.id,develop_news.in_news, date_time, title, text, develop_news.comment,develop_news_type.name as news_type 
	 from develop_news left join develop_news_type on develop_news_type.id=develop_news.news_type 
	 where news_type="'.$_GET['filtr'].'"'.$search_query.' 
	 order by develop_news.id DESC ';}
else {
 $query="SELECT develop_news.id,develop_news.in_news, date_time, title, text, develop_news.comment,develop_news_type.name as news_type 
			from develop_news left join
 develop_news_type on develop_news_type.id=develop_news.news_type where 1 ".$search_query."ORDER by develop_news.id DESC ";}
	$res=mysql_query($query.'limit '.(($page-1)*$pageVals).','.$pageVals);
//echo $query;
echo '<form name=devel_news_list><table name=tab1 bnew=1 cellpadding="1" cellspacing="1"><tr align="center" class="title" height="30">';
	if ($write_mode===true) {
	echo '<td width="50"><img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';}
	echo '<td width="100">тип</td>';
	echo '<td width="70">дата </td>';
	echo '<td width="100">заголовок</td>';
	echo '<td width="400">текст</td>';
	echo '<td>комментарий</td>';
	echo '<td>внешняя</td>';
	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		$sum=0;
		
		if ($tmpval['news_type']=='Планы')  {echo '<tr align="left" class="main" bgcolor="#FFFFCC">';} 
		else if ($tmpval['news_type']=='Модификация БД')  {echo '<tr align="left" class="main" bgcolor="#FFCC33">';} 				//
			 else {echo '<tr align="left" class="main" bgcolor="#DFEFFF">';}
		
		//echo '<tr align="left" class="main">';
		if ($write_mode===true) {
		echo '<td align="center"> <a href=javascript:del_confirm("'.f_ro($tmpval['id']).'","'.str_replace(' ','_',f_ro($tmpval['title'])).'","'.f_ro($_GET['page']).'","'.f_ro($_GET['filtr']).'") title="Удалить">
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="'.$main_page.'?new_id='.$tmpval['id'].'&type=edit" title="Правка">
			<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';}
		echo '<td>&nbsp;'.$tmpval['news_type'].'</td>';
		
		$date_rus=DateTimeCustomConvert($tmpval['date_time'],'dt','mysql2rus');
		
		echo '<td>&nbsp;'.color_mark($search_val,$date_rus).'</td>';
		echo '<td>&nbsp;'.color_mark($search_val,$tmpval['title']).'</td>';
		
		$tmpval['text']=preg_replace("/\n/","<br>",$tmpval['text']);
		echo '<td>&nbsp;'.color_mark($search_val,$tmpval['text']).'</td>';
		echo '<td>&nbsp;'.color_mark($search_val,$tmpval['comment']).'</td>';
		echo '<td>&nbsp;'.$tmpval['in_news'].'</td>';
		echo '</tr>';
	}
echo '</table></form>';

//постраничный вывод списка обновлений (по 10 обновлений)
/*if (isset($_GET['filtr']) & $_GET['filtr']!=0) {$query='select id from develop_news where news_type="'.$_GET['filtr'].'"';}
else {$query='select id from develop_news';}
//echo $query;*/
$res=mysql_query($query);
$pages_cnt=floor(mysql_num_rows($res)/$pageVals)+1;
echo 'страницы ';
for ($i=1;$i<=$pages_cnt;$i++) {if ($i!=$page) {echo '<a href=?page='.$i.'&filtr='.$_GET['filtr'].'> '.$i.' </a>';} else {echo ' <b>'.$i.'</b> ';}}
//--------------------------------------------------------
echo '<div align="left"> Всего обновлений: '.mysql_num_rows($res).'</div>'; 	
	}

show_footer();
?>

<?php include('footer.php'); ?>