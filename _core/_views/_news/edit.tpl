{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование новости</h2>
    {CHtml::helpForCurrentPage()}

    {include file="_news/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_news/edit.right.tpl"}
{/block}