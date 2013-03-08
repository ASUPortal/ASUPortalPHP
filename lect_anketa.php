<?php
//правка списка НЕ рецензетов разрешена $_SESSION['task_rights_id']==4 (правка всех записей) 

include ('authorisation.php');

// <abarmin date="06.05.2012">
// а не проще это все переписать?
require_once("core.php");
// </abarmin>

$kadri_id=0;
$person_type=0;
if (isset($_GET['kadri_id']) && intval($_GET['kadri_id'])>0 ) {$kadri_id=intval($_GET['kadri_id']);}
if (isset($_GET['person_type']) && intval($_GET['person_type'])>0 ) {$person_type=intval($_GET['person_type']);}

$small_img_path='/small';
$obrazov_path='library/anketa/obrazov';
$disser_k_path='library/anketa/kandid';

if ($_SESSION['task_rights_id']!=4)	//проверка на гр.админов и роль 'kadri'
{
	  if ($kadri_id>0) {
	  //проверка на рецензента, показываем всем админам и преп-м
		 $query='select id from kadri where id='.$kadri_id.' and id in 
		    (select kpt.kadri_id from kadri_in_ptypes kpt where kpt.person_type_id=2) ';
		 $res=mysql_query($query);
		 if (mysql_num_rows($res)==0) {// выбран не рецензент
			
			if ($kadri_id!=intval($_SESSION['kadri_id'])) 
				{
				 header('Location:?kadri_id='.intval($_SESSION['kadri_id']).'&action=update');
				 }
    
			}
	   }
	   else {
		if (intval($_SESSION['kadri_id'])>0) {
		 	header('Location:?kadri_id='.$_SESSION['kadri_id'].'&action=update');
		 	//echo 'Location:?kadri_id='.$_SESSION['kadri_id'].'&action=update';
			 }
		else {
		 	if ($person_type!=2) {
			 	header('Location:?person_type=2#1');
			 	}	
			}
	   }
	
}

if (isset($_GET['action']) or isset($_GET['kadri_id']))
	{$bodyOnLoad=' onLoad="update_lect_load();"';} 
else 
	{$bodyOnLoad=' onLoad="Click_color(\'c1\',3);"';}
include ('master_page_short.php');

$multy_ptypes=true;	//множественные "тип участия на каф." у сотрудника

?>

<script language="javaScript" src="scripts/tabs.js"></script>
<script type="text/javascript" src="scripts/calendar_init.js"></script>
<script type="text/javascript" src="scripts/rows_edit.js"></script>
<link rel="stylesheet" type="text/css" href="_ajax_templ/multiSelect/styles.css" >

<script src="_ajax_templ/multiSelect/jquery.inlinemultiselect-1.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
    $('select.none').inlinemultiselect({triggerPopup:{'empty':'','nonempty':'','disabled':''}});
    $('select.imglink').inlinemultiselect({'formName':'formPT',triggerPopup:{'empty':'<img src="images/new_elem.gif" border=0 title="добавить элементы" width="16" height="18" /><span style="color:#090;font-style:italic;padding-left:3px;">Выбрать...</span>',
					'nonempty':'<img src="images/toupdate.png" border=0 title="изменение списка" width="12" height="13" /><span style="color:#00f;font-style:italic;padding-left:3px;">Сменить...</span>',
					'disabled':'<img src="images/toupdateD.png" border=0 title="изменение недоступно" width="12" height="13" /><span style="color:#f00;font-style:italic;padding-left:3px;">disabled</span>'}});
});  
</script>
<script type="text/javascript" src="scripts/jquery.autocomplete.js"></script>
<LINK href="css/autocomplete.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
	//массив полей автозаполнения: имя поля (#id), тип запроса к БД для выборки
	var fieldsArr=new Array(
		new Array("#nation","nation"),
		new Array("#social","social")
	);
</script>
<script type="text/javascript" src="scripts/autocomplete_custom.js"></script>

<script language="javaScript">
function win_open_(win_type)
{
   var kadri_id=document.anket_form.elem0.value;
   var kadri_id=kadri_id.replace(/\D/,'');

   /*newWin=window.open('spav_other.php?&type='+win_type+'&kadri_id='+kadri_id+'&action=new',
             'Справочники','height=2000,width=2000,resizable=yes,scrollbars=yes');   //,width=700,height=600*/
             
 	window.location.href='spav_other.php?&type='+win_type+'&kadri_id='+kadri_id+'&action=new';
}

function on_click()
{
str='';num=0;
for (i=0;i<document.anket_form.length-2;i++) {
  if (document.anket_form.elements[i].type=='button') {}
  else {
    num=num+1;
    tmp='';//alert(document.anket_form.elements[i].name);
	if (document.anket_form.elements[i].value=='') {
			if (document.anket_form.elements[i].type=='select-one')   {tmp=document.anket_form.elements[i].options[document.anket_form.elements[i].selectedIndex].text; }; }
	str=str+(num+'   '+document.anket_form.elements[i].value)+tmp+'   '+document.anket_form.elements[i].type+'\n';  };
	}
}
function check_form()	//проверить данные формы перед отправкой
{
var err=false;
a = new Array(
	new Array('elem2','ФИО_полное'),
	new Array('elem42','ФИО_краткое')
);
requireFieldCheck(a,'anket_form');	

} 

function new_item()
{ window.open('spravochnik.php', 'Справочники','height=2000,width=2000,resizable=yes,scrollbars=yes');}

 function orders(kadri_id)
{ window.open('orders.php?kadri_id='+kadri_id+'&type_money=2', 'Справочники','height=2000,width=2000,resizable=yes,scrollbars=yes');}

function win_izdan()
{
alert('открытие страницы публикаций');
val_str1=document.anket_form.elem0.value;   //id-kadri
//val_str2=document.anket_form.elem2.value;   //fio
window.open('izdan.php?id_kadri='+val_str1,'Публикации_издания','height=2000,width=2000,resizable=yes,scrollbars=yes');
}
function win_courses()
{
val_str1=document.anket_form.elem0.value;   //id-kadri
val_str2=document.anket_form.elem2.value;   //fio
window.open('courses.php?kadri_id='+val_str1+'&fio='+val_str2,'Курсы_повышения_квалификации','height=2000,width=2000,resizable=yes,scrollbars=yes');


}
function update_lect_load()
{for (i=7;i<document.anket_form.length-7;i++) {document.anket_form.elements[i].disabled=true;}
Click_color('c1',3); }

function update_lect()
{for (i=7;i<document.anket_form.length;i++) {document.anket_form.elements[i].disabled=false;}
 for (i=18;i<=26;i++) {eval("document.anket_form.elem"+i+".disabled=true");}
 for (i=43;i<=51;i++) {eval("document.anket_form.elem"+i+".disabled=true");}
 }

