{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование вида занятия</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/technologyTermType/form.tpl"}

    {CHtml::activeComponent("workplantechnologytermloads.php?type_id={$object->getId()}", $object)}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/technologyTermType/common.right.tpl"}
{/block}