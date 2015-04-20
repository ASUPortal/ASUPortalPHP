<?php
function getSqlNULL($item_name)	// заполнение NULL-значениями для sql-запроса
{	
	// удаляем экранирование выражения для sql-запроса
	$item_name_=preg_replace('/^[\'\"]|[\'\"]$/','',$item_name);
	if ($item_name_=='' || $item_name_='0') return 'NULL';
	else return $item_name;
}

function isNewItem($date_time)
{
 $day=substr($date_time,8,2);
 $mon=substr($date_time,5,2);
 $year=substr($date_time,0,4);

 $today  = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
 $last7  = mktime(0, 0, 0, $mon, $day+7, $year);

 if ($today<$last7) return true;
 else return false;
}

function mark_new($date_time)	// выделение обновления за последние 7 дней
{
	$date_time=DateTimeCustomConvert($date_time,'d','rus2mysql');
	if (isNewItem($date_time)) echo '<span class=warning>new</span>';	
}

function file_uniq_name($file_name)	// получение уникального имени файла\папки
{
	$file_name_=basename($file_name);
	
	if (file_exists($file_name)) {
		$dirname=dirname($file_name);
		if ($dirname!='') $dirname.='/';
		
		$f_name_only=basename($file_name);	// имя файла
		$f_ext=fileExt($file_name);	// расширение файла
		
		if ($f_ext!='') $f_name_only=basename($file_name,'.'.$f_ext); 
		$i=1;
		$file_name_=$f_name_only.'_'.$i.($f_ext!=''?'.'.$f_ext:'');
		//echo '<div>file_exist'.$dirname.$file_name_.'</div>';
		while (file_exists($dirname.$file_name_) && $i<100)
		{
			$file_name_=$f_name_only.'_'.$i.($f_ext!=''?'.'.$f_ext:'');
			//echo '<div>*'.$file_name_.'</div>';
			$i++;		
		}
		if ($i==100)$file_name_='';	// ошибка зацикливания
	}
	return $file_name_;
}

function fileExt($file_name)	// получение расширения файла
{
	$f_ext='';
	if ($file_name!='' && strrpos($file_name,'.')!=false)
		$f_ext=substr($file_name,strrpos($file_name,'.')+1);
	return $f_ext;
}

function isAuthSess()	// проверка авторизации сессии
{
	if (isset($_SESSION['auth']) && intval($_SESSION['auth'])==1)
		return true;
	else return false;	
}

function getTaskRightId($user_id,$pg_url)	// возвращение типа доступа к задаче
{
	$taskRightId=0;
	if (intval($user_id)>0 && trim($pg_url)!='') {
	$query="SELECT distinct tig.task_rights_id 
		FROM task_in_group tig inner join tasks t on t.id=tig.task_id 
		WHERE tig.user_group_id in (
		  SELECT group_id
			FROM user_in_group
			WHERE user_id ='".$user_id."') and t.url like '".$pg_url."%'";

	//введение персональных задач пользователя
	$query.="union 
		SELECT distinct tiu.task_rights_id 
		FROM task_in_user tiu inner join tasks t on t.id=tiu.task_id 
		WHERE user_id ='".$user_id."' and t.url like '".$pg_url."%'
		order by 1 desc limit 0,1";
	$taskRightId=intval(getScalarVal($query));
	}
	return $taskRightId;
}
function del_HTMLTags($item_name)	// удаление html-тегов
{
$search = array ("'<script[^>]*?>.*?</script>'si",  // Вырезает javaScript
                 "'<[\/\!]*?[^<>]*?>'si");	 // Вырезает HTML-теги

	$item_name=preg_replace($search,'',$item_name);
	return $item_name;
}