function help_msg()
{ alert('Эта главная форма, предназначена для ввода и правки данных по преподавателям.\n'+
'Правка данных выполняется выбором ссылки "Изменить данные преподавателя",\n'+
'Предварительно необходимо проверить данные справочников(ин.язык, звание...). '+
'Для этого выберите ссылку "Просмотреть и заполнить выпадающие справочники". '+
'Данные этих справочников выбираются на форме ввода данных о преподавателе.');
}
function show_hide(name_) {
	if (name_>"")
	{
			if (name_=="Layer1") {
//				document.links[0].style="font-weight:bold;";
//				document.links[1].style="font-weight:normal;";
//				document.links[2].style="font-weight:normal;";
				document.getElementById(''+"Layer1"+'').style.display="";
				document.getElementById(''+"Layer2"+'').style.display="none";
				document.getElementById(''+"Layer3"+'').style.display="none"; 									}
			if (name_=="Layer2") {
				document.getElementById(''+"Layer2"+'').style.display="";
				document.getElementById(''+"Layer1"+'').style.display="none";
				document.getElementById(''+"Layer3"+'').style.display="none"; 									}
			if (name_=="Layer3") {
				document.getElementById(''+"Layer3"+'').style.display="";
				document.getElementById(''+"Layer2"+'').style.display="none";
				document.getElementById(''+"Layer1"+'').style.display="none"; 									}
	}
}
function FIO_sokr()
{
//сокращенное ФИО от полного
var fio= document.anket_form.elem2.value.toString();
var fio_sokr='';
var start_id_1=0,start_id_2=0;

start_id_1=fio.indexOf(' ',2);
start_id_2=fio.indexOf(' ',start_id_1+1);
fio_sokr=fio.substring(0,start_id_1)+' '+fio.substring(start_id_1+1,start_id_1+2)+'.'+fio.substring(start_id_2+1,start_id_2+2)+'.';
document.anket_form.elem42.value=fio_sokr;

}

</script>
<style>
.name_desc {text-align:right; font-size: 0.8em;}	/* наименования показателей анкеты	*/
</style>
<NOSCRIPT>
<h3>Для корректной работы форм ввода требуется включение JavaScript ....
<br> Дальнейшая работа невозможна. Обратитесь к администратору проекта ...<p></h3> </NOSCRIPT>

<?php
//последний  elem id=47

//include ('menu.htm');
//include ('sql_connect.php');

//-----------------------------update---------------------------------------------------------------------------
$disabled_val="";   //для вставки
$err=false;
$query_string='';


if (isset($_SERVER['QUERY_STRING'])) {$query_string=$_SERVER['QUERY_STRING'];}

