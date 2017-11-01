{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление ресурса</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_staff/person/resources/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_staff/person/resources/common.right.tpl"}
{/block}