function del_filter_item($item_name)	//очистка\отмена индивидуального фильтра
{
	global $query_string;
	$link='';
	if ($item_name!='')
	{
		  $link='<a class=button title="удалить параметр фильтра" href="?'.reset_param_name($query_string,$item_name).'"><img src="images/del_filter.gif" border=0></a>';
	}
	return $link;
}
/*  удаление файла, с поддержкой авто-удаления оконок
$path-пути,
$file_name-имя_файла,
$trumb_path-пути_иконки_изображения (например "/small")
*/
function delFile($path,$file_name,$trumb_path)
{

if ($file_name!="") {
	if (file_exists($path.'/'.$file_name))			 
		if (unlink($path.'/'.$file_name)) echo '<div class=success>файл успешно удален</div>';
		else echo '<div class=warning>файл не удален: '.$path.'/'.$file_name.'</div>';
	if ($trumb_path!='' && file_exists($path.$trumb_path.'/'.$file_name))
		if (unlink($path.$trumb_path.'/'.$file_name)) echo '<div class=success>иконка файла успешно удалена</div>';
		else echo '<div class=warning>файл не удален: '.$path.$trumb_path.'/'.$file_name.'</div>';
}
}
/*  сохранение файла, с указанием:
$path-пути,
$file_obj-объекта_файла $_FILES,
$file_mask_name-маски_имени,
$file_type_arr-разрешенных_типов_файла (например array('.jpg','.gif'),
$trumb_path-пути_иконки_изображения (например "small/sm_")
$latin - использование транслитерации
*/
function saveFile($path,$file_obj,$file_mask_name='',$file_type_arr,$trumb_path='',$latin=true)
{
	$err=false; //флаг ошибок
	$max_f_len=50;	//максимальный число символов в имени файла
	$image_name='';
	$trumb_file_type_arr=array('.gif','.jpg','.png');
	
	if ($file_obj['name']!="") {
	    $image_ext=strtolower(substr($file_obj['name'],strrpos($file_obj['name'],'.')));
	    if  ($file_obj['size']==0) {
                  echo '<div class="warning"> Не удается прикрепить тип файла <strong>'.$image_ext.'</strong>, попробуйте другой!</div>';
                  $err=true;		
	    }
	    else //if ($file_obj['name']!="" && $file_obj['size']>0)             
               {
		
		if (isset($file_type_arr) && array_search($image_ext, $file_type_arr)===false )
                 {
                  echo '<div class="warning"> Файл должен быть только '.implode(', ',$file_type_arr).' типа!</div>';
                  $err=true;
                 }
            
            if (!$err) {
                $i=1;
                if ($file_mask_name=='')  //имя по ранее заданной маске
                   {
                    $file_mask_name=strtolower(substr($file_obj['name'],0,strpos($file_obj['name'],'.')));                    
                    if (strlen($file_mask_name)>$max_f_len) $file_mask_name=substr($file_mask_name,0,$max_f_len);                    
                    }
                if ($latin) $file_mask_name=Trans_file_word($file_mask_name);   //транслитерация в латиницу    
                $image_name=$file_mask_name.$image_ext;
                while (file_exists($path.$image_name))
                    { $image_name=$file_mask_name.'_'.$i.$image_ext; $i++; }
                
                if(!copy($file_obj['tmp_name'],$path.$image_name))
                    { echo '<div class="warning"> Не получилось скопировать файл.</div>';$err=true;}
                else              
                    // проверка допустимости создания иконки
		    if ($trumb_path!='' && array_search($image_ext, $trumb_file_type_arr) ) {
                        if (!img_resize($path.$image_name, $path.$trumb_path.$image_name, 0, 120))
                           { echo '<div class="warning">ошибка создания иконки изображения</div>';$err=true;}
		    }
               }
            }
	}
        return $image_name;  
}
function getObjectInTask($task_path,$obj_name)	//проверка число обращений к таблицам Сотрудники, Студенты в БД
//$task_path	путь к файлу задачи
//$obj_name	имя объекта поиска в задаче (student,kadri,both)
{
	$find=0;	
	if ($task_path!=null && $task_path!='')
	{
		$f_content=file_get_contents(($task_path));
		
		//поиск соответствия в файле задачи		
		if ($obj_name=='both')  $find=preg_match("/(join|from) (kadri|students)/i", $f_content);
		else if ($obj_name=='kadri')  $find=preg_match("/(join|from) kadri/i", $f_content);
		else if ($obj_name=='student')  $find=preg_match("/(join|from) students/i", $f_content);
	}	
	return $find;
}

function getTaskAccess($user_id,$url)	//проверка доступа к задаче через матрицу доступа пользователя
//$user_id	идентификатор польдователя
//$url		адрес url задачи
{
		$query="SELECT distinct tig.task_rights_id,t.url,t.name as pg_name 
			FROM task_in_group tig inner join tasks t on t.id=tig.task_id 
			WHERE tig.user_group_id in (
			  SELECT group_id
				FROM user_in_group
				WHERE user_id ='".$user_id."') and t.url like '".$url."%'";

		//введение персональных задач пользователя
		$query.="union 
			SELECT distinct tiu.task_rights_id,t.url,t.name as pg_name 
			FROM task_in_user tiu inner join tasks t on t.id=tiu.task_id 
			WHERE user_id ='".$user_id."' and t.url like '".$url."%'
			order by 1 desc limit 0,1";
		$res=mysql_query($query);
		if (mysql_num_rows($res)<=0) return false;
		else return true;

}

function makeDoc($f_name_tml,$f_name_out,$data)	//создать документ на основе rtf-шаблона
{
	function tortf($str) {	//формируем представление символов в rtf-формат
	  $s = '';
	  for ($i = 0; $i < strlen($str); $i++)
	    $s .= (ord($str[$i]) > 127) ? sprintf("\\'%02x",ord($str[$i])) : $str[$i];
	  return $s;
	}	
	
	header("Content-type: application/msword");
	header("Content-Disposition: attachment; filename=$f_name_out");	
	
	$doc = file_get_contents($f_name_tml);
	
	$doc = preg_replace('#\\\\{(.*?)\\\\}#se','isset($data["$1"]) ? tortf($data["$1"]) : "$0"',$doc);

	echo $doc;	
}

function showPrintSaveOpt($typeAct,$q_str,$pg_name)
{	
	$response='';
	if (strpos($typeAct,'print') !== false)
		$response.=' <a class=pgLink href="'.$pg_name.'?'.$q_str.'&print" title="Распечатать" target="_blank"><img src="images/print.gif" border=0 alt="Печать"></a> ';
	
	if (strpos($typeAct,'doc') !== false)
		$response.=' <a class=pgLink href="'.$pg_name.'?'.$q_str.'&save&attach=doc" title="Выгрузить в MS Word" target="_blank"><img src="images/design/file_types/word_file.gif" border=0 alt="Открыть в MS Word"></a> ';
	

	return $response;
}
function print_col($col_num,$col_name)
{	//формирование заголовка столбца  списочной таблицы
global $sort,$stype,$files_path;
$query_string=$_SERVER['QUERY_STRING'];
if ($col_num>0 && $col_name!='') {
$col='';

$col='<a href="?'.reset_param_name_ARR($query_string,array('sort','page','stype')).'&sort='.$col_num;	
$col.='&stype='.echoIf(($sort==$col_num && $stype=='asc'),'desc','asc').'" title="сортировать">'.$col_name;
	
	if ($sort==$col_num) 
		{
		$col.= '<img border=0 src="'.$files_path.'images/design/'.echoIf(($stype=='asc'),'s_asc.png','s_desc.png').'">';
		}
	$col.= '</a>';
	return $col;
	}
}

