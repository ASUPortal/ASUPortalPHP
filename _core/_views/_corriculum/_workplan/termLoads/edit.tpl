{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование нагрузки</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/termLoads/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/termLoads/common.right.tpl"}
{/block}