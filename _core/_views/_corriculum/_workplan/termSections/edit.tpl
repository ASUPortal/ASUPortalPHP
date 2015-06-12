{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование раздела</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/termSections/form.tpl"}

    {CHtml::activeComponent("workplantermsectionloads.php?section_id={$object->getId()}", $object)}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/termSections/common.right.tpl"}
{/block}