function echoIf($if,$actTrue,$actFalse)
{//формирование выражения по условия, для использования в строке вывода
	if ($if) {return $actTrue;}
	else {return $actFalse;}	
}

function showSpravLink($sprav_rus_name)
{
 //spravochnik.php?sprav_id=2&spr_type=1#id_2
 $sprav_type=0;
 $sprav_id=0;
 $link='';
 if (trim($sprav_rus_name)!='') {
	$query='select sprav_main_id,id from sprav_links where comment like "'.trim($sprav_rus_name).'"';
	$res=mysql_query($query);
	$a=mysql_fetch_assoc($res);
	$sprav_type=intval($a['sprav_main_id'],10);
	$sprav_id=intval($a['id'],10);
 $link='sprav_id='.$sprav_id.'&spr_type='.$sprav_type.'#id_'.$sprav_id;
 }
 return $link;
}

function sprav_edit_link($sprav_name)	//быстрая ссылка на справочник для правки элементов
{
  global $files_path;
  
  $spr_url='';
  $sprav_stat=getRowSqlVar('select sl.id,t.url
			 from sprav_links sl
				left join tasks t on t.id=sl.task_id
			where sprav_name="'.$sprav_name.'"');
  $sprav_id=$sprav_stat[0]['id'];
  $url=$sprav_stat[0]['url'];
  if ($url!='') $spr_url='<a href="'.$url.'"><img src='.$files_path.'images/toupdate.png border=0 alt="редактировать список" title="редактировать список"></a>';
  else if ($sprav_id>0) {
  	$spr_url= '<a href="spravochnik_edit.php?sprav_id='.$sprav_id.'">
		<img src='.$files_path.'images/toupdate.png border=0 alt="редактировать список" title="редактировать список">
		</a>';
  }  
  return $spr_url;
} 
function SavePrintMode()	// режим страницы при печати или выгрузке
{
	if (isset($_GET['print']) || isset($_GET['save'])) return true;
	else return false;
}

function getPagenumList($pg_cnt,$pg_cur,$pg_tw,$pg_name,$q_str,$link_tmp) {
    //нумерация страниц с возможностью использования шаблона с %%\w+%%

    /*
    pg_cnt  -общее число страниц
    pg_cur  -текущая страница
    pg_tw   -число соседних страниц, по умолчанию 3
    pg_name -имя переменной GET
    q_str   -query string
    link_tmp- шаблон ссылки, например "javascript:sPage.page.value=%%i%%;sPage.submit();", по умолчанию '?"+q_str+"&"+pg_name+"="+(pg_cur-1)+"'
            
    пример нумерации 1 2 3...5 6 7...9 10 11
    */
    $pg_response="";
    $pg_cur=intval($pg_cur);
    $pg_cnt=intval($pg_cnt);
  
    if (SavePrintMode()) {
	    $pg_response= '<span > '.$pg_cur.' из '.$pg_cnt.'</span> ';
	} else {
        if (intval($pg_tw)<=0) {
            $pg_tw = 3;
        };
        $pages = array();

        if (intval($pg_cur) > 1) {
            if ($link_tmp == '') {
                $pages[] = "<a href='?".$q_str."&".$pg_name."=".($pg_cur-1)."'>< назад</a>";
            } else {
                $pages[] = "<a href=".preg_replace ('/%%\w+%%/',$pg_cur-1,$link_tmp).">< назад</a>";
            }
        }
        for ($i = 1; $i <= $pg_cnt; $i++) {
            if ($i == $pg_cur) {
                $pages["current"] = '<a href="#">'.$i.'</a>';
            } else {
                if (abs($i-$pg_cur)<$pg_tw-1 || $i<=$pg_tw || $i>$pg_cnt-$pg_tw) {
                    if ($link_tmp=='') {
                        $pages[] = "<a href='?".$q_str."&".$pg_name."=".$i."'>".$i."</a>";
                    } else {
                        $pages[] = "<a href=".preg_replace('/%%\w+%%/',$i,$link_tmp).">".$i."</a>";
                    }
                } else {
                    if (!array_search('<a href="#">...</a>', $pages)) {
                        $pages[] = '<a href="#">...</a>';
                    }
                }
            }
        }
        if ($pg_cur<$pg_cnt) {
            if ($link_tmp=='') {
                $pages[] = "<a href='?".$q_str."&".$pg_name."=".($pg_cur+1)."'>вперед ></a>";
            } else {
                $pages[] = "<a href=".preg_replace('/%%\w+%%/',$pg_cur+1,$link_tmp).">вперед ></a>";
            }
        }

        $pg_response = '<div class="pagination">';
        $pg_response .= '<ul>';
        foreach ($pages as $key=>$link) {
            if ($key == "current") {
                $pg_response .= '<li class="active">'.$link.'</li>';
            } else {
                $pg_response .= '<li>'.$link.'</li>';
            }
        }
        $pg_response .= '</ul>';
        $pg_response .= '</div>';
    }
    return $pg_response;
}
//-----------------------------------
function getScalarVal($q_val)
{	//находим скалярное значение по запросу к БД
$ScalarVal='';
 if ($q_val!='')
 {
	$res=mysql_query($q_val);
	$ScalarVal=mysql_result($res,0);	
  }
return $ScalarVal;
} 
function getRowSqlVar($q_val)
{	//получаем строку данных
$ArrSql=array();
if ($q_val!='') { 
	$res=mysql_query($q_val);
	if (@mysql_num_rows($res)>0) {
		while ($a=mysql_fetch_assoc($res))
        {

        //$ArrSql=mysql_fetch_array($res);
        array_push($ArrSql, $a);
        }
		//$ArrSql=mysql_fetch_assoc($res);
 	}
	}
return $ArrSql;
} 

function color_mark($search_val,$date2color)	// подсветка найденного при поиске
{
//$date2color=str_replace($search_val,"<b><font color='#2020fe'>".$search_val."</font></b>",$date2color);
if (trim($search_val)!='') {
$date2color=preg_replace("/".$search_val."/i","<b><font color='#2020fe'>\${0}</font></b>",$date2color); 
}
return $date2color; 
} 

//предотвращение sql-инъекции глобально, слово и пробел
function request_arr_replace_($val) {
	// <abarmin date="10.06.2012">
	// никто не думал, что массив тоже можно в post передавать?
	if (!is_array($val)) {
		$val=preg_replace("/(select |insert |drop |delete |create )/i", "\\1_", $val);
		$val=str_replace("'","\"",$val);	
	} 
	// </abarmin>
	return $val;
}

// f_ri, f_ro использовать во ВСЕХ sql-запросах !
/*
function request_arr_replace($arr_in)
{
reset($arr_in);
while (list($key, $val) = each($arr_in)) {
    $arr_in[$key]=str_replace("'","\"",$val);
	$arr_in[$key]=mysql_real_escape_string($val);	//только при соед. с БД
	//$val.' add_str';
	//$arr_in[$key]=preg_replace("/(select |insert |drop |delete |create )/i", "_\\1_", $val);
	//echo ' $fruit='.$_REQUEST[$key].'<br>';
	//echo "$key => $val\n";
	}
reset($arr_in);
return $arr_in;
} 
*/
//print_r($_GET);
//устранение sql_инъекции
/*
$_POST=request_arr_replace($_POST);
$_REQUEST=request_arr_replace($_REQUEST);
$_GET=request_arr_replace($_GET);
*/

	$_POST=array_map('request_arr_replace_',$_POST);
	$_REQUEST=array_map('request_arr_replace_',$_REQUEST);
	$_GET=array_map('request_arr_replace_',$_GET);

//echo '<hr>';
//print_r($_GET);
//print_r($_GET);
//
//----------------------------------------------------------------------------
function print_file_size($fileName)
{
global $mirror_url_name,$inner_url_name; //из сис.переменных

if ($fileName!='') {	//$fileName='library/gost/'.$dir_name.$g1['nameFile'])
	 if (file_exists($fileName)) {echo '<font size=-2 style="font-family:Arial;">, размер файла: <b>'.round(filesize($fileName)/1024/1024,3).'</b> МБ </font>';}
	 else {
	  echo '<span><font size=-2 color="#FF0000" style="font-family:Arial;"> файл не найден.</font></span>';
		 
	  }
}

} 

function success_msg($msg_caption)
{//вывод сообщения об успехе операции
	 $out_str='';
	 if ($msg_caption!='')
	 {	$out_str='<div class=success>'.$msg_caption.'</div>';	}
	 return $out_str;
} 
function error_msg($msg_caption)
{//вывод сообщения о провале операции
	 $out_str='';
	 if ($msg_caption!='')
	 {	$out_str='<div class=warning>'.$msg_caption.'</div>';	}
	 return $out_str;
} 

function print_sort_col($sort_id,$col_caption)
 {//печать ссылки столбца сортировки, текущий столбец сортировки выделяется красным 
  global $query_string;
  
  $out_str='';
  $add_string=reset_param_name($query_string,'sort');
  if ($sort_id>0 && $col_caption!='') {
	  $mark_style='';
	  
	  if (isset($_GET['sort']) && $sort_id==$_GET['sort']) {$mark_style='color:#FF0000;';}
	  $out_str='<a href="?'.$add_string.'&sort='.$sort_id.'" title="сортировать"><span style="'.$mark_style.'">'.$col_caption.'</span></a>';
	}
	return $out_str;
  
 } 
//--------------------------------------------------------------------
function reset_param_name ($query_string,$param_name)	//выдираем из query_string лишний параметр для его установки новым значением
//только для числовых параметров и текстовых значений параметров!
//все параметры д\б уникальными и недопускается вхождение строки названия д\д например page и pageVals
{
if ($query_string!='' && $param_name!='') {
	$query_string_='';
	if (!strstr($query_string,'&')) {//если только 1 параметр в строке
	 	$query_string_=preg_replace("/(".$param_name."(=|))([^&]*)/i","",$query_string);
	}
	else
	{
	 	//удаление переменных и с входящими названиями пример Page - pageVal
	 	//разделитель переменных & 
		$preg_tmpl="/(&".$param_name."$|^".$param_name."(=|&)|&".$param_name."(=|&))([^&]*)/i";	//|^".$param_name."(=|&)|&".$param_name."(=|&)([^&]*)
	 	$query_string_=preg_replace($preg_tmpl,"",$query_string);
	}
	return str_replace('&&','&',$query_string_);
	}
}
function reset_param_name_ARR ($query_string,$param_arr)	//применение reset_param_name к массиву параметров
{
	if ($query_string!='' && count($param_arr)>0) {
		for ($i=0;$i<count($param_arr);$i++)
		{
		$query_string=reset_param_name($query_string,$param_arr[$i]);
		}
		return $query_string;
	}

}

function getFormItemValue($FormItemName)
//поиск значения в БД или при ошибке возврат прошлого, для вывода в элемент формы (поле формы)
//ВАЖНО чтобы имя в БД и в форме совпадали либо зависили по к-нить правилу !
//не для списков (SELECT), для него использовать getFrom_ListItemValue
{global $res_edit;
 if (isset($res_edit)) {
     // <abarmin date="06.05.2012">
     if (array_key_exists($FormItemName, $res_edit)) {
         return f_ro($res_edit[$FormItemName]);	//при правке значения (по клику править в спис.форме)
     }
     // </abarmin>
 }
 else 
 	if (isset($_POST[$FormItemName])) {return $_POST[$FormItemName];}	//при ошибке сохранения (не зап. все реквизиты)
} 

function getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName,$is_mList = false,$is_color=false)
/*
listQuery -  запрос для формирования списка с listId и listName
	listId- наименование ID в списке, 
	listName- название элемента в списке
FormListItemName - имя элемента формы
is_mList	- необязательный параметр, для множественного выбора в списках
$is_color	-необязательный параметр, для выделения цветом в списке
*/
{
	//	
	global $res_edit;
	/*
	$listQuery='select id,fio from students order by fio';
	$FormListItemName='student_id';
	$listName='fio';
	$listId='id';
	*/
//проверка на множественый выбор в списке
//$is_mList=false;
    // <abarmin date="06.05.2012">
    if (array_key_exists($FormListItemName, $_GET)) {
        if (!$is_mList && is_array($_GET[$FormListItemName])) {$is_mList=true;}
    }
    // </abarmin>

$res=mysql_query($listQuery) or die("mysql error - > ".mysql_error()." (".$listQuery.")");
$list_str='';
if (!$is_mList) $list_str=$list_str.'<option value=0>выберите из списка...('.mysql_num_rows($res).')</option>';
while ($a=mysql_fetch_array($res)) 	{	
	$select_val='';
	$style='';
	if ($is_color && isset($a['color_mark']) && $a['color_mark']!='') {
        $style=' style="color:'.$a['color_mark'].'; "';
    }
    /*
    if ($FormListItemName == "person_type2") {
        echo "listId-> ".$listId."\n";
        if (array_key_exists($listId, $a)) {
            if (array_key_exists($a[$listId], $res_edit[$FormListItemName])) {
                echo "some messave\n";
            }
        }
    }
    */
    // <abarmin date="06.05.2012">
	if (isset($res_edit)) {
        if (array_key_exists($FormListItemName, $res_edit)) {
            if (array_key_exists($listId, $a)) {
                if (is_string($res_edit[$FormListItemName])) {
                    if ($a[$listId] == $res_edit[$FormListItemName]) {
                        $select_val=' selected';
                    }
                } elseif(is_array($res_edit[$FormListItemName])) {
                    if (array_key_exists($a[$listId], $res_edit[$FormListItemName])) {
                        $select_val=' selected';
                    }
                }
            }
        }
    }
    // </abarmin>
	else if (isset($_POST[$FormListItemName])) {
		if (!$is_mList && $_POST[$FormListItemName]==$a[$listId]) {$select_val=' selected';} 
		else if ($is_mList && in_array($a[$listId], $_POST[$FormListItemName])) {$select_val=' selected';}
	}
	else if (isset($_GET[$FormListItemName])) {
		if (!$is_mList && $_GET[$FormListItemName]==$a[$listId]) {$select_val=' selected';} 
		else if ($is_mList && in_array($a[$listId], $_GET[$FormListItemName])) {$select_val=' selected';}
	}
		
	$list_str=$list_str. '<option value="'.$a[$listId].'"'.$select_val.' '.$style.'>'.$a[$listName].'</option>'."\n\t";
	}
return $list_str;
 
} 
//---------------------------------------------------------------------------------
/* преобразовать дробное число с учетом направления отображения (browse, [mysql, php,...]	)
 $number - число для преобразования
 $direction - направление преобразования
 пример: numLocal('12.51','browse') -> '12,51'
 пример: numLocal('12,51','sql') -> '12.51'
*/
function numLocal($number,$direction='browse')
{
	$localnum='';	
	if (floatval(str_replace(',','.',$number))==0) $number='';	// очистить нулевые значения
	if ($number!='')
	{
		if ($direction=='browse') $localnum=str_replace('.',',',$number);
		else $localnum=str_replace(',','.',$number);
	}
	return $localnum;
}

