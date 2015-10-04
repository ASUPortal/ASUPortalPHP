{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление вида контроля</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/typesControl/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/typesControl/common.right.tpl"}
{/block}