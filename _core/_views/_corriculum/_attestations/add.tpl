{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление итоговой аттестации</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_attestations/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_attestations/common.right.tpl"}
{/block}