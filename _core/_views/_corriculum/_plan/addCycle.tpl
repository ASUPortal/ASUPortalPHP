{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Добавление цикла в учебный план</h2>
{CHtml::helpForCurrentPage()}
    {include file="_corriculum/_plan/form.cycle.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_plan/addCycle.right.tpl"}
{/block}