function DateTimeCustomConvert($strDate,$type,$direction)
{ //принимает "дд.мм.гггг чч:мм:сс" -> "гггг-мм-дд чч:мм:сс", жесткое ограничение на число символов 10 в дате и 19 в датаВремя
 //type=('dt','d') т.е. датаВремя или только дата, если не указан, то считаем что только дата (d)
 //derection=('mysql2rus','rus2mysql'); rus2mysql-по умолчанию
//DateTimeCustomConvert($strDate,'d','mysql2rus')

 if ($type=='' || !isset($type)) {$type='d';}
 if ($direction=='' || !isset($direction)) {$direction='rus2mysql';}
 
 if ($type=='dt' && strlen($strDate)!=19) {return $strDate;/*'errConvert to DateTime.Rong char number'*/;}
 if ($type=='d'  && strlen($strDate)!=10) {return $strDate;/*'errConvert to Date.Rong char number'*/;}

 $defDateTime='';$time='';$day='';$month='';$year='';
 
 if ($direction=='rus2mysql') {//преобразование для передачи в тип данных DateTime MySQL
 		$charSeparater="-";	//разделитель в дате

		$day=substr($strDate,0,2);
	  	$month=substr($strDate,3,2);
	   	$year=substr($strDate,6,4);
	   	
	   	
		if ($type=='dt') {$time=' '.substr($strDate,11);}
	   	$defDateTime=$year.$charSeparater.$month.$charSeparater.$day.$time;
	   	return $defDateTime;
   	}
  else 	if ($direction=='mysql2rus') // выдираем из mysql в рус.представление или аналог date("d.m.Y H:i:s",strtotime($strDate))
  		{
 		$charSeparater=".";	//разделитель в дате

		$day=substr($strDate,8,2);
	  	$month=substr($strDate,5,2);
	   	$year=substr($strDate,0,4);
	   	
		if ($type=='dt') {$time=' '.substr($strDate,11);}
	   	$defDateTime=$day.$charSeparater.$month.$charSeparater.$year.$time;
	   	return $defDateTime;
			
		}
  	 	else {return 'rong Direction';}   	
} 
/* печать иконки картинки или типа файла (если не картинка)
считать картинкой только .jpg, .jpeg, .png, .gif
 $imgName - имя файла
 $path - путь от родительской папки портала
 $sm_path - относительный путь иконки картинки 
*/
function printThrumb($imgName,$path,$sm_path)
{
$imgHTML='';

if ($imgName!='') {
	$imgHTML.='<a href="'.$path.'/'.$imgName.'" target="_blank" title="открыть файл в новом окне">';
	
	if (preg_match('/(\.jpg|\.jpeg|\.png|\.gif)$/i',$imgName) && file_exists($path.$sm_path.'/'.$imgName))
	{
	$imgHTML.='<img align=right src="'.$path.$sm_path.'/'.$imgName.'"  border=0 height=50 onMouseOver="javascript:this.height=\'120\';" onMouseOut="javascript:this.height=\'50\';">';
	}
	else $imgHTML.=file_type_img($imgName, true);
	
	$imgHTML.='</a>';
}
return $imgHTML;
}

