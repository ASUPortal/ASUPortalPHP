{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление учебного года</h2>
    {CHtml::helpForCurrentPage()}

    {include file="_time_intervals/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_time_intervals/add.right.tpl"}
{/block}