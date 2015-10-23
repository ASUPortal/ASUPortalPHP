{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование контролируемого раздела</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/fundMarkTypes/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/fundMarkTypes/common.right.tpl"}
{/block}