function file_type_img($nameFile, $strMode=false, $fName_out=false)
//	$strMode - выводить строкой, иначе через echo;
//	$fName_out - выводить справа от типа файла наименование файла
{
if ($nameFile!="") {	
	$str="";
	$out_str="";
	$def_val='other_file.gif';
	$img_path='images/design/file_types/';
	//echo $str;
	
	$patterns=array(
		"/.*\.rar|.*\.zip/i",
		"/.*\.doc.?|.*\.rtf/i",
		"/.*\.xls.?/i",
		"/.*\.pdf/i",
		"/.*\.txt/i",	
		"/.*\.exe/i",
		"/.*\.htm|.*\.html|.*\.mht/i",
		"/.*\.jpg$|.*\.jpeg$|.*\.tif$|.*\.bmp$|.*\.png$/i",
		"/.*\.ppt.?|.*\.pps/i",
		"/.*\.chm|.*\.hlp/i",
		"/.*\.msi|.*\.msp/i"
	);
	
	$replacements=array(
		"winrar_file.gif",
		"word_file.gif",
		"excel_file.gif",
		"pdf_file.gif",
		"txt_file.gif",
		"app_file.gif",
		"web_file.gif",
		"img_file.gif",
		"ppt_file.gif",
		"help_file.gif",
		"install_file.gif"
	);
	
	$str=preg_replace($patterns, $replacements, $nameFile);
	
	$out_str.='<img src="'.$img_path;	 
	if ($str==$nameFile) {$out_str.=$def_val;}	//вывод значения по умолчанию
	else {$out_str.=$str;}
	$out_str.='" border=0>';
	if ($fName_out) $out_str.='&nbsp;'.$nameFile;
	
	if ($strMode) return $out_str;
	else echo $out_str;
	}
} 

