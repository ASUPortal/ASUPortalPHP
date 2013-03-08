<?php
$bodyOnLoad=' onload="disable_();"';

include ('authorisation.php');

if ($view_all_mode!==true && (trim($_GET['kadri_id'])!=trim($_SESSION['kadri_id']) )	) 
	{header('Location:?kadri_id='.$_SESSION['kadri_id']);echo 'redirect';}

include ('master_page_short.php');


?>
<script language="javaScript">
function page_cnt()
{
var pages_range=document.izdan_form.elem10.value;
var stPos=pages_range.indexOf('-');
var stPage=pages_range.substring(0,stPos);
var enPage=pages_range.substring(stPos+1);
 
document.izdan_form.elem6.value=parseInt(enPage)-parseInt(stPage)+1;
}
 
function on_click()
{
str='';num=0;
for (i=0;i<document.izdan_form.length-2;i++) {
  if (document.izdan_form.elements[i].type=='button') {}
  else {
    num=num+1;
    tmp='';	
	if (document.izdan_form.elements[i].value=='') {
			if (document.izdan_form.elements[i].type=='select-one')   {tmp=document.izdan_form.elements[i].options[document.izdan_form.elements[i].selectedIndex].text; }; }
	str=str+(num+'   '+document.izdan_form.elements[i].value)+tmp+'   '+document.izdan_form.elements[i].type+'\n';  };
	}
}
function izdan_veiw()
{
alert('Просмотр всех публикаций автора...');
val_str1=document.izdan_form.elem1.value;   //id-kadri
val_str2=document.izdan_form.teach_name.value;   //fio

window.open('izdan_view.php?kadri_id='+val_str1,
          'Все_публикации','height=2000,width=2000,resizable=yes,scrollbars=yes');      //,width=1060,height=600
}
function disable_()	//отключить\включить "выбор публикации"\"смену сведений" по галочке "сменить\добавить публикацию"
{
var c;
c=document.getElementById('max').value;

var elem_cnt=document.izdan_form.elements.length;

    var ch_status=document.izdan_form.add_izdan.checked;
    
    document.izdan_form.izdan_id.disabled=ch_status;
    for (i=7;i<elem_cnt-4;i++) {
        var el_item=document.izdan_form.elements[i];
	if (el_item!=null) el_item.disabled=!ch_status;
	}
    
    for(i=0;i<=c;i++) {
      var el_item_=document.getElementById('item_'+(i));
      if (el_item_!=null) el_item_.disabled=!ch_status;
      } 
     
  
}
function win_close(){
if (window.opener!=null)
{window.opener.document.anket_form.elem36.value=document.izdan_form.izdan_num.value;}
window.close();
}
function help_msg()
{ alert('Эта форма предназначена для ввода данных по изданиям преподавателей.\n'+
'Возможен как ввод соавторства(выбор из списка уже введенных изданий),\n'+
'так и добавление нового издания в БД. Выбор способа задается переключателем "Ввести новое издание".\n'+
'Просмотр уже введенных в БД изданий или их правка выполняется по кнопке "Просмотреть."');
}

var c=0; //счётчик количества строк
var c_send=0; //счётчик количества строк для рассылки
function addline()
{
	c=document.getElementById('max').value;
	//alert(c);
	c++; // увеличиваем счётчик строк
	s=document.getElementById('table_kadri').innerHTML; // получаем HTML-код таблицы
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
	document.getElementById('table_kadri').innerHTML=s;
	//document.innerHTML=(' s='+s);
	//document.write(' s='+s);
	//alert(' s='+s);
	
	document.izdan_form.max.value=c;
	                // возвращаем результат на место исходной таблицы
	return false; // чтобы не происходил переход по ссылке
}
function rmline(q)
{
 c=document.getElementById('max').value;
 //alert(' del c='+c);
                if (q==0)return false;
                if (c==0) return false; else c--;
                // если раскомментировать предыдущую строчку, то последний (единственный) 
                // элемент удалить будет нельзя.
           
	
	s=document.getElementById('table_kadri').innerHTML;
	s=s.replace(/[\r\n]/g,'');
	re=new RegExp('<tr id="?newline"? nomer="?_'+q+'.*?<\\/tr>','gi');
                // это регулярное выражение позволяет выделить строку таблицы с заданным номером
	s=s.replace(re,'');
                // заменяем её на пустое место
	
	document.getElementById('table_kadri').innerHTML=s;
	document.izdan_form.max.value=c;
	
	return false;
}

