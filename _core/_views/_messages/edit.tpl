{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование сообщения</h2>
    {CHtml::helpForCurrentPage()}

    {include file="_messages/subform.new.tpl"}
{/block}

{block name="asu_right"}
    {include file="_messages/edit.right.tpl"}
{/block}