{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление модуля</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/contentModules/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentModules/common.right.tpl"}
{/block}