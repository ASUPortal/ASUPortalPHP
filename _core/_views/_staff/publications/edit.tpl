{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование публикации</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_staff/publications/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_staff/publications/common.right.tpl"}
{/block}