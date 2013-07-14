{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование перевода</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_core/translation/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_core/translation/add.right.tpl"}
{/block}