if (isset($_GET['action']))            {
      //------------------------------------------------------------------------------------------------------------
      if ($_GET['action']=="delete" and $_GET['kadri_id']!="") {        //удаление сотрудника
         $query='select fio from kadri where id='.$_GET['kadri_id'];
         $res=mysql_query($query);
         $a=mysql_fetch_array($res);
		 $query_del='delete from kadri where id='.$_GET['kadri_id'];
         $query_del2='delete from works where kadri_id='.$_GET['kadri_id'];
         echo "<p>&nbsp;</p>";
         if (mysql_query($query_del) and mysql_query($query_del2))
         { 
		 $query_string=reset_param_name(reset_param_name($query_string,'action'),'kadri_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}		  
		  echo "<div>Сотрудник: <b>".$a['fio']."</b> и его пособия <span class='success'>успешно удалены.</span>".$onEditRemain_text."<div>";
		  echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.$query_string.'\"",2000);</script>';}  
		  else {echo "<div class=warning>Ошибка удаления сотрудника ".$a['fio']."...</div>";$err=true;}
         		 $onEditRemain_text='';

		 echo '<p>&nbsp;</p><a href="lect_anketa_view.php">К списку преподавателей</a>';
         exit;
      }
                                           }
//----------------------------end update------------------------------------------------------------------------
//определяем следующий номер записи сотрудника в БД
    $tab_name='kadri';
    $res=mysql_query('select id from '.$tab_name.' order by id DESC limit 0,1');
    $a=mysql_fetch_array($res);reset($a);array_walk($a,'arr_replace');

//определяем число публикаций для вывода их кол-ва
    if (isset($_GET['kadri_id']))      {
    $tab_name='works';
    $res=mysql_query('select id from '.$tab_name.' where kadri_id='.trim($_GET['kadri_id']));
    $izdan_nums=mysql_num_rows($res);    }

//    
$act_type='';                                                                                                        
if (isset($_POST['elem2']) )    {  //если нажали кнопку Добавить\Изменить

      if ($kadri_id>0) {  //в режиме обновления данных
       //echo 'person_type2=';
       //print_r($_POST['person_type2']);
       
       $query="update kadri set fio='".f_ri($_POST["elem2"])."',
       pol='".f_ri($_POST["elem3"])."', passp_seria='".f_ri($_POST["elem4"])."', passp_nomer='".f_ri($_POST["elem5"])."',
       date_rogd='".f_ri($_POST["elem6"])."', language1='".f_ri($_POST["elem7"])."', work_place='".f_ri($_POST["elem8"])."',
       dolgnost='".f_ri($_POST["elem9"])."', zvanie='".f_ri($_POST["elem10"])."', stepen='".f_ri($_POST["elem11"])."',
       add_work='".f_ri($_POST["elem12"])."', tel_work='".f_ri($_POST["elem13"])."', add_home='".f_ri($_POST["elem14"])."',tel_home='".f_ri($_POST["elem15"])."',
       e_mail='".f_ri($_POST["elem16"])."', site='".f_ri($_POST["elem17"])."', 
       stag_ugatu='".f_ri($_POST["elem27"])."', stag_pps='".f_ri($_POST["elem28"])."', stag_itogo='".f_ri($_POST["elem29"])."',
       din_nauch_kar='".f_ri($_POST["elem30"])."', ekspert_spec='".f_ri($_POST["elem31"])."', ekspert_kluch_slova='".f_ri($_POST["elem32"])."',
       nauch_eksper='".f_ri($_POST["elem33"])."', prepod_rabota='".f_ri($_POST["elem34"])."', nagradi='".f_ri($_POST["elem35"])."',
       primech='".f_ri($_POST["elem37"])."',fio_short='".f_ri($_POST["elem42"])."',
       passp_date='".f_ri($_POST["elem38"])."',passp_place='".f_ri($_POST["elem39"])."',
       INN='".f_ri($_POST["elem40"])."',insurance_num='".f_ri($_POST["elem41"])."', 
       person_type='".f_ri($_POST["person_type"])."',
       add_contact='".f_ri($_POST["add_contact"])."',
       to_tabel=".echoIf($_POST["to_tabel"]=='on',1,0).",
       is_slave=".echoIf($_POST["is_slave"]=='on',1,0).",
       birth_place='".f_ri($_POST["birth_place"])."',
       manager_id = ".f_ri(CRequest::getInt("manager_id")).",
       department_role_id = ".f_ri(CRequest::getInt("department_role_id")).",
       nation='".f_ri($_POST["nation"])."',
       social='".f_ri($_POST["social"])."',
       family_status='".intval($_POST["family_status"])."' 
           where id='".$kadri_id."'";

      $act_type='обновлена';     
			
	  }
      else {
      //если в режиме вставки данных
        $insert_vals='';
        for ($i=2;$i<18;$i++)  //для проверки пока не все элементы
        {$nam_var='elem'.$i;  $insert_vals=$insert_vals.'"'.f_ri($_POST[$nam_var]).'",'; }

        $insert_vals2=''; //35
        for ($i=27;$i<=41;$i++)  //для проверки пока не все элементы
        if ($i!=36){$nam_var='elem'.$i;  $insert_vals2=$insert_vals2.'"'.f_ri($_POST[$nam_var]).'",'; }

        $query="insert into kadri (id,fio,pol,passp_seria,passp_nomer,date_rogd,language1,work_place,dolgnost,zvanie,stepen,
                add_work,tel_work,add_home,tel_home,e_mail,site,stag_ugatu,stag_pps,stag_itogo,
                din_nauch_kar , ekspert_spec , ekspert_kluch_slova ,nauch_eksper , prepod_rabota , nagradi , primech,
                passp_date,passp_place,INN,insurance_num,fio_short,person_type,add_contact,to_tabel,is_slave,
	  birth_place,nation,social,family_status)
        values('".f_ri($_POST["elem0"])."',".$insert_vals."".$insert_vals2."'".f_ri($_POST["elem42"])."',
			'".f_ri($_POST["person_type"])."',
			'".f_ri($_POST["add_contact"])."',
			".echoIf($_POST["to_tabel"]=='on',1,0).",
			".echoIf($_POST["is_slave"]=='on',1,0).",
			'".f_ri($_POST["birth_place"])."',
			'".f_ri($_POST["nation"])."',
			'".f_ri($_POST["social"])."',
			'".intval($_POST["family_status"])."' 
			)";
		$act_type='дополнена';
			}


	  //echo $query;
//--------------------------------------------------------------------------------------------------

    //обновление у сотрудника списка "тип участия на каф."
    // <abarmin date="11.09.2012">
    if (array_key_exists("person_type2", $_POST)){
        mysql_query('delete from kadri_in_ptypes where kadri_id='.$kadri_id) or die(mysql_error());	//удаление старых ролей

        $roles = $_POST['person_type2'];
        foreach ($roles as $role) {
            mysql_query('insert into kadri_in_ptypes(kadri_id,person_type_id)
			values('.$kadri_id.','.$role.') ') or die(mysql_error());
        }
    }
    // </abarmin>

    // <abarmin date="08.05.2012">
    // не забываем включать отладчик и смотреть ошибки
    if ($res=mysql_query ($query) && mysql_affected_rows()) {
        echo '<div class="success"> запись успешно '.$act_type.' <b>('.f_ri($_POST['elem2']).')</b>.</div>';
	 
    // при добавлении записи используется максимальный номер id
    if ($kadri_id==0) $kadri_id=getScalarVal('select max(id) from kadri');
	  
	  //обновление сведений по детям
	  if ($kadri_id>0) {
	  $query='delete from kadri_childs where kadri_id='.$kadri_id;	    
	  mysql_query($query);
	  
	  while (list($val,$name)=each($_POST)) { 
	   		if (strstr($val,'child_pol_'))
			   {$i=preg_replace("/[^0-9]/","",$val); //выделение цифры из имени переменной
			    if (intval($_POST['child_pol_'.$i])>0 || $_POST['child_birth_'.$i]!='')
				{$query="insert into kadri_childs(`kadri_id`,`pol_id`,`birth_date`)
				values($kadri_id,".intval($_POST['child_pol_'.$i]).",'".DateTimeCustomConvert($_POST['child_birth_'.$i],'d','rus2mysql')."')";
				//echo $query.'<br>';
				mysql_query($query);}
				}
			   }
	  }	 
	  //--------------------------
	 }
      else {echo '<div class="warning"> БД сотрудников не '.$act_type.'<b>('.f_ri($_POST['elem2']).')</b>.</div>';}
      
	  //echo $query;
                                  }
//------------------для вывода измененных данных--------------------------------------------------------------------------
if (isset ($_GET['action']) and $_GET['action']=="update" and $_GET['kadri_id']!="") {
//    echo $_GET['kadri_id']." !!!!! ";
    $query_all='
          select kadri.id , photo , fio ,fio_short, pol.name as pol,INN,insurance_num ,
          passp_seria , passp_nomer ,passp_place,passp_date, date_rogd , manager_id, department_role_id,
          language.name as language1 , language2 , work_place , dolgnost.name as dolgnost,
          zvanie.name as zvanie, stepen.name as stepen, add_work , tel_work , add_home , tel_home ,
          e_mail , site , stag_ugatu, stag_pps,stag_itogo, din_nauch_kar , ekspert_spec , ekspert_kluch_slova , nauch_eksper ,
          prepod_rabota , nagradi , primech, kadri.person_type, kadri.add_contact, kadri.to_tabel, kadri.is_slave,kadri.birth_place,kadri.nation,kadri.social,kadri.family_status   
          from ((((kadri left join pol on kadri.pol=pol.id) left join language on kadri.language1=language.id)
          left join dolgnost on kadri.dolgnost=dolgnost.id)
          left join zvanie on kadri.zvanie=zvanie.id)left join stepen on kadri.stepen=stepen.id 
          where kadri.id="'.$_GET['kadri_id'].'"';	// limit 0,1
// echo $query_all;
    if ( $res_all=mysql_query($query_all)) {$res_edit=mysql_fetch_array($res_all);
    }    //выборка не пустая
    else {echo "ошибки в выборке. Никто не найден с номером=".$_GET['kadri_id'];exit;}
    //$disabled_val="disabled";
                                                            }

															$person_type=0;
if (isset($_GET['person_type']) && intval($_GET['person_type'])>0)
{$person_type=intval($_GET['person_type']);}

?>

<form name="anket_form" id=anket_form action="" method="post">
  <table name=anketa cellpadding="0" cellspacing="0" width="99%" border=0>
<tr valign="middle" height="44"><td width=35%>
<input type="button" value="Новый" onclick="window.location.href='<?php echo $curpage;?>'" title="новый сотрудник"> 
<input type="button" value="Правка" onclick="javascript:update_lect();" <?php if ($kadri_id==0) {echo 'disabled';}  ?>> 
<input type="button" value="Сохранить" title="<?php
if (!isset($res_edit['id']) or $res_edit['id']=="") {echo "Добавить нового";} else {echo "Сохранить текущего";}?>"
 onclick="javascipt:check_form();"></td> <td><input type="reset" value="Очистить"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    <?php
        // <abarmin date="11.09.2012">
        if (array_key_exists("kadri_id", $_GET)) { ?>
            <input type="button" value="Удалить" onclick="javascript:del_confirm_act('сотрудника','lect_anketa.php?action=delete&kadri_id=<?php echo $_GET['kadri_id'];?>');" <?php if ($kadri_id==0) {echo 'disabled';}  ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php }
    // </abarmin> ?>

 <input type="button" value="Справка" onclick="javascript:help_msg();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 
 <a class=button title="печать" href="javascript:window.open('lect_anketa_print.php?print=1&kadri_id=<?php echo $kadri_id;?>','print_anketa');"><img src="images/print.gif" border=0 alt="печать" title="печать"></a>
 &nbsp; 
 <a class=button target="_blank" title="передача в Word" href="_modules/docs_tpl/index.php?item_id=<?php echo $kadri_id;?>"><img src="images/design/file_types/word_file.gif" border=0 alt="word" title="передача в Word"></a>
 <a href="lect_anketa_view.php">К списку</a></td>  </tr>


<tr><td colspan=2>
        <?php 
		if ($write_mode) {
		?>
		<a href="s_hours.php?tab=2&kadri_id=<?php if(isset($_GET['kadri_id'])) { echo $_GET['kadri_id']; } ?>">Нагрузка, почасовка</a>
		    <a href="kadri_child_rp.php" style="padding-left:40px;">Список сотрудников с детьми </a> &nbsp; 
<span class=success>выбран сотрудник</span> <select name="kadri_id" id="kadri_id" style="width:300;" onChange="javascript:window.location.href='?<?php echo reset_param_name_ARR($query_string,array('kadri_id','action'));?>&action=update&kadri_id='+this.options[this.selectedIndex].value;"> 
		<?php
		$query='SELECT kadri.id  as id,concat(kadri.fio," (",kadri_role(kadri.id,","),")") as fio 
			FROM kadri ';
		if (!$view_all_mode) {$query.=' where kadri.id="'.$kadri_id.'"';}
		$query.=' order by 2';
		
		echo getFrom_ListItemValue($query,'id','fio','kadri_id');
		?>
	  </select>
<p> 
		<?php } ?>
		
	<table border="1" cellpadding="0" cellspacing="0" width="777">
      <tr>
 	<?php //с правами "преподаватель" и при просмотре рецензента пункт "Приказы" недоступен
		if (getTaskAccess($_SESSION['id'],'orders.php')) {
		?>
		<td height=40 id="c0" width="300" bgcolor="#FFFFFF">
          <div align="center"><font size="3"><b><a href="orders.php?kadri_id=<?php if(isset($_GET['kadri_id'])) {echo $_GET['kadri_id'];}?>&type_money=2"
               title="Приказы по сотрудникам...">Приказы</a> </b></font></div>
        </td>
        <?php } ?>
        <td height=40 id="c1" onMouseOver="newColor(this.id);" onMouseOut="backColor(this.id);" onClick="Click_color(this.id,3);" width="300">
          <div align="center"><font size="3"><b><a href="javascript:show_hide('Layer1');">Общие сведения</a> </b></font></div>
        </td>
        <td height=40 id="c2" onMouseOver="newColor(this.id);" onMouseOut="backColor(this.id);" onClick="Click_color(this.id,3);" width="300">
          <div align="center"><font size="3"><b><a href="javascript:show_hide('Layer2');">Образование, диссертации</a></b></font></div>
        </td>
        <td  height=40 id="c3" onMouseOver="newColor(this.id);" onMouseOut="backColor(this.id);" onClick="Click_color(this.id,3);" width="300">
          <div align="center"><font size="3"><b><a href="javascript:show_hide('Layer3');">Трудовая и научная деятельность</b></font></div>
        </td>
      </tr>
    </table>
</td></tr></table>

<div id="Layer1" style="display:"><table name=tab1 cellpadding="0" cellspacing="10" class=forms_under_border width="777">
<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Общие сведения:</b></td></tr>
    <tr><td><!--id--></td> <td>      <input name="elem0" type="hidden" 
	value="<?php if (!isset($res_edit['id']) or $res_edit['id']=="") {echo trim($a['id']+1);} else {echo trim($res_edit['id']);} ?>" >  </td> </tr>
	<tr valign="middle"><td width=300 class=name_desc> Тип участия на каф.</td><td><?php
	
	$style_pType='';$label_text='';
	
	if (!$multy_ptypes)	{
        if ($_SESSION['group_id']!=2 && $_SESSION['role']!='kadri') {//скрываем выбор "Тип участия на каф." для НЕ админа
		    $style_pType=' style="display:none;"';
		     
		    $query='select pt.name from kadri k inner join person_types pt on k.person_type=pt.id where k.id='.$kadri_id;
		    $res=mysql_query($query);
		    $pTypeName=mysql_result($res,0);
		     
		    if ($pTypeName=='') {
			    if ($person_type==2) {
                    $pTypeName='рецензент';
                }elseif ($pTypeName=='') {
                    $pTypeName='профессорско-преподавательский состав';}
				}
            }
		    $label_text='<div><b>'.$pTypeName.'</b></div>';

		    echo $label_text;
		    
		    ?>
            <select <?php echo $style_pType;?> id="person_type" name="person_type">
            <?php
		    $listQuery="select id,name from person_types order by name";
		    //getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName)
		    echo getFrom_ListItemValue($listQuery,'id','name','person_type'); 
		    ?>
            </select><br>
	<?php
	} else { // режим множественного выбора "тип участия"
        $query_='
            SELECT
                pt.id AS pt_id,
                pt.name_short AS pt_name,
                kpt.kadri_id
		    FROM
		        person_types pt
		    LEFT JOIN
		        (select person_type_id,kadri_id from kadri_in_ptypes where kadri_id='.$kadri_id.')kpt
		    ON (pt.id = kpt.person_type_id)
		    order by pt.name_short';
        //echo ' query_='.$query_;
        $_POST['person_type2']=array();
        if ($kadri_id>0) {	//выборка типа участия для сотрудника
            $query_pt='select person_type_id from kadri_in_ptypes where kadri_id='.$kadri_id.'';
            $stat_pt=getRowSqlVar($query_pt);
            if (count($stat_pt)>0) {	//указана роль сотрудника
                for ($i=0;$i<count($stat_pt);$i++){
                    array_push($_POST['person_type2'],$stat_pt[$i]['person_type_id']);
                    $res_edit['person_type2'][$stat_pt[$i]['person_type_id']] = $stat_pt[$i]['person_type_id'];
                }
            }
        } else {	//значения по умолчанию
            if ($person_type==2)	array_push($_POST['person_type2'],2);	//рецензент
            else array_push($_POST['person_type2'],1);	//ППС
        }
        echo '<span >
		  <select class="'.echoIf(!isset($_GET['save']) && !isset($_GET['print']),'imglink','none').'" multiple="multiple" name="person_type2" id="person_type2">';
		  echo getFrom_ListItemValue($query_,'pt_id','pt_name','person_type2',true);
		  echo '</select></span>';
	}
	?>	
	<br>
	<label><input type=checkbox <?php if (isset($res_edit['to_tabel']) && $res_edit['to_tabel']!='1') echo ''; else echo 'checked';  ?> id=to_tabel name=to_tabel >учитывать в <a href="kadri_time_table.php">табеле</a></label>
	<label><input type=checkbox <?php if (isset($res_edit['is_slave']) && $res_edit['is_slave']=='1') echo 'checked';  ?> id=is_slave name=is_slave>совместитель</label>
	</td> </tr>

    <tr>
        <td class="name_desc">Руководитель</td>
        <td><?php CHtml::dropDownList("manager_id", array(0=>"Не указан") + CStaffManager::getPersonsList(), $res_edit['manager_id']); ?></td>
    </tr>

    <tr>
        <td class="name_desc">Роль на кафедре</td>
        <td><?php  CHtml::dropDownList("department_role_id", array(0=>"Не указана") + CTaxonomyManager::getTaxonomy(TAXONOMY_DEPARTMENT_ROLES)->getTermsList(), $res_edit['department_role_id']); ?></td>
    </tr>

	<tr valign="middle">
	  <td class=name_desc> Фото </td><td><a href="lect_photo.php?kadri_id=<?php if(isset($_GET['kadri_id'])) {echo $_GET['kadri_id'];} ?>">
		<?php if (isset($res_edit['photo']) && $res_edit['photo']!='') {echo '<img src="images/lects/small/sm_'.urlencode($res_edit['photo']).'" height=120>';}
		else {echo '<img src="images/no_photo.jpg">';} ?> 
		изменить... </a>  </td> </tr>
    <tr><td class=name_desc> ФИО_полное <span class=warning>*</span>   </td> <td> <input name="elem2" id="elem2" onChange="FIO_sokr();" type="text" value="<?php echo getFormItemValue('fio').'" '.$disabled_val;?> size=60>  </td> </tr>
    <tr><td class=name_desc> ФИО_краткое <span class=warning>*</span></td> <td> <input name="elem42" id="elem42" type="text" value='<?php if (isset($res_edit['fio_short'])) {echo $res_edit['fio_short'];} echo"'".$disabled_val;?> size=60>  </td> </tr>
<tr><td class=name_desc> Пол </td> <td><select name="elem3" ><option value="0">нет данных</option>
<?php 
$res=mysql_query('select * from pol');
while($a=mysql_fetch_array($res))  {array_walk($a,'arr_replace');
    if (isset($res_edit['pol']) && $a['name']==$res_edit['pol']) {echo '<option value='.($a['id']).' selected>'.($a['name']).'</option>';}
    else {echo '<option value='.($a['id']).'>'.($a['name']).'</option>';}     }
?>
</select>    </td>  </tr>
<tr><td class=name_desc> Дата, место рождения </td>
	  <td><input title="дата рождения" name="elem6" id=elem6 type=text value="<?php echo $res_edit['date_rogd'];?>" size=12 maxlength="10" class="tab_view">
<button type="reset" id="f_trigger_elem6">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "elem6",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_elem6",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script> 
	<input title="место рождения" type=text id="birth_place" name="birth_place" value="<?php echo $res_edit['birth_place'];?>" maxlength=60 size=40> 
	  </td>
	  
</tr>
<tr><td class=name_desc> Национальность <a class=help title="автозаполнение">А</a></td>
	  <td><input title="национальность" name="nation" id="nation" type=text value="<?php echo $res_edit['nation'];?>" size=50 maxlength="60" ></td>	  
</tr>
<tr><td class=name_desc> Социальное происхождение <a class=help title="автозаполнение">А</a></td>
	  <td><input title="соц. происхождение" name="social" id=social type=text value="<?php echo $res_edit['social'];?>" size=50 maxlength="60" ></td>	  
</tr>
<tr><td class=name_desc> Семейное положение</td>
	  <td><select title="сем. положение" name="family_status" id="family_status" style="width:200;"> 
		<?php
		$query='SELECT id  as id,name from `family_status` order by 2';
		
		echo getFrom_ListItemValue($query,'id','name','family_status');
		?>
</select></td>	  
</tr>
<tr><td class=name_desc> ИНН </td> <td>      <input name="elem40" type=text value="<?php echo $res_edit['INN'];?>" size=15 maxlength="12" class="tab_view" >
 страховой номер  <input name="elem41" type=text value="<?php echo $res_edit['insurance_num'];?>" size=18 maxlength="14" class="tab_view" >        </td> </tr>
<tr><td class=name_desc> Паспортные данные: </td>
	  <td class=text style="text-align:left;">серия
	  <input name="elem4" type=text value="<?php echo $res_edit['passp_seria'];?>" size=8 maxlength="4" class="tab_view" >
, 	номер
	  <input name="elem5" type=text value="<?php echo $res_edit['passp_nomer'];?>" size=10 maxlength="6" class="tab_view" >
	  дата выдачи
	  <input name="elem38" id=elem38 type=text value="<?php echo $res_edit['passp_date'];?>" size=12 maxlength="10" class="tab_view" >
 <button type="reset" id="f_trigger_elem38">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "elem38",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_elem38",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
 <br>
 кем выдан документ
 <textarea  name="elem39" type=text cols=50 rows=2 class="tab_view" style="overflow-y:hidden;overflow:hidden;" ><?php echo $res_edit['passp_place'];?></textarea></td> </tr>

<tr><td class=name_desc>Иностранный язык:</td> <td>      <select name="elem7" 
title="Выберите значение из списка, если нужного значения нет, используйте кнопку Новый для пополнения списка... ">
<option value="0">нет данных</option>
<?php $res=mysql_query('select * from language order by name');
while($a=mysql_fetch_array($res)) {array_walk($a,'arr_replace');
    if ($a['name']==$res_edit['language1']) {echo '<option value='.($a['id']).' selected>'.($a['name']).'</option>';}
    else {echo '<option value='.($a['id']).'>'.($a['name']).'</option>';}     }

?></select>
<input type=button value=Новый onclick="new_item();">     </td>  </tr>

<tr><td class=name_desc> Основное место работы<br>(для совместителей) </td>
<td><textarea name="elem8" rows=2 cols=50 class="tab_view" style="overflow-y:hidden; overflow:hidden;" ><?php echo $res_edit['work_place'];?></textarea>  </td> </tr>

<tr><td class=name_desc>Должность:</td> <td>      <select name="elem9" ><option value="0">нет данных</option>
<?php 
$res=mysql_query('select * from dolgnost');
while($a=mysql_fetch_array($res))  {array_walk($a,'arr_replace');
    if ($a['name']==$res_edit['dolgnost']) {echo '<option value='.$a['id'].' selected>'.$a['name'].'</option>';}
    else {echo '<option value='.$a['id'].'>'.$a['name'].'</option>';}     }
?>
</select>
<input type=button value=Новый onclick="new_item();"> &nbsp; 
Ставка<a href="#ratedetail" class=help title="считается по данным действующих приказов: основной-бюджет">?</a>: <?php
if ($kadri_id>0) {
	  $query_rate='SELECT round(sum(od.rate),2) as rate_sum,count(od.rate) as ord_cnt
  FROM `orders` od
  WHERE cast(concat(substring(od.date_end,7,4),".",substring(od.date_end,4,2),".",substring(od.date_end,1,2)) as datetime)>=now() and kadri_id='.$kadri_id;
  $rate_stat=getRowSqlVar($query_rate);
  if ($rate_stat[0]['rate_sum']>0)
	  echo '<a href="orders.php?kadri_id='.$kadri_id.'" title="к приказам сотрудника">'.$rate_stat[0]['rate_sum'].'<sup>'.$rate_stat[0]['ord_cnt'].'</sup></a>';
}
?>
 </td>  </tr>
<tr>
    <td class=name_desc>Звание:</td>
    <td>
        <select name="elem10" >
            <option value="0">нет данных</option>
<?php 
    $res=mysql_query('select * from zvanie');
    while($a=mysql_fetch_array($res)) {
        array_walk($a,'arr_replace');
        if ($a['name']==$res_edit['zvanie']) {
            echo '<option value='.$a['id'].' selected>'.$a['name'].'</option>';
        } else {
            echo '<option value='.$a['id'].'>'.$a['name'].'</option>';
        }
    }
?>
        </select>
        <input type=button value=Новый onclick="new_item();">
    </td>
</tr>

<tr><td class=name_desc>Ученая степень:</td> <td>      <select name="elem11" ><option value="0">нет данных</option>
<?php
$res=mysql_query('select * from stepen');
while($a=mysql_fetch_array($res))  {array_walk($a,'arr_replace');
    if ($a['name']==$res_edit['stepen']) {echo '<option value='.$a['id'].' selected>'.$a['name'].'</option>';}
    else {echo '<option value='.$a['id'].'>'.$a['name'].'</option>';}     }

?>
</select> <input type=button value=Новый onclick="new_item();">     </td>  </tr>

<!-- начало списка детей сотрудников	-->
<tr><td class=name_desc>Дети:</td><td>
	  
<div id="table_childs" name="table_childs" style="text-align:left" >
   <table border="0" cellspacing="2" cellpadding="0">
    <?php 	$row_id=0;	?>
     <tr id="newline" nomer="_<?php echo $row_id; ?>">
       <td valign="top" align="left">
	   <a href="#" onclick="return addline('table_childs');" style="text-decoration:none"><img src="images/design/pl.gif" border="0" WIDTH="17" HEIGHT="18"></a></td></tr>
    <?php    
    $query_ch='select id,pol_id,birth_date from kadri_childs where kadri_id='.$kadri_id;
    $res_ch=mysql_query($query_ch);
    if (mysql_numrows($res_ch)>0) {
    while ($a_ch=mysql_fetch_assoc($res_ch)) {    
    $_POST['child_pol_'.$row_id]=$a_ch['pol_id'];
    $_POST['child_birth_'.$row_id]=DateTimeCustomConvert($a_ch['birth_date'],'d','mysql2rus');
    ?>
    <tr id="newline" nomer="_<?php echo $row_id; ?>">
	  <td>
	  пол <select name="child_pol_<?php echo $row_id; ?>" id="child_pol_<?php echo $row_id; ?>" style="width:120;">
	  <?php
	  $listQuery='select id,name from pol order by name';
	  echo getFrom_ListItemValue($listQuery,'id','name','child_pol_'.$row_id);
	  ?>
	  </select>
	  дата рождения <input type="text" name="child_birth_<?php echo $row_id;?>" id="child_birth_<?php echo $row_id;?>" maxlength=10 size=10 value="<?php if (isset($_POST['child_birth_'.$row_id])) {echo f_ro($_POST['child_birth_'.$row_id]);}?>">	  
	  <button type="reset" id="f_trigger_child_birth_<?php echo $row_id;?>">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "child_birth_<?php echo $row_id;?>",      
        ifFormat       :    "%d.%m.%Y",       
        showsTime      :    false,            
        button         :    "f_trigger_child_birth_<?php echo $row_id;?>",   
        singleClick    :    true,           
        step           :    1               
    });
	</script>
      <td valign="top" align="center"><a href="#" onclick="return rmline(<?php echo $row_id;?>,'table_childs');" style="text-decoration:none"><img src="images/design/mn.gif" border="0" WIDTH="17" HEIGHT="18"></a></td></tr>
	<?php
	$row_id++;
	}
    } else {//шаблон нового элемента 
	?>
<tr id="newline" nomer="_<?php echo $row_id; ?>">
	  <td>
	  пол <select name="child_pol_<?php echo $row_id; ?>" id="child_pol_<?php echo $row_id; ?>" style="width:120;">
	  <?php
	  $listQuery='select id,name from pol order by name';
	  echo getFrom_ListItemValue($listQuery,'id','name',"child_pol_".$row_id);
	  ?>
	  </select>
	  дата рождения <input type="text" name="child_birth_<?php echo $row_id;?>" id="child_birth_<?php echo $row_id;?>" maxlength=10 size=10 value="<?php echo getFormItemValue('child_birth_'.$row_id); ?>">	  
	  <button type="reset" id="f_trigger_child_birth_<?php echo $row_id;?>">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "child_birth_<?php echo $row_id;?>",      
        ifFormat       :    "%d.%m.%Y",       
        showsTime      :    false,            
        button         :    "f_trigger_child_birth_<?php echo $row_id;?>",   
        singleClick    :    true,           
        step           :    1               
    });
	</script>
      <td valign="top" align="center"><a href="#" onclick="return rmline(<?php echo $row_id;?>,'table_childs');" style="text-decoration:none"><img src="images/design/mn.gif" border="0" WIDTH="17" HEIGHT="18"></a></td></tr>
      <?php } ?>
  </table>
