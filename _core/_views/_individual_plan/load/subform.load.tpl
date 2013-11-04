<ul class="nav nav-pills">
    <li class="active"><a href="#work_{$load->getId()}_1" data-toggle="tab">Учебная</a></li>
    <li>
        <a href="#work_{$load->getId()}_2" data-toggle="tab">
            Учебно- и организационно-методическая
            <i class="icon-plus-sign" onclick="window.location.href='work.php?action=add&id={$load->getId()}&type=2'"></i>
        </a>
    </li>
    <li>
        <a href="#work_{$load->getId()}_3" data-toggle="tab">
            Научно-методическая и госбюджетная научно-исследовательская
            <i class="icon-plus-sign" onclick="window.location.href='work.php?action=add&id={$load->getId()}&type=3'"></i>
        </a>
    </li>
    <li>
        <a href="#work_{$load->getId()}_4" data-toggle="tab">
            Учебно-воспитательная
            <i class="icon-plus-sign" onclick="window.location.href='work.php?action=add&id={$load->getId()}&type=4'"></i>
        </a>
    </li>
    <li>
        <a href="#work_{$load->getId()}_5" data-toggle="tab">
            Перечень научных и научно-методических работ
            <i class="icon-plus-sign" onclick="window.location.href='work.php?action=add&id={$load->getId()}&type=5'"></i>
        </a>
    </li>
    <li>
        <a href="#work_{$load->getId()}_6" data-toggle="tab">
            Записи об изменениях
            <i class="icon-plus-sign" onclick="window.location.href='work.php?action=add&id={$load->getId()}&type=6'"></i>
        </a>
    </li>
    <li>
        <a href="#work_{$load->getId()}_7" data-toggle="tab">
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
    <div class="tab-pane" id="work_{$load->getId()}_5">
        {include file="_individual_plan/load/subform.work5.tpl"}
    </div>
    <div class="tab-pane" id="work_{$load->getId()}_6">
        {include file="_individual_plan/load/subform.work6.tpl"}
    </div>
    <div class="tab-pane" id="work_{$load->getId()}_7">
        {include file="_individual_plan/load/subform.work7.tpl"}
    </div>
</div>