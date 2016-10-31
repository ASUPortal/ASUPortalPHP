{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование вида промежуточного контроля</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/mediumControl/form.tpl"}

{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/mediumControl/common.right.tpl"}
{/block}