<?php
$pg_title='Создание веб-страниц';

$files_path="";
$fileBackup='user_pages/_portal_pages/';

include ('authorisation.php');
include ('master_page_short.php');


?>
<script language="javascript" type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
		// General options
		//mode : "textareas",
		mode : "exact", elements : 'content',
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		indentation : '200pt',
		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",
		spellchecker_languages : "+English=en,Russian=ru",
		language : "ru"
		
});

</script>
<script language="javascript" type="text/javascript">
function form_check()
{	//массив id,текст ошибки
 	a = new Array(
	 	new Array('pg_title',''),
		new Array('pg_name',''),
		new Array('content','введите Ваш текст')
	);
    requireFieldCheck(a,'pg_new');
}
function confirm_stat()
{ 
 var val=document.getElementById('pg_stat');
 if (val.checked==true) {alert('Страница попадет в список статичных и будет Вам недоступна, пока администратор не добавит ее.');}
}  
var c=0; //счётчик количества строк
var c_send=0; //счётчик количества строк для рассылки
function addline()
{
	c=document.getElementById('max').value;
	c++; // увеличиваем счётчик строк
	s=document.getElementById('pg_links_edit').innerHTML; // получаем HTML-код таблицы
	s=s.replace(/[\r\n]/g,''); // вырезаем все символы перевода строк
	re=/(.*)(<tr id=.*>)(<\/table>)/gi; 
                // это регулярное выражение позволяет выделить последнюю строку таблицы
	s1=s.replace(re,'$2'); // получаем HTML-код последней строки таблицы
	s2=s1.replace(/\_\d/gi,'_'+c+''); // заменяем все цифры к квадратных скобках
                // на номер новой строки
	s2=s2.replace(/(rmline\()(\d+\))/gi,'$1'+c+')');
                // заменяем аргумент функции rmline на номер новой строки
	s=s.replace(re,'$1$2'+s2+'$3');
                // создаём HTML-код с добавленным кодом новой строки
	document.getElementById('pg_links_edit').innerHTML=s;
	
	document.getElementById('max').value=c;
	                // возвращаем результат на место исходной таблицы
	return false; // чтобы не происходил переход по ссылке
}
function rmline(q)
{
 c=document.getElementById('max').value;
                if (q==0)return false;
                if (c==0) return false; else c--;
                // если раскомментировать предыдущую строчку, то последний (единственный) 
                // элемент удалить будет нельзя.
           
	
	s=document.getElementById('pg_links_edit').innerHTML;
	s=s.replace(/[\r\n]/g,'');
	re=new RegExp('<tr id="?newline"? nomer="?_'+q+'.*?<\\/tr>','gi');
                // это регулярное выражение позволяет выделить строку таблицы с заданным номером
	s=s.replace(re,'');
                // заменяем её на пустое место
	
	document.getElementById('pg_links_edit').innerHTML=s;
	document.getElementById('max').value=c;
	
	return false;
}

</script>
<?php
function authors_sec($query_main,$query_sub,$item_name_pref)
{
	$item_id=1;

	  $res=mysql_query($query_main);
	  $cnt_=mysql_num_rows($res);
?>
<table border="0" cellspacing="0" cellpadding="3">
     <tr id="newline" nomer="_0">
       <td></td>
       <td valign="top" align="center">
	   <a href="#" onclick="return addline();" style="text-decoration:none">
		<img src="images/design/pl.gif" border="0" WIDTH="17" HEIGHT="18"></a></td></tr>
<?php			  
	  while ($res_edit=mysql_fetch_array($res))
	  {	   
	  	print_r($res_edit);
	  	
		  ?>
    <tr id="newline" nomer="_<?php echo $item_id;?>">
      <td>
		 <select id="<?php echo $item_name_pref.$item_id;?>" name="<?php echo $item_name_pref.$item_id;?>" style="width:300;">
		 <?php
	  	 	$_POST[$item_name_pref.$item_id]=$res_edit[0];
	  	 	$_GET[$item_name_pref.$item_id]=$res_edit[0];			   	  	 	
	  		echo 'res_edit['.$item_name_pref.$item_id.']='.$res_edit[$item_name_pref.$item_id].'\n';
		   echo getFrom_ListItemValue($query_sub,'id','title',$item_name_pref.$item_id);	
	  	 ?>
	  </select>
	  </td>
      <td valign="top" align="center"><a href="#" onclick="return rmline(<?php echo $item_id;?>);" style="text-decoration:none"><img src="images/design/mn.gif" border="0" WIDTH="17" HEIGHT="18"></a></td></tr>
	  
	  <?php 	  $item_id++;
	  }
	?>
	<tr id="newline" nomer="_<?php echo $item_id; ?>">
      <td>
	  <select name="<?php echo $item_name_pref.$item_id; ?>" id="<?php echo $item_name_pref.$item_id; ?>" style="width:300;">
	  	<?php	//список преподавателей
	  	 echo getFrom_ListItemValue($query_sub,'id','title',$item_name_pref.$item_id);	
	  		?>
	  </select>
	  </td>
      <td valign="top" align="center"><a href="#" onclick="return rmline(<?php echo $item_id; ?>);" style="text-decoration:none"><img src="images/design/mn.gif" border="0" WIDTH="17" HEIGHT="18"></a></td></tr>
  </table>
  <input type="hidden" name="max" id="max" value="<?php echo $item_id; ?>">
  	<?php
}

