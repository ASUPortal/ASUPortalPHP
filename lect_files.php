<?php
include 'authorisation.php';
include ('master_page_short.php');


  if (isset($_GET['toinsertfile']))
   {

    $resa01=mysql_query ('select nameSubject from subjects where id="'.$_SESSION['id'].'" and
    nameFolder="'.$_GET['folder'].'"');
    $a01=mysql_fetch_array($resa01);
    echo '<div class="text"><a href="p_administration.php">Консоль управления преподавателя/ </a>
    <a href="lect_library.php">Пособия/ </a>
    <a href="lect_library.php?go=1&toopen='.$_GET['folder'].'">Файлы/ </a>
    <a href="lect_files.php?toinsertfile=1&folder='.$_GET['folder'].'">Добавление файла</a></div><br>
    <div class="main">Добавление файла</div><br>
	<div class=text style="text-align:center;"> для экономии дискового пространства сервера и увеличения скорости загрузки файлов, <br>
старайтесь файлы сжимать архиватором WinRar, загрузить архиватор Вы можете <a href="apps/wrar34b5ru.exe"> <u>здесь</u> </a></div><br>
    <div class="middle">'.$a01['nameSubject'].'</div><br>
    <form action="lect_files.php" method="post" enctype="multipart/form-data">
    <div class="text">
    <b>Размер загружаемого файла не должен превышать '.$upload_max_filesize.' Мб.</b><br><br>
    Выберите файл для загрузки *<br>
    <input type="file" name="lfile" class="text2" size="50"><br><br>
    Имя файла на сервере *<br>
    (под этим названием пользователь увидит его в разделе)<br>
    <input type="text" name="insertbrowserFile" class="text2" size="50"><br><br>
    Сопутствующие ссылки (ПО, эл.ресурсы)<br>
    (каждая ссылка с новой строки, набирать полностью, 
		например <i><u>http://asu-ugatu.ueuo.com</u></i> или <u><i>ftp://anonymous@10.61.2.63/asu</i></u>)<br>
		Вы также можете указать текстовые надписи, они будут выделены жирным <i><u>(в тексте надписи не должно быть слов "www.","http://","ftp://")</i></u><br>
    <textarea name="add_link" class="text2" rows=5 cols=80></textarea><br><br>
    <input type="submit" name="gotoinsertfile" value="Загрузить" class="button">
    <input type="hidden" name="folder" value="'.$_GET['folder'].'">
    </div><form>';

   }
  if (isset($_POST['gotoinsertfile']))
   {

      $resa02=mysql_query ('select nameSubject from subjects where id="'.$_SESSION['id'].'" and
      nameFolder="'.$_POST['folder'].'"');
      $a02=mysql_fetch_array($resa02);

      echo '<div class="text"><a href="p_administration.php">Консоль управления преподавателя/ </a>
      <a href="lect_library.php">Пособия/ </a>
      <a href="lect_library.php?go=1&toopen='.$_POST['folder'].'">Файлы/ </a>
      <a href="lect_files.php?toinsertfile=1&folder='.$_POST['folder'].'">Добавление файла</a></div><br>
      <div class="main">Добавление файла</div><br>
	  <div class=text style="text-align:center;"> для экономии дискового пространства сервера и увеличения скорости загрузки файлов, <br>
старайтесь файлы сжимать архиватором WinRar, загрузить архиватор Вы можете <a href="apps/wrar34b5ru.exe"> <u>здесь</u> </a></div><br>
      <div class="middle">'.$a02['nameSubject'].'</div><br>
      <div class="warning">';

     $err=false;
    if ($_FILES['lfile']['name']=="" || $_FILES['lfile']['size']==0)
     {
      echo("<div>Выберите файл для загрузки!</div>");$err=true;
     }

    if ($_POST['insertbrowserFile']=="")
     {
      echo('<div>Поле "Имя файла на сервере" не заполнено!</div>');$err=true;
     }
	$file_name=Trans_file_word($_FILES['lfile']['name']);
	if (!test_file($file_name,$_FILES['lfile']['size'])) 
	{
	    $err=true;		
	}
     if ($err==true) {
	  echo '</div><br><form action="lect_files.php" method="post" enctype="multipart/form-data">
      <div class="text"><b>Размер загружаемого файла не должен превышать '.$upload_max_filesize.' Мб.</b><br><br>
      Выберите файл для загрузки<br>
      <input type="file" name="lfile" class="text2" size="50"><br><br>
      Имя файла на сервере<br>
      (под этим названием пользователь увидит его в разделе)<br>
      <input type="text" name="insertbrowserFile" class="text2" size="50"><br><br>
    Сопутствующие ссылки (ПО, эл.ресурсы)<br>
    (каждая ссылка с новой строки, набирать полностью, 
		например <i><u>http://asu-ugatu.ueuo.com</u></i> или <u><i>ftp://anonymous@10.61.2.63/asu</i></u>)<br>
		Вы также можете указать текстовые надписи, они будут выделены жирным <i><u>(в тексте надписи не должно быть слов "www.","http://","ftp://")</i></u><br><br>
    <textarea name="add_link" class="text2" rows=5 cols=80></textarea><br><br>
      <input type="submit" name="gotoinsertfile" value="Загрузить" class="button">
      <input type="hidden" name="folder" value="'.$_POST['folder'].'">
      </div><form>';

}

    $_POST['insertbrowserFile']=trim($_POST['insertbrowserFile']);
    $_FILES['lfile']['name']=trim($_FILES['lfile']['name']);
    $browserFile_trans=Trans_file_word($_POST['insertbrowserFile']);

    $_POST['insertbrowserFile']=Upper_word($_POST['insertbrowserFile']);
    $_FILES['lfile']['name']=Trans_file_word($_FILES['lfile']['name']);
    $_POST['insertbrowserFile']=htmlspecialchars($_POST['insertbrowserFile'],ENT_COMPAT );
    $_POST['insertbrowserFile']=str_replace ("\\","\\\\",$_POST['insertbrowserFile']);
    $res022=mysql_query ('select nameFile from files where id="'.$_SESSION['id'].'" and
    nameFolder="'.$_POST['folder'].'" and browserFile="'.$_POST['insertbrowserFile'].'"');
    $bbb=mysql_fetch_array($res022);
    if (mysql_num_rows($res022)==1)
     {
      if ($bbb['nameFile']!=$_FILES['lfile']['name'])
       {
        echo error_msg('Данное "имя файла на сервере" уже закреплено за другим файлом!');
       }
     }
    $res01=mysql_query ('select * from files where id="'.$_SESSION['id'].'"
    and nameFolder="'.$_POST['folder'].'" and nameFile="'.$_FILES['lfile']['name'].'"');
    //echo ' tmp_name='.$_FILES['lfile']['tmp_name'].' ---'.'library/'.$_POST['folder'].'/'.$_FILES['lfile']['name'].'<hr>';
	if (!file_exists('library/'.$_POST['folder'])) {
		mkdir('library/'.$_POST['folder']); 
		echo '<div class=success>создан каталог для хранения файлов</div>'; 
		}
	if(copy($_FILES['lfile']['tmp_name'], 'library/'.$_POST['folder'].'/'.$_FILES['lfile']['name']))
     {
      if (mysql_num_rows($res01)==0)
       {
        if ($res1=mysql_query ('insert into files (id,nameFolder,nameFile,browserFile,browserFile_trans,entry,date_time,add_link)
        values ("'.$_SESSION['id'].'","'.$_POST['folder'].'","'.$_FILES['lfile']['name'].'",
        "'.$_POST['insertbrowserFile'].'","'.$browserFile_trans.'","0","'.date("Y-m-d H:i:s").'","'.$_POST['add_link'].'")'))
         {
          
		  echo '<script language="Javascript">setTimeout("window.location.href=\"lect_library.php?go=1&toopen='.$_POST['folder'].'\"",2000);</script>
          <div class="middle_lite">Файл <b>'.$_FILES['lfile']['name'] .'</b>
          успешно загружен на сайт.</div>';

         }
        else
         {
          echo error_msg('He удалось занести в базу данные по файлу');
         }
       }
      else
       {
        if ($res02=mysql_query ('update files set browserFile="'.$_POST['insertbrowserFile'].'",
        browserFile_trans="'.$browserFile_trans.'",add_link="'.$_POST['add_link'].'" where
        id="'.$_SESSION['id'].'" and nameFolder="'.$_POST['folder'].'" and nameFile="'.$_FILES['lfile']['name'].'"'))
         {//
          echo '<script language="Javascript">setTimeout("window.location.href=\"lect_library.php?go=1&toopen='.$_POST['folder'].'\"",2000);</script>
          <div class="middle_lite">Файл <b>'.$_FILES['lfile']['name'] .'</b>
          успешно перезаписан.</div>';

         }
        else
         {
          echo error_msg('He удалось занести в базу перезаписанные данные по файлу');
         }
       }
     }
    else
     {
      echo error_msg('He удалось скопировать "'.$_FILES['lfile']['name'].'"');
     }

   }
  if (isset($_GET['toupdatefile']))
   {

    $res2=mysql_query ('select * from files where id="'.$_SESSION['id'].'" and 
    		nameFolder="'.$_GET['folder'].'" and nameFile="'.$_GET['toupdatefile'].'"');

    $res2a=mysql_query ('select nameSubject from subjects where id="'.$_SESSION['id'].'" and
    		nameFolder="'.$_GET['folder'].'"');
    $ca=mysql_fetch_array($res2a);
    $c=mysql_fetch_array($res2);

    echo '<div class="text"><a href="p_administration.php">Консоль управления преподавателя/</a>
    <a href="lect_library.php">Пособия/ </a>
    <a href="lect_library.php?go=1&toopen='.$_GET['folder'].'">Файлы/ </a>
    <a href="lect_files.php?folder='.$_GET['folder'].'&toupdatefile='.$_GET['toupdatefile'].'">Изменение параметров файла</a></div><br>
    <div class="main">Изменение параметров файла</div><br>
    <div class="middle">'.$ca['nameSubject'].'</div><br>
    <form action="lect_files.php" method="get">
    <div class="text">
    Название файла<br>
    <input type="text" name="nameFile1" class="text2" size="50" value="'.$_GET['toupdatefile'].'" disabled><br>
    Имя файла на сервере<br>
    <input type="text" name="updatebrowserFile" class="text2" size="50" value="'.$c['browserFile'].'"><br><br>
    Сопутствующие ссылки (ПО, эл.ресурсы)<br>
    (каждая ссылка с новой строки, набирать полностью, 
		например <i><u>http://asu-ugatu.ueuo.com</u></i> или <u><i>ftp://anonymous@10.61.2.63/asu</i></u>)<br>
		Вы также можете указать текстовые надписи, они будут выделены жирным <i><u>(в тексте надписи не должно быть слов "www.","http://","ftp://")</i></u><br><br>
    <textarea name="add_link" class="text2" rows=5 cols=80>'.$c['add_link'].'</textarea><br><br>
    <input type="submit" name="gotoupdatefile" value="Обновить" class="button">
    <input type="hidden" name="folder" value="'.$_GET['folder'].'">
    <input type="hidden" name="nameFile" value="'.$_GET['toupdatefile'].'">
    </div><form>';

   }
  if (isset($_GET['gotoupdatefile']))
   {

    $_GET['updatebrowserFile']=trim($_GET['updatebrowserFile']);
    $browserFile_trans1=Trans_file_word($_GET['updatebrowserFile']);
    $_GET['updatebrowserFile']=Upper_word($_GET['updatebrowserFile']);
    //$_GET['updatebrowserFile']=htmlspecialchars($_GET['updatebrowserFile'],ENT_COMPAT );
    //$_GET['updatebrowserFile']=str_replace ("\\","\\\\",$_GET['updatebrowserFile']);
    $res30=mysql_query ('select * from files where id="'.$_SESSION['id'].'"
    and nameFolder="'.$_GET['folder'].'" and nameFile="'.$_GET['nameFile'].'" and
    browserFile="'.$_GET['updatebrowserFile'].'"');
    if(mysql_num_rows($res30)==1)
     {

	  echo '<script language="Javascript">setTimeout("window.location.href=\"lect_library.php?go=1&toopen='.$_GET['folder'].'\"",200);</script>';
	  
     }
    $res32=mysql_query ('select nameFile from files where id="'.$_SESSION['id'].'" and
    nameFolder="'.$_GET['folder'].'" and browserFile="'.$_GET['updatebrowserFile'].'"');
    $ddd=mysql_fetch_array($res32);
    if (mysql_num_rows($res32)==1)
     {
      if ($ddd['nameFile']!=$_GET['nameFile'])
       {
        echo error_msg('Данное "имя файла на сервере" уже закреплено за другим файлом!');
       }
     }
    if (!$res31=mysql_query ('update files set browserFile="'.$_GET['updatebrowserFile'].'",
    browserFile_trans="'.$browserFile_trans1.'",add_link="'.$_GET['add_link'].'" where
    id="'.$_SESSION['id'].'" and nameFolder="'.$_GET['folder'].'" and nameFile="'.$_GET['nameFile'].'"'))
     {
      error_msg('Не смог занести в базу изменения');
     }

	echo '<script language="Javascript">setTimeout("window.location.href=\"lect_library.php?go=1&toopen='.$_GET['folder'].'\"",2000);</script>
    <div class="middle_lite">Файл <b>'.$_GET['nameFile'] .'</b> успешно обновлен.</div>';

   }
  if (isset($_GET['gotodeletefile']))
   {

    $res4=mysql_query ('select * from files where id="'.$_SESSION['id'].'"
    and nameFolder="'.$_GET['folder'].'" and nameFile="'.$_GET['gotodeletefile'].'"');
    if(mysql_num_rows($res4)==1)
     {
      if ($res40=mysql_query ('delete from files where id="'.$_SESSION['id'].'"
      and nameFolder="'.$_GET['folder'].'" and nameFile="'.$_GET['gotodeletefile'].'"'))
       {
        if (unlink ('library/'.$_GET['folder'].'/'.$_GET['gotodeletefile']))
         {
          
		  echo '<script language="Javascript">setTimeout("window.location.href=\"lect_library.php?go=1&toopen='.$_GET['folder'].'\"",2000);</script>
          <div class="middle_lite">Файл <b>'.$_GET['gotodeletefile'] .'</b> успешно удален.</div>';

         }
        else
         {
          echo '<br>  <div class="warning">Не могу удалить файл</div>';
 
          }
       }
      else
       {
        echo '<br>  <div class="warning">Не могу удалить данные из базы</div>';
       }
     }
    else
     {
      echo '<script language="Javascript">setTimeout("window.location.href=\"lect_library.php?go=1&toopen='.$_GET['folder'].'\"",200);</script>';
     }
   }

        echo $end1;
        include "display_voting.php";
define("CORRECT_FOOTER", true);
        echo $end2; include('footer.php'); 
?>