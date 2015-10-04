{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление вида итогового контроля</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/contentFinalControl/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentFinalControl/common.right.tpl"}
{/block}