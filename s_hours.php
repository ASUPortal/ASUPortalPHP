<?php

$bodyOnLoad=' OnLoad=""';//update_lect_load();"';
/*
if (isset($_GET['tab'])) {
	if ($_GET['tab']=='1') {$bodyOnLoad.='Click_color(\'tab_c1\',4);show_hide(\'LayerTab1\');"';}
	else if ($_GET['tab']=='2') {$bodyOnLoad.= 'Click_color(\'tab_c2\',4);show_hide(\'LayerTab2\');"';}
	else if ($_GET['tab']=='3') {$bodyOnLoad.='Click_color(\'tab_c3\',4);show_hide(\'LayerTab3\');"';}
}
else {$bodyOnLoad.="\"";}*/

include ('authorisation.php');

include ('master_page_short.php');


$kind_type_defaults='&kind_type1=on&kind_type2=on&kind_type3=on&kind_type4=on&filial_flag=on';		//какую нагрузку показывать
$query_string=$_SERVER['QUERY_STRING'];

$tab=2;
if (isset($_GET['tab']) && intval($_GET['tab'])>0) $tab=intval($_GET['tab']);


?>
    <script language="JavaScript" src="scripts/tabs.js"></script>
    <script language="JavaScript">
        /*  применить фильтр по виду средств нагрузки  */
        function setCat(chElement)
        {
            var hrefPath=window.location.search;

            //alert(chElement.id+'='+chElement.checked);

            var re = new RegExp("&"+chElement.id+"=[^&]+","gi");
            hrefPath=hrefPath.replace(re,'')+'&'+chElement.id+'='+(chElement.checked?1:0);
            hrefPath=hrefPath.replace(/tab=\d+/,'tab=3');	// позиционировать на своде
            //alert(hrefPath);
            window.location.search=hrefPath;
        }
        /* преобразовать дробное число с учетом направления отображения (browse, [mysql, php,...]	)*/
        function numLocal(number,direction)
        {
            localnum='';
            number=String(number);
            //if (parseFloat(number)==0) number='';	// очистить нулевые значения
            if (number!='' && number!=0)
            {
                if (direction=='browse') localnum=number.replace('.',',');
                else localnum=number.replace(',','.');
            }
            if (localnum=='' && direction!='browse') return 0;
            else return localnum;
        }

        function calc_sum(item_name)	// пересчет итоговой суммы
        {
            var kind1=numLocal($("input[name*='"+item_name+"']").val());
            var kind2=numLocal($("input[name*='"+item_name+"_add']").val());
            var kind_sum=$("span[name*='"+item_name+"_sum']");

            kind_sum.text(numLocal(parseFloat(kind1)+parseFloat(kind2),'browse'));
        }
        function select_year_itog(kadri_id,year_id)
        {
            alert('?tab=3&kadri_id='+kadri_id+'&year='+year_id);
            window.location.href='?tab=3&kadri_id='+kadri_id+'&year='+year_id;
        }
        function show_hide(layerId) {	// вывести выбранный слой вкладки
            var tabMask=layerId.substr(0,layerId.length-1);
            $('div[id*='+tabMask+']').attr("style", "display:none;" );
            $('div[id='+layerId+']').attr("style", "" );

            // корректировка видимости элементов в IE6
            $('div[id*='+tabMask+'][id!='+layerId+']').children('form').css("display","none");
            $('div[id='+layerId+']').children('form').css("display","");
        }
        function Click_color(tabId)	// подсветить выбранную вкладку
        {
            var tabMask=tabId.substr(0,tabId.length-1);
            $('td[id*='+tabMask+']').removeClass("forms_under_border");
            $('td[id*='+tabMask+']').css("font-weight","normal");

            $('td[id='+tabId+']').addClass("forms_under_border");
            $('td[id='+tabId+']').css("font-weight","bold");
        }
        function help_msg()
        { alert('Эта главная форма, предназначена для ввода и анализа почасовой нагрузки преподавателей.\n'+'');
        }
        function test_hours_kind_form()
        {
//alert('form_suubmit');
            var err=false;
            var msg='Не указаны:';

            if (document.hours_kind_form.year_id.value==0) {msg=msg+'\n год;';err=true;}
            if (document.hours_kind_form.part_id.value==0) {msg=msg+'\n семестр;';err=true;}
            if (document.hours_kind_form.subject_id.value==0) {msg=msg+'\n дисциплина;';err=true;}
            if (document.hours_kind_form.spec_id.value==0) {msg=msg+'\n специальность;';err=true;}
            if (document.hours_kind_form.level_id.value==0) {msg=msg+'\n курс;';err=true;}
//if (document.hours_kind_form.group_list.value==0) {msg=msg+'\n группа;';err=true;}

            if (err) {alert(msg+'\nТребуется исправить все ошибки.');}
            else {document.hours_kind_form.submit();}
        }
        function test_hours_kind_form_and_view()
        {
            document.hours_kind_form.goView.value=1;	//признак перехода в просмотр
            test_hours_kind_form();
//alert(document.links['view_hours_form'].href);
//document.links['view_hours_form'].onClick();
//setTimeout("goView()", 1000);
//window.location.href='?tab=3&kadri_id='+kadri_id+'&year='+year_id;
//setTimeout("alert('test')", 1000);test_hours_kind_form();
        }
    </script>
<?php
//echo phpinfo(); exit();
//session_start();


//include ('menu.htm');
//include ('sql_connect.php');


?>
    <h4>Часовая нагрузка сотрудников(ППС) <span > <a href="_modules/_hrs_rate/">справочник ставок </a> </span> </h4>
<?php
//print_r($_SESSION);
//для расчета почасовки по месяцам
$months=array(9=>'сентябрь','октябрь','ноябрь','декабрь','январь','февраль','март','апрель','май','июнь','июль','август');
$multy_ptypes=true;	//множественные "тип участия на каф." у сотрудника

//для расчета нагрузки - список показателей видов часов
$hour_kind_name=array('лекции', 'практич.', 'лаборатор. занятий', 'расчет.-грф. работы', 'КСР', 'рецензир. контр. работ', 'консультация', 'зачеты',
    'экзамены', 'учебная практика', 'производств. практика', 'курсовые проекты', 'консультац. диплом. проект', 'ГЭК', 'занятия с аспирантами',
    'руководство аспирант.', 'посещение занятий');
$hour_kind_code=array('lects', 'practs', 'labor', 'rgr', 'ksr', 'recenz', 'consult', 'test', 'exams', 'study_pract', 'work_pract', 'kurs_proj', 'consult_dipl', 'gek', 'aspirants', 'aspir_manage', 'duty');


if (!isset($_GET['kadri_id']) or $_GET['kadri_id']=="")
{
    ?>
    <select id="teach_name" name="teach_name"
            onChange="javascript:window.location.href='?tab=2&kadri_id='+this.options[this.selectedIndex].value;"><?php
        $listQuery="select k.id,k.fio as name
		 	from kadri k left join kadri_in_ptypes kpt on k.id = kpt.kadri_id left join person_types pt on pt.id=kpt.person_type_id
			where pt.name_short like '%ППС%'
			order by k.fio_short";
        //getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName)
        echo getFrom_ListItemValue($listQuery,'id','name','kadri_id');
        ?></select>
    <?php

    //persons_select('tab=2&kadri_id');
    echo'<p>';

    echo '<p><a href="p_administration.php">К списку задач.</a></p>';

    exit;}

$fio="";            //

