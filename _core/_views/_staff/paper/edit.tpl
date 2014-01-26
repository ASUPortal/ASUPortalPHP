{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование диссертации</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_staff/paper/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_staff/paper/common.right.tpl"}
{/block}