{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление темы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/contentTopic/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentTopic/common.right.tpl"}
{/block}