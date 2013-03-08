<?php
/*  краткое описание задачи
   для пользователя необходимы права Super,REPLICATION SLAVE
 * 
цель задачи- помощь при синхронизации внешнего зеркала портала и анализ действий пользователей
 *
инстумент- операции по работе с журналом бинарных логов (Binary Logs) на сервере
 * 
возможности:
 * - получение списка действий по BL (Binary Log Events)
 * - фильтрация записей по маске включения, исключения;
 * - поддержка регулярных выражений;
 * - возможность обработки сразу всех BL;
 * - интеграция в консоль Портала АСУ.
 * 
особенности:
 * т.к. записи BL могут содержать закрытые данные, доступ к задаче должен быть строго рекламентирован
  
 */

//error_reporting(E_ALL);
$files_path='';
$head_title='Бинарные логи. Список действий.';

include ($files_path.'authorisation.php');
include ($files_path.'master_page_short.php');

$sql_login='binlog';
$sql_passw='9a3Peyd5ZVKdTYdM';

//соединение для пользователя с правами бинарных логов
    if(!mysql_connect($sql_host,$sql_login,$sql_passw))
     {
	  echo '<div class=text>Не могу соединиться с сервером Базы Данных с правами чтения бинарных логов. <font color=red>Дальнейшая работа невозможна.</font></div>';
      exit();
     }

?>
<style>
    .form_el{padding:10px; height:20px;}    
</style>
<script>
    function test_from()
    {
        var mask;
        var logFile;
        var form;

        mask=document.getElementById('exept_mask');
        logFile=document.getElementById('binlog_name');
        form=document.getElementById('form1');

        if (mask!=null && logFile!=null && form!=null)
            {// проверка на заполнение данных формы для снижения нагрузки на сервер
                if (mask.value=='' && logFile.value=='')
                    {if (confirm('Не заполнены: маска ввода и имя лог-файла.\nЭто может увеличить время обработки запроса.\n\nПродолжить ?')) form.submit();}
                else form.submit();
            }        
    }
</script>
<?php
//включение ведения бинарных логов в течение текущего сеанса, если при конфигурирования mysql-сервера опция задана не была (log bin=OFF)
//mysql_query('SET SQL_LOG_BIN =1');  //для отключения  =0

//получение значения переменной, по аналогии с set globals on
function getRequestParam($paramName,$typeR)
{// имя параметра, тип запроса
    
    $retVal='';     //возвращаемое значение

    if ($typeR=='get') $typeR='get';
    else $typeR='post';  //тип по умолчанию post

    if ($paramName!='')
    {
        if ($typeR=='get' && isset($_GET[$paramName])) {$retVal=$_GET[$paramName];}
        if ($typeR=='post' && isset($_POST[$paramName])) {$retVal=$_POST[$paramName];}        
    }    
    return $retVal;
}
function getBinLogEventsList($query_events,$exept_mask,$type_mask,$regex_mask)
{
    global $rows_total; //число рядов
    global $rows_cnt;

    $res=mysql_query($query_events);
    $list_str='';
    $rows_cnt_cur=0;
 
    while ($a=mysql_fetch_array($res)) 	{
        
	if ($exept_mask!='') {  //если задан поиск по маске
            if ($regex_mask=='on') {    //поиск с учетом регулярных выражений

                if ($type_mask=='on' && preg_match($exept_mask,$a['Info']))
                    {$list_str.=$a['Info'].";\n\n";$rows_cnt_cur+=1;}
                else if ($type_mask=='' && !preg_match($exept_mask,$a['Info']))
                    {$list_str.=$a['Info'].";\n\n";$rows_cnt_cur+=1;}
            }
            else {  //поиск без регулярных выражений, быстрый
                if ($type_mask=='on' && stristr($a['Info'],$exept_mask))
                    {$list_str.=$a['Info'].";\n\n";$rows_cnt_cur+=1;}
                else if ($type_mask=='' && !stristr($a['Info'],$exept_mask))
                    {$list_str.=$a['Info'].";\n\n";$rows_cnt_cur+=1;}
            }
        }
        else    //поиск без использования маски
            {$list_str.=$a['Info'].";\n\n";$rows_cnt_cur+=1;}
    }
    $rows_total+=mysql_num_rows($res);
    $rows_cnt+=$rows_cnt_cur;
    return $list_str;
}
//--------------------------------------------------------------------------

$query_logs='SHOW BINARY LOGS';
$query_events='SHOW BINLOG EVENTS';

