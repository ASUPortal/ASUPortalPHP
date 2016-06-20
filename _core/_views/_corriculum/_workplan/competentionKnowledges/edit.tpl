{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование знания по компетенции рабочей программы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/competentionKnowledges/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/competentionKnowledges/common.right.tpl"}
{/block}