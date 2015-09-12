{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление материального обеспечения</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/additionalSupply/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/additionalSupply/common.right.tpl"}
{/block}