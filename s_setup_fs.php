<?php
include ('authorisation.php');

//$head_title='Настройки портала';

include ('master_page_short.php');

?>
<h4 > <?php echo $pg_title ?> </h4>
<?php
// меняем права доступа к папкам
$acc_val=777;
if (isset($_GET['acc_val']) && strlen($_GET['acc_val'])==4)  
	{
	$acc_val=intval($_GET['acc_val']);
	echo '<dib>Выбранные права доступа для установки: <b>'.'0'.$acc_val.'</b></div>';
}
// папки для установки прав доступа	
$resource_arr=array(
	"images",	
	"images/news",				
	"images/news/small",
	"images/lects",
	"images/lects/small",
	"f_mails",		   
	"library",	  
	"news/attachement",
	"user_pages"
);
?>
<table border="1" cellspacing="0" cellpadding="5">
<tr class="table_title">
<td>Каталог</td>
<td>Доступна для записи</td>
<td>Права доступа</td>
</tr>

<?php 
for ($i=0;$i<count($resource_arr);$i++)
	{
	$resource_arr[$i]=$resource_arr[$i];
	if ($_GET['acc_repair']=='on') chmod($resource_arr[$i], sprintf('%o','0'.$acc_val) );
	
	echo '<tr class="table_row">';
	echo '<td>'.$resource_arr[$i].'</td>';
	echo '<td>'.(is_writable($resource_arr[$i])?'<span class="success">да</span>':'<span class="warning">нет</span>').'</td>';
	echo '<td>'.substr(sprintf('%o', fileperms($resource_arr[$i])), -4).'</td>';
	echo '</tr>';
	}

// проверка на запись и удаление файла из папки	
?>
</table>
<p>
<form id="acc_form" name="acc_form" action="" method="get">
	<label><input type="checkbox" id="acc_repair" name="acc_repair">установить права на запись для указанных каталогов</label> <br>
	<input type="text" value="" id="acc_val" name="acc_val"> права доступа на каталог (например, 0775) <br>
	<input type="submit" value="Ok">
</form>
</p>
<?php
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>
<?php include('footer.php'); ?>