</div>
<!-- окончание списка детей сотрудников	-->
<input type="hidden" id=max_table_childs name="max_table_childs" value="<?php echo $row_id;?>">
</td></tr>
<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Контактная информация:</b></td></tr>
<tr><td height="27" class=name_desc> Адрес служебный: </td> <td height="27"><input name="elem12" type=text value="<?php if (isset($res_edit)) {echo $res_edit['add_work'];}echo '" '.$disabled_val; ?> size=60>  </td> </tr>
<tr><td class=name_desc> Телефон служебный: </td> <td><input name="elem13" type=text value="<?php if (isset($res_edit)) {echo $res_edit['tel_work'];}echo'" '.$disabled_val; ?> size=60>  </td> </tr>
<tr><td class=name_desc> Адрес домашний: </td> <td><input name="elem14" type=text value="<?php if (isset($res_edit)) {echo $res_edit['add_home'];}echo'" '.$disabled_val; ?> size=60>  </td> </tr>
<tr><td class=name_desc> Телефон домашний: </td> <td><input name="elem15" type=text value="<?php if (isset($res_edit)) {echo $res_edit['tel_home'];}echo'" '.$disabled_val; ?> size=60>  </td> </tr>
<tr><td class=name_desc> Электронная почта: </td> <td><input name="elem16" type=text value="<?php if (isset($res_edit)) {echo $res_edit['e_mail'];}echo'" '.$disabled_val; ?> size=60>  </td> </tr>
<tr><td class=name_desc> Дополнительные контакты: </td> <td><textarea name="add_contact" id=add_contact cols=50 rows=2 style="overflow-y:hidden;overflow:hidden;" title="icq, jabber, skype ..."<?php echo " ".$disabled_val.">"; if (isset($res_edit)) {echo $res_edit['add_contact'];} ?></textarea> </td> </tr>
<tr><td class=name_desc> Сайт в интернете: </td> <td><input name="elem17" type=text value="<?php if (isset($res_edit)) {echo $res_edit['site'];}echo'" '.$disabled_val; ?> size=60>  </td> </tr>
</table></div>