$fio_res=mysql_query("select fio from kadri where id=".$_GET['kadri_id']." limit 0,1");
//echo "select fio from kadri where id=".$_GET['kadri_id'];
$a=mysql_fetch_array($fio_res);
$fio=$a['fio'];

$filial_flag=0;
if (isset($_POST['filial_flag']) && $_POST['filial_flag']=='on') {$filial_flag=1;};

//выбираем найстройки по умолчанию (год, семестр)
$def_settings = array();
$def_settings['year_id'] = CUtils::getCurrentYear()->getId();
$def_settings['part_id'] = CUtils::getCurrentYearPart()->getId();

$year_id_all=$def_settings['year_id'];$i=1;
if (isset($_GET['year']) && intval($_GET['year']>0 )) { $year_id_all=intval($_GET['year']);}

//-----------обработка изменений в Почасовке---------------------------------------------------
if (isset($_POST['hours_type1']))
{//echo '<hr>type1='.$_POST['hours_type1'].', type2='.$_POST['hours_type2'].'<hr>';

    //----------------------hours_type1
    if ($_POST['hours_type1']=="insert")
    {//echo "Добавление бюджетной почасовки";
        $insert_string="";$insert_action=false;
        for ($i=1;$i<13;$i++)
        { $name_elem="elBudget_".$i; if ($_POST[$name_elem]!='') {$insert_action=true;break;} }

        if ($insert_action==true) {
            for ($i=1;$i<13;$i++) {$name_elem="elBudget_".$i; $insert_string=$insert_string.', "'.$_POST[$name_elem].'"';}
            $query='insert into hours_year(kadri_id,year_id, m09, m10, m11, m12, m01, m02, m03, m04, m05, m06, m07, m08, bud_commerce)
				           values("'.$_GET['kadri_id'].'","'.$_POST['year_id'].'" '.$insert_string.',"бюджет")';
            //echo "<hr>".$query;
            if (mysql_query($query)) {echo "<br>Почасовка бюджетная по сотруднику: <b>".$fio."</b> успешно добавлена";}
            else {echo "<br>Почасовка бюджетная не добавлена...";}
        }
        else {echo '<br><font color=red>Почасовка бюджетная не добавлена......Не внесены необходимые данные .</font>';}
    }
    if ($_POST['hours_type1']=="update")
    {//echo "Изменение бюджетной почасовки";
        $query='update hours_year set year_id="'.$_POST['year_id'].'", m09="'.$_POST['elBudget_1'].'",m10="'.$_POST['elBudget_2'].'",
	                   m11="'.$_POST['elBudget_3'].'",m12="'.$_POST['elBudget_4'].'",m01="'.$_POST['elBudget_5'].'",
	                   m02="'.$_POST['elBudget_6'].'", m03="'.$_POST['elBudget_7'].'",m04="'.$_POST['elBudget_8'].'",
					   m05="'.$_POST['elBudget_9'].'", m06="'.$_POST['elBudget_10'].'",m07="'.$_POST['elBudget_11'].'",
	                   m08="'.$_POST['elBudget_12'].'" where kadri_id="'.$_GET['kadri_id'].'" and bud_commerce="бюджет"';
        //echo "<hr>".$query;
        if (mysql_query($query)) {echo "<br>Почасовка бюджетная по сотруднику: <b>".$fio."</b> успешно обновлена";}
        else {echo "<br><font color=red>Почасовка бюджетная не обновлена...</font>";}
    }

    //---------------------hours_type2
    if ($_POST['hours_type2']=="insert")
    {//echo "Добавление коммерческой почасовки";
        $insert_string="";$insert_action=false;
        for ($i=1;$i<13;$i++) //проверка на пустые значения
        { $name_elem="elComm_".$i; if ($_POST[$name_elem]!='') {$insert_action=true;break;} }


        if ($insert_action==true) {
            for ($i=1;$i<13;$i++) {$name_elem="elComm_".$i; $insert_string=$insert_string.', "'.$_POST[$name_elem].'"';}
            $query='insert into hours_year(kadri_id, year_id, m09, m10, m11, m12, m01, m02, m03, m04, m05, m06, m07, m08, bud_commerce)
				           values("'.$_GET['kadri_id'].'","'.$_POST['year_id'].'"'.$insert_string.',"контракт")';
            //echo "<hr>".$query;
            if (mysql_query($query)) {echo "<br>Почасовка коммерческая по сотруднику: <b>".$fio."</b> успешно добавлена";}
            else {echo "<br>Почасовка коммерческая не добавлена...";}
        }
        else {echo '<br><font color=red>Почасовка коммерческая  не добавлена...Не внесены необходимые данные .</font>';}

    }
    if ($_POST['hours_type2']=="update")
    {//echo "Изменение коммерческой почасовки";
        $query='update hours_year set year_id="'.$_POST['year_id'].'", m09="'.$_POST['elComm_1'].'",m10="'.$_POST['elComm_2'].'",
	                   m11="'.$_POST['elComm_3'].'",m12="'.$_POST['elComm_4'].'",m01="'.$_POST['elComm_5'].'",
	                   m02="'.$_POST['elComm_6'].'", m03="'.$_POST['elComm_7'].'",m04="'.$_POST['elComm_8'].'",
					   m05="'.$_POST['elComm_9'].'", m06="'.$_POST['elComm_10'].'",m07="'.$_POST['elComm_11'].'",
	                   m08="'.$_POST['elComm_12'].'" where kadri_id="'.$_GET['kadri_id'].'" and bud_commerce="контракт"';
        //echo "<hr>".$query;
        if (mysql_query($query)) {echo "<br>Почасовка коммерческая по сотруднику: <b>".$fio."</b> успешно обновлена";}
        else {echo "<br><font color=red>Почасовка коммерческая не обновлена...</font>";}
    }
}
//-----------------Почасовка------------------------------------------------------------

