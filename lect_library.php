<?php
include 'authorisation.php';
include ('master_page_short.php');


function display_subjects()
 {
  global $cons_of_cont_lect,$end1,$end2;
  global $sql_host,$sql_base,$sql_login,$sql_passw;

  $res=mysql_query ('select subjects.name,documents.nameFolder,documents.user_id,(select count(files.id) from files where files.id='.intval($_SESSION['id']).' and files.nameFolder=documents.nameFolder) as fCnt  
		from subjects inner join documents on documents.subj_id=subjects.id where documents.user_id='.$_SESSION['id'].' order by subjects.name');
  if(mysql_num_rows($res)==0)
   {
    echo '<div class="text"><a href="p_administration.php">Консоль управления преподавателя/</a>
    <a href="lect_library.php">Пособия</a></div><br>
    <div class="main">Пособия</div><br>';
    echo '<div class="middle_lite">Нет предметов для просмотра.<br><a href="?go=1&toinsert=1">Добавить новый предмет</a></div><br>';
   }
  echo '<div class="text"><a href="p_administration.php">Консоль управления преподавателя/</a>
  <a href="lect_library.php">Пособия</a></div><br>
  <div class="main">Пособия</div><br>
  <table border="0" width="640" cellspacing="2" cellpadding="0" align="center" class="middle_lite" bgcolor="#FFFFFF">
  <tr height="20" bgcolor="#d3d3d3" class="middle"><td colspan=3></td><td>Предмет</td></tr>';
  while($a=mysql_fetch_array($res))
   {
    echo '<tr align="left" height="20" bgcolor="#cccccc" onMouseOver=this.style.background="#00FFFF"
    onMouseOut=this.style.background="#cccccc">
    <td align="center" width="20"><a href="?go=1&toopen='.$a['nameFolder'].'" title="просмотреть"><img src="./images/design/folder.gif" border="0"></a></td>
    <td align="center" width="20"><a href="?go=1&toupdate='.$a['nameFolder'].'" title="изменить"><img src="./images/toupdate.png" border="0"></a></td>';
    ?><td align="center" width="20"><a href="?go=1&gotodelete=<?php echo $a['nameFolder'];?>" title="удалить"
    onClick="return window.confirm('<?php $aa=addcslashes($a['name'],"\'");
    echo 'Удалить `'.$aa.'`?'; ?>')">
    <img src="./images/todelete.png" border="0"></a></td><td>&nbsp;<?php echo $a['name'].' ('.$a1['fCnt'].')';?></td></tr>
    <?php
    if($a1=mysql_fetch_array($res))
    {
     echo '<tr align="left" height="20" bgcolor="#dddddd" onMouseOver=this.style.background="#00FFFF"
     onMouseOut=this.style.background="#dddddd">
     <td align="center" width="20"><a href="?go=1&toopen='.$a1['nameFolder'].'" title="просмотреть"><img src="./images/design/folder.gif" border="0"></a></td>
     <td align="center" width="20"><a href="?go=1&toupdate='.$a1['nameFolder'].'" title="изменить"><img src="./images/toupdate.png" border="0"></a></td>';
     ?>
	 <td align="center" width="20">
     <a href="?go=1&gotodelete=<?php echo $a1['nameFolder'];?>" title="удалить" onClick='return window.confirm("<?php $aa=addcslashes($a1['name'],"\'");
     echo 'Удалить `'.$aa.'`?'; ?>
	 ")'>
     <img src="./images/todelete.png" border="0"></a></td><td>&nbsp;<?php echo $a1['name'].' ('.$a1['fCnt'].')';?></td></tr><?php
    }
   }
  //mysql_close();
  echo '</table><br>
  <div class="middle_lite"><a href="?go=1&toinsert=1">Добавить новый предмет</a></div><br>';
  echo $end1;
  include "display_voting.php";
     define("CORRECT_FOOTER", true);
  echo $end2; include('footer.php'); 
 }
/*function Upper_word($word)
 {
  global $orig_bigRUS,$orig_smallRUS,$transRUS,$orig_bigENG,$transENG;
  $word=trim($word);
  $b=substr($word,0,1);
  if($b=="'" || $b=='"')
   {
    $b=substr($word,1,1);
    for ($b1=0;$b1<count($orig_smallRUS);$b1++)
     {
      if($b==$orig_smallRUS[$b1])
       {
        $b=$orig_bigRUS[$b1];
        $word=substr_replace($word,$b,1,1);
        break;
       }
     }
    for ($b2=0;$b2<count($transENG);$b2++)
     {
      if($b==$transENG[$b2])
       {
        $b=$orig_bigENG[$b2];
        $word=substr_replace($word,$b,1,1);
        break;
       }
     }
   }
  else
   {
    for ($b1=0;$b1<count($orig_smallRUS);$b1++)
     {
      if($b==$orig_smallRUS[$b1])
       {
        $b=$orig_bigRUS[$b1];
        $word=substr_replace($word,$b,0,1);
        break;
       }
     }
    for ($b2=0;$b2<count($transENG);$b2++)
     {
      if($b==$transENG[$b2])
       {
        $b=$orig_bigENG[$b2];
        $word=substr_replace($word,$b,0,1);
        break;
       }
     }
   }
  return $word;
 }*/
/*function Trans_file_word($word1)
 {
  global $transRUS,$orig_bigRUS,$orig_smallRUS,$transENG,$orig_bigENG;
  for ($b03=0;$b03<count($transRUS);$b03++)
   {
    $word1=str_replace ($transRUS[$b03],'',$word1);
   }
  $word1=str_replace ('"','',$word1);
  $word1=str_replace ('\\','',$word1);
  $word1=str_replace ('{','',$word1);
  $word1=str_replace ('}','',$word1);
  $word1=trim($word1);
  for ($b3=0;$b3<count($transRUS);$b3++)
   {
    $word1=str_replace ($orig_bigRUS[$b3],$transRUS[$b3],$word1);
    $word1=str_replace ($orig_smallRUS[$b3],$transRUS[$b3],$word1);
   }
  for ($b4=0;$b4<count($transENG);$b4++)
   {
    $word1=str_replace ($orig_bigENG[$b4],$transENG[$b4],$word1);
   }
  return $word1;
 }*/
 
if($_SESSION['auth']==1)
 {
  if (!isset($_GET['go']))
   {
    display_subjects();
   }
  else
   {
    if (isset($_GET['toinsert']))
     {
      echo '<div class="text"><a href="p_administration.php">Консоль управления преподавателя/</a>
      <a href="lect_library.php">Пособия/</a>
      <a href="?go=1&toinsert=1">Добавление нового предмета</a></div><br>
      <div class="main">Добавление нового предмета</div><br>
      <form action="lect_library.php" method="get">
      <div class="text">Название предмета<br>
	  <select name="toinsertname" style="width:300;" class="text2"> 
		<option value="0">...выберите дисциплину ...</option>';
		$query='select id,name from subjects';
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
			 if (isset($tmpval)) { if ($tmpval['subject_id']==$a['id']) {$select_val=' selected';} } 
			echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
			}
echo '	</select>
	  <!--<input type="text" name="toinsertname" size="50" class="text2">--><br><br>
      <input type="reset" value="Очистить" class="button">&nbsp;
      <input type="submit" name="gotoinsert" value="Добавить" class="button">
      <input type="hidden" name="go" value="1">
      </div><form>';
      echo $end1;
      include "display_voting.php";
         define("CORRECT_FOOTER", true);
      echo $end2; include('footer.php'); 
     }
    if (isset($_GET['gotoinsert']))
     {
      function error($message)
       {
        global $cons_of_cont_lect,$end1,$end2;
        echo $cons_of_cont_lect.'<div class="text"><a href="p_administration.php">Консоль управления преподавателя/</a>
        <a href="lect_library.php">Пособия/</a>
        <a href="?go=1&toinsert=1">Добавление нового предмета</a></div><br>
        <div class="main">Добавление нового предмета</div><br><div class="warning">'.$message;
        echo '</div><br><form action="lect_library.php" method="get">
        <div class="text">Название предмета<br><select name="toinsertname" style="width:300;" class="text2"> 
		<option value="0">...выберите дисциплину ...</option>';
		$query='select id,name from subjects';
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
			 if (isset($tmpval)) { if ($tmpval['subject_id']==$a['id']) {$select_val=' selected';} } 
			echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
			}
echo '	</select>
	  <!--<input type="text" name="toinsertname" size="50" class="text2">--><br><br>
        <input type="reset" value="Очистить" class="button">&nbsp;
        <input type="submit" name="gotoinsert" value="Добавить" class="button">
        <input type="hidden" name="go" value="1">
        </div><form>';
        echo $end1;
           define("CORRECT_FOOTER", true);
        include "display_voting.php";
        echo $end2; include('footer.php'); 
       }
      if($_GET['toinsertname']=="")
       {
        error('Поле "Название предмета" не заполнено!'); 
        
       }
      $_GET['toinsertname']=trim($_GET['toinsertname']);
      $name_trans=Trans_file_word($_GET['toinsertname']);
 /*     if(!mysql_connect($sql_host,$sql_login,$sql_passw))
       {
        echo $cons_of_cont_lect.'Не могу соединиться с сервером Базы Данных';
        echo $end1;

       }
      if(!mysql_select_db($sql_base))
       {
        echo $cons_of_cont_lect.'Не могу выбрать базу';

       }   */
      $_GET['toinsertname']=Upper_word($_GET['toinsertname']);
      $_GET['toinsertname']=htmlspecialchars($_GET['toinsertname'],ENT_COMPAT );
      $true_name=$_GET['toinsertname'];
      $_GET['toinsertname']=str_replace ("\\","\\\\",$_GET['toinsertname']);
      $res01=mysql_query ('select subjects.name,documents.nameFolder,documents.user_id  
		from subjects inner join documents on documents.subj_id=subjects.id where documents.user_id="'.$_SESSION['id'].'"
      and subjects.name="'.$_GET['toinsertname'].'"');
      if(mysql_num_rows($res01)==1)
       {
        Header("Location:lect_library.php");
       }
      $res1=mysql_query ('select nameFolder from documents');
      $nameFolder=0;
      while($c=mysql_fetch_array($res1))
       {
        if($c['nameFolder']>$nameFolder)
         {
          $nameFolder=$c['nameFolder'];
         }
       }
      $nameFolder++;
      if(!$res2=mysql_query ('insert into documents (user_id,nameFolder,subj_id)
      values ("'.$_SESSION['id'].'","'.$nameFolder.'","'.$_GET['toinsertname'].'")'))
       {
        error('Недопустимый символ в названии предмета!'); 
       }
      if(!mkdir("./library/".$nameFolder,0777))
       {
        error('Ошибка создания каталога для предмета!');
       }
      //mysql_close();
      echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'\"",2000);</script>
      <div class="middle_lite">Предмет <!--<b>"'.$true_name.'"</b>--> успешно добавлен.</div>';
      echo $end1;
      include "display_voting.php";
         define("CORRECT_FOOTER", true);
      echo $end2; include('footer.php'); 
     }
    if(isset($_GET['gotodelete']))
     {

      $res3=mysql_query ('select subjects.name,documents.nameFolder,documents.user_id  
		from subjects inner join documents on documents.subj_id=subjects.id where documents.user_id="'.$_SESSION['id'].'"
      and documents.nameFolder="'.$_GET['gotodelete'].'"');
      if(mysql_num_rows($res3)==1)
       {
        $d=mysql_fetch_array($res3);
        function deleteSubject ($directory)
         {
          $dir4 = opendir($directory);
          while(($file = readdir($dir4)))
           {
            if ( is_file ($directory."/".$file))
             {
              unlink ($directory."/".$file);
             }
            else if ( is_dir ($directory."/".$file) &&  ($file != ".") && ($file != ".."))
             {
              deleteSubject ($directory."/".$file);
             }
           }
          closedir ($dir4);
          rmdir ($directory);
         }
        deleteSubject ("./library/".$_GET['gotodelete']);
        $res30=mysql_query ('delete from documents where user_id="'.$_SESSION['id'].'"
        and nameFolder="'.$_GET['gotodelete'].'"');
        $res301=mysql_query ('delete from files where id="'.$_SESSION['id'].'"
        and nameFolder="'.$_GET['gotodelete'].'"');
        echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'\"",2000);</script>
        <div class="middle_lite">Предмет <b>"'.$d['name'].'"</b> удалён.</div>';
        echo $end1;
        include "display_voting.php";
           define("CORRECT_FOOTER", true);
        echo $end2; include('footer.php'); 
        //mysql_close();
       }
      else
       {
        Header("Location:lect_library.php");
       }
     }
    if(isset($_GET['toupdate']))
     {

      $res4=mysql_query ('select subjects.name,documents.subj_id,documents.nameFolder,documents.user_id  
		from subjects inner join documents on documents.subj_id=subjects.id where documents.user_id="'.$_SESSION['id'].'"
      and documents.nameFolder="'.$_GET['toupdate'].'"');
      $f=mysql_fetch_array($res4);
      
      echo $cons_of_cont_lect.'<div class="text"><a href="p_administration.php">Консоль управления преподавателя/</a>
      <a href="lect_library.php">Пособия/</a>
      <a href="?go=1&toupdate='.$_GET['toupdate'].'">Изменение параметров предмета</a></div><br>
      <div class="main">Изменение параметров предмета</div><br>
      <form action="lect_library.php" method="get">
      <div class="text">Название предмета<br>
	  <!--<input type="text" name="toupdatename" size="50" value="'.$f['name'].'" class="text2">-->
	  <select name="toupdatename" style="width:300;" class="text2"> 
		<option value="0">...выберите дисциплину ...</option>';
		$query='select id,name from subjects';
		
		$res=mysql_query($query);
		echo 'mysql_num_rows='.mysql_num_rows($res);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
			 if (isset($f)) { if ($f['subj_id']==$a['id']) {$select_val=' selected';} } 
			echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
			}
echo '	</select>	  <br><br>
      <input type="submit" name="gotoupdate" value="Изменить" class="button">
      <input type="hidden" name="go" value="1">
      <input type="hidden" name="nameFolder1" value="'.$f['nameFolder'].'">
      </div><form><br><div class="middle_lite"><a href="?go=1&toupdate='.$f['nameFolder'].'">
        Отменить изменения</a></div><br>';
      echo $end1;
      include "display_voting.php";
         define("CORRECT_FOOTER", true);
      echo $end2; include('footer.php'); 
      //mysql_close();
     }
    if(isset($_GET['gotoupdate']))
     {
      function error1($message)
       {
        global $cons_of_cont_lect,$end1,$end2;

        $res5=mysql_query ('select subjects.name,documents.nameFolder,documents.user_id  
		from subjects inner join documents on documents.subj_id=subjects.id where documents.user_id="'.$_SESSION['id'].'"
        and documents.nameFolder="'.$_GET['nameFolder1'].'"');
        $e=mysql_fetch_array($res5);
        //mysql_close();
        echo $cons_of_cont_lect.'<div class="text"><a href="p_administration.php">Консоль управления преподавателя/</a>
        <a href="lect_library.php">Пособия/</a>
        <a href="?go=1&toupdate='.$_GET['nameFolder1'].'">Изменение параметров предмета</a></div><br>
        <div class="main">Изменение параметров предмета</div><br><div class="warning">'.$message;
        echo '<form action="lect_library.php" method="get">
        <div class="text">Название предмета<br><input type="text" name="toupdatename" size="50"
        value="'.$e['name'].'" class="text2"><br><br>
        <input type="submit" name="gotoupdate" value="Изменить" class="button">
        <input type="hidden" name="go" value="1">
        <input type="hidden" name="nameFolder1" value="'.$e['nameFolder'].'">
        </div><form><br><div class="middle_lite"><a href="?go=1&toupdate='.$e['nameFolder'].'">
        Отменить изменения</a></div><br>';
        echo $end1;
        include "display_voting.php";
           define("CORRECT_FOOTER", true);
        echo $end2; include('footer.php'); 
       }
      if($_GET['toupdatename']=="")
       {
        error1('Поле "Название предмета" осталось пустым!'); 
       }
      $_GET['toupdatename']=trim($_GET['toupdatename']);
      $name_trans1=Trans_file_word($_GET['toupdatename']);

      $_GET['toupdatename']=Upper_word($_GET['toupdatename']);
      $_GET['toupdatename']=htmlspecialchars($_GET['toupdatename'],ENT_COMPAT );
      //$true_name1=$_GET['toupdatename'];
      $_GET['toupdatename']=str_replace ("\\","\\\\",$_GET['toupdatename']);
      $res50=mysql_query ('select subjects.name,documents.nameFolder,documents.user_id  
		from subjects inner join documents on documents.subj_id=subjects.id where documents.user_id="'.$_SESSION['id'].'"
      and subjects.name="'.$_GET['toupdatename'].'"');
      if(mysql_num_rows($res50)==1)
       {
        Header("Location:lect_library.php");
       }
      $res500=mysql_query ('select subjects.name,documents.nameFolder,documents.user_id  
		from subjects inner join documents on documents.subj_id=subjects.id where documents.user_id="'.$_SESSION['id'].'"
      and documents.nameFolder="'.$_GET['nameFolder1'].'"');
      $eee=mysql_fetch_array($res500);
      $res51=mysql_query ('update documents set subj_id="'.$_GET['toupdatename'].'" 
      where user_id="'.$_SESSION['id'].'" and nameFolder="'.$_GET['nameFolder1'].'"');
	  //mysql_close();
      echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'\"",2000);</script>
      <div class="middle_lite">Предмет <b>"'.$eee['name'].'"</b> успешно обновлен </div>';
      echo $end1;
      include "display_voting.php";
         define("CORRECT_FOOTER", true);
      echo $end2; include('footer.php'); 
     }
    if(isset($_GET['toopen']))
     {

      $query='select f.nameFile,f.nameFolder,f.add_link,f.browserFile,ft.name as fileType,f.entry
	  	from files f left join file_types ft on ft.id=f.file_type
	  	where f.id="'.$_SESSION['id'].'" and f.nameFolder="'.$_GET['toopen'].'" order by f.browserFile_trans';
      $res6=mysql_query ($query);
      $res60=mysql_query ('select name from subjects where id="'.$_SESSION['id'].'" and nameFolder="'.$_GET['toopen'].'"');
      $g0=mysql_fetch_array($res60);
      
      if(mysql_num_rows($res6)==0)
       {
        echo '<div class="text"><a href="p_administration.php">Консоль управления преподавателя/</a>
        <a href="lect_library.php">Пособия</a>/
        <a href="?go=1&toopen='.$_GET['toopen'].'">Файлы</a></div><br>
        <div class="main">Файлы</div><br><div class="middle">'.$g0['name'].'</div><br>';
        echo '<div class="middle_lite">Нет файлов для просмотра.<br><a href="lect_files.php?go=1&folder='.$_GET['toopen'].'&toinsertfile=1">Добавить новый файл</a></div><br>';

       }
      echo '<div class="text"><a href="p_administration.php">Консоль управления преподавателя/</a>
      <a href="lect_library.php">Пособия/ </a>
      <a href="?go=1&toopen='.$_GET['toopen'].'">Файлы</a></div><br>
      <div class="main">Файлы</div><br>
      <table border="0" width="100%" cellspacing="2" cellpadding="0" align="center" class="middle_lite_other" bgcolor="#FFFFFF">
      <caption class="middle">'.$g0['name'].'</caption>
      <tr height="20" bgcolor="#d3d3d3" class="middle"><td colspan=4>Файлы</td>
      <td><a href="#" title="выгрузка файла на зеркало портала"> тип файла</a></td>
	  <td><a href="#" title="наличии доп.ссылок к файлу пособия"> Примечание</a></td></tr>';
      while($g=mysql_fetch_array($res6))
       {
        echo '<tr align="left" height="20" bgcolor="#dddddd" onMouseOver=this.style.background="#00FFFF"
        onMouseOut=this.style.background="#dddddd">
        <td align="center" width="20"><a href="library/'.$g['nameFolder'].'/'.$g['nameFile'].'" title="просмотреть" target="_blank">';
		
		file_type_img('library/'.$g['nameFolder'].'/'.$g['nameFile']);	//выводим тип файла
		
		echo'</a></td>
        <td align="center" width="20"><a href="lect_files.php?go=1&folder='.$g['nameFolder'].'&toupdatefile='.$g['nameFile'].'" title="изменить"><img src="./images/toupdate.png" border="0"></a></td>';
        ?><td align="center" width="20"><a href="lect_files.php?go=1&folder=<?php echo $g['nameFolder'];?>&gotodeletefile=<?php echo $g['nameFile'];?>" title="удалить"
        onClick="return window.confirm('<?php $gg=addcslashes($g['browserFile'],"\'");
        echo 'Удалить `'.$gg.'`?'; ?>')">
        <img src="./images/todelete.png" border="0"></a></td><td>&nbsp;<?php echo '<a href="#" 
				title="'.$g['nameFile'].'">'.$g['browserFile'].' ('.$g['entry'].')</a>';
		  $add_link='';
		  if (trim($g['add_link'])!='') {$add_link='<a href="#" title="'.$g['add_link'].'"> + </a>';}
		  echo '<td>'.$g['fileType'].'</td>';
		  echo '<td align=center>&nbsp;'.$add_link.'</td>';
		  echo '</tr>';
		

       }
      echo '</table><br>
      <div class=text> Колонка примечание говорит о наличии доп.ссылок к файлу пособия, подведите мышку к "+" для отображения ссылки</div><br> 
	  <div class="middle_lite"><a href="lect_files.php?go=1&folder='.$_GET['toopen'].'&toinsertfile=1">Добавить новый файл</a></div><br>';
      }
   }
 }
else
 {
  Header("Location:p_administration.php");
 }
 
     	echo $end1;
      include "display_voting.php";
define("CORRECT_FOOTER", true);
      echo $end2; include('footer.php'); 
 
?>