{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование семестра</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/technologyTerms/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/technologyTerms/common.right.tpl"}
{/block}