<div id="Layer2" style="display:none">
<table name=tab2 cellpadding="0" cellspacing="10" class=forms_under_border width="777" border=0>
<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Высшее образование:</b>&nbsp; &nbsp;
<input type=button name=Bun_obraz value="Правка" onclick="javascript:win_open_('obrazov');"></td></tr>

	<?php
		 if (isset($res_edit)) {
		 	$query='select * from obrazov where kadri_id="'.$_GET['kadri_id'].'" order by god_okonch desc' ;
		 	$res=mysql_query($query);$i=1;
		 	if (mysql_num_rows($res)==0) { echo '<tr><td colspan=2>Нет данных<hr></td></tr>';}
			while ($res_edit_obraz=mysql_fetch_array($res))
		 	{
			echo '<tr><td colspan=2>
			<table><tr>
			<td><a href="spav_other.php?type=obrazov&kadri_id='.$_GET['kadri_id'].'&id='.$res_edit_obraz['id'].'" 
				style="color:grey;text-decoration:none; font-family:Arial; font-size:12pt;" title="кликните для редактирования">'.$i;
			echo '<br><small>ВУЗ:</small><b>'.$res_edit_obraz['zaved_name']. '</b>, ';
		 	echo '<small>год окончания:</small><b>'.$res_edit_obraz['god_okonch']. '</b>, ';
		 	echo '<br><small>специальность в дипломе:</small><b>'.$res_edit_obraz['spec_name'].'</b>.';
		 	echo '<!--<br><small>Доп.информация:</small><b>'.$res_edit_obraz['spec_comment']. '</b>--></a></td>
			<td width="*" align=right>'.
			($res_edit_obraz['file_attach']!=''?printThrumb($res_edit_obraz['file_attach'],$obrazov_path,$small_img_path):'')
			.'</td>
			</tr></table>
			</td></tr>';
		 	$i++;
			echo '<tr><td colspan=2><hr></td></tr>';
		 	
			}				}
	 ?>


