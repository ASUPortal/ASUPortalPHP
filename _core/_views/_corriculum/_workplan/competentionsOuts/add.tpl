{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление компетенции</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/competentionsOuts/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/competentionsOuts/common.right.tpl"}
{/block}