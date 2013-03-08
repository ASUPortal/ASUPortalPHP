<?php
if (!isset($files_path)) {$files_path='';}

include $files_path.'header.php';
if (!isset($_GET['save']) && !isset($_GET['print'])) 
    {				 
	echo $head;
	echo '<span class="notinfo">';
        include_once($files_path.'task_menu.php');
        echo '</span>';
} else {echo $head1;}


//проверка ограничения на отражения записей из таблиц Сотрудники, Студенты с учетом кода скрипта файла задачи
if ($hide_student_tasks || $hide_kadri_tasks) {
    $hide_task=getRowSqlVar('select kadri_in_task, students_in_task from tasks where url like "%'.$curpage.'%"');
    if ($hide_kadri_tasks && $hide_student_tasks &&
        ($hide_task[0]['kadri_in_task']>0 || $hide_task[0]['students_in_task']>0) )
                die($hide_kadri_students_tasks_text);
    else 
        if ($hide_student_tasks && $hide_task[0]['students_in_task']>0) die($hide_kadri_students_tasks_text);
        else
            if ($hide_student_tasks && $hide_task[0]['kadri_in_task']>0) die($hide_kadri_students_tasks_text);
}
/*
//проверка ограничения на отражения записей из таблиц Сотрудники, Студенты с учетом кода скрипта файла задачи
if ($hide_kadri_tasks && $hide_student_tasks && getObjectInTask($curpage,'both')) die($hide_kadri_students_tasks_text);
else 
    if ($hide_student_tasks && getObjectInTask($curpage,'student')) die($hide_kadri_students_tasks_text);
    else
        if ($hide_student_tasks && getObjectInTask($curpage,'kadri')) die($hide_kadri_students_tasks_text);
*/
//-------------------------
/*
if (intval($_SESSION['task_rights_id'])<=0)
    die('<div class=warning>Не определены права к задаче. Работа с текущей задачей прекращена.</div>');

if (intval($_SESSION['kadri_id'])<=0 && $view_all_mode==false )
    die('<div class=warning>Не указана привязка пользователя к сотруднику. Работа с текущей задачей прекращена.</div>');
*/	
//-------------------------
?> 