$binlog_name='';   //имя лог файла
$exept_mask=''; //маска исключения
$type_mask='';  //тип маски
$go='';
$regex_mask=''; //маска как регулярное выражение

$binlog_name=getRequestParam('binlog_name','get');
$exept_mask=getRequestParam('exept_mask','get');
$type_mask=getRequestParam('type_mask','get');
$regex_mask=getRequestParam('regex_mask','get');

$go=getRequestParam('go','get');

    $res=mysql_query($query_logs);
    if (mysql_num_rows($res)==0) {?>
        <p class=warning>Бинарные логи не найдены. Возможно они отключены при настройке сервера.</p>
        <p class=success>Для включения Бинарных-логов, Вы так же можете использовать запрос
            'SET SQL_LOG_BIN =1' в файле подключения к MySQL-серверу.<br>
            При таком включении бинарного логирования- операция будет работать в текущем сеансе.</p>
        <?php }
    else {
?>
<p>
<form method="get" id=form1 name=form1 class=text style="border: solid black 1px; padding:10px;width:95%">
    <select id=binlog_name name=binlog_name >
    <?php
    //формируем список бинарный файлов с указанием размера

    $list_str='';

    while ($a=mysql_fetch_array($res)) 	{
        $selected='';
        if ($a['Log_name']==$binlog_name) $selected='selected';
        $list_str='<option value="'.$a['Log_name'].'" '.$selected.'>'.$a['Log_name'].' ('.$a['File_size'].' Б)</option>'."\n\t".$list_str;
        }
    $list_str='<option value="">выберите из списка...('.mysql_num_rows($res).')</option>'.$list_str;
    echo   $list_str;
    ?>
    </select>
    Выберите бинарный лог-файл или оставьте пустым для анализа всех файлов &nbsp;<br>

    <input type=text id=exept_mask name=exept_mask size="40" maxlength="45" value="<?php echo $exept_mask;?>"> <a href="#help1" title="строки будут исключены по маске при формирование общего лог-дампа">исключаемое выражение-маску</a> из поиска <br>
    <label>
    <input class=form_el id=type_mask name=type_mask type=checkbox <?php echo echoIf($type_mask=='on', 'checked', ''); ?> > использовать маску для поиска вхождения (инверсия маски), например <b>update</b> <br>
    </label>
    <label>
    <input class=form_el id=regex_mask name=regex_mask type=checkbox <?php echo echoIf($regex_mask=='on', 'checked', ''); ?> > использовать маску как регулярное выражение, например <b>/phpmyadmin|INSERT_ID=/</b> <br>
    </label>
    <input type=hidden value=1 id=go name=go>
    <input class=form_el type="button" onclick="test_from();" value="ок" style="width:100px;"> &nbsp;
    <input class=form_el type="button" value="очистить" onclick="window.location.href='?'">
</form>
<?php
if ($binlog_name!='')   $query_events.=" IN '$binlog_name'";
else {  //формируем массив бинарных логов для глобального поиска
    $binlog_name_arr=array();
    $binlog_name_arr=getRowSqlVar($query_logs);
    //print_r($binlog_name_arr);
}
if ($go==1) //форма отправлена, начинаем формирование бинарного журнала с учетом параметров формы
{
$rows_total=0;
$rows_cnt=0;
$list_str='';

    if (isset($binlog_name_arr) && count($binlog_name_arr)>0 )  //формирование цикла по все бинарным журналам
    {
        for ($i=0;$i<count($binlog_name_arr);$i++)
        {            
            $list_str.='--  '.$binlog_name_arr[$i]['Log_name'].' лог-файл'."\n\n\n\n".
                getBinLogEventsList($query_events." IN '".addcslashes($binlog_name_arr[$i]['Log_name'],'\'\"')."'",$exept_mask,$type_mask,$regex_mask);
        }
    }
    else    //формирование по 1 выбранному бинарному-логу
        $list_str=getBinLogEventsList($query_events,$exept_mask,$type_mask,$regex_mask);

?>
<div>Был произведен поиск по <b><?php echo echoIf($binlog_name!='', $binlog_name. ' лог-файлу', 'всем лог-файлам') ?></b> <br>
    результат поиска (записей: <b><?php echo $rows_cnt;  ?> из <?php echo $rows_total;?></b>):
    <div class=text style="border: solid black 1px; padding:10px;width:95%"><!--textarea rows="60" cols="120"--> <?php echo str_ireplace("\n\n", "<br>",$list_str); ?> <!--/textarea--> </div>
</div>
<?php
}
    }
?>
</p>