{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление нагрузки</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/contentLoads/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentLoads/common.right.tpl"}
{/block}