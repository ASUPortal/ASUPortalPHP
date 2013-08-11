<?php
include ('authorisation.php');
include ('master_page_short.php');

//include 'sql_connect.php';
//session_start();
echo     '<form> <div class="main">Нормативно-справочная документация</div></form>';

echo '<div class=text style="text-align:center;"> для экономии дискового пространства сервера и увеличения скорости загрузки файлов, <br>
старайтесь файлы сжимать архиватором WinRar, загрузить архиватор Вы можете <a href="apps/wrar34b5ru.exe"> <u>здесь</u> </a></div><br>';

$folders=array();
//путь						//название
$folders[0][0]='';			$folders[0][1]='';
$folders[1][0]='dolg_instr';$folders[1][1]='должностные инструкции';
$folders[2][0]='edu_stand';	$folders[2][1]='образовательные стандарты';
$folders[3][0]='att_spec';	$folders[3][1]='аттестация специальностей';
$folders[4][0]='uch_plan';	$folders[4][1]='учебные планы';
$folders[5][0]='diplom';	$folders[5][1]='к диплому';
$folders[6][0]='instr';	$folders[6][1]='Интеллектуальная система самообучения и самоорганизации пользователей веб-портала';
$folders[7][0]='moodle';	$folders[7][1]='СДО MOODLE';
$folders[8][0]='practice';	$folders[8][1]='Практика';
$folders[9][0]='umk';	$folders[9][1]='Материалы для оформления УМК';
//$folders[3][0]='';			$folders[3][1]='';

//если папки не существуют система их создаст в library/gost/
//не забудьте прописать папки в файле p_gost_docs_view.php - для просмотра файлов пользователями

function dir_name_by_id($id)
{
	global $folders;
	$dir_name='';
	//$id= substr($a['nameFolder'],4,1);
	$dir_name=$folders[$id][0];
	if ($dir_name!='') {$dir_name=$dir_name.'/';}
	
	return $dir_name;
} 
$act='';

		
		$dir_name='';
		for ($i=1;$i<count($folders);$i++) {//создаем папки, если их нет
			$dir_name=$folders[$i][0];
			if (!file_exists('library/gost/'.$dir_name)) {mkdir('library/gost/'.$dir_name);} 
			}

  if (isset($_GET['act']) && isset($_GET['id_file']))
   {
	  $res=mysql_query("select * from files where nameFolder like 'gost%' and id_file='".$_GET['id_file']."'");
	  $a=mysql_fetch_array($res);
	  if ($_GET['act']=='delete') {
		  if ($res=mysql_query ('delete from files where id_file="'.$_GET['id_file'].'"'))
	       {
		
			$id= substr($a['nameFolder'],4,1);
			$dir_name=dir_name_by_id($id);
			
			if (unlink ('library/'.'gost/'.$dir_name.$a['nameFile']))
	         {  
	           echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'\"",2000);</script>
	          <div class="middle_lite">Файл успешно удален.</div>';
	          echo $end1;
                define("CORRECT_FOOTER", true);
	          include "display_voting.php";
	          echo $end2;include('footer.php'); exit();
			  //echo '<div class="middle_lite">Файл <b>'.$a['nameFile'].'</b> успешно удален.</div>';   
			  }
	        else
	         {     echo '<br>  <div class="warning">Не могу удалить файл</div>';      }
	       }
	      else
	       { echo '<br>  <div class="warning">Не могу удалить данные из базы</div>';    }
       }
	  if ($_GET['act']=='update') {echo 'обновление файла. <a href="gost_docs.php"> Отменить </a>'; $act='update';}
       
     }
 
