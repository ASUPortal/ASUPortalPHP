<?php

    // <abarmin date="04.05.2012">
    // сделаю как надо
    require_once("core.php");

    $controller = new COrgChartController();

    $pg_title='Организационная структура кафедры АСУ';
    include ('master_page_short.php');

    echo '<h4>Организационная структура кафедры АСУ</h4>';
    CStaffManager::buildPersonHierarchy();
    COrgStructureWidget::display(array(
        'items' => CStaffManager::getCachePerson(),
        'itemTemplate' => '_orgStructureItem.html.php',
        'id' => 'asuOrgChartContent',
        'style' => 'display: none;'
    ));
    CHtml::div("asuOrgChart", "", "orgChart");
    //CLog::dump();

    include('footer.php');


//include ('authorisation.php');

?>