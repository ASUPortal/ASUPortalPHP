{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование папки</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_documents/_folder/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_documents/_folder/common.right.tpl"}
{/block}