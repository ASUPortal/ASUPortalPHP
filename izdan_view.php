<?php
include ('authorisation.php');

if ($view_all_mode!==true && (trim($_GET['kadri_id'])!=trim($_SESSION['kadri_id']) )	)  {
    header('Location:?kadri_id='.$_SESSION['kadri_id'].'');
}
include ('master_page_short.php');
?>
<link rel="stylesheet" type="text/css" href="css/print.css" media="print">

    <?php if (isset($_GET['save']) || isset($_GET['print'])) {
	?>
<style>
body {
	FONT: 11px/15px Tahoma, Arial, sans-serif; VERTICAL-ALIGN: top; COLOR: #000000; TEXT-ALIGN: justify ;
}
    <?php }
	?>

</style>
</head>
<script language="JavaScript">
var main_page='izdan_view.php';	//for redirect & links
function go2search(kadri_id,search_path) {
	var search_query=document.getElementById('q').value;
 	var href_addr='';
 	
 	if (search_query!='') {
        href_addr='q='+search_query+'&kadri_id='+kadri_id;
	 	window.location.href=main_page+'?'+href_addr;
	} else {
        alert('Введите строку поиска');
    }
} 
function del_run(tmp,works_name) {
   if(confirm('Удалить пособие: \"'+works_name+'\" ?')) {
       window.location.href=tmp;
   }
}

function test_liter_order(){//проверка порядка сортировки для вывода литературы
var val_tmp_i='';
var val_tmp_j='';

var cur_val='';
var err=false;

	for (i=0;i<7;i++) {
	 try {
         val_tmp_i=document.getElementById('num'+i).value;
     } catch (e) {
         val_tmp_i=document.all['num'+i].value;
     }
	 for (j=0;j<7;j++) 	{
		if (i!=j) 	{
			 try {val_tmp_j=document.getElementById('num'+j).value;}
			 catch (e) {val_tmp_j=document.all['num'+j].value;}
		if (parseInt(val_tmp_j)==parseInt(val_tmp_i)) 
			{alert('Cовпадение в порядке столбцов для: '+val_tmp_i+'\nCортировка по столбцам не применена');
			err=true;exit();}
					}
						}
	 }
	if (err==false) {
        liter_params.submit();
    }
} 
</script>

<?php
$izdan_array=array();

$izdan_array[0]=array("name","","","имя");
$izdan_array[1]=array("name_full","","","тип");
$izdan_array[2]=array("bibliografya","","","библиография");
$izdan_array[3]=array("authors_all","","","авторы все");
$izdan_array[4]=array("publisher","","","издательство");
$izdan_array[5]=array("year","","","год");
$izdan_array[6]=array("page_range","C.","","диапазон страниц");	//предикат для вывода страниц

//---------------------

$main_page='izdan_view.php';
$err=false;
$query_string=$_SERVER['QUERY_STRING'];

$kadri_id='';		//отбор по руководителю диплома
$izdan_id=0;
$works_id=0;

$q='';			//строка поиска
$sort='6';	//сортировка по году по умолчанию по убыванию
$stype='desc';
$filt_str_display='';	//отражение по чему идет отбор
$search_query='';
if (isset($_GET['kadri_id']) && intval($_GET['kadri_id'])>0) {$kadri_id=$_GET['kadri_id'];$filt_str_display=$filt_str_display.' преподавателю;';}
if (isset($_GET['sort'])) {$sort=$_GET['sort'];}

//if (isset($_GET['kadri_id'])) {$kadri_id=$_GET['kadri_id'];}
if (isset($_GET['q'])) {$q=$_GET['q'];$filt_str_display=$filt_str_display.'  поиску;'.del_filter_item('q');}
//if (isset($_GET['archiv'])) {$query_string=$query_string.'&archiv';}
if (isset($_GET['stype']) && ($_GET['stype']=='desc' || $_GET['stype']=='asc')) {$stype=$_GET['stype'];}