function test_file($file_name,$file_size)
{
global $upload_max_filesize;
// $file_size  размер файла в байтах

$test_status=true;
$maxFileSize=$upload_max_filesize;//2.0;	//MB
if (ereg(".js$|.php$|.asp$|.exe$|.htaccess$",$file_name)) 
	{$test_status=false;
	$path_parts = pathinfo($file_name);
	 echo '<div class="warning"> файл не может иметь указанное расширение (<font size="+1">'.$path_parts['extension'].'</font>). 
	 Если Вы все же хотите отправить такой файл - поместите его в архив. </div><br>';	} 

$size_mb=round($file_size/1024/1024,2);	//размер файла в KB

if ($size_mb>$maxFileSize) {$test_status=false; echo'<div class="warning"> размер Вашего файла: <u>'.$size_mb.'</u> Мб, что  превышает допустимые <u>'.$maxFileSize.'</u> МБ </div><br>';}
return $test_status; 
} 

function f_ri($val2replace)
{
//замена запрещенных символов в запросах SQL при вводе/обновлении данных (Update/Insert)

$val2replace=str_replace("'",'"',$val2replace);
$val2replace=str_replace("<|>"," ",$val2replace);
//$val2replace=mysql_real_escape_string($val2replace);
return $val2replace; 

}
function f_ro($val2replace)	//function replace out
{
//замена запрещенных символов в запросах SQL при выводе на страницу (Select)

$val2replace=@str_replace("'",'"',$val2replace);
$val2replace=@str_replace('"','&quot;',$val2replace);
$val2replace=@str_replace("<|>"," ",$val2replace);
return $val2replace; 

}

function arr_replace(&$arr_val,$arr_key)
{
$arr_val=f_ro($arr_val); 
} 

function msg_replace($s)	//замена при выводе сообщений на экран для форматирования
{//для редактора ...
        $s=str_replace ("\r\n","<br>",$s);

        $s=str_replace ("[url]","<a href='http://",$s);
        $s=str_replace ("[/url]","' target='_blank' style='font-weight:normal; text-decoration:underline;'>Ресурс</a>",$s);

        $s=str_replace ("[quote]","<u>Цитата</u><br><span style='color:grey;background:white;'>",$s);
        $s=str_replace ("[/quote]","</span><br>",$s);

        $s=str_replace ("[b]","<b>",$s);
        $s=str_replace ("[/b]","</b>",$s);		        

        $s=str_replace ("[u]","<u>",$s);
        $s=str_replace ("[/u]","</u>",$s);		        
        
        $s=str_replace ("[i]","<i>",$s);
        $s=str_replace ("[/i]","</i>",$s);		        
 		
		return $s;  
} 

function persons_select($queryString)
{//выпадающий список с выбором преподавателя и переходом на выбранного
 //в параметре задаем параметр перехода,kadri_id всегда идет последним !!!!
 global $view_all_mode;
 $tab_name='kadri';
 
 $item_id=preg_replace("/(kadri_id|id_kadri|user_id|id_user)=(\d+|)(.*)/","\\2",$queryString);
 //echo 'item_id='.$item_id;
 
 if (strstr($queryString,"user"))	 {$tab_name='users';}
 if (!strstr($queryString,"not_redirect"))	 {
 //echo $tab_name.'!!!!'; //$queryString='type_money=2&kadri_id';
 //echo '<h2>select</h2>';
 	//echo '!!'.$_SESSION['all_user_select'].'  !!';
	 echo'Преподаватель:<select name="teach_name" style="width:300;" 
		onChange="javascript:window.location.href=\'?'.$queryString.'=\'+this.options[this.selectedIndex].value;"> ';
			}
		
	else {	 echo'<select name="teach_name" style="width:300;"> ';}	
	
	
		if ($view_all_mode===true) {
		 echo '<option value="0">...выберите преподавателя ...</option>';
		 if ($tab_name!='users') {
		    $query='select k.id,concat(k.fio," (",kadri_role(k.id,","),")") as fio
		 	from kadri k order by k.fio';
         } else {
             // $query='select id,fio from '.$tab_name.' where status="преподаватель" order by fio';
             $query='select id,fio from '.$tab_name.' order by fio';
         }
		 
		 
		 }
		else {	//для преподавателя только просмотр своих публикаций
		 if ($tab_name!='users') {
		 $query='select id,fio from '.$tab_name.' where id="'.$_SESSION['kadri_id'].'" order by fio ';}
		 else {$query='select id,fio from '.$tab_name.' where id="'.$_SESSION['id'].'" order by fio ';}
		 //echo $query; 
		 }
		
		//if (strstr($queryString,"not_redirect")) {$query='select id,fio from kadri order by fio';}
		
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
			 
			 if (isset($_GET['kadri_id']) && trim($_GET['kadri_id'])==$a['id'] && $select_val=='') {$select_val=' selected';} 
			 if (isset($_GET['id_kadri']) && trim($_GET['id_kadri'])==$a['id'] && $select_val=='') {$select_val=' selected';} 
			 
			 if (isset($_GET['user_id']) && trim($_GET['user_id'])==$a['id'] && $select_val=='') {$select_val=' selected';} 
			 if (isset($_GET['id_user']) && trim($_GET['id_user'])==$a['id'] && $select_val=='') {$select_val=' selected';} 
			 
			 if ($item_id==$a['id'] && $select_val=='') {$select_val=' selected';} 
			echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['fio'].'</option>';
			}

echo '</select>';
} 

