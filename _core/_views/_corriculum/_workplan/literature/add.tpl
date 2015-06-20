{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление литературы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/literature/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/literature/common.right.tpl"}
{/block}