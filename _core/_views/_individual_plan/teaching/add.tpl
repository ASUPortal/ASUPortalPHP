{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление распределения нагрузки по видам работ</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_individual_plan/teaching/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/teaching/add.right.tpl"}
{/block}