function persons_select_chart($select_name,$queryString,$kadri_id,$width_select)
{//выпадающий список с выбором преподавателя без перехода
 //в параметре задаем параметр перехода,kadri_id всегда идет последним !!!!
 $tab_name='kadri';
 $msg_text='...выберите преподавателя...';
 if (strstr($queryString,"user"))	 {$tab_name='users';$msg_text='...выберите пользователя...';}

 if ($width_select=='') {$width_select='160';}
 
    echo'<select name="'.$select_name.'" style="width:'.$width_select.';"> ';	
	echo '<option value="0">'.$msg_text.'</option>';
	
	$query='select id,fio from '.$tab_name.' order by fio';
	//echo $query;	
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
			 if (isset($kadri_id) && trim($kadri_id)==$a['id']) {$select_val=' selected';} 
			echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['fio'].'</option>';
			}

echo '</select>';
// echo $query;
} 

function show_footer()
{//отражается в нижней части основной колонки, используется в админ-й части
	global $portal_path;
	if (!isset($portal_path)) $portal_path='';	
	?>
	<p><a href="#top">Наверх</a></p>
	<p><a href="<?php echo $portal_path;?>p_administration.php">К списку задач.</a><p>

	<!--left column end-->
	</td></tr></table>
	
	<!--main template table end-->
	</td><td></td>
	</tr>	 
	</table>
	<?php
}

