{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление класса отчета</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_reports/report/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_reports/report/common.right.tpl"}
{/block}