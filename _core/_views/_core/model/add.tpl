{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление модели</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_core/model/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_core/model/add.right.tpl"}
{/block}