//-----------------Нагрузка по видам часов----------------------------------------------
// <abarmin date="12.07.2012">
// bug 0000102
if (array_key_exists("type_kind", $_POST)) {
    if ($_POST['type_kind']=="insert") {
        //echo "Добавление нагрузки";
        $insert_string="";
        $insert_action1=false;
        $insert_action2=false;
        for ($i=1;$i<=17;$i++) 	// проверка заполнения полей данными
        {
            $name_elem="kind_".$i;
            if (numLocal($_POST[$name_elem])!='' || numLocal($_POST[$name_elem.'_add'])!='')
            {
                $insert_action1=true;
                break;
            }
        }
        if ($_POST['year_id']==0 || $_POST['part_id']==0 || $_POST['subject_id']==0 || $_POST['spec_id']==0 ||
            $_POST['level_id']==0 /*|| $_POST['group_list']==0*/) {$insert_action2=false;}
        else {$insert_action2=true;}

        if ($insert_action1==true && $insert_action2==true) {
            $insert_fields='';
            for ($i=0;$i<sizeof($hour_kind_code);$i++) {
                $insert_fields.=', '.$hour_kind_code[$i].', '.$hour_kind_code[$i].'_add';
                //$name_elem="kind_".($i+1);

                //if (numLocal($_POST['kind_'.($i+1)],'sql')!='')
                $insert_string.=', '.getSqlNULL('"'.numLocal($_POST['kind_'.($i+1)],'sql').'"');
                //if (numLocal($_POST['kind_'.($i+1).'_add'],'sql')!='')
                $insert_string.=', '.getSqlNULL('"'.numLocal($_POST['kind_'.($i+1).'_add'],'sql').'"');
            }


            $query='insert into hours_kind(kadri_id ,year_id, part_id, subject_id, spec_id, level_id, group_id,
							hours_kind_type,on_filial,groups_cnt,comment,stud_cnt,stud_cnt_add'.
                $insert_fields.')
				           values("'.intval($_GET['kadri_id']).'",
					   "'.intval($_POST['year_id']).'",
					   "'.intval($_POST['part_id']).'",
					   "'.intval($_POST['subject_id']).'",
					   "'.intval($_POST['spec_id']).'",
					   "'.intval($_POST['level_id']).'",
					   "'.intval($_POST['group_list']).'",
					   "'.intval($_POST['hours_kind_type']).'",
					   "'.$filial_flag.'",
					   "'.intval($_POST['groups_cnt']).'",
					   "'.f_ri($_POST['comment']).'",
					   "'.intval($_POST['stud_cnt']).'",
					   "'.intval($_POST['stud_cnt_add']).'" '.$insert_string.')';
            //echo "<hr>".$query;
            if (mysql_query($query)) {
                echo "<div class=success>Нагрузка по сотруднику: <b>".$fio."</b> успешно добавлена</div>";
            } else {
                echo "<br><font color=red>Нагрузка  не добавлена...Возможно такая запись уже есть.</font>";
            }
        } else {
            echo '<br><font color=red>Нагрузка  не добавлена...';
            if (!$insert_action1) {echo ' Не внесены необходимые данные (все строки от лекции..посещение занятий пустые).';}
            if (!$insert_action2) {echo ' Не заполнены справочники. Требуется заполнить все списки выбора (год..группа)';}
            echo '</font>';
        }
    }

    if ($_POST['type_kind']=="update") {
        //echo "Изменение нагрузки";
        $query_update_str='';
        for ($i=0;$i<sizeof($hour_kind_code);$i++)
        {

            $query_update_str.=$hour_kind_code[$i].'='.getSqlNULL('"'.numLocal($_POST['kind_'.($i+1)],'sql').'"').', ';
            $query_update_str.=$hour_kind_code[$i].'_add='.getSqlNULL('"'.numLocal($_POST['kind_'.($i+1).'_add'],'sql').'"').', ';
        }
        //$query_update_str=substr($query_update_str,0,strlen($query_update_str)-2);

        $query='update hours_kind set '.$query_update_str.'
			year_id="'.intval($_POST['year_id']).'",
			part_id="'.intval($_POST['part_id']).'",
			subject_id="'.intval($_POST['subject_id']).'",
			spec_id="'.intval($_POST['spec_id']).'",
			level_id="'.intval($_POST['level_id']).'",
			group_id="'.intval($_POST['group_list']).'",
			hours_kind_type="'.intval($_POST['hours_kind_type']).'",
			groups_cnt="'.intval($_POST['groups_cnt']).'",
			comment="'.f_ri($_POST['comment']).'",
			stud_cnt="'.intval($_POST['stud_cnt']).'",
			stud_cnt_add="'.intval($_POST['stud_cnt_add']).'",
			on_filial="'.$filial_flag.'"
			 where id="'.intval($_GET['hours_id']).'" ';
        //echo '<hr>'.$query;
        if (mysql_query($query)) {echo "<div class=success>Нагрузка по сотруднику: <b>".$fio."</b> успешно обновлена.</div>";
            if ($_POST['goView']==1) {echo 'автопереход к списку через 2 сек.<script language="Javascript">setTimeout("window.location.href=\"'.'s_hours_view.php?kadri_id='.$_GET['kadri_id'].'&year='.$_POST['year_id'].$kind_type_defaults.'\"",100);</script>';}
        }
        else {echo "<br><font color=red>Нагрузка не обновлена...Возможно появление дубликата.</font>";}
    }
}
// </abarmin>
//----------------Нагрузка----------------------------------------------------------------------
?>

    <p><a href="_modules/_staff/index.php?action=edit&id=<?php echo $_GET['kadri_id']; ?>">Вернуться к анкете...</a><p>

    <table border="0" bordercolor="#F0F0F0" cellpadding="0" cellspacing="0" width="100%">
        <tr bgcolor="#E6E6FF">
            <!--td height=40 id="c1" onMouseOver="newColor('c1');" onMouseOut="backColor('c1');" onClick="Click_color('c1',3);" width="200">
              <div align="center" style="display:none;"><font size="3"><b><a href="javascript:show_hide('Layer1');">Почасовка</a> </b></font></div>
            </td-->
            <td height=40 id="tab_c2" onMouseOver="newColor(this.id);" onMouseOut="backColor(this.id);" onClick="Click_color(this.id,2);show_hide('LayerTab2');" width="200" align=center style="font-weight:<?php echo ($tab==2?"bold":"normal"); ?>;">
                <font size="3">Нагрузка</font>
            </td>
            <td  height=40 id="tab_c3" onMouseOver="newColor(this.id);" onMouseOut="backColor(this.id);" onClick="Click_color(this.id,2);show_hide('LayerTab3');" width="200" align=center style="font-weight:<?php echo ($tab==3?"bold":"normal"); ?>;">
                <font size="3">Свод нагрузки</font>
            </td>
            <!--td  height=40 id="tab_c4" onMouseOver="newColor(this.id);" onMouseOut="backColor(this.id);" onClick="Click_color(this.id,4);show_hide('LayerTab4');" width="200" align=center>
              <font size="3"><b>Свод нагрузки<br/>(бюджет)</b></font>
            </td>
            <td  height=40 id="tab_c5" onMouseOver="newColor(this.id);" onMouseOut="backColor(this.id);" onClick="Click_color(this.id,4);show_hide('LayerTab5');" width="200">
              <font size="3"><b>Свод нагрузки<br/>(коммерция)</b></font>
            </td-->
        </tr>
    </table>
    <table>
    <table name=tab1 cellpadding="0" cellspacing="10" bgcolor="#E6E6FF" width="100%">
        <tr><td width="275"></td><td>

            </td></tr>
        <tr><td width="275"></td>
            <td>
            </td></tr>
    </table>

    <!-- ---------------------Почасовка начало--------------------------------------	-->
    <!-- ---------------------------------------------------------------------------	-->
    <?php
    $query_all='SELECT * FROM hours_year
          where kadri_id='.$_GET['kadri_id'].' and bud_commerce="бюджет" limit 0,1';
    //echo $query_all;
    if ( $res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {
        $res_edit_1=mysql_fetch_array($res_all);$type1="update";}
    else {$type1="insert";}
    $query_all='SELECT * FROM hours_year
          where kadri_id='.$_GET['kadri_id'].' and bud_commerce="контракт" limit 0,1';
    if ( $res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {
        $res_edit_2=mysql_fetch_array($res_all);$type2="update";}
    else {$type2="insert";}

    ?>

    <div id="Layer1" style="display:<?php if (!isset($_GET['tab']) || $_GET['tab']!='1') {echo 'none';} ?>">
        <form name=orders_form action="s_hours.php?kadri_id=<?php echo $_GET['kadri_id']; ?>&tab=2" method="post">
            <table name=tab1 cellpadding="0" cellspacing="10" bgcolor="#E6E6FF" width="100%">
                <tr><td colspan=2 align="left" valign="bottom" height="27">
                        <?php if (isset($res_edit_1)) {echo '<a href="s_hours_subj.php?kadri_id='.$_GET['kadri_id'].'">Список закрепленных дисциплин </a>';}
                        else {echo 'список дисциплин недоступен (не введена почасовка)';} ?>
                    </td></tr>

                <tr valign="middle" height="44">
                    <td width=200>
                        <input type=hidden name=hours_type1 value="<?php echo $type1; ?>">
                        <input type=hidden name=hours_type2 value="<?php echo $type2; ?>">
                        <input type="submit" value="Изменить"></td>
                    <td width=200><input type="reset" value="Очистить"></td>
                    <td width=200><input type="button" value="Справка" onclick="javascript:help_msg();"></td>
                </tr>

                <tr><td colspan=2 align="center" valign="bottom" height="27"><b>Почасовка:</b></td></tr>
                <tr>
                    <td width="400" colspan="3">
                        <input name="elem10" type="hidden" value="<?php if (isset($res_edit)) {echo $res_edit['id'];} echo '" '.$disabled_val; ?>>
    </td>
  </tr>
  <tr><td> год </td><td colspan=2>	<select name="year_id" style="width:300;">
                        <option value="0">...выберите год ...</option>
                        <?php
                        $query='select id,name from time_intervals order by name';
                        $res=mysql_query($query);
                        while ($a=mysql_fetch_array($res)) 	{
                            $select_val='';
                            if (isset($res_edit_1)) { if ($res_edit_1['year_id']==$a['id']) {$select_val=' selected';} }
                            else {
                                if (isset($def_settings)) { if ($def_settings['year_id']==$a['id']) {$select_val=' selected';}}
                            }
                            echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
                        }
                        ?>
                        </select></td>
                </tr>
                <tr>
                    <td width="275"> Тип почасовки (бюджет\контракт)</td>
                    <td width="">
                        <?php if (isset($res_edit_1)) {echo '<div style="background-color:#3399FF; font-weight:bold"> бюджет </div>';} else {echo 'бюджет';}?>
                    </td>
                    <td width="">
                        <?php if (isset($res_edit_2)) {echo '<div style="background-color:#3399FF; font-weight:bold"> контракт </div>';} else {echo 'контракт';}?>
                    </td>
                </tr>
                <?php
                $j=9;$str='';
                $bud_sum=0;$comm_sum=0;
                for ($i=9;$i<sizeof($months)+9;$i++)
                {
                    if ($i>12) {$j=$i-12;} else {$j=$i;}
                    echo '
		  <tr>
		    <td width=""> '.$months[$i].'</td>
		    <td width="">
		      <input name="elBudget_'.($i-8).'" type=text value="';
                    $sumRow=0;
                    if (isset($res_edit_1))
                    {
                        if ($j<10) {$str='m0'.$j;} else {$str='m'.$j;}
                        echo $res_edit_1[$str];$bud_sum+=$res_edit_1[$str];
                        //eval("\$str = \"$str\";");
                        $sumRow+=$res_edit_1[$str];
                        //echo $str;
                    }
                    echo '" size=20 class="tab_view" >    </td>
			  <td width="">
		      <input name="elComm_'.($i-8).'" type=text value="';

                    if (isset($res_edit_2))
                    {
                        if ($j<10) {$str='m0'.$j;} else {$str='m'.$j;}
                        echo $res_edit_2[$str];$comm_sum+=$res_edit_2[$str];
                        $sumRow+=$res_edit_2[$str];
                        //eval("\$str = \"$str\";");
                        //echo $str;
                    }
                    echo '" size=20 class="tab_view" >&nbsp;&nbsp;&nbsp;'.$sumRow.'    </td>';

                    echo '</tr>';

                }
                echo '<tr style="font-weight:bold;">
  	<td>Итого часов: '.($bud_sum+$comm_sum).' </td><td>бюджет: '.$bud_sum.'</td><td>контракт: '.$comm_sum.'</td></tr>';
                ?>

                <tr valign="middle" height="44">
                    <td width=200>	<input type="submit" value="Изменить"></td>
                    <td width=200><input type="reset" value="Очистить"></td>
                    <td width=200><input type="button" value="Справка" onclick="javascript:help_msg();"></td>
                </tr>
            </table></form></div>
    <!-- ---------------------Почасовка конец--------------------------------------	-->
    <!-- --------------------------------------------------------------------------	-->

    <!-- ---------------------Нагрузка начало--------------------------------------	-->
    <!-- --------------------------------------------------------------------------	-->

    <div id="LayerTab2" style="display:<?php if (!isset($_GET['tab']) || $_GET['tab']!='2') {echo 'none';} ?>">
        <?php
        // <abarmin date="12.07.2012">
        // bug 0000102
        $kadri_id = null;
        $hours_id = null;
        if (array_key_exists("kadri_id", $_GET)) {
            $kadri_id = $_GET['kadri_id'];
        }
        if (array_key_exists("hours_id", $_GET)) {
            $hours_id = $_GET['hours_id'];
        }
        ?>
        <form name=hours_kind_form action="s_hours.php?kadri_id=<?php echo $kadri_id.'&hours_id='.$hours_id; ?>&tab=2" method="post">
            <input type=hidden name="goView" id="goView" value="">
            <?php

            $query_all='SELECT * FROM hours_kind where id="'.$hours_id.'" limit 0,1';
            // </abarmin>
            //echo $query_all;
            if ( $res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {
                $res_edit=mysql_fetch_array($res_all);$type_kind="update";}
            else {$type_kind="insert";}
            //$type_kind="insert";
            ?>

            <table border=0 name=tabLayer2 cellpadding="0" cellspacing="10" bgcolor="#E6E6FF" width="100%">
                <tr>
                    <td>Сотрудник: </td>
                    <td
                    <input name="elem0" type="hidden" value="<?php if ($_GET['kadri_id']) echo trim($_GET['kadri_id']); ?>">
                    <?php
                    if (!isset($_GET['save']) && !isset($_GET['print'])) {
                        echo '<select name="teach_name" style="width:300;" onChange="javascript:window.location.href=\'?tab=2&kadri_id=\'+this.options[this.selectedIndex].value;"> ';
                        if ($_SESSION['task_rights_id']==4) {
                            $query="select k.id,k.fio as name
		 	from kadri k left join kadri_in_ptypes kpt on k.id = kpt.kadri_id left join person_types pt on pt.id=kpt.person_type_id where pt.name_short like '%ППС%'
			order by k.fio_short";
                        }
                        else {	//для преподавателя только просмотр своих публикаций
                            $query='select k.id,k.fio
		 	from kadri k left join kadri_in_ptypes kpt on k.id = kpt.kadri_id left join person_types pt on pt.id=kpt.person_type_id
			where pt.name_short like \'%ППС%\' and k.id="'.trim($_GET['kadri_id']).'" order by k.fio ';};
                        echo getFrom_ListItemValue($query,'id','name','kadri_id');
                        echo '</select>';
                    }
                    else { echo $fio;}
                    ?>
                    </td>
                </tr>
                <tr><td colspan=2 align="left" valign="bottom" height="27">
                        <a id=view_hours_form href="#?" onClick="javascript:document.location.href='s_hours_view.php?kadri_id=<?php echo $_GET['kadri_id']; ?>'+'&year='+document.hours_kind_form.year_id.value+'<?php echo $kind_type_defaults;?>'"> Просмотреть нагрузку </a> </td></tr>
                <tr valign="middle" height="44">
                    <td width=400>
                        <input type=hidden name="type_kind" value="<?php echo $type_kind; ?>">
                        <input type="button" value="Изменить" onClick="javascript:test_hours_kind_form();">
                        <input type="button" value="Изменить и просмотреть" onclick="javascript:test_hours_kind_form_and_view();">
                    </td>

                    <td width=*><input type="reset" value="Очистить"></td>
                    <td><input type="button" value="Справка" onclick="javascript:help_msg();"></td>
                </tr>
                <tr><td colspan=2 align="center" valign="bottom" height="27"><b>Нагрузка:</b></td></tr>

                <?php
                //вывод списка дисциплин, спец-й, курсов, групп
                //выделение в списке при обновлении данных
                echo '
	<tr><td> год * <span class=text>(по умолчанию текущий)</span></td><td>
	<select name="year_id" id="year_id" style="width:300;">';
                $listQuery='select id,name from time_intervals order by name desc';
                if (!isset($_POST['year_id']) && !isset($res_edit) && isset($def_settings))
                    $_POST['year_id']=intval($def_settings['year_id']);

                echo getFrom_ListItemValue($listQuery,'id','name','year_id');

                echo '	</select></td></tr>';

                echo '
	<tr><td> семестр * <span class=text>(по умолчанию текущий)</span></td><td>	<select name="part_id" style="width:300;">';
                $listQuery='select id,name from time_parts order by name desc';
                if (!isset($_POST['part_id']) && !isset($res_edit) && isset($def_settings))
                    $_POST['part_id']=intval($def_settings['part_id']);

                echo getFrom_ListItemValue($listQuery,'id','name','part_id');

                echo '	</select></td></tr>';

                echo '
	<tr><td> дисциплина *	</td><td>	<select name="subject_id" style="width:300;">';
                $listQuery='select id,name from subjects order by name';
                echo getFrom_ListItemValue($listQuery,'id','name','subject_id');
                echo '	</select></td></tr>';
                $select_val='';
                echo '
	<tr><td> специальность *</td><td>	<select name="spec_id" id="spec_id" style="width:300;">';
                $listQuery='select id,name from specialities order by name';
                echo getFrom_ListItemValue($listQuery,'id','name','spec_id');

                echo '	</select></td></tr>';
                echo '
	<tr><td> курс *	</td><td>	<select name="level_id" style="width:300;">';
                $listQuery='select id,name from levels order by name';
                echo getFrom_ListItemValue($listQuery,'id','name','level_id');

                echo '	</select></td></tr>';

                //------------------------------------------------------------------------------
                ?>
                <tr><td> тип нагрузки</td><td>	<select name="hours_kind_type" style="width:300;">
                            <?php
                            $listQuery='select id,name from hours_kind_type order by id';
                            echo getFrom_ListItemValue($listQuery,'id','name','hours_kind_type');
                            ?>
                        </select><br>
                        <label><input type=checkbox name="filial_flag" <?php if (isset($res_edit) && $res_edit['on_filial']==1) {echo ' checked';} ?>>
                            <font color=red>с учетом выезда</font></label>
                    </td></tr>
                <tr><td> число групп</td>
                    <td><input type=text maxlength="3" value="<?php if (isset($res_edit)) {echo $res_edit['groups_cnt'];} ?>" name="groups_cnt" style="width:40;text-align:right;">
                    </td></tr>
                <tr><td> число студентов</td>
                    <td class=text>
                        <input type=text maxlength="3" value="<?php if (isset($res_edit)) {echo numLocal($res_edit['stud_cnt'],'browse');} ?>" name="stud_cnt" style="width:40;text-align:right;" id="stud_cnt" onChange="javascript:calc_sum('stud_cnt');"> бюджет
                        <input type=text maxlength="3" value="<?php if (isset($res_edit)) {echo numLocal($res_edit['stud_cnt_add'],'browse');} ?>" name="stud_cnt_add" style="width:40;text-align:right;" id="stud_cnt_add" onChange="javascript:calc_sum('stud_cnt');"> коммерция
		<span style="padding-left:20;">
			всего: <b>
			<span name="stud_cnt_sum">
			<?php echo numLocal($res_edit['stud_cnt']+$res_edit['stud_cnt_add'],'browse'); ?>
			</span>
            </b>
		</span>
                    </td></tr>
                <tr><td> комментарий</td>
                    <td><textarea name="comment" cols=40 rows=5><?php if (isset($res_edit)) {echo $res_edit['comment'];} ?></textarea>
                    </td></tr>
                <?php
                //------------------------------------------------------------------------------
                echo '<tr><td colspan=2><hr width="450" align="left"></td></tr>';
                echo '<tr><td></td><td class=text>бюджет
<span style="padding-left:50;">коммерция</span>
<span style="padding-left:50;">итоговая</span></td></tr>';
                // вывод формы для правки типов нагрузки (лекции, практики, ...)
                $res_edit_sum=0;$res_edit_el_sum=0;$res_edit_el_add_sum=0;
                for ($i=0;$i<sizeof($hour_kind_name);$i++)
                {
                    $res_edit_el='';
                    if (isset($res_edit)) {
                        $res_edit_el= $res_edit[$hour_kind_code[$i]];
                        $res_edit_el_add= $res_edit[$hour_kind_code[$i].'_add'];

                        $res_edit_el_sum+=$res_edit_el;
                        $res_edit_el_add_sum+=$res_edit_el_add;
                        $res_edit_sum+=$res_edit_el+$res_edit_el_add;
                    } else {
                        // <abarmin date="12.07.2012">
                        // bug 0000102
                        $res_edit_el_add = null;
                        // </abarmin>
                    }
                    echo '
  <tr>
    <td width="275"> '.$hour_kind_name[$i].'</td>
    <td width="472">
      <input name="kind_'.($i+1).'" title="бюджет" type=text value="'.numLocal($res_edit_el,'browse').'" size=10 class="numb"  onChange="javascript:calc_sum(\'kind_'.($i+1).'\');"> &nbsp;
      <input name="kind_'.($i+1).'_add" title="коммерция" type=text value="'.numLocal($res_edit_el_add,'browse').'" size=10 class="numb" onChange="javascript:calc_sum(\'kind_'.($i+1).'\');"> &nbsp;
      <span name="kind_'.($i+1).'_sum" title="итоговая" class="text" disabled style="padding-left:40;text-align=right";>
      '.numLocal(number_format($res_edit_el+$res_edit_el_add,1,',',''),'browse').'</span>
    </td>
  </tr>';

                }

                ?>
                <tr>
                    <td></td>
                    <td>
                        <span id="kind_sum_total" style="padding-left:50;"><?php echo numLocal(number_format($res_edit_el_sum,1,',','')); ?></span>
                        <span id="kind_sum_total" style="padding-left:70;text-align:right;"><?php echo numLocal(number_format($res_edit_el_add_sum,1,',','')); ?></span>
                        <span id="kind_sum_total" style="padding-left:40;text-align:right;"><?php echo numLocal(number_format($res_edit_sum,1,',','')); ?></span>

                    </td>
                </tr>
                <tr valign="middle" height="44">
                    <td>	<input type="button" value="Изменить" onClick="javascript:test_hours_kind_form();">&nbsp;
                        <input type="button" value="Изменить и просмотреть" onclick="javascript:test_hours_kind_form_and_view();"></td>
                    <td><input type="reset" value="Очистить">
                    </td>
                    <td><input type="button" value="Справка" onclick="javascript:help_msg();"></td>
                </tr>
            </table></form></div>
    <!-- ---------------------Нагрузка конец--------------------------------------	-->

    <!-- ---------------------Свод начало----------------------------------------	-->
    <div id="LayerTab3" style="display:<?php if (!isset($_GET['tab']) || $_GET['tab']!='3') {echo 'none';} ?>">
    <form>
    <table name=tab1 cellpadding="2" cellspacing="0" bgcolor="#E6E6FF" width="100%" border="1">
    <tr height="50" >
        <td colspan=15 align="LEFT" valign="center" height="27">

            <b>Свод нагрузки</b> за
            <?php
            if (!isset($_GET['save']) && !isset($_GET['print']))
            {	?>
                <select name="year_id" style="width:300;" onChange="javascript:window.location.href='?kadri_id=<?php echo $_GET['kadri_id']; ?>&tab=3&year='+this.options[this.selectedIndex].value;">
                    <option value="0">...выберите год ...</option>
                    <?php
                    $query='select id,name from time_intervals order by name desc';
                    $res=mysql_query($query);
                    while ($a=mysql_fetch_array($res)) 	{
                        $select_val='';
                        if (isset($_GET['year'])) { if ($_GET['year']==$a['id']) {$select_val=' selected';} }
                        else {
                            if (isset($def_settings)) { if ($def_settings['year_id']==$a['id']) {$select_val=' selected';}}
                        }
                        echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
                    }
                    ?>
                </select>
            <?php } else { ?>

                <?php
                echo getScalarVal('select name from time_intervals where id='.intval($_GET['year']));
            }?>
            год &nbsp;
            <?php

            if (!isset($_GET['save']) && !isset($_GET['print']))
            { echo '<a href="?'.$query_string.'&print">печать</a>'; }	else { echo '<a href="?'.reset_param_name($query_string,'print').'">просмотр</a>';}
            ?>
            <span style="padding-left:50;white-space:nowrap;">
		<?php	// показывать по умолчанию сводную статистику
                $main_cat=true;
                if (isset($_GET['main_cat']) && $_GET['main_cat']==0) $main_cat=false;

                $add_cat=true;
                if (isset($_GET['add_cat']) && $_GET['add_cat']==0) $add_cat=false;


                ?>
                <label><input type=checkbox id=main_cat onclick="setCat(this);" <?php echo ($main_cat?"checked":"") ?> >бюджет&nbsp;</label>&nbsp;
		<label><input type=checkbox id=add_cat onclick="setCat(this);" <?php echo ($add_cat?"checked":"") ?> >коммерция&nbsp;</label>

	<span style="padding-left:20; font-weight:bold;"> итого:&nbsp;<?php
        $sumFields=($main_cat?'sum+ ':'').($add_cat?'sum_add+ ':'');
        $sumFields=preg_replace("/\+ $/",'',$sumFields);

        $query='select sum('.$sumFields.') FROM hours_kind WHERE year_id = "'.$year_id_all.'"';

        echo numLocal(getScalarVal($query)); ?></span>
	</span>
        </td></tr>
    <tr class=title height="30" align=center>
        <?php
        if (!isset($_GET['save']) && !isset($_GET['print'])) { ?>
            <td>Правка</td>
        <?php } ?>
        <td width=20>№ п\п</td>
        <td>ФИО преподавателя</td>
        <td>долж.</td>
        <td>ставка факт <a href="#ratedetail" class=help title="справка по показателю">?</a></td>
        <td>ставка план<a href="#rateplan" class=help title="справка по показателю">?</a></td>
        <td>число групп</td>
        <td>число студ.</td>
        <td>лек.</td>
        <td>дип.</td>
        <td>осн.</td>
        <td>доп.</td>
        <td>надбавка</td>
        <td>почасовка</td>
        <td>всего часов в году</td>
    </tr>
    <?php
    $query='SELECT "'.$year_id_all.'" as year_id,k.id as kadri_id,k.fio,k.fio_short,d.name_short as dolgnost
	from kadri k
	left join kadri_in_ptypes kpt on k.id = kpt.kadri_id
	left join person_types pt on pt.id=kpt.person_type_id
	left join dolgnost d on d.id=k.dolgnost
where pt.name_short like "%ППС%"
	and k.id not in (select kadri_id from hours_kind where year_id="'.$year_id_all.' and (sum+sum_add)=0" )
order by k.fio_short';
    //echo $query;
    $res=mysql_query($query);	// у кого нет нагрузки в указанному году и он ППС

    //текущая дата для расчета ставки по актуальным приказам ОК
    $date_from=date('Y.m.d',mktime(0,0,0, date("m"),date("d"),  date("Y"))  );
    $query_orders_null='SELECT round(sum(rate),2) as rate_sum,count(id) as ord_cnt FROM `orders`
WHERE concat(substring(date_end,7,4),".",substring(date_end,4,2),".",substring(date_end,1,2))>="'.$date_from.'" ';
    // сводная таблица по нагрузке
    $i = 1;
    while ($a=mysql_fetch_array($res)) 	{
        // <abarmin date="12.07.2012">
        // bug 0000102
        if (array_key_exists("id", $a)) {
            $res_orders=mysql_query($query_orders_null.' and kadri_id="'.$a['id'].'"');
        } else {
            $res_orders=mysql_query($query_orders_null.' and kadri_id = null');
        }
        // </abarmin>
        $a_orders=mysql_fetch_array($res_orders,MYSQL_ASSOC);

        echo '<tr class=text height="20" >';

        if (!isset($_GET['save']) && !isset($_GET['print']))
        {
            echo '<td align=center>
	<a href="s_hours_view.php?kadri_id='.$a['kadri_id'].'&year='.$a['year_id'].$kind_type_defaults.'" title="Просмотреть">
				<img src="images/toopen.png" alt="Просмотреть" border="0"></a> &nbsp;
	<a href="s_hours.php?kadri_id='.$a['kadri_id'].'&tab=2" title="Добавить">
				<img src="images/new_elem.gif" alt="Добавить" border="0"></a>
				</td>';
        }
        echo '<td>&nbsp;'.$i.'</td><td>&nbsp;<a href="#fullname" title="'.$a['fio'].'" style="color:#ff0000;">'.$a['fio_short'].'</a><a href="#redmark" class=help title="справка по показателю">?</a></td>
	<td>&nbsp;'.$a['dolgnost'].'</td>
	<td>&nbsp;</td>
	<td>&nbsp;'.numLocal($a_orders['rate_sum']).'<sup>'.numLocal($a_orders['ord_cnt']).'</td>
	<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
	<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
	</tr>';
        $i++;
    }

    //------------------------------------- у кого есть нагрузка даже если не ППС----------------------------------------

    //------использовался ранее, до выделения разных типов нагрузки--------------------
    /*
    $query='SELECT kadri.id,year_id,fio,fio_short,kadri_id,sum(stud_cnt) as stud_cnt_sum,sum(groups_cnt)as groups_cnt_sum,
    sum(lects) as lects_sum,sum(consult_dipl ) as dipl_sum,
    "0" as hours_sum1,"0"  as hours_sum2,"0" as hours_sum3,"0" as hours_sum4,sum(lects+practs+labor+rgr +recenz+kurs_proj +consult +test+exams+study_pract +work_pract +consult_dipl +gek +aspirants +aspir_manage+duty) as hours_sum

     FROM hours_kind
    right join kadri on kadri.id=hours_kind.kadri_id
    where year_id="'.$year_id_all.'"
    group by kadri.id,hours_kind_type  order by kadri.fio_short';
    */
    //---------------------------------------------------------------------------------
    // <abarmin date="12.07.2012">
    // bug 0000102
    /*
    $gArr=array();	// выражения запроса с учетом категории средств (основные, доп.)
    $gArr['lects']=($main_cat?'lects+ ':'').($add_cat?'lects_add+ ':'');
    $gArr['dipl']=($main_cat?'consult_dipl+ ':'').($add_cat?'consult_dipl_add+ ':'');
    $gArr['sum']=($main_cat?'sum+ ':'').($add_cat?'sum_add+ ':'');
    $gArr['stud']=($main_cat?'stud_cnt+ ':'').($add_cat?'stud_cnt_add+ ':'');

    foreach ($gArr as &$value) {
    $value = preg_replace("/\+ $/",'',$value);	// удаляем последнюю знак для корректности
    }

    $query='
    SELECT kadri.id,year_id,fio,fio_short,kadri_id,d.name_short as dolgnost,hr.rate,sum(stud_cnt_sum) as stud_cnt_sum_,sum(groups_cnt_sum)as groups_cnt_sum_,
    sum(lects_sum) as lects_sum_,sum(dipl_sum) as dipl_sum_,
    sum(hours_sum1) as hours_sum1_,sum(hours_sum2) as hours_sum2_,sum(hours_sum3) as hours_sum3_,sum(hours_sum4) as hours_sum4_,
    sum(hours_sum1+hours_sum2+hours_sum3+hours_sum4) as hours_sum
    from
    (
    SELECT year_id,kadri_id,sum('.$gArr['stud'].') as stud_cnt_sum,sum(groups_cnt)as groups_cnt_sum,
    sum('.$gArr['lects'].') as lects_sum,sum('.$gArr['dipl'].') as dipl_sum,
    sum('.$gArr['sum'].') as hours_sum1,"0"  as hours_sum2,"0" as hours_sum3,"0" as hours_sum4
    FROM hours_kind
    where year_id="'.$year_id_all.'" and hours_kind_type=1
    group by kadri_id
    union
    SELECT year_id,kadri_id,sum('.$gArr['stud'].') as stud_cnt_sum,sum(groups_cnt)as groups_cnt_sum,
    sum('.$gArr['lects'].') as lects_sum,sum('.$gArr['dipl'].') as dipl_sum,
    "0" as hours_sum1,sum('.$gArr['sum'].')  as hours_sum2,"0" as hours_sum3,"0" as hours_sum4
    FROM hours_kind
    where year_id="'.$year_id_all.'" and hours_kind_type=2
    group by kadri_id
    union
    SELECT year_id,kadri_id,sum('.$gArr['stud'].') as stud_cnt_sum,sum(groups_cnt)as groups_cnt_sum,
    sum('.$gArr['lects'].') as lects_sum,sum('.$gArr['dipl'].') as dipl_sum,
    "0" as hours_sum1,"0"  as hours_sum2,sum('.$gArr['sum'].') as hours_sum3,"0" as hours_sum4
    FROM hours_kind
    where year_id="'.$year_id_all.'" and hours_kind_type=3
    group by kadri_id
    union
    SELECT year_id,kadri_id,sum('.$gArr['stud'].') as stud_cnt_sum,sum(groups_cnt)as groups_cnt_sum,
    sum('.$gArr['lects'].') as lects_sum,sum('.$gArr['dipl'].') as dipl_sum,
    "0" as hours_sum1,"0"  as hours_sum2,"0" as hours_sum3,sum('.$gArr['sum'].') as hours_sum4
    FROM hours_kind
    where year_id="'.$year_id_all.'" and hours_kind_type=4
    group by kadri_id
    )T
    right join kadri on kadri.id=kadri_id left join dolgnost d on d.id=kadri.dolgnost left join hours_rate hr on hr.dolgnost_id=kadri.dolgnost
    group by kadri.id having sum(hours_sum1+hours_sum2+hours_sum3+hours_sum4)>0 order by kadri.fio_short
    ';
    */
    // запрос переписал без union-ов, в них где-то ошибка
    // да и работают они совсем неочевидно

    $gArr=array();	// выражения запроса с учетом категории средств (основные, доп.)
    if ($main_cat) {
        $gArr['lects'][] = "ifnull(hours.lects, 0)";
        $gArr['dipl'][] = "ifnull(hours.consult_dipl, 0)";
        $gArr['sum'][] = "ifnull(hours.practs, 0)";
        $gArr['sum'][] = "ifnull(hours.lects, 0)";
        $gArr['sum'][] = "ifnull(hours.labor, 0)";
        $gArr['sum'][] = "ifnull(hours.rgr, 0)";
        $gArr['sum'][] = "ifnull(hours.ksr, 0)";
        $gArr['sum'][] = "ifnull(hours.recenz, 0)";
        $gArr['sum'][] = "ifnull(hours.kurs_proj, 0)";
        $gArr['sum'][] = "ifnull(hours.consult, 0)";
        $gArr['sum'][] = "ifnull(hours.test, 0)";
        $gArr['sum'][] = "ifnull(hours.exams, 0)";
        $gArr['sum'][] = "ifnull(hours.study_pract, 0)";
        $gArr['sum'][] = "ifnull(hours.work_pract, 0)";
        $gArr['sum'][] = "ifnull(hours.consult_dipl, 0)";
        $gArr['sum'][] = "ifnull(hours.gek, 0)";
        $gArr['sum'][] = "ifnull(hours.aspirants, 0)";
        $gArr['sum'][] = "ifnull(hours.aspir_manage, 0)";
        $gArr['sum'][] = "ifnull(hours.duty, 0)";
        $gArr['stud'][] = "ifnull(hours.stud_cnt, 0)";
    }
    if ($add_cat) {
        $gArr['lects'][] = "ifnull(hours.lects_add, 0)";
        $gArr['dipl'][] = "ifnull(hours.consult_dipl_add, 0)";
        $gArr['sum'][] = "ifnull(hours.practs_add, 0)";
        $gArr['sum'][] = "ifnull(hours.lects_add, 0)";
        $gArr['sum'][] = "ifnull(hours.labor_add, 0)";
        $gArr['sum'][] = "ifnull(hours.rgr_add, 0)";
        $gArr['sum'][] = "ifnull(hours.ksr_add, 0)";
        $gArr['sum'][] = "ifnull(hours.recenz_add, 0)";
        $gArr['sum'][] = "ifnull(hours.kurs_proj_add, 0)";
        $gArr['sum'][] = "ifnull(hours.consult_add, 0)";
        $gArr['sum'][] = "ifnull(hours.test_add, 0)";
        $gArr['sum'][] = "ifnull(hours.exams_add, 0)";
        $gArr['sum'][] = "ifnull(hours.study_pract_add, 0)";
        $gArr['sum'][] = "ifnull(hours.work_pract_add, 0)";
        $gArr['sum'][] = "ifnull(hours.consult_dipl_add, 0)";
        $gArr['sum'][] = "ifnull(hours.gek_add, 0)";
        $gArr['sum'][] = "ifnull(hours.aspirants_add, 0)";
        $gArr['sum'][] = "ifnull(hours.aspir_manage_add, 0)";
        $gArr['sum'][] = "ifnull(hours.duty_add, 0)";
        $gArr['stud'][] = "ifnull(hours.stud_cnt_add, 0)";
    }

    $query = "
select
	kadri.id as kadri_id,
	hours.year_id as year_id,
	kadri.fio as fio,
	kadri.fio_short,
	dolgnost.name_short as dolgnost,
	hr.rate,

	sum(hours.groups_cnt) as groups_cnt_sum_,
	sum(".implode("+", $gArr["stud"]).") as stud_cnt_sum_,
	sum(".implode("+", $gArr["lects"]).") as lects_sum_,
	sum(".implode("+", $gArr["dipl"]).") as dipl_sum_,

	sum(case when hours.hours_kind_type = 1 then (".implode("+", $gArr["sum"]).") else 0 end) as hours_sum1_,
	sum(case when hours.hours_kind_type = 2 then (".implode("+", $gArr["sum"]).") else 0 end) as hours_sum2_,
	sum(case when hours.hours_kind_type = 3 then (".implode("+", $gArr["sum"]).") else 0 end) as hours_sum3_,
	sum(case when hours.hours_kind_type = 4 then (".implode("+", $gArr["sum"]).") else 0 end) as hours_sum4_,

	sum(".implode("+", $gArr["sum"]).") as hours_sum
from
	kadri as kadri

left join
	hours_kind as hours
		on
			hours.kadri_id = kadri.id

left join
	dolgnost
		on
			dolgnost.id = kadri.dolgnost

left join
	hours_rate as hr
		on
			hr.dolgnost_id=kadri.dolgnost

where
	hours.year_id = $year_id_all

group by
	kadri.id

order by
	kadri.fio_short asc
";
    // мда, запрос на выходе получился длинне. Но очевиднее, если кто заметил
    //echo $query;
    // </abarmin>

    $query_orders='SELECT round(sum(rate),2) as rate_sum,count(id) as ord_cnt FROM `orders`
WHERE concat(substring(date_end,7,4),".",substring(date_end,4,2),".",substring(date_end,1,2))>="'.$date_from.'" ';
    //kadri_id
    $res=mysql_query($query);
    //echo $query_orders;
    
    $groupsCntTotal = 0;
    $studCntTotal = 0;
    $lectsTotal = 0;
    $diplTotal = 0;
    $mainTotal = 0;
    $additionalTotal = 0;
    $premiumTotal = 0;
    $byTimeTotal = 0;
    $sumTotal = 0;
    
    while ($a=mysql_fetch_array($res))
    {$mark='';

        // <abarmin date="2012.08.12">
        // $res_orders=mysql_query($query_orders.' and kadri_id="'.$a['id'].'"');
        $res_orders=mysql_query($query_orders.' and kadri_id="'.$a['kadri_id'].'"');
        // </abarmin>
        $a_orders=mysql_fetch_array($res_orders,MYSQL_ASSOC);

        //$rate_orders=getScalarVal($query_orders.' and kadri_id="'.$a['id'].'"');

        if ($a['hours_sum']<=0) {$mark='style="color:#ff0000;"';}
        echo '<tr class=text height="20" '.$mark.'>';
        if (!isset($_GET['save']) && !isset($_GET['print']))
        {

            echo'<td align=center>
	<a href="s_hours_view.php?kadri_id='.$a['kadri_id'].'&year='.$a['year_id'].$kind_type_defaults.'" title="Просмотреть">
				<img src="images/toopen.png" alt="Просмотреть" border="0"></a> &nbsp;
	<a href="s_hours.php?kadri_id='.$a['kadri_id'].'&tab=2" title="Добавить">
				<img src="images/new_elem.gif" alt="Добавить" border="0"></a>
				</td>';
        }

        echo '<td>&nbsp;'.$i.'</td>
	<td>&nbsp;<a href="#fullname" title="'.$a['fio'].'" '.$mark.'>'.$a['fio_short'].'</a></td>
	<td>&nbsp;'.$a['dolgnost'].'</td>';
        // <abarmin date="12.07.2012">
        // bug 0000102
        if ($a['rate'] != 0) {
            echo '<td class=numb>&nbsp;'.numLocal(round($a['hours_sum']/$a['rate'],2)).'</td>';
        } else {
            echo '<td class=numb>&nbsp;</td>';
        }
        // </abarmin>
        echo '
	<td class=numb>&nbsp;'.numLocal($a_orders['rate_sum']).'<sup>'.numLocal($a_orders['ord_cnt']).'</sup></td>
	<td class=numb>&nbsp;'.numLocal($a['groups_cnt_sum_']).'</td>
	<td class=numb>&nbsp;'.numLocal($a['stud_cnt_sum_']).'</td>
	<td class=numb>&nbsp;'.numLocal($a['lects_sum_']).'</td>
	<td class=numb>&nbsp;'.numLocal($a['dipl_sum_']).'</td>
	<td class=numb>&nbsp;'.numLocal(number_format($a['hours_sum1_'],1,',','')).'</td>
	<td class=numb>&nbsp;'.numLocal(number_format($a['hours_sum2_'],1,',','')).'</td>
	<td class=numb>&nbsp;'.numLocal(number_format($a['hours_sum3_'],1,',','')).'</td>
	<td class=numb>&nbsp;'.numLocal(number_format($a['hours_sum4_'],1,',','')).'</td>
	<td class=numb>&nbsp;'.numLocal(number_format($a['hours_sum'],1,',','')).'</td></tr>';
        $groupsCntTotal += $a['groups_cnt_sum_'];
        $studCntTotal += $a['stud_cnt_sum_'];
        $lectsTotal += $a['lects_sum_'];
        $diplTotal += $a['dipl_sum_'];
        $mainTotal += $a['hours_sum1_'];
        $additionalTotal += $a['hours_sum2_'];
        $premiumTotal += $a['hours_sum3_'];
        $byTimeTotal += $a['hours_sum4_'];
        $sumTotal += $a['hours_sum'];
        $i++;
    }
    echo'<td>&nbsp;</td>
    <td>&nbsp;</td>
	<td><b>Итого</b></td>
	<td>&nbsp;</td>
    <td>&nbsp;</td>
	<td class=numb>&nbsp;</td>
	<td class=numb>&nbsp;<b>'.$groupsCntTotal.'</b></td>
	<td class=numb>&nbsp;<b>'.$studCntTotal.'</b></td>
	<td class=numb>&nbsp;<b>'.$lectsTotal.'</b></td>
	<td class=numb>&nbsp;<b>'.$diplTotal.'</b></td>
	<td class=numb>&nbsp;<b>'.numLocal(number_format($mainTotal,1,',','')).'</b></td>
	<td class=numb>&nbsp;<b>'.numLocal(number_format($additionalTotal,1,',','')).'</b></td>
	<td class=numb>&nbsp;<b>'.numLocal(number_format($premiumTotal,1,',','')).'</b></td>
	<td class=numb>&nbsp;<b>'.numLocal(number_format($byTimeTotal,1,',','')).'</b></td>
	<td class=numb>&nbsp;<b>'.numLocal(number_format($sumTotal,1,',','')).'</b></td></tr>';
    ?>
    </table>
    </form>
    <div class=text><b>Примечание:</b> <a href="#top">перейти наверх</a><br>
        <ul>
            <li><a name="redmark"></a><span style="color:#ff0000;font-weight:bold;"> Красным </span> отмечаны сотрудники ППС, у которых нет нагрузки (=0) в выбранном учебном году</li>
            <li><a name="ratedetail"></a><b>ставка фактическая</b> рассчитывается путем деления (Всего часов в году) на (Плановые часы по указанной должности ППС)  и округления до 2-х знаков после запятой </li>
            <li><a name="rateplan"></a><b>ставка плановая</b> рассчитывается путем суммирования ставок по всем <u>действующим</u> (дата окончания приказа не истекла) приказам сотрудника. <br/>Дополнительно <sup>верхним индексом</sup> указывается число расчетных для ставки приказов</li>
        </ul>
    </div>
    </div>

    <p><a href="_modules/_staff/index.php?action=edit&id=<?php echo $_GET['kadri_id']; ?>">Вернуться к анкете...</a><p>
    <p><a href="p_administration.php">К списку задач.</a></p>


<?php include('footer.php'); ?>