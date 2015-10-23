{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование вида итогового контроля</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/finalControl/form.tpl"}

{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/finalControl/common.right.tpl"}
{/block}