</script>
 <NOSCRIPT>
Для корректной работы форм ввода требуется включение JavaScript ....
<br> Дальнейшая работа невозможна. Обратитесь к администратору проекта ...<p> </NOSCRIPT>

<script type="text/javascript" src="scripts/calendar_init.js"></script>
</head>

<?php
function authors_sec($izdan_id,$auth_id)
{
//соавторы
global $item_id;
//global $cnt_izdan,$kadri_fios;
//echo ' $izdan_id='.$izdan_id;

//$cnt_izdan=0;$kadri_fios='';  
  if ($izdan_id>0) {
	  $query='select distinct works.kadri_id from works inner join kadri on kadri.id=works.kadri_id
	  where works.izdan_id='.$izdan_id. ' and works.kadri_id<>'.$auth_id.'
	  order by kadri.fio';
	  
	  $res_edit=mysql_query($query);
	  $cnt_izdan=mysql_num_rows($res_edit);
	  while ($b=mysql_fetch_array($res_edit))
	  {
	   
	  	?>


    <tr id="newline" nomer="_<?php echo $item_id;?>">
      <td>


		  <select name="item_<?php echo $item_id;?>" style="width:300;">1<br>
		 <?php
	  		//список преподавателей
		 echo '<option value="0">...выберите сотрудника ...</option>';
	 	$query='select id,fio from kadri where id<>'.$auth_id.' order by fio ';
	 	echo ' query='.$query;
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
		 	if ($a['id']==$b['kadri_id']) { $select_val=' selected';}
			echo '<option value="'.$a['id'].'" '.$select_val.'>'.$a['fio'].'</option>';
			}
	  		?>
	  </select>
	  </td>
      <td valign="top" align="center"><a href="#" onclick="return rmline(<?php echo $item_id;?>);" style="text-decoration:none"><img src="images/design/mn.gif" border="0" WIDTH="17" HEIGHT="18"></a></td></tr>
	  
	  <?php 	  $item_id++;
	  }
/*
		$authors_all=trim($authors_all);
		if (substr($authors_all,-1)==',') {$authors_all=substr($authors_all,0,-1);} //убираем запятую вконце списка
		
		if (trim($authors_all)=='') {$authors_all='-';}

		$kadri_fios=trim($kadri_fios);
		if (substr($kadri_fios,-1)==',') {$kadri_fios=substr($kadri_fios,0,-1);} //убираем запятую вконце списка
		
		return $authors_all;*/
	}
}
$path="library/izdan";

$kadri_id=0;
if (isset($_GET['kadri_id']) and $_GET['kadri_id']>0)
{
	$kadri_id=intval($_GET['kadri_id']);	
}
$izdan_id=0;
if (isset($_GET['izdan_id']) and $_GET['izdan_id']>0)
{
	$izdan_id=intval($_GET['izdan_id']);	
}



//include ('authorisation.php');
//include ('sql_connect.php');

//global $a,$izdan_nums,$teach_name;

echo '<h4>Сведения о публикациях (изданиях)</h4>';
if (!isset($_GET['kadri_id']) or $_GET['kadri_id']=="") {
	persons_select('kadri_id');exit;}


