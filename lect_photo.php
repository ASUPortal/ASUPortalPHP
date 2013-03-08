<?php 
include 'authorisation.php';


//session_start();
if ($view_all_mode!==true && (trim($_GET['kadri_id'])!=trim($_SESSION['kadri_id']) )	) 
	{header('Location:?kadri_id='.$_SESSION['kadri_id'].'');}

$pg_title='Фото преподавателя';
	
include ('master_page_short.php');


//include ('authorisation.php');


//include ('sql_connect.php');

echo '<h4 align=center>'.$pg_title.'</h4>';


$fio="";
$kadri_id=0;
$path="images/lects/";

if (!isset($_GET['kadri_id']) or $_GET['kadri_id']=='' or $_GET['kadri_id']==0) 
	{echo "Преподаватель не найден. <a href='lect_anketa_view.php'> Вернуться </a> ";exit;}


$kadri_id=$_GET['kadri_id'];
//echo "ФИО: ".$kadri_id;

if (is_uploaded_file($_FILES['photo_file']['tmp_name'])) {

$query="select photo, fio, fio_short, id from kadri where id=".$kadri_id." limit 0,1";
//echo "query= ".$query."<br>";
$res=mysql_query($query);
$a=mysql_fetch_array($res);


 	//echo "file upload....<hr>";
	/*echo "tmp_name=".$_FILES['photo_file']['tmp_name']."<br>";
	echo "type=".$_FILES['photo_file']['type']."<br>";
	echo "name=".$_FILES['photo_file']['name']."<br>";
	echo "size=".$_FILES['photo_file']['size']."<br>";*/
	$type=substr($_FILES['photo_file']['name'],strrpos($_FILES['photo_file']['name'],"."));
	$file_name=Trans_file_word($a['fio_short']).strtolower($type);
	//echo "file_name=".$file_name."<br>";
	move_uploaded_file($_FILES['photo_file']['tmp_name'],$path.$file_name);

//-----------------------------------
if (!img_resize($path.$file_name, $path."small/sm_".$file_name, 0, 120))    echo '<div class=warning>ошибка создания иконки изображения</div>';
//-----------------------------------

	$query="update kadri set photo='".$file_name."' where id=".$kadri_id;
	$res=mysql_query($query);
 	
	if ($res=mysql_query($query)) echo "<div>графический материал в анкете <span class=success>обновлен</span>.</div>";
       
        $user_id=getScalarVal('select id from users where kadri_id='.$kadri_id);
        $user_id=intval($user_id);
        
        //обновляем фото в биографии пользователи открытого раздела Преподаватели
        if ($user_id>0) {
            $query='update biography set image="'.$file_name.'" where user_id="'.$user_id.'"';          
            if ($res=mysql_query($query)) echo "<div>графический материал в биографии раздела 'Преподаватели' <span class=success>обновлен</span>...</div>";
            }
        else {echo "<div>графический материал в биографии раздела 'Преподаватели' <span class=warning>не обновлен</span> Возможно у сотрудника нет `привязанного` пользователя</div>";}
}
//----------------------------------------------------------------------
$query="select photo, fio, id from kadri where id=".$kadri_id." limit 0,1";
//echo "query= ".$query."<br>";
$res=mysql_query($query);
$a=mysql_fetch_array($res);
//echo "photo=".$a['photo']."<br>";
echo "ФИО преподавателя: <b> ".$a['fio']."</b><br>";
//echo "kadri_id=".$a['id']."<hr>";

if ($a['photo']!=null and $a['photo']!='') {echo "<br><a href=images/lects/".urlencode($a['photo'])."><img src='images/lects/small/sm_".urlencode($a['photo'])."'></a><p>";}
else {echo "<br><img src='images/no_photo.jpg'><p>";}
//----------------------------------------------------------------------
echo "<a href='lect_anketa.php?kadri_id=".$_GET['kadri_id']."&action=update'> Вернуться </a> ";

//header('Location:lect_photo.php?kadri_id=56');

?>
<p> Выберите графический файл для загрузки
<form enctype="multipart/form-data" name=photo_form action="" method="post">
<input type=file name="photo_file" size=60> &nbsp;&nbsp;&nbsp;
<input type=submit value=Обновить>

</form>
<?php include('footer.php'); ?>