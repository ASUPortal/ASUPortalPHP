<?php
if (!isset($files_path)) {$files_path='';}

define("NO_RIGHT_COLUMN", true);

include $files_path.'header.php';
if (!isset($_GET['save']) && !isset($_GET['print'])) {
	echo $head;
    //include_once($files_path.'task_menu.php');
} else {
    echo $head1;
}


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