if (isset($_GET['action']) and $_GET['action']=="update") //обновление
{//echo $_GET['works_id']." !!!!! ";
   if (isset($_GET['works_id']) and $_GET['works_id']!="")
   {$query='select works.id as works_id, izdan.id as izdan_id, izdan.kadri_id, izdan.name,grif, publisher,volume,bibliografya,
       year,copy,izdan_type.name as type_name,type_book as type_id,authors_all 
	   from works inner join izdan on works.izdan_id=izdan.id
       	left join izdan_type on izdan.type_book=izdan_type.id 
	   where works.id='.$_GET['works_id'].' limit 0,1';}
	else {
	$query='select  izdan.id as izdan_id, kadri_id as kadri_id, izdan.name,grif, publisher,volume,bibliografya,
       year,copy,type_book as type_id,authors_all from izdan where id='.$_GET['izdan_id'].' limit 0,1';	
	}
   //echo ' $query='.$query;
   $res=mysql_query($query);
    if ($res_edit=mysql_fetch_array($res)) {echo " ";} else {echo "ошибки в выборке1";}
//   $res='UPDATE works SET izdan_id = 10 WHERE id ='.$_GET['works_id'].' LIMIT 1 ';

//   $res="";exit;
}
//echo ' !!!!!!!!!1';
/*  if(!mysql_connect($sql_host,$sql_login,$sql_passw))
       {echo 'Не могу соединиться с сервером Базы Данных'.mysql_error(); exit();        }
  if(!mysql_select_db($sql_base))
       {echo 'Не могу выбрать базу'.mysql_error(); exit();      }

  mysql_query("SET NAMES cp1251");      */

//определяем ФИО преподавателя по ID для вывода
    $tab_name='kadri';
    $res=mysql_query('select fio from '.$tab_name.' where id='.trim($_GET['kadri_id']));
    $teach_name=mysql_fetch_array($res);
///////////////////////////////////////////////////////////
/*
if (isset($_GET['action']) and $_GET['action']=="update") //обновление
{//echo $_GET['works_id']." !!!!! ";
   if (isset($_GET['works_id']) and $_GET['works_id']!="")
   {$query='select works.id as works_id, izdan.id as izdan_id, kadri_id, izdan.name,grif, publisher,volume,bibliografya,
       year,copy,izdan_type.name as type_name,type_book as type_id,authors_all 
	   from works inner join izdan on works.izdan_id=izdan.id
       	left join izdan_type on izdan.type_book=izdan_type.id 
	   where works.id='.$_GET['works_id'].' limit 0,1';}
	else {
	$query='select  izdan.id as izdan_id, kadri_id as kadri_id, izdan.name,grif, publisher,volume,bibliografya,
       year,copy,type_book as type_id,authors_all from izdan where id='.$_GET['izdan_id'].' limit 0,1';	
	}
   echo ' $query='.$query;
   $res=mysql_query($query);
    if ($res_edit=mysql_fetch_array($res)) {echo " ";} else {echo "ошибки в выборке";}
//   $res='UPDATE works SET izdan_id = 10 WHERE id ='.$_GET['works_id'].' LIMIT 1 ';

//   $res="";exit;
}*/
//////////////////////////////////////////////////////////
//смотрим последний номер издания ....
    $tab_name='izdan';
    $res=mysql_query('select id from '.$tab_name.' order by id DESC limit 0,1');
    $a=mysql_fetch_array($res);
if (isset($_POST['elem2'])) {
  $insert_vals="";
  for ($i=0;$i<8;$i++)  //для проверки пока не все элементы
  {$nam_var='elem'.$i;
  $insert_vals=$insert_vals."'".$_POST[$nam_var]."',"; }   }

   //переключатель
  if (!isset($_POST['add_izdan'])) {
     $query='';
     //print_r($_POST);
     //print_r($res_edit);
	 echo "добавление/обновление уже сущ-й публикации...<br>";
     if (!isset($_POST['izdan_id']) or $_POST['izdan_id']==0) {echo "Не выбрана публикация из списка ...";}
     else {

     //echo "Выбрана №=".$_POST['izdan_id'];
        if (isset($res_edit)) {//header("Location: izdan_view.php?kadri_id=".$_GET['kadri_id']);
          $query='update works set izdan_id="'.$_POST['izdan_id'].'" where id="'.$_GET['works_id'].'" limit 1;';
          if (mysql_query($query)) {echo success_msg("Издание успешно изменено с учетом имеющихся образцов");}
          else {echo error_msg('Издание не изменено. Возможно такое уже есть.');}
        }
        else  {
          $query='insert into works(kadri_id,izdan_id) values("'.trim($_GET['kadri_id']).'","'.$_POST['izdan_id'].'");';
          //$res=
          if (mysql_query($query)) {echo success_msg("Издания успешно дополнены с учетом имеющихся образцов.");} 
          else {echo error_msg("Издания не дополнено с учетом имеющихся образцов. Возможно такое уже есть.");}
		  }
          };
  
  }
  else
  {     //если режим обновления самого издания (его данных)

$add_izdan=$_POST['add_izdan'];
      if ($_POST['elem2']!='') {

//-------------------------------------------file upload----------------------------  
if (is_uploaded_file($_FILES['file_izdan']['tmp_name'])) {
	$cur_fvar_name='file_izdan';
	$cur_dir_name=$path;
	
	if (!file_exists($cur_dir_name) ) {mkdir($cur_dir_name, 0755); echo '<div class=succes>папка создана</div>';}
	
	$file_name=saveFile($cur_dir_name.'/',$_FILES[$cur_fvar_name],null,null,null,true);            
	if ($file_name!='') echo '<div class=success> Фaйл "'.$file_name.'" успешно прикреплен.</div>';
}
//-------------------------------------------file upload----------------------------  
              


			  
if (isset($_POST['max'])) {
	if (isset($res_edit['izdan_id']) && $res_edit['izdan_id']>0) //удаляем прежних соавторов для уже сущ-й публикации
		{mysql_query('delete from works where kadri_id<>'.$_GET['kadri_id'].' and izdan_id='.$res_edit['izdan_id']);}

	while (list($val,$name)=each($_POST)) {
		if (strstr($val,'item_') && $_POST[$val]!=0) {
			  
			  if (!$res_edit) {$query2='insert into works (kadri_id,izdan_id) values("'.trim($_POST[$val]).'","'.trim($a['id']+1).'");';}
			  else {$query2='insert into works (kadri_id,izdan_id) values("'.trim($_POST[$val]).'","'.$res_edit['izdan_id'].'");';}
			  
			  if (mysql_query($query2)) {echo '<div class=success>Соавтор для издания добавлен: '.getScalarVal('select fio from kadri where id='.intval($_POST[$val])).'</div>';}
			  $query2='';
			  
			 }
		
		}
}
          if ($res_edit && intval($res_edit['izdan_id'])>0) {	//обновление данных публикации
		
		//удаляем прикрепленный файл если выбрана опция удаление или произведена замена на новый файл		
		$cur_fval_del_name='del_file_izdan';
		$del_f_name=getScalarVal('select copy from izdan where id='.intval($res_edit['izdan_id']));
		
		//echo '$del_f_name='.$del_f_name.', $file_name='.$file_name;
		if  ( (isset($_POST[$cur_fval_del_name]) && $_POST[$cur_fval_del_name]=='on') ||
			($del_f_name!="" && $file_name!="")  ) {			    
		    $cur_dir_name=$path;		    
		    if ($del_f_name!="" && file_exists($cur_dir_name.'/'.$del_f_name))	{			     
			
			if (unlink($cur_dir_name.'/'.$del_f_name)) echo '<div class=success>'.($file_name!=''?'прежний ':'').'файл успешно удален</div>';
			else echo '<div class=warning>'.($file_name!=''?'прежний ':'').'файл не удален</div>';
		      } else echo '<div class=warning>файл для удаления не найден в каталоге</div>';
		    }
		    
		$query="
		    update izdan set
		        name='".$_POST['elem2']."',
		        grif='".$_POST['elem3']."',
                publisher='".$_POST['elem4']."',
                year='".$_POST['elem5']."',
                volume='".$_POST['elem6']."',
                ".($file_name!='' || $_POST[$cur_fval_del_name]=='on'?"copy='".$file_name."',":"")."
                type_book='".$_POST['elem7']."',
                bibliografya='".$_POST['elem8']."',
                authors_all='".$_POST['elem9']."',
                page_range='".$_POST['elem10']."',
                approve_date = '".$_POST['approve_date']."'
            where id='".$res_edit['izdan_id']."'";
                  if ($res=mysql_query($query)) {
		    echo "<div class=success>Данные издания обновлены</div>";
		    header("Location: izdan_view.php?kadri_id=".$_GET['kadri_id']);
		    }
		  else {echo "<div class=warning>Данные издания не обновлены</div>"; }
                  
                  }

          else { //режим добавления нового издания
              $query="
            insert into izdan (
                id,
                kadri_id,
                name,
                grif,
                publisher,
                year,
                volume,
                type_book,
                bibliografya,
                copy,
                authors_all,
                page_range,
                approve_date)
            values(
                ".$insert_vals."'".$_POST['elem8']."',
                '".$file_name."',
                '".$_POST['elem9']."',
                '".$_POST['elem10']."',
                '".$_POST['approve_date']."')";

			  $query2='insert into works (kadri_id,izdan_id) values("'.trim($_GET['kadri_id']).'","'.trim($a['id']+1).'");';
          if  ($res=mysql_query($query)) {echo success_msg('Издания успешно дополнены новым образцом.');}
          else {echo error_msg('ошибка при добавлении образца в  издания.');}
          
		  if  ($res=mysql_query($query2)) {echo success_msg('Издание для автора успешно дополнено.'); }
		  else {echo error_msg('ошибка при добавлении издания автору.');} 
		  }
			
						
			

			  }
     else {echo error_msg("Требуется ввести хотя бы название для нового издания... ");}
  }
if (isset($_GET['action']) and $_GET['action']=="update") //обновление
{//echo $_GET['works_id']." !!!!! ";
    if (isset($_GET['works_id']) and $_GET['works_id']!="") {
        $query='
            select
                works.id as works_id,
                izdan.id as izdan_id,
                izdan.kadri_id,
                izdan.name,
                grif,
                publisher,
                volume,
                bibliografya,
                year,
                copy,
                izdan_type.name as type_name,
                type_book as type_id,
                authors_all,
                page_range,
                approve_date
            from works
                inner join izdan on
                    works.izdan_id=izdan.id
                left join izdan_type on
                    izdan.type_book=izdan_type.id
            where works.id='.$_GET['works_id'].' limit 0,1';}
	else {
	$query='select  izdan.id as izdan_id, kadri_id as kadri_id, izdan.name,grif, publisher,volume,bibliografya,
       year,copy,type_book as type_id,authors_all,page_range from izdan where id='.$_GET['izdan_id'].' limit 0,1';	
	}
   //echo ' $query='.$query;
   $res=mysql_query($query);
    if ($res_edit=mysql_fetch_array($res)) {echo " ";} else {echo "ошибки в выборке2";}
//   $res='UPDATE works SET izdan_id = 10 WHERE id ='.$_GET['works_id'].' LIMIT 1 ';

//   $res="";exit;
}
//echo ' !!!!!!!!!1';

//смотрим последний номер издания ....
    $tab_name='izdan';
    $res=mysql_query('select id from '.$tab_name.' order by id DESC limit 0,1');
    $a=mysql_fetch_array($res);

//определяем число публикаций для вывода их кол-ва
    //$tab_name='works';
    	//$query='select id from works';
	$izdan_nums=0;
	if ($kadri_id>0) {
		$res=mysql_query('select count(*)as cnt_rows from works where kadri_id='.trim($_GET['kadri_id']));
		$izdan_nums=mysql_result($res,0);}
	else {$res=mysql_query('select count(*)as cnt_rows from izdan');
		$izdan_nums=mysql_result($res,0);}
//    echo 'select id from works where kadri_id='.trim($_GET['kadri_id']);
	//$izdan_nums=mysql_num_rows($res);

//echo 'query='.$query;
?>

<style>
.tab_view {border-style:none; background-color:transparent; font:red};
</style>
<form name="izdan_form" action="" method="post" enctype="multipart/form-data">

<table name=izdan bgcolor="#E6E6FF">
<tr><td colspan=2> <!--<input name="teach_name" class=tab_view type=text value="<?php echo $_GET['fio']; ?>" size=30>-->

<?php 
//include 'funcs_php.php';
persons_select('kadri_id');
?>
&nbsp;&nbsp;&nbsp;всего публикаций в БД: <input name="izdan_num" class=tab_view type=text value="<?php echo $izdan_nums; ?>" size=3>
<input name=izdan_view type=button value="Просмотреть" onclick="javascript:document.location.href='izdan_view.php<?php if (isset($_GET['kadri_id'])) {echo '?kadri_id='.$_GET['kadri_id'];}?>';"> <!-- onclick="javascript:izdan_veiw();" -->
  </td> </tr>
<tr><td colspan2>&nbsp;</td></tr>

<tr><td><!-- ID:--> </td> <td><input name="elem0" type=hidden value="
<?php if (!isset($_GET['id_obrazov'])) {echo trim($a['id']+1);} ?>" size=4>  </td> </tr>

<tr><td> <!--kadri_id:--> </td> <td><input name="elem1" type=hidden value="
<?php echo trim($_GET['kadri_id']); ?>" size=4>  </td> </tr>

<tr><td colspan=2>

<select name="izdan_id" id="izdan_id" style="width:300px" title="замена на другое издание">
<?php
$query="(select id,concat(name,'( - )')as name from izdan where id not in (select izdan_id from works) )
union 
(select izdan.id,concat(izdan.name,' ( авторов: ',count(izdan.id),')') as name  
	from izdan inner join works on works.izdan_id=izdan.id 
	where izdan.id not in (select izdan_id from works where kadri_id=".$kadri_id.") group by izdan.id)
order by name";
//echo ' $query='.$query;
echo getFrom_ListItemValue($query,'id','name','izdan_id');

?>
</select>&nbsp;
<?php 

if (isset($_GET['action'])) {echo 'Изменить ';} else {echo 'Добавить ';}?>издание: 
<input title="правка данных издания" type=checkbox <?php if ($izdan_id>0) {echo "checked ";}?>
name="add_izdan" id="add_izdan" onclick="javascript:disable_()"></td></tr>

<tr><td> Название: *</td>     <td><textarea name="elem2" rows=3 cols=45><?php if (isset ($res_edit)) {echo trim($res_edit['name']);} ?></textarea></td> </tr>
<tr><td> Библиография: </td>     <td><textarea name="elem8" rows=3 cols=45><?php if (isset ($res_edit)) {echo trim($res_edit['bibliografya']);} ?></textarea>  </td> </tr>
<tr><td> Гриф издания: </td> <td><input name="elem3" type=text value="<?php if (isset ($res_edit)) {echo trim($res_edit['grif']);} ?>" size=60>  </td> </tr>
<tr><td> Издательство: </td> <td><textarea name="elem4" rows=3 cols=45><?php if (isset ($res_edit)) {echo trim($res_edit['publisher']);} ?></textarea>  </td> </tr>
<tr><td> Год издания: </td>  <td><input name="elem5" type=text value="<?php if (isset ($res_edit)) {echo trim($res_edit['year']);} ?>" size=8 maxlength="4">  </td> </tr>
<tr><td> Страницы.: </td>  <td>диапазон страниц <input name="elem10" OnChange="javascript:page_cnt();" type=text title="Введите через тире диапазон страниц, например 10-23, число страниц автоматически рассчитается" value="<?php if (isset ($res_edit)) {echo trim($res_edit['page_range']);} ?>" size=13 maxlength="10">
&nbsp;&nbsp; число <input name="elem6" type=text value="<?php if (isset ($res_edit)) {echo trim($res_edit['volume']);} ?>" size=8 maxlength="4">
</td> </tr>
<tr>
    <td> Вид издания: </td>
    <td>
        <select name="elem7" style="width:300px">
<option value=0>Выберите вид издания...</option>
<?php
$query="select id,name from izdan_type order by name";
$res=mysql_query($query);
while ($a=mysql_fetch_array($res)) {
    if ($res_edit['type_id']!="" and $res_edit['type_id']==$a['id']) {
        echo "<option value=".$a['id']." selected>".$a['name']."</option>\n";
    } else {
        echo "<option value=".$a['id'].">".$a['name']."</option>\n";
    }
} ?>
        </select>
</td></tr>
    <tr>
        <td> Дата подписания в печать: </td>
        <td>
            <input type="text" id="approve_date" name="approve_date" value="<?php if (isset($res_edit)) : echo trim($res_edit['approve_date']); endif; ?>">
            <button type="reset" id="approve_date_select">...</button>
            <script type="text/javascript">
                Calendar.setup({
                    inputField     :    "approve_date",      // id of the input field
                    ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
                    showsTime      :    false,            // will display a time selector
                    button         :    "approve_date_select",   // trigger for the calendar (button ID)
                    singleClick    :    true,           // double-click mode false
                    step           :    1                // show all years in drop-down boxes (instead of every other year as default)
                });
            </script>
        </td>
    </tr>
<tr><td><br>Авторы все <a href="#" title="перечислите всех через запятую в форме: Фамилия И.О.">?</a></small></td><td><textarea name="elem9" rows=3 cols=45 title="Перечислите через запятую всех соавторов в порядке их следования, включая соавторов кафедры"><?php if (isset ($res_edit)) {echo trim($res_edit['authors_all']);} ?></textarea></td>
</tr>
<tr><td> Прикрепленный файл: </td>  <td><input type=file name="file_izdan" size=30> 
	<?php if (isset ($res_edit) && $res_edit['copy']!='') {echo '<a class=text href="library/izdan/'.$res_edit['copy'].'" target=_blank>'.file_type_img($res_edit['copy'],true,true).'</a> <label><input type=checkbox id=del_file_izdan name=del_file_izdan> удалить </label>';} ?>
<tr><td valign=top><br>Соавторы кафедры <a href="#" title="при указании соавторов с кафедры, добавленная работа отразится и у них, возможен выбор нескольних соавторов - кликните символ плюс(+) чуть выше списка соавторов">?</a><br>
<small>(для удаления соавтора -<br>укажите "выберите сотрудника")</small>
</td><td><label name="authors_all" id="authors_all">
<div id="table_kadri" name="table_kadri" style="display:;">

   <table border="0" cellspacing="0" cellpadding="3">
     <tr id="newline" nomer="_0">
       <td></td>
       <td valign="top" align="center">
	   <a href="#" onclick="return addline();" style="text-decoration:none">
		<img src="images/design/pl.gif" border="0" WIDTH="17" HEIGHT="18"></a></td></tr>

<?php
$item_id=0;
if (isset($res_edit)) { 	
 	
	 //echo ' izdan_id='.$res_edit['izdan_id'];echo ' id_kadr='.$_GET['kadri_id'];
	authors_sec($res_edit['izdan_id'],$_GET['kadri_id']); 
	}
//echo ' res_edit=';
//print_r($res_edit);
?>
    <tr id="newline" nomer="_<?php echo $item_id; ?>">
      <td>
	  <select name="item_<?php echo $item_id; ?>" style="width:300;">
	  	<?php	//список преподавателей
		 echo '<option value="0">...выберите сотрудника ...</option>';
	 	$query='select id,fio from kadri order by fio';
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
			echo '<option value="'.$a['id'].'">'.$a['fio'].'</option>';
			}

	  		?>
	  </select>
	  </td>
      <td valign="top" align="center"><a href="#" onclick="return rmline(<?php echo $item_id; ?>);" style="text-decoration:none"><img src="images/design/mn.gif" border="0" WIDTH="17" HEIGHT="18"></a></td></tr>
  </table>

</div>
</label>
<input type="hidden" name="max" id="max" value="<?php echo $item_id; ?>">
</td></tr> 


<tr><td colspan=2><hr></td>  </tr>
<tr><td valign="top"><input type="submit" value="<?php if (isset($_GET['action']) and $_GET['action']=="update") {echo "Изменить";} else {echo "Добавить";} ?>"
onclick="javascipt:on_click();"></td>
<td valign="top"><input type="reset" value="Очистить">
<div align=right><input type="button" value="Справка" onclick="javascript:help_msg();"></div></td>  </tr>

</table></form>
<p><a href="p_administration.php">К списку задач.</a></p>
<script language=javascript>  
  disable_();  
</script>
<?php
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>
						  
<?php include('footer.php'); ?>
