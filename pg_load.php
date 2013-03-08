<?php
$pg_title='Добавление веб-страниц';

//echo phpinfo();
$files_path="";

include ('authorisation.php');
include ('master_page_short.php');

echo ' <p class=text><a href="pg_view.php">Просмотреть веб-страницы</a></p>';
echo '<h4>'.$pg_title.'</h4>';

$maxPgsize=2048; //in Kb
$folder='user_pages/';	//куда складываем пользовательские страницы
$folder_upload_pages='upload_pages/';	//имя каталога загруженного архива
$appRarfolder='apps/';	//где лежит rar.exe

$fileTypes='css|jpg|gif|png|jpeg|htm|html'; //типы допустимых файлов при распаковке
//-----------------------------


function rmNR_recurse($file) {	//удаление небезопасных файлов
global $fileTypes;
    if (is_dir($file) && !is_link($file)) {
        foreach(glob($file.'/*') as $sf) {
            if ( !rmNR_recurse($sf) ) {
                //error_log("Failed to remove $sf\n");
                return false;
            }
        }
        return true;
    } else {
	    if (!preg_match('/\.('.$fileTypes.')$/i',$file)) {	unlink($file);	}        
        return true;
    }
}

?>

загрузка только <b>html,zip\rar</b> файлов, с размером до <b><?php echo $maxPgsize;?> Кб</b>
<form name="pg_load" id="pg_load" method="post" enctype="multipart/form-data" class=text>
<table border="0" cellpadding="5" class=text>
  <tr>
    <td><input type="file" name="pg_file" style="width:400;" title="файл для загрузки"></td>
    <td>файл для загрузки <span class=warning>*</span> </td>
  </tr>
  <tr>
    <td><input type=text name="pg_title" style="width:400;" title="заголовок страницы"></td>
    <td>заголовок страницы <span class=warning>*</span></td>
  </tr>
  <tr>
    <td><input type=text name="pg_name" id="pg_name" style="width:400;" title="имя страницы с расширением"></td>
    <td>имя страницы (главной) с расширением <span class=text>(например, index.htm)</span> <span class=warning>*</span></td>
  </tr>  
  <tr>
    <td><input type=text name="comment" style="width:400;"></td>
    <td>примечание</td>
  </tr>
  <tr>
    <td><input name="button" type=button value="загрузить" 
	onClick="javascript:requireFieldCheck(new Array(new Array('pg_file',''),new Array('pg_title','')),'pg_load');"></td>
    <td>      <input name="reset" type=reset value="сбросить"></td>
  </tr>
</table>

</form>

<?php
if (isset($_FILES['pg_file']))
{
	$file_ext=fileExt($_FILES['pg_file']['name']);
	
	
	
	
	if (   ($_FILES['pg_file']['type']=="text/html" || $file_ext=='zip' || $file_ext=='rar' )
		 && $_FILES['pg_file']['size']>0 && $_FILES['pg_file']['size']<=$maxPgsize*1024)
	{
	    // уникальное имя файла
	    $file_item=file_uniq_name($folder.$_FILES['pg_file']['name']);	
		
	    if(copy($_FILES['pg_file']['tmp_name'], $folder.$file_item))
	    {
		echo '<div class=success>Файл: <b>'.$file_item.'</b> загружен успешно</div>';
		//получаем имя файла без расширения
	    
		
		$fName_only=basename($file_item,'.'.$file_ext);// $pgViewName;
	    
		$file_title=$_POST['pg_title'];
		$file_comment=$_POST['comment'];

		
		if ($file_ext=='zip' || $file_ext=='rar')  {//распаковка файла, если он архив

		    // уникальное имя каталога загруженного архива
		    $file_folder=file_uniq_name($folder.$folder_upload_pages.$fName_only);
		    if ($file_folder!='') $file_folder.='/';

		    $zip = new ZipArchive;
		    if ($zip->open($folder.$file_item) === true) {
			$result=$zip->extractTo($folder.$folder_upload_pages.$file_folder);
			$zip->close();
			echo 'Статус распаковки архива: <b>'.
			    ($result?"успешно":"<span class=warning>ошибка</span>").'</b></div>';
		    } else  {
			  // использование exec, более опасный метод, если не подключено расширения
			//echo 'php zip failed';
			
			chdir('apps');
			$cmd_val='rar x -o+ -r "../'.$file_item.'" "'.$folder.$folder_upload_pages.$file_folder.'"';
			
			$result=exec($cmd_val,$output,$return_var);
			chdir('..');
			
			echo '<div>Статус распаковки архива: <b>'.convert_cyr_string($result,'a','w').'</b></div>';
			}
			$pgViewName=$folder_upload_pages.$file_folder;
			echo '<div class=text>временный файл: <b>'.(unlink($folder.$file_item)?'успешно удален':'ошибка удаления').'</b></div>';
		}
		
//-------
		// имя индексной страницы для просмотра
		if ($_FILES['pg_file']['type']=="text/html") $pgViewName.=basename($file_item);		
		else if ($_POST['pg_name']!='') {$pgViewName.=$_POST['pg_name'];}
		else $pgViewName.='index.htm';
//-------		
		  $query="insert into `pg_uploads`(`title`,`name`,`user_id_insert`,`comment`,`type_id`) 
		 	values('$file_title',
			'".$pgViewName."',
			'$_SESSION[id]','$file_comment','2')";
		 //echo $query;
		 if (mysql_query($query))
		 	{ 
		 	 //echo ' mysql_affected_rows='.mysql_affected_rows;
			 $query='select max(`id`) as max_id from `pg_uploads`';
		 	 $res=mysql_query($query);
		 	 $max_id=mysql_result($res,0);
			 echo "<div class=success>Файл (<b>$file_item</b>) успешно сохранен. </div>
			 <div class=text>
			 <a href='pg_view.php?pg_id=$max_id'>просмотреть файл на сервере</a>
			 <br/>
			 <a href='?'>вернуться к созданию файла</a>
			 </div>";
			 }
		 else {echo '<div class=warning>ошибка при записи веб-страницы в БД</div>';}
			
	    }
     }
     else {echo '<div class=warning>размер и\или тип файла ошибочны.</div>';}
}else { echo 'Выберите файл для загрузки';}

?><br>
<div class=text><b>Примечание</b>:
	<ul>
		<li>Вы можете загрузить существующую веб-страницу или даже часть сайта</li>
		<li>если Вы хотите загрузить веб-страницу или даже часть сайта с сохранением на нем всех картинок и другого оформления:<br> 
		- сохраните страницу как "HTML-файл с рисунками" в браузере, дав краткое лат.имя странице;<br>
		- упакуйте архиватором, например WinRar с именем, равным ранее введенным лат.именем страницы (без расширения);<br>
		- выберите Ваш архив для загруки на этой странице и укажите заголовок.</li>
		<li>допустимы только типы-файлов: <b><?php echo $fileTypes; ?></b>, остальные считаются небезопасными и будут удалены после распаковки</li>
		
	</div>
<?php
//--------------------------------

echo $end1;
include "display_voting.php";
echo $end2; include('footer.php'); 

?>