{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование раздела дисциплины</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/contentSections/form.tpl"}

    <ul class="nav nav-tabs">
        <li class="active"><a href="#loads" data-toggle="tab">Нагрузка</a></li>
        <li><a href="#controltypes" data-toggle="tab">Виды контроля</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="loads">
            {include file="_corriculum/_workplan/contentSections/subform.load.tpl"}
        </div>
        <div class="tab-pane" id="controltypes">
            {CHtml::activeComponent("workplantypescontrol.php?id={$object->getId()}", $object)}
        </div>
    </div>
    
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentSections/common.right.tpl"}
{/block}