{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование семестра</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_disciplineSections/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_disciplineSections/common.right.tpl"}
{/block}