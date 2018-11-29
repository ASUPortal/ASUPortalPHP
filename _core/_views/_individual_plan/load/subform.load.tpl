<ul class="nav nav-pills">
    <li class="active">
        <a href="#work_{$load->getId()}_1" data-toggle="tab">
            Учебная
            <i title="Редактировать" class="icon-pencil" onclick="window.open('{$web_root}_modules/_individual_plan/work.php?action=add&id={$load->getId()}&type=1&year={$load->year->id}')"></i>
            {*
            <i title="Скопировать" class="icon-share" onclick="window.open('{$web_root}_modules/_individual_plan/load.php?action=selectYearLoadWorks&load_id={$load->getId()}&type=1')"></i>
            *}
        </a>
    </li>
    <li>
        <a href="#work_{$load->getId()}_2" data-toggle="tab">
            {*
            Учебная работа
            *}
            Учебно- и организационно-методическая
            <i title="Добавить" class="icon-plus-sign" onclick="window.open('{$web_root}_modules/_individual_plan/work.php?action=add&id={$load->getId()}&type=2&year={$load->year->id}')"></i>
            <i title="Скопировать" class="icon-share" onclick="window.open('{$web_root}_modules/_individual_plan/load.php?action=selectYearLoadWorks&load_id={$load->getId()}&type=2')"></i>
        </a>
    </li>
    <li>
        <a href="#work_{$load->getId()}_3" data-toggle="tab">
            {*
            Научно-исследовательская
            *}
            Научно-методическая и госбюджетная научно-исследовательская
            <i title="Добавить" class="icon-plus-sign" onclick="window.open('{$web_root}_modules/_individual_plan/work.php?action=add&id={$load->getId()}&type=3&year={$load->year->id}')"></i>
            <i title="Скопировать" class="icon-share" onclick="window.open('{$web_root}_modules/_individual_plan/load.php?action=selectYearLoadWorks&load_id={$load->getId()}&type=3')"></i>
        </a>
    </li>
    <li>
        <a href="#work_{$load->getId()}_4" data-toggle="tab">
            {*
            Учебно-методическая, научно-методическая и воспитательная
            *}
            Учебно-воспитательная
            <i title="Добавить" class="icon-plus-sign" onclick="window.open('{$web_root}_modules/_individual_plan/work.php?action=add&id={$load->getId()}&type=4&year={$load->year->id}')"></i>
            <i title="Скопировать" class="icon-share" onclick="window.open('{$web_root}_modules/_individual_plan/load.php?action=selectYearLoadWorks&load_id={$load->getId()}&type=4')"></i>
        </a>
    </li>
    
    {*
    <li>
        <a href="#work_{$load->getId()}_5" data-toggle="tab">
            Организационно-методическая работа
            <i title="Добавить" class="icon-plus-sign" onclick="window.open('{$web_root}_modules/_individual_plan/work.php?action=add&id={$load->getId()}&type=7&year={$load->year->id}')"></i>
            <i title="Скопировать" class="icon-share" onclick="window.open('{$web_root}_modules/_individual_plan/load.php?action=selectYearLoadWorks&load_id={$load->getId()}&type=7')"></i>
        </a>
    </li>
    <li>
        <a href="#work_{$load->getId()}_6" data-toggle="tab">
            Подготовка кадров высшей квалификации в аспирантуре
            <i title="Добавить" class="icon-plus-sign" onclick="window.open('{$web_root}_modules/_individual_plan/work.php?action=add&id={$load->getId()}&type=8&year={$load->year->id}')"></i>
            <i title="Скопировать" class="icon-share" onclick="window.open('{$web_root}_modules/_individual_plan/load.php?action=selectYearLoadWorks&load_id={$load->getId()}&type=8')"></i>
        </a>
    </li>
    *}
    
    <li>
        <a href="#work_{$load->getId()}_7" data-toggle="tab">
            Перечень научных и научно-методических работ
            <i title="Добавить" class="icon-plus-sign" onclick="window.open('{$web_root}_modules/_individual_plan/work.php?action=add&id={$load->getId()}&type=5&year={$load->year->id}')"></i>
            <i title="Скопировать" class="icon-share" onclick="window.open('{$web_root}_modules/_individual_plan/load.php?action=selectYearLoadWorks&load_id={$load->getId()}&type=5')"></i>
        </a>
    </li>
    <li>
        <a href="#work_{$load->getId()}_8" data-toggle="tab">
            Записи об изменениях
            <i title="Добавить" class="icon-plus-sign" onclick="window.open('{$web_root}_modules/_individual_plan/work.php?action=add&id={$load->getId()}&type=6&year={$load->year->id}')"></i>
            <i title="Скопировать" class="icon-share" onclick="window.open('{$web_root}_modules/_individual_plan/load.php?action=selectYearLoadWorks&load_id={$load->getId()}&type=6')"></i>
        </a>
    </li>
    <li>
        <a href="#work_{$load->getId()}_9" data-toggle="tab">
            Заключение
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="active tab-pane" id="work_{$load->getId()}_1">
        {include file="_individual_plan/load/subform.work1.tpl"}
    </div>
    <div class="tab-pane" id="work_{$load->getId()}_2">
        {include file="_individual_plan/load/subform.work2.tpl"}
    </div>
    <div class="tab-pane" id="work_{$load->getId()}_3">
        {include file="_individual_plan/load/subform.work3.tpl"}
    </div>
    <div class="tab-pane" id="work_{$load->getId()}_4">
        {include file="_individual_plan/load/subform.work4.tpl"}
    </div>
    
    {*
    <div class="tab-pane" id="work_{$load->getId()}_5">
        {include file="_individual_plan/load/subform.work8.tpl"}
    </div>
    <div class="tab-pane" id="work_{$load->getId()}_6">
        {include file="_individual_plan/load/subform.work9.tpl"}
    </div>
    *}
    
    <div class="tab-pane" id="work_{$load->getId()}_7">
        {include file="_individual_plan/load/subform.work5.tpl"}
    </div>
        <div class="tab-pane" id="work_{$load->getId()}_8">
        {include file="_individual_plan/load/subform.work6.tpl"}
    </div>
    <div class="tab-pane" id="work_{$load->getId()}_9">
        {include file="_individual_plan/load/subform.work7.tpl"}
    </div>
</div>