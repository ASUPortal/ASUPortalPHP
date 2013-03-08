<?php
include 'authorisation.php';

include ('master_page_short.php');

$err=false;
$path='images/lects/';  //путь размещения фотографий по умолчанию
$main_text='';
$image='';
$error_msg='';

if (isset($_POST['main_text']) && $_POST['main_text']!='')  //обновление биографии
{
    $main_text=htmlspecialchars(trim($_POST['main_text']),ENT_COMPAT );
    $_FILES['loadimage']['name']=trim($_FILES['loadimage']['name']);

    if ($_FILES['loadimage']['name']!="")
    {
      if ($_FILES['loadimage']['size']==0)
        { echo $error_msg.="по указанному пути фото не найдено!";}
      else {
        $type=substr($_FILES['loadimage']['name'],strrpos($_FILES['loadimage']['name'],"."));
        if ($type!=".jpg" && $type!=".JPG" && $type!=".gif" && $type!=".GIF")
         {
          echo $error_msg.="Фотоматериал должен быть только *jpg и *gif типа!";
         }
        else {  //ошибок не найдено и файл указан
            if (!test_file($_FILES['loadimage']['name'],$_FILES['loadimage']['size']))
            {
                $error_msg.='ошибки при проверке файла. ';
            } 
             $fio_short=getScalarVal("select fio_short from kadri where id in(select kadri_id as id from users where id='".$_SESSION['id']."') limit 0,1");             
             if ($fio_short=='') $error_msg.='пользователь не найден в анкете сотрудников, либо не `привязан` к анкете. ';
             else {
                  $file_name=Trans_file_word($fio_short).$type;

                  if(!move_uploaded_file($_FILES['loadimage']['tmp_name'],$path.$file_name))
                    echo $error_msg.="Не получилось скопировать временный фотоматериал.";

                  else if (!img_resize($path.$file_name, $path."small/sm_".$file_name, 0, 120))
                    $error_msg.='ошибка создания иконки изображения. ';
             }
          }
        }
     }

     if ($error_msg!='') //возникла ошибка, биографию не обновляем
        {echo '<div class=warning>'.$error_msg.'</div>';}
     else { //обновление биографии
         //проверка наличия биографии
         $update_biog=intval(getScalarVal('select count(*) from biography where user_id="'.$_SESSION['id'].'"'),10);

         if ($update_biog!=1) {
         $query='insert into biography (user_id,image,main_text) values (
            "'.$_SESSION['id'].'","'.f_ri($file_name).'","'.f_ri($_POST['main_text']).'")';
         } else {
         $query="update biography set
            main_text='".f_ri($_POST['main_text'])."'".echoIf($file_name!='', ', image="'.f_ri($file_name).'"', '')." where user_id='".$_SESSION['id']."'";
         }
         
         
         if (!mysql_query($query)) $error_msg.='ошибка обновления биографии';
         else if ($file_name!='') {
            $query="update kadri set photo='".$file_name."' where id='".$_SESSION['kadri_id']."'";
                   
            if (!mysql_query($query)) $error_msg.='ошибка обновления фото в анкете сотрудников';
         }
         if ($error_msg!='') //возникла ошибка, биографию возможно обновили
            {echo '<div class=warning>'.$error_msg.'</div>';}
         else {
             if ($update_biog==1) echo '<div class=success>Биография успешно обновлена</div> ';
             else echo '<div class=success>Биография успешно дополнена</div> ';
         }

     }
}

	$query='select * from biography where user_id="'.$_SESSION['id'].'"';
	$res=mysql_query($query);
    if (mysql_num_rows($res)==1) {
        $a=mysql_fetch_assoc($res);
        $main_text=$a['main_text'];
        $image=$a['image'];
    }
?>
    <div class="main"><?php echo $pg_title; ?></div><br>
    <form action="" method="post" enctype="multipart/form-data">
    <div class="text">
    Введите текст<br>
    <textarea name="main_text" id=main_text class="text2" style="height:400px; width:95%;"><?php echo $main_text; ?></textarea><br><br>
      <?php
      if ($image!='')
       {
        echo'<a href="'.$path.$image.'" target="_blank">
            <img src="'.$path.'small/sm_'.$image.'" alt="фото в биографии" align=left style="padding-right:20px;"></a>
            Фото обновиться в разделе "Преподаватели" и Вашей анкете<br><br>';
       }
      else echo'<b>Фото не выложено.</b><br><br>';
       
      ?>
      Выберите фото к биографии (только *.jpg или *.gif)<br>

    <input type="file" name="loadimage" class="text" size="50"><br><br>
    <input type="reset" value="Очистить" class="button">&nbsp;&nbsp;
    <input type="submit" value="Загрузить" class="button">
    </div>
    </form>
    <?php

        echo $end1;
        include "display_voting.php";
        echo $end2; include('footer.php'); 

?>