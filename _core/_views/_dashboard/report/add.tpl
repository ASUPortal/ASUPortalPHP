{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление отчета для рабочего стола</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_dashboard/report/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_dashboard/report/common.right.tpl"}
{/block}