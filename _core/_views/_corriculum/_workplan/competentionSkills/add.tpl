{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление умения по компетенции рабочей программы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/competentionSkills/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/competentionSkills/common.right.tpl"}
{/block}