{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование cтраницы</h2>
    {CHtml::helpForCurrentPage()}

    {include file="_pages/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_pages/edit.right.tpl"}
{/block}