{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление образовательных технологий</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/contentTechnology/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentTechnology/common.right.tpl"}
{/block}