{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование страницы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_staff/person/staffInfo/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_staff/person/staffInfo/common.right.tpl"}
{/block}