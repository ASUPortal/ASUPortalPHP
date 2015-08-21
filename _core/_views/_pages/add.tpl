{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление cтраницы</h2>
    {CHtml::helpForCurrentPage()}

    {include file="_pages/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_pages/add.right.tpl"}
{/block}