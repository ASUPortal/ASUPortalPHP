{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование семестра</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/terms/form.tpl"}

    {CHtml::activeComponent("workplantermloads.php?term_id={$object->getId()}", $object)}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/terms/common.right.tpl"}
{/block}