$rus2lat=array(
array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'),
array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф', 'х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я'),
array('A','B','V','G','D','E','E','G','Z','I','I','K','L','M','N','O','P','R','S','T','U','F','H','C','Ch','Sh','Sh','_','_','_','E','Ju','Ja'),
array('a','b','v','g','d','e','e','g','z','i','i','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sh','_','_','_','e','ju','ja')
);

$transRUS=array (" ","#","$","%","&","'","(",")","*","+",",","-","","/","\"","\\","{","}","№","!","","","","",
":",";","<","=",">","?","@","[","]");

function Upper_word($word)	//сборка слова с учетом первой заглавной и спец.символов
 {
  global $transRUS;	//спец.символы для исключения
  
  $word=trim($word);
  $i=0;$key=false;
  while ($key=array_search(substr($word,$i,1),$transRUS) && $i<strlen($word)	)
  {    $i++;		}  
  
  if ($key>=0) {$word=substr($word,0,$i).strtoupper(substr($word,$i,1)).substr($word,$i+1);	}
  else {	$word=strtoupper(substr($word,0,1)).substr($word,1);	   }
  
  return $word;
 }
function Trans_file_word($word1)		//перевод слова в латиниwe с учетом спец.символов
 {
  //global $transRUS,$orig_bigRUS,$orig_smallRUS,$transENG,$orig_bigENG;
  global $transRUS,$rus2lat;
  if ($word1!='')
  {
  	   
   	//выделяем расширение по точке с конца имени файла
   	/*$ext_pos=strrpos($word1,'.');
   	
   	if (ext_pos>0 && ext_pos<strlen($word1))//если позиция расширения верна
	{
		ext_str=substr($word1,ext_pos+1);
	   	name_str=substr($word1,0,ext_pos);
   	}*/
	$word1=str_replace($transRUS,'_',$word1);	// замена запретных символов на "-"
    $word1=str_replace($rus2lat[0],$rus2lat[2],$word1);		//перевод в латиницу в верхнем регистре
	$word1=str_replace($rus2lat[1],$rus2lat[3],$word1);		//перевод в латиницу в нижнем регистре
	$word1=preg_replace("/[^\w\.]/","",$word1);
$word1=strtolower(trim($word1));
   }
  return $word1;
 }
 
function img_resize($src, $dest, $width, $height, $rgb=0xFFFFFF, $quality=100)
{//в фото преподавателя анкеты, биографии
  /***********************************************************************************
Функция img_resize(): генерация thumbnails
Параметры:
  $src             - имя исходного файла
  $dest            - имя генерируемого файла
  $width, $height  - ширина и высота генерируемого изображения, в пикселях
Необязательные параметры:
  $rgb             - цвет фона, по умолчанию - белый
  $quality         - качество генерируемого JPEG, по умолчанию - максимальное (100)
***********************************************************************************/
if (!file_exists($src)) return false;

  $size = getimagesize($src);
  if ($size === false) return false;

  // Определяем исходный формат по MIME-информации, предоставленной
  // функцией getimagesize, и выбираем соответствующую формату
  // imagecreatefrom-функцию.
  $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
  $icfunc = "imagecreatefrom" . $format;
  if (!function_exists($icfunc)) return false;
  
  if ($width!=0 && $height!=0) {$x_ratio = $width / $size[0];  $y_ratio = $height / $size[1];}
  if ($width==0) {$y_ratio = $height / $size[1];$x_ratio=$y_ratio;$width=$size[0]*$x_ratio;}
  if ($height==0) {$x_ratio = $width / $size[0];$y_ratio=$x_ratio;$height=$size[1]*$x_ratio;}	
	
  $ratio       = min($x_ratio, $y_ratio);
  $use_x_ratio = ($x_ratio == $ratio);

  $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
  $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
  $new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
  $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

  $isrc = $icfunc($src);
  $idest = imagecreatetruecolor($width, $height);

  imagefill($idest, 0, 0, $rgb);
  imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, 
    $new_width, $new_height, $size[0], $size[1]);

  imagejpeg($idest, $dest, $quality);

  imagedestroy($isrc);
  imagedestroy($idest);

  return true;

}
//восстановление авторизации при наличии
if ($saveLogin && isset($_COOKIE[$saveLogin_cook]) && $_COOKIE[$saveLogin_cook]!='' && (!isset($_SESSION['auth']) || $_SESSION['auth']!=1) )
    {	
	
	if (isset($curpage) && $curpage!='' && $curpage!='p_administration.php' && $curpage!='p_denied_access.php') //перейти на указанный адрес
	{			
		header('location:'.$files_path.'p_administration.php?url='.$curpage);
		echo('ошибка в переходе на авторизацию...');
	}
    }

if (isset($_GET['save']) && isset($_GET['attach']) && $_GET['attach']=='doc')
{
      header('Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0');
      header('Pragma: no-cache');
      header('Content-Type: application/msword; charset=windows-1251; format=attachment;');
      header('Content-Disposition: attachment;');	// filename=.doc
}
if (isset($_GET['print'])) {
	$bodyOnLoad=' onLoad="setTimeout(\'window.print()\',2000);"';
	echo '<link rel="stylesheet" type="text/css" media="print" href="'.$server_name.$root_folder.'css/print.css" />';
	}
// ограничения по персональным данным
$hide_person_data_rule=$hide_personal_data && (!isset($_SESSION['auth']) || $_SESSION['auth']!=1);
?>