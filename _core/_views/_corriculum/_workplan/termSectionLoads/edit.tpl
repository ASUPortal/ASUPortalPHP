{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование вида нагрузки</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/termSectionLoads/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/termSectionLoads/common.right.tpl"}
{/block}