if (isset($_GET['izdan_id'])) {$izdan_id=intval($_GET['izdan_id']);}
if (isset($_GET['works_id'])) {$works_id=intval($_GET['works_id']);}

function authors_sec($izdan_id,$authors_all,$fio_short,$auth_id) {
    //соавторы
    global $cnt_izdan,$kadri_fios;

    $cnt_izdan=0;$kadri_fios='';
    if ($izdan_id>0) {
        $query='select distinct works.id,kadri.fio_short,works.izdan_id,works.kadri_id
        from works inner join kadri on kadri.id=works.kadri_id
        where works.izdan_id='.$izdan_id.'
        order by 1 desc';
        $res1=mysql_query($query);
        $cnt_izdan=mysql_num_rows($res1);
        $kadri_fios='';
        while ($b=mysql_fetch_array($res1)) {
            $cnt_izdan=mysql_num_rows($res1);
            if (intval($b['kadri_id'])==intval($auth_id)) {
                $b['fio_short']='<b>'.$b['fio_short'].'</b>';
            }

            if (trim($authors_all)=='') {
                $authors_all=trim($b['fio_short']);
            } else {
                if (trim($b['fio_short'])!='' && !strstr($authors_all,trim($b['fio_short'])))  {
                    $authors_all=$authors_all.', '.trim($b['fio_short']);
                }
            }
            $kadri_fios=$b['fio_short'].', '.$kadri_fios;
        }

        if ($fio_short!=''){
            //не показывать самого
            $authors_all=str_replace(trim($fio_short).",","",$authors_all);
            $authors_all=str_replace(trim($fio_short)." ,","",$authors_all);
            $authors_all=str_replace(trim($fio_short),"",$authors_all);
        }

        $authors_all=trim($authors_all);
        if (substr($authors_all,-1)==',') {
            $authors_all=substr($authors_all,0,-1);
        } //убираем запятую вконце списка

        if (trim($authors_all)=='') {
            $authors_all='-';
        }

        $kadri_fios=trim($kadri_fios);
        if (substr($kadri_fios,-1)==',') {
            $kadri_fios=substr($kadri_fios,0,-1);
        } //убираем запятую вконце списка

        $kadri_fios=preg_replace('/<b>|<\/b>/i','',$kadri_fios);
        return $authors_all;
	}
}
//---------------------------------------------------------------------------------
	if ($izdan_id>0 && $_GET['action']=='delete') {
        if ($kadri_id>0) {
            if (mysql_query('delete from works where id='.$works_id)) {
                echo "<div class=success> издание у сотрудника с id=".intval($kadri_id)." удалено успешно </div>";
            } else {
                echo "<div class=warning> издание у сотрудника с id=".intval($kadri_id)." не удалено</div>";
                $err=true;
            }
        } elseif ($izdan_id>0) {
            $res_=mysql_query('select izdan_id from works where izdan_id='.$izdan_id);
            if (mysql_num_rows($res_)==0) {
			    if (mysql_query('delete from izdan where id='.$izdan_id)) {
                    echo '<div class=success> издание удалено успешно </div>';
                } else {
                    echo '<div class=warning> издание не удалено</div>';
                    $err=true;
                }
			} else {
                echo '<div class=warning> издание не удалено. Удалите пособия у всех авторов/соавторов.</div>';
                $err=true;
            }
        }
	       
        if (!$err && !$onEditRemain) {
            echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?kadri_id='.$kadri_id.'\"",2000);</script>';
            echo ' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?kadri_id='.$kadri_id.'">по ссылке</a>';
        }
        exit();
    }

    // <abarmin date="14.11.2012">
    $fio="";
    $fio_short = "";
    if ($kadri_id !== "") {
        $res=mysql_query('select fio,fio_short from kadri where id='.$kadri_id." limit 0,1") or die(mysql_error());
        $a=mysql_fetch_array($res);
        $fio=$a['fio'];
        $fio_short=$a['fio_short'];
    }
    // </abarmin>
   

    echo '<form> <!--<b>'.$fio.'</b>-->';
    if (!isset($_GET['liter'])) {
        echo "<div class=text style='text-align:right'>Форма №16</div>";
        echo "<h4 align=center>СПИСОК научных и учебно-методических трудов</h4> ";
    }

	
	if (isset($_GET['save']) || isset($_GET['print'])) {
        echo '<div align=center>'.$fio.'</div><br>';
    } else {
        /*
        $listQuery='select id, IF(cnt>0,concat(fio," (",role,")"," - ",cnt), concat(fio," (",role,")") ) as fio
            from
            (select id, k.fio,kadri_role(k.id,",") as role,
            (select count(*)  from works w where w.kadri_id=k.id) as cnt
             from kadri k
            where SUBSTRING(fio,1,1)!="_"
            )t
        order by 2';
        */
	    echo 'Преподаватель:
	        <select name="kadri_id" id="kadri_id" style="width:300px"   onChange="javascript:window.location.href=\'?kadri_id=\'+this.options[this.selectedIndex].value;">';
	    //echo getFrom_ListItemValue($listQuery,'id','fio','kadri_id');
        foreach (CStaffManager::getAllPersons()->getItems() as $person) {
            echo '<option value="'.$person->getId().'">'.$person->getName().'</option>';
        }
	    echo '</select>';
    }

    if ($kadri_id>0 || (isset($_GET['kadri_id']) && $_GET['kadri_id']=='all') ) {
        if ($q!='') {
            $search_query=' and (izdan.name like "%'.$q.'%" or
                            bibliografya like "%'.$q.'%" or
                            publisher like "%'.$q.'%" or
                            izdan_type.name like "%'.$q.'%" or
                            year like "%'.$q.'%" or
                            grif like "%'.$q.'%" or
                            authors_all like "%'.$q.'%")';
        }

        $query='
        select
            izdan.name,
            page_range,
            bibliografya,
            grif,
            publisher,
            year,
            izdan_type.name as type_name,
            copy,
            volume,
            authors_all,
            works.kadri_id,
            works.id as works_id,
            izdan.id as izdan_id,
            izdan.kadri_id as izdan_kadri_id,
            izdan.approve_date as approve_date
        from works
        right join izdan on
            works.izdan_id=izdan.id
        left join izdan_type on
            izdan.type_book=izdan_type.id';
       		
        if (isset($_GET['kadri_id']) && $_GET['kadri_id']=='all' ) {
            $query=$query.' where 1 '.$search_query.' GROUP BY izdan.id';
        } else {
            $query=$query.' where works.kadri_id='.$kadri_id.' '.$search_query.' GROUP BY works.izdan_id';
        }
	
        $query=$query.' order by '.$sort.' '.$stype.'';
        $res=mysql_query($query);
        if (mysql_num_rows($res)==0) {
            if ($kadri_id!=0) {
                echo "<p class=warning>Для выбранного автора изданий в БД не найдено";
            }
        } else {
            if (!isset($_GET['save']) && !isset($_GET['print'])) {
                echo "изданий автора в базе:<b>".mysql_num_rows($res)."</b> &nbsp; ";
                $query_izdan='select count(*) as all_izdan_cnt from izdan';
                echo ' &nbsp; <a href="?kadri_id=all" title="всех авторов" style="font-size:10pt;">всего изданий кафедры ('.getScalarVal($query_izdan).')</a>';
                echo" <div style='text-align:right;'>
                    <a class=text href='?".$_SERVER["QUERY_STRING"]."&save&attach=doc' title='Выгрузить'>Передать в MS Word</a>&nbsp;&nbsp;&nbsp;
                    <a class=text href='?".$_SERVER["QUERY_STRING"]."&print' title='Распечатать'>Печать</a>&nbsp;&nbsp;&nbsp;";
                if (!isset($_GET['liter'])) {
                    echo "<a class=text href='?kadri_id=".$kadri_id."&liter' title='Список литературы'>Литература</a> </div> ";
                } else {
                    echo "<a class=text href='?kadri_id=".$kadri_id."' title='Публикации, ф.16'>Публикации, ф.16</a> </div> ";
                }
                echo '<div style="text-align:right;">
                    <input type=text name="q" id="q" width=50 value=""> &nbsp;
                    <input type=button value="Найти" title="поиск проводится в текущем разделе (архив или тек.уч.год) выбором соот.ссылки" OnClick=javascript:go2search(\''.$kadri_id.'\',\'\')></div>';

                if ($q!='') {
                    echo '<div>Поиск: <b><u>'.$q.'</u></b></div><br>';
                }
                if ($filt_str_display!='') {
                    echo '<div class=text><img src="images/filter.gif" alt="фильтр" border=0>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; <a href="?kadri_id=all"> сбросить фильтр <img src="images/del_multi_filter.gif" alt="сбросить фильтр" border=0> </a></div>';
                }
            }
        }
    } else { ?>
        Не задан автор изданий для поиска...
        <p><a href="izdan_view.php?kadri_id=all">Все издания кафедры </a>
        <?php exit;
    }

    echo '<p></form>';

    $bgcolor_title='';$bgcolor_row1='';$bgcolor_row2='';
    if (!isset($_GET['save']) && !isset($_GET['print'])) {
        $bgcolor_title=' bgcolor="#B3B3FF"';
        $bgcolor_row1=' bgcolor=#E6E6FF';
        $bgcolor_row2=' bgcolor=#D7D7FF';
    }
    if (isset($_GET['liter'])) {
        echo '<h4> Cписок литературы</h4>';

        $izdan_array[0]=array("name","","","имя");
        $izdan_array[1]=array("name_full","","","тип");
        $izdan_array[2]=array("bibliografya","","","библиография");
        $izdan_array[3]=array("authors_all","","","авторы все");
        $izdan_array[4]=array("publisher","","","издательство");
        $izdan_array[5]=array("year","","","год");
        $izdan_array[6]=array("page_range","C.","","диапазон страниц");	//предикат для вывода страниц

        //-------------------для работы произвольного порядка реквизитов в списке литературы----------
        //чтение порядка для списка литературы из БД
        $num_rows=count($izdan_array);
        $tmp_arr=array();
        $tmp_arr=$izdan_array;

        if (!isset($_GET['default'])) {//если настройки не по умолчанию
            $import_arr=array();
            $query_="select value from settings2 where name='liter_order'";
            $res_=mysql_query($query_);
            $a_=mysql_fetch_array($res_);
            $import_arr=split(',',$a_['value']);
            $num_rows=count($import_arr);

            for ($j=0;$j<$num_rows;$j++)  {
                for ($i=0;$i<$num_rows;$i++) {
                    if ($import_arr[$j]==$izdan_array[$i][0]) {
                        $tmp_arr[$j]=$izdan_array[$i];
                    }
                }
            }
            $izdan_array=$tmp_arr;

        }

        if (isset($_POST) && count($_POST)>0) {
            for ($i=0;$i<count($_POST);$i++)  {
                $izdan_array[$i]=$tmp_arr[$_POST['num'.$i]-1];
                if ($value==''){
                    $value=$izdan_array[$i][0];
                } else {
                    $value=$value.','.$izdan_array[$i][0];
                }
            }

            $value=trim($value);
            if (substr($value,-1)==',') {
                $value=substr($value,0,-1);
            }
            $query="update settings2 set value='".$value."' where name='liter_order'";
            mysql_query($query);
         }

        if (!isset($_GET['save']) && !isset($_GET['print'])) {
            echo '<div  class=text>укажите требуемый порядок вывода данных в списке литературы...При неудачной сортировке все можно восстановить к настройкам по умолчанию.</div>';
            //вывод порядка параметров для списка литературы
            echo '<form name=liter_params method=post action="">';
            for ($j=0;$j<$num_rows;$j++)  {
                echo $izdan_array[$j][3].'<select name=num'.$j.'>';
                for ($i=1;$i<=$num_rows;$i++) {
                    $selected='';
                    if ($i==$j+1) {
                        $selected=' selected';
                    }
                    echo '<option value='.$i.''.$selected.'>'.$i.'</option>';
                }
                echo '</select> &nbsp;';
            }
            echo ' &nbsp; <input type=checkbox name=show_cols>делить на столбцы &nbsp; <input type=button value=Ok onClick=javascript:test_liter_order();></form>';
            echo '<input type=button value="по умолчанию" title="восстановить параметры по умолчанию"
                onClick=window.location.href="'.$main_page.'?kadri_id='.$_GET['kadri_id'].'&liter&default">';
            //------------------------------------------------------------------------------------
        }
        if (isset($_POST['show_cols']) && $_POST['show_cols']=='on') {//вывод с делением на столбики
            ?>
            <table border="1"  class=text width=99% cellspacing=0 cellpadding=0>
                <tr <?php echo $bgcolor_title;?> >
                    <td width="31" height=50>№ п\п</td>
                    <?php
                    for ($i=0;$i<count($izdan_array)-1;$i++) {
                        echo '<td>'.$izdan_array[$i][3].'&nbsp;</td>';
                    }
                    ?>
                </tr>
            <?php

            $i=0;
            while($a=mysql_fetch_array($res))  {
                $i++;
                if (($i/2)<>round($i/2)) {
                    echo "<tr ".$bgcolor_row1.">\n";
                } else {
                    echo "<tr  ".$bgcolor_row2." valign=top>\n";
                }
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                echo " <td width=31 valign=top alicn=left>".$i."&nbsp;</td>\n";

                //соавторы
                $a['authors_all']=authors_sec($a['izdan_id'],$a['authors_all'],'',0);

                $str_out='';

                for ($j=0;$j<count($izdan_array)-1;$j++) {
                    echo '<td>'.$izdan_array[$j][1].trim($a[$izdan_array[$j][0]]).trim($a[$izdan_array[$j][2]]).'&nbsp;</td>';
                }
                echo "</tr>\n";
            }
        ?>
            </table>
        <?php
        } else {
        ?>
            <table border="1"  class=text width=99% cellspacing=0 cellpadding=0>
              <tr <?php echo $bgcolor_title;?> >
                <td width="31" height=50>№ п\п</td>
                <td>Название</td></tr>
            <?php

            $i=0;
            while($a=mysql_fetch_array($res))  {
                $i++;
                if (($i/2)<>round($i/2)) {
                    echo "<tr ".$bgcolor_row1.">\n";
                } else {
                    echo "<tr  ".$bgcolor_row2." valign=top>\n";
                }
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                echo " <td width=31 valign=top alicn=left>".$i."&nbsp;</td>\n";
                //--------------------------------------------------------------------------------------------
                //соавторы
                $a['authors_all']=authors_sec($a['izdan_id'],$a['authors_all'],'',0);
                $str_out='';
                for ($j=count($izdan_array)-1;$j>=0;$j--) {
                    if (trim($a[$izdan_array[$j][0]]!='')) {
                        $str_out=$izdan_array[$j][1].trim($a[$izdan_array[$j][0]]).', '.$str_out;
                    }
                }
                $str_out=trim($str_out);
                if (substr($str_out,-1)==',') {
                    $str_out=substr($str_out,0,-1);
                }
                echo "<td valign=top align=left  height=40>".$str_out."&nbsp;</td></tr>\n";
            }
        ?>
            </table>
        <?php
        }//конец столбиков
    } else { ?>
        <table border="1"  class=text width=99% cellspacing=0 cellpadding=0>
            <tr <?php echo $bgcolor_title;?> >
            <?php if (!isset($_GET['save']) && !isset($_GET['print'])) {//т.е. для просмотра на портале ?>
            <?php if ($write_mode===true) {?>
                <td width="31" class=notinfo>
                    <img src="images/toupdate.png" alt="изменить">&nbsp;&nbsp;&nbsp;
                </td>
                <td width="31" class=notinfo>
                    <img src="images/todelete.png" alt="изменить">&nbsp;&nbsp;&nbsp;
                </td>
            <?php } ?>
            <td width="31">№ п\п</td>
            <td><?php echo print_col(1,'Название');?></td>
            <td width="42"><?php echo print_col(2,'Объем страниц (диапазон)');?></td>
            <td><?php echo print_col(3,'Библиография');?></td>
            <td>Авторы все</td>
            <td>в т.ч. с кафедры</td>
            <td><?php echo print_col(4,'Гриф издания');?></td>
            <td><?php echo print_col(5,'Издательство');?></td>
            <td width="73">	<?php echo print_col(6,'Год издания');?></td>
            <td><?php echo print_col(9, "Подписано в печать"); ?></td>
            <td width="146"><?php echo print_col(7,'Вид издания');?></td>
            <td><?php echo print_col(8,'Копия издания');?></td>
            </tr>
        <?php

        $i=0;
        while($a=mysql_fetch_array($res))  {
            $i++;
            if (($i/2)<>round($i/2)) {
                echo "<tr ".$bgcolor_row1." valign=top>\n";
            } else {
                echo "<tr  ".$bgcolor_row2." valign=top>\n";
            }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if ($write_mode===true) {
                echo "  <td width=31 class=notinfo><a href='izdan.php?izdan_id=".$a['izdan_id']."&works_id=".$a['works_id']."&".$query_string.
                "&action=update'> <img src=images/toupdate.png alt=изменить border=0></a></td>
                <td width=31 class=notinfo><a href='javascript:del_run(\"izdan_view.php?izdan_id=".$a['izdan_id']."&works_id=".$a['works_id']."&".
                $query_string."&action=delete\",\"".substr($a['name'],0,strpos($a['name'],' ',10))."...\");'> <img src=images/todelete.png alt=удалить border=0></a></td>";
            }
            echo " <td width=31 valign=top alicn=left>".$i."&nbsp;</td>\n";
            echo " <td valign=top>".color_mark($q,$a['name'])."</td>\n";
            $tmp_str1='';
            if (trim($a['page_range'])!='') {
                $tmp_str1=" (".trim($a['page_range']).")";
            }
            $tmp_str2='-';
            if (trim($a['volume'])!='') {
                $tmp_str2=trim($a['volume']);
            }
            echo "<td width=42 valign=top align=center>".$tmp_str2.$tmp_str1."&nbsp;</td>\n";
            echo "<td valign=top align=left>".color_mark($q,$a['bibliografya'])."&nbsp;</td>\n";
            //соавторы
            $a['authors_all']=authors_sec($a['izdan_id'],$a['authors_all'],'',$a['izdan_kadri_id']);
            echo "<td valign=top align=left width=120>".color_mark($q,$a['authors_all'])."&nbsp;</td>\n ";
            echo "<td align=center valign=top>";
            if ($cnt_izdan>0) {
                echo "<a href='autors.php?izdan_id=".$a['izdan_id']."&kadri_id=".$kadri_id."' target='_blank' title='".$kadri_fios."'>".$cnt_izdan."</a>";
            } {
                echo '-';
            }
            $izdan_file='';
            // перекодировка пути файла с учетом кодировки на сервере и в браузере (актуально для кирилицы)
            if ($server_encoding!='') {
                $izdan_file=mb_convert_encoding($a['copy'],$server_encoding,$browser_encoding);
            }
            $izdan_file=rawurlencode($izdan_file); //urlencode, различия в кодировке пробела
            echo "</td>
            <td valign=top>".color_mark($q,$a['grif'])."&nbsp;</td>\n
            <td width=129 valign=top>".color_mark($q,$a['publisher'])."&nbsp;</td>\n
            <td width=73 valign=top>".color_mark($q,$a['year'])."&nbsp;</td>\n
            <td>".color_mark($q, $a['approve_date'])."</td>
            <td width=146 valign=top>".color_mark($q,$a['type_name'])."&nbsp;</td>\n
            <td width=121>".
            ($a['copy']!=''?"<a href='library/izdan/".$izdan_file."' target=_blank>".file_type_img($a['copy'],true,true)."</a>":"").
            "&nbsp;</td></tr>\n";
        }
    } else {//при печати или выгрузке ф.16
        ?>
        <td width="31">№ п\п</td>
        <td>Название</td>
        <td width="42">Объем, страниц</td>
        <td>Библиография</td>
        <td>Соавторы</td></tr>
        </tr>
        <?php

        $i=0;
        while($a=mysql_fetch_array($res))  {
        $i++;
        if (($i/2)<>round($i/2)) {echo "<tr ".$bgcolor_row1.">\n";}
        else {echo "<tr  ".$bgcolor_row2." valign=top>\n";}
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        echo " <td width=31 valign=top alicn=left>".$i."&nbsp;</td>\n";

        //добавляем в название тип_публикации для печати и экспорта
        echo " <td valign=top>".$a['name']."<p><b>".$a['type_name']." </b></td>\n";

        //--------------------------------------------------------------------------------------------
        //соавторы
        $a['authors_all']=authors_sec($a['izdan_id'],$a['authors_all'],$fio_short);


        $tmp_str2='-';
        if (trim($a['volume'])!='') {$tmp_str2=$a['volume']."<br><u>".ceil($a['volume']/($cnt_izdan+1))."</u>";}

        echo "<td width=42 valign=top align=center>".$tmp_str2."&nbsp;</td>\n";

        //добавляем в библиографию год и издательство, если не указано
        if (trim($a['name'])!='' && !strstr($a['bibliografya'],$a['name'])) {$a['bibliografya']=$a['name'].','.$a['bibliografya'];}
        if (trim($a['publisher'])!='' && !strstr($a['bibliografya'],$a['publisher'])) {$a['bibliografya']=$a['bibliografya'].', '.$a['publisher'];}
        if (trim($a['year'])!='' && !strstr($a['bibliografya'],$a['year'])) {$a['bibliografya']=$a['bibliografya'].', '.$a['year'];}
        if (trim($a['page_range'])!='' && !strstr($a['bibliografya'],$a['page_range'])) {$a['bibliografya']=$a['bibliografya'].', -С.'.$a['page_range'];}


        echo "<td valign=top align=left>".$a['bibliografya']."&nbsp;</td>\n";
        echo "<td valign=top align=left width=120>".$a['authors_all']."&nbsp;</td>\n</tr>\n";
        }
        }

        ?>
        </table>
    <?php 
	}
	if (!isset($_GET['save']) && !isset($_GET['print'])) {
	?>
<p class=text><b>Примечание:</b><br> 	
- удалить издание можно <b>только</b> при отборе по преподавателю или <br>
- если у издания нет авторов/соавторов с кафедры в <a href="?kadri_id=all">общем списке изданий </a> <br>
- <b>жирным</b> шрифтом выделен текущий автор при использовании его в отборе, либо автор добавления публикации в общий список при просмотре в общем списке (без отбора по автору)
</p>
<?php if ($write_mode===true) {?><p><a href="izdan.php?kadri_id=<?php echo $kadri_id;?>">Добавить издание</a></p><?php } ?>

	<?php
	}else {
?>
<p>&nbsp;</p>
<table border=0 class=text>
	<tr><td>Соискатель</td> <td>________________ <?php echo $fio_short;?></td> </tr>
	<tr><td>Список верен:</td> <td></td> </tr>
	<tr><td>Заведующий кафедрой АСУ</td> <td>________________  Куликов Г.Г.</td> </tr>
	<tr><td>Ученый секретарь</td> <td>________________  Буткин Н.С.</td> </tr>			
</table>			
<?php
if (!isset($_GET['save'])) {
?>
<p><a class=notinfo href="javascript:history.back();">Назад.</a></p>

<?php
} }

if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';}
?>

<?php include('footer.php'); ?>