<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Курсы повышения квалификации:</b>&nbsp; &nbsp;
<input type=button name=Bun_obraz value="Правка" onclick="javascript:win_open_('course');"></td></tr>
	<?php
		 if (isset($res_edit)) {
		 	$query='SELECT id, name , place , date_start , date_end , document , comment,file_attach FROM courses where kadri_id="'.$_GET['kadri_id'].'" order by date_end desc,id desc' ;
		 	$res=mysql_query($query);$i=1;
		 	if (mysql_num_rows($res)==0) { echo '<tr><td colspan=2>Нет данных<hr></td></tr>';}
		 	while ($res_edit_obraz=mysql_fetch_array($res))
		 	{
			echo '<tr><td><a href="spav_other.php?type=course&kadri_id='.$_GET['kadri_id'].'&id='.$res_edit_obraz['id'].'" 
				style="color:grey;text-decoration:none; font-family:Arial; font-size:12pt;" title="кликните для редактирования">'.$i;
			echo '<br><small>Название курсов: </small><b>'.$res_edit_obraz['name']. '</b>, ';
		 	echo '<br><small>Место проведения: </small><b>'.$res_edit_obraz['place']. '</b>, ';
		 	echo '<br><small>Время проведения: начало <b>'.DateTimeCustomConvert($res_edit_obraz['date_start'],'d','mysql2rus').'</b>, окончание <b>'.DateTimeCustomConvert($res_edit_obraz['date_end'],'d','mysql2rus').'</b></small>.';
		 	echo '<br><small>Документ по завершении: </small><b>'.$res_edit_obraz['document'].'</b>.';
		 	echo '<!--<br><small>Доп.информация: </small><b>'.$res_edit_obraz['comment']. '</b>--></a></td>
			<td width="*" align=right>'.
			($res_edit_obraz['file_attach']!=''?printThrumb($res_edit_obraz['file_attach'],$obrazov_path,$small_img_path):'')
			.'</td></tr>';
		 	$i++;
			echo '<tr><td colspan=2><hr></td></tr>';
		 	
			}				}
	 ?>


