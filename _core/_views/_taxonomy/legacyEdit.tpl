{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование термина таксономии</h2>
    {CHtml::helpForCurrentPage()}
    
    {if $taxonomy->getTableName() == "spravochnik_uch_rab"}
        {include file="_taxonomy/form.legacy.term.workTypes.tpl"}
    {else}
        {include file="_taxonomy/form.legacy.term.default.tpl"}
    {/if}
    
{/block}

{block name="asu_right"}
	{include file="_taxonomy/common.right.tpl"}
{/block}