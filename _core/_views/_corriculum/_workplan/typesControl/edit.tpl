{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование вида контроля</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/typesControl/form.tpl"}
    
    <ul class="nav nav-tabs">
        <li class="active"><a href="#marks" data-toggle="tab">Описание и количество баллов за учебную деятельность</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="marks">
            {CHtml::activeComponent("workplanmarksstudyactivity.php?id={$object->getId()}", $object, ["withoutScripts" => "true"])}
        </div>
    </div>
    
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/typesControl/common.right.tpl"}
{/block}