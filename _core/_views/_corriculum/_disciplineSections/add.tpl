{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление семестра</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_disciplineSections/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_disciplineSections/common.right.tpl"}
{/block}