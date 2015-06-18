{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление нагрузку</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/technologyTermTypeLoads/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/technologyTermTypeLoads/common.right.tpl"}
{/block}