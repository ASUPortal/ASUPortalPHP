{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление владения по компетенции рабочей программы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/competentionExperiences/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/competentionExperiences/common.right.tpl"}
{/block}