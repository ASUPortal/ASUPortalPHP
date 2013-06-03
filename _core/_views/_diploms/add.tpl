{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление дипломной темы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_diploms/form.tpl"}
{/block}

{block name="asu_right"}
{include file="_diploms/add.right.tpl"}
{/block}