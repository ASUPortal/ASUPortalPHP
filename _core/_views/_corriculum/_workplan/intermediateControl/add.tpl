{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление вида промежуточного контроля</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/intermediateControl/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/intermediateControl/common.right.tpl"}
{/block}