<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Кандидатская диссертация:</b>&nbsp; &nbsp;
<input type=button name=Bun_obraz value="Правка" onclick="javascript:win_open_('kandid');"></td></tr>
	<?php
		 if (isset($res_edit)) {
		 	$query='SELECT id,tema , spec_nom , god_zach , disser_type , comment , kadri_id,file_attach FROM disser where kadri_id="'.$_GET['kadri_id'].'"  and disser_type="кандидат" order by id desc' ;
		 	$res=mysql_query($query);$i=1;
		 	if (mysql_num_rows($res)==0) { echo '<tr><td colspan=2>Нет данных<hr></td></tr>';}
		 	while ($res_edit_kandid=mysql_fetch_array($res))
		 	{
			echo '<tr><td ><a href="spav_other.php?type=kandid&kadri_id='.$_GET['kadri_id'].'&id='.$res_edit_kandid['id'].'" 
				style="color:grey;text-decoration:none; font-family:Arial; font-size:12pt;" title="кликните для редактирования">'.$i;
			echo '<br><small>Тема: </small><b>'.$res_edit_kandid['tema']. '</b>, ';
		 	echo '<br><small>Номер спец-ти по ВАК: </small><b>'.$res_edit_kandid['spec_nom']. '</b>, ';
		 	echo '<br><small>Год защиты: </small><b>'.$res_edit_kandid['god_zach'].'</b>';
		 	echo '<!--<br><small>Доп.информация: </small><b>'.$res_edit_kandid['comment']. '</b>--></a></td>
			<td width="*" align=right>'.
			($res_edit_kandid['file_attach']!=''?printThrumb($res_edit_kandid['file_attach'],$disser_k_path,$small_img_path):'')
			.'</td></tr>';
		 	$i++;
			echo '<tr><td colspan=2><hr></td></tr>';
		 	
			}				}
	 ?>