function codeHTML($contents)
{
if ($contents!='') {
	$contents=preg_replace("/<\?.*?>/is", "", $contents);
	$contents=preg_replace("/<(|\/)html[^>]*>/is", "", $contents);	//убрали html(title+meta)
	$contents=preg_replace("/<(|\/)body[^>]*>/is", "", $contents);	//убрали body(title+meta)
	$contents=preg_replace("/<head[^>]*?>.*?<\/head>/is", "", $contents);
	$contents=preg_replace("/(<script[^>]*?>.*?<\/script>)/is", "", $contents);
}
 return $contents;
} 


$maxPgsize=1024; //in Kb
$folder='user_pages/';

$file_name='';
$file_title='';
$file_comment='';
$somecontent='';
$contents='';
$err=false;	//флаг ошибок

echo '<h4>'.$pg_title.' <span class=text>(создание <b>htm</b> файлов, с размером до <b>'.$maxPgsize.' Кб</b>)</span></h4>';
echo ' <p class=text><a href="pg_view.php">К списку веб-страниц</a></p>';
//-----------------------------
if (isset($_POST['content']) && $_POST['content']!='')	//запись созданной/правленной в редакторе страницы
{
 	$file_name=$_POST['pg_name'];//'Copy of intrabe.htm';
	$file_title=$_POST['pg_title'];
	
	$magic_quotes=get_magic_quotes_gpc();	//экранирование смиволов на сервере

 	if ($magic_quotes) {           
 	    $_POST['content']=stripslashes($_POST['content']);
 	}

	$contents=codeHTML($_POST['content']);
	
	if ($_POST['type_id']==1) {//портальная страница
 
	 
	 $folder=$files_path;
	 $file_name.='.php';
	 $somecontent='<?php
	$pg_title="'.$file_title.'";
	include "sql_connect.php";
	include "header.php";
	if (!isset($_GET["wap"])) {	echo $head;}
	else { echo $head_wap;}
	?>'."\n\n".
	$contents."\n\n".'<?php
	if (!isset($_GET["wap"])) {
	  echo $end1;
	  include "display_voting.php";
	  }
	echo $end2; include("footer.php"); 
	?>';
	
	 //создание копии портальной страницы для восстановления
	 
		echo '<div>Создание копии файла: <b>'.$file_name.'</b></div>';
		if (!copy($file_name, $fileBackup.$file_name)) {    echo "<div>не удалось скопировать <b>".$file_name."</b></div>"; }
		else {echo "<div class=success>файл <b>".$file_name."</b> успешно скопирован...</div>";}
	 //----------------------------
	
	}
	else  {$somecontent=$_POST['content'];$file_name.='.htm';}	//обычная пользовательская страница
	
	$handle = fopen($folder.$file_name, "w");
	
	 
    if (fwrite($handle, $somecontent) === FALSE) {
        echo "<div>Не могу произвести запись в файл (<b>$file_name</b>)</div>";        
    }
    else {
	 $id=intval($_GET['pg_id']);
	 	
	 	$pg_cat=intval($_POST['pg_cat'],10);
		 if (isset($_GET['type']) && $_GET['type']=='edit') {
		  if (intval($_GET['pg_id'])>0) {
		  $query='update `pg_uploads` 
		  set 	`title`="'.$file_title.'",
			`name`="'.$file_name.'",
			`user_id_update`="'.$_SESSION['id'].'",
			`comment`="'.$file_comment.'",
			`pg_cat`="'.$pg_cat.'",
			`static`='.($_POST['pg_stat']=='on'?'1':'0').'			
			where id='.$id;
		  $act_msg='обновлен';}
		  else {echo '<div>идентфиикатор веб-страницы не найден</div>';}
		 }
		 else {
		  $query='insert into `pg_uploads`(`title`,`name`,`user_id_insert`,`comment`,`pg_cat`,`static`,`type_id`) 
		 	values(
			"'.$file_title.'",
			"'.$file_name.'",
			"'.$_SESSION['id'].'",
			"'.$file_comment.'",
			"'.$pg_cat.'",
			'.($_POST['pg_stat']=='on'?'1':'0').',
			0
			)';
			 $act_msg='добавлен';
			 }

		 if (mysql_query($query))
		 	{ 
			 if ($act_msg=='добавлен') {
				 $query='select max(`id`) as max_id from `pg_uploads`';
			 	 $res=mysql_query($query);
			 	 $max_id=mysql_result($res,0); }
			 else {$max_id=$id;}
		 	 
		 	 echo "<div class=success>Файл (<b>$file_name</b>) успешно $act_msg.<br/>";
		 	 
			  if ($_POST['type_id']==1) { echo '<a href="'.$files_path.$_POST['pg_name'].'.php">'; }
		 	  else {echo "<a href='pg_view.php?pg_id=$max_id'>";}
			 
			 echo "Просмотреть файл на сервере</a>";
			 echo '<br/><a href="?">вернуться к созданию файла</a></div>';
			 }
		 else {echo '<div class=warning>ошибка при записи веб-страницы в БД</div>';}
	   
	 }
	fclose($handle);

	
}else { //вывести форму создания/правки веб-файла

$contents='введите Ваш текст...для проверки конечного HTML-кода используйте кнопку HTML на панели инструментов редактора';

	if (isset($_GET['pg_id']) && intval($_GET['pg_id']>0))	
	{
//---------------выборка данных из БД и кода файла для правки-------
$query='select `pg_uploads`.*,`users`.`fio_short` 
		from `pg_uploads` left join `users` on `users`.`id`=`pg_uploads`.`user_id_insert`';
	$id=intval($_GET['pg_id']); 

	$query.=' where `pg_uploads`.`id`='.$id;
	$res=mysql_query($query);
	if (mysql_num_rows($res)==1) {
		$res_edit=mysql_fetch_array($res);
		
		//открываем, если пользователь Админ, автор страницы или в его группы входит указанная страница
		if ($write_mode===false && $view_all_mode===false && intval($res_edit['user_id_insert'])!=intval($_SESSION['id']) && 
			intval(getScalarVal('select count(*) as cnt from pg_uploads pu
				where pu.id="'.$id.'" and (pu.`type_id`=1 or pu.`static`=1) and pu.id in (
						select pg_id from pg_in_group pig where group_id in (SELECT group_id
						FROM user_in_group
						WHERE user_id ="'.intval($_SESSION['id']).'")
						union 
						select pg_id from pg_in_user pig where user_id='.intval($_SESSION['id']).')'),10)<=0	)
		{
			$err=true;
			echo '<div class=warning>Произошла ошибка при правке страницы. Возможно, страница не является Вашей.</div>';
			echo '<script language="Javascript">setTimeout("window.location.href=\"'.'pg_view.php'.'\"",4000);</script>';
			echo '<div class=text>автоматический переход через 4 сек <a href="pg_view.php">к списку веб-страниц</a></div> ';
		}
		else {
		
			if ($res_edit['type_id']==1){//портальная страницы
				$folder=$files_path; 
			}
			$type_id=$res_edit['type_id'];
			$file_name=$res_edit['name'];
			$file_title=$res_edit['title'];
			$file_user_insert=$res_edit['fio_short'];
			
			if (file_exists($folder.$file_name)) {
				$handle = fopen($folder.$file_name, "r");						
				$contents = fread($handle, filesize($folder.$file_name));	
				$contents=codeHTML($contents);	//убираем PHP-код
				fclose($handle);				
				}
				
		}
	}
}


if (!$err) {
?>
<form name="pg_new" id="pg_new" method="post" action="">

<table border="0" cellpadding="5" class=text>
<tr>
	<td >
	<input type="button" value="Сохранить" onclick="javascript:form_check();" style="width:90;" /> &nbsp; 
	<input type=text name="pg_name" id="pg_name" style="width:300;" value="<?php echo substr($file_name,0,strrpos($file_name,'.'));?>" title="имя страницы на сервере(без расширения)">
	</td>
	<td>имя страницы на сервере (без расширения)
	<a href="#comment" class=help title="будет выдано авт.имя с шаблоном *.htm">?</a>
	</td>
</tr>
<tr>
	<td >
	<input type=text name="pg_title" id="pg_title" style="width:400;" value="<?php echo $file_title;?>"  title="заголовок страницы">
	</td>
	<td>заголовок страницы <a href="#comment" title="взят с исходной страницы" class=help>?</a></td>	
</tr>

	<td align=right>	
	<select id="pg_cat" name="pg_cat">
	<?php
	$listQuery='select id,name from pg_categories order by name';
	echo getFrom_ListItemValue($listQuery,'id','name','pg_cat');
	?>
	</select>
	</td>
	<td>
	категория страницы
	</td>
</tr>
<tr>
	<td colspan=2>
	связанные страницы <a href="#comment" class=help>?</a>: 
	<span id=pg_links_view> 
	<?php 
	$query_='select title,name,type_id,id from pg_uploads where id in(select relate_pg_id from pg_links where pg_id="'.$id.'")';
	$res_=mysql_query($query_);
	$rec_cnt=mysql_num_rows($res_);
	if ($rec_cnt>0 ) {
		echo '('.$rec_cnt.') ';
		while ($a_=mysql_fetch_array($res_))
		{echo ' <a href="'.echoIf($a_['type_id']==1 || $a_['type_id']==3,$a_['name'],'pg_view.php?pg_id='.$a_['id']).'"
				title="просмотреть страницу">'.$a_['title'].'</a>';}
	}else {echo '<span> страниц нет </span>';}
	?>
	</span> 
	<a href="#" onclick="javascript:hide_show('pg_links_view');hide_show('pg_links_edit');">изменить</a>
	<span id=pg_links_edit style="background-color: #e0ffff; width: 400px; height: 400px; position: absolute; border: 1px solid #000000;display:none;padding:5px;">
	<div align=right><a href="#close" onclick="javascript:hide_show('pg_links_view');hide_show('pg_links_edit');">закрыть</a> </div>	

	<?php
	// рисуем множественные списки
	$query_main='select distinct relate_pg_id from pg_links where pg_id="'.$id.'"';
	$query_sub='SELECT `id`,`title` FROM `pg_uploads` order by `title`';
	$item_name_pref='pg_';	
	authors_sec($query_main,$query_sub,$item_name_pref);

	?>
	</span>	
	
	<?php 
	//доступно если страница пользовательская
	if (intval($type_id)==0) {
	?>
	<label><input type=checkbox name="pg_stat" id="pg_stat" <?php if (intval($res_edit['static'])==1) echo 'checked'; ?> onclick="javascript:confirm_stat();"
		title="перевод в статичную страницу для доступа других пользователей"> статичная страница</label>
	<?php } ?>
	</td>
</tr>	
</table>
    <textarea name="content" id="content" rows="15" style="width:99%;" title="текст страницы"><?php echo $contents; ?> </textarea><br/>
	<input name="type_id" id="type_id" type=hidden value="<?php echo $type_id;?>">
	<input name="pg_id" id="pg_id" type=hidden value="<?php echo $id;?>">
	
	<input type="button" value="Сохранить" onclick="javascript:form_check();" />
</form>
<a name=comment></a>
<div class=text><b>Примечание</b>:
	<ul>
		<li>страница поддерживает только HTML, никакие клиентские/серверные скрипты не допускаются</li>
		<li>Вы можете вставить оформленный текст из MsWord, воспользуйтесь аналогичной кнопкой на панели инструментов редактора страницы</li>
		<li>рекомендуется использовать <b>&lt;div class=text&gt; <i>для основного текста</i>&lt;/div&gt;, <br/>
		&lt;h4&gt;<i> для заголовков </i>&lt;/h4&gt; или &lt;div class=main&gt; <i>для заголовков</i>&lt;/div&gt;</b></li>
		
		<!--li>при отражение загруженных страниц (тип=2) используются фреймы, для созданных через редактор - слои (лучше для поисковиков)</li-->
	</div>
<?php
}
}

//--------------------------------

echo $end1;
include "display_voting.php";
define("CORRECT_FOOTER", true);
echo $end2; include('footer.php'); 

?>