if (isset($_POST['insertbrowserFile']) ){
    //$_POST['insertbrowserFile']=Upper_word($_POST['insertbrowserFile']);
    $_FILES['lfile']['name']=Trans_file_word($_FILES['lfile']['name']);
    $_POST['insertbrowserFile']=$_POST['insertbrowserFile'];
    //$_POST['insertbrowserFile']=str_replace ("\\","\\\\",$_POST['insertbrowserFile']);
    //$res01=mysql_query ('select * from files where nameFolder="'.'gost'.'" and nameFile="'.$_FILES['lfile']['name'].'"');
		//echo '!!'.$act.'!!';
		
		//$dir_name='';
		$id= substr($_POST['folder_list'],4,1);
		$dir_name=dir_name_by_id($id);
		//$dir_name=$folders[$id][0];
		//echo 'id='.$id.', dir_name='.$dir_name;
		
		//if ($dir_name!='') {$dir_name=$dir_name.'/';}
		
		
		$i=0;
		if (isset($_FILES['lfile']) && trim($_FILES['lfile']['name'])!='' && test_file($_FILES['lfile']['name'],$_FILES['lfile']['size']) ) {
			if (file_exists('library/gost/'.$dir_name.''.$_FILES['lfile']['name']))
			while (file_exists('library/gost/'.$dir_name.''.$_FILES['lfile']['name'])) 
				{
				 $_FILES['lfile']['name']=$i.$_FILES['lfile']['name'];$i++;
				 }
			move_uploaded_file($_FILES['lfile']['tmp_name'], 'library/gost/'.$dir_name.$_FILES['lfile']['name']);
									}
								
		if ($_POST['folder_list']=='') {$_POST['folder_list']='gost';}
		
		  if ($act=='update')
	       {
				 
				 $var1="";
				 if ($_FILES['lfile']['name']!='') {$var1=",nameFile='".$_FILES['lfile']['name']."'";}
				 //if (trim($_POST['insertbrowserFile'])!='') {$var1=",nameFile='".$_FILES['lfile']['name']."'";}
				 
				 $query="select * from files where id_file='".$_GET['id_file']."'";
				 $res=mysql_query($query);

				 $a=mysql_fetch_array($res);
//			copy('library/gost/'.$dir_name.$a['nameFile'].'');	
		$dir_name='';
		$id= substr($a['nameFolder'],4,1);
		$dir_name1=$folders[$id][0];
		//echo 'id='.$id.', dir_name='.$dir_name;
		if ($dir_name1!='') {$dir_name1=$dir_name1.'/';}

		$dir_name='';
		$id= substr($_POST['folder_list'],4,1);
		$dir_name2=$folders[$id][0];
		//echo 'id='.$id.', dir_name='.$dir_name;
		
		if ($dir_name2!='') {$dir_name2=$dir_name2.'/';}


	 	if (trim($a['nameFile'])!='') 
	 	{
			$i=0;
			while (file_exists('library/gost/'.$dir_name2.$a['nameFile'])) 
				{$dir_name2=$dir_name2.$i;$i++;}
		  
		  if (copy('library/gost/'.$dir_name1.$a['nameFile'],'library/gost/'.$dir_name2.$a['nameFile']))
		 	{$_FILES['lfile']['name']=$a['nameFile'];
		 	 unlink('library/gost/'.$dir_name1.$a['nameFile']);
			 echo '<div>файл перемещен успешно </div> ';}
		 else {echo '<div class=warning>файл не перемещен !</div>';}
		}
				 	
				 $query="update files set browserFile='".$_POST['insertbrowserFile']."'".$var1.", nameFolder='".$_POST['folder_list']."',
				 	user_id_update='".$_SESSION['id']."' where id_file='".$_GET['id_file']."'";//}
	       }
	       else {$query='insert into files (id,nameFolder,nameFile,browserFile,entry,date_time)
			        values ("'.$_SESSION['id'].'","'.$_POST['folder_list'].'","'.$_FILES['lfile']['name'].'",
			        "'.$_POST['insertbrowserFile'].'","0","'.date("Y-m-d H:i:s").'")';}
	        //echo $query;
			if ($res1=mysql_query ($query))
	         {
	           echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'\"",2000);</script>
	          <div class="middle_lite">Файл <b>'.$_FILES['lfile']['name'] .'</b> успешно загружен на сайт.</div>';
	          echo $end1;
	          include "display_voting.php";
                 define("CORRECT_FOOTER", true);
	          echo $end2;include('footer.php'); exit();
	         }
	        else    {    echo 'He удалось занести в базу данные по файлу';     }			        
		}

	  $res6=mysql_query ('select * from files where nameFolder like "'.'gost%'.'" order by browserFile_trans');
      if(mysql_num_rows($res6)==0)
       {echo '<div class=text> сейчас не загружено ни одного нормативно-справочного документа </div>';}
      else 
	  {
      echo '<p> <table border="0" width="95%" cellspacing="2" cellpadding="0" align="center" class="middle_lite_other" bgcolor="#FFFFFF">
      <tr height="20" bgcolor="#d3d3d3" class="middle"><td colspan=2></td><td width=400>Файлы</td><td width="10">создал / <u>обновил</u></td></tr>';
      for ($i=count($folders)-1;$i>0;$i--)
      	{
		  $query='select files.*,users.FIO_short as FIO_short_ins,users.login as user_login  
		  	from files left join users on users.id=files.id 
			where files.nameFolder="'.'gost'.$i.'" order by files.browserFile_trans';
		  
		  $res=mysql_query ($query);
		  echo "<tr><td colspan=4><a href=# onclick=javascript:hide_show('gost".$i."');><img src='images/design/folder.gif' border=0>".$folders[$i][1].'('.mysql_num_rows($res).')</a></td></tr>';
		  echo '<tr><td colspan=4><div style="display:none;" id="gost'.$i.'" name="gost'.$i.'"><table class="middle_lite_other" width="100%">';
		  while($g1=mysql_fetch_array($res))
	       {
			$query_='select users.FIO_short as user_id_update,users.login as user_login from files left join users on files.user_id_update=users.id 
				where files.id_file='.$g1['id_file'].' limit 0,1';
			$res_=mysql_query($query_);$g1_=mysql_fetch_array($res_);
			
			
			$id= substr($g1['nameFolder'],4,1);
			
			$dir_name=$folders[$id][0];
			//echo 'id='.$id.', dir_name='.$dir_name;
			
			if ($dir_name!='') {$dir_name=$dir_name.'/';}

	          echo '<tr align="left" height="20" bgcolor="#dddddd" onMouseOver=this.style.background="#00FFFF"
	          onMouseOut=this.style.background="#dddddd">
	          <td align="center" width="20"><a href="library/gost/'.$dir_name.$g1['nameFile'].'" title="просмотреть" target="_blank"><img alt="просмотреть" src="./images/toopen.png" border="0"></a></td>
	          <td align="center" width="20"><a href="?act=update&id_file='.$g1['id_file'].'#file_upload" title="изменить"><img alt="изменить" src="./images/toupdate.png" border="0"></a></td>';
	          ?><td align="center" width="20"><a href="?act=delete&id_file=<?php echo $g1['id_file'];?>" title="удалить"
	          onClick="return window.confirm('<?php $gg=$g1['browserFile'];
	          echo 'Удалить `'.$gg.'`?'; ?>')">
	          <img alt="удалить" src="./images/todelete.png" border="0"></a></td><td width=380>&nbsp;<?php echo '<a href="#" title="'.$g1['id_file'].'">'.$g1['browserFile'].
	          '</a>';?></td><td width="80"><?php 
			  	if(trim($g1['FIO_short_ins'])=="") {$g1['FIO_short_ins']=$g1['user_login'];}
			  	if(trim($g1_['user_id_update'])=="") {$g1_['user_id_update']=$g1_['user_login'];}
			  	
			  	echo '<small style="color:#666666;">'.$g1['FIO_short_ins'].' / <u>'.$g1_['user_id_update'].'</u></small> ';?></td></tr><?php
	         }
	    	echo '</table></div></td></tr>';
         }
      /////////для $i=0  т.е. для файлов без папки
      	
		  echo "<tr><td colspan=4><a href=# onclick=javascript:hide_show('gost".$i."');>".$folders[$i][1]."</a></td></tr>";
		  
		  $query='select files.*,users.FIO_short as FIO_short_ins,users.login as user_login from files left join users on users.id=files.id 
		  	where files.nameFolder="'.'gost'.'" order by files.browserFile_trans';
		  echo '<tr><td colspan=4>&nbsp;</td></tr>';
		  $res=mysql_query ($query);
		  echo '<tr><td colspan=4><div style="display:;" id="gost'.$i.'" name="gost'.$i.'"><table class="middle_lite_other" width="100%">';
		  while($g1=mysql_fetch_array($res))
	       {
			$query_='select users.FIO_short as user_id_update,users.login as user_login from files left join users on files.user_id_update=users.id 
				where files.id_file='.$g1['id_file'].' limit 0,1';
			$res_=mysql_query($query_);$g1_=mysql_fetch_array($res_);
			//echo $query_;
			
	          echo '<tr align="left" height="20" bgcolor="#dddddd" onMouseOver=this.style.background="#00FFFF"
	          onMouseOut=this.style.background="#dddddd">
	          <td align="center" width="20"><a href="library/'.$g1['nameFolder'].'/'.$g1['nameFile'].'" title="просмотреть" target="_blank"><img alt="просмотреть" src="./images/toopen.png" border="0"></a></td>
	          <td align="center" width="20"><a href="?act=update&id_file='.$g1['id_file'].'#file_upload" title="изменить"><img alt="изменить" src="./images/toupdate.png" border="0"></a></td>';
	          ?><td align="center" width="20"><a href="?act=delete&id_file=<?php echo $g1['id_file'];?>" title="удалить"
	          onClick="return window.confirm('<?php $gg=$g1['browserFile'];
	          echo 'Удалить `'.$gg.'`?'; ?>')">
	          <img alt="удалить" src="./images/todelete.png" border="0"></a></td><td width=380>&nbsp;<?php echo '<a href="#" title="'.$g1['id_file'].'">'.$g1['browserFile'].
	          '</a>';?></td><td width="80"><?php 
			  	if(trim($g1['FIO_short_ins'])=="") {$g1['FIO_short_ins']=$g1['user_login'];}
			  	if(trim($g1_['user_id_update'])=="") {$g1_['user_id_update']=$g1_['user_login'];}
			  
			  echo '<small style="color:#666666;">'.$g1['FIO_short_ins'].' / <u>'.$g1_['user_id_update'].'</small></u>';?></td></tr><?php
	         }
	    	echo '</table></div></td></tr>';
         
      echo '</table><br>';	   
         
       }
  	   
	    
	
?>	<div class=text><b>Примечание: </b>если имя пользователя на латинском в графе "создал/<u>обновил</u>", ему необходиом присвоить <u>краткое имя</u> администратором портала </div>
    <p><a href="#file_upload" onClick="javascript:hide_show('upload_file');">  Добавить файл </a><br> 
	<div id="upload_file" name="upload_file" <?php if ($act!='update') { echo 'style="display:none"'; }?> > 
	<form action="" method="post" enctype="multipart/form-data">
	    <div class="text">
	    <b><a name=file_upload></a>Размер загружаемого файла не должен превышать 10 Мб.</b><br><br>
	    Выберите файл для загрузки, папку куда помещаете файл<br>
	    <input type="file" name="lfile" class="text2" size="50">&nbsp;&nbsp;&nbsp;
	    
		<select name=folder_list style="width:200;">
	    <option value="gost">без папки</option> 
		<?php 
		for ($i=1;$i<count($folders);$i++) {
		 $selected='';
		 if ($act='update' && str_replace('gost','',$a['nameFolder'])==$i) {$selected=' selected';}
		 echo '<option value="gost'.$i.'"'.$selected.'>'.$folders[$i][1].'</option>';}
		?>
		</select>		
		<br>
	    Имя файла на сервере<br>
	    (под этим названием пользователь увидит его в разделе)<br>
	    <input type="text" name="insertbrowserFile" class="text2" size="50" value='<?php if ($act='update') {echo f_ro($a['browserFile']);} ?>'><br><br>
		<input type="submit" name="gotoinsertfile" value="Загрузить" class="button">
    </div><form>
    </div>
<?php    

echo $end1;
include "display_voting.php";
define("CORRECT_FOOTER", true);
echo $end2;include('footer.php'); 
?>