<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Докторская диссертация:</b>&nbsp; &nbsp;
<input type=button name=Bun_obraz value="Правка" onclick="javascript:win_open_('doktor');"></td></tr>
	<?php
		 if (isset($res_edit)) {
		 	$query='SELECT id,tema , spec_nom , god_zach , disser_type , comment , kadri_id FROM disser where kadri_id="'.$_GET['kadri_id'].'" and disser_type="доктор" order by id desc' ;
		 	if (mysql_num_rows($res)==0) { echo '<tr><td colspan=2>Нет данных<hr></td></tr>';}
			$res=mysql_query($query);$i=1;
		 	while ($res_edit_doktor=mysql_fetch_array($res))
		 	{
			echo '<tr><td colspan=2><a href="spav_other.php?type=doktor&kadri_id='.$_GET['kadri_id'].'&id='.$res_edit_doktor['id'].'" 
				style="color:grey;text-decoration:none; font-family:Arial; font-size:12pt;" title="кликните для редактирования">'.$i;
			echo '<br><small>Тема: </small><b>'.$res_edit_doktor['tema']. '</b>, ';
		 	echo '<br><small>Номер спец-ти по ВАК: </small><b>'.$res_edit_doktor['spec_nom']. '</b>, ';
		 	echo '<br><small>Год защиты: </small><b>'.$res_edit_doktor['god_zach'].'</b>';
		 	echo '<!--<br><small>Доп.информация: </small><b>'.$res_edit_doktor['comment']. '</b>--></a></td></tr>';
		 	$i++;
			echo '<tr><td colspan=2><hr></td></tr>';
		 	
			}				}
	 ?>

    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td colspan="2" align="center" valign="bottom" height="27"><b>Звание:</b>&nbsp;&nbsp;
        <input type=button name=Bun_obraz value="Правка" onclick="javascript:win_open_('degree');"></td>
    </tr>
    <?php
    $person = CStaffManager::getPerson(CRequest::getInt("kadri_id")); $i = 1;
    if (!is_null($person)) : foreach ($person->degrees->getItems() as $degree) :
        ?>
    <tr>
        <td>
            <p><?php echo $i++; ?></p>
            <p>Степень <a href="spav_other.php?kadri_id=<?php echo $person->getId(); ?>&type=degree&id=<?php echo $degree->getId(); ?>"><?php echo $degree->degree->getValue(); ?> <?php echo $degree->subject; ?></a></p>
            <p>Присвоена в <?php echo $degree->year; ?> году</p>
            <p>Свидетельство номер <?php echo $degree->doc_num; ?>, серия <?php echo $degree->doc_series; ?></p>
        </td>
        <td>
            <?php if ($degree->file != "") : ?>
                <a href="<?php echo WEB_ROOT."library/anketa/kandid/".$degree->file; ?>"><img src="<?php
                echo CUtils::getFileMimeIcon(CORE_CWD."/library/anketa/kandid/".$degree->file);
                ?>"></a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; endif; ?>

</table>
<div class=text><strong>Примечание:</strong>
<ul>
	  <li>записи в каждом из подразделов приводятся в обратном хронологическом порядке;</li>
	  <li>справа от вида образования указавается прикрепленный файл (при наличии);</li>
	  <li>если найдена пиктограмма для прикрепленного файла, она также отражается.</li>
</ul>
</div>
<!-- конец вкладки "Образование, диссертации" -->
</div>


<div id="Layer3" style="display:none"><table name=tab3 cellpadding="0" cellspacing="10" class=forms_under_border width="777">
<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Трудовая и научная деятельность:</b></td></tr>
<tr><td> Стаж,полных лет  </td> <td>в УГАТУ<input name="elem27" type=text value="<?php if (isset($res_edit)) {echo $res_edit['stag_ugatu'];}echo'" '.$disabled_val; ?> size=10>
, ППС<input name="elem28" type=text value="<?php if (isset($res_edit)) {echo $res_edit['stag_pps'];}echo'" '.$disabled_val; ?> size=10>
, общий<input name="elem29" type=text value="<?php if (isset($res_edit)) {echo $res_edit['stag_itogo'];}echo'" '.$disabled_val; ?> size=10></td> </tr>
<tr><td width=300>Динамика научной карьеры (должность, учреждение, годы):  </td>
      <td>        <textarea name="elem30" rows="8" cols="60" <?php echo " ".$disabled_val.">"; if (isset($res_edit)) {echo $res_edit['din_nauch_kar'];} ?></textarea>      </td>     </tr>
<tr><td width=300>Экспертная область:  </td> <td>     научная специальность<br>  <textarea name="elem31" cols="60"
<?php echo " ".$disabled_val.">";if (isset($res_edit)) {echo $res_edit['ekspert_spec'];} ?></textarea>
<br>  ключевые слова<br> <textarea name="elem32" rows="4" cols="60" <?php echo " ".$disabled_val.">";if (isset($res_edit)) {echo $res_edit['ekspert_kluch_slova'];} ?></textarea>          </td> </tr>

<tr><td width=300>Опыт научной экспертизы:</td><td><textarea name="elem33" rows="4" cols="60" <?php echo " ".$disabled_val.">";if (isset($res_edit)) {echo $res_edit['nauch_eksper'];} ?></textarea>          </td> </tr>
<tr><td width=300>Опыт преподавательской работы:</td> <td><textarea name="elem34" rows="4" cols="60" <?php echo " ".$disabled_val.">";if (isset($res_edit)) {echo $res_edit['prepod_rabota'];} ?></textarea>          </td> </tr>
<tr><td width=300>Научные награды:</td> <td><textarea name="elem35" cols="60" <?php echo " ".$disabled_val.">";if (isset($res_edit)) {echo $res_edit['nagradi'];} ?></textarea>          </td> </tr>

<tr><td>Общее число публикаций:  </td> <td><input name="elem36" type=text value="<?php if (isset($izdan_nums)) {echo $izdan_nums;} ?>" size=10 disabled>
<input type=button name=Bun_izdan value="Правка" onclick="document.location.href='izdan_view.php?kadri_id=<?php if (!isset($res_edit['id']) or $res_edit['id']=="") {echo trim($a['id']+1);} else {echo trim($res_edit['id']);} ?>'">
</td> </tr>


<tr><td> Примечание: </td> <td> <textarea name="elem37" cols="60" <?php echo " ".$disabled_val.">";if (isset($izdan_nums)) {echo $res_edit['primech'];} ?></textarea>  </td> </tr>
</table></div>

<table name=anketa cellpadding="0" cellspacing="0" width="99%" border=0>
<tr valign="middle" height="44"><td width=35%>
<input type="button" value="Новый" onclick="window.location.href='<?php echo $curpage;?>'" title="новый сотрудник"> 
<input type="button" value="Правка" onclick="javascript:update_lect();" <?php if ($kadri_id==0) {echo 'disabled';}  ?>> 
<input type="button" value="Сохранить" title="<?php
if (!isset($res_edit['id']) or $res_edit['id']=="") {echo "Добавить нового";} else {echo "Сохранить текущего";}?>"
 onclick="javascipt:check_form();"></td> <td><input type="reset" value="Очистить"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  
 <input type="button" value="Удалить" onclick="javascript:del_confirm_act('сотрудника','lect_anketa.php?action=delete&kadri_id=<?php echo $_GET['kadri_id'];?>');" <?php if ($kadri_id==0) {echo 'disabled';}  ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <input type="button" value="Справка" onclick="javascript:help_msg();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <a class=button title="печать" href="javascript:window.open('lect_anketa_print.php?print=1&kadri_id=<?php echo $kadri_id;?>','print_anketa');"><img src="images/print.gif" border=0 alt="печать" title="печать"></a>
 &nbsp; 
 <a class=button target="_blank" title="передача в Word" href="_modules/docs_tpl/index.php?item_id=<?php echo $kadri_id;?>"><img src="images/design/file_types/word_file.gif" border=0 alt="word" title="передача в Word"></a> 
 <a href="lect_anketa_view.php">К списку</a></td>  </tr>
</table>

</form>
<p> <a href="p_administration.php">К списку задач.</a></p>
<?php